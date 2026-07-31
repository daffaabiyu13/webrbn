import 'package:flutter/material.dart';

import '../theme/app_colors.dart';

/// A serif-style wordmark that echoes the website's monogram feel.
/// Uses text only, so we don't depend on remote or bundled logo assets.
class BrandLogo extends StatelessWidget {
  final Color color;
  final double fontSize;
  final double letterSpacing;

  const BrandLogo({
    super.key,
    this.color = AppColors.primary,
    this.fontSize = 22,
    this.letterSpacing = 4,
  });

  @override
  Widget build(BuildContext context) {
    return Text.rich(
      TextSpan(
        style: TextStyle(
          color: color,
          fontSize: fontSize,
          fontWeight: FontWeight.w300,
          letterSpacing: letterSpacing,
        ),
        children: const [
          TextSpan(
            text: 'ANSTHE',
            style: TextStyle(fontWeight: FontWeight.w700),
          ),
          TextSpan(text: 'LABEL'),
        ],
      ),
    );
  }
}
