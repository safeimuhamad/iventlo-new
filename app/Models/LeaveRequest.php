<?php

class LeaveRequest
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
            FROM leave_requests lr
            LEFT JOIN employees e ON e.id = lr.employee_id
            WHERE 1=1
        ";

        $params = [];

        if (!empty($search)) {
            $sql .= "
                AND (
                    e.full_name LIKE ?
                    OR e.employee_code LIKE ?
                    OR lr.leave_type LIKE ?
                    OR lr.status LIKE ?
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
                lr.*,
                e.employee_code,
                e.full_name,
                d.name AS department_name,
                p.name AS position_name
            FROM leave_requests lr
            LEFT JOIN employees e ON e.id = lr.employee_id
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
                    OR lr.leave_type LIKE ?
                    OR lr.status LIKE ?
                )
            ";

            $keyword = "%{$search}%";
            $params = [$keyword, $keyword, $keyword, $keyword];
        }

        $sql .= " ORDER BY lr.id DESC LIMIT {$limit} OFFSET {$offset}";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT 
                lr.*,
                e.employee_code,
                e.full_name,
                d.name AS department_name,
                p.name AS position_name,
                u.name AS approved_by_name
            FROM leave_requests lr
            LEFT JOIN employees e ON e.id = lr.employee_id
            LEFT JOIN departments d ON d.id = e.department_id
            LEFT JOIN positions p ON p.id = e.position_id
            LEFT JOIN users u ON u.id = lr.approved_by
            WHERE lr.id = ?
            LIMIT 1
        ");

        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO leave_requests
            (
                employee_id,
                leave_type,
                start_date,
                end_date,
                total_days,
                reason,
                status
            )
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['employee_id'],
            $data['leave_type'] ?? 'annual_leave',
            $data['start_date'],
            $data['end_date'],
            $data['total_days'] ?? 1,
            $data['reason'] ?? '',
            $data['status'] ?? 'draft'
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE leave_requests SET
                employee_id = ?,
                leave_type = ?,
                start_date = ?,
                end_date = ?,
                total_days = ?,
                reason = ?,
                status = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['employee_id'],
            $data['leave_type'] ?? 'annual_leave',
            $data['start_date'],
            $data['end_date'],
            $data['total_days'] ?? 1,
            $data['reason'] ?? '',
            $data['status'] ?? 'draft',
            $id
        ]);
    }

    public function approve($id, $userId)
    {
        $stmt = $this->db->prepare("
            UPDATE leave_requests SET
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
            UPDATE leave_requests SET
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
            DELETE FROM leave_requests
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }
}