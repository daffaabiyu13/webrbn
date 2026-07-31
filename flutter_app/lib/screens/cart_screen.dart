import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../models/cart_item.dart';
import '../providers/cart_provider.dart';
import '../theme/app_colors.dart';
import '../utils/currency.dart';
import '../widgets/brand_buttons.dart';
import '../widgets/loading_placeholders.dart';
import '../widgets/quantity_selector.dart';
import 'checkout_screen.dart';

class CartScreen extends StatelessWidget {
  final bool embedded;
  const CartScreen({super.key, this.embedded = false});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        automaticallyImplyLeading: !embedded,
        title: const Text('KERANJANG'),
      ),
      body: Consumer<CartProvider>(
        builder: (context, cart, _) {
          if (cart.isEmpty) {
            return EmptyState(
              icon: Icons.shopping_bag_outlined,
              title: 'Keranjangmu masih kosong',
              subtitle: 'Yuk mulai belanja koleksi terbaru dari AnstheLabel.',
              action: OutlineBrandButton(
                label: 'Mulai Belanja',
                onPressed: () {
                  if (embedded) return;
                  Navigator.popUntil(context, (r) => r.isFirst);
                },
              ),
            );
          }
          return Column(
            children: [
              Expanded(
                child: ListView.separated(
                  padding: const EdgeInsets.all(16),
                  itemCount: cart.items.length,
                  separatorBuilder: (_, __) => const Divider(height: 20),
                  itemBuilder: (_, i) => _CartRow(item: cart.items[i]),
                ),
              ),
              _buildFooter(context, cart),
            ],
          );
        },
      ),
    );
  }

  Widget _buildFooter(BuildContext context, CartProvider cart) {
    return Container(
      padding: const EdgeInsets.fromLTRB(20, 16, 20, 20),
      decoration: const BoxDecoration(
        color: Colors.white,
        border: Border(top: BorderSide(color: AppColors.divider)),
      ),
      child: SafeArea(
        top: false,
        child: Column(
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                const Text('Subtotal',
                    style: TextStyle(
                        color: AppColors.textSecondary,
                        fontSize: 12,
                        letterSpacing: 1)),
                Text(
                  formatIdr(cart.subtotal),
                  style: const TextStyle(
                    color: AppColors.primary,
                    fontSize: 16,
                    fontWeight: FontWeight.w700,
                  ),
                ),
              ],
            ),
            const SizedBox(height: 4),
            const Align(
              alignment: Alignment.centerLeft,
              child: Text(
                'Voucher & ongkir dihitung saat checkout.',
                style:
                    TextStyle(fontSize: 10, color: AppColors.textMuted),
              ),
            ),
            const SizedBox(height: 16),
            PrimaryButton(
              label: 'Lanjut ke Checkout',
              icon: Icons.arrow_forward,
              onPressed: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(
                    builder: (_) => const CheckoutScreen(),
                  ),
                );
              },
            ),
          ],
        ),
      ),
    );
  }
}

class _CartRow extends StatelessWidget {
  final CartItem item;
  const _CartRow({required this.item});

  @override
  Widget build(BuildContext context) {
    final cart = context.read<CartProvider>();
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        SizedBox(
          width: 88,
          height: 112,
          child: item.photoUrl != null
              ? CachedNetworkImage(
                  imageUrl: item.photoUrl!,
                  fit: BoxFit.cover,
                  errorWidget: (_, __, ___) => Container(color: AppColors.blush),
                )
              : Container(color: AppColors.blush),
        ),
        const SizedBox(width: 14),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                children: [
                  Expanded(
                    child: Text(
                      item.name,
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                      style: const TextStyle(
                        fontSize: 13,
                        fontWeight: FontWeight.w600,
                      ),
                    ),
                  ),
                  IconButton(
                    padding: EdgeInsets.zero,
                    constraints: const BoxConstraints(),
                    icon: const Icon(Icons.close,
                        size: 18, color: AppColors.textMuted),
                    onPressed: () => cart.remove(item.compositeKey),
                  ),
                ],
              ),
              const SizedBox(height: 4),
              Text(
                'Warna: ${item.colorName}  •  Ukuran: ${item.sizeName}',
                style: const TextStyle(
                  fontSize: 11,
                  color: AppColors.textSecondary,
                ),
              ),
              const SizedBox(height: 8),
              Text(
                formatIdr(item.unitPrice),
                style: const TextStyle(
                  fontSize: 13,
                  color: AppColors.primary,
                  fontWeight: FontWeight.w700,
                ),
              ),
              const SizedBox(height: 10),
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  QuantitySelector(
                    value: item.quantity,
                    onChanged: (v) {
                      if (v > item.quantity) {
                        cart.increment(item.compositeKey);
                      } else {
                        cart.decrement(item.compositeKey);
                      }
                    },
                  ),
                  Text(
                    formatIdr(item.subtotal),
                    style: const TextStyle(
                      fontSize: 12,
                      fontWeight: FontWeight.w600,
                      color: AppColors.textPrimary,
                    ),
                  ),
                ],
              ),
            ],
          ),
        ),
      ],
    );
  }
}
