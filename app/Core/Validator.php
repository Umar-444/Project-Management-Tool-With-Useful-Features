<?php

/**
 * Validation utility class
 */
class Validator
{
    private $errors = [];

    /**
     * Validate todo data
     */
    public static function validateTodoData($data)
    {
        $validator = new self();

        // Required fields
        if (empty(trim($data['title'] ?? ''))) {
            $validator->addError('title', 'Title is required');
        }

        // Optional but validated fields
        if (isset($data['category']) && !in_array($data['category'], self::getValidCategories())) {
            $validator->addError('category', 'Invalid category');
        }

        if (isset($data['priority']) && !in_array($data['priority'], self::getValidPriorities())) {
            $validator->addError('priority', 'Invalid priority');
        }

        if (isset($data['due_date']) && !empty($data['due_date'])) {
            if (!strtotime($data['due_date'])) {
                $validator->addError('due_date', 'Invalid date format');
            }
        }

        return $validator;
    }

    /**
     * Validate kanban column
     */
    public static function validateKanbanColumn($column)
    {
        $validator = new self();

        if (!in_array($column, self::getValidKanbanColumns())) {
            $validator->addError('column', 'Invalid kanban column');
        }

        return $validator;
    }

    /**
     * Check if validation passed
     */
    public function passes()
    {
        return empty($this->errors);
    }

    /**
     * Check if validation failed
     */
    public function fails()
    {
        return !$this->passes();
    }

    /**
     * Get validation errors
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Add error
     */
    public function addError($field, $message)
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        $this->errors[$field][] = $message;
    }

    /**
     * Get first error for field
     */
    public function getFirstError($field)
    {
        return $this->errors[$field][0] ?? null;
    }

    /**
     * Get valid categories
     */
    public static function getValidCategories()
    {
        return ['General', 'Work', 'Personal', 'Shopping', 'Health', 'Learning'];
    }

    /**
     * Get valid priorities
     */
    public static function getValidPriorities()
    {
        return ['Low', 'Medium', 'High', 'Urgent'];
    }

    /**
     * Get valid kanban columns
     */
    public static function getValidKanbanColumns()
    {
        return ['todo', 'in_progress', 'review', 'done'];
    }

    /**
     * Sanitize string input
     */
    public static function sanitizeString($string)
    {
        return trim(filter_var($string, FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    }

    /**
     * Validate ID
     */
    public static function validateId($id)
    {
        return is_numeric($id) && $id > 0;
    }
}
