<?php

class WebsiteSlider
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function active()
    {
        $stmt = $this->db->prepare("
            SELECT * FROM website_sliders
            WHERE status = 'active'
            ORDER BY sort_order ASC, id DESC
        ");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function all()
    {
        $stmt = $this->db->query("
            SELECT * FROM website_sliders
            ORDER BY sort_order ASC, id DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function paginate($limit, $offset)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM website_sliders
            ORDER BY sort_order ASC, id DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->bindValue(1, (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(2, (int) $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll()
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM website_sliders")->fetchColumn();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM website_sliders WHERE id = ?");
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO website_sliders
            (
                title_id, title_en, subtitle_id, subtitle_en,
                description_id, description_en, image,
                button_text_id, button_text_en, button_link,
                sort_order, status
            )
            VALUES
            (
                :title_id, :title_en, :subtitle_id, :subtitle_en,
                :description_id, :description_en, :image,
                :button_text_id, :button_text_en, :button_link,
                :sort_order, :status
            )
        ");

        return $stmt->execute([
            ':title_id' => $data['title_id'],
            ':title_en' => $data['title_en'] ?? null,
            ':subtitle_id' => $data['subtitle_id'] ?? null,
            ':subtitle_en' => $data['subtitle_en'] ?? null,
            ':description_id' => $data['description_id'] ?? null,
            ':description_en' => $data['description_en'] ?? null,
            ':image' => $data['image'] ?? null,
            ':button_text_id' => $data['button_text_id'] ?? null,
            ':button_text_en' => $data['button_text_en'] ?? null,
            ':button_link' => $data['button_link'] ?? null,
            ':sort_order' => $data['sort_order'] ?? 0,
            ':status' => $data['status'] ?? 'active'
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE website_sliders SET
                title_id = :title_id,
                title_en = :title_en,
                subtitle_id = :subtitle_id,
                subtitle_en = :subtitle_en,
                description_id = :description_id,
                description_en = :description_en,
                image = :image,
                button_text_id = :button_text_id,
                button_text_en = :button_text_en,
                button_link = :button_link,
                sort_order = :sort_order,
                status = :status
            WHERE id = :id
        ");

        return $stmt->execute([
            ':id' => $id,
            ':title_id' => $data['title_id'],
            ':title_en' => $data['title_en'] ?? null,
            ':subtitle_id' => $data['subtitle_id'] ?? null,
            ':subtitle_en' => $data['subtitle_en'] ?? null,
            ':description_id' => $data['description_id'] ?? null,
            ':description_en' => $data['description_en'] ?? null,
            ':image' => $data['image'] ?? null,
            ':button_text_id' => $data['button_text_id'] ?? null,
            ':button_text_en' => $data['button_text_en'] ?? null,
            ':button_link' => $data['button_link'] ?? null,
            ':sort_order' => $data['sort_order'] ?? 0,
            ':status' => $data['status'] ?? 'active'
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM website_sliders WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
