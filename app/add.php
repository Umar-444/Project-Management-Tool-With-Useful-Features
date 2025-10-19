<?php
header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    require '../db_conn.php';

    $input = json_decode(file_get_contents('php://input'), true);
    
    if(!$input) {
        $input = $_POST;
    }

    $title = trim($input['title'] ?? '');
    $description = trim($input['description'] ?? '');
    $category = $input['category'] ?? 'General';
    $priority = $input['priority'] ?? 'Medium';
    $due_date = $input['due_date'] ?? null;

    if(empty($title)){
        echo json_encode(['success' => false, 'message' => 'Title is required']);
        exit();
    }

    // Validate priority
    $validPriorities = ['Low', 'Medium', 'High', 'Urgent'];
    if(!in_array($priority, $validPriorities)) {
        $priority = 'Medium';
    }

    // Validate category
    $validCategories = ['General', 'Work', 'Personal', 'Shopping', 'Health', 'Learning'];
    if(!in_array($category, $validCategories)) {
        $category = 'General';
    }

    // Format due date
    if($due_date) {
        $due_date = date('Y-m-d H:i:s', strtotime($due_date));
    }

    try {
        $stmt = $conn->prepare("INSERT INTO todos(title, description, category, priority, due_date, kanban_column) VALUES(?, ?, ?, ?, ?, 'todo')");
        $res = $stmt->execute([$title, $description, $category, $priority, $due_date]);

        if($res){
            $todoId = $conn->lastInsertId();
            echo json_encode([
                'success' => true, 
                'message' => 'Todo added successfully',
                'todo_id' => $todoId
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add todo']);
        }
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
    
    $conn = null;
    exit();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}