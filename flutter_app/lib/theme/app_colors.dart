import 'package:flutter/material.dart';

/// Brand palette lifted from the AnstheLabel website.
class AppColors {
  AppColors._();

  static const Color primary = Color(0xFF560024);
  static const Color primaryDark = Color(0xFF4A002A);
  static const Color primarySoft = Color(0xFF96516E);
  static const Color accent = Color(0xFFA65A6A);
  static const Color blush = Color(0xFFFBE9EB);
  static const Color peach = Color(0xFFF4D6CC);
  static const Color mutedRose = Color(0xFFDBB2B6);

  static const Color surface = Colors.white;
  static const Color scaffold = Color(0xFFFAFAFA);
  static const Color textPrimary = Color(0xFF1F1F1F);
  static const Color textSecondary = Color(0xFF666666);
  static const Color textMuted = Color(0xFF9B9B9B);
  static const Color border = Color(0xFFEAEAEA);
  static const Color divider = Color(0xFFF0F0F0);
  static const Color discount = Color(0xFFD81313);

  static const LinearGradient primaryGradient = LinearGradient(
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
    colors: [primary, primaryDark],
  );
}
