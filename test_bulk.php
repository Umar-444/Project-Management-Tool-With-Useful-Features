<?php

require_once 'app/Core/autoload.php';

try {
    $service = new TodoService();
    $result = $service->bulkCompleteTodos([32, 33]);
    var_dump($result);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
