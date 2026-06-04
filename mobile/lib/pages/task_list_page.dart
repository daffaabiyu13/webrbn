import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import '../database/database_helper.dart';
import '../models/task.dart';

class TaskListPage extends StatefulWidget {
  const TaskListPage({super.key});

  @override
  State<TaskListPage> createState() => _TaskListPageState();
}

class _TaskListPageState extends State<TaskListPage> {
  List<Task> _tasks = [];
  String _filter = 'semua';
  bool _loading = true;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    setState(() => _loading = true);
    final tasks = await DatabaseHelper.instance.getAllTasks();
    if (!mounted) return;
    setState(() {
      _tasks = tasks;
      _loading = false;
    });
  }

  List<Task> get _filteredTasks {
    switch (_filter) {
      case 'penting':
        return _tasks.where((t) => t.category == TaskCategory.penting).toList();
      case 'biasa':
        return _tasks.where((t) => t.category == TaskCategory.biasa).toList();
      case 'selesai':
        return _tasks.where((t) => t.isCompleted).toList();
      case 'belum':
        return _tasks.where((t) => !t.isCompleted).toList();
      default:
        return _tasks;
    }
  }

  Future<void> _toggleComplete(Task task) async {
    await DatabaseHelper.instance.toggleComplete(task.id!, !task.isCompleted);
    _load();
  }

  Future<void> _delete(Task task) async {
    final confirm = await showDialog<bool>(
      context: context,
      builder: (_) => AlertDialog(
        title: const Text('Hapus tugas?'),
        content: Text('Yakin hapus "${task.title}"?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context, false),
            child: const Text('Batal'),
          ),
          TextButton(
            onPressed: () => Navigator.pop(context, true),
            style: TextButton.styleFrom(foregroundColor: Colors.red),
            child: const Text('Hapus'),
          ),
        ],
      ),
    );
    if (confirm == true) {
      await DatabaseHelper.instance.deleteTask(task.id!);
      _load();
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Daftar Tugas'),
        leading: IconButton(
          icon: const Icon(Icons.arrow_back),
          onPressed: () => Navigator.of(context).pop(),
        ),
      ),
      body: Column(
        children: [
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
            color: Colors.white,
            child: SingleChildScrollView(
              scrollDirection: Axis.horizontal,
              child: Row(
                children: [
                  _FilterChip(
                    label: 'Semua',
                    selected: _filter == 'semua',
                    onTap: () => setState(() => _filter = 'semua'),
                  ),
                  _FilterChip(
                    label: 'Penting',
                    selected: _filter == 'penting',
                    onTap: () => setState(() => _filter = 'penting'),
                    color: Colors.red,
                  ),
                  _FilterChip(
                    label: 'Biasa',
                    selected: _filter == 'biasa',
                    onTap: () => setState(() => _filter = 'biasa'),
                    color: Colors.green,
                  ),
                  _FilterChip(
                    label: 'Selesai',
                    selected: _filter == 'selesai',
                    onTap: () => setState(() => _filter = 'selesai'),
                  ),
                  _FilterChip(
                    label: 'Belum',
                    selected: _filter == 'belum',
                    onTap: () => setState(() => _filter = 'belum'),
                  ),
                ],
              ),
            ),
          ),
          Expanded(
            child: _loading
                ? const Center(child: CircularProgressIndicator())
                : _filteredTasks.isEmpty
                    ? Center(
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Icon(Icons.inbox_outlined,
                                size: 80, color: Colors.grey.shade400),
                            const SizedBox(height: 12),
                            const Text(
                              'Belum ada tugas',
                              style: TextStyle(
                                fontSize: 16,
                                color: Colors.black54,
                              ),
                            ),
                          ],
                        ),
                      )
                    : RefreshIndicator(
                        onRefresh: _load,
                        child: ListView.separated(
                          padding: const EdgeInsets.all(12),
                          itemCount: _filteredTasks.length,
                          separatorBuilder: (_, __) => const SizedBox(height: 8),
                          itemBuilder: (context, i) {
                            final t = _filteredTasks[i];
                            return _TaskTile(
                              task: t,
                              onToggle: () => _toggleComplete(t),
                              onDelete: () => _delete(t),
                            );
                          },
                        ),
                      ),
          ),
        ],
      ),
    );
  }
}

