<?php

// Include autoloader for OOP classes
require_once 'Core/autoload.php';

// Get todo ID from request
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    $input = $_POST;
}
$id = $input['id'] ?? null;

// Create controller and handle request
$controller = new TodoController();
$controller->toggleStatus($id);