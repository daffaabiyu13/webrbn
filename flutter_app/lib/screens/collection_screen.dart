import 'dart:async';

import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../models/filter_options.dart';
import '../models/product.dart';
import '../services/api_client.dart';
import '../services/product_service.dart';
import '../theme/app_colors.dart';
import '../utils/currency.dart';
import '../widgets/brand_buttons.dart';
import '../widgets/loading_placeholders.dart';
import '../widgets/product_card.dart';
import 'product_detail_screen.dart';

class CollectionScreen extends StatefulWidget {
  final bool initialBestOnly;
  final bool autofocusSearch;

  const CollectionScreen({
    super.key,
    this.initialBestOnly = false,
    this.autofocusSearch = false,
  });

  @override
  State<CollectionScreen> createState() => _CollectionScreenState();
}

class _CollectionScreenState extends State<CollectionScreen> {
  late final ProductService _service;
  final _searchController = TextEditingController();
  final _scrollController = ScrollController();
  Timer? _searchDebounce;

  FilterOptions? _filters;
  final List<Product> _products = [];
  int _page = 1;
  int _lastPage = 1;
  bool _loading = false;
  bool _loadingMore = false;
  String? _error;

  final Set<int> _categoryIds = {};
  final Set<int> _materialIds = {};
  final Set<int> _colorIds = {};
  final Set<int> _sizeIds = {};
  double? _minPrice;
  double? _maxPrice;
  bool _hasStock = false;
  bool _bestOnly = false;
  String _sortBy = 'terbaru';
  String _sortOrder = 'desc';

  @override
  void initState() {
    super.initState();
    _service = ProductService(context.read<ApiClient>());
    _bestOnly = widget.initialBestOnly;
    _load(initial: true);
    _fetchFilters();
    _scrollController.addListener(_onScroll);
  }

  @override
  void dispose() {
    _searchController.dispose();
    _scrollController.dispose();
    _searchDebounce?.cancel();
    super.dispose();
  }

  Future<void> _fetchFilters() async {
    try {
      final result = await _service.filters();
      if (!mounted) return;
      setState(() => _filters = result);
    } catch (_) {
      // Silent — filters are optional
    }
  }

  ProductQuery _buildQuery(int page) => ProductQuery(
        page: page,
        perPage: 12,
        search: _searchController.text.trim().isEmpty
            ? null
            : _searchController.text.trim(),
        categoryIds: _categoryIds.isEmpty ? null : _categoryIds.toList(),
        materialIds: _materialIds.isEmpty ? null : _materialIds.toList(),
        colorIds: _colorIds.isEmpty ? null : _colorIds.toList(),
        sizeIds: _sizeIds.isEmpty ? null : _sizeIds.toList(),
        minPrice: _minPrice,
        maxPrice: _maxPrice,
        hasStock: _hasStock ? true : null,
        isBest: _bestOnly ? true : null,
        sortBy: _sortBy,
        sortOrder: _sortOrder,
      );

  Future<void> _load({bool initial = false}) async {
    if (initial) {
      setState(() {
        _loading = true;
        _error = null;
        _page = 1;
        _products.clear();
      });
    }
    try {
      final result = await _service.list(_buildQuery(1));
      if (!mounted) return;
      setState(() {
        _products
          ..clear()
          ..addAll(result.items);
        _page = result.currentPage;
        _lastPage = result.lastPage;
        _loading = false;
      });
    } catch (e) {
      if (!mounted) return;
      setState(() {
        _error = e is ApiException ? e.message : e.toString();
        _loading = false;
      });
    }
  }

  Future<void> _loadMore() async {
    if (_loadingMore || _page >= _lastPage) return;
    setState(() => _loadingMore = true);
    try {
      final result = await _service.list(_buildQuery(_page + 1));
      if (!mounted) return;
      setState(() {
        _products.addAll(result.items);
        _page = result.currentPage;
        _lastPage = result.lastPage;
        _loadingMore = false;
      });
    } catch (_) {
      if (!mounted) return;
      setState(() => _loadingMore = false);
    }
  }

  void _onScroll() {
    if (_scrollController.position.pixels >=
        _scrollController.position.maxScrollExtent - 300) {
      _loadMore();
    }
  }

