<?php include 'include/header.php'; ?>

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
                <button id="exportBtn" class="sort-btn" style="background: var(--success-color);">
                    <i class="fas fa-download"></i>
                    Export
                </button>
            </div>

            <!-- Kanban Board Container -->
            <div class="kanban-board">
                <div class="kanban-column" data-column="todo">
                    <div class="column-header">
                        <h3>📋 To Do</h3>
                        <span class="column-count" id="todoCount">0</span>
                    </div>
                    <div class="column-content" id="todoColumn">
                        <!-- Todo items will be loaded here -->
                    </div>
                </div>

                <div class="kanban-column" data-column="in_progress">
                    <div class="column-header">
                        <h3>⚡ In Progress</h3>
                        <span class="column-count" id="in_progressCount">0</span>
                    </div>
                    <div class="column-content" id="in_progressColumn">
                        <!-- In progress items will be loaded here -->
                    </div>
                </div>

                <div class="kanban-column" data-column="review">
                    <div class="column-header">
                        <h3>👀 Review</h3>
                        <span class="column-count" id="reviewCount">0</span>
                    </div>
                    <div class="column-content" id="reviewColumn">
                        <!-- Review items will be loaded here -->
                    </div>
                </div>

                <div class="kanban-column" data-column="done">
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

<?php include 'include/footer.php'; ?>

</body>
</html>