class ProductCategory {
  final int? id;
  final String? name;

  const ProductCategory({this.id, this.name});

  factory ProductCategory.fromJson(dynamic json) {
    if (json is Map<String, dynamic>) {
      return ProductCategory(
        id: json['kategori_id'] as int?,
        name: json['nama_kategori'] as String?,
      );
    }
    if (json is String) return ProductCategory(name: json);
    return const ProductCategory();
  }
}

class ProductMaterial {
  final int? id;
  final String? name;
  final String? description;

  const ProductMaterial({this.id, this.name, this.description});

  factory ProductMaterial.fromJson(Map<String, dynamic>? json) {
    if (json == null) return const ProductMaterial();
    return ProductMaterial(
      id: json['bahan_id'] as int?,
      name: json['nama_bahan'] as String?,
      description: json['deskripsi_bahan'] as String?,
    );
  }
}

class Product {
  final int id;
  final String name;
  final double price;
  final double? discount;
  final double? priceAfterDiscount;
  final int? discountPercent;
  final int stock;
  final bool isBest;
  final String? photoUrl;
  final ProductCategory category;
  final ProductMaterial material;

  const Product({
    required this.id,
    required this.name,
    required this.price,
    this.discount,
    this.priceAfterDiscount,
    this.discountPercent,
    this.stock = 0,
    this.isBest = false,
    this.photoUrl,
    this.category = const ProductCategory(),
    this.material = const ProductMaterial(),
  });

  bool get hasDiscount => discount != null && discount! > 0;
  double get displayPrice => priceAfterDiscount ?? price;

  factory Product.fromJson(Map<String, dynamic> json) {
    return Product(
      id: (json['produk_id'] as num).toInt(),
      name: json['nama_produk'] as String? ?? '',
      price: (json['harga'] as num?)?.toDouble() ?? 0,
      discount: (json['diskon'] as num?)?.toDouble(),
      priceAfterDiscount: (json['harga_setelah_diskon'] as num?)?.toDouble(),
      discountPercent: (json['diskon_persen'] as num?)?.toInt(),
      stock: (json['stok_produk'] as num?)?.toInt() ?? 0,
      isBest: json['is_best'] == true,
      photoUrl: json['foto_utama'] as String?,
      category: ProductCategory.fromJson(json['kategori']),
      material: json['bahan'] is Map<String, dynamic>
          ? ProductMaterial.fromJson(json['bahan'] as Map<String, dynamic>)
          : const ProductMaterial(),
    );
  }
}

class Paginated<T> {
  final List<T> items;
  final int currentPage;
  final int lastPage;
  final int perPage;
  final int total;
  final bool hasMore;

  const Paginated({
    required this.items,
    this.currentPage = 1,
    this.lastPage = 1,
    this.perPage = 10,
    this.total = 0,
    this.hasMore = false,
  });

  factory Paginated.fromJson(
    Map<String, dynamic> json,
    T Function(Map<String, dynamic>) map,
  ) {
    final data = (json['data'] as List? ?? []).cast<Map<String, dynamic>>();
    final pagination = json['pagination'] as Map<String, dynamic>? ?? const {};
    return Paginated(
      items: data.map(map).toList(),
      currentPage: (pagination['current_page'] as num?)?.toInt() ?? 1,
      lastPage: (pagination['last_page'] as num?)?.toInt() ?? 1,
      perPage: (pagination['per_page'] as num?)?.toInt() ?? 10,
      total: (pagination['total'] as num?)?.toInt() ?? data.length,
      hasMore: pagination['has_more'] == true,
    );
  }
}
