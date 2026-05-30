<?php

class WebsiteAbout
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function first()
    {
        $stmt = $this->db->query("
            SELECT *
            FROM website_about
            ORDER BY id ASC
            LIMIT 1
        ");

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function active()
    {
        return $this->first();
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE website_about SET
                title_id = :title_id,
                title_en = :title_en,
                content_id = :content_id,
                content_en = :content_en,
                image = :image,
                image_2 = :image_2,
                vision_id = :vision_id,
                vision_en = :vision_en,
                mission_id = :mission_id,
                mission_en = :mission_en
            WHERE id = :id
        ");

        return $stmt->execute([
            ':id' => $id,
            ':title_id' => $data['title_id'],
            ':title_en' => $data['title_en'],
            ':content_id' => $data['content_id'],
            ':content_en' => $data['content_en'],
            ':image' => $data['image'],
            ':image_2' => $data['image_2'],
            ':vision_id' => $data['vision_id'],
            ':vision_en' => $data['vision_en'],
            ':mission_id' => $data['mission_id'],
            ':mission_en' => $data['mission_en'],
        ]);
    }
}
