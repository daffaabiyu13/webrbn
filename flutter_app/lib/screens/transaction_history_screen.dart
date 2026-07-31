import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../models/transaction.dart';
import '../providers/customer_provider.dart';
import '../services/api_client.dart';
import '../services/transaction_service.dart';
import '../theme/app_colors.dart';
import '../utils/currency.dart';
import '../widgets/brand_buttons.dart';
import '../widgets/loading_placeholders.dart';
import 'transaction_detail_screen.dart';

class TransactionHistoryScreen extends StatefulWidget {
  const TransactionHistoryScreen({super.key});

  @override
  State<TransactionHistoryScreen> createState() =>
      _TransactionHistoryScreenState();
}

class _TransactionHistoryScreenState extends State<TransactionHistoryScreen> {
  late final TransactionService _service;
  final _emailController = TextEditingController();
  final _invoiceController = TextEditingController();

  List<TransactionSummary>? _items;
  bool _loading = false;
  String? _error;

  @override
  void initState() {
    super.initState();
    _service = TransactionService(context.read<ApiClient>());
    final customer = context.read<CustomerProvider>();
    _emailController.text = customer.email;
    if (customer.email.isNotEmpty) {
      _fetch();
    }
  }

  @override
  void dispose() {
    _emailController.dispose();
    _invoiceController.dispose();
    super.dispose();
  }

  Future<void> _fetch() async {
    final email = _emailController.text.trim();
    if (email.isEmpty) {
      setState(() => _error = 'Masukkan email untuk melihat riwayat pesanan.');
      return;
    }
    setState(() {
      _loading = true;
      _error = null;
    });
    try {
      final result = await _service.byEmail(email);
      if (!mounted) return;
      setState(() {
        _items = result;
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

  void _openByInvoice() {
    final invoice = _invoiceController.text.trim();
    if (invoice.isEmpty) return;
    Navigator.push(
      context,
      MaterialPageRoute(
        builder: (_) => TransactionDetailScreen(invoice: invoice),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.scaffold,
      appBar: AppBar(
        automaticallyImplyLeading: false,
        title: const Text('PESANAN SAYA'),
      ),
      body: RefreshIndicator(
        color: AppColors.primary,
        onRefresh: _fetch,
        child: ListView(
          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 16),
          children: [
            _cardBlock(
              title: 'Cek Cepat via Kode Invoice',
              child: Row(
                children: [
                  Expanded(
                    child: TextField(
                      controller: _invoiceController,
                      textCapitalization: TextCapitalization.characters,
                      decoration:
                          const InputDecoration(hintText: 'INV-2025-XXXX'),
                    ),
                  ),
                  const SizedBox(width: 8),
                  SizedBox(
                    height: 46,
                    child: PrimaryButton(
                      label: 'Cek',
                      expand: false,
                      onPressed: _openByInvoice,
                    ),
                  ),
                ],
              ),
            ),
            _cardBlock(
              title: 'Riwayat Berdasarkan Email',
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.stretch,
                children: [
                  TextField(
                    controller: _emailController,
                    keyboardType: TextInputType.emailAddress,
                    decoration: const InputDecoration(
                      hintText: 'Email yang digunakan saat checkout',
                    ),
                  ),
                  const SizedBox(height: 10),
                  OutlineBrandButton(
                    label: 'Cari Riwayat',
                    onPressed: _loading ? null : _fetch,
                  ),
                ],
              ),
            ),
            const SizedBox(height: 8),
            if (_loading)
              const Padding(
                padding: EdgeInsets.only(top: 40),
                child: BrandLoader(),
              )
            else if (_error != null)
              Padding(
                padding: const EdgeInsets.symmetric(vertical: 24),
                child: Text(
                  _error!,
                  textAlign: TextAlign.center,
                  style: const TextStyle(
                    color: AppColors.textSecondary,
                    fontSize: 12,
                  ),
                ),
              )
            else if (_items == null)
              const Padding(
                padding: EdgeInsets.only(top: 40),
                child: EmptyState(
                  icon: Icons.receipt_long_outlined,
                  title: 'Belum ada riwayat',
                  subtitle:
                      'Masukkan email checkout kamu untuk melihat pesanan.',
                ),
              )
            else if (_items!.isEmpty)
              const Padding(
                padding: EdgeInsets.only(top: 40),
                child: EmptyState(
                  icon: Icons.receipt_long_outlined,
                  title: 'Belum ada pesanan',
                  subtitle: 'Pesanan baru kamu akan tampil di sini.',
                ),
              )
            else
              ..._items!.map(_summaryCard),
          ],
        ),
      ),
    );
  }

  Widget _summaryCard(TransactionSummary tx) {
    return InkWell(
      onTap: () {
        Navigator.push(
          context,
          MaterialPageRoute(
            builder: (_) => TransactionDetailScreen(invoice: tx.invoice),
          ),
        );
      },
      child: Container(
        margin: const EdgeInsets.symmetric(vertical: 6),
        padding: const EdgeInsets.all(14),
        decoration: BoxDecoration(
          color: Colors.white,
          border: Border.all(color: AppColors.border),
        ),
        child: Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            SizedBox(
              width: 60,
              height: 76,
              child: tx.previewPhoto != null
                  ? CachedNetworkImage(
                      imageUrl: tx.previewPhoto!,
                      fit: BoxFit.cover,
                      errorWidget: (_, __, ___) =>
                          Container(color: AppColors.blush),
                    )
                  : Container(color: AppColors.blush),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      Expanded(
                        child: Text(
                          tx.invoice,
                          style: const TextStyle(
                            fontSize: 12,
                            color: AppColors.primary,
                            fontWeight: FontWeight.w700,
                            letterSpacing: 1,
                          ),
                        ),
                      ),
                      Text(
                        _label(tx.transactionStatus),
                        style: const TextStyle(
                          fontSize: 10,
                          color: AppColors.textSecondary,
                          fontWeight: FontWeight.w600,
                          letterSpacing: 0.8,
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 4),
                  Text(
                    tx.previewProductName ?? 'Pesanan',
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                    style: const TextStyle(
                      fontSize: 13,
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    '${tx.itemCount} item • ${formatIdr(tx.total)}',
                    style: const TextStyle(
                      fontSize: 11,
                      color: AppColors.textMuted,
                    ),
                  ),
                ],
              ),
            ),
            const Icon(Icons.chevron_right,
                color: AppColors.textMuted, size: 18),
          ],
        ),
      ),
    );
  }

  String _label(String v) {
    if (v.isEmpty) return '-';
    return v
        .split(' ')
        .map((w) => w.isEmpty ? w : '${w[0].toUpperCase()}${w.substring(1)}')
        .join(' ');
  }

  Widget _cardBlock({required String title, required Widget child}) {
    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: Colors.white,
        border: Border.all(color: AppColors.border),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
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
          child,
        ],
      ),
    );
  }
}
