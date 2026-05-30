<?php

class Technician
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
            FROM technicians
            ORDER BY name ASC
            ");

        return $stmt->fetchAll();
    }

    public function getActive()
    {
        $stmt = $this->db->query("
            SELECT *
            FROM technicians
            WHERE status = 'active'
            ORDER BY name ASC
            ");

        return $stmt->fetchAll();
    }

    public function countAll()
    {
        $stmt = $this->db->query("SELECT COUNT(*) AS total FROM technicians");
        $row = $stmt->fetch();

        return (int) $row['total'];
    }

    public function getPaginated($limit, $offset)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM technicians
            ORDER BY name ASC
            LIMIT ? OFFSET ?
            ");

        $stmt->bindValue(1, (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(2, (int) $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM technicians
            WHERE id = ?
            LIMIT 1
            ");

        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO technicians (name, phone, role_type, status)
            VALUES (?, ?, ?, ?)
            ");

        $stmt->execute([
            $data['name'],
            $data['phone'],
            $data['role_type'],
            $data['status']
        ]);

        return $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE technicians SET
                name = ?,
                phone = ?,
                role_type = ?,
                status = ?
            WHERE id = ?
            ");

        return $stmt->execute([
            $data['name'],
            $data['phone'],
            $data['role_type'],
            $data['status'],
            $id
        ]);
    }

    public function getAvailableByDate($date)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM technicians
            WHERE status = 'active'
            AND id NOT IN (
                SELECT technician_id
                FROM rental_technicians
                WHERE scheduled_date = ?
                AND status != 'cancelled'
                )
            ORDER BY name ASC
            ");

        $stmt->execute([$date]);

        return $stmt->fetchAll();
    }

    public function getAssignedByDate($date)
    {
        $stmt = $this->db->prepare("
            SELECT 
            rt.*,
            t.name,
            r.no_rental,
            r.customer_name,
            r.lokasi
            FROM rental_technicians rt

            JOIN technicians t
            ON t.id = rt.technician_id

            JOIN rentals r
            ON r.id = rt.rental_id

            WHERE rt.scheduled_date = ?
            AND rt.status != 'cancelled'

            ORDER BY rt.scheduled_time ASC
            ");

        $stmt->execute([$date]);

        return $stmt->fetchAll();
    }
}
