<?php

class Department
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
            FROM departments
            WHERE status = 'active'
            ORDER BY name ASC
        ");

        return $stmt->fetchAll();
    }

    public function countAll()
    {
        $stmt = $this->db->query("
            SELECT COUNT(*) AS total
            FROM departments
        ");

        $row = $stmt->fetch();

        return (int) ($row['total'] ?? 0);
    }

    public function paginate($limit = 10, $offset = 0)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM departments
            ORDER BY id DESC
            LIMIT {$limit} OFFSET {$offset}
        ");

        $stmt->execute();

        return $stmt->fetchAll();
    }
    
    public function all()
    {
        $stmt = $this->db->query("
            SELECT *
            FROM departments
            ORDER BY id DESC
        ");

        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM departments
            WHERE id = ?
            LIMIT 1
        ");

        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO departments
            (
                name,
                description,
                status
            )
            VALUES (?, ?, ?)
        ");

        return $stmt->execute([
            $data['name'],
            $data['description'] ?? '',
            $data['status'] ?? 'active'
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE departments SET
                name = ?,
                description = ?,
                status = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['name'],
            $data['description'] ?? '',
            $data['status'] ?? 'active',
            $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("
            DELETE FROM departments
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }
}