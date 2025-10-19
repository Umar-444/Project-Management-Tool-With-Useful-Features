<?php
header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    require '../db_conn.php';

    $input = json_decode(file_get_contents('php://input'), true);
    
    if(!$input) {
        $input = $_POST;
    }

    $id = $input['id'] ?? null;
    $title = trim($input['title'] ?? '');
    $description = trim($input['description'] ?? '');
    $category = $input['category'] ?? 'General';
    $priority = $input['priority'] ?? 'Medium';
    $due_date = $input['due_date'] ?? null;

    if(empty($id) || empty($title)){
        echo json_encode(['success' => false, 'message' => 'ID and title are required']);
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
        // First check if todo exists
        $checkStmt = $conn->prepare("SELECT id FROM todos WHERE id=?");
        $checkStmt->execute([$id]);
        
        if(!$checkStmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Todo not found']);
            exit();
        }

        $stmt = $conn->prepare("UPDATE todos SET title=?, description=?, category=?, priority=?, due_date=? WHERE id=?");
        $res = $stmt->execute([$title, $description, $category, $priority, $due_date, $id]);

        if($res){
            echo json_encode(['success' => true, 'message' => 'Todo updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update todo']);
        }
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
    
    $conn = null;
    exit();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
