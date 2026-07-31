import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../models/cart_item.dart';
import '../models/checkout.dart';
import '../providers/cart_provider.dart';
import '../providers/customer_provider.dart';
import '../services/api_client.dart';
import '../services/checkout_service.dart';
import '../theme/app_colors.dart';
import '../utils/currency.dart';
import '../widgets/brand_buttons.dart';
import '../widgets/loading_placeholders.dart';
import 'order_success_screen.dart';

class CheckoutScreen extends StatefulWidget {
  const CheckoutScreen({super.key});

  @override
  State<CheckoutScreen> createState() => _CheckoutScreenState();
}

class _CheckoutScreenState extends State<CheckoutScreen> {
  late final CheckoutService _service;
  final _formKey = GlobalKey<FormState>();
  final _nameController = TextEditingController();
  final _phoneController = TextEditingController();
  final _emailController = TextEditingController();
  final _addressController = TextEditingController();
  final _voucherController = TextEditingController();

  CheckoutDependencies? _deps;
  bool _loading = true;
  bool _submitting = false;
  bool _validatingVoucher = false;
  String? _error;

  Expedition? _selectedExpedition;
  PaymentOption? _selectedPayment;
  VoucherValidation? _voucher;
  String? _voucherError;

  @override
  void initState() {
    super.initState();
    _service = CheckoutService(context.read<ApiClient>());
    _prefill();
    _load();
  }

  void _prefill() {
    final customer = context.read<CustomerProvider>();
    _nameController.text = customer.name;
    _phoneController.text = customer.phone;
    _emailController.text = customer.email;
    _addressController.text = customer.address;
  }

  @override
  void dispose() {
    _nameController.dispose();
    _phoneController.dispose();
    _emailController.dispose();
    _addressController.dispose();
    _voucherController.dispose();
    super.dispose();
  }

  Future<void> _load() async {
    setState(() {
      _loading = true;
      _error = null;
    });
    try {
      final result = await _service.dependencies();
      if (!mounted) return;
      setState(() {
        _deps = result;
        _loading = false;
        _selectedExpedition =
            result.expeditions.isNotEmpty ? result.expeditions.first : null;
        _selectedPayment = _firstPaymentOption(result);
      });
    } catch (e) {
      if (!mounted) return;
      setState(() {
        _error = e is ApiException ? e.message : e.toString();
        _loading = false;
      });
    }
  }

  PaymentOption? _firstPaymentOption(CheckoutDependencies d) {
    for (final group in d.paymentGroups) {
      if (group.options.isNotEmpty) return group.options.first;
    }
    return null;
  }

  Future<void> _applyVoucher() async {
    final code = _voucherController.text.trim();
    if (code.isEmpty) return;
    final cart = context.read<CartProvider>();
    setState(() {
      _validatingVoucher = true;
      _voucherError = null;
    });
    try {
      final result = await _service.validateVoucher(
        code: code,
        subtotal: cart.subtotal,
      );
      if (!mounted) return;
      setState(() {
        _voucher = result.isValid ? result : null;
        _voucherError = result.isValid ? null : result.message;
        _validatingVoucher = false;
      });
    } catch (e) {
      if (!mounted) return;
      setState(() {
        _voucherError = e is ApiException ? e.message : e.toString();
        _validatingVoucher = false;
      });
    }
  }

  void _clearVoucher() {
    setState(() {
      _voucher = null;
      _voucherError = null;
      _voucherController.clear();
    });
  }

