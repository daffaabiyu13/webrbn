import 'dart:async';

import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../models/product.dart';
import '../services/api_client.dart';
import '../services/product_service.dart';
import '../theme/app_colors.dart';
import '../utils/currency.dart';
import '../widgets/brand_buttons.dart';
import '../widgets/brand_logo.dart';
import '../widgets/loading_placeholders.dart';
import '../widgets/product_card.dart';
import '../widgets/section_header.dart';
import 'cart_screen.dart';
import 'collection_screen.dart';
import 'product_detail_screen.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  late final ProductService _service;

  List<Product>? _best;
  List<Product>? _newArrivals;
  bool _loading = true;
  String? _error;

  @override
  void initState() {
    super.initState();
    _service = ProductService(context.read<ApiClient>());
    _load();
  }

  Future<void> _load() async {
    setState(() {
      _loading = true;
      _error = null;
    });
    try {
      final results = await Future.wait([
        _service.best(perPage: 8),
        _service
            .list(const ProductQuery(perPage: 8, sortBy: 'terbaru'))
            .then((p) => p.items),
      ]);
      if (!mounted) return;
      setState(() {
        _best = results[0];
        _newArrivals = results[1];
        _loading = false;
      });
    } catch (e) {
      if (!mounted) return;
      setState(() {
        _error = e is ApiException ? e.message : e.toString();
        _loading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: _buildAppBar(),
      body: RefreshIndicator(
        color: AppColors.primary,
        onRefresh: _load,
        child: _loading
            ? const Padding(
                padding: EdgeInsets.only(top: 120),
                child: BrandLoader(label: 'Memuat koleksi terbaru...'),
              )
            : _error != null
                ? ErrorState(message: _error!, onRetry: _load)
                : _buildContent(),
      ),
    );
  }

  PreferredSizeWidget _buildAppBar() {
    return AppBar(
      automaticallyImplyLeading: false,
      title: const BrandLogo(fontSize: 18, letterSpacing: 4),
      actions: [
        IconButton(
          icon: const Icon(Icons.search),
          onPressed: () {
            Navigator.push(
              context,
              MaterialPageRoute(
                builder: (_) => const CollectionScreen(autofocusSearch: true),
              ),
            );
          },
        ),
        IconButton(
          icon: const Icon(Icons.shopping_bag_outlined),
          onPressed: () {
            Navigator.push(
              context,
              MaterialPageRoute(builder: (_) => const CartScreen()),
            );
          },
        ),
        const SizedBox(width: 4),
      ],
    );
  }

  Widget _buildContent() {
    return ListView(
      padding: EdgeInsets.zero,
      children: [
        const _HeroBanner(),
        const SizedBox(height: 32),
        Padding(
          padding: const EdgeInsets.symmetric(horizontal: 20),
          child: SectionHeader(
            title: 'Best Product',
            actionLabel: 'LIHAT SEMUA',
            onAction: () => _openCollection(isBest: true),
          ),
        ),
        const SizedBox(height: 16),
        _buildProductRow(_best ?? const []),
        const SizedBox(height: 40),
        const Padding(
          padding: EdgeInsets.symmetric(horizontal: 20),
          child: SectionHeader(title: 'New Arrival', centered: true),
        ),
        const SizedBox(height: 20),
        _NewArrivalCarousel(products: _newArrivals ?? const []),
        const SizedBox(height: 32),
        Center(
          child: OutlineBrandButton(
            label: 'Shop All',
            onPressed: () => _openCollection(),
          ),
        ),
        const SizedBox(height: 40),
        const _BrandStory(),
        const SizedBox(height: 40),
      ],
    );
  }

  Widget _buildProductRow(List<Product> items) {
    if (items.isEmpty) {
      return const Padding(
        padding: EdgeInsets.symmetric(horizontal: 20),
        child: Text(
          'Belum ada produk yang ditampilkan.',
          style: TextStyle(color: AppColors.textSecondary, fontSize: 12),
        ),
      );
    }
    return SizedBox(
      height: 320,
      child: ListView.separated(
        padding: const EdgeInsets.symmetric(horizontal: 20),
        scrollDirection: Axis.horizontal,
        itemCount: items.length,
        separatorBuilder: (_, __) => const SizedBox(width: 14),
        itemBuilder: (_, i) {
          final p = items[i];
          return SizedBox(
            width: 170,
            child: ProductCard(
              product: p,
              onTap: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(
                    builder: (_) => ProductDetailScreen(productId: p.id),
                  ),
                );
              },
            ),
          );
        },
      ),
    );
  }

  void _openCollection({bool isBest = false}) {
    Navigator.push(
      context,
      MaterialPageRoute(
        builder: (_) => CollectionScreen(initialBestOnly: isBest),
      ),
    );
  }
}

