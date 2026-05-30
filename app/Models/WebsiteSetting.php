<?php

class WebsiteSetting
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function first()
    {
        $stmt = $this->db->query("SELECT * FROM website_settings ORDER BY id ASC LIMIT 1");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE website_settings SET
                company_name = :company_name,
                tagline = :tagline,
                logo = :logo,
                logo_white = :logo_white,
                favicon = :favicon,
                phone = :phone,
                whatsapp = :whatsapp,
                email = :email,
                address = :address,
                instagram = :instagram,
                facebook = :facebook,
                linkedin = :linkedin,
                youtube = :youtube,
                tiktok = :tiktok,
                google_map = :google_map,
                meta_title = :meta_title,
                meta_keywords = :meta_keywords,
                meta_description = :meta_description
            WHERE id = :id
        ");

        return $stmt->execute([
            ':id' => $id,
            ':company_name' => $data['company_name'],
            ':tagline' => $data['tagline'],
            ':logo' => $data['logo'],
            ':logo_white' => $data['logo_white'],
            ':favicon' => $data['favicon'],
            ':phone' => $data['phone'],
            ':whatsapp' => $data['whatsapp'],
            ':email' => $data['email'],
            ':address' => $data['address'],
            ':instagram' => $data['instagram'],
            ':facebook' => $data['facebook'],
            ':linkedin' => $data['linkedin'],
            ':youtube' => $data['youtube'],
            ':tiktok' => $data['tiktok'],
            ':google_map' => $data['google_map'],
            ':meta_title' => $data['meta_title'],
            ':meta_keywords' => $data['meta_keywords'],
            ':meta_description' => $data['meta_description'],
        ]);
    }
}