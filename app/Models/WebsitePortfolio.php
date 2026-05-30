<?php

class WebsitePortfolio
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
            FROM website_portfolios
            ORDER BY id DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function paginate($limit, $offset)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM website_portfolios
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
        return (int) $this->db->query("SELECT COUNT(*) FROM website_portfolios")->fetchColumn();
    }

    public function active()
    {
        $stmt = $this->db->query("
            SELECT *
            FROM website_portfolios
            WHERE status IN ('active', 'publish')
            ORDER BY id DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function activeByCategory($categoryId, $limit = null)
    {
        $sql = "
            SELECT *
            FROM website_portfolios
            WHERE status IN ('active', 'publish')
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

    public function findBySlug($slug)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM website_portfolios
            WHERE (slug_id = ? OR slug_en = ?)
            AND status IN ('active', 'publish')
            LIMIT 1
        ");

        $stmt->execute([$slug, $slug]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM website_portfolios
            WHERE id = ?
        ");

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO website_portfolios
            (
                title_id,
                title_en,
                slug_id,
                slug_en,
                category_id,
                category_en,
                client_name,
                event_date,
                location_id,
                location_en,
                description_id,
                description_en,
                thumbnail,
                status
            )
            VALUES
            (
                :title_id,
                :title_en,
                :slug_id,
                :slug_en,
                :category_id,
                :category_en,
                :client_name,
                :event_date,
                :location_id,
                :location_en,
                :description_id,
                :description_en,
                :thumbnail,
                :status
            )
        ");

        return $stmt->execute([
            ':title_id' => $data['title_id'],
            ':title_en' => $data['title_en'],
            ':slug_id' => $data['slug_id'],
            ':slug_en' => $data['slug_en'],
            ':category_id' => $data['category_id'],
            ':category_en' => $data['category_en'],
            ':client_name' => $data['client_name'],
            ':event_date' => $data['event_date'],
            ':location_id' => $data['location_id'],
            ':location_en' => $data['location_en'],
            ':description_id' => $data['description_id'],
            ':description_en' => $data['description_en'],
            ':thumbnail' => $data['thumbnail'],
            ':status' => $data['status'],
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE website_portfolios SET
                title_id = :title_id,
                title_en = :title_en,
                slug_id = :slug_id,
                slug_en = :slug_en,
                category_id = :category_id,
                category_en = :category_en,
                client_name = :client_name,
                event_date = :event_date,
                location_id = :location_id,
                location_en = :location_en,
                description_id = :description_id,
                description_en = :description_en,
                thumbnail = :thumbnail,
                status = :status
            WHERE id = :id
        ");

        return $stmt->execute([
            ':id' => $id,
            ':title_id' => $data['title_id'],
            ':title_en' => $data['title_en'],
            ':slug_id' => $data['slug_id'],
            ':slug_en' => $data['slug_en'],
            ':category_id' => $data['category_id'],
            ':category_en' => $data['category_en'],
            ':client_name' => $data['client_name'],
            ':event_date' => $data['event_date'],
            ':location_id' => $data['location_id'],
            ':location_en' => $data['location_en'],
            ':description_id' => $data['description_id'],
            ':description_en' => $data['description_en'],
            ':thumbnail' => $data['thumbnail'],
            ':status' => $data['status'],
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("
            DELETE FROM website_portfolios
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }
}
