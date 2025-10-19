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
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Theme Toggle -->
    <div class="theme-toggle" id="themeToggle">
        <i>🌙</i>
    </div>

    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-brand">
                <h1>📋 Advanced To-Do List</h1>
            </div>
            <div class="nav-links">
                <a href="#" id="todoFormNav" class="nav-link active">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add Todo</span>
                </a>
                <a href="#" id="kanbanNav" class="nav-link">
                    <i class="fas fa-columns"></i>
                    <span>Kanban Board</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <div class="main-container">
        <!-- Todo Form Section -->
        <section id="todoFormSection" class="content-section active">
            <div class="section-header">
                <h2>Add New Todo</h2>
                <p>Create and manage your tasks efficiently</p>
            </div>
            
    <div class="main-section">
        <!-- Add Todo Section -->
       <div class="add-section">
            <form id="todoForm" autocomplete="off">
                <div class="form-row">
                    <div class="form-group">
                        <label for="title">What needs to be done?</label>
                        <input type="text" id="title" name="title" placeholder="Enter your todo..." required>
                    </div>
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select id="category" name="category">
                            <option value="General">📝 General</option>
                            <option value="Work">💼 Work</option>
                            <option value="Personal">👤 Personal</option>
                            <option value="Shopping">🛒 Shopping</option>
                            <option value="Health">🏥 Health</option>
                            <option value="Learning">📚 Learning</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="priority">Priority</label>
                        <select id="priority" name="priority">
                            <option value="Low">🟢 Low</option>
                            <option value="Medium" selected>🟡 Medium</option>
                            <option value="High">🟠 High</option>
                            <option value="Urgent">🔴 Urgent</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="description">Description (Optional)</label>
                    <textarea id="description" name="description" placeholder="Add more details..."></textarea>
                </div>
                <div class="form-group">
                    <label for="due_date">Due Date (Optional)</label>
                    <input type="datetime-local" id="due_date" name="due_date">
                </div>
                <button type="submit" class="add-btn">
                    <i class="fas fa-plus"></i>
                    Add Todo
                </button>
          </form>
       </div>

        <!-- Statistics Section -->
        <div class="stats-section">
            <div class="stat-card">
                <div class="stat-number" id="totalTodos">0</div>
                <div class="stat-label">Total Todos</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="completedTodos">0</div>
                <div class="stat-label">Completed</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="pendingTodos">0</div>
                <div class="stat-label">Pending</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="overdueTodos">0</div>
                <div class="stat-label">Overdue</div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="progress-bar">
            <div class="progress-fill" style="width: 0%"></div>
        </div>

            </div>
        </section>

        <!-- Kanban Board Section -->
        <section id="kanbanSection" class="content-section">
            
            <!-- Filter and Search Section -->
            <div class="filter-section">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Search todos...">
                </div>
                <select id="categoryFilter" class="filter-select">
                    <option value="all">All Categories</option>
                    <option value="General">📝 General</option>
                    <option value="Work">💼 Work</option>
                    <option value="Personal">👤 Personal</option>
                    <option value="Shopping">🛒 Shopping</option>
                    <option value="Health">🏥 Health</option>
                    <option value="Learning">📚 Learning</option>
                </select>
                <select id="priorityFilter" class="filter-select">
                    <option value="all">All Priorities</option>
                    <option value="Low">🟢 Low</option>
                    <option value="Medium">🟡 Medium</option>
                    <option value="High">🟠 High</option>
                    <option value="Urgent">🔴 Urgent</option>
                </select>
                <select id="statusFilter" class="filter-select">
                    <option value="all">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                </select>
                <button id="sortBtn" class="sort-btn">
                    <i class="fas fa-sort"></i>
                    Sort: Newest
                </button>
                <button id="exportBtn" class="sort-btn" style="background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%);">
                    <i class="fas fa-download"></i>
                    Export
                </button>
            </div>

            <!-- Statistics Section -->
            <div class="stats-section">
                <div class="stat-card">
                    <div class="stat-number" id="totalTodos">0</div>
                    <div class="stat-label">Total</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="completedTodos">0</div>
                    <div class="stat-label">Completed</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="pendingTodos">0</div>
                    <div class="stat-label">Pending</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="overdueTodos">0</div>
                    <div class="stat-label">Overdue</div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="progress-bar">
                <div class="progress-fill" style="width: 0%"></div>
            </div>

            <!-- Kanban Board Container -->
            <div class="kanban-board" style="display: flex; flex-direction: row; gap: 2rem;">
                <div class="kanban-column" data-column="todo" style="flex: 1 1 0;">
                    <div class="column-header">
                        <h3>📋 To Do</h3>
                        <span class="column-count" id="todoCount">0</span>
                    </div>
                    <div class="column-content" id="todoColumn">
                        <!-- Todo items will be loaded here -->
                    </div>
                </div>
                
                <div class="kanban-column" data-column="in_progress" style="flex: 1 1 0;">
                    <div class="column-header">
                        <h3>⚡ In Progress</h3>
                        <span class="column-count" id="in_progressCount">0</span>
                    </div>
                    <div class="column-content" id="in_progressColumn">
                        <!-- In progress items will be loaded here -->
                    </div>
                </div>
                <!-- 
                FIXED: 
                - id and data-column must be in_progressColumn / in_progressCount for consistency with renderKanbanView in script.js.
                - Confirmed script.js uses "in_progressColumn", "in_progressCount" (not "inProgressColumn").
                -->
                
                <div class="kanban-column" data-column="review" style="flex: 1 1 0;">
                    <div class="column-header">
                        <h3>👀 Review</h3>
                        <span class="column-count" id="reviewCount">0</span>
                    </div>
                    <div class="column-content" id="reviewColumn">
                        <!-- Review items will be loaded here -->
                    </div>
                </div>

                <div class="kanban-column" data-column="done" style="flex: 1 1 0;">
                    <div class="column-header">
                        <h3>✅ Done</h3>
                        <span class="column-count" id="doneCount">0</span>
                    </div>
                    <div class="column-content" id="doneColumn">
                        <!-- Done items will be loaded here -->
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Notification Container -->
    <div id="notificationContainer"></div>

    <!-- Scripts -->
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/script.js"></script>

    <!-- Edit Modal Styles -->
    <style>
        .edit-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background: var(--card-bg);
            border-radius: var(--border-radius);
            padding: 2rem;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: var(--shadow-hover);
        }

        .modal-content h3 {
            margin-bottom: 1.5rem;
            color: var(--text-dark);
            font-size: 1.5rem;
        }

        .modal-content form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .modal-content input,
        .modal-content textarea,
        .modal-content select {
            padding: 0.75rem;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            background: var(--card-bg);
            color: var(--text-dark);
            font-family: inherit;
            transition: var(--transition);
        }

        .modal-content input:focus,
        .modal-content textarea:focus,
        .modal-content select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .modal-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .cancel-btn,
        .save-btn {
            flex: 1;
            padding: 0.75rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }

        .cancel-btn {
            background: var(--border-color);
            color: var(--text-dark);
        }

        .cancel-btn:hover {
            background: var(--text-light);
            color: white;
        }

        .save-btn {
            background: var(--primary-gradient);
            color: white;
        }

        .save-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
    </style>
</body>
</html>