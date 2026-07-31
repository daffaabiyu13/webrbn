import '../models/transaction.dart';
import 'api_client.dart';

class TransactionService {
  final ApiClient _api;
  TransactionService(this._api);

  Future<List<TransactionSummary>> byEmail(String email,
      {String? status, int perPage = 20}) async {
    final json = await _api.get('/transactions', query: {
      'email': email,
      'per_page': perPage,
      if (status != null) 'status': status,
    });
    final data = (json['data'] as List? ?? []).cast<Map<String, dynamic>>();
    return data.map(TransactionSummary.fromJson).toList();
  }

  Future<TransactionDetail> detail(String invoice) async {
    final json = await _api.get('/transactions/$invoice');
    return TransactionDetail.fromJson(json['data'] as Map<String, dynamic>);
  }

  Future<String> uploadPayment({
    required String invoice,
    required List<int> bytes,
    required String filename,
  }) async {
    final json = await _api.multipart(
      '/transactions/$invoice/upload-payment',
      field: 'bukti_pembayaran',
      filename: filename,
      bytes: bytes,
    );
    final data = json['data'] as Map<String, dynamic>? ?? const {};
    return data['bukti_pembayaran_url'] as String? ?? '';
  }

  Future<void> cancel(String invoice) async {
    await _api.post('/transactions/$invoice/cancel');
  }
}
