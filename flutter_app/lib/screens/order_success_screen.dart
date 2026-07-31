import 'package:flutter/material.dart';
import 'package:flutter/services.dart';

import '../models/checkout.dart';
import '../theme/app_colors.dart';
import '../utils/currency.dart';
import '../widgets/brand_buttons.dart';
import 'main_navigation.dart';
import 'transaction_detail_screen.dart';

class OrderSuccessScreen extends StatelessWidget {
  final CheckoutResult result;
  const OrderSuccessScreen({super.key, required this.result});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        title: const Text('KONFIRMASI PESANAN'),
        leading: IconButton(
          icon: const Icon(Icons.close),
          onPressed: () => Navigator.pushAndRemoveUntil(
            context,
            MaterialPageRoute(builder: (_) => const MainNavigation()),
            (r) => false,
          ),
        ),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 24),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            Container(
              padding: const EdgeInsets.symmetric(vertical: 32),
              decoration: const BoxDecoration(color: AppColors.blush),
              child: const Column(
                children: [
                  Icon(Icons.check_circle,
                      color: AppColors.primary, size: 48),
                  SizedBox(height: 12),
                  Text(
                    'Terima kasih!',
                    style: TextStyle(
                      color: AppColors.primary,
                      fontSize: 22,
                      fontWeight: FontWeight.w300,
                      letterSpacing: 0.5,
                    ),
                  ),
                  SizedBox(height: 6),
                  Text(
                    'Pesananmu telah kami terima.\nSelesaikan pembayaran untuk kami proses.',
                    textAlign: TextAlign.center,
                    style: TextStyle(
                      color: AppColors.textSecondary,
                      fontSize: 12,
                      height: 1.5,
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 24),
            _detailCard(context),
            const SizedBox(height: 24),
            PrimaryButton(
              label: 'Lihat Detail Pesanan',
              icon: Icons.receipt_long,
              onPressed: () {
                Navigator.pushAndRemoveUntil(
                  context,
                  MaterialPageRoute(
                    builder: (_) => TransactionDetailScreen(
                        invoice: result.invoice),
                  ),
                  (r) => r.isFirst,
                );
              },
            ),
            const SizedBox(height: 10),
            OutlineBrandButton(
              label: 'Kembali ke Beranda',
              onPressed: () => Navigator.pushAndRemoveUntil(
                context,
                MaterialPageRoute(builder: (_) => const MainNavigation()),
                (r) => false,
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _detailCard(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        border: Border.all(color: AppColors.border),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text('KODE INVOICE',
              style: TextStyle(
                fontSize: 10,
                letterSpacing: 1.6,
                color: AppColors.textMuted,
                fontWeight: FontWeight.w700,
              )),
          const SizedBox(height: 4),
          Row(
            children: [
              Expanded(
                child: SelectableText(
                  result.invoice,
                  style: const TextStyle(
                    fontSize: 16,
                    fontWeight: FontWeight.w700,
                    color: AppColors.primary,
                    letterSpacing: 1,
                  ),
                ),
              ),
              IconButton(
                icon: const Icon(Icons.copy, size: 18),
                color: AppColors.primary,
                onPressed: () {
                  Clipboard.setData(ClipboardData(text: result.invoice));
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(content: Text('Kode invoice disalin.')),
                  );
                },
              ),
            ],
          ),
          const Divider(height: 24),
          _row('Total Bayar',
              formatIdr(result.totalAmount), emphasized: true),
          _row('Jumlah Produk', '${result.itemsCount} item'),
          if (result.discount > 0)
            _row('Diskon Voucher', '- ${formatIdr(result.discount)}',
                valueColor: AppColors.discount),
          if (result.paymentName != null && result.paymentName!.isNotEmpty)
            _row('Metode Pembayaran', result.paymentName!),
          if (result.paymentAccount != null &&
              result.paymentAccount!.isNotEmpty)
            _row('No. Rekening', result.paymentAccount!, copyable: context),
          if (result.paymentAccountHolder != null &&
              result.paymentAccountHolder!.isNotEmpty)
            _row('Atas Nama', result.paymentAccountHolder!),
          if (result.deadline != null)
            _row('Batas Bayar',
                _formatDate(result.deadline!, withTime: true)),
        ],
      ),
    );
  }

  Widget _row(String label, String value,
      {bool emphasized = false, Color? valueColor, BuildContext? copyable}) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 6),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 130,
            child: Text(
              label,
              style: const TextStyle(
                fontSize: 11,
                color: AppColors.textMuted,
                letterSpacing: 0.6,
              ),
            ),
          ),
          Expanded(
            child: Text(
              value,
              style: TextStyle(
                fontSize: emphasized ? 15 : 13,
                fontWeight: emphasized ? FontWeight.w700 : FontWeight.w500,
                color: valueColor ??
                    (emphasized
                        ? AppColors.primary
                        : AppColors.textPrimary),
              ),
            ),
          ),
          if (copyable != null)
            InkWell(
              onTap: () {
                Clipboard.setData(ClipboardData(text: value));
                ScaffoldMessenger.of(copyable).showSnackBar(
                  const SnackBar(content: Text('Disalin.')),
                );
              },
              child: const Padding(
                padding: EdgeInsets.symmetric(horizontal: 4),
                child: Icon(Icons.copy,
                    size: 14, color: AppColors.primarySoft),
              ),
            ),
        ],
      ),
    );
  }

  String _formatDate(DateTime d, {bool withTime = false}) {
    final local = d.toLocal();
    final date =
        '${local.day.toString().padLeft(2, '0')}/${local.month.toString().padLeft(2, '0')}/${local.year}';
    if (!withTime) return date;
    final time =
        '${local.hour.toString().padLeft(2, '0')}:${local.minute.toString().padLeft(2, '0')}';
    return '$date, $time';
  }
}
