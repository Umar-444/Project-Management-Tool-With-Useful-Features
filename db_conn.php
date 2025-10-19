<?php

$host = "localhost";
$user = "root";
$password = "";
$dbname = "todo_php";

try {
    $conn = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user, 
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("A database connection error occurred. Please try again later.");
}