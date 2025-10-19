<?php
require '../db_conn.php';

$format = $_GET['format'] ?? 'json';

try {
    $stmt = $conn->prepare("SELECT * FROM todos ORDER BY id DESC");
    $stmt->execute();
    $todos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    switch($format) {
        case 'csv':
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="todos.csv"');
            
            $output = fopen('php://output', 'w');
            fputcsv($output, ['ID', 'Title', 'Description', 'Category', 'Priority', 'Due Date', 'Created', 'Completed', 'Status']);
            
            foreach($todos as $todo) {
                fputcsv($output, [
                    $todo['id'],
                    $todo['title'],
                    $todo['description'],
                    $todo['category'],
                    $todo['priority'],
                    $todo['due_date'],
                    $todo['date_time'],
                    $todo['completed_at'],
                    $todo['checked'] ? 'Completed' : 'Pending'
                ]);
            }
            fclose($output);
            break;
            
        case 'json':
        default:
            header('Content-Type: application/json');
            header('Content-Disposition: attachment; filename="todos.json"');
            echo json_encode($todos, JSON_PRETTY_PRINT);
            break;
    }
} catch(PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}

$conn = null;
?>
