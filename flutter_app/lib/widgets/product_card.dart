import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';

import '../models/product.dart';
import '../theme/app_colors.dart';
import '../utils/currency.dart';

class ProductCard extends StatelessWidget {
  final Product product;
  final VoidCallback? onTap;
  final double imageHeight;

  const ProductCard({
    super.key,
    required this.product,
    this.onTap,
    this.imageHeight = 220,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      behavior: HitTestBehavior.opaque,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          _buildImage(),
          const SizedBox(height: 10),
          Text(
            product.category.name ?? '',
            style: const TextStyle(
              fontSize: 11,
              color: AppColors.textMuted,
              letterSpacing: 0.6,
            ),
            maxLines: 1,
            overflow: TextOverflow.ellipsis,
          ),
          const SizedBox(height: 4),
          Text(
            product.name,
            style: const TextStyle(
              fontSize: 13,
              fontWeight: FontWeight.w600,
              color: AppColors.textPrimary,
              height: 1.3,
            ),
            maxLines: 2,
            overflow: TextOverflow.ellipsis,
          ),
          const SizedBox(height: 6),
          _buildPrice(),
        ],
      ),
    );
  }

  Widget _buildImage() {
    return AspectRatio(
      aspectRatio: 3 / 4,
      child: Stack(
        children: [
          Positioned.fill(
            child: Container(
              color: AppColors.blush,
              child: product.photoUrl != null
                  ? CachedNetworkImage(
                      imageUrl: product.photoUrl!,
                      fit: BoxFit.cover,
                      placeholder: (_, __) => Container(color: AppColors.blush),
                      errorWidget: (_, __, ___) => const Center(
                        child: Icon(Icons.image_not_supported_outlined,
                            color: AppColors.textMuted),
                      ),
                    )
                  : const Center(
                      child: Icon(Icons.image_outlined,
                          color: AppColors.textMuted),
                    ),
            ),
          ),
          if (product.hasDiscount)
            Positioned(
              top: 0,
              right: 0,
              child: Container(
                padding:
                    const EdgeInsets.symmetric(horizontal: 8, vertical: 6),
                color: AppColors.primary,
                child: Text(
                  '-${product.discountPercent ?? 0}%',
                  style: const TextStyle(
                    color: Colors.white,
                    fontSize: 10,
                    fontWeight: FontWeight.w700,
                    letterSpacing: 0.4,
                  ),
                ),
              ),
            ),
          if (product.stock <= 0)
            const Positioned.fill(
              child: ColoredBox(
                color: Color(0x66FFFFFF),
                child: Center(
                  child: Text(
                    'SOLD OUT',
                    style: TextStyle(
                      color: AppColors.primary,
                      fontWeight: FontWeight.w700,
                      letterSpacing: 2,
                    ),
                  ),
                ),
              ),
            ),
        ],
      ),
    );
  }

  Widget _buildPrice() {
    if (product.hasDiscount) {
      return Wrap(
        crossAxisAlignment: WrapCrossAlignment.center,
        spacing: 6,
        runSpacing: 2,
        children: [
          Text(
            formatIdr(product.price),
            style: const TextStyle(
              fontSize: 11,
              color: AppColors.textMuted,
              decoration: TextDecoration.lineThrough,
              decorationColor: AppColors.discount,
            ),
          ),
          Text(
            formatIdr(product.displayPrice),
            style: const TextStyle(
              fontSize: 13,
              color: AppColors.primary,
              fontWeight: FontWeight.w700,
            ),
          ),
        ],
      );
    }
    return Text(
      formatIdr(product.price),
      style: const TextStyle(
        fontSize: 13,
        color: AppColors.primary,
        fontWeight: FontWeight.w700,
      ),
    );
  }
}
