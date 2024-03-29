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
        $query = "SELECT id, name, cod, specifications, internal_part, external_part FROM products WHERE deleted_at IS NULL";
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
        $query = "SELECT p.id id, p.name name, p.cod cod, p.specifications specifications, p.internal_part internal_part, p.external_part external_part FROM products p INNER JOIN category_product cp ON p.id = cp.product_id WHERE cp.category_id = ?";
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
        $query = "SELECT sc.name, sc.id
        FROM sub_categories sc
        JOIN product_sub_category psc ON sc.id = psc.sub_category_id
        WHERE psc.product_id = ?";
        $categories = $this->db->select($query, array($productId));
        return $categories;
    }
    
    public function getProductsRelated($ids)
    {
        $prods = [];
        foreach($ids as $id){
            $ps = $this->getProductsByCategory($id);
            $prods = array_merge($prods, $ps);
        }
        return array_slice($prods, 0, 10);
        
    }
    

    public function getProductsById($id)
    {
        $query = "SELECT id, name, cod, specifications, internal_part, external_part, recordings, description FROM products WHERE id = ?";
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
        $query = "SELECT id, name FROM categories WHERE deleted_at IS NULL" ;
        $categories = $this->db->select($query);
        foreach ($categories as &$category) {
            $query = "SELECT id, name FROM sub_categories WHERE category_id = ? AND deleted_at IS NULL";
            $subcategories = $this->db->select($query, array($category['id']));
            $category['subcategories'] = $subcategories;
        }
        return $categories;
    }
}
