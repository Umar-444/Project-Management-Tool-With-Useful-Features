<?php
header('Content-Type: application/json');

require '../db_conn.php';

try {
    $stmt = $conn->prepare("SELECT * FROM todos ORDER BY id DESC");
    $stmt->execute();
    $todos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'todos' => $todos
    ]);
} catch(PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}

$conn = null;
?>
