<?php

class WebsiteInquiry
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function create($data)
    {
        $sql = "INSERT INTO website_inquiries 
        (name, email, phone, company_name, service_interest, message, status, source)
        VALUES 
        (:name, :email, :phone, :company_name, :service_interest, :message, :status, :source)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':phone' => $data['phone'] ?? null,
            ':company_name' => $data['company_name'] ?? null,
            ':service_interest' => $data['service_interest'] ?? null,
            ':message' => $data['message'],
            ':status' => 'new',
            ':source' => 'website'
        ]);
    }


    public function all()
    {
        $stmt = $this->db->query("
            SELECT *
            FROM website_inquiries
            ORDER BY created_at DESC
            ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function paginate($limit, $offset)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM website_inquiries
            ORDER BY created_at DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->bindValue(1, (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(2, (int) $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll()
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM website_inquiries")->fetchColumn();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM website_inquiries
            WHERE id = ?
            ");

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE website_inquiries SET
                status = :status,
                notes = :notes,
                follow_up_date = :follow_up_date
                WHERE id = :id
                ");

        return $stmt->execute([
            ':id' => $id,
            ':status' => $data['status'],
            ':notes' => $data['notes'],
            ':follow_up_date' => $data['follow_up_date'] ?: null
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("
            DELETE FROM website_inquiries
                WHERE id = ?
                ");

        return $stmt->execute([$id]);
    }
}
