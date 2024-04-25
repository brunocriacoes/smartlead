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

    public function getIdCategoryBySlug($slug)
    {
        $query = "SELECT id FROM categories WHERE slug = ?";
        $categoria = $this->db->select($query, array($slug));
        return $categoria[0]['id'];
    }

    public function getProductsByCategory($categoryId)
    {
        $id_category = $this->getIdCategoryBySlug($categoryId);

        $categoryId = $id_category;
        $query = "SELECT p.id id, p.name name, p.slug slug, p.cod cod, p.specifications specifications, p.internal_part internal_part, p.external_part external_part FROM products p INNER JOIN category_product cp ON p.id = cp.product_id WHERE cp.category_id = ?";
        $produtos = $this->db->select($query, array($categoryId));
        foreach ($produtos as &$produto) {
            $query = "SELECT id, path FROM product_photos WHERE product_id = ?";
            $photos = $this->db->select($query, array($produto['id']));
            $produto['photos'] = $photos;
        }
        return $produtos ?? [];
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
        $query = "SELECT id, name, slug, cod, specifications, internal_part, external_part, recordings, description FROM products WHERE slug = ?";
        $produtos = $this->db->select($query, array($id));
        foreach ($produtos as &$produto) {
            $query = "SELECT id, path FROM product_photos WHERE product_id = ?";
            $photos = $this->db->select($query, array($produto['id']));
            $produto['photos'] = $photos;
        }
        return $produtos;
    }

    public function getIdProductsBySlug($slug)
    {
        $query = "SELECT id FROM products WHERE slug = ?";
        $produtos = $this->db->select($query, array($slug));

        return $produtos[0]['id'];
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
            observations,
            created_at
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ? )";

        return $this->db->insert($query, array(
            $name,
            $company,
            $phone,
            $email,
            '2',
            'pending',
            $observations,
            date('Y-m-d H:i:s')
        ));
    }

    public function insertItenOrcamento($quantity, $product_id, $budget_id)
    {
        $query_produto = "SELECT * FROM products WHERE id=?";
        $select_produto = $this->db->select($query_produto, array($product_id));
        $produto = $select_produto[0];
        $sql_insert_produto = "INSERT INTO products (
            name,
            slug,
            cod,
            description,
            specifications,
            recordings,
            included,
            features,
            internal_part,
            external_part,
            observations,
            budget_id,
            created_at
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $dados_produto = array(
            $produto['name'],
            $produto['slug'],
            'unlisted',
            $produto['description'],
            $produto['specifications'],
            $produto['recordings'],
            $produto['included'],
            $produto['features'],
            $produto['internal_part'],
            $produto['external_part'],
            $produto['observations'],
            $budget_id,
            $produto['created_at'],
        );

        $insert_produtos = $this->db->insert($sql_insert_produto, $dados_produto);

        $sql_insert_product_values = "INSERT INTO product_values (
            quantity,
            price,
            product_id,
        );
        VALUES (?, ?, ?)";

        $dados_product_values = array(
            $quantity,
            '100',
            $insert_produtos,
        );

        $this->db->insert($sql_insert_product_values, $dados_product_values);



        $query = "INSERT INTO budget_product (
            quantity,
            product_id,
            budget_id,
            price
        )
        VALUES (?, ?, ?, ?)";

        return $this->db->insert($query, array(
            $quantity,
            $insert_produtos,
            $budget_id,
            '1'
        ));
    }
}
