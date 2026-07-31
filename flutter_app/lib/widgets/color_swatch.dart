import 'package:flutter/material.dart';

import '../models/product_detail.dart';
import '../theme/app_colors.dart';

class ColorSwatchButton extends StatelessWidget {
  final ProductColor color;
  final bool selected;
  final VoidCallback onTap;

  const ColorSwatchButton({
    super.key,
    required this.color,
    required this.selected,
    required this.onTap,
  });

  Color get _fill {
    final hex = color.hex;
    if (hex == null || hex.isEmpty) return AppColors.blush;
    var cleaned = hex.replaceAll('#', '');
    if (cleaned.length == 6) cleaned = 'FF$cleaned';
    return Color(int.tryParse(cleaned, radix: 16) ?? 0xFFEAEAEA);
  }

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        width: 34,
        height: 34,
        decoration: BoxDecoration(
          shape: BoxShape.circle,
          color: _fill,
          border: Border.all(
            color: selected ? AppColors.primary : AppColors.border,
            width: selected ? 2 : 1,
          ),
        ),
      ),
    );
  }
}
