<?php include 'include/header.php'; ?>

    <!-- Main Content Container -->
    <div class="main-container">
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="hero-content">
                <div class="hero-text">
                    <h1>Welcome to Advanced To-Do List</h1>
                    <p>Organize your tasks efficiently with our modern, feature-rich task management application.</p>
                    <p>Choose how you'd like to get started:</p>
                </div>

                <div class="hero-actions">
                    <a href="todo.php" class="hero-btn primary-btn">
                        <i class="fas fa-plus-circle"></i>
                        <span>Add New Todo</span>
                    </a>
                    <a href="kanban.php" class="hero-btn secondary-btn">
                        <i class="fas fa-columns"></i>
                        <span>View Kanban Board</span>
                    </a>
                </div>

                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">📝</div>
                        <h3>Easy Task Creation</h3>
                        <p>Create todos with categories, priorities, and due dates</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">🎯</div>
                        <h3>Kanban Board</h3>
                        <p>Visualize your workflow with drag-and-drop kanban boards</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">🌙</div>
                        <h3>Dark/Light Theme</h3>
                        <p>Switch between themes for comfortable viewing</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">📊</div>
                        <h3>Progress Tracking</h3>
                        <p>Monitor your productivity with live statistics</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">🔍</div>
                        <h3>Advanced Filters</h3>
                        <p>Find todos quickly with powerful search and filter options</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">📱</div>
                        <h3>Responsive Design</h3>
                        <p>Works perfectly on desktop, tablet, and mobile devices</p>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Notification Container -->
    <div id="notificationContainer"></div>

<?php include 'include/footer.php'; ?>

<style>
.hero-section {
    padding: 4rem 2rem;
    text-align: center;
    max-width: 1200px;
    margin: 0 auto;
}

.hero-text h1 {
    font-size: 3rem;
    font-weight: 800;
    color: var(--primary-color);
    margin-bottom: 1rem;
}

.hero-text p {
    font-size: 1.2rem;
    color: var(--text-light);
    margin-bottom: 2rem;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.hero-actions {
    display: flex;
    gap: 2rem;
    justify-content: center;
    margin-bottom: 4rem;
    flex-wrap: wrap;
}

.hero-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem 2rem;
    border-radius: var(--border-radius);
    text-decoration: none;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    box-shadow: var(--shadow);
}

.primary-btn {
    background: var(--primary-color);
    color: white;
}

.primary-btn:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-hover);
    background: #0b5ed7;
}

.secondary-btn {
    background: var(--light-color);
    color: var(--text-dark);
    border: 1px solid var(--border-color);
}

.secondary-btn:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-hover);
    background: #dee2e6;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-top: 3rem;
}

.feature-card {
    background: var(--card-bg);
    padding: 2rem;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
    border: 1px solid var(--border-color);
}

.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-hover);
}

.feature-icon {
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

.feature-card h3 {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: var(--text-dark);
}

.feature-card p {
    color: var(--text-light);
    font-size: 0.95rem;
}

@media (max-width: 768px) {
    .hero-text h1 {
        font-size: 2.2rem;
    }

    .hero-actions {
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }

    .hero-btn {
        width: 100%;
        max-width: 300px;
        justify-content: center;
    }

    .features-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
}

@media (max-width: 480px) {
    .hero-section {
        padding: 2rem 1rem;
    }

    .hero-text h1 {
        font-size: 1.8rem;
    }

    .feature-card {
        padding: 1.5rem;
    }
}
</style>
