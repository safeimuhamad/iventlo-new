<?php

class RentalTechnician
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getByRental($rentalId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                rt.*,
                t.name,
                t.phone,
                t.role_type
            FROM rental_technicians rt
            JOIN technicians t ON t.id = rt.technician_id
            WHERE rt.rental_id = ?
            ORDER BY rt.task_type ASC, t.name ASC
        ");

        $stmt->execute([$rentalId]);
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO rental_technicians
            (
                rental_id,
                technician_id,
                task_type,
                scheduled_date,
                scheduled_time,
                status
            )
            VALUES (?, ?, ?, ?, ?, 'assigned')
        ");

        return $stmt->execute([
            $data['rental_id'],
            $data['technician_id'],
            $data['task_type'],
            $data['scheduled_date'],
            $data['scheduled_time']
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM rental_technicians WHERE id = ?");
        return $stmt->execute([$id]);
    }
}