  Future<void> _submit() async {
    if (!(_formKey.currentState?.validate() ?? false)) return;
    if (_selectedPayment == null || _selectedExpedition == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
            content: Text('Pilih metode pembayaran & ekspedisi terlebih dahulu.')),
      );
      return;
    }
    final cart = context.read<CartProvider>();
    if (cart.isEmpty) return;

    setState(() => _submitting = true);
    try {
      final result = await _service.checkout(
        items: cart.items,
        paymentMethodId: _selectedPayment!.id,
        expeditionId: _selectedExpedition!.id,
        voucherCode: _voucher?.code,
        customer: CustomerInfo(
          name: _nameController.text.trim(),
          phone: _phoneController.text.trim(),
          email: _emailController.text.trim().isEmpty
              ? null
              : _emailController.text.trim(),
          address: _addressController.text.trim(),
        ),
      );
      if (!mounted) return;
      await context.read<CustomerProvider>().save(
            name: _nameController.text.trim(),
            phone: _phoneController.text.trim(),
            email: _emailController.text.trim(),
            address: _addressController.text.trim(),
          );
      cart.clear();
      if (!mounted) return;
      Navigator.pushAndRemoveUntil(
        context,
        MaterialPageRoute(
          builder: (_) => OrderSuccessScreen(result: result),
        ),
        (route) => route.isFirst,
      );
    } catch (e) {
      if (!mounted) return;
      final message = e is ApiException ? e.message : e.toString();
      ScaffoldMessenger.of(context)
          .showSnackBar(SnackBar(content: Text(message)));
    } finally {
      if (mounted) setState(() => _submitting = false);
    }
  }

  double get _totalPay {
    final subtotal = context.watch<CartProvider>().subtotal;
    final discount = _voucher?.discountAmount ?? 0;
    return (subtotal - discount).clamp(0, double.infinity);
  }

  @override
  Widget build(BuildContext context) {
    final cart = context.watch<CartProvider>();
    return Scaffold(
      backgroundColor: AppColors.scaffold,
      appBar: AppBar(title: const Text('CHECKOUT')),
      body: _loading
          ? const BrandLoader(label: 'Menyiapkan checkout...')
          : _error != null
              ? ErrorState(message: _error!, onRetry: _load)
              : _buildContent(),
      bottomNavigationBar: cart.isEmpty ? null : _buildBottomBar(),
    );
  }

  Widget _buildContent() {
    final cart = context.watch<CartProvider>();
    return Form(
      key: _formKey,
      child: ListView(
        padding: const EdgeInsets.symmetric(vertical: 16),
        children: [
          _sectionCard(
            title: 'Informasi Pengiriman',
            child: Column(
              children: [
                TextFormField(
                  controller: _nameController,
                  decoration:
                      const InputDecoration(labelText: 'Nama Lengkap *'),
                  validator: (v) =>
                      (v ?? '').trim().isEmpty ? 'Wajib diisi' : null,
                ),
                const SizedBox(height: 12),
                TextFormField(
                  controller: _phoneController,
                  keyboardType: TextInputType.phone,
                  decoration:
                      const InputDecoration(labelText: 'Nomor WhatsApp *'),
                  validator: (v) =>
                      (v ?? '').trim().isEmpty ? 'Wajib diisi' : null,
                ),
                const SizedBox(height: 12),
                TextFormField(
                  controller: _emailController,
                  keyboardType: TextInputType.emailAddress,
                  decoration: const InputDecoration(
                      labelText: 'Email (opsional)',
                      hintText: 'nama@email.com'),
                ),
                const SizedBox(height: 12),
                TextFormField(
                  controller: _addressController,
                  maxLines: 3,
                  decoration: const InputDecoration(
                    labelText: 'Alamat Lengkap *',
                    hintText:
                        'Jalan, kelurahan, kecamatan, kota, kode pos',
                  ),
                  validator: (v) =>
                      (v ?? '').trim().isEmpty ? 'Wajib diisi' : null,
                ),
              ],
            ),
          ),
          if (_deps?.expeditions.isNotEmpty ?? false)
            _sectionCard(
              title: 'Kurir Pengiriman',
              child: Column(
                children: _deps!.expeditions
                    .map((e) => _expeditionTile(e))
                    .toList(),
              ),
            ),
          if (_deps?.paymentGroups.isNotEmpty ?? false)
            _sectionCard(
              title: 'Metode Pembayaran',
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: _deps!.paymentGroups
                    .expand(
                      (group) => [
                        Padding(
                          padding: const EdgeInsets.only(bottom: 8, top: 4),
                          child: Text(
                            group.name.toUpperCase(),
                            style: const TextStyle(
                              fontSize: 10,
                              letterSpacing: 1.6,
                              color: AppColors.textMuted,
                              fontWeight: FontWeight.w700,
                            ),
                          ),
                        ),
                        ...group.options.map(_paymentTile),
                        const SizedBox(height: 12),
                      ],
                    )
                    .toList(),
              ),
            ),
          _sectionCard(
            title: 'Kode Voucher',
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  children: [
                    Expanded(
                      child: TextField(
                        controller: _voucherController,
                        textCapitalization: TextCapitalization.characters,
                        decoration: const InputDecoration(
                          hintText: 'Masukkan kode voucher',
                        ),
                      ),
                    ),
                    const SizedBox(width: 8),
                    SizedBox(
                      height: 46,
                      child: OutlineBrandButton(
                        label:
                            _voucher != null ? 'Ganti' : 'Terapkan',
                        onPressed: _validatingVoucher
                            ? null
                            : (_voucher != null ? _clearVoucher : _applyVoucher),
                      ),
                    ),
                  ],
                ),
                if (_voucher != null) ...[
                  const SizedBox(height: 10),
                  Container(
                    padding: const EdgeInsets.symmetric(
                        horizontal: 10, vertical: 8),
                    color: AppColors.blush,
                    child: Row(
                      children: [
                        const Icon(Icons.local_offer_outlined,
                            size: 16, color: AppColors.primary),
                        const SizedBox(width: 8),
                        Expanded(
                          child: Text(
                            _voucher!.description ?? _voucher!.code,
                            style: const TextStyle(
                              color: AppColors.primary,
                              fontSize: 12,
                              fontWeight: FontWeight.w600,
                            ),
                          ),
                        ),
                        Text(
                          '- ${formatIdr(_voucher!.discountAmount)}',
                          style: const TextStyle(
                            color: AppColors.primary,
                            fontSize: 12,
                            fontWeight: FontWeight.w700,
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
                if (_voucherError != null) ...[
                  const SizedBox(height: 8),
                  Text(
                    _voucherError!,
                    style: const TextStyle(
                        color: AppColors.discount, fontSize: 12),
                  ),
                ],
              ],
            ),
          ),
          _sectionCard(
            title: 'Ringkasan Pesanan',
            child: Column(
              children: [
                ...context
                    .watch<CartProvider>()
                    .items
                    .map(_summaryRow)
                    .toList(),
                const Divider(height: 24),
                _totalRow('Subtotal',
                    formatIdr(context.watch<CartProvider>().subtotal)),
                if (_voucher != null)
                  _totalRow(
                    'Diskon voucher',
                    '- ${formatIdr(_voucher!.discountAmount)}',
                    valueColor: AppColors.discount,
                  ),
                const SizedBox(height: 8),
                _totalRow(
                  'Total Bayar',
                  formatIdr(_totalPay),
                  emphasized: true,
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _expeditionTile(Expedition e) {
    final selected = _selectedExpedition?.id == e.id;
    return InkWell(
      onTap: () => setState(() => _selectedExpedition = e),
      child: Container(
        margin: const EdgeInsets.only(bottom: 8),
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 12),
        decoration: BoxDecoration(
          border: Border.all(
            color: selected ? AppColors.primary : AppColors.border,
            width: selected ? 1.4 : 1,
          ),
        ),
        child: Row(
          children: [
            if (e.iconUrl != null)
              SizedBox(
                width: 40,
                height: 24,
                child: CachedNetworkImage(
                  imageUrl: e.iconUrl!,
                  fit: BoxFit.contain,
                  errorWidget: (_, __, ___) => const SizedBox.shrink(),
                ),
              )
            else
              const Icon(Icons.local_shipping_outlined,
                  color: AppColors.primary, size: 20),
            const SizedBox(width: 12),
            Expanded(
              child: Text(
                e.name,
                style: TextStyle(
                  fontSize: 13,
                  fontWeight:
                      selected ? FontWeight.w700 : FontWeight.w500,
                ),
              ),
            ),
            if (selected)
              const Icon(Icons.check_circle,
                  size: 18, color: AppColors.primary),
          ],
        ),
      ),
    );
  }

  Widget _paymentTile(PaymentOption p) {
    final selected = _selectedPayment?.id == p.id;
    return InkWell(
      onTap: () => setState(() => _selectedPayment = p),
      child: Container(
        margin: const EdgeInsets.only(bottom: 8),
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 12),
        decoration: BoxDecoration(
          border: Border.all(
            color: selected ? AppColors.primary : AppColors.border,
            width: selected ? 1.4 : 1,
          ),
        ),
        child: Row(
          children: [
            if (p.iconUrl != null)
              SizedBox(
                width: 40,
                height: 24,
                child: CachedNetworkImage(
                  imageUrl: p.iconUrl!,
                  fit: BoxFit.contain,
                  errorWidget: (_, __, ___) => const SizedBox.shrink(),
                ),
              )
            else
              const Icon(Icons.account_balance_wallet_outlined,
                  color: AppColors.primary, size: 20),
            const SizedBox(width: 12),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    p.name,
                    style: TextStyle(
                      fontSize: 13,
                      fontWeight:
                          selected ? FontWeight.w700 : FontWeight.w500,
                    ),
                  ),
                  if (p.accountNumber != null && p.accountNumber!.isNotEmpty &&
                      p.codeType != 'image')
                    Padding(
                      padding: const EdgeInsets.only(top: 2),
                      child: Text(
                        '${p.accountNumber}${p.accountHolder != null ? ' • ${p.accountHolder}' : ''}',
                        style: const TextStyle(
                          fontSize: 11,
                          color: AppColors.textMuted,
                        ),
                      ),
                    ),
                ],
              ),
            ),
            Radio<int>(
              value: p.id,
              groupValue: _selectedPayment?.id,
              onChanged: (v) {
                if (v != null) setState(() => _selectedPayment = p);
              },
              activeColor: AppColors.primary,
            ),
          ],
        ),
      ),
    );
  }

  Widget _summaryRow(CartItem item) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        children: [
          Expanded(
            child: Text(
              '${item.name}  ×${item.quantity}',
              maxLines: 2,
              overflow: TextOverflow.ellipsis,
              style: const TextStyle(fontSize: 12),
            ),
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
    );
  }

  Widget _totalRow(String label, String value,
      {bool emphasized = false, Color? valueColor}) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 3),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(
            label,
            style: TextStyle(
              fontSize: emphasized ? 14 : 12,
              color: emphasized
                  ? AppColors.textPrimary
                  : AppColors.textSecondary,
              fontWeight: emphasized ? FontWeight.w700 : FontWeight.w400,
            ),
          ),
          Text(
            value,
            style: TextStyle(
              fontSize: emphasized ? 16 : 12,
              fontWeight: FontWeight.w700,
              color: valueColor ??
                  (emphasized ? AppColors.primary : AppColors.textPrimary),
            ),
          ),
        ],
      ),
    );
  }

  Widget _sectionCard({required String title, required Widget child}) {
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
          Text(
            title.toUpperCase(),
            style: const TextStyle(
              fontSize: 11,
              fontWeight: FontWeight.w700,
              letterSpacing: 1.6,
              color: AppColors.primary,
            ),
          ),
          const SizedBox(height: 14),
          child,
        ],
      ),
    );
  }

  Widget _buildBottomBar() {
    return Container(
      padding: const EdgeInsets.fromLTRB(16, 12, 16, 20),
      decoration: const BoxDecoration(
        color: Colors.white,
        border: Border(top: BorderSide(color: AppColors.divider)),
      ),
      child: SafeArea(
        top: false,
        child: Row(
          children: [
            Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              mainAxisSize: MainAxisSize.min,
              children: [
                const Text('TOTAL BAYAR',
                    style: TextStyle(
                        color: AppColors.textMuted,
                        fontSize: 10,
                        letterSpacing: 1.6)),
                const SizedBox(height: 2),
                Text(
                  formatIdr(_totalPay),
                  style: const TextStyle(
                    color: AppColors.primary,
                    fontSize: 18,
                    fontWeight: FontWeight.w700,
                  ),
                ),
              ],
            ),
            const SizedBox(width: 16),
            Expanded(
              child: PrimaryButton(
                label: 'Buat Pesanan',
                loading: _submitting,
                onPressed: _submit,
              ),
            ),
          ],
        ),
      ),
    );
  }
}
