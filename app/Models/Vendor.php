<?php

class Vendor
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getAll()
    {
        $stmt = $this->db->query("
            SELECT *
            FROM vendors
            WHERE is_active = 1
            ORDER BY vendor_name ASC
        ");

        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM vendors
            WHERE id = ?
            LIMIT 1
        ");

        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function generateCode()
    {
        return 'VDR-' . date('Ymd-His');
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO vendors
            (
                vendor_code,
                vendor_name,
                phone,
                email,
                address,
                npwp,
                pic_name,
                notes,
                is_active
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)
        ");

        return $stmt->execute([
            $data['vendor_code'],
            $data['vendor_name'],
            $data['phone'],
            $data['email'],
            $data['address'],
            $data['npwp'],
            $data['pic_name'],
            $data['notes']
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE vendors SET
                vendor_name = ?,
                phone = ?,
                email = ?,
                address = ?,
                npwp = ?,
                pic_name = ?,
                notes = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['vendor_name'],
            $data['phone'],
            $data['email'],
            $data['address'],
            $data['npwp'],
            $data['pic_name'],
            $data['notes'],
            $id
        ]);
    }

    public function softDelete($id)
    {
        $stmt = $this->db->prepare("
            UPDATE vendors
            SET is_active = 0
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }
}