class _FilterChip extends StatelessWidget {
  final String label;
  final bool selected;
  final VoidCallback onTap;
  final Color? color;
  const _FilterChip({
    required this.label,
    required this.selected,
    required this.onTap,
    this.color,
  });

  @override
  Widget build(BuildContext context) {
    final c = color ?? const Color(0xFF1565C0);
    return Padding(
      padding: const EdgeInsets.only(right: 8),
      child: GestureDetector(
        onTap: onTap,
        child: AnimatedContainer(
          duration: const Duration(milliseconds: 150),
          padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 8),
          decoration: BoxDecoration(
            color: selected ? c : Colors.grey.shade100,
            borderRadius: BorderRadius.circular(20),
            border: Border.all(
              color: selected ? c : Colors.grey.shade300,
            ),
          ),
          child: Text(
            label,
            style: TextStyle(
              color: selected ? Colors.white : Colors.black87,
              fontWeight: FontWeight.w600,
              fontSize: 13,
            ),
          ),
        ),
      ),
    );
  }
}

class _TaskTile extends StatelessWidget {
  final Task task;
  final VoidCallback onToggle;
  final VoidCallback onDelete;
  const _TaskTile({
    required this.task,
    required this.onToggle,
    required this.onDelete,
  });

  @override
  Widget build(BuildContext context) {
    final isPenting = task.category == TaskCategory.penting;
    final arrowColor = isPenting ? Colors.red : Colors.green;

    return Material(
      color: Colors.white,
      borderRadius: BorderRadius.circular(12),
      elevation: 1,
      shadowColor: Colors.black12,
      child: InkWell(
        borderRadius: BorderRadius.circular(12),
        onTap: onToggle,
        onLongPress: onDelete,
        child: Padding(
          padding: const EdgeInsets.all(12),
          child: Row(
            children: [
              Checkbox(
                value: task.isCompleted,
                onChanged: (_) => onToggle(),
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(4),
                ),
              ),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      task.title,
                      style: TextStyle(
                        fontSize: 15,
                        fontWeight: FontWeight.w600,
                        decoration: task.isCompleted
                            ? TextDecoration.lineThrough
                            : null,
                        color:
                            task.isCompleted ? Colors.grey : Colors.black87,
                      ),
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                    ),
                    const SizedBox(height: 4),
                    Text(
                      task.description,
                      style: const TextStyle(
                          fontSize: 12, color: Colors.black54),
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                    ),
                    const SizedBox(height: 6),
                    Row(
                      children: [
                        Container(
                          padding: const EdgeInsets.symmetric(
                              horizontal: 8, vertical: 2),
                          decoration: BoxDecoration(
                            color: (isPenting ? Colors.red : Colors.green)
                                .withOpacity(0.15),
                            borderRadius: BorderRadius.circular(6),
                          ),
                          child: Text(
                            isPenting ? 'Penting' : 'Biasa',
                            style: TextStyle(
                              fontSize: 10,
                              fontWeight: FontWeight.bold,
                              color: isPenting ? Colors.red : Colors.green,
                            ),
                          ),
                        ),
                        const SizedBox(width: 8),
                        Icon(Icons.event,
                            size: 12, color: Colors.grey.shade600),
                        const SizedBox(width: 3),
                        Text(
                          DateFormat('d MMM yyyy').format(task.dueDate),
                          style: TextStyle(
                            fontSize: 11,
                            color: Colors.grey.shade600,
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
              Icon(
                Icons.arrow_forward_ios,
                color: arrowColor,
                size: 18,
              ),
            ],
          ),
        ),
      ),
    );
  }
}