  void _onSearchChanged(String value) {
    _searchDebounce?.cancel();
    _searchDebounce = Timer(const Duration(milliseconds: 400), () {
      _load(initial: true);
    });
  }

  Future<void> _openFilters() async {
    if (_filters == null) return;
    final applied = await showModalBottomSheet<bool>(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.white,
      builder: (_) => _FilterSheet(
        filters: _filters!,
        selectedCategories: _categoryIds,
        selectedMaterials: _materialIds,
        selectedColors: _colorIds,
        selectedSizes: _sizeIds,
        minPrice: _minPrice,
        maxPrice: _maxPrice,
        hasStock: _hasStock,
        bestOnly: _bestOnly,
        sortBy: _sortBy,
        onApply: ({
          required categories,
          required materials,
          required colors,
          required sizes,
          required minPrice,
          required maxPrice,
          required hasStock,
          required bestOnly,
          required sortBy,
        }) {
          setState(() {
            _categoryIds
              ..clear()
              ..addAll(categories);
            _materialIds
              ..clear()
              ..addAll(materials);
            _colorIds
              ..clear()
              ..addAll(colors);
            _sizeIds
              ..clear()
              ..addAll(sizes);
            _minPrice = minPrice;
            _maxPrice = maxPrice;
            _hasStock = hasStock;
            _bestOnly = bestOnly;
            _sortBy = sortBy;
            _sortOrder = sortBy == 'harga_termurah' ? 'asc' : 'desc';
          });
        },
      ),
    );
    if (applied == true) {
      _load(initial: true);
    }
  }

