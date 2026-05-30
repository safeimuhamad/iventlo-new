<?php

class OvertimeRequest
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
            FROM overtime_requests o
            LEFT JOIN employees e ON e.id = o.employee_id
            WHERE 1=1
        ";

        $params = [];

        if (!empty($search)) {
            $sql .= "
                AND (
                    e.full_name LIKE ?
                    OR e.employee_code LIKE ?
                    OR o.overtime_date LIKE ?
                    OR o.status LIKE ?
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
                o.*,
                e.employee_code,
                e.full_name,
                d.name AS department_name,
                p.name AS position_name
            FROM overtime_requests o
            LEFT JOIN employees e ON e.id = o.employee_id
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
                    OR o.overtime_date LIKE ?
                    OR o.status LIKE ?
                )
            ";

            $keyword = "%{$search}%";
            $params = [$keyword, $keyword, $keyword, $keyword];
        }

        $sql .= " ORDER BY o.id DESC LIMIT {$limit} OFFSET {$offset}";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT 
                o.*,
                e.employee_code,
                e.full_name,
                d.name AS department_name,
                p.name AS position_name,
                u.name AS approved_by_name
            FROM overtime_requests o
            LEFT JOIN employees e ON e.id = o.employee_id
            LEFT JOIN departments d ON d.id = e.department_id
            LEFT JOIN positions p ON p.id = e.position_id
            LEFT JOIN users u ON u.id = o.approved_by
            WHERE o.id = ?
            LIMIT 1
        ");

        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO overtime_requests
            (
                employee_id,
                overtime_date,
                start_time,
                end_time,
                total_minutes,
                reason,
                status
            )
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['employee_id'],
            $data['overtime_date'],
            $data['start_time'],
            $data['end_time'],
            $data['total_minutes'] ?? 0,
            $data['reason'] ?? '',
            $data['status'] ?? 'draft'
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE overtime_requests SET
                employee_id = ?,
                overtime_date = ?,
                start_time = ?,
                end_time = ?,
                total_minutes = ?,
                reason = ?,
                status = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['employee_id'],
            $data['overtime_date'],
            $data['start_time'],
            $data['end_time'],
            $data['total_minutes'] ?? 0,
            $data['reason'] ?? '',
            $data['status'] ?? 'draft',
            $id
        ]);
    }

    public function approve($id, $userId)
    {
        $stmt = $this->db->prepare("
            UPDATE overtime_requests SET
                status = 'approved',
                approved_by = ?,
                approved_at = NOW()
            WHERE id = ?
        ");

        return $stmt->execute([$userId, $id]);
    }

    public function reject($id, $reason, $userId)
    {
        $stmt = $this->db->prepare("
            UPDATE overtime_requests SET
                status = 'rejected',
                approved_by = ?,
                approved_at = NOW(),
                rejected_reason = ?
            WHERE id = ?
        ");

        return $stmt->execute([$userId, $reason, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("
            DELETE FROM overtime_requests
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }
}