import 'package:flutter/material.dart';

import '../theme/app_colors.dart';

class SectionHeader extends StatelessWidget {
  final String title;
  final String? actionLabel;
  final VoidCallback? onAction;
  final bool centered;

  const SectionHeader({
    super.key,
    required this.title,
    this.actionLabel,
    this.onAction,
    this.centered = false,
  });

  @override
  Widget build(BuildContext context) {
    final titleWidget = Text(
      title,
      textAlign: centered ? TextAlign.center : TextAlign.start,
      style: const TextStyle(
        fontSize: 18,
        fontWeight: FontWeight.w700,
        color: AppColors.primary,
        letterSpacing: 0.5,
      ),
    );

    if (centered) {
      return Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          titleWidget,
          Container(
            margin: const EdgeInsets.only(top: 8),
            height: 2,
            width: 32,
            color: AppColors.accent,
          ),
        ],
      );
    }

    return Row(
      children: [
        Expanded(child: titleWidget),
        if (actionLabel != null)
          TextButton(
            onPressed: onAction,
            style: TextButton.styleFrom(
              foregroundColor: AppColors.primarySoft,
              padding: EdgeInsets.zero,
              minimumSize: const Size(0, 30),
              tapTargetSize: MaterialTapTargetSize.shrinkWrap,
            ),
            child: Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                Text(
                  actionLabel!,
                  style: const TextStyle(
                    fontSize: 12,
                    fontWeight: FontWeight.w600,
                  ),
                ),
                const SizedBox(width: 4),
                const Icon(Icons.chevron_right, size: 16),
              ],
            ),
          ),
      ],
    );
  }
}
