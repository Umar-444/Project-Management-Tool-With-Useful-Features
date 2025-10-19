<?php
header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    require '../db_conn.php';

    $input = json_decode(file_get_contents('php://input'), true);
    
    if(!$input) {
        $input = $_POST;
    }

    $id = $input['id'] ?? null;

    if(empty($id)){
        echo json_encode(['success' => false, 'message' => 'ID is required']);
        exit();
    }

    try {
        // First check if todo exists
        $checkStmt = $conn->prepare("SELECT id FROM todos WHERE id=?");
        $checkStmt->execute([$id]);
        
        if(!$checkStmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Todo not found']);
            exit();
        }

        $stmt = $conn->prepare("DELETE FROM todos WHERE id=?");
        $res = $stmt->execute([$id]);

        if($res){
            echo json_encode(['success' => true, 'message' => 'Todo deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete todo']);
        }
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
    
    $conn = null;
    exit();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}