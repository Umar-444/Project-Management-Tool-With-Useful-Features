<?php

/**
 * Autoloader for OOP classes
 */
spl_autoload_register(function ($className) {
    $baseDir = __DIR__ . '/../';

    // Map class names to file paths
    $classMap = [
        'Database' => 'Core/Database.php',
        'Response' => 'Core/Response.php',
        'Validator' => 'Core/Validator.php',
        'Todo' => 'Models/Todo.php',
        'Category' => 'Models/Category.php',
        'TodoService' => 'Services/TodoService.php',
        'CategoryService' => 'Services/CategoryService.php',
        'TodoController' => 'Controllers/TodoController.php',
        'CategoryController' => 'Controllers/CategoryController.php',
    ];

    if (isset($classMap[$className])) {
        $file = $baseDir . $classMap[$className];
        if (file_exists($file)) {
            require_once $file;
        }
    }
});
