<?php

class ApiRemote
{
    private $db;

    public function __construct()
    {
        $this->db = new BaseRemote();
    }

    public function addBudget($data)
    {
    }

    public function getAllProducts()
    {
        $query = "SELECT * FROM products";
        return $this->db->select($query);
    }

    public function getProductsByCategory($categoryId)
    {
        $query = "SELECT p.* FROM products p INNER JOIN category_product cp ON p.id = cp.product_id WHERE cp.category_id = ?";
        return $this->db->select($query, array($categoryId));
    }

    public function getAllCategoriesAndSubcategories()
    {
        $query = "SELECT * FROM categories";
        $categories = $this->db->select($query);
        foreach ($categories as &$category) {
            $query = "SELECT * FROM sub_categories WHERE category_id = ?";
            $subcategories = $this->db->select($query, array($category['id']));
            $category['subcategories'] = $subcategories;
        }
        return $categories;
    }
}
