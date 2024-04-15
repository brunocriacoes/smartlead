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
        $query = "SELECT id, name, slug, cod, specifications, internal_part, external_part FROM products WHERE deleted_at IS NULL";
        $produtos = $this->db->select($query);
        foreach ($produtos as &$produto) {
            $query = "SELECT id, path FROM product_photos WHERE product_id = ?";
            $photos = $this->db->select($query, array($produto['id']));
            $produto['photos'] = $photos;
        }
        return $produtos;
    }

    public function getProductsByCategory($categoryId)
    {
        $query = "SELECT p.id id, p.name name, p.slug slug, p.cod cod, p.specifications specifications, p.internal_part internal_part, p.external_part external_part FROM products p INNER JOIN category_product cp ON p.id = cp.product_id WHERE cp.category_id = ?";
        $produtos = $this->db->select($query, array($categoryId));
        foreach ($produtos as &$produto) {
            $query = "SELECT id, path FROM product_photos WHERE product_id = ?";
            $photos = $this->db->select($query, array($produto['id']));
            $produto['photos'] = $photos;
        }
        return $produtos;
    }

    public function getCategoryByProductId($productId)
    {
        $query = "SELECT sc.name, sc.id, sc.slug
        FROM sub_categories sc
        JOIN product_sub_category psc ON sc.id = psc.sub_category_id
        WHERE psc.product_id = ?";
        $categories = $this->db->select($query, array($productId));
        return $categories;
    }

    public function getProductsRelated($ids)
    {
        $prods = [];
        foreach ($ids as $id) {
            $ps = $this->getProductsByCategory($id);
            $prods = array_merge($prods, $ps);
        }
        return array_slice($prods, 0, 10);
    }


    public function getProductsById($id)
    {
        $query = "SELECT id, name, slug, cod, specifications, internal_part, external_part, recordings, description FROM products WHERE id = ?";
        $produtos = $this->db->select($query, array($id));
        foreach ($produtos as &$produto) {
            $query = "SELECT id, path FROM product_photos WHERE product_id = ?";
            $photos = $this->db->select($query, array($produto['id']));
            $produto['photos'] = $photos;
        }
        return $produtos;
    }


    public function getAllCategoriesAndSubcategories()
    {
        $query = "SELECT id, name, slug FROM categories WHERE deleted_at IS NULL";
        $categories = $this->db->select($query);
        foreach ($categories as &$category) {
            $query = "SELECT id, name, slug FROM sub_categories WHERE category_id = ? AND deleted_at IS NULL";
            $subcategories = $this->db->select($query, array($category['id']));
            $category['subcategories'] = $subcategories;
        }
        return $categories;
    }
    public function insertOrcamento($name, $company, $phone, $email, $observations)
    {
        $query = "INSERT INTO budgets (
            client_name,
            client_company,
            client_phone,
            client_email,
            user_id,
            status,
            observations
        )
        VALUES (?, ?, ?, ?, ?, ?, ? )";

        return $this->db->insert($query, array(
            $name,
            $company,
            $phone,
            $email,
            '2',
            'pending',
            $observations
        ));
    }

    public function insertItenOrcamento($quantity, $product_id, $budget_id)
    {
        $query = "INSERT INTO budget_product (
            quantity,
            product_id,
            budget_id,
            price
        )
        VALUES (?, ?, ?, ?)";

        return $this->db->insert($query, array(
            $quantity,
            $product_id,
            $budget_id,
            '1'
        ));
    }
}
