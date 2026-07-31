class PaymentOption {
  final int id;
  final String name;
  final String? accountNumber;
  final String? accountHolder;
  final String? codeType;
  final String? iconUrl;

  const PaymentOption({
    required this.id,
    required this.name,
    this.accountNumber,
    this.accountHolder,
    this.codeType,
    this.iconUrl,
  });

  factory PaymentOption.fromJson(Map<String, dynamic> json) => PaymentOption(
        id: (json['metode_pembayaran_id'] as num).toInt(),
        name: json['nama_pembayaran'] as String? ?? '',
        accountNumber: json['kode_bayar'] as String?,
        accountHolder: json['atas_nama'] as String?,
        codeType: json['tipe_kode_bayar'] as String?,
        iconUrl: json['icon'] as String?,
      );
}

class PaymentMethodGroup {
  final int id;
  final String name;
  final List<PaymentOption> options;

  const PaymentMethodGroup({
    required this.id,
    required this.name,
    this.options = const [],
  });

  factory PaymentMethodGroup.fromJson(Map<String, dynamic> json) =>
      PaymentMethodGroup(
        id: (json['metode_id'] as num).toInt(),
        name: json['nama_metode'] as String? ?? '',
        options: (json['pembayaran'] as List? ?? [])
            .cast<Map<String, dynamic>>()
            .map(PaymentOption.fromJson)
            .toList(),
      );
}

class Expedition {
  final int id;
  final String name;
  final String? iconUrl;

  const Expedition({required this.id, required this.name, this.iconUrl});

  factory Expedition.fromJson(Map<String, dynamic> json) => Expedition(
        id: (json['ekspedisi_id'] as num).toInt(),
        name: json['nama_ekspedisi'] as String? ?? '',
        iconUrl: json['icon'] as String?,
      );
}

class CheckoutDependencies {
  final List<PaymentMethodGroup> paymentGroups;
  final List<Expedition> expeditions;

  const CheckoutDependencies({
    this.paymentGroups = const [],
    this.expeditions = const [],
  });

  factory CheckoutDependencies.fromJson(Map<String, dynamic> json) =>
      CheckoutDependencies(
        paymentGroups: (json['metode_pembayaran'] as List? ?? [])
            .cast<Map<String, dynamic>>()
            .map(PaymentMethodGroup.fromJson)
            .toList(),
        expeditions: (json['ekspedisi'] as List? ?? [])
            .cast<Map<String, dynamic>>()
            .map(Expedition.fromJson)
            .toList(),
      );
}

class VoucherValidation {
  final String code;
  final String? description;
  final String? discountType;
  final double discountValue;
  final double discountAmount;
  final bool isValid;
  final String message;

  const VoucherValidation({
    required this.code,
    this.description,
    this.discountType,
    this.discountValue = 0,
    this.discountAmount = 0,
    this.isValid = false,
    this.message = '',
  });

  factory VoucherValidation.fromResponse(Map<String, dynamic> json) {
    final data = json['data'] as Map<String, dynamic>? ?? const {};
    return VoucherValidation(
      code: data['kode_voucher'] as String? ?? '',
      description: data['deskripsi'] as String?,
      discountType: data['tipe_diskon'] as String?,
      discountValue: (data['nilai_diskon'] as num?)?.toDouble() ?? 0,
      discountAmount: (data['potongan'] as num?)?.toDouble() ?? 0,
      isValid: data['is_valid'] == true,
      message: json['message'] as String? ?? '',
    );
  }
}

class CheckoutResult {
  final String invoice;
  final int transactionId;
  final double totalAmount;
  final int itemsCount;
  final double discount;
  final String paymentStatus;
  final String transactionStatus;
  final String? paymentName;
  final String? paymentAccount;
  final String? paymentAccountHolder;
  final DateTime? deadline;

  const CheckoutResult({
    required this.invoice,
    required this.transactionId,
    required this.totalAmount,
    this.itemsCount = 0,
    this.discount = 0,
    this.paymentStatus = '',
    this.transactionStatus = '',
    this.paymentName,
    this.paymentAccount,
    this.paymentAccountHolder,
    this.deadline,
  });

  factory CheckoutResult.fromJson(Map<String, dynamic> json) {
    final data = json['data'] as Map<String, dynamic>? ?? const {};
    final status = data['status'] as Map<String, dynamic>? ?? const {};
    final metode = data['metode_pembayaran'] as Map<String, dynamic>? ?? const {};
    return CheckoutResult(
      invoice: data['kode_invoice'] as String? ?? '',
      transactionId: (data['transaksi_id'] as num?)?.toInt() ?? 0,
      totalAmount: (data['total_bayar'] as num?)?.toDouble() ?? 0,
      itemsCount: (data['jumlah_produk'] as num?)?.toInt() ?? 0,
      discount: (data['diskon'] as num?)?.toDouble() ?? 0,
      paymentStatus: status['pembayaran'] as String? ?? '',
      transactionStatus: status['transaksi'] as String? ?? '',
      paymentName: metode['nama'] as String?,
      paymentAccount: metode['kode_bayar'] as String?,
      paymentAccountHolder: metode['atas_nama'] as String?,
      deadline: data['batas_waktu'] != null
          ? DateTime.tryParse(data['batas_waktu'] as String)
          : null,
    );
  }
}
