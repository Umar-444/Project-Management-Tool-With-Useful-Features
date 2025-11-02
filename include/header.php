<?php 
require 'db_conn.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Advanced To-Do List</title>
    <link rel="stylesheet" href="css/style.css?v=4">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Style for the dark/white theme toggle button */
        .theme-toggle {
            display: flex;
            align-items: center;
            gap: 0.4em;
            background: #4263eb;
            color: #fff;
            border: none;
            padding: 0.4em 1.12em;
            border-radius: 28px;
            font-weight: 600;
            font-size: 1.02em;
            box-shadow: 0 2px 8px rgba(66,99,235,0.11);
            cursor: pointer;
            outline: none;
            transition: background 0.18s, color 0.18s, box-shadow .15s;
        }
        .theme-toggle:hover,
        .theme-toggle:focus {
            background: #294bd0;
            color: #fff;
            box-shadow: 0 3px 14px rgba(66,99,235,0.18);
        }
        .theme-toggle i {
            font-size: 1.25em;
        }
        .theme-toggle .theme-text {
            font-weight: 600;
            font-size: 1em;
            letter-spacing: 0;
            margin-left: 0.27em;
        }
        /* In dark mode, make the button white with dark blue text & icon */
        [data-theme="dark"] .theme-toggle {
            background: #fff;
            color: #294bd0;
            box-shadow: 0 1px 8px rgba(0,0,0,0.08);
        }
        [data-theme="dark"] .theme-toggle:hover,
        [data-theme="dark"] .theme-toggle:focus {
            background: #f1f5ff;
            color: #0d2463;
            box-shadow: 0 4px 14px rgba(0,0,0,0.13);
        }
        [data-theme="dark"] .theme-toggle i {
            color: #294bd0 !important;
        }
        /* force .theme-toggle i in light mode to stay white */
        .theme-toggle i {
            color: #fff;
            transition: color 0.18s;
        }
        [data-theme="dark"] .theme-toggle .theme-text {
            color: #294bd0 !important;
        }
    </style>
</head>
<body>
    <!-- Modern Navigation Bar -->
    <nav class="navbar">
        <div class="nav-container">
            <!-- Logo/Brand Section -->
            <div class="nav-brand">
                <a href="index.php" class="brand-link">
                    <div class="brand-icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="brand-text">
                        <span class="brand-title">TaskFlow</span>
                        <span class="brand-subtitle">Advanced To-Do</span>
                    </div>
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="nav-menu">
                <div class="nav-links">
                    <a href="index.php" id="homeNav" class="nav-link">
                        <i class="fas fa-home"></i>
                        <span>Home</span>
                    </a>
                    <a href="todo.php" id="todoFormNav" class="nav-link">
                        <i class="fas fa-plus-circle"></i>
                        <span>Add Todo</span>
                    </a>
                    <a href="kanban.php" id="kanbanNav" class="nav-link">
                        <i class="fas fa-columns"></i>
                        <span>Kanban</span>
                    </a>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="nav-actions">
                <!-- Theme Toggle -->
                <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">
                    <i class="fas fa-moon"></i>
                    <span class="theme-text">Dark</span>
                </button>

                <!-- Mobile Menu Toggle -->
                <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle menu">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Overlay -->
        <div class="mobile-menu-overlay" id="mobileMenuOverlay">
            <div class="mobile-menu">
                <div class="mobile-nav-links">
                    <a href="index.php" id="mobileHomeNav" class="mobile-nav-link">
                        <i class="fas fa-home"></i>
                        <span>Home</span>
                    </a>
                    <a href="todo.php" id="mobileTodoFormNav" class="mobile-nav-link">
                        <i class="fas fa-plus-circle"></i>
                        <span>Add Todo</span>
                    </a>
                    <a href="kanban.php" id="mobileKanbanNav" class="mobile-nav-link">
                        <i class="fas fa-columns"></i>
                        <span>Kanban Board</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>