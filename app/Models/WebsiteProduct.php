<?php

class WebsiteProduct
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function all()
    {
        $stmt = $this->db->query("
            SELECT *
            FROM website_products
            ORDER BY id DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function paginate($limit, $offset)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM website_products
            ORDER BY id DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->bindValue(1, (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(2, (int) $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll()
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM website_products")->fetchColumn();
    }

    public function activeFiltered($q = '', $category = '')
    {
        $sql = "
            SELECT *
            FROM website_products
            WHERE status = 'active'
        ";

        $params = [];

        if ($q !== '') {
            $sql .= "
                AND (
                    title_id LIKE :q
                    OR title_en LIKE :q
                    OR description_id LIKE :q
                    OR description_en LIKE :q
                    OR category LIKE :q
                )
            ";

            $params[':q'] = '%' . $q . '%';
        }

        if ($category !== '') {
            $sql .= " AND category = :category ";
            $params[':category'] = $category;
        }

        $sql .= " ORDER BY id DESC ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function active()
    {
        $stmt = $this->db->query("
            SELECT *
            FROM website_products
            WHERE status = 'active'
            ORDER BY id DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM website_products WHERE id = ?");
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO website_products
            (
                title_id, title_en,
                description_id, description_en,
                category, image,
                price_label_id, price_label_en,
                status
            )
            VALUES
            (
                :title_id, :title_en,
                :description_id, :description_en,
                :category, :image,
                :price_label_id, :price_label_en,
                :status
            )
        ");

        return $stmt->execute([
            ':title_id' => $data['title_id'],
            ':title_en' => $data['title_en'],
            ':description_id' => $data['description_id'],
            ':description_en' => $data['description_en'],
            ':category' => $data['category'],
            ':image' => $data['image'],
            ':price_label_id' => $data['price_label_id'],
            ':price_label_en' => $data['price_label_en'],
            ':status' => $data['status'],
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE website_products SET
                title_id = :title_id,
                title_en = :title_en,
                description_id = :description_id,
                description_en = :description_en,
                category = :category,
                image = :image,
                price_label_id = :price_label_id,
                price_label_en = :price_label_en,
                status = :status
            WHERE id = :id
        ");

        return $stmt->execute([
            ':id' => $id,
            ':title_id' => $data['title_id'],
            ':title_en' => $data['title_en'],
            ':description_id' => $data['description_id'],
            ':description_en' => $data['description_en'],
            ':category' => $data['category'],
            ':image' => $data['image'],
            ':price_label_id' => $data['price_label_id'],
            ':price_label_en' => $data['price_label_en'],
            ':status' => $data['status'],
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM website_products WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
