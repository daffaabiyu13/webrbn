import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:image_picker/image_picker.dart';
import 'package:provider/provider.dart';

import '../models/transaction.dart';
import '../services/api_client.dart';
import '../services/transaction_service.dart';
import '../theme/app_colors.dart';
import '../utils/currency.dart';
import '../widgets/brand_buttons.dart';
import '../widgets/loading_placeholders.dart';

class TransactionDetailScreen extends StatefulWidget {
  final String invoice;
  const TransactionDetailScreen({super.key, required this.invoice});

  @override
  State<TransactionDetailScreen> createState() =>
      _TransactionDetailScreenState();
}

class _TransactionDetailScreenState extends State<TransactionDetailScreen> {
  late final TransactionService _service;
  final _picker = ImagePicker();

  TransactionDetail? _tx;
  bool _loading = true;
  bool _uploading = false;
  bool _cancelling = false;
  String? _error;

  @override
  void initState() {
    super.initState();
    _service = TransactionService(context.read<ApiClient>());
    _load();
  }

  Future<void> _load() async {
    setState(() {
      _loading = true;
      _error = null;
    });
    try {
      final tx = await _service.detail(widget.invoice);
      if (!mounted) return;
      setState(() {
        _tx = tx;
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

  Future<void> _uploadPayment() async {
    try {
      final picked = await _picker.pickImage(
        source: ImageSource.gallery,
        imageQuality: 80,
        maxWidth: 1600,
      );
      if (picked == null) return;
      setState(() => _uploading = true);
      final bytes = await picked.readAsBytes();
      await _service.uploadPayment(
        invoice: widget.invoice,
        bytes: bytes,
        filename: picked.name,
      );
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Bukti pembayaran berhasil dikirim.')),
      );
      _load();
    } catch (e) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
            content: Text(e is ApiException ? e.message : e.toString())),
      );
    } finally {
      if (mounted) setState(() => _uploading = false);
    }
  }

