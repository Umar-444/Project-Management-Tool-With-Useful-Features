<?php
header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    require '../db_conn.php';

    $input = json_decode(file_get_contents('php://input'), true);
    
    if(!$input) {
        $input = $_POST;
    }

    $todoId = $input['todoId'] ?? null;
    $newColumn = $input['column'] ?? null;
    $newOrder = $input['order'] ?? 0;

    if(empty($todoId) || empty($newColumn)){
        echo json_encode(['success' => false, 'message' => 'Todo ID and column are required']);
        exit();
    }

    // Validate column
    $validColumns = ['todo', 'in_progress', 'review', 'done'];
    if(!in_array($newColumn, $validColumns)) {
        echo json_encode(['success' => false, 'message' => 'Invalid column']);
        exit();
    }

    try {
        // Update the todo's column and order
        $stmt = $conn->prepare("UPDATE todos SET kanban_column=?, sort_order=? WHERE id=?");
        $res = $stmt->execute([$newColumn, $newOrder, $todoId]);

        if($res){
            // If moving to 'done' column, mark as completed
            if($newColumn === 'done') {
                $completeStmt = $conn->prepare("UPDATE todos SET checked=1, completed_at=NOW() WHERE id=?");
                $completeStmt->execute([$todoId]);
            } else {
                // If moving away from 'done', mark as not completed
                $incompleteStmt = $conn->prepare("UPDATE todos SET checked=0, completed_at=NULL WHERE id=?");
                $incompleteStmt->execute([$todoId]);
            }

            echo json_encode([
                'success' => true, 
                'message' => 'Todo moved successfully',
                'column' => $newColumn,
                'order' => $newOrder
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to move todo']);
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
