<?php

class WebsiteTestimonial
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->ensureCategoryColumns();
    }

    public function all()
    {
        $stmt = $this->db->query("
            SELECT *
            FROM website_testimonials
            ORDER BY id DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function paginate($limit, $offset)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM website_testimonials
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
        return (int) $this->db->query("SELECT COUNT(*) FROM website_testimonials")->fetchColumn();
    }

    public function active()
    {
        $stmt = $this->db->query("
            SELECT *
            FROM website_testimonials
            WHERE status = 'active'
            ORDER BY id DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function activeByCategory($categoryId, $limit = null)
    {
        $sql = "
            SELECT *
            FROM website_testimonials
            WHERE status = 'active'
            AND category_id = :category_id
            ORDER BY id DESC
        ";

        if ($limit !== null) {
            $sql .= " LIMIT " . max(1, (int) $limit);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':category_id' => $categoryId,
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM website_testimonials WHERE id = ?");
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO website_testimonials
            (name, company_name, position, category_id, category_en, testimonial_id, testimonial_en, image, rating, status)
            VALUES
            (:name, :company_name, :position, :category_id, :category_en, :testimonial_id, :testimonial_en, :image, :rating, :status)
        ");

        return $stmt->execute([
            ':name' => $data['name'],
            ':company_name' => $data['company_name'],
            ':position' => $data['position'],
            ':category_id' => $data['category_id'],
            ':category_en' => $data['category_en'],
            ':testimonial_id' => $data['testimonial_id'],
            ':testimonial_en' => $data['testimonial_en'],
            ':image' => $data['image'],
            ':rating' => $data['rating'],
            ':status' => $data['status'],
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE website_testimonials SET
                name = :name,
                company_name = :company_name,
                position = :position,
                category_id = :category_id,
                category_en = :category_en,
                testimonial_id = :testimonial_id,
                testimonial_en = :testimonial_en,
                image = :image,
                rating = :rating,
                status = :status
            WHERE id = :id
        ");

        return $stmt->execute([
            ':id' => $id,
            ':name' => $data['name'],
            ':company_name' => $data['company_name'],
            ':position' => $data['position'],
            ':category_id' => $data['category_id'],
            ':category_en' => $data['category_en'],
            ':testimonial_id' => $data['testimonial_id'],
            ':testimonial_en' => $data['testimonial_en'],
            ':image' => $data['image'],
            ':rating' => $data['rating'],
            ':status' => $data['status'],
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM website_testimonials WHERE id = ?");
        return $stmt->execute([$id]);
    }

    private function ensureCategoryColumns()
    {
        $columns = [
            'category_id' => "ALTER TABLE website_testimonials ADD COLUMN category_id VARCHAR(150) NULL AFTER position",
            'category_en' => "ALTER TABLE website_testimonials ADD COLUMN category_en VARCHAR(150) NULL AFTER category_id",
        ];

        foreach ($columns as $column => $sql) {
            $stmt = $this->db->prepare("SHOW COLUMNS FROM website_testimonials LIKE ?");
            $stmt->execute([$column]);

            if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
                $this->db->exec($sql);
            }
        }
    }
}
