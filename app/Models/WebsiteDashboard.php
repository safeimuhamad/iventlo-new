<?php

class WebsiteDashboard
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function countActiveSliders()
    {
        $stmt = $this->db->query("
            SELECT COUNT(*) 
            FROM website_sliders 
            WHERE status = 'active'
        ");

        return (int) $stmt->fetchColumn();
    }

    public function countTotalInquiries()
    {
        $stmt = $this->db->query("
            SELECT COUNT(*) 
            FROM website_inquiries
        ");

        return (int) $stmt->fetchColumn();
    }

    public function countTodayInquiries()
    {
        $stmt = $this->db->query("
            SELECT COUNT(*) 
            FROM website_inquiries
            WHERE DATE(created_at) = CURDATE()
        ");

        return (int) $stmt->fetchColumn();
    }

    public function countThisMonthInquiries()
    {
        $stmt = $this->db->query("
            SELECT COUNT(*) 
            FROM website_inquiries
            WHERE YEAR(created_at) = YEAR(CURDATE())
            AND MONTH(created_at) = MONTH(CURDATE())
        ");

        return (int) $stmt->fetchColumn();
    }

    public function latestInquiries($limit = 5)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM website_inquiries
            ORDER BY created_at DESC
            LIMIT :limit
        ");

        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}