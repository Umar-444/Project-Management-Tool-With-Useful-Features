// Advanced To-Do List JavaScript
class TodoApp {
    constructor() {
        this.todos = [];
        this.filteredTodos = [];
        this.currentCategoryFilter = 'all';
        this.currentPriorityFilter = 'all';
        this.currentStatusFilter = 'all';
        this.currentSort = 'newest';
        this.searchTerm = '';
        this.isDarkTheme = false;
        this.draggedElement = null;
        this.currentView = 'list'; // 'list' or 'kanban'
        this.draggedTodo = null;
        this.dragOverColumn = null;
        
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.loadTheme();
        this.loadTodos();
        this.setupDragAndDrop();
        this.setupNotifications();
    }

    setupEventListeners() {
        // Form submission
        document.getElementById('todoForm')?.addEventListener('submit', (e) => this.handleFormSubmit(e));
        
        // Search functionality
        document.getElementById('searchInput')?.addEventListener('input', (e) => this.handleSearch(e));
        
        // Filter functionality
        document.getElementById('categoryFilter')?.addEventListener('change', (e) => this.handleCategoryFilter(e));
        document.getElementById('priorityFilter')?.addEventListener('change', (e) => this.handlePriorityFilter(e));
        document.getElementById('statusFilter')?.addEventListener('change', (e) => this.handleStatusFilter(e));
        
        // Sort functionality
        document.getElementById('sortBtn')?.addEventListener('click', (e) => this.handleSort(e));
        
        // Set active navigation based on current page
        this.setActiveNavigation();
        
        // Export functionality
        document.getElementById('exportBtn')?.addEventListener('click', (e) => this.exportTodos());
        
        // Theme toggle
        document.getElementById('themeToggle')?.addEventListener('click', (e) => this.toggleTheme());

        // Mobile menu
        this.initMobileMenu();
        
        // Checkbox changes
        document.addEventListener('change', (e) => {
            if (e.target.classList.contains('todo-checkbox')) {
                this.toggleTodo(e.target);
            }
        });
        
        // Delete buttons
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('remove-btn') || e.target.classList.contains('kanban-remove-btn')) {
                this.deleteTodo(e.target);
            }
            if (e.target.classList.contains('edit-btn') || e.target.classList.contains('kanban-edit-btn')) {
                this.editTodo(e.target);
            }
        });
        
        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => this.handleKeyboardShortcuts(e));
        
        // Auto-save on input
        document.addEventListener('input', (e) => {
            if (e.target.matches('input, textarea, select')) {
                this.debounce(() => this.autoSave(), 1000)();
            }
        });
    }

    async handleFormSubmit(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const todoData = {
            title: formData.get('title').trim(),
            description: formData.get('description')?.trim() || '',
            category: formData.get('category') || 'General',
            priority: formData.get('priority') || 'Medium',
            due_date: formData.get('due_date') || null
        };

        if (!todoData.title) {
            this.showNotification('Please enter a title for your todo', 'error');
            return;
        }

        try {
            const response = await fetch('app/add.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(todoData)
            });

            if (response.ok) {
                this.showNotification('Todo added successfully!', 'success');
                e.target.reset();
                
                // Redirect to Kanban view to show the new todo
                setTimeout(() => {
                    window.location.href = 'kanban.php';
                }, 1000);
            } else {
                throw new Error('Failed to add todo');
            }
        } catch (error) {
            this.showNotification('Error adding todo. Please try again.', 'error');
            console.error('Error:', error);
        }
    }

    async loadTodos() {
        try {
            const response = await fetch('app/get_todos.php', {
                method: 'GET',
                headers: {
                    'Cache-Control': 'no-cache',
                    'Pragma': 'no-cache'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            if (data.success) {
                this.todos = data.todos;
                this.applyFilters();
                this.updateStatistics();
                this.renderTodos();
            } else {
                throw new Error(data.message || 'Failed to load todos');
            }
        } catch (error) {
            console.error('Error loading todos:', error);
            this.showNotification(`❌ ${error.message || 'Error loading todos'}`, 'error');
        }
    }

    applyFilters() {
        this.filteredTodos = this.todos.filter(todo => {
            const matchesSearch = !this.searchTerm ||
                todo.title.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                todo.description.toLowerCase().includes(this.searchTerm.toLowerCase());

            const matchesCategory = this.currentCategoryFilter === 'all' || todo.category === this.currentCategoryFilter;
            const matchesPriority = this.currentPriorityFilter === 'all' || todo.priority === this.currentPriorityFilter;
            const matchesStatus = this.currentStatusFilter === 'all' ||
                (this.currentStatusFilter === 'completed' && todo.checked) ||
                (this.currentStatusFilter === 'pending' && !todo.checked);

            return matchesSearch && matchesCategory && matchesPriority && matchesStatus;
        });

        this.sortTodos();
    }

    sortTodos() {
        this.filteredTodos.sort((a, b) => {
            switch (this.currentSort) {
                case 'newest':
                    return new Date(b.date_time) - new Date(a.date_time);
                case 'oldest':
                    return new Date(a.date_time) - new Date(b.date_time);
                case 'priority':
                    const priorityOrder = { 'Urgent': 4, 'High': 3, 'Medium': 2, 'Low': 1 };
                    return priorityOrder[b.priority] - priorityOrder[a.priority];
                case 'alphabetical':
                    return a.title.localeCompare(b.title);
                case 'due_date':
                    if (!a.due_date && !b.due_date) return 0;
                    if (!a.due_date) return 1;
                    if (!b.due_date) return -1;
                    return new Date(a.due_date) - new Date(b.due_date);
                default:
                    return 0;
            }
        });
    }

    renderTodos() {
        // Only render Kanban view since we're using sections now
        this.renderKanbanView();
    }

    renderKanbanView() {
        const columns = ['todo', 'in_progress', 'review', 'done'];
        
        columns.forEach(column => {
            const columnElement = document.getElementById(`${column}Column`);
            const countElement = document.getElementById(`${column}Count`);
            
            if (!columnElement || !countElement) return;
            
            const columnTodos = this.filteredTodos.filter(todo => todo.kanban_column === column);
            countElement.textContent = columnTodos.length;
            
            if (columnTodos.length === 0) {
                columnElement.innerHTML = `
                    <div class="kanban-column-empty">
                        <i class="fas fa-inbox"></i>
                        <p>No todos in this column</p>
                    </div>
                `;
            } else {
                columnElement.innerHTML = columnTodos.map(todo => this.getKanbanTodoHTML(todo)).join('');
            }
        });
        
        // Add animations
        this.animateKanbanTodos();
    }

    getTodoHTML(todo) {
        const isOverdue = todo.due_date && new Date(todo.due_date) < new Date() && !todo.checked;
        const dueDateFormatted = todo.due_date ? new Date(todo.due_date).toLocaleDateString() : '';
        
        return `
            <div class="todo-item ${todo.checked ? 'checked' : ''} priority-${todo.priority.toLowerCase()}" 
                 data-id="${todo.id}" draggable="true">
                <div class="todo-header">
                    <div class="todo-checkbox ${todo.checked ? 'checked' : ''}" 
                         data-todo-id="${todo.id}"></div>
                    <div class="todo-content">
                        <h3 class="todo-title">${this.escapeHtml(todo.title)}</h3>
                        ${todo.description ? `<p class="todo-description">${this.escapeHtml(todo.description)}</p>` : ''}
                        <div class="todo-meta">
                            <span class="todo-category" style="background-color: ${todo.color || '#185cff'}">
                                ${this.getCategoryIcon(todo.category)} ${todo.category}
                            </span>
                            <span class="todo-priority priority-${todo.priority.toLowerCase()}">
                                ${todo.priority}
                            </span>
                            ${todo.due_date ? `
                                <span class="todo-due-date ${isOverdue ? 'overdue' : ''}">
                                    📅 ${dueDateFormatted}
                                </span>
                            ` : ''}
                        </div>
                        <div class="todo-date">
                            Created: ${new Date(todo.date_time).toLocaleString()}
                            ${todo.completed_at ? ` | Completed: ${new Date(todo.completed_at).toLocaleString()}` : ''}
                        </div>
                    </div>
                    <div class="todo-actions">
                        <button class="todo-action-btn edit-btn" data-id="${todo.id}" title="Edit">
                            ✏️
                        </button>
                        <button class="todo-action-btn remove-btn" data-id="${todo.id}" title="Delete">
                            🗑️
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    getKanbanTodoHTML(todo) {
        const isOverdue = todo.due_date && new Date(todo.due_date) < new Date() && !todo.checked;
        const dueDateFormatted = todo.due_date ? new Date(todo.due_date).toLocaleDateString() : '';
        
        return `
            <div class="kanban-todo-item priority-${todo.priority.toLowerCase()}" 
                 data-id="${todo.id}" 
                 data-column="${todo.kanban_column}"
                 draggable="true">
                <div class="kanban-todo-header">
                    <h4 class="kanban-todo-title">${this.escapeHtml(todo.title)}</h4>
                    <div class="kanban-todo-actions">
                        <button class="kanban-todo-action-btn kanban-edit-btn" data-id="${todo.id}" title="Edit">
                            ✏️
                        </button>
                        <button class="kanban-todo-action-btn kanban-remove-btn" data-id="${todo.id}" title="Delete">
                            🗑️
                        </button>
                    </div>
                </div>
                ${todo.description ? `<p class="kanban-todo-description">${this.escapeHtml(todo.description)}</p>` : ''}
                <div class="kanban-todo-meta">
                    <span class="kanban-todo-category" style="background-color: ${todo.color || '#185cff'}">
                        ${this.getCategoryIcon(todo.category)} ${todo.category}
                    </span>
                    <span class="kanban-todo-priority priority-${todo.priority.toLowerCase()}">
                        ${todo.priority}
                    </span>
                </div>
                ${todo.due_date ? `
                    <div class="kanban-todo-due-date ${isOverdue ? 'overdue' : ''}">
                        📅 ${dueDateFormatted}
                    </div>
                ` : ''}
                <div class="kanban-todo-date">
                    Created: ${new Date(todo.date_time).toLocaleDateString()}
                </div>
            </div>
        `;
    }

    getEmptyStateHTML() {
        return `
            <div class="empty-state">
                <div class="empty-icon">📝</div>
                <h3>No todos found</h3>
                <p>${this.searchTerm ? 'Try adjusting your search or filters' : 'Add your first todo to get started!'}</p>
            </div>
        `;
    }

    async toggleTodo(checkbox) {
        const todoId = checkbox.dataset.todoId;
        const todo = this.todos.find(t => t.id == todoId);
        
        if (!todo) return;

        try {
            const response = await fetch('app/check.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: todoId })
            });

            if (response.ok) {
                todo.checked = !todo.checked;
                todo.completed_at = todo.checked ? new Date().toISOString() : null;
                
                this.applyFilters();
                this.updateStatistics();
                this.renderTodos();
                
                this.showNotification(
                    todo.checked ? 'Todo completed! 🎉' : 'Todo marked as pending',
                    'success'
                );
                
                this.animateCompletion(todo);
            }
        } catch (error) {
            console.error('Error toggling todo:', error);
            this.showNotification('Error updating todo', 'error');
        }
    }

    async deleteTodo(button) {
        const todoId = button.dataset.id;
        
        if (!confirm('Are you sure you want to delete this todo?')) {
            return;
        }

        try {
            const response = await fetch('app/remove.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: todoId })
            });

            if (response.ok) {
                this.todos = this.todos.filter(t => t.id != todoId);
                this.applyFilters();
                this.updateStatistics();
                this.renderTodos();
                
                this.showNotification('Todo deleted successfully', 'success');
                this.animateDeletion(button.closest('.todo-item'));
            }
        } catch (error) {
            console.error('Error deleting todo:', error);
            this.showNotification('Error deleting todo', 'error');
        }
    }

    editTodo(button) {
        const todoId = button.dataset.id;
        const todo = this.todos.find(t => t.id == todoId);
        
        if (!todo) return;

        // Create edit modal or inline editing
        this.showEditModal(todo);
    }

    showEditModal(todo) {
        // Implementation for edit modal
        const modal = document.createElement('div');
        modal.className = 'edit-modal';
        modal.innerHTML = `
            <div class="modal-content">
                <h3>Edit Todo</h3>
                <form id="editForm">
                    <input type="hidden" name="id" value="${todo.id}">
                    <input type="text" name="title" value="${this.escapeHtml(todo.title)}" required>
                    <textarea name="description" placeholder="Description">${this.escapeHtml(todo.description || '')}</textarea>
                    <select name="category">
                        <option value="General" ${todo.category === 'General' ? 'selected' : ''}>General</option>
                        <option value="Work" ${todo.category === 'Work' ? 'selected' : ''}>Work</option>
                        <option value="Personal" ${todo.category === 'Personal' ? 'selected' : ''}>Personal</option>
                        <option value="Shopping" ${todo.category === 'Shopping' ? 'selected' : ''}>Shopping</option>
                        <option value="Health" ${todo.category === 'Health' ? 'selected' : ''}>Health</option>
                        <option value="Learning" ${todo.category === 'Learning' ? 'selected' : ''}>Learning</option>
                    </select>
                    <select name="priority">
                        <option value="Low" ${todo.priority === 'Low' ? 'selected' : ''}>Low</option>
                        <option value="Medium" ${todo.priority === 'Medium' ? 'selected' : ''}>Medium</option>
                        <option value="High" ${todo.priority === 'High' ? 'selected' : ''}>High</option>
                        <option value="Urgent" ${todo.priority === 'Urgent' ? 'selected' : ''}>Urgent</option>
                    </select>
                    <input type="datetime-local" name="due_date" value="${todo.due_date ? todo.due_date.slice(0, 16) : ''}">
                    <div class="modal-actions">
                        <button type="button" class="cancel-btn">Cancel</button>
                        <button type="submit" class="save-btn">Save Changes</button>
                    </div>
                </form>
            </div>
        `;

        document.body.appendChild(modal);

        // Handle form submission
        modal.querySelector('#editForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            await this.updateTodo(new FormData(e.target));
            document.body.removeChild(modal);
        });

        // Handle cancel
        modal.querySelector('.cancel-btn').addEventListener('click', () => {
            document.body.removeChild(modal);
        });
    }

    async updateTodo(formData) {
        const todoData = {
            id: formData.get('id'),
            title: formData.get('title').trim(),
            description: formData.get('description').trim(),
            category: formData.get('category'),
            priority: formData.get('priority'),
            due_date: formData.get('due_date') || null
        };

        try {
            const response = await fetch('app/update.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(todoData)
            });

            if (response.ok) {
                this.showNotification('Todo updated successfully!', 'success');
                this.loadTodos();
            }
        } catch (error) {
            console.error('Error updating todo:', error);
            this.showNotification('Error updating todo', 'error');
        }
    }

    handleSearch(e) {
        this.searchTerm = e.target.value;
        this.applyFilters();
        this.renderTodos();
    }

    handleCategoryFilter(e) {
        this.currentCategoryFilter = e.target.value;
        this.applyFilters();
        this.renderTodos();
    }

    handlePriorityFilter(e) {
        this.currentPriorityFilter = e.target.value;
        this.applyFilters();
        this.renderTodos();
    }

    handleStatusFilter(e) {
        this.currentStatusFilter = e.target.value;
        this.applyFilters();
        this.renderTodos();
    }

    handleSort(e) {
        const sortOptions = ['newest', 'oldest', 'priority', 'alphabetical', 'due_date'];
        const currentIndex = sortOptions.indexOf(this.currentSort);
        this.currentSort = sortOptions[(currentIndex + 1) % sortOptions.length];
        
        e.target.textContent = `Sort: ${this.currentSort.charAt(0).toUpperCase() + this.currentSort.slice(1)}`;
        this.applyFilters();
        this.renderTodos();
    }

    toggleTheme() {
        this.isDarkTheme = !this.isDarkTheme;
        document.documentElement.setAttribute('data-theme', this.isDarkTheme ? 'dark' : 'light');
        localStorage.setItem('theme', this.isDarkTheme ? 'dark' : 'light');

        const icon = document.querySelector('#themeToggle i');
        const themeText = document.querySelector('#themeToggle .theme-text');

        if (this.isDarkTheme) {
            icon.className = 'fas fa-sun';
            if (themeText) themeText.textContent = 'Light';
        } else {
            icon.className = 'fas fa-moon';
            if (themeText) themeText.textContent = 'Dark';
        }

        this.showNotification(`Switched to ${this.isDarkTheme ? 'dark' : 'light'} theme`, 'success');
    }

    loadTheme() {
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            this.isDarkTheme = savedTheme === 'dark';
            document.documentElement.setAttribute('data-theme', savedTheme);
        }
        
        const icon = document.querySelector('#themeToggle i');
        const themeText = document.querySelector('#themeToggle .theme-text');

        if (icon) {
            if (this.isDarkTheme) {
                icon.className = 'fas fa-sun';
                if (themeText) themeText.textContent = 'Light';
            } else {
                icon.className = 'fas fa-moon';
                if (themeText) themeText.textContent = 'Dark';
            }
        }
    }

    updateStatistics() {
        const total = this.todos.length;
        const completed = this.todos.filter(t => t.checked).length;
        const pending = total - completed;
        const overdue = this.todos.filter(t =>
            t.due_date && new Date(t.due_date) < new Date() && !t.checked
        ).length;

        // Only update statistics if elements exist (they're only on kanban.php)
        const totalEl = document.getElementById('totalTodos');
        const completedEl = document.getElementById('completedTodos');
        const pendingEl = document.getElementById('pendingTodos');
        const overdueEl = document.getElementById('overdueTodos');

        if (totalEl) totalEl.textContent = total;
        if (completedEl) completedEl.textContent = completed;
        if (pendingEl) pendingEl.textContent = pending;
        if (overdueEl) overdueEl.textContent = overdue;

        // Update progress bar only if it exists
        const progressFill = document.querySelector('.progress-fill');
        if (progressFill) {
            const progress = total > 0 ? (completed / total) * 100 : 0;
            progressFill.style.width = `${progress}%`;
        }
    }

    setupDragAndDrop() {
        // List view drag and drop
        document.addEventListener('dragstart', (e) => {
            if (e.target.classList.contains('todo-item')) {
                this.draggedElement = e.target;
                e.target.classList.add('dragging');
            } else if (e.target.classList.contains('kanban-todo-item')) {
                this.draggedTodo = e.target;
                e.target.classList.add('dragging');
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/html', e.target.outerHTML);
            }
        });

        document.addEventListener('dragend', (e) => {
            if (e.target.classList.contains('todo-item')) {
                e.target.classList.remove('dragging');
                this.draggedElement = null;
            } else if (e.target.classList.contains('kanban-todo-item')) {
                e.target.classList.remove('dragging');
                this.draggedTodo = null;
                this.clearDragOverStates();
            }
        });

        document.addEventListener('dragover', (e) => {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            
            if (this.draggedTodo) {
                const column = e.target.closest('.kanban-column');
                if (column) {
                    this.setDragOverState(column);
                }
            }
        });

        document.addEventListener('dragleave', (e) => {
            if (this.draggedTodo) {
                const column = e.target.closest('.kanban-column');
                if (column && !column.contains(e.relatedTarget)) {
                    column.classList.remove('drag-over');
                    column.querySelector('.column-content').classList.remove('drag-over');
                }
            }
        });

        document.addEventListener('drop', (e) => {
            e.preventDefault();
            
            if (this.draggedTodo) {
                const targetColumn = e.target.closest('.kanban-column');
                if (targetColumn) {
                    this.handleKanbanDrop(this.draggedTodo, targetColumn);
                }
            } else if (this.draggedElement && e.target.classList.contains('todo-item')) {
                this.handleReorder(this.draggedElement, e.target);
            }
        });
    }

    handleReorder(draggedElement, targetElement) {
        // Implementation for reordering todos
        const draggedId = draggedElement.dataset.id;
        const targetId = targetElement.dataset.id;
        
        // Update order in database and refresh
        this.updateTodoOrder(draggedId, targetId);
    }

    async updateTodoOrder(draggedId, targetId) {
        try {
            await fetch('app/reorder.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ draggedId, targetId })
            });
            
            this.loadTodos();
        } catch (error) {
            console.error('Error reordering todos:', error);
        }
    }

    // Navigation methods
    switchSection(section) {
        // Update nav link states
        document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
        
        if (section === 'todoForm') {
            document.getElementById('todoFormNav').classList.add('active');
            document.getElementById('todoFormSection').classList.add('active');
            document.getElementById('kanbanSection').classList.remove('active');
        } else if (section === 'kanban') {
            document.getElementById('kanbanNav').classList.add('active');
            document.getElementById('kanbanSection').classList.add('active');
            document.getElementById('todoFormSection').classList.remove('active');
            
            // Load and render todos when switching to Kanban
            this.loadTodos();
        }
        
        this.showNotification(`Switched to ${section === 'todoForm' ? 'Add Todo' : 'Kanban Board'}`, 'success');
    }

    setActiveNavigation() {
        // Set active navigation based on current page
        const currentPage = window.location.pathname.split('/').pop();

        // Clear all active states
        document.querySelectorAll('.nav-link, .mobile-nav-link').forEach(link => link.classList.remove('active'));

        if (currentPage === 'index.php' || currentPage === '') {
            document.getElementById('homeNav')?.classList.add('active');
            document.getElementById('mobileHomeNav')?.classList.add('active');
        } else if (currentPage === 'todo.php') {
            document.getElementById('todoFormNav')?.classList.add('active');
            document.getElementById('mobileTodoFormNav')?.classList.add('active');
        } else if (currentPage === 'kanban.php') {
            document.getElementById('kanbanNav')?.classList.add('active');
            document.getElementById('mobileKanbanNav')?.classList.add('active');
        }
    }

    initMobileMenu() {
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
        const mobileMenuLinks = document.querySelectorAll('.mobile-nav-link');

        if (mobileMenuToggle && mobileMenuOverlay) {
            // Toggle mobile menu
            mobileMenuToggle.addEventListener('click', () => {
                mobileMenuOverlay.classList.toggle('active');
                mobileMenuToggle.classList.toggle('active');
            });

            // Close mobile menu when clicking on a link
            mobileMenuLinks.forEach(link => {
                link.addEventListener('click', () => {
                    mobileMenuOverlay.classList.remove('active');
                    mobileMenuToggle.classList.remove('active');
                });
            });

            // Close mobile menu when clicking outside
            mobileMenuOverlay.addEventListener('click', (e) => {
                if (e.target === mobileMenuOverlay) {
                    mobileMenuOverlay.classList.remove('active');
                    mobileMenuToggle.classList.remove('active');
                }
            });

            // Close mobile menu on escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && mobileMenuOverlay.classList.contains('active')) {
                    mobileMenuOverlay.classList.remove('active');
                    mobileMenuToggle.classList.remove('active');
                }
            });
        }
    }

    setDragOverState(column) {
        this.clearDragOverStates();
        column.classList.add('drag-over');
        column.querySelector('.column-content').classList.add('drag-over');
    }

    clearDragOverStates() {
        document.querySelectorAll('.kanban-column').forEach(col => {
            col.classList.remove('drag-over');
            col.querySelector('.column-content').classList.remove('drag-over');
        });
    }

    async handleKanbanDrop(draggedElement, targetColumn) {
        const todoId = draggedElement.dataset.id;
        const newColumn = targetColumn.dataset.column;
        const oldColumn = draggedElement.dataset.column;
        
        if (newColumn === oldColumn) {
            this.clearDragOverStates();
            return;
        }

        try {
            const response = await fetch('app/update_kanban.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ 
                    todoId: todoId, 
                    column: newColumn,
                    order: 0
                })
            });

            if (response.ok) {
                // Update local data
                const todo = this.todos.find(t => t.id == todoId);
                if (todo) {
                    todo.kanban_column = newColumn;
                    if (newColumn === 'done') {
                        todo.checked = 1;
                        todo.completed_at = new Date().toISOString();
                    } else {
                        todo.checked = 0;
                        todo.completed_at = null;
                    }
                }
                
                this.applyFilters();
                this.updateStatistics();
                this.renderTodos();
                
                this.showNotification(`Todo moved to ${newColumn.replace('_', ' ')}`, 'success');
            }
        } catch (error) {
            console.error('Error moving todo:', error);
            this.showNotification('Error moving todo', 'error');
        }
        
        this.clearDragOverStates();
    }

    async exportTodos() {
        try {
            const response = await fetch('app/export.php?format=json');
            const data = await response.json();
            
            const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            
            const a = document.createElement('a');
            a.href = url;
            a.download = `todos-${new Date().toISOString().split('T')[0]}.json`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
            
            this.showNotification('Todos exported successfully!', 'success');
        } catch (error) {
            console.error('Error exporting todos:', error);
            this.showNotification('Error exporting todos', 'error');
        }
    }

    animateKanbanTodos() {
        const todos = document.querySelectorAll('.kanban-todo-item');
        todos.forEach((todo, index) => {
            todo.style.opacity = '0';
            todo.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                todo.style.transition = 'all 0.3s ease';
                todo.style.opacity = '1';
                todo.style.transform = 'translateY(0)';
            }, index * 50);
        });
    }

    setupNotifications() {
        // Create notification container if it doesn't exist
        if (!document.getElementById('notificationContainer')) {
            const container = document.createElement('div');
            container.id = 'notificationContainer';
            document.body.appendChild(container);
        }
    }

    showNotification(message, type = 'info') {
        const container = document.getElementById('notificationContainer');
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        
        const icons = {
            success: '✅',
            error: '❌',
            warning: '⚠️',
            info: 'ℹ️'
        };

        notification.innerHTML = `
            <div class="notification-icon">${icons[type] || icons.info}</div>
            <div class="notification-content">
                <div class="notification-title">${type.charAt(0).toUpperCase() + type.slice(1)}</div>
                <div class="notification-message">${message}</div>
            </div>
            <button class="notification-close">×</button>
        `;

        container.appendChild(notification);

        // Show notification
        setTimeout(() => notification.classList.add('show'), 100);

        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 5000);

        // Handle close button
        notification.querySelector('.notification-close').addEventListener('click', () => {
            notification.classList.remove('show');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        });
    }

    handleKeyboardShortcuts(e) {
        // Ctrl/Cmd + N: New todo
        if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
            e.preventDefault();
            document.querySelector('input[name="title"]')?.focus();
        }
        
        // Escape: Close modals
        if (e.key === 'Escape') {
            const modal = document.querySelector('.edit-modal');
            if (modal) {
                document.body.removeChild(modal);
            }
        }
    }

    animateTodos() {
        const todos = document.querySelectorAll('.todo-item');
        todos.forEach((todo, index) => {
            todo.style.opacity = '0';
            todo.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                todo.style.transition = 'all 0.3s ease';
                todo.style.opacity = '1';
                todo.style.transform = 'translateY(0)';
            }, index * 100);
        });
    }

    animateAddTodo() {
        const container = document.getElementById('todosContainer');
        if (container) {
            container.style.transform = 'scale(1.05)';
            setTimeout(() => {
                container.style.transform = 'scale(1)';
            }, 200);
        }
    }

    animateCompletion(todo) {
        const todoElement = document.querySelector(`[data-id="${todo.id}"]`);
        if (todoElement) {
            todoElement.style.transform = 'scale(1.1)';
            todoElement.style.background = 'rgba(76, 175, 80, 0.2)';
            
            setTimeout(() => {
                todoElement.style.transform = 'scale(1)';
                todoElement.style.background = '';
            }, 500);
        }
    }

    animateDeletion(element) {
        element.style.transform = 'scale(0.8)';
        element.style.opacity = '0';
        
        setTimeout(() => {
            if (element.parentNode) {
                element.parentNode.removeChild(element);
            }
        }, 300);
    }

    getCategoryIcon(category) {
        const icons = {
            'General': '📝',
            'Work': '💼',
            'Personal': '👤',
            'Shopping': '🛒',
            'Health': '🏥',
            'Learning': '📚'
        };
        return icons[category] || '📝';
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    autoSave() {
        // Implementation for auto-save functionality
        console.log('Auto-saving...');
    }
}

// Initialize the app when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.todoApp = new TodoApp();
});

// Service Worker for offline functionality
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then(registration => {
                console.log('SW registered: ', registration);

                // Force update check
                registration.update();

                // Listen for updates
                registration.addEventListener('updatefound', () => {
                    const newWorker = registration.installing;
                    if (newWorker) {
                        newWorker.addEventListener('statechange', () => {
                            if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                // New version available, show notification
                                console.log('New version available, refreshing...');
                                window.location.reload();
                            }
                        });
                    }
                });
            })
            .catch(registrationError => {
                console.log('SW registration failed: ', registrationError);
            });
    });
}
