import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:provider/provider.dart';

import 'providers/cart_provider.dart';
import 'providers/customer_provider.dart';
import 'screens/main_navigation.dart';
import 'services/api_client.dart';
import 'theme/app_theme.dart';

void main() {
  WidgetsFlutterBinding.ensureInitialized();
  SystemChrome.setSystemUIOverlayStyle(
    const SystemUiOverlayStyle(
      statusBarColor: Colors.transparent,
      statusBarIconBrightness: Brightness.dark,
    ),
  );

  final apiClient = ApiClient();
  final cartProvider = CartProvider();
  final customerProvider = CustomerProvider();

  // Hydrate persisted state before mounting so screens see current data.
  Future.wait([cartProvider.load(), customerProvider.load()]).then((_) {
    // No-op — providers notify listeners once loaded.
  });

  runApp(AnstheLabelApp(
    apiClient: apiClient,
    cartProvider: cartProvider,
    customerProvider: customerProvider,
  ));
}

class AnstheLabelApp extends StatelessWidget {
  final ApiClient apiClient;
  final CartProvider cartProvider;
  final CustomerProvider customerProvider;

  const AnstheLabelApp({
    super.key,
    required this.apiClient,
    required this.cartProvider,
    required this.customerProvider,
  });

  @override
  Widget build(BuildContext context) {
    return MultiProvider(
      providers: [
        Provider<ApiClient>.value(value: apiClient),
        ChangeNotifierProvider<CartProvider>.value(value: cartProvider),
        ChangeNotifierProvider<CustomerProvider>.value(value: customerProvider),
      ],
      child: MaterialApp(
        title: 'AnstheLabel',
        debugShowCheckedModeBanner: false,
        theme: AppTheme.light(),
        home: const MainNavigation(),
      ),
    );
  }
}