class _HeroBanner extends StatefulWidget {
  const _HeroBanner();
  @override
  State<_HeroBanner> createState() => _HeroBannerState();
}

class _HeroBannerState extends State<_HeroBanner> {
  final _controller = PageController();
  int _current = 0;
  Timer? _timer;

  final _slides = const [
    _HeroSlide(
      eyebrow: 'AUTUMN COLLECTION',
      title: 'The Modern\nMinimalist Edit',
      description:
          'Siluet lembut dengan palet earthy — dirancang untuk keseharian yang tenang.',
      color: AppColors.blush,
      accent: AppColors.primary,
    ),
    _HeroSlide(
      eyebrow: 'SIGNATURE PIECES',
      title: 'Warna Marun,\nGaya Tak Lekang',
      description:
          'Koleksi ikonik AnstheLabel dengan sentuhan detail yang sempurna.',
      color: AppColors.peach,
      accent: AppColors.primaryDark,
    ),
    _HeroSlide(
      eyebrow: 'NEW ARRIVAL',
      title: 'Elegance is\nAn Attitude',
      description:
          'Temukan potongan baru yang siap menemani setiap momen istimewa.',
      color: AppColors.mutedRose,
      accent: AppColors.primary,
    ),
  ];

  @override
  void initState() {
    super.initState();
    _timer = Timer.periodic(const Duration(seconds: 5), (_) {
      if (!mounted) return;
      final next = (_current + 1) % _slides.length;
      _controller.animateToPage(
        next,
        duration: const Duration(milliseconds: 600),
        curve: Curves.easeInOut,
      );
    });
  }

  @override
  void dispose() {
    _timer?.cancel();
    _controller.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      height: 420,
      child: Stack(
        children: [
          PageView.builder(
            controller: _controller,
            itemCount: _slides.length,
            onPageChanged: (i) => setState(() => _current = i),
            itemBuilder: (_, i) => _slides[i],
          ),
          Positioned(
            bottom: 20,
            left: 0,
            right: 0,
            child: Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: List.generate(_slides.length, (i) {
                final active = i == _current;
                return AnimatedContainer(
                  duration: const Duration(milliseconds: 300),
                  margin: const EdgeInsets.symmetric(horizontal: 4),
                  height: 4,
                  width: active ? 24 : 8,
                  color:
                      active ? AppColors.primary : AppColors.primary.withValues(alpha: .35),
                );
              }),
            ),
          ),
        ],
      ),
    );
  }
}

class _HeroSlide extends StatelessWidget {
  final String eyebrow;
  final String title;
  final String description;
  final Color color;
  final Color accent;

  const _HeroSlide({
    required this.eyebrow,
    required this.title,
    required this.description,
    required this.color,
    required this.accent,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      color: color,
      padding: const EdgeInsets.fromLTRB(28, 28, 28, 48),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
            decoration: BoxDecoration(
              border: Border.all(color: accent, width: 1),
            ),
            child: Text(
              eyebrow,
              style: TextStyle(
                color: accent,
                fontSize: 10,
                fontWeight: FontWeight.w700,
                letterSpacing: 2.5,
              ),
            ),
          ),
          const SizedBox(height: 20),
          Text(
            title,
            style: TextStyle(
              color: accent,
              fontSize: 34,
              height: 1.05,
              fontWeight: FontWeight.w300,
              letterSpacing: -0.5,
            ),
          ),
          const SizedBox(height: 16),
          SizedBox(
            width: 300,
            child: Text(
              description,
              style: TextStyle(
                color: accent.withValues(alpha: .75),
                fontSize: 13,
                height: 1.5,
              ),
            ),
          ),
        ],
      ),
    );
  }
}

