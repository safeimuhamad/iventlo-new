<?php

class Vehicle
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
            FROM vehicles
            ORDER BY id DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActive()
    {
        $stmt = $this->db->query("
            SELECT *
            FROM vehicles
            WHERE status = 'active'
            ORDER BY vehicle_name ASC
        ");

        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM vehicles
            WHERE id = ?
        ");
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO vehicles
            (
                vehicle_code,
                vehicle_name,
                plate_number,
                vehicle_type,
                brand,
                year,
                total_km,
                maintenance_interval_km,
                maintenance_interval_month,
                stnk_expired_date,
                tax_expired_date,
                kir_expired_date,
                notes
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['vehicle_code'],
            $data['vehicle_name'],
            $data['plate_number'],
            $data['vehicle_type'] ?? null,
            $data['brand'] ?? null,
            !empty($data['year']) ? (int) $data['year'] : null,
            !empty($data['total_km']) ? (int) $data['total_km'] : 0,
            !empty($data['maintenance_interval_km']) ? (int) $data['maintenance_interval_km'] : 5000,
            !empty($data['maintenance_interval_month']) ? (int) $data['maintenance_interval_month'] : 3,
            $data['stnk_expired_date'] ?? null,
            $data['tax_expired_date'] ?? null,
            $data['kir_expired_date'] ?? null,
            $data['notes'] ?? null
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE vehicles
            SET
                vehicle_code = ?,
                vehicle_name = ?,
                plate_number = ?,
                vehicle_type = ?,
                brand = ?,
                year = ?,
                total_km = ?,
                maintenance_interval_km = ?,
                maintenance_interval_month = ?,
                stnk_expired_date = ?,
                tax_expired_date = ?,
                kir_expired_date = ?,
                notes = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['vehicle_code'],
            $data['vehicle_name'],
            $data['plate_number'],
            $data['vehicle_type'] ?? null,
            $data['brand'] ?? null,
            !empty($data['year']) ? (int) $data['year'] : null,
            !empty($data['total_km']) ? (int) $data['total_km'] : 0,
            !empty($data['maintenance_interval_km']) ? (int) $data['maintenance_interval_km'] : 5000,
            !empty($data['maintenance_interval_month']) ? (int) $data['maintenance_interval_month'] : 3,
            $data['stnk_expired_date'] ?? null,
            $data['tax_expired_date'] ?? null,
            $data['kir_expired_date'] ?? null,
            $data['notes'] ?? null,
            $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("
            DELETE FROM vehicles
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }

public function reminders()
{
    $stmt = $this->db->query("
        SELECT *,
            DATEDIFF(stnk_expired_date, CURDATE()) AS stnk_remaining_days,
            DATEDIFF(tax_expired_date, CURDATE()) AS tax_remaining_days,
            DATEDIFF(kir_expired_date, CURDATE()) AS kir_remaining_days
        FROM vehicles
        WHERE 
            stnk_expired_date IS NOT NULL
            OR tax_expired_date IS NOT NULL
            OR kir_expired_date IS NOT NULL
        ORDER BY 
            kir_expired_date ASC,
            stnk_expired_date ASC,
            tax_expired_date ASC
    ");

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


}