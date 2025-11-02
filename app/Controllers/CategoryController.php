<?php

/**
 * Category controller class - handles HTTP requests for category operations
 */
class CategoryController
{
    private $categoryService;

    public function __construct()
    {
        $this->categoryService = new CategoryService();
    }

    /**
     * Handle get categories request
     */
    public function getCategories()
    {
        $includeCounts = isset($_GET['counts']) && $_GET['counts'] === 'true';

        if ($includeCounts) {
            $result = $this->categoryService->getCategoriesWithCounts();
        } else {
            $result = $this->categoryService->getCategories();
        }

        if ($result['success']) {
            // Return in format expected by frontend (if used)
            echo json_encode([
                'success' => true,
                'categories' => $result['categories']
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => $result['message']
            ]);
        }
        exit();
    }
}
