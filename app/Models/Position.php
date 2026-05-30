<?php

class Position
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getActive()
    {
        $stmt = $this->db->query("
            SELECT p.*, d.name AS department_name
            FROM positions p
            LEFT JOIN departments d ON d.id = p.department_id
            WHERE p.status = 'active'
            ORDER BY p.name ASC
            ");

        return $stmt->fetchAll();
    }

    public function countAll()
    {
        $stmt = $this->db->query("
            SELECT COUNT(*) AS total
            FROM positions
            ");

        $row = $stmt->fetch();

        return (int) ($row['total'] ?? 0);
    }

    public function paginate($limit = 10, $offset = 0)
    {
        $stmt = $this->db->prepare("
            SELECT 
            p.*,
            d.name AS department_name
            FROM positions p
            LEFT JOIN departments d ON d.id = p.department_id
            ORDER BY p.id DESC
            LIMIT {$limit} OFFSET {$offset}
            ");

        $stmt->execute();

        return $stmt->fetchAll();
    }


    public function all()
    {
        $stmt = $this->db->query("
            SELECT p.*, d.name AS department_name
            FROM positions p
            LEFT JOIN departments d ON d.id = p.department_id
            ORDER BY p.id DESC
            ");

        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM positions
            WHERE id = ?
            LIMIT 1
            ");

        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO positions
            (
                department_id,
                name,
                description,
                status
                )
            VALUES (?, ?, ?, ?)
            ");

        return $stmt->execute([
            $data['department_id'] ?? null,
            $data['name'],
            $data['description'] ?? '',
            $data['status'] ?? 'active'
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE positions SET
            department_id = ?,
            name = ?,
            description = ?,
            status = ?
            WHERE id = ?
            ");

        return $stmt->execute([
            $data['department_id'] ?? null,
            $data['name'],
            $data['description'] ?? '',
            $data['status'] ?? 'active',
            $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("
            DELETE FROM positions
            WHERE id = ?
            ");

        return $stmt->execute([$id]);
    }
}