<?php

/**
 * Category model class
 */
class Category
{
    private $id;
    private $name;
    private $color;
    private $icon;

    /**
     * Constructor
     */
    public function __construct($data = [])
    {
        $this->setId($data['id'] ?? null);
        $this->setName($data['name'] ?? '');
        $this->setColor($data['color'] ?? '#3498db');
        $this->setIcon($data['icon'] ?? '📝');
    }

    // Getters
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getColor() { return $this->color; }
    public function getIcon() { return $this->icon; }

    // Setters
    public function setId($id) { $this->id = $id; }

    public function setName($name)
    {
        $name = Validator::sanitizeString($name);
        if (empty($name)) {
            throw new InvalidArgumentException('Category name cannot be empty');
        }
        $this->name = $name;
    }

    public function setColor($color) { $this->color = $color; }
    public function setIcon($icon) { $this->icon = $icon; }

    /**
     * Convert to array for JSON response
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'color' => $this->color,
            'icon' => $this->icon
        ];
    }

    /**
     * Get display name with icon
     */
    public function getDisplayName()
    {
        return $this->icon . ' ' . $this->name;
    }
}
