import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../models/cart_item.dart';
import '../models/product_detail.dart';
import '../providers/cart_provider.dart';
import '../services/api_client.dart';
import '../services/product_service.dart';
import '../theme/app_colors.dart';
import '../utils/currency.dart';
import '../widgets/brand_buttons.dart';
import '../widgets/color_swatch.dart';
import '../widgets/loading_placeholders.dart';
import '../widgets/quantity_selector.dart';
import '../widgets/size_chip.dart';
import 'cart_screen.dart';

class ProductDetailScreen extends StatefulWidget {
  final int productId;
  const ProductDetailScreen({super.key, required this.productId});

  @override
  State<ProductDetailScreen> createState() => _ProductDetailScreenState();
}

class _ProductDetailScreenState extends State<ProductDetailScreen> {
  late final ProductService _service;
  final _pageController = PageController();

  ProductDetail? _product;
  bool _loading = true;
  String? _error;
  int _currentPhoto = 0;
  int _quantity = 1;
  ProductColor? _selectedColor;
  ProductSize? _selectedSize;

  @override
  void initState() {
    super.initState();
    _service = ProductService(context.read<ApiClient>());
    _load();
  }

  @override
  void dispose() {
    _pageController.dispose();
    super.dispose();
  }

  Future<void> _load() async {
    setState(() {
      _loading = true;
      _error = null;
    });
    try {
      final product = await _service.detail(widget.productId);
      if (!mounted) return;
      setState(() {
        _product = product;
        _loading = false;
        _selectedColor =
            product.colors.isNotEmpty ? product.colors.first : null;
        _selectedSize =
            product.sizes.isNotEmpty ? product.sizes.first : null;
      });
    } catch (e) {
      if (!mounted) return;
      setState(() {
        _error = e is ApiException ? e.message : e.toString();
        _loading = false;
      });
    }
  }