  Future<void> _cancel() async {
    final confirmed = await showDialog<bool>(
      context: context,
      builder: (_) => AlertDialog(
        title: const Text('Batalkan pesanan?'),
        content: const Text('Aksi ini tidak dapat dibatalkan.'),
        actions: [
          TextButton(
              onPressed: () => Navigator.pop(context, false),
              child: const Text('Tidak')),
          TextButton(
              style: TextButton.styleFrom(foregroundColor: AppColors.discount),
              onPressed: () => Navigator.pop(context, true),
              child: const Text('Ya, batalkan')),
        ],
      ),
    );
    if (confirmed != true) return;

    setState(() => _cancelling = true);
    try {
      await _service.cancel(widget.invoice);
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Pesanan berhasil dibatalkan.')),
      );
      _load();
    } catch (e) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
            content: Text(e is ApiException ? e.message : e.toString())),
      );
    } finally {
      if (mounted) setState(() => _cancelling = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.scaffold,
      appBar: AppBar(title: const Text('DETAIL PESANAN')),
      body: _loading
          ? const BrandLoader(label: 'Memuat detail...')
          : _error != null
              ? ErrorState(message: _error!, onRetry: _load)
              : _buildContent(),
    );
  }

  Widget _buildContent() {
    final tx = _tx!;
    return RefreshIndicator(
      color: AppColors.primary,
      onRefresh: _load,
      child: ListView(
        padding: const EdgeInsets.symmetric(vertical: 16),
        children: [
          _card(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  children: [
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          const Text('KODE INVOICE',
                              style: TextStyle(
                                fontSize: 10,
                                color: AppColors.textMuted,
                                letterSpacing: 1.4,
                                fontWeight: FontWeight.w700,
                              )),
                          const SizedBox(height: 4),
                          SelectableText(
                            tx.invoice,
                            style: const TextStyle(
                              fontSize: 15,
                              fontWeight: FontWeight.w700,
                              color: AppColors.primary,
                              letterSpacing: 1,
                            ),
                          ),
                        ],
                      ),
                    ),
                    _statusBadge(tx.transactionStatus),
                  ],
                ),
                const SizedBox(height: 8),
                Text(
                  tx.orderedAt != null
                      ? 'Dipesan pada ${_formatDateTime(tx.orderedAt!)}'
                      : '',
                  style: const TextStyle(
                    fontSize: 11,
                    color: AppColors.textMuted,
                  ),
                ),
              ],
            ),
          ),
          _card(
            title: 'Timeline Pesanan',
            child: Column(
              children: tx.timeline
                  .asMap()
                  .entries
                  .map((e) => _timelineStep(
                      e.value, e.key == tx.timeline.length - 1))
                  .toList(),
            ),
          ),
          _card(
            title: 'Item Pesanan',
            child: Column(
              children: tx.items.map(_itemRow).toList(),
            ),
          ),
          _card(
            title: 'Pembayaran',
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                if (tx.paymentName != null)
                  _row('Metode', tx.paymentName!),
                if (tx.paymentAccount != null &&
                    tx.paymentAccount!.isNotEmpty)
                  _row('No. Rekening', tx.paymentAccount!, copyable: true),
                if (tx.paymentAccountHolder != null &&
                    tx.paymentAccountHolder!.isNotEmpty)
                  _row('Atas Nama', tx.paymentAccountHolder!),
                _row('Total Bayar', formatIdr(tx.totalAmount),
                    emphasized: true),
                _row('Status', _statusText(tx.paymentStatus)),
                const SizedBox(height: 12),
                if (tx.paymentProofUrl != null) ...[
                  const Text('Bukti Pembayaran',
                      style: TextStyle(
                          fontSize: 12,
                          color: AppColors.textSecondary,
                          fontWeight: FontWeight.w600)),
                  const SizedBox(height: 8),
                  ClipRect(
                    child: CachedNetworkImage(
                      imageUrl: tx.paymentProofUrl!,
                      height: 220,
                      width: double.infinity,
                      fit: BoxFit.cover,
                      errorWidget: (_, __, ___) => const SizedBox.shrink(),
                    ),
                  ),
                ]
                else if (tx.paymentStatus == 'menunggu pembayaran') ...[
                  const SizedBox(height: 8),
                  PrimaryButton(
                    label: 'Upload Bukti Pembayaran',
                    icon: Icons.file_upload_outlined,
                    loading: _uploading,
                    onPressed: _uploadPayment,
                  ),
                ],
              ],
            ),
          ),
          _card(
            title: 'Pengiriman',
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                _row('Nama', tx.customerName),
                _row('No. Telp', tx.phone),
                if (tx.email != null && tx.email!.isNotEmpty)
                  _row('Email', tx.email!),
                _row('Alamat', tx.address),
                if (tx.expeditionName != null)
                  _row('Kurir', tx.expeditionName!),
              ],
            ),
          ),
          if (tx.transactionStatus == 'menunggu pembayaran') ...[
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
              child: OutlineBrandButton(
                label: _cancelling ? 'Membatalkan...' : 'Batalkan Pesanan',
                onPressed: _cancelling ? null : _cancel,
              ),
            ),
            const SizedBox(height: 16),
          ],
        ],
      ),
    );
  }

  Widget _timelineStep(TimelineStep step, bool isLast) {
    return IntrinsicHeight(
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Column(
            children: [
              Container(
                width: 20,
                height: 20,
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  color: step.done ? AppColors.primary : Colors.white,
                  border: Border.all(
                    color: step.done
                        ? AppColors.primary
                        : AppColors.border,
                    width: 2,
                  ),
                ),
                child: step.done
                    ? const Icon(Icons.check, color: Colors.white, size: 12)
                    : null,
              ),
              if (!isLast)
                Expanded(
                  child: Container(
                    width: 2,
                    color: step.done
                        ? AppColors.primary
                        : AppColors.divider,
                  ),
                ),
            ],
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Padding(
              padding: const EdgeInsets.only(bottom: 14),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    step.label,
                    style: TextStyle(
                      fontSize: 13,
                      fontWeight: step.done
                          ? FontWeight.w600
                          : FontWeight.w500,
                      color: step.done
                          ? AppColors.textPrimary
                          : AppColors.textSecondary,
                    ),
                  ),
                  if (step.at != null)
                    Padding(
                      padding: const EdgeInsets.only(top: 2),
                      child: Text(
                        _formatDateTime(step.at!),
                        style: const TextStyle(
                          fontSize: 11,
                          color: AppColors.textMuted,
                        ),
                      ),
                    ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _itemRow(TransactionItem item) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            width: 44,
            height: 44,
            decoration: BoxDecoration(
              color: item.colorHex != null
                  ? _colorFromHex(item.colorHex!)
                  : AppColors.blush,
              border: Border.all(color: AppColors.border),
            ),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(item.productName,
                    style: const TextStyle(
                        fontSize: 13, fontWeight: FontWeight.w600)),
                const SizedBox(height: 2),
                Text(
                  '${item.colorName} • ${item.sizeName} • ×${item.quantity}',
                  style: const TextStyle(
                      fontSize: 11, color: AppColors.textMuted),
                ),
              ],
            ),
          ),
          Text(
            formatIdr(item.subtotal),
            style: const TextStyle(
              fontSize: 12,
              fontWeight: FontWeight.w700,
              color: AppColors.primary,
            ),
          ),
        ],
      ),
    );
  }

  Color _colorFromHex(String hex) {
    var cleaned = hex.replaceAll('#', '');
    if (cleaned.length == 6) cleaned = 'FF$cleaned';
    return Color(int.tryParse(cleaned, radix: 16) ?? 0xFFEEEEEE);
  }

  Widget _statusBadge(String status) {
    Color color = AppColors.primary;
    switch (status) {
      case 'selesai':
        color = Colors.green.shade700;
        break;
      case 'batal':
        color = AppColors.discount;
        break;
      case 'dikirim':
        color = Colors.blue.shade700;
        break;
      case 'dikemas':
        color = Colors.orange.shade700;
        break;
    }
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
      decoration: BoxDecoration(
        color: color.withValues(alpha: .1),
        border: Border.all(color: color),
      ),
      child: Text(
        _statusText(status),
        style: TextStyle(
          color: color,
          fontSize: 10,
          fontWeight: FontWeight.w700,
          letterSpacing: 1,
        ),
      ),
    );
  }

  String _statusText(String status) {
    if (status.isEmpty) return '-';
    return status
        .split(' ')
        .map((w) => w.isEmpty ? w : '${w[0].toUpperCase()}${w.substring(1)}')
        .join(' ');
  }

  Widget _card({String? title, required Widget child}) {
    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        border: Border.all(color: AppColors.border),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          if (title != null) ...[
            Text(
              title.toUpperCase(),
              style: const TextStyle(
                fontSize: 11,
                fontWeight: FontWeight.w700,
                letterSpacing: 1.6,
                color: AppColors.primary,
              ),
            ),
            const SizedBox(height: 12),
          ],
          child,
        ],
      ),
    );
  }

  Widget _row(String label, String value,
      {bool emphasized = false, bool copyable = false}) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 110,
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
                fontSize: emphasized ? 14 : 12,
                fontWeight:
                    emphasized ? FontWeight.w700 : FontWeight.w500,
                color: emphasized ? AppColors.primary : AppColors.textPrimary,
              ),
            ),
          ),
          if (copyable)
            InkWell(
              onTap: () {
                Clipboard.setData(ClipboardData(text: value));
                ScaffoldMessenger.of(context).showSnackBar(
                  const SnackBar(content: Text('Disalin.')),
                );
              },
              child: const Padding(
                padding: EdgeInsets.only(left: 4),
                child: Icon(Icons.copy,
                    size: 14, color: AppColors.primarySoft),
              ),
            ),
        ],
      ),
    );
  }

  String _formatDateTime(DateTime d) {
    final local = d.toLocal();
    final date =
        '${local.day.toString().padLeft(2, '0')}/${local.month.toString().padLeft(2, '0')}/${local.year}';
    final time =
        '${local.hour.toString().padLeft(2, '0')}:${local.minute.toString().padLeft(2, '0')}';
    return '$date · $time';
  }
}
