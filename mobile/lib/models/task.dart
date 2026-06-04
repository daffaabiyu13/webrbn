enum TaskCategory { penting, biasa }

class Task {
  final int? id;
  final String title;
  final String description;
  final DateTime dueDate;
  final TaskCategory category;
  final bool isCompleted;
  final DateTime? completedAt;
  final DateTime createdAt;

  Task({
    this.id,
    required this.title,
    required this.description,
    required this.dueDate,
    required this.category,
    this.isCompleted = false,
    this.completedAt,
    DateTime? createdAt,
  }) : createdAt = createdAt ?? DateTime.now();

  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'title': title,
      'description': description,
      'due_date': dueDate.toIso8601String(),
      'category': category.name,
      'is_completed': isCompleted ? 1 : 0,
      'completed_at': completedAt?.toIso8601String(),
      'created_at': createdAt.toIso8601String(),
    };
  }

  factory Task.fromMap(Map<String, dynamic> map) {
    return Task(
      id: map['id'] as int?,
      title: map['title'] as String,
      description: map['description'] as String,
      dueDate: DateTime.parse(map['due_date'] as String),
      category: (map['category'] as String) == 'penting'
          ? TaskCategory.penting
          : TaskCategory.biasa,
      isCompleted: (map['is_completed'] as int) == 1,
      completedAt: map['completed_at'] != null
          ? DateTime.parse(map['completed_at'] as String)
          : null,
      createdAt: DateTime.parse(map['created_at'] as String),
    );
  }

  Task copyWith({
    int? id,
    String? title,
    String? description,
    DateTime? dueDate,
    TaskCategory? category,
    bool? isCompleted,
    DateTime? completedAt,
    DateTime? createdAt,
  }) {
    return Task(
      id: id ?? this.id,
      title: title ?? this.title,
      description: description ?? this.description,
      dueDate: dueDate ?? this.dueDate,
      category: category ?? this.category,
      isCompleted: isCompleted ?? this.isCompleted,
      completedAt: completedAt ?? this.completedAt,
      createdAt: createdAt ?? this.createdAt,
    );
  }
}
