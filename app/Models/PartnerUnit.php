<?php

class PartnerUnit
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function countAll()
    {
        $stmt = $this->db->query("SELECT COUNT(*) AS total FROM partner_units");
        $row = $stmt->fetch();

        return (int) $row['total'];
    }

    public function getPaginated($limit, $offset)
    {
        $stmt = $this->db->prepare("
            SELECT 
            pu.*,
            p.partner_name
            FROM partner_units pu
            LEFT JOIN partners p ON pu.partner_id = p.id
            ORDER BY pu.id DESC
            LIMIT ? OFFSET ?
            ");

        $stmt->bindValue(1, (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(2, (int) $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO partner_units 
            (
                partner_id,
                unit_name,
                category,
                brand,
                capacity,
                rental_cost,
                status
                )
            VALUES (?, ?, ?, ?, ?, ?, ?)
            ");

        $stmt->execute([
            $data['partner_id'],
            $data['unit_name'],
            $data['category'],
            $data['brand'],
            $data['capacity'],
            $data['rental_cost'],
            $data['status']
        ]);

        return $this->db->lastInsertId();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT
            pu.*,
            p.partner_name
            FROM partner_units pu
            LEFT JOIN partners p ON pu.partner_id = p.id
            WHERE pu.id = ?
            LIMIT 1
            ");

        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE partner_units SET
                partner_id = ?,
                unit_name = ?,
                category = ?,
                brand = ?,
                capacity = ?,
                rental_cost = ?,
                status = ?
            WHERE id = ?
            ");

        return $stmt->execute([
            $data['partner_id'],
            $data['unit_name'],
            $data['category'],
            $data['brand'],
            $data['capacity'],
            $data['rental_cost'],
            $data['status'],
            $id
        ]);
    }
}
