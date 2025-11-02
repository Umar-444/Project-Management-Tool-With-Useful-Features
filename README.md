> **This Project is created by Umar FarooQ, a Project Manager at WorldWebTree. It is open for contribution – let's make it big with new best features! Please email me if you want me to collab with you in other projects: Umarpak995@gmail.com**

# 🚀 Advanced PHP To-Do List

A modern, feature-rich to-do list application built with **Object-Oriented PHP**, JavaScript, and CSS. This application provides a beautiful, responsive interface with advanced functionality for managing your daily tasks using a clean, maintainable OOP architecture.

---

**👤 Author & Collaboration**
- **Umar FarooQ** | Project Manager at WorldWebTree  
- Open to collaborations! Email: [Umarpak995@gmail.com](mailto:Umarpak995@gmail.com)
- LinkedIn: [linkedin/in/umar444](https://linkedin.com/in/umar444)
- GitHub: [github/umar-444](https://github.com/umar-444)
- #umar44

---

<img width="1500" height="657" alt="image" src="https://github.com/user-attachments/assets/31845c30-b1be-4dd3-bc26-28f765213c8d" />

## ✨ Features

### 🎨 Modern UI/UX
- **Beautiful Design**: Modern gradient backgrounds with glassmorphism effects
- **Dark/Light Theme**: Toggle between dark and light themes with smooth transitions
- **Responsive Design**: Works perfectly on desktop, tablet, and mobile devices
- **Smooth Animations**: Elegant animations and transitions throughout the app
- **Custom Scrollbars**: Styled scrollbars that match the theme
- **Welcome Landing Page**: Professional homepage with feature overview and quick navigation

### 📝 Advanced Todo Management
- **Rich Todo Creation**: Add title, description, category, priority, and due date
- **Categories**: Organize todos with predefined categories (General, Work, Personal, Shopping, Health, Learning)
- **Priority Levels**: Set priority as Low, Medium, High, or Urgent with visual indicators
- **Due Dates**: Set and track due dates with overdue notifications
- **Edit Functionality**: Edit existing todos with a beautiful modal interface
- **Completion Tracking**: Mark todos as complete with completion timestamps

### 🔍 Search & Filter
- **Real-time Search**: Search through todos by title or description
- **Advanced Filtering**: Filter by category, priority, or completion status
- **Smart Sorting**: Sort by newest, oldest, priority, alphabetical, or due date
- **Combined Filters**: Use multiple filters simultaneously

### 📊 Statistics & Progress
- **Live Statistics**: Real-time counters for total, completed, pending, and overdue todos
- **Progress Bar**: Visual progress indicator showing completion percentage
- **Overdue Alerts**: Visual indicators for overdue tasks with pulsing animations

### 🎯 User Experience
- **Drag & Drop**: Reorder todos by dragging (with visual feedback)
- **Keyboard Shortcuts**: 
  - `Ctrl/Cmd + N`: Focus on new todo input
  - `Escape`: Close modals
- **Notifications**: Beautiful toast notifications for all actions
- **Auto-save**: Automatic saving of form data
- **Offline Support**: Service worker for offline functionality

### 📤 Export & Data Management
- **Export Options**: Export todos as JSON or CSV
- **Data Persistence**: All data stored in MySQL database
- **Backup Ready**: Easy to backup and restore data

### 🏗️ OOP Architecture
- **Object-Oriented Design**: Clean separation of concerns with Models, Services, and Controllers
- **Singleton Database**: Efficient database connection management
- **Centralized Validation**: Robust input validation and sanitization
- **Service Layer**: Business logic separated from presentation
- **Modular Structure**: Easy to extend and maintain

## 🛠️ Technology Stack

- **Backend**: PHP 7.4+ with OOP architecture and PDO
- **Architecture**: MVC-inspired (Models, Services, Controllers)
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Libraries**: jQuery 3.2.1
- **Icons**: Font Awesome 6.0
- **Fonts**: Inter (Google Fonts)

## 📋 Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd php-to-do-list
   ```

2. **Database Setup**
   - Create a MySQL database named `todo_php`
   - Import the `todo_php.sql` file to create the required tables
   - Update database credentials in `db_conn.php` if needed

3. **Web Server Setup**
   - Place the files in your web server directory
   - Ensure PHP and MySQL are properly configured
   - Access the application at `http://your-domain/` (opens the landing page)
   - Or directly access `http://your-domain/todo.php` or `http://your-domain/kanban.php`

4. **Optional: SSL Certificate**
   - For service worker functionality, HTTPS is recommended
   - The app works on HTTP but some features may be limited

## 🗄️ Database Schema

### Tables

#### `todos`
- `id` (INT, Primary Key, Auto Increment)
- `title` (TEXT, Required)
- `description` (TEXT, Optional)
- `category` (VARCHAR(50), Default: 'General')
- `priority` (ENUM: 'Low', 'Medium', 'High', 'Urgent')
- `due_date` (DATETIME, Optional)
- `date_time` (DATETIME, Auto-generated)
- `checked` (TINYINT(1), Default: 0)
- `completed_at` (DATETIME, Optional)
- `color` (VARCHAR(7), Default: '#3498db')

#### `categories`
- `id` (INT, Primary Key, Auto Increment)
- `name` (VARCHAR(50), Unique)
- `color` (VARCHAR(50), Default: '#3498db')
- `icon` (VARCHAR(50), Default: '📝')

#### `todos` (Updated Fields)
- `kanban_column` (ENUM: 'todo', 'in_progress', 'review', 'done', Default: 'todo')
- `sort_order` (INT, Default: 0)

## 📁 Project Structure

```
project-root/
├── app/
│   ├── Core/              # Core OOP classes
│   │   ├── Database.php   # Singleton database connection
│   │   ├── Response.php   # Standardized JSON responses
│   │   ├── Validator.php  # Input validation & sanitization
│   │   └── autoload.php   # Class autoloader
│   ├── Models/            # Data models
│   │   ├── Todo.php       # Todo entity with business logic
│   │   └── Category.php   # Category entity
│   ├── Services/          # Business logic layer
│   │   ├── TodoService.php    # Todo business operations
│   │   └── CategoryService.php # Category business operations
│   ├── Controllers/       # HTTP request handlers
│   │   ├── TodoController.php    # Todo API endpoints
│   │   └── CategoryController.php # Category API endpoints
│   ├── add.php           # Add todo endpoint
│   ├── get_todos.php     # Get todos endpoint
│   ├── update.php        # Update todo endpoint
│   ├── check.php         # Toggle completion endpoint
│   ├── remove.php        # Delete todo endpoint
│   ├── update_kanban.php # Kanban board updates
│   ├── get_categories.php # Get categories endpoint
│   └── export.php        # Export functionality
├── css/
│   └── style.css         # Application styles
├── js/
│   └── script.js         # Frontend JavaScript
├── include/
│   ├── header.php        # HTML header
│   └── footer.php        # HTML footer
├── index.php            # Landing page
├── todo.php            # Todo management page
├── kanban.php          # Kanban board page
├── db_conn.php         # Legacy database connection (kept for compatibility)
├── todo_php.sql        # Database schema
├── sw.js              # Service worker
└── README.md          # This file
```

## 🎨 Customization

### Themes
The app supports both light and dark themes. You can customize colors by modifying the CSS variables in `style.css`:

```css
:root {
  --primary-color: #667eea;
  --secondary-color: #f093fb;
  /* ... other variables */
}
```

### Categories
Add new categories by:
1. Inserting into the `categories` table
2. Adding options to the category select in `todo.php`
3. Updating the JavaScript category handling

### Priorities
Modify priority levels by updating the ENUM values in the database schema and corresponding JavaScript arrays.

## 🔧 API Endpoints

All API endpoints now use the new OOP architecture with Controllers, Services, and Models:

- `POST /app/add.php` - Add new todo (TodoController::add)
- `POST /app/update.php` - Update existing todo (TodoController::update)
- `POST /app/check.php` - Toggle todo completion (TodoController::toggleStatus)
- `POST /app/remove.php` - Delete todo (TodoController::delete)
- `GET /app/get_todos.php` - Get all todos with filtering (TodoController::getTodos)
- `POST /app/update_kanban.php` - Update kanban position (TodoController::updateKanban)
- `GET /app/get_categories.php` - Get all categories (CategoryController::getCategories)
- `GET /app/export.php?format=json|csv` - Export todos (TodoController::export)

### API Response Formats

**Success Response:**
```json
{
  "success": true,
  "todos": [...]
}
```

**Error Response:**
```json
{
  "success": false,
  "message": "Error description"
}
```

## 🏗️ OOP Architecture Details

### Core Classes

#### Database (Singleton Pattern)
```php
Database::getInstance()->getConnection()
```
- Manages PDO connections efficiently
- Prevents multiple database connections
- Handles connection errors gracefully

#### Response (Utility Class)
```php
Response::success($data, "Success message");
Response::error("Error message", 400);
```
- Standardized JSON response formatting
- Proper HTTP status codes
- Consistent API responses

#### Validator (Utility Class)
```php
Validator::validateTodoData($input);
Validator::sanitizeString($string);
```
- Centralized input validation
- SQL injection prevention
- Data sanitization

### Model Classes

#### Todo Model
```php
$todo = new Todo($data);
$todo->markComplete();
$todo->isOverdue();
```
- Encapsulates todo data and business logic
- Built-in validation and formatting
- Rich business methods

#### Category Model
```php
$category = new Category($data);
$category->getDisplayName();
```
- Category entity management
- Display formatting methods

### Service Classes

#### TodoService
```php
$service = new TodoService();
$result = $service->createTodo($data);
$todos = $service->getTodos($filters);
```
- Contains all todo business logic
- Database operations abstraction
- Data transformation and validation

#### CategoryService
```php
$service = new CategoryService();
$categories = $service->getCategories();
```
- Category business operations
- Todo count aggregation

### Controller Classes

#### TodoController
```php
$controller = new TodoController();
$controller->add();        // Handles POST /add
$controller->getTodos();   // Handles GET /get_todos
$controller->export();     // Handles GET /export
```
- HTTP request/response handling
- Input parsing and validation
- Service coordination

## 🚀 Performance Features

- **Lazy Loading**: Todos are loaded efficiently
- **Debounced Search**: Search input is debounced for better performance
- **Optimized Queries**: Database queries are optimized with proper indexing
- **Caching**: Service worker provides offline caching
- **Minified Assets**: Production-ready minified CSS and JS

## 🔒 Security Features

- **SQL Injection Protection**: All queries use prepared statements with PDO
- **XSS Prevention**: Centralized input sanitization and validation
- **CSRF Protection**: Form tokens and validation
- **Input Validation**: Server-side validation with dedicated Validator class
- **OOP Security**: Clean separation prevents security vulnerabilities
- **Error Handling**: Proper exception handling and error responses

## 🔄 Migration to OOP

This project has been converted from procedural PHP to a modern Object-Oriented architecture:

### What Changed
- ✅ **Procedural → OOP**: All code converted to classes and objects
- ✅ **Separation of Concerns**: Business logic separated from presentation
- ✅ **Database Layer**: Singleton pattern for efficient connections
- ✅ **Validation Layer**: Centralized input validation and sanitization
- ✅ **Response Layer**: Standardized JSON response formatting
- ✅ **Maintainability**: Easy to extend and modify

### Backward Compatibility
- ✅ **Frontend Unchanged**: JavaScript code works without modifications
- ✅ **API Endpoints**: Same URLs and request formats maintained
- ✅ **Database Schema**: No changes required
- ✅ **Functionality**: All features work exactly as before

### Benefits Achieved
- 🚀 **Scalability**: Easy to add new features (users, projects, teams)
- 🔧 **Maintainability**: Clear structure and separation of concerns
- 🧪 **Testability**: Classes can be unit tested independently
- 💪 **Robustness**: Better error handling and validation
- 📈 **Performance**: Optimized database connections and queries

## 📱 Browser Support

- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+
- Mobile browsers (iOS Safari, Chrome Mobile)

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## 📄 License

This project is open source and available under the [MIT License](LICENSE).

## 🙏 Acknowledgments

- Icons by Font Awesome
- Fonts by Google Fonts
- Inspiration from modern web design trends
- Community feedback and suggestions

---

**Built with Modern OOP PHP Architecture** 🚀

**Made with ❤️ for productivity enthusiasts and developers**

---

> **This Project is created by Umar FarooQ, a Project Manager at WorldWebTree, and a Senior Software engineer in Riyadh, Saudi Arabia.  
Use this email to contact: Umarpak995@gmail.com  
LinkedIn: linkedin/in/umar444  
GitHub: github/umar-444  
#umar44**

---
