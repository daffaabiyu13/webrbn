import 'package:flutter/foundation.dart';
import 'package:shared_preferences/shared_preferences.dart';

/// Remembers the most recent customer info so the checkout form
/// and the transaction history screen can pre-fill from storage.
class CustomerProvider extends ChangeNotifier {
  static const _kName = 'customer_name';
  static const _kPhone = 'customer_phone';
  static const _kEmail = 'customer_email';
  static const _kAddress = 'customer_address';

  String name = '';
  String phone = '';
  String email = '';
  String address = '';

  Future<void> load() async {
    final prefs = await SharedPreferences.getInstance();
    name = prefs.getString(_kName) ?? '';
    phone = prefs.getString(_kPhone) ?? '';
    email = prefs.getString(_kEmail) ?? '';
    address = prefs.getString(_kAddress) ?? '';
    notifyListeners();
  }

  Future<void> save({
    required String name,
    required String phone,
    required String email,
    required String address,
  }) async {
    this.name = name;
    this.phone = phone;
    this.email = email;
    this.address = address;
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(_kName, name);
    await prefs.setString(_kPhone, phone);
    await prefs.setString(_kEmail, email);
    await prefs.setString(_kAddress, address);
    notifyListeners();
  }
}
