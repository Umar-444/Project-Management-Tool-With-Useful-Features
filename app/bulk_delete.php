<?php

// Include autoloader for OOP classes
require_once 'Core/autoload.php';

// Create controller and handle request
$controller = new TodoController();
$controller->bulkDelete();
