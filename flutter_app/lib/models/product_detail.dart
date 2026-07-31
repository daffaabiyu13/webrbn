import 'product.dart';

class ProductPhoto {
  final int id;
  final String url;
  final bool isPrimary;

  const ProductPhoto({
    required this.id,
    required this.url,
    this.isPrimary = false,
  });

  factory ProductPhoto.fromJson(Map<String, dynamic> json) => ProductPhoto(
        id: (json['foto_produk_id'] as num).toInt(),
        url: json['url'] as String? ?? '',
        isPrimary: json['is_utama'] == true,
      );
}

class ProductColor {
  final int id;
  final String name;
  final String? hex;

  const ProductColor({required this.id, required this.name, this.hex});

  factory ProductColor.fromJson(Map<String, dynamic> json) => ProductColor(
        id: (json['warna_id'] as num).toInt(),
        name: json['nama_warna'] as String? ?? '',
        hex: json['kode_hex'] as String?,
      );
}

class ProductSize {
  final int id;
  final String name;
  final String? description;

  const ProductSize({required this.id, required this.name, this.description});

  factory ProductSize.fromJson(Map<String, dynamic> json) => ProductSize(
        id: (json['ukuran_id'] as num).toInt(),
        name: json['nama_ukuran'] as String? ?? '',
        description: json['deskripsi'] as String?,
      );
}

class ProductDetail {
  final int id;
  final String name;
  final String? description;
  final double price;
  final double? discount;
  final double? priceAfterDiscount;
  final int? discountPercent;
  final int stock;
  final bool isBest;
  final ProductCategory category;
  final ProductMaterial material;
  final List<ProductPhoto> photos;
  final List<ProductColor> colors;
  final List<ProductSize> sizes;

  const ProductDetail({
    required this.id,
    required this.name,
    this.description,
    required this.price,
    this.discount,
    this.priceAfterDiscount,
    this.discountPercent,
    this.stock = 0,
    this.isBest = false,
    this.category = const ProductCategory(),
    this.material = const ProductMaterial(),
    this.photos = const [],
    this.colors = const [],
    this.sizes = const [],
  });

  bool get hasDiscount => discount != null && discount! > 0;
  double get displayPrice => priceAfterDiscount ?? price;
  String? get primaryPhoto {
    if (photos.isEmpty) return null;
    return photos.firstWhere((p) => p.isPrimary, orElse: () => photos.first).url;
  }

  factory ProductDetail.fromJson(Map<String, dynamic> json) {
    return ProductDetail(
      id: (json['produk_id'] as num).toInt(),
      name: json['nama_produk'] as String? ?? '',
      description: json['deskripsi'] as String?,
      price: (json['harga'] as num?)?.toDouble() ?? 0,
      discount: (json['diskon'] as num?)?.toDouble(),
      priceAfterDiscount: (json['harga_setelah_diskon'] as num?)?.toDouble(),
      discountPercent: (json['diskon_persen'] as num?)?.toInt(),
      stock: (json['stok_produk'] as num?)?.toInt() ?? 0,
      isBest: json['is_best'] == true,
      category: ProductCategory.fromJson(json['kategori']),
      material: json['bahan'] is Map<String, dynamic>
          ? ProductMaterial.fromJson(json['bahan'] as Map<String, dynamic>)
          : const ProductMaterial(),
      photos: (json['foto'] as List? ?? [])
          .cast<Map<String, dynamic>>()
          .map(ProductPhoto.fromJson)
          .toList(),
      colors: (json['warna'] as List? ?? [])
          .cast<Map<String, dynamic>>()
          .map(ProductColor.fromJson)
          .toList(),
      sizes: (json['ukuran'] as List? ?? [])
          .cast<Map<String, dynamic>>()
          .map(ProductSize.fromJson)
          .toList(),
    );
  }
}
