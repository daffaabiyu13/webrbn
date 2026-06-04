import 'package:shared_preferences/shared_preferences.dart';

class AuthService {
  static const _kUsername = 'auth_username';
  static const _kPassword = 'auth_password';
  static const _kInitialized = 'auth_initialized';

  static const String defaultUsername = 'user';
  static const String defaultPassword = 'user';

  Future<void> _ensureInitialized() async {
    final prefs = await SharedPreferences.getInstance();
    if (!(prefs.getBool(_kInitialized) ?? false)) {
      await prefs.setString(_kUsername, defaultUsername);
      await prefs.setString(_kPassword, defaultPassword);
      await prefs.setBool(_kInitialized, true);
    }
  }

  Future<bool> login(String username, String password) async {
    await _ensureInitialized();
    final prefs = await SharedPreferences.getInstance();
    final u = prefs.getString(_kUsername) ?? defaultUsername;
    final p = prefs.getString(_kPassword) ?? defaultPassword;
    return username == u && password == p;
  }

  Future<String> currentUsername() async {
    await _ensureInitialized();
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString(_kUsername) ?? defaultUsername;
  }

  Future<bool> verifyPassword(String password) async {
    await _ensureInitialized();
    final prefs = await SharedPreferences.getInstance();
    final p = prefs.getString(_kPassword) ?? defaultPassword;
    return password == p;
  }

  Future<void> updatePassword(String newPassword) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(_kPassword, newPassword);
  }
}
