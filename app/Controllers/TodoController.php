<?php

/**
 * Todo controller class - handles HTTP requests for todo operations
 */
class TodoController
{
    private $todoService;

    public function __construct()
    {
        $this->todoService = new TodoService();
    }

    /**
     * Handle add todo request
     */
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Response::methodNotAllowed();
        }

        $input = $this->getJsonInput();

        $result = $this->todoService->createTodo($input);

        if ($result['success']) {
            // Return in format expected by frontend
            echo json_encode([
                'success' => true,
                'message' => $result['message'],
                'todo_id' => $result['todo_id']
            ]);
        } else {
            if (isset($result['errors'])) {
                Response::validationError($result['errors']);
            } else {
                Response::error($result['message']);
            }
        }
    }

    /**
     * Handle get todos request
     */
    public function getTodos()
    {
        // Build filters from GET parameters
        $filters = [
            'category' => $_GET['category'] ?? null,
            'priority' => $_GET['priority'] ?? null,
            'status' => $_GET['status'] ?? null,
            'search' => $_GET['search'] ?? null,
            'sort' => $_GET['sort'] ?? 'newest'
        ];

        // Remove null values
        $filters = array_filter($filters, function($value) {
            return $value !== null;
        });

        $result = $this->todoService->getTodos($filters);

        if ($result['success']) {
            // Return in format expected by frontend
            echo json_encode([
                'success' => true,
                'todos' => $result['todos']
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => $result['message']
            ]);
        }
        exit();
    }

    /**
     * Handle update todo request
     */
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Response::methodNotAllowed();
        }

        if (!Validator::validateId($id)) {
            Response::error('Invalid todo ID');
        }

        $input = $this->getJsonInput();

        $result = $this->todoService->updateTodo($id, $input);

        if ($result['success']) {
            Response::success(null, $result['message']);
        } else {
            if (isset($result['errors'])) {
                Response::validationError($result['errors']);
            } else {
                Response::error($result['message']);
            }
        }
    }

    /**
     * Handle delete todo request
     */
    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Response::methodNotAllowed();
        }

        if (!Validator::validateId($id)) {
            Response::error('Invalid todo ID');
        }

        $result = $this->todoService->deleteTodo($id);

        if ($result['success']) {
            Response::success(null, $result['message']);
        } else {
            Response::error($result['message']);
        }
    }

    /**
     * Handle toggle todo status request
     */
    public function toggleStatus($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Response::methodNotAllowed();
        }

        if (!Validator::validateId($id)) {
            Response::error('Invalid todo ID');
        }

        $result = $this->todoService->toggleTodoStatus($id);

        if ($result['success']) {
            Response::success([
                'checked' => $result['checked']
            ], $result['message']);
        } else {
            Response::error($result['message']);
        }
    }

    /**
     * Handle kanban update request
     */
    public function updateKanban()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Response::methodNotAllowed();
        }

        $input = $this->getJsonInput();

        $todoId = $input['todoId'] ?? null;
        $column = $input['column'] ?? null;
        $order = $input['order'] ?? 0;

        if (!$todoId || !$column) {
            Response::error('Todo ID and column are required');
        }

        $result = $this->todoService->updateKanbanPosition($todoId, $column, $order);

        if ($result['success']) {
            Response::success([
                'column' => $result['column'],
                'order' => $result['order']
            ], $result['message']);
        } else {
            if (isset($result['errors'])) {
                Response::validationError($result['errors']);
            } else {
                Response::error($result['message']);
            }
        }
    }

    /**
     * Handle export request
     */
    public function export()
    {
        $format = $_GET['format'] ?? 'json';

        if (!in_array($format, ['json', 'csv'])) {
            Response::error('Invalid export format. Supported formats: json, csv');
        }

        $result = $this->todoService->exportTodos($format);

        if (!$result['success']) {
            Response::error($result['message']);
        }

        if ($format === 'csv') {
            // Set CSV headers
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="todos.csv"');

            $output = fopen('php://output', 'w');
            foreach ($result['data'] as $row) {
                fputcsv($output, $row);
            }
            fclose($output);
            exit();
        } else {
            // JSON export
            header('Content-Type: application/json');
            header('Content-Disposition: attachment; filename="todos.json"');
            echo json_encode($result['data'], JSON_PRETTY_PRINT);
            exit();
        }
    }

    /**
     * Handle bulk complete request
     */
    public function bulkComplete()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit();
        }

        $input = $this->getJsonInput();
        $todoIds = $input['todo_ids'] ?? [];

        if (empty($todoIds) || !is_array($todoIds)) {
            echo json_encode(['success' => false, 'message' => 'Valid todo IDs array required']);
            exit();
        }

        $result = $this->todoService->bulkCompleteTodos($todoIds);

        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => $result['message'],
                'count' => $result['count']
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => $result['message']
            ]);
        }
        exit();
    }

    /**
     * Handle bulk priority change request
     */
    public function bulkPriority()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Response::methodNotAllowed();
        }

        $input = $this->getJsonInput();
        $todoIds = $input['todo_ids'] ?? [];
        $priority = $input['priority'] ?? '';

        if (empty($todoIds) || !is_array($todoIds)) {
            Response::error('Valid todo IDs array required');
        }

        if (empty($priority)) {
            Response::error('Priority is required');
        }

        $result = $this->todoService->bulkChangePriority($todoIds, $priority);

        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => $result['message'],
                'count' => $result['count']
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => $result['message']
            ]);
        }
        exit();
    }

    /**
     * Handle bulk delete request
     */
    public function bulkDelete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Response::methodNotAllowed();
        }

        $input = $this->getJsonInput();
        $todoIds = $input['todo_ids'] ?? [];

        if (empty($todoIds) || !is_array($todoIds)) {
            Response::error('Valid todo IDs array required');
        }

        $result = $this->todoService->bulkDeleteTodos($todoIds);

        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => $result['message'],
                'count' => $result['count']
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => $result['message']
            ]);
        }
        exit();
    }

    /**
     * Get JSON input from request
     */
    private function getJsonInput()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        // Fallback to POST data if JSON is empty
        if (!$input) {
            $input = $_POST;
        }

        return $input;
    }
}