  int get _activeFilterCount {
    var count = 0;
    if (_categoryIds.isNotEmpty) count++;
    if (_materialIds.isNotEmpty) count++;
    if (_colorIds.isNotEmpty) count++;
    if (_sizeIds.isNotEmpty) count++;
    if (_minPrice != null || _maxPrice != null) count++;
    if (_hasStock) count++;
    if (_bestOnly) count++;
    return count;
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        title: const Text('COLLECTION'),
      ),
      body: Column(
        children: [
          _buildSearchBar(),
          const Divider(height: 1),
          _buildToolbar(),
          const Divider(height: 1),
          Expanded(
            child: _loading
                ? const BrandLoader(label: 'Memuat produk...')
                : _error != null
                    ? ErrorState(
                        message: _error!,
                        onRetry: () => _load(initial: true),
                      )
                    : _products.isEmpty
                        ? const EmptyState(
                            icon: Icons.search_off,
                            title: 'Produk tidak ditemukan',
                            subtitle:
                                'Coba ubah kata kunci atau filter pencarianmu.',
                          )
                        : RefreshIndicator(
                            color: AppColors.primary,
                            onRefresh: () => _load(initial: true),
                            child: GridView.builder(
                              controller: _scrollController,
                              padding: const EdgeInsets.all(16),
                              gridDelegate:
                                  const SliverGridDelegateWithFixedCrossAxisCount(
                                crossAxisCount: 2,
                                mainAxisSpacing: 18,
                                crossAxisSpacing: 14,
                                childAspectRatio: 0.56,
                              ),
                              itemCount: _products.length +
                                  (_loadingMore ? 2 : 0),
                              itemBuilder: (_, i) {
                                if (i >= _products.length) {
                                  return const Center(
                                    child: SizedBox(
                                      width: 22,
                                      height: 22,
                                      child: CircularProgressIndicator(
                                        strokeWidth: 2,
                                        color: AppColors.primary,
                                      ),
                                    ),
                                  );
                                }
                                final p = _products[i];
                                return ProductCard(
                                  product: p,
                                  onTap: () {
                                    Navigator.push(
                                      context,
                                      MaterialPageRoute(
                                        builder: (_) =>
                                            ProductDetailScreen(productId: p.id),
                                      ),
                                    );
                                  },
                                );
                              },
                            ),
                          ),
          ),
        ],
      ),
    );
  }

  Widget _buildSearchBar() {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
      child: TextField(
        controller: _searchController,
        autofocus: widget.autofocusSearch,
        onChanged: _onSearchChanged,
        style: const TextStyle(fontSize: 13),
        decoration: InputDecoration(
          hintText: 'Cari produk...',
          prefixIcon: const Icon(Icons.search,
              color: AppColors.textMuted, size: 20),
          suffixIcon: _searchController.text.isEmpty
              ? null
              : IconButton(
                  icon: const Icon(Icons.close, size: 18),
                  onPressed: () {
                    _searchController.clear();
                    _load(initial: true);
                  },
                ),
        ),
      ),
    );
  }

  Widget _buildToolbar() {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
      child: Row(
        children: [
          Text(
            '${_products.length} Produk',
            style: const TextStyle(
              fontSize: 11,
              color: AppColors.textSecondary,
              letterSpacing: 1,
            ),
          ),
          const Spacer(),
          _toolbarChip(
            icon: Icons.tune,
            label: 'Filter',
            badge: _activeFilterCount,
            onTap: _openFilters,
          ),
          const SizedBox(width: 10),
          _toolbarChip(
            icon: Icons.swap_vert,
            label: _sortLabel(_sortBy),
            onTap: _pickSort,
          ),
        ],
      ),
    );
  }

  Widget _toolbarChip({
    required IconData icon,
    required String label,
    VoidCallback? onTap,
    int badge = 0,
  }) {
    return InkWell(
      onTap: onTap,
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
        decoration: BoxDecoration(
          border: Border.all(color: AppColors.border),
        ),
        child: Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(icon, size: 14, color: AppColors.primary),
            const SizedBox(width: 6),
            Text(
              label,
              style: const TextStyle(
                fontSize: 11,
                fontWeight: FontWeight.w600,
                color: AppColors.textPrimary,
                letterSpacing: 0.5,
              ),
            ),
            if (badge > 0) ...[
              const SizedBox(width: 6),
              Container(
                padding: const EdgeInsets.symmetric(
                    horizontal: 5, vertical: 1),
                decoration: const BoxDecoration(
                  color: AppColors.primary,
                  shape: BoxShape.circle,
                ),
                constraints:
                    const BoxConstraints(minWidth: 16, minHeight: 16),
                child: Text(
                  '$badge',
                  textAlign: TextAlign.center,
                  style: const TextStyle(
                    fontSize: 9,
                    color: Colors.white,
                    fontWeight: FontWeight.w700,
                  ),
                ),
              ),
            ],
          ],
        ),
      ),
    );
  }

  String _sortLabel(String value) {
    final options = _filters?.sortOptions ?? const <SortOption>[];
    for (final o in options) {
      if (o.value == value) return o.label;
    }
    return 'Terbaru';
  }

  Future<void> _pickSort() async {
    if (_filters == null) return;
    final options = _filters!.sortOptions;
    final picked = await showModalBottomSheet<String>(
      context: context,
      backgroundColor: Colors.white,
      builder: (_) => SafeArea(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            const Padding(
              padding: EdgeInsets.symmetric(vertical: 14),
              child: Text('URUTKAN',
                  style: TextStyle(
                    fontWeight: FontWeight.w700,
                    color: AppColors.primary,
                    letterSpacing: 2,
                  )),
            ),
            const Divider(height: 1),
            ...options.map(
              (o) => ListTile(
                onTap: () => Navigator.pop(context, o.value),
                title: Text(
                  o.label,
                  style: TextStyle(
                    fontSize: 13,
                    color: AppColors.textPrimary,
                    fontWeight: _sortBy == o.value
                        ? FontWeight.w700
                        : FontWeight.w500,
                  ),
                ),
                trailing: _sortBy == o.value
                    ? const Icon(Icons.check, color: AppColors.primary)
                    : null,
              ),
            ),
            const SizedBox(height: 12),
          ],
        ),
      ),
    );
    if (picked != null) {
      setState(() {
        _sortBy = picked;
        _sortOrder = picked == 'harga_termurah' ? 'asc' : 'desc';
      });
      _load(initial: true);
    }
  }
}

typedef _FilterApplyCallback = void Function({
  required Set<int> categories,
  required Set<int> materials,
  required Set<int> colors,
  required Set<int> sizes,
  required double? minPrice,
  required double? maxPrice,
  required bool hasStock,
  required bool bestOnly,
  required String sortBy,
});

