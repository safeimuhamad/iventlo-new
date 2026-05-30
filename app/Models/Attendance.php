<?php

class Attendance
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function countAll($search = '')
    {
        $sql = "
            SELECT COUNT(*) AS total
            FROM attendances a
            LEFT JOIN employees e ON e.id = a.employee_id
            WHERE 1=1
        ";

        $params = [];

        if (!empty($search)) {
            $sql .= "
                AND (
                    e.full_name LIKE ?
                    OR e.employee_code LIKE ?
                    OR a.attendance_date LIKE ?
                    OR a.status LIKE ?
                )
            ";

            $keyword = "%{$search}%";
            $params = [$keyword, $keyword, $keyword, $keyword];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $row = $stmt->fetch();

        return (int) ($row['total'] ?? 0);
    }

    public function paginate($search = '', $limit = 10, $offset = 0)
    {
        $sql = "
            SELECT 
                a.*,
                e.employee_code,
                e.full_name,
                d.name AS department_name,
                p.name AS position_name
            FROM attendances a
            LEFT JOIN employees e ON e.id = a.employee_id
            LEFT JOIN departments d ON d.id = e.department_id
            LEFT JOIN positions p ON p.id = e.position_id
            WHERE 1=1
        ";

        $params = [];

        if (!empty($search)) {
            $sql .= "
                AND (
                    e.full_name LIKE ?
                    OR e.employee_code LIKE ?
                    OR a.attendance_date LIKE ?
                    OR a.status LIKE ?
                    OR d.name LIKE ?
                    OR p.name LIKE ?
                )
            ";

            $keyword = "%{$search}%";
            $params = [$keyword, $keyword, $keyword, $keyword, $keyword, $keyword];
        }

        $sql .= " ORDER BY a.attendance_date DESC, a.id DESC LIMIT {$limit} OFFSET {$offset}";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT 
                a.*,
                e.employee_code,
                e.full_name,
                d.name AS department_name,
                p.name AS position_name
            FROM attendances a
            LEFT JOIN employees e ON e.id = a.employee_id
            LEFT JOIN departments d ON d.id = e.department_id
            LEFT JOIN positions p ON p.id = e.position_id
            WHERE a.id = ?
            LIMIT 1
        ");

        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO attendances
            (
                employee_id,
                attendance_date,
                check_in,
                check_out,
                status,
                late_minutes,
                overtime_minutes,
                notes
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['employee_id'],
            $data['attendance_date'],
            $data['check_in'] ?? null,
            $data['check_out'] ?? null,
            $data['status'] ?? 'present',
            $data['late_minutes'] ?? 0,
            $data['overtime_minutes'] ?? 0,
            $data['notes'] ?? ''
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE attendances SET
                employee_id = ?,
                attendance_date = ?,
                check_in = ?,
                check_out = ?,
                status = ?,
                late_minutes = ?,
                overtime_minutes = ?,
                notes = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['employee_id'],
            $data['attendance_date'],
            $data['check_in'] ?? null,
            $data['check_out'] ?? null,
            $data['status'] ?? 'present',
            $data['late_minutes'] ?? 0,
            $data['overtime_minutes'] ?? 0,
            $data['notes'] ?? '',
            $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("
            DELETE FROM attendances
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }
}