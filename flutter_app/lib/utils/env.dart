/// Base URL for the AnstheLabel Laravel API.
///
/// Override at build time:
///   flutter run --dart-define=API_BASE_URL=https://your-domain.tld
const String kApiBaseUrl = String.fromEnvironment(
  'API_BASE_URL',
  defaultValue: 'https://ansthelabel.test',
);

/// Full API prefix. All endpoints live under /api/v1.
String get kApiPrefix => '$kApiBaseUrl/api/v1';
