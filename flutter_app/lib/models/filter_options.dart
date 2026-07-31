import 'product_detail.dart';

class FilterCategory {
  final int id;
  final String name;
  const FilterCategory({required this.id, required this.name});
  factory FilterCategory.fromJson(Map<String, dynamic> json) => FilterCategory(
        id: (json['kategori_id'] as num).toInt(),
        name: json['nama_kategori'] as String? ?? '',
      );
}

class FilterMaterial {
  final int id;
  final String name;
  const FilterMaterial({required this.id, required this.name});
  factory FilterMaterial.fromJson(Map<String, dynamic> json) => FilterMaterial(
        id: (json['bahan_id'] as num).toInt(),
        name: json['nama_bahan'] as String? ?? '',
      );
}

class SortOption {
  final String value;
  final String label;
  const SortOption({required this.value, required this.label});
  factory SortOption.fromJson(Map<String, dynamic> json) => SortOption(
        value: json['value'] as String,
        label: json['label'] as String,
      );
}

class FilterOptions {
  final List<FilterCategory> categories;
  final List<FilterMaterial> materials;
  final List<ProductColor> colors;
  final List<ProductSize> sizes;
  final double minPrice;
  final double maxPrice;
  final List<SortOption> sortOptions;

  const FilterOptions({
    this.categories = const [],
    this.materials = const [],
    this.colors = const [],
    this.sizes = const [],
    this.minPrice = 0,
    this.maxPrice = 0,
    this.sortOptions = const [],
  });

  factory FilterOptions.fromJson(Map<String, dynamic> json) {
    final harga = json['harga'] as Map<String, dynamic>? ?? const {};
    return FilterOptions(
      categories: (json['kategori'] as List? ?? [])
          .cast<Map<String, dynamic>>()
          .map(FilterCategory.fromJson)
          .toList(),
      materials: (json['bahan'] as List? ?? [])
          .cast<Map<String, dynamic>>()
          .map(FilterMaterial.fromJson)
          .toList(),
      colors: (json['warna'] as List? ?? [])
          .cast<Map<String, dynamic>>()
          .map(ProductColor.fromJson)
          .toList(),
      sizes: (json['ukuran'] as List? ?? [])
          .cast<Map<String, dynamic>>()
          .map(ProductSize.fromJson)
          .toList(),
      minPrice: (harga['min'] as num?)?.toDouble() ?? 0,
      maxPrice: (harga['max'] as num?)?.toDouble() ?? 0,
      sortOptions: (json['sort_options'] as List? ?? [])
          .cast<Map<String, dynamic>>()
          .map(SortOption.fromJson)
          .toList(),
    );
  }
}