  void _addToCart() {
    if (_product == null) return;
    if (_selectedColor == null || _selectedSize == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Pilih warna & ukuran terlebih dahulu.')),
      );
      return;
    }
    context.read<CartProvider>().add(
          CartItem.fromProduct(
            product: _product!,
            size: _selectedSize!,
            color: _selectedColor!,
            quantity: _quantity,
          ),
        );
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text('${_product!.name} ditambahkan ke keranjang.'),
        action: SnackBarAction(
          label: 'LIHAT',
          textColor: Colors.white,
          onPressed: () {
            Navigator.push(
              context,
              MaterialPageRoute(builder: (_) => const CartScreen()),
            );
          },
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        title: const Text('DETAIL PRODUK'),
        actions: [
          Consumer<CartProvider>(
            builder: (_, cart, __) => Stack(
              alignment: Alignment.center,
              children: [
                IconButton(
                  icon: const Icon(Icons.shopping_bag_outlined),
                  onPressed: () {
                    Navigator.push(
                      context,
                      MaterialPageRoute(builder: (_) => const CartScreen()),
                    );
                  },
                ),
                if (cart.itemCount > 0)
                  Positioned(
                    top: 10,
                    right: 8,
                    child: Container(
                      padding: const EdgeInsets.symmetric(
                          horizontal: 4, vertical: 1),
                      decoration: const BoxDecoration(
                        color: AppColors.primary,
                        shape: BoxShape.circle,
                      ),
                      constraints:
                          const BoxConstraints(minWidth: 16, minHeight: 16),
                      child: Text(
                        '${cart.itemCount}',
                        textAlign: TextAlign.center,
                        style: const TextStyle(
                          color: Colors.white,
                          fontSize: 9,
                          fontWeight: FontWeight.w700,
                        ),
                      ),
                    ),
                  ),
              ],
            ),
          ),
        ],
      ),
      body: _loading
          ? const BrandLoader(label: 'Memuat produk...')
          : _error != null
              ? ErrorState(message: _error!, onRetry: _load)
              : _buildContent(),
      bottomNavigationBar: _product == null ? null : _buildBottomBar(),
    );
  }

  Widget _buildContent() {
    final product = _product!;
    return ListView(
      padding: EdgeInsets.zero,
      children: [
        _buildGallery(product),
        Padding(
          padding: const EdgeInsets.all(20),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              if (product.category.name != null)
                Text(
                  product.category.name!.toUpperCase(),
                  style: const TextStyle(
                    fontSize: 11,
                    letterSpacing: 2,
                    color: AppColors.textMuted,
                  ),
                ),
              const SizedBox(height: 6),
              Text(
                product.name,
                style: const TextStyle(
                  fontSize: 20,
                  fontWeight: FontWeight.w600,
                  color: AppColors.textPrimary,
                ),
              ),
              const SizedBox(height: 12),
              _buildPrice(product),
              const SizedBox(height: 20),
              Row(
                children: [
                  Icon(
                    product.stock > 0
                        ? Icons.check_circle_outline
                        : Icons.remove_circle_outline,
                    size: 16,
                    color: product.stock > 0
                        ? Colors.green.shade600
                        : AppColors.discount,
                  ),
                  const SizedBox(width: 6),
                  Text(
                    product.stock > 0
                        ? 'Stok tersedia (${product.stock})'
                        : 'Stok habis',
                    style: TextStyle(
                      fontSize: 12,
                      color: product.stock > 0
                          ? Colors.green.shade700
                          : AppColors.discount,
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 24),
              if (product.colors.isNotEmpty) ...[
                _sectionLabel('Warna',
                    trailing: _selectedColor?.name ?? ''),
                const SizedBox(height: 10),
                Wrap(
                  spacing: 12,
                  runSpacing: 12,
                  children: product.colors
                      .map(
                        (c) => ColorSwatchButton(
                          color: c,
                          selected: _selectedColor?.id == c.id,
                          onTap: () => setState(() => _selectedColor = c),
                        ),
                      )
                      .toList(),
                ),
                const SizedBox(height: 24),
              ],
              if (product.sizes.isNotEmpty) ...[
                _sectionLabel('Ukuran',
                    trailing: _selectedSize?.description ?? ''),
                const SizedBox(height: 10),
                Wrap(
                  spacing: 10,
                  runSpacing: 10,
                  children: product.sizes
                      .map(
                        (s) => SizeChip(
                          size: s,
                          selected: _selectedSize?.id == s.id,
                          onTap: () => setState(() => _selectedSize = s),
                        ),
                      )
                      .toList(),
                ),
                const SizedBox(height: 24),
              ],
              _sectionLabel('Jumlah'),
              const SizedBox(height: 10),
              QuantitySelector(
                value: _quantity,
                min: 1,
                max: product.stock <= 0 ? 1 : product.stock,
                onChanged: (v) => setState(() => _quantity = v),
              ),
              const SizedBox(height: 28),
              if (product.description != null &&
                  product.description!.isNotEmpty) ...[
                _sectionLabel('Deskripsi'),
                const SizedBox(height: 10),
                Text(
                  product.description!,
                  style: const TextStyle(
                    fontSize: 13,
                    color: AppColors.textSecondary,
                    height: 1.6,
                  ),
                ),
                const SizedBox(height: 20),
              ],
              if (product.material.name != null &&
                  product.material.name!.isNotEmpty) ...[
                _detailRow('Bahan', product.material.name!),
                if (product.material.description != null &&
                    product.material.description!.isNotEmpty)
                  Padding(
                    padding: const EdgeInsets.only(top: 6),
                    child: Text(
                      product.material.description!,
                      style: const TextStyle(
                        fontSize: 11,
                        color: AppColors.textMuted,
                        height: 1.6,
                      ),
                    ),
                  ),
              ],
              const SizedBox(height: 80),
            ],
          ),
        ),
      ],
    );
  }

  Widget _sectionLabel(String label, {String trailing = ''}) {
    return Row(
      children: [
        Text(
          label.toUpperCase(),
          style: const TextStyle(
            fontSize: 11,
            fontWeight: FontWeight.w700,
            letterSpacing: 1.6,
            color: AppColors.primary,
          ),
        ),
        const Spacer(),
        Text(
          trailing,
          style: const TextStyle(fontSize: 12, color: AppColors.textSecondary),
        ),
      ],
    );
  }

  Widget _detailRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 6),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 80,
            child: Text(
              label,
              style: const TextStyle(
                fontSize: 11,
                color: AppColors.textMuted,
                letterSpacing: 1,
              ),
            ),
          ),
          Expanded(
            child: Text(
              value,
              style: const TextStyle(
                fontSize: 13,
                color: AppColors.textPrimary,
                fontWeight: FontWeight.w500,
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildPrice(ProductDetail product) {
    if (product.hasDiscount) {
      return Row(
        crossAxisAlignment: CrossAxisAlignment.end,
        children: [
          Text(
            formatIdr(product.displayPrice),
            style: const TextStyle(
              fontSize: 20,
              color: AppColors.primary,
              fontWeight: FontWeight.w700,
            ),
          ),
          const SizedBox(width: 10),
          Padding(
            padding: const EdgeInsets.only(bottom: 3),
            child: Text(
              formatIdr(product.price),
              style: const TextStyle(
                fontSize: 12,
                color: AppColors.textMuted,
                decoration: TextDecoration.lineThrough,
                decorationColor: AppColors.discount,
              ),
            ),
          ),
          const SizedBox(width: 8),
          Padding(
            padding: const EdgeInsets.only(bottom: 3),
            child: Container(
              padding:
                  const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
              color: AppColors.primary,
              child: Text(
                '-${product.discountPercent ?? 0}%',
                style: const TextStyle(
                  fontSize: 10,
                  color: Colors.white,
                  fontWeight: FontWeight.w700,
                ),
              ),
            ),
          ),
        ],
      );
    }
    return Text(
      formatIdr(product.price),
      style: const TextStyle(
        fontSize: 20,
        color: AppColors.primary,
        fontWeight: FontWeight.w700,
      ),
    );
  }

  Widget _buildGallery(ProductDetail product) {
    final photos = product.photos;
    if (photos.isEmpty) {
      return Container(
        height: 420,
        color: AppColors.blush,
        child: const Center(
          child: Icon(Icons.image_outlined,
              size: 48, color: AppColors.textMuted),
        ),
      );
    }

    return SizedBox(
      height: 480,
      child: Stack(
        children: [
          PageView.builder(
            controller: _pageController,
            itemCount: photos.length,
            onPageChanged: (i) => setState(() => _currentPhoto = i),
            itemBuilder: (_, i) => CachedNetworkImage(
              imageUrl: photos[i].url,
              fit: BoxFit.cover,
              placeholder: (_, __) => Container(color: AppColors.blush),
              errorWidget: (_, __, ___) => const Center(
                child: Icon(Icons.broken_image_outlined,
                    color: AppColors.textMuted),
              ),
            ),
          ),
          Positioned(
            bottom: 16,
            left: 0,
            right: 0,
            child: Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: List.generate(
                photos.length,
                (i) => AnimatedContainer(
                  duration: const Duration(milliseconds: 200),
                  margin: const EdgeInsets.symmetric(horizontal: 3),
                  width: _currentPhoto == i ? 18 : 6,
                  height: 3,
                  color: _currentPhoto == i
                      ? AppColors.primary
                      : AppColors.primary.withValues(alpha: .3),
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildBottomBar() {
    final product = _product!;
    return Container(
      padding: const EdgeInsets.fromLTRB(20, 12, 20, 20),
      decoration: const BoxDecoration(
        color: Colors.white,
        border: Border(top: BorderSide(color: AppColors.divider)),
      ),
      child: SafeArea(
        top: false,
        child: Row(
          children: [
            Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              mainAxisSize: MainAxisSize.min,
              children: [
                const Text(
                  'TOTAL',
                  style: TextStyle(
                    fontSize: 10,
                    color: AppColors.textMuted,
                    letterSpacing: 1.6,
                  ),
                ),
                const SizedBox(height: 2),
                Text(
                  formatIdr(product.displayPrice * _quantity),
                  style: const TextStyle(
                    color: AppColors.primary,
                    fontSize: 16,
                    fontWeight: FontWeight.w700,
                  ),
                ),
              ],
            ),
            const SizedBox(width: 16),
            Expanded(
              child: PrimaryButton(
                label: product.stock <= 0 ? 'Sold Out' : 'Tambah ke Keranjang',
                icon: Icons.add_shopping_cart,
                onPressed: product.stock <= 0 ? null : _addToCart,
              ),
            ),
          ],
        ),
      ),
    );
  }
}
