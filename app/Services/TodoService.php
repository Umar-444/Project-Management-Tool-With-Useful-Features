<?php

/**
 * Todo service class - contains business logic for todo operations
 */
class TodoService
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Create a new todo
     */
    public function createTodo($data)
    {
        $validator = Validator::validateTodoData($data);
        if ($validator->fails()) {
            return ['success' => false, 'errors' => $validator->getErrors()];
        }

        try {
            $todo = new Todo($data);
            $todo->setDateTime(date('Y-m-d H:i:s'));
            $todo->setKanbanColumn('todo'); // Default column

            $stmt = $this->db->prepare("
                INSERT INTO todos (title, description, category, priority, due_date, date_time, kanban_column)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");

            $result = $stmt->execute([
                $todo->getTitle(),
                $todo->getDescription(),
                $todo->getCategory(),
                $todo->getPriority(),
                $todo->getDueDate(),
                $todo->getDateTime(),
                $todo->getKanbanColumn()
            ]);

            if ($result) {
                $todoId = $this->db->lastInsertId();
                return [
                    'success' => true,
                    'message' => 'Todo added successfully',
                    'todo_id' => $todoId,
                    'todo' => $this->getTodoById($todoId)->toArray()
                ];
            }

            return ['success' => false, 'message' => 'Failed to add todo'];

        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Get all todos with optional filters
     */
    public function getTodos($filters = [])
    {
        try {
            $where = [];
            $params = [];

            // Build WHERE clause based on filters
            if (!empty($filters['category']) && $filters['category'] !== 'all') {
                $where[] = "category = ?";
                $params[] = $filters['category'];
            }

            if (!empty($filters['priority']) && $filters['priority'] !== 'all') {
                $where[] = "priority = ?";
                $params[] = $filters['priority'];
            }

            if (!empty($filters['status'])) {
                if ($filters['status'] === 'pending') {
                    $where[] = "checked = 0";
                } elseif ($filters['status'] === 'completed') {
                    $where[] = "checked = 1";
                }
            }

            if (!empty($filters['search'])) {
                $where[] = "(title LIKE ? OR description LIKE ?)";
                $searchTerm = '%' . $filters['search'] . '%';
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }

            $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

            // Sort options
            $sortOptions = [
                'newest' => 'date_time DESC',
                'oldest' => 'date_time ASC',
                'priority' => 'FIELD(priority, "Urgent", "High", "Medium", "Low"), date_time DESC',
                'alphabetical' => 'title ASC',
                'due_date' => 'due_date IS NULL, due_date ASC'
            ];

            $sortBy = $sortOptions[$filters['sort'] ?? 'newest'] ?? 'date_time DESC';

            $stmt = $this->db->prepare("SELECT * FROM todos $whereClause ORDER BY $sortBy");
            $stmt->execute($params);

            $todos = [];
            while ($row = $stmt->fetch()) {
                $todo = new Todo($row);
                $todos[] = $todo->toArray();
            }

            return ['success' => true, 'todos' => $todos];

        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Get todo by ID
     */
    public function getTodoById($id)
    {
        if (!Validator::validateId($id)) {
            return null;
        }

        try {
            $stmt = $this->db->prepare("SELECT * FROM todos WHERE id = ?");
            $stmt->execute([$id]);
            $data = $stmt->fetch();

            return $data ? new Todo($data) : null;

        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Update todo
     */
    public function updateTodo($id, $data)
    {
        if (!Validator::validateId($id)) {
            return ['success' => false, 'message' => 'Invalid todo ID'];
        }

        $validator = Validator::validateTodoData($data);
        if ($validator->fails()) {
            return ['success' => false, 'errors' => $validator->getErrors()];
        }

        $existingTodo = $this->getTodoById($id);
        if (!$existingTodo) {
            return ['success' => false, 'message' => 'Todo not found'];
        }

        try {
            $todo = new Todo(array_merge($existingTodo->toArray(), $data));
            $todo->setId($id); // Preserve ID

            $stmt = $this->db->prepare("
                UPDATE todos SET
                    title = ?,
                    description = ?,
                    category = ?,
                    priority = ?,
                    due_date = ?
                WHERE id = ?
            ");

            $result = $stmt->execute([
                $todo->getTitle(),
                $todo->getDescription(),
                $todo->getCategory(),
                $todo->getPriority(),
                $todo->getDueDate(),
                $id
            ]);

            return $result
                ? ['success' => true, 'message' => 'Todo updated successfully']
                : ['success' => false, 'message' => 'Failed to update todo'];

        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Delete todo
     */
    public function deleteTodo($id)
    {
        if (!Validator::validateId($id)) {
            return ['success' => false, 'message' => 'Invalid todo ID'];
        }

        try {
            $stmt = $this->db->prepare("DELETE FROM todos WHERE id = ?");
            $result = $stmt->execute([$id]);

            return $result
                ? ['success' => true, 'message' => 'Todo deleted successfully']
                : ['success' => false, 'message' => 'Failed to delete todo'];

        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Toggle todo completion status
     */
    public function toggleTodoStatus($id)
    {
        $todo = $this->getTodoById($id);
        if (!$todo) {
            return ['success' => false, 'message' => 'Todo not found'];
        }

        try {
            $newStatus = !$todo->isCompleted();
            $completedAt = $newStatus ? date('Y-m-d H:i:s') : null;

            $stmt = $this->db->prepare("UPDATE todos SET checked = ?, completed_at = ? WHERE id = ?");
            $result = $stmt->execute([$newStatus, $completedAt, $id]);

            return $result
                ? ['success' => true, 'message' => 'Todo status updated', 'checked' => $newStatus]
                : ['success' => false, 'message' => 'Failed to update todo status'];

        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Update kanban position
     */
    public function updateKanbanPosition($todoId, $column, $order = 0)
    {
        if (!Validator::validateId($todoId)) {
            return ['success' => false, 'message' => 'Invalid todo ID'];
        }

        $validator = Validator::validateKanbanColumn($column);
        if ($validator->fails()) {
            return ['success' => false, 'errors' => $validator->getErrors()];
        }

        try {
            // Update kanban column and order
            $stmt = $this->db->prepare("UPDATE todos SET kanban_column = ?, sort_order = ? WHERE id = ?");
            $result = $stmt->execute([$column, $order, $todoId]);

            if ($result) {
                // Handle completion status based on column
                if ($column === 'done') {
                    $completeStmt = $this->db->prepare("UPDATE todos SET checked = 1, completed_at = NOW() WHERE id = ?");
                    $completeStmt->execute([$todoId]);
                } else {
                    $incompleteStmt = $this->db->prepare("UPDATE todos SET checked = 0, completed_at = NULL WHERE id = ?");
                    $incompleteStmt->execute([$todoId]);
                }
            }

            return $result
                ? ['success' => true, 'message' => 'Todo moved successfully', 'column' => $column, 'order' => $order]
                : ['success' => false, 'message' => 'Failed to move todo'];

        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Get statistics
     */
    public function getStatistics()
    {
        try {
            // Get total counts
            $stmt = $this->db->prepare("SELECT
                COUNT(*) as total,
                SUM(CASE WHEN checked = 1 THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN checked = 0 THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN checked = 0 AND due_date < NOW() AND due_date IS NOT NULL THEN 1 ELSE 0 END) as overdue
                FROM todos");
            $stmt->execute();
            $stats = $stmt->fetch();

            // Get kanban column counts
            $kanbanStmt = $this->db->prepare("SELECT kanban_column, COUNT(*) as count FROM todos GROUP BY kanban_column");
            $kanbanStmt->execute();
            $kanbanStats = $kanbanStmt->fetchAll(PDO::FETCH_KEY_PAIR);

            return [
                'success' => true,
                'statistics' => [
                    'total' => (int)($stats['total'] ?? 0),
                    'completed' => (int)($stats['completed'] ?? 0),
                    'pending' => (int)($stats['pending'] ?? 0),
                    'overdue' => (int)($stats['overdue'] ?? 0),
                    'kanban' => [
                        'todo' => (int)($kanbanStats['todo'] ?? 0),
                        'in_progress' => (int)($kanbanStats['in_progress'] ?? 0),
                        'review' => (int)($kanbanStats['review'] ?? 0),
                        'done' => (int)($kanbanStats['done'] ?? 0)
                    ]
                ]
            ];

        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Export todos
     */
    public function exportTodos($format = 'json')
    {
        $result = $this->getTodos();
        if (!$result['success']) {
            return $result;
        }

        $todos = $result['todos'];

        switch ($format) {
            case 'csv':
                return $this->exportToCsv($todos);
            case 'json':
            default:
                return ['success' => true, 'data' => $todos, 'format' => 'json'];
        }
    }

    /**
     * Export to CSV format
     */
    private function exportToCsv($todos)
    {
        $csvData = [];
        $csvData[] = ['ID', 'Title', 'Description', 'Category', 'Priority', 'Due Date', 'Created', 'Completed', 'Status'];

        foreach ($todos as $todo) {
            $csvData[] = [
                $todo['id'],
                $todo['title'],
                $todo['description'],
                $todo['category'],
                $todo['priority'],
                $todo['due_date'],
                $todo['date_time'],
                $todo['completed_at'],
                $todo['checked'] ? 'Completed' : 'Pending'
            ];
        }

        return ['success' => true, 'data' => $csvData, 'format' => 'csv'];
    }

    /**
     * Bulk complete todos
     */
    public function bulkCompleteTodos($todoIds)
    {
        if (empty($todoIds)) {
            return ['success' => false, 'message' => 'No todos specified'];
        }

        try {
            // Validate all IDs exist
            $placeholders = str_repeat('?,', count($todoIds) - 1) . '?';
            $stmt = $this->db->prepare("SELECT id FROM todos WHERE id IN ($placeholders)");
            $stmt->execute($todoIds);
            $existingIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if (count($existingIds) !== count($todoIds)) {
                return ['success' => false, 'message' => 'Some todos not found'];
            }

            // Bulk update to completed
            $stmt = $this->db->prepare("UPDATE todos SET checked = 1, completed_at = NOW() WHERE id IN ($placeholders)");
            $result = $stmt->execute($todoIds);

            return $result
                ? ['success' => true, 'message' => count($todoIds) . ' todos marked as completed', 'count' => count($todoIds)]
                : ['success' => false, 'message' => 'Failed to complete todos'];

        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Bulk change priority
     */
    public function bulkChangePriority($todoIds, $priority)
    {
        if (empty($todoIds)) {
            return ['success' => false, 'message' => 'No todos specified'];
        }

        if (!in_array($priority, Todo::VALID_PRIORITIES)) {
            return ['success' => false, 'message' => 'Invalid priority'];
        }

        try {
            // Validate all IDs exist
            $placeholders = str_repeat('?,', count($todoIds) - 1) . '?';
            $stmt = $this->db->prepare("SELECT id FROM todos WHERE id IN ($placeholders)");
            $stmt->execute($todoIds);
            $existingIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if (count($existingIds) !== count($todoIds)) {
                return ['success' => false, 'message' => 'Some todos not found'];
            }

            // Bulk update priority
            $stmt = $this->db->prepare("UPDATE todos SET priority = ? WHERE id IN ($placeholders)");
            $params = array_merge([$priority], $todoIds);
            $result = $stmt->execute($params);

            return $result
                ? ['success' => true, 'message' => 'Priority updated for ' . count($todoIds) . ' todos', 'count' => count($todoIds)]
                : ['success' => false, 'message' => 'Failed to update priority'];

        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Bulk delete todos
     */
    public function bulkDeleteTodos($todoIds)
    {
        if (empty($todoIds)) {
            return ['success' => false, 'message' => 'No todos specified'];
        }

        try {
            // Validate all IDs exist
            $placeholders = str_repeat('?,', count($todoIds) - 1) . '?';
            $stmt = $this->db->prepare("SELECT id FROM todos WHERE id IN ($placeholders)");
            $stmt->execute($todoIds);
            $existingIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if (count($existingIds) !== count($todoIds)) {
                return ['success' => false, 'message' => 'Some todos not found'];
            }

            // Bulk delete
            $stmt = $this->db->prepare("DELETE FROM todos WHERE id IN ($placeholders)");
            $result = $stmt->execute($todoIds);

            return $result
                ? ['success' => true, 'message' => count($todoIds) . ' todos deleted', 'count' => count($todoIds)]
                : ['success' => false, 'message' => 'Failed to delete todos'];

        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }
}
