class TransactionSummary {
  final String invoice;
  final int transactionId;
  final DateTime? orderedAt;
  final String paymentStatus;
  final String transactionStatus;
  final int itemCount;
  final double total;
  final String? previewProductName;
  final String? previewPhoto;

  const TransactionSummary({
    required this.invoice,
    required this.transactionId,
    this.orderedAt,
    this.paymentStatus = '',
    this.transactionStatus = '',
    this.itemCount = 0,
    this.total = 0,
    this.previewProductName,
    this.previewPhoto,
  });

  factory TransactionSummary.fromJson(Map<String, dynamic> json) {
    final status = json['status'] as Map<String, dynamic>? ?? const {};
    final preview = json['preview'] as Map<String, dynamic>? ?? const {};
    return TransactionSummary(
      invoice: json['kode_invoice'] as String? ?? '',
      transactionId: (json['transaksi_id'] as num?)?.toInt() ?? 0,
      orderedAt: json['tanggal_pesan'] != null
          ? DateTime.tryParse(json['tanggal_pesan'] as String)
          : null,
      paymentStatus: status['pembayaran'] as String? ?? '',
      transactionStatus: status['transaksi'] as String? ?? '',
      itemCount: (json['jumlah_produk'] as num?)?.toInt() ?? 0,
      total: (json['total_bayar'] as num?)?.toDouble() ?? 0,
      previewProductName: preview['nama_produk'] as String?,
      previewPhoto: preview['foto'] as String?,
    );
  }
}

class TimelineStep {
  final String status;
  final String label;
  final DateTime? at;
  final bool done;

  const TimelineStep({
    required this.status,
    required this.label,
    this.at,
    this.done = false,
  });

  factory TimelineStep.fromJson(Map<String, dynamic> json) => TimelineStep(
        status: json['status'] as String? ?? '',
        label: json['label'] as String? ?? '',
        at: json['tanggal'] != null
            ? DateTime.tryParse(json['tanggal'] as String)
            : null,
        done: json['selesai'] == true,
      );
}

class TransactionItem {
  final int detailId;
  final int productId;
  final String productName;
  final double price;
  final double? priceAfterDiscount;
  final String sizeName;
  final String colorName;
  final String? colorHex;
  final int quantity;
  final double subtotal;

  const TransactionItem({
    required this.detailId,
    required this.productId,
    required this.productName,
    this.price = 0,
    this.priceAfterDiscount,
    this.sizeName = '',
    this.colorName = '',
    this.colorHex,
    this.quantity = 1,
    this.subtotal = 0,
  });

  factory TransactionItem.fromJson(Map<String, dynamic> json) {
    final produk = json['produk'] as Map<String, dynamic>? ?? const {};
    final ukuran = json['ukuran'] as Map<String, dynamic>? ?? const {};
    final warna = json['warna'] as Map<String, dynamic>? ?? const {};
    return TransactionItem(
      detailId: (json['detail_id'] as num?)?.toInt() ?? 0,
      productId: (produk['produk_id'] as num?)?.toInt() ?? 0,
      productName: produk['nama_produk'] as String? ?? '',
      price: (produk['harga'] as num?)?.toDouble() ?? 0,
      priceAfterDiscount: (produk['harga_diskon'] as num?)?.toDouble(),
      sizeName: ukuran['nama_ukuran'] as String? ?? '',
      colorName: warna['nama_warna'] as String? ?? '',
      colorHex: warna['kode_hex'] as String?,
      quantity: (json['jumlah'] as num?)?.toInt() ?? 1,
      subtotal: (json['subtotal'] as num?)?.toDouble() ?? 0,
    );
  }
}

class TransactionDetail {
  final String invoice;
  final int transactionId;
  final DateTime? orderedAt;
  final String paymentStatus;
  final String transactionStatus;
  final String customerName;
  final String phone;
  final String? email;
  final String address;
  final String? expeditionName;
  final int quantity;
  final double totalAmount;
  final String? paymentProofUrl;
  final String? paymentName;
  final String? paymentAccount;
  final String? paymentAccountHolder;
  final String? paymentGroup;
  final List<TransactionItem> items;
  final List<TimelineStep> timeline;

  const TransactionDetail({
    required this.invoice,
    required this.transactionId,
    this.orderedAt,
    this.paymentStatus = '',
    this.transactionStatus = '',
    this.customerName = '',
    this.phone = '',
    this.email,
    this.address = '',
    this.expeditionName,
    this.quantity = 0,
    this.totalAmount = 0,
    this.paymentProofUrl,
    this.paymentName,
    this.paymentAccount,
    this.paymentAccountHolder,
    this.paymentGroup,
    this.items = const [],
    this.timeline = const [],
  });

  factory TransactionDetail.fromJson(Map<String, dynamic> json) {
    final status = json['status'] as Map<String, dynamic>? ?? const {};
    final customer = json['customer'] as Map<String, dynamic>? ?? const {};
    final ekspedisi = json['ekspedisi'] as Map<String, dynamic>? ?? const {};
    final pembayaran = json['pembayaran'] as Map<String, dynamic>? ?? const {};
    final metode = pembayaran['metode'] as Map<String, dynamic>? ?? const {};

    return TransactionDetail(
      invoice: json['kode_invoice'] as String? ?? '',
      transactionId: (json['transaksi_id'] as num?)?.toInt() ?? 0,
      orderedAt: json['tanggal_pesan'] != null
          ? DateTime.tryParse(json['tanggal_pesan'] as String)
          : null,
      paymentStatus: status['pembayaran'] as String? ?? '',
      transactionStatus: status['transaksi'] as String? ?? '',
      customerName: customer['nama'] as String? ?? '',
      phone: customer['no_telp'] as String? ?? '',
      email: customer['email'] as String?,
      address: customer['alamat'] as String? ?? '',
      expeditionName: ekspedisi['nama_ekspedisi'] as String?,
      quantity: (pembayaran['jumlah_produk'] as num?)?.toInt() ?? 0,
      totalAmount: (pembayaran['total_harga'] as num?)?.toDouble() ?? 0,
      paymentProofUrl: pembayaran['bukti_pembayaran'] as String?,
      paymentName: metode['nama_pembayaran'] as String?,
      paymentAccount: metode['kode_bayar'] as String?,
      paymentAccountHolder: metode['atas_nama'] as String?,
      paymentGroup: metode['tipe'] as String?,
      items: (json['items'] as List? ?? [])
          .cast<Map<String, dynamic>>()
          .map(TransactionItem.fromJson)
          .toList(),
      timeline: (json['timeline'] as List? ?? [])
          .cast<Map<String, dynamic>>()
          .map(TimelineStep.fromJson)
          .toList(),
    );
  }
}
