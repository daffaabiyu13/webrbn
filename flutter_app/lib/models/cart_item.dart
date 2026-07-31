import 'product_detail.dart';

class CartItem {
  final int productId;
  final String name;
  final String? photoUrl;
  final double unitPrice;
  final double? originalPrice;
  final int sizeId;
  final String sizeName;
  final int colorId;
  final String colorName;
  final String? colorHex;
  int quantity;

  CartItem({
    required this.productId,
    required this.name,
    this.photoUrl,
    required this.unitPrice,
    this.originalPrice,
    required this.sizeId,
    required this.sizeName,
    required this.colorId,
    required this.colorName,
    this.colorHex,
    this.quantity = 1,
  });

  double get subtotal => unitPrice * quantity;

  String get compositeKey => '$productId-$sizeId-$colorId';

  factory CartItem.fromProduct({
    required ProductDetail product,
    required ProductSize size,
    required ProductColor color,
    int quantity = 1,
  }) {
    return CartItem(
      productId: product.id,
      name: product.name,
      photoUrl: product.primaryPhoto,
      unitPrice: product.displayPrice,
      originalPrice: product.hasDiscount ? product.price : null,
      sizeId: size.id,
      sizeName: size.name,
      colorId: color.id,
      colorName: color.name,
      colorHex: color.hex,
      quantity: quantity,
    );
  }

  Map<String, dynamic> toJson() => {
        'produk_id': productId,
        'name': name,
        'photo_url': photoUrl,
        'unit_price': unitPrice,
        'original_price': originalPrice,
        'ukuran_id': sizeId,
        'size_name': sizeName,
        'warna_id': colorId,
        'color_name': colorName,
        'color_hex': colorHex,
        'jumlah': quantity,
      };

  factory CartItem.fromJson(Map<String, dynamic> json) => CartItem(
        productId: (json['produk_id'] as num).toInt(),
        name: json['name'] as String? ?? '',
        photoUrl: json['photo_url'] as String?,
        unitPrice: (json['unit_price'] as num?)?.toDouble() ?? 0,
        originalPrice: (json['original_price'] as num?)?.toDouble(),
        sizeId: (json['ukuran_id'] as num).toInt(),
        sizeName: json['size_name'] as String? ?? '',
        colorId: (json['warna_id'] as num).toInt(),
        colorName: json['color_name'] as String? ?? '',
        colorHex: json['color_hex'] as String?,
        quantity: (json['jumlah'] as num?)?.toInt() ?? 1,
      );

  /// Checkout API item payload.
  Map<String, dynamic> toApiItem() => {
        'produk_id': productId,
        'jumlah': quantity,
        'ukuran_id': sizeId,
        'warna_id': colorId,
      };
}
