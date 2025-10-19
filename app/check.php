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
        $todos = $conn->prepare("SELECT id, checked FROM todos WHERE id=?");
        $todos->execute([$id]);
        $todo = $todos->fetch(PDO::FETCH_ASSOC);

        if(!$todo) {
            echo json_encode(['success' => false, 'message' => 'Todo not found']);
            exit();
        }

        $uId = $todo['id'];
        $checked = $todo['checked'];
        $uChecked = $checked ? 0 : 1;
        $completed_at = $uChecked ? date('Y-m-d H:i:s') : null;

        $stmt = $conn->prepare("UPDATE todos SET checked=?, completed_at=? WHERE id=?");
        $res = $stmt->execute([$uChecked, $completed_at, $uId]);

        if($res){
            echo json_encode([
                'success' => true, 
                'checked' => $uChecked,
                'completed_at' => $completed_at
            ]);
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