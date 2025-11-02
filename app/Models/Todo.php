<?php

/**
 * Todo model class
 */
class Todo
{
    private $id;
    private $title;
    private $description;
    private $category;
    private $priority;
    private $dueDate;
    private $dateTime;
    private $checked;
    private $completedAt;
    private $color;
    private $kanbanColumn;
    private $sortOrder;

    // Valid values
    const VALID_PRIORITIES = ['Low', 'Medium', 'High', 'Urgent'];
    const VALID_CATEGORIES = ['General', 'Work', 'Personal', 'Shopping', 'Health', 'Learning'];
    const VALID_KANBAN_COLUMNS = ['todo', 'in_progress', 'review', 'done'];

    /**
     * Constructor
     */
    public function __construct($data = [])
    {
        $this->setId($data['id'] ?? null);
        $this->setTitle($data['title'] ?? '');
        $this->setDescription($data['description'] ?? '');
        $this->setCategory($data['category'] ?? 'General');
        $this->setPriority($data['priority'] ?? 'Medium');
        $this->setDueDate($data['due_date'] ?? null);
        $this->setDateTime($data['date_time'] ?? date('Y-m-d H:i:s'));
        $this->setChecked($data['checked'] ?? false);
        $this->setCompletedAt($data['completed_at'] ?? null);
        $this->setColor($data['color'] ?? '#3498db');
        $this->setKanbanColumn($data['kanban_column'] ?? 'todo');
        $this->setSortOrder($data['sort_order'] ?? 0);
    }

    // Getters
    public function getId() { return $this->id; }
    public function getTitle() { return $this->title; }
    public function getDescription() { return $this->description; }
    public function getCategory() { return $this->category; }
    public function getPriority() { return $this->priority; }
    public function getDueDate() { return $this->dueDate; }
    public function getDateTime() { return $this->dateTime; }
    public function getChecked() { return $this->checked; }
    public function getCompletedAt() { return $this->completedAt; }
    public function getColor() { return $this->color; }
    public function getKanbanColumn() { return $this->kanbanColumn; }
    public function getSortOrder() { return $this->sortOrder; }

    // Setters with validation
    public function setId($id) { $this->id = $id; }

    public function setTitle($title)
    {
        $title = Validator::sanitizeString($title);
        if (empty($title)) {
            throw new InvalidArgumentException('Title cannot be empty');
        }
        $this->title = $title;
    }

    public function setDescription($description)
    {
        $this->description = Validator::sanitizeString($description);
    }

    public function setCategory($category)
    {
        if (!in_array($category, self::VALID_CATEGORIES)) {
            $category = 'General';
        }
        $this->category = $category;
    }

    public function setPriority($priority)
    {
        if (!in_array($priority, self::VALID_PRIORITIES)) {
            $priority = 'Medium';
        }
        $this->priority = $priority;
    }

    public function setDueDate($dueDate)
    {
        if ($dueDate && strtotime($dueDate)) {
            $this->dueDate = date('Y-m-d H:i:s', strtotime($dueDate));
        } else {
            $this->dueDate = null;
        }
    }

    public function setDateTime($dateTime) { $this->dateTime = $dateTime; }
    public function setChecked($checked) { $this->checked = (bool)$checked; }
    public function setCompletedAt($completedAt) { $this->completedAt = $completedAt; }
    public function setColor($color) { $this->color = $color; }

    public function setKanbanColumn($kanbanColumn)
    {
        if (!in_array($kanbanColumn, self::VALID_KANBAN_COLUMNS)) {
            $kanbanColumn = 'todo';
        }
        $this->kanbanColumn = $kanbanColumn;
    }

    public function setSortOrder($sortOrder) { $this->sortOrder = (int)$sortOrder; }

    // Business logic methods
    public function isCompleted()
    {
        return $this->checked;
    }

    public function isOverdue()
    {
        if (!$this->dueDate || $this->checked) {
            return false;
        }
        return strtotime($this->dueDate) < time();
    }

    public function markComplete()
    {
        $this->checked = true;
        $this->completedAt = date('Y-m-d H:i:s');
        $this->kanbanColumn = 'done';
    }

    public function markIncomplete()
    {
        $this->checked = false;
        $this->completedAt = null;
        if ($this->kanbanColumn === 'done') {
            $this->kanbanColumn = 'todo';
        }
    }

    /**
     * Convert to array for JSON response
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->category,
            'priority' => $this->priority,
            'due_date' => $this->dueDate,
            'date_time' => $this->dateTime,
            'checked' => $this->checked,
            'completed_at' => $this->completedAt,
            'color' => $this->color,
            'kanban_column' => $this->kanbanColumn,
            'sort_order' => $this->sortOrder,
            'is_overdue' => $this->isOverdue()
        ];
    }

    /**
     * Get priority color
     */
    public function getPriorityColor()
    {
        $colors = [
            'Low' => '#28a745',
            'Medium' => '#ffc107',
            'High' => '#fd7e14',
            'Urgent' => '#dc3545'
        ];
        return $colors[$this->priority] ?? '#6c757d';
    }

    /**
     * Get formatted due date
     */
    public function getFormattedDueDate()
    {
        if (!$this->dueDate) return null;
        return date('M j, Y g:i A', strtotime($this->dueDate));
    }
}
