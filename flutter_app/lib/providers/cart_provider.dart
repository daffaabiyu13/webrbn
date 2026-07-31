import 'dart:convert';

import 'package:flutter/foundation.dart';
import 'package:shared_preferences/shared_preferences.dart';

import '../models/cart_item.dart';

class CartProvider extends ChangeNotifier {
  static const _storageKey = 'anasthelabel_cart_v1';

  final List<CartItem> _items = [];
  bool _loaded = false;

  List<CartItem> get items => List.unmodifiable(_items);
  int get itemCount => _items.fold(0, (sum, item) => sum + item.quantity);
  double get subtotal => _items.fold(0, (sum, item) => sum + item.subtotal);
  bool get isEmpty => _items.isEmpty;
  bool get isLoaded => _loaded;

  Future<void> load() async {
    if (_loaded) return;
    final prefs = await SharedPreferences.getInstance();
    final raw = prefs.getString(_storageKey);
    if (raw != null && raw.isNotEmpty) {
      try {
        final list = jsonDecode(raw) as List;
        _items
          ..clear()
          ..addAll(list
              .cast<Map<String, dynamic>>()
              .map(CartItem.fromJson));
      } catch (_) {
        // ignore malformed cache
      }
    }
    _loaded = true;
    notifyListeners();
  }

  Future<void> _persist() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(
      _storageKey,
      jsonEncode(_items.map((e) => e.toJson()).toList()),
    );
  }

  void add(CartItem item) {
    final existing = _items.firstWhere(
      (i) => i.compositeKey == item.compositeKey,
      orElse: () => item,
    );
    if (identical(existing, item)) {
      _items.add(item);
    } else {
      existing.quantity += item.quantity;
    }
    _persist();
    notifyListeners();
  }

  void increment(String key) {
    for (final item in _items) {
      if (item.compositeKey == key) {
        item.quantity += 1;
        break;
      }
    }
    _persist();
    notifyListeners();
  }

  void decrement(String key) {
    for (var i = 0; i < _items.length; i++) {
      if (_items[i].compositeKey == key) {
        if (_items[i].quantity <= 1) {
          _items.removeAt(i);
        } else {
          _items[i].quantity -= 1;
        }
        break;
      }
    }
    _persist();
    notifyListeners();
  }

  void remove(String key) {
    _items.removeWhere((item) => item.compositeKey == key);
    _persist();
    notifyListeners();
  }

  void clear() {
    _items.clear();
    _persist();
    notifyListeners();
  }
}
