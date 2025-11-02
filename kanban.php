<?php include 'include/header.php'; ?>

    <!-- Main Content Container -->
    <div class="main-container" style="max-width: 1600px; margin: 0 auto;">
        <!-- Kanban Board Section -->
        <section id="kanbanSection" class="content-section active">
            <div class="section-header">
                <h2>Kanban Board</h2>
            </div>
            
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

            <!-- Statistics Section -->
            <div class="stats-section" style="margin-bottom: 1.5rem;">
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

            <!-- All Progress Bars In One Row -->
            <div class="progress-bar-row" style="display: flex; gap: 1.5rem; margin-bottom: 2rem; flex-wrap: wrap;">
                <div style="flex:1; min-width:180px;">
                    <div style="display:flex;align-items:center;justify-content:space-between; margin-bottom:0.25rem;">
                        <span style="font-weight:600;">Completed</span>
                        <span id="progress-completed-label">0%</span>
                    </div>
                    <div class="progress-bar" style="height: 16px;">
                        <div class="progress-fill" id="progress-completed" style="width:0%;background:var(--success-color);transition:width .5s;"></div>
                    </div>
                </div>
                <div style="flex:1; min-width:180px;">
                    <div style="display:flex;align-items:center;justify-content:space-between; margin-bottom:0.25rem;">
                        <span style="font-weight:600;">Pending</span>
                        <span id="progress-pending-label">0%</span>
                    </div>
                    <div class="progress-bar" style="height: 16px;">
                        <div class="progress-fill" id="progress-pending" style="width:0%;background:var(--warning-color);transition:width .5s;"></div>
                    </div>
                </div>
                <div style="flex:1; min-width:180px;">
                    <div style="display:flex;align-items:center;justify-content:space-between; margin-bottom:0.25rem;">
                        <span style="font-weight:600;">Overdue</span>
                        <span id="progress-overdue-label">0%</span>
                    </div>
                    <div class="progress-bar" style="height: 16px;">
                        <div class="progress-fill" id="progress-overdue" style="width:0%;background:var(--danger-color);transition:width .5s;"></div>
                    </div>
                </div>
                <div style="flex:1; min-width:180px;">
                    <div style="display:flex;align-items:center;justify-content:space-between; margin-bottom:0.25rem;">
                        <span style="font-weight:600;">Total Progress</span>
                        <span id="progress-total-label">0%</span>
                    </div>
                    <div class="progress-bar" style="height: 16px;">
                        <div class="progress-fill" id="progress-total" style="width:0%;background:var(--primary-color);transition:width .5s;"></div>
                    </div>
                </div>
            </div>
            <script>
                // Progress bar dynamic update (must be improved in js/script.js for real data)
                function updateKanbanProgressBars() {
                    const total = parseInt(document.getElementById('totalTodos').textContent) || 0;
                    const completed = parseInt(document.getElementById('completedTodos').textContent) || 0;
                    const pending = parseInt(document.getElementById('pendingTodos').textContent) || 0;
                    const overdue = parseInt(document.getElementById('overdueTodos').textContent) || 0;

                    function percent(val) {
                        if (!total) return 0;
                        return Math.round((val/total)*100);
                    }

                    const completedPct = percent(completed);
                    const pendingPct = percent(pending);
                    const overduePct = percent(overdue);
                    const totalPct = completedPct;

                    document.getElementById('progress-completed').style.width = completedPct + "%";
                    document.getElementById('progress-completed-label').textContent = completedPct + "%";
                    document.getElementById('progress-pending').style.width = pendingPct + "%";
                    document.getElementById('progress-pending-label').textContent = pendingPct + "%";
                    document.getElementById('progress-overdue').style.width = overduePct + "%";
                    document.getElementById('progress-overdue-label').textContent = overduePct + "%";
                    document.getElementById('progress-total').style.width = totalPct + "%";
                    document.getElementById('progress-total-label').textContent = totalPct + "%";
                }

                // Update on page load and when stats change (adjust real logic location if needed)
                document.addEventListener('DOMContentLoaded', updateKanbanProgressBars);
                [
                    'totalTodos','completedTodos','pendingTodos','overdueTodos'
                ].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) {
                        new MutationObserver(updateKanbanProgressBars).observe(el, {characterData:true,childList:true,subtree:true});
                    }
                });
            </script>

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