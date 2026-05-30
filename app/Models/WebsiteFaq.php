<?php

class WebsiteFaq
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
            FROM website_faqs
            ORDER BY id DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function paginate($limit, $offset)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM website_faqs
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
        return (int) $this->db->query("SELECT COUNT(*) FROM website_faqs")->fetchColumn();
    }

    public function active()
    {
        $stmt = $this->db->query("
            SELECT *
            FROM website_faqs
            WHERE status = 'active'
            ORDER BY id DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function activeByCategory($categoryId, $limit = null)
    {
        $sql = "
            SELECT *
            FROM website_faqs
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

    public function activeGeneral($limit = 4)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM website_faqs
            WHERE status = 'active'
            AND (category_id IS NULL OR category_id = '' OR LOWER(category_id) IN ('umum', 'general'))
            ORDER BY id DESC
            LIMIT ?
        ");
        $stmt->bindValue(1, max(1, (int) $limit), PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function activeGeneralOrLatest($limit = 4)
    {
        $faqs = $this->activeGeneral($limit);

        if (!empty($faqs)) {
            return $faqs;
        }

        $stmt = $this->db->prepare("
            SELECT *
            FROM website_faqs
            WHERE status = 'active'
            ORDER BY id DESC
            LIMIT ?
        ");
        $stmt->bindValue(1, max(1, (int) $limit), PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM website_faqs
            WHERE id = ?
        ");

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO website_faqs
            (
                question_id,
                question_en,
                answer_id,
                answer_en,
                category_id,
                category_en,
                status
            )
            VALUES
            (
                :question_id,
                :question_en,
                :answer_id,
                :answer_en,
                :category_id,
                :category_en,
                :status
            )
        ");

        return $stmt->execute([
            ':question_id' => $data['question_id'],
            ':question_en' => $data['question_en'],
            ':answer_id' => $data['answer_id'],
            ':answer_en' => $data['answer_en'],
            ':category_id' => $data['category_id'],
            ':category_en' => $data['category_en'],
            ':status' => $data['status'],
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE website_faqs SET
                question_id = :question_id,
                question_en = :question_en,
                answer_id = :answer_id,
                answer_en = :answer_en,
                category_id = :category_id,
                category_en = :category_en,
                status = :status
            WHERE id = :id
        ");

        return $stmt->execute([
            ':id' => $id,
            ':question_id' => $data['question_id'],
            ':question_en' => $data['question_en'],
            ':answer_id' => $data['answer_id'],
            ':answer_en' => $data['answer_en'],
            ':category_id' => $data['category_id'],
            ':category_en' => $data['category_en'],
            ':status' => $data['status'],
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("
            DELETE FROM website_faqs
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }

    private function ensureCategoryColumns()
    {
        $columns = [
            'category_id' => "ALTER TABLE website_faqs ADD COLUMN category_id VARCHAR(150) NULL AFTER answer_en",
            'category_en' => "ALTER TABLE website_faqs ADD COLUMN category_en VARCHAR(150) NULL AFTER category_id",
        ];

        foreach ($columns as $column => $sql) {
            $stmt = $this->db->prepare("SHOW COLUMNS FROM website_faqs LIKE ?");
            $stmt->execute([$column]);

            if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
                $this->db->exec($sql);
            }
        }
    }
}
