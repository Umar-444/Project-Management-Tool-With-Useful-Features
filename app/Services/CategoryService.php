<?php

/**
 * Category service class - contains business logic for category operations
 */
class CategoryService
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Get all categories
     */
    public function getCategories()
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM categories ORDER BY name ASC");
            $stmt->execute();

            $categories = [];
            while ($row = $stmt->fetch()) {
                $category = new Category($row);
                $categories[] = $category->toArray();
            }

            return ['success' => true, 'categories' => $categories];

        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Get category by ID
     */
    public function getCategoryById($id)
    {
        if (!Validator::validateId($id)) {
            return null;
        }

        try {
            $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = ?");
            $stmt->execute([$id]);
            $data = $stmt->fetch();

            return $data ? new Category($data) : null;

        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Get category by name
     */
    public function getCategoryByName($name)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM categories WHERE name = ?");
            $stmt->execute([$name]);
            $data = $stmt->fetch();

            return $data ? new Category($data) : null;

        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Get categories with todo counts
     */
    public function getCategoriesWithCounts()
    {
        try {
            $stmt = $this->db->prepare("
                SELECT c.*, COUNT(t.id) as todo_count
                FROM categories c
                LEFT JOIN todos t ON c.name = t.category
                GROUP BY c.id
                ORDER BY c.name ASC
            ");
            $stmt->execute();

            $categories = [];
            while ($row = $stmt->fetch()) {
                $category = new Category($row);
                $categoryData = $category->toArray();
                $categoryData['todo_count'] = (int)$row['todo_count'];
                $categories[] = $categoryData;
            }

            return ['success' => true, 'categories' => $categories];

        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }
}