class _FilterSheet extends StatefulWidget {
  final FilterOptions filters;
  final Set<int> selectedCategories;
  final Set<int> selectedMaterials;
  final Set<int> selectedColors;
  final Set<int> selectedSizes;
  final double? minPrice;
  final double? maxPrice;
  final bool hasStock;
  final bool bestOnly;
  final String sortBy;
  final _FilterApplyCallback onApply;

  const _FilterSheet({
    required this.filters,
    required this.selectedCategories,
    required this.selectedMaterials,
    required this.selectedColors,
    required this.selectedSizes,
    required this.minPrice,
    required this.maxPrice,
    required this.hasStock,
    required this.bestOnly,
    required this.sortBy,
    required this.onApply,
  });

  @override
  State<_FilterSheet> createState() => _FilterSheetState();
}

class _FilterSheetState extends State<_FilterSheet> {
  late Set<int> _categories = {...widget.selectedCategories};
  late Set<int> _materials = {...widget.selectedMaterials};
  late Set<int> _colors = {...widget.selectedColors};
  late Set<int> _sizes = {...widget.selectedSizes};
  late RangeValues _range = RangeValues(
    widget.minPrice ?? widget.filters.minPrice,
    widget.maxPrice ?? widget.filters.maxPrice,
  );
  late bool _hasStock = widget.hasStock;
  late bool _bestOnly = widget.bestOnly;