class _NewArrivalCarousel extends StatelessWidget {
  final List<Product> products;
  const _NewArrivalCarousel({required this.products});

  @override
  Widget build(BuildContext context) {
    if (products.isEmpty) {
      return const Padding(
        padding: EdgeInsets.symmetric(horizontal: 20),
        child: Text(
          'Koleksi terbaru akan hadir segera.',
          style: TextStyle(color: AppColors.textSecondary, fontSize: 12),
        ),
      );
    }
    return SizedBox(
      height: 440,
      child: PageView.builder(
        controller: PageController(viewportFraction: 0.72),
        itemCount: products.length,
        itemBuilder: (_, i) {
          final p = products[i];
          return Padding(
            padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
            child: GestureDetector(
              onTap: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(
                    builder: (_) => ProductDetailScreen(productId: p.id),
                  ),
                );
              },
              child: Stack(
                children: [
                  Positioned.fill(
                    child: Container(
                      color: AppColors.blush,
                      child: p.photoUrl != null
                          ? CachedNetworkImage(
                              imageUrl: p.photoUrl!,
                              fit: BoxFit.cover,
                              errorWidget: (_, __, ___) => const Icon(
                                Icons.image_not_supported_outlined,
                                color: AppColors.textMuted,
                              ),
                            )
                          : const Center(
                              child: Icon(Icons.image_outlined,
                                  color: AppColors.textMuted),
                            ),
                    ),
                  ),
                  Positioned(
                    left: 16,
                    right: 16,
                    bottom: 20,
                    child: Container(
                      padding: const EdgeInsets.all(14),
                      decoration: const BoxDecoration(
                        color: Colors.white,
                      ),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          Text(
                            p.name.toUpperCase(),
                            maxLines: 1,
                            overflow: TextOverflow.ellipsis,
                            style: const TextStyle(
                              fontSize: 12,
                              fontWeight: FontWeight.w700,
                              color: AppColors.textPrimary,
                              letterSpacing: 1,
                            ),
                          ),
                          const SizedBox(height: 4),
                          Text(
                            formatIdr(p.displayPrice),
                            style: const TextStyle(
                              color: AppColors.primary,
                              fontSize: 13,
                              fontWeight: FontWeight.w700,
                            ),
                          ),
                        ],
                      ),
                    ),
                  ),
                ],
              ),
            ),
          );
        },
      ),
    );
  }
}

class _BrandStory extends StatelessWidget {
  const _BrandStory();

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 20),
      padding: const EdgeInsets.symmetric(horizontal: 28, vertical: 36),
      decoration: const BoxDecoration(color: AppColors.blush),
      child: Column(
        children: [
          const BrandLogo(color: AppColors.primary, fontSize: 20),
          const SizedBox(height: 12),
          const Text(
            'Elegance,\nRefined.',
            textAlign: TextAlign.center,
            style: TextStyle(
              fontSize: 28,
              height: 1.05,
              color: AppColors.primary,
              fontWeight: FontWeight.w300,
              letterSpacing: -0.5,
            ),
          ),
          const SizedBox(height: 12),
          const Text(
            'AnstheLabel adalah rumah dari koleksi feminin dengan potongan modern, palet lembut, dan kualitas tenun yang tahan lama.',
            textAlign: TextAlign.center,
            style: TextStyle(
              fontSize: 12,
              height: 1.6,
              color: AppColors.textSecondary,
            ),
          ),
        ],
      ),
    );
  }
}
