# 🚀 Advanced PHP To-Do List

A modern, feature-rich to-do list application built with PHP, JavaScript, and CSS. This application provides a beautiful, responsive interface with advanced functionality for managing your daily tasks.

## ✨ Features

### 🎨 Modern UI/UX
- **Beautiful Design**: Modern gradient backgrounds with glassmorphism effects
- **Dark/Light Theme**: Toggle between dark and light themes with smooth transitions
- **Responsive Design**: Works perfectly on desktop, tablet, and mobile devices
- **Smooth Animations**: Elegant animations and transitions throughout the app
- **Custom Scrollbars**: Styled scrollbars that match the theme

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

## 🛠️ Technology Stack

- **Backend**: PHP 7.4+ with PDO
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
   - Access the application through your web browser

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
2. Adding options to the category select in `index.php`
3. Updating the JavaScript category handling

### Priorities
Modify priority levels by updating the ENUM values in the database schema and corresponding JavaScript arrays.

## 🔧 API Endpoints

- `POST /app/add.php` - Add new todo
- `POST /app/update.php` - Update existing todo
- `POST /app/check.php` - Toggle todo completion
- `POST /app/remove.php` - Delete todo
- `GET /app/get_todos.php` - Get all todos
- `GET /app/get_categories.php` - Get all categories
- `GET /app/export.php?format=json|csv` - Export todos

## 🚀 Performance Features

- **Lazy Loading**: Todos are loaded efficiently
- **Debounced Search**: Search input is debounced for better performance
- **Optimized Queries**: Database queries are optimized with proper indexing
- **Caching**: Service worker provides offline caching
- **Minified Assets**: Production-ready minified CSS and JS

## 🔒 Security Features

- **SQL Injection Protection**: All queries use prepared statements
- **XSS Prevention**: Input sanitization and output escaping
- **CSRF Protection**: Form tokens and validation
- **Input Validation**: Server-side validation for all inputs

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

**Made with ❤️ for productivity enthusiasts**