  @override
  Widget build(BuildContext context) {
    final double maxPrice =
        widget.filters.maxPrice > 0 ? widget.filters.maxPrice : 1000000.0;
    final double minPrice = widget.filters.minPrice;

    return DraggableScrollableSheet(
      expand: false,
      initialChildSize: 0.9,
      maxChildSize: 0.95,
      builder: (_, controller) => Column(
        children: [
          Container(
            padding: const EdgeInsets.fromLTRB(20, 16, 12, 16),
            decoration: const BoxDecoration(color: AppColors.blush),
            child: Row(
              children: [
                const Expanded(
                  child: Text(
                    'FILTER',
                    style: TextStyle(
                      fontWeight: FontWeight.w700,
                      color: AppColors.primary,
                      letterSpacing: 2,
                    ),
                  ),
                ),
                IconButton(
                  icon: const Icon(Icons.close, color: AppColors.primary),
                  onPressed: () => Navigator.pop(context),
                ),
              ],
            ),
          ),
          Expanded(
            child: ListView(
              controller: controller,
              padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
              children: [
                _sectionTitle('Kategori'),
                _wrapChips(
                  widget.filters.categories
                      .map((c) => _chip(
                            label: c.name,
                            selected: _categories.contains(c.id),
                            onTap: () => setState(() {
                              _categories.contains(c.id)
                                  ? _categories.remove(c.id)
                                  : _categories.add(c.id);
                            }),
                          ))
                      .toList(),
                ),
                if (widget.filters.materials.isNotEmpty) ...[
                  const SizedBox(height: 20),
                  _sectionTitle('Bahan'),
                  _wrapChips(widget.filters.materials
                      .map((c) => _chip(
                            label: c.name,
                            selected: _materials.contains(c.id),
                            onTap: () => setState(() {
                              _materials.contains(c.id)
                                  ? _materials.remove(c.id)
                                  : _materials.add(c.id);
                            }),
                          ))
                      .toList()),
                ],
                if (widget.filters.colors.isNotEmpty) ...[
                  const SizedBox(height: 20),
                  _sectionTitle('Warna'),
                  _wrapChips(widget.filters.colors
                      .map((c) => _chip(
                            label: c.name,
                            selected: _colors.contains(c.id),
                            onTap: () => setState(() {
                              _colors.contains(c.id)
                                  ? _colors.remove(c.id)
                                  : _colors.add(c.id);
                            }),
                          ))
                      .toList()),
                ],
                if (widget.filters.sizes.isNotEmpty) ...[
                  const SizedBox(height: 20),
                  _sectionTitle('Ukuran'),
                  _wrapChips(widget.filters.sizes
                      .map((s) => _chip(
                            label: s.name,
                            selected: _sizes.contains(s.id),
                            onTap: () => setState(() {
                              _sizes.contains(s.id)
                                  ? _sizes.remove(s.id)
                                  : _sizes.add(s.id);
                            }),
                          ))
                      .toList()),
                ],
                const SizedBox(height: 20),
                _sectionTitle('Rentang Harga'),
                RangeSlider(
                  activeColor: AppColors.primary,
                  inactiveColor: AppColors.blush,
                  min: minPrice,
                  max: maxPrice,
                  divisions: 20,
                  values: RangeValues(
                    _range.start.clamp(minPrice, maxPrice),
                    _range.end.clamp(minPrice, maxPrice),
                  ),
                  labels: RangeLabels(
                    formatIdr(_range.start),
                    formatIdr(_range.end),
                  ),
                  onChanged: (values) => setState(() => _range = values),
                ),
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text(formatIdr(_range.start),
                        style: const TextStyle(
                            fontSize: 12, color: AppColors.textSecondary)),
                    Text(formatIdr(_range.end),
                        style: const TextStyle(
                            fontSize: 12, color: AppColors.textSecondary)),
                  ],
                ),
                const SizedBox(height: 16),
                SwitchListTile(
                  value: _hasStock,
                  onChanged: (v) => setState(() => _hasStock = v),
                  activeThumbColor: AppColors.primary,
                  contentPadding: EdgeInsets.zero,
                  title: const Text('Hanya tampilkan yang tersedia',
                      style: TextStyle(fontSize: 13)),
                ),
                SwitchListTile(
                  value: _bestOnly,
                  onChanged: (v) => setState(() => _bestOnly = v),
                  activeThumbColor: AppColors.primary,
                  contentPadding: EdgeInsets.zero,
                  title: const Text('Best Product saja',
                      style: TextStyle(fontSize: 13)),
                ),
              ],
            ),
          ),
          Container(
            padding: const EdgeInsets.fromLTRB(16, 12, 16, 16),
            decoration: const BoxDecoration(
              border: Border(top: BorderSide(color: AppColors.divider)),
            ),
            child: Row(
              children: [
                Expanded(
                  child: OutlineBrandButton(
                    label: 'Reset',
                    onPressed: () {
                      setState(() {
                        _categories.clear();
                        _materials.clear();
                        _colors.clear();
                        _sizes.clear();
                        _range =
                            RangeValues(minPrice, maxPrice);
                        _hasStock = false;
                        _bestOnly = false;
                      });
                    },
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  flex: 2,
                  child: PrimaryButton(
                    label: 'Terapkan',
                    onPressed: () {
                      widget.onApply(
                        categories: _categories,
                        materials: _materials,
                        colors: _colors,
                        sizes: _sizes,
                        minPrice: _range.start == minPrice ? null : _range.start,
                        maxPrice: _range.end == maxPrice ? null : _range.end,
                        hasStock: _hasStock,
                        bestOnly: _bestOnly,
                        sortBy: widget.sortBy,
                      );
                      Navigator.pop(context, true);
                    },
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _sectionTitle(String title) => Padding(
        padding: const EdgeInsets.only(bottom: 12),
        child: Text(
          title.toUpperCase(),
          style: const TextStyle(
            fontWeight: FontWeight.w700,
            fontSize: 11,
            letterSpacing: 1.6,
            color: AppColors.primary,
          ),
        ),
      );

  Widget _wrapChips(List<Widget> chips) =>
      Wrap(spacing: 8, runSpacing: 8, children: chips);

  Widget _chip({
    required String label,
    required bool selected,
    required VoidCallback onTap,
  }) {
    return InkWell(
      onTap: onTap,
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 8),
        decoration: BoxDecoration(
          color: selected ? AppColors.primary : Colors.white,
          border: Border.all(
            color: selected ? AppColors.primary : AppColors.border,
          ),
        ),
        child: Text(
          label,
          style: TextStyle(
            fontSize: 12,
            fontWeight: FontWeight.w600,
            color: selected ? Colors.white : AppColors.textPrimary,
          ),
        ),
      ),
    );
  }
}
