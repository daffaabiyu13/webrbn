import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';

import 'package:agenda_nusantara/main.dart';

void main() {
  testWidgets('Login page tampil', (WidgetTester tester) async {
    await tester.pumpWidget(const AgendaNusantaraApp());
    expect(find.text('Agenda Nusantara'), findsOneWidget);
    expect(find.text('Login'), findsWidgets);
  });
}
