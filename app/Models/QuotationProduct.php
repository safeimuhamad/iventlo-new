<?php

class QuotationProduct
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getActive()
    {
        $stmt = $this->db->query("
            SELECT *
            FROM quotation_products
            WHERE status = 'active'
            ORDER BY name ASC
        ");

        return $stmt->fetchAll();
    }
}