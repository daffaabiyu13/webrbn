import 'package:path/path.dart';
import 'package:sqflite/sqflite.dart';
import '../models/task.dart';

class DatabaseHelper {
  static final DatabaseHelper instance = DatabaseHelper._init();
  static Database? _database;

  DatabaseHelper._init();

  Future<Database> get database async {
    if (_database != null) return _database!;
    _database = await _initDB('agenda_nusantara.db');
    return _database!;
  }

  Future<Database> _initDB(String fileName) async {
    final dbPath = await getDatabasesPath();
    final path = join(dbPath, fileName);
    return await openDatabase(
      path,
      version: 1,
      onCreate: _createDB,
    );
  }

  Future _createDB(Database db, int version) async {
    await db.execute('''
      CREATE TABLE tasks (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        description TEXT NOT NULL,
        due_date TEXT NOT NULL,
        category TEXT NOT NULL,
        is_completed INTEGER NOT NULL DEFAULT 0,
        completed_at TEXT,
        created_at TEXT NOT NULL
      )
    ''');
  }

  Future<int> insertTask(Task task) async {
    final db = await instance.database;
    final map = task.toMap();
    map.remove('id');
    return await db.insert('tasks', map);
  }

  Future<List<Task>> getAllTasks() async {
    final db = await instance.database;
    final result = await db.query('tasks', orderBy: 'due_date ASC');
    return result.map((m) => Task.fromMap(m)).toList();
  }

  Future<int> countByCategory(TaskCategory category, {required bool completed}) async {
    final db = await instance.database;
    final result = await db.rawQuery(
      'SELECT COUNT(*) AS c FROM tasks WHERE category = ? AND is_completed = ?',
      [category.name, completed ? 1 : 0],
    );
    return Sqflite.firstIntValue(result) ?? 0;
  }

  Future<int> countCompleted() async {
    final db = await instance.database;
    final result =
        await db.rawQuery('SELECT COUNT(*) AS c FROM tasks WHERE is_completed = 1');
    return Sqflite.firstIntValue(result) ?? 0;
  }

  Future<int> countPending() async {
    final db = await instance.database;
    final result =
        await db.rawQuery('SELECT COUNT(*) AS c FROM tasks WHERE is_completed = 0');
    return Sqflite.firstIntValue(result) ?? 0;
  }

  Future<int> updateTask(Task task) async {
    final db = await instance.database;
    return await db.update(
      'tasks',
      task.toMap(),
      where: 'id = ?',
      whereArgs: [task.id],
    );
  }

  Future<int> toggleComplete(int id, bool completed) async {
    final db = await instance.database;
    return await db.update(
      'tasks',
      {
        'is_completed': completed ? 1 : 0,
        'completed_at': completed ? DateTime.now().toIso8601String() : null,
      },
      where: 'id = ?',
      whereArgs: [id],
    );
  }

  Future<int> deleteTask(int id) async {
    final db = await instance.database;
    return await db.delete('tasks', where: 'id = ?', whereArgs: [id]);
  }

  Future<Map<DateTime, int>> completedPerDay({int lastDays = 7}) async {
    final db = await instance.database;
    final result = await db.rawQuery('''
      SELECT date(completed_at) AS d, COUNT(*) AS c
      FROM tasks
      WHERE is_completed = 1 AND completed_at IS NOT NULL
      GROUP BY date(completed_at)
      ORDER BY d ASC
    ''');

    final map = <DateTime, int>{};
    for (final row in result) {
      final dateStr = row['d'] as String?;
      if (dateStr == null) continue;
      final date = DateTime.parse(dateStr);
      map[date] = row['c'] as int;
    }

    final today = DateTime.now();
    final result2 = <DateTime, int>{};
    for (int i = lastDays - 1; i >= 0; i--) {
      final d = DateTime(today.year, today.month, today.day - i);
      result2[d] = map[d] ?? 0;
    }
    return result2;
  }
}
