import 'dart:convert';
import 'dart:io';

import 'package:http/http.dart' as http;

import '../utils/env.dart';

class ApiException implements Exception {
  final String message;
  final int? statusCode;
  final dynamic details;

  ApiException(this.message, {this.statusCode, this.details});

  @override
  String toString() => 'ApiException($statusCode): $message';
}

class ApiClient {
  final http.Client _client;
  ApiClient({http.Client? client}) : _client = client ?? http.Client();

  Uri _uri(String path, [Map<String, dynamic>? query]) {
    final normalized = path.startsWith('/') ? path : '/$path';
    final base = Uri.parse('$kApiPrefix$normalized');
    if (query == null || query.isEmpty) return base;
    final stringified = <String, String>{};
    query.forEach((k, v) {
      if (v == null) return;
      if (v is List) {
        stringified[k] = v.join(',');
      } else if (v is bool) {
        stringified[k] = v ? 'true' : 'false';
      } else {
        stringified[k] = v.toString();
      }
    });
    return base.replace(queryParameters: stringified);
  }

  Map<String, String> get _headers => const {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      };

  Future<Map<String, dynamic>> get(String path,
      {Map<String, dynamic>? query}) async {
    try {
      final response = await _client
          .get(_uri(path, query), headers: _headers)
          .timeout(const Duration(seconds: 30));
      return _handle(response);
    } on SocketException {
      throw ApiException('Tidak dapat terhubung ke server.');
    }
  }

  Future<Map<String, dynamic>> post(String path,
      {Map<String, dynamic>? body}) async {
    try {
      final response = await _client
          .post(_uri(path), headers: _headers, body: jsonEncode(body ?? {}))
          .timeout(const Duration(seconds: 30));
      return _handle(response);
    } on SocketException {
      throw ApiException('Tidak dapat terhubung ke server.');
    }
  }

  Future<Map<String, dynamic>> multipart(
    String path, {
    required String field,
    required String filename,
    required List<int> bytes,
    String contentType = 'image/jpeg',
    Map<String, String>? fields,
  }) async {
    try {
      final request = http.MultipartRequest('POST', _uri(path))
        ..headers['Accept'] = 'application/json'
        ..files.add(
          http.MultipartFile.fromBytes(
            field,
            bytes,
            filename: filename,
          ),
        );
      if (fields != null) request.fields.addAll(fields);
      final streamed = await request.send().timeout(const Duration(seconds: 60));
      final response = await http.Response.fromStream(streamed);
      return _handle(response);
    } on SocketException {
      throw ApiException('Tidak dapat terhubung ke server.');
    }
  }

  Map<String, dynamic> _handle(http.Response response) {
    Map<String, dynamic>? parsed;
    try {
      final decoded = jsonDecode(response.body);
      if (decoded is Map<String, dynamic>) parsed = decoded;
    } catch (_) {}

    if (response.statusCode >= 200 && response.statusCode < 300) {
      if (parsed == null) {
        throw ApiException('Format respons tidak valid.',
            statusCode: response.statusCode);
      }
      return parsed;
    }

    final message = parsed?['message'] as String? ??
        'Terjadi kesalahan (${response.statusCode})';
    throw ApiException(
      message,
      statusCode: response.statusCode,
      details: parsed,
    );
  }

  void dispose() => _client.close();
}
