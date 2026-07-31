import '../models/filter_options.dart';
import '../models/product.dart';
import '../models/product_detail.dart';
import 'api_client.dart';

class ProductQuery {
  final int page;
  final int perPage;
  final String? search;
  final List<int>? categoryIds;
  final List<int>? materialIds;
  final List<int>? colorIds;
  final List<int>? sizeIds;
  final double? minPrice;
  final double? maxPrice;
  final bool? hasStock;
  final bool? isBest;
  final String sortBy;
  final String sortOrder;

  const ProductQuery({
    this.page = 1,
    this.perPage = 12,
    this.search,
    this.categoryIds,
    this.materialIds,
    this.colorIds,
    this.sizeIds,
    this.minPrice,
    this.maxPrice,
    this.hasStock,
    this.isBest,
    this.sortBy = 'terbaru',
    this.sortOrder = 'desc',
  });

  Map<String, dynamic> toQuery() {
    return {
      'page': page,
      'per_page': perPage,
      if (search != null && search!.isNotEmpty) 'search': search,
      if (categoryIds != null && categoryIds!.isNotEmpty)
        'kategori_id': categoryIds,
      if (materialIds != null && materialIds!.isNotEmpty)
        'bahan_id': materialIds,
      if (colorIds != null && colorIds!.isNotEmpty) 'warna_id': colorIds,
      if (sizeIds != null && sizeIds!.isNotEmpty) 'ukuran_id': sizeIds,
      if (minPrice != null) 'min_price': minPrice,
      if (maxPrice != null) 'max_price': maxPrice,
      if (hasStock != null) 'has_stock': hasStock,
      if (isBest != null) 'is_best': isBest,
      'sort_by': sortBy,
      'sort_order': sortOrder,
    };
  }
}

class ProductService {
  final ApiClient _api;
  ProductService(this._api);

  Future<Paginated<Product>> list(ProductQuery query) async {
    final json = await _api.get('/products', query: query.toQuery());
    return Paginated.fromJson(json, Product.fromJson);
  }

  Future<List<Product>> best({int perPage = 8}) async {
    final json =
        await _api.get('/products/best', query: {'per_page': perPage});
    final data = (json['data'] as List? ?? []).cast<Map<String, dynamic>>();
    return data.map(Product.fromJson).toList();
  }

  Future<ProductDetail> detail(int id) async {
    final json = await _api.get('/products/$id');
    return ProductDetail.fromJson(json['data'] as Map<String, dynamic>);
  }

  Future<FilterOptions> filters() async {
    final json = await _api.get('/products/filters');
    return FilterOptions.fromJson(json['data'] as Map<String, dynamic>);
  }
}
