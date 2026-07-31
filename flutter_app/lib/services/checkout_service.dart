import '../models/cart_item.dart';
import '../models/checkout.dart';
import 'api_client.dart';

class CustomerInfo {
  final String name;
  final String phone;
  final String? email;
  final String address;

  const CustomerInfo({
    required this.name,
    required this.phone,
    this.email,
    required this.address,
  });
}

class CheckoutService {
  final ApiClient _api;
  CheckoutService(this._api);

  Future<CheckoutDependencies> dependencies() async {
    final json = await _api.get('/checkout-dependencies');
    return CheckoutDependencies.fromJson(
        json['data'] as Map<String, dynamic>);
  }

  Future<VoucherValidation> validateVoucher({
    required String code,
    required double subtotal,
  }) async {
    final json = await _api.post('/checkout/validate-voucher', body: {
      'kode_voucher': code,
      'total_belanja': subtotal,
    });
    return VoucherValidation.fromResponse(json);
  }

  Future<CheckoutResult> checkout({
    required List<CartItem> items,
    required int paymentMethodId,
    required int expeditionId,
    required CustomerInfo customer,
    String? voucherCode,
  }) async {
    final json = await _api.post('/checkout', body: {
      'items': items.map((e) => e.toApiItem()).toList(),
      'metode_pembayaran_id': paymentMethodId,
      'ekspedisi_id': expeditionId,
      if (voucherCode != null && voucherCode.isNotEmpty)
        'kode_voucher': voucherCode,
      'nama_customer': customer.name,
      'no_telp': customer.phone,
      if (customer.email != null && customer.email!.isNotEmpty)
        'email': customer.email,
      'alamat': customer.address,
    });
    return CheckoutResult.fromJson(json);
  }
}
