<?php

class ApprovalMatrix
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
            FROM approval_matrices am
            LEFT JOIN departments d ON d.id = am.department_id
            LEFT JOIN roles r ON r.id = am.approver_role_id
            LEFT JOIN users u ON u.id = am.approver_user_id
            WHERE 1=1
        ";

        $params = [];

        if (!empty($search)) {
            $sql .= "
                AND (
                    am.module_name LIKE ?
                    OR am.document_type LIKE ?
                    OR d.name LIKE ?
                    OR r.name LIKE ?
                    OR u.name LIKE ?
                )
            ";

            $keyword = "%{$search}%";
            $params = [$keyword, $keyword, $keyword, $keyword, $keyword];
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
                am.*,
                d.name AS department_name,
                r.name AS approver_role_name,
                u.name AS approver_user_name
            FROM approval_matrices am
            LEFT JOIN departments d ON d.id = am.department_id
            LEFT JOIN roles r ON r.id = am.approver_role_id
            LEFT JOIN users u ON u.id = am.approver_user_id
            WHERE 1=1
        ";

        $params = [];

        if (!empty($search)) {
            $sql .= "
                AND (
                    am.module_name LIKE ?
                    OR am.document_type LIKE ?
                    OR d.name LIKE ?
                    OR r.name LIKE ?
                    OR u.name LIKE ?
                )
            ";

            $keyword = "%{$search}%";
            $params = [$keyword, $keyword, $keyword, $keyword, $keyword];
        }

        $sql .= " ORDER BY am.module_name ASC, am.approval_level ASC, am.min_amount ASC LIMIT {$limit} OFFSET {$offset}";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT 
                am.*,
                d.name AS department_name,
                r.name AS approver_role_name,
                u.name AS approver_user_name
            FROM approval_matrices am
            LEFT JOIN departments d ON d.id = am.department_id
            LEFT JOIN roles r ON r.id = am.approver_role_id
            LEFT JOIN users u ON u.id = am.approver_user_id
            WHERE am.id = ?
            LIMIT 1
        ");

        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO approval_matrices
            (
                module_name,
                document_type,
                min_amount,
                max_amount,
                department_id,
                approval_level,
                approver_role_id,
                approver_user_id,
                is_active
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['module_name'],
            $data['document_type'] ?? null,
            $data['min_amount'] ?? 0,
            $data['max_amount'] !== '' ? $data['max_amount'] : null,
            $data['department_id'] !== '' ? $data['department_id'] : null,
            $data['approval_level'] ?? 1,
            $data['approver_role_id'] !== '' ? $data['approver_role_id'] : null,
            $data['approver_user_id'] !== '' ? $data['approver_user_id'] : null,
            $data['is_active'] ?? 1
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE approval_matrices SET
                module_name = ?,
                document_type = ?,
                min_amount = ?,
                max_amount = ?,
                department_id = ?,
                approval_level = ?,
                approver_role_id = ?,
                approver_user_id = ?,
                is_active = ?,
                updated_at = NOW()
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['module_name'],
            $data['document_type'] ?? null,
            $data['min_amount'] ?? 0,
            $data['max_amount'] !== '' ? $data['max_amount'] : null,
            $data['department_id'] !== '' ? $data['department_id'] : null,
            $data['approval_level'] ?? 1,
            $data['approver_role_id'] !== '' ? $data['approver_role_id'] : null,
            $data['approver_user_id'] !== '' ? $data['approver_user_id'] : null,
            $data['is_active'] ?? 1,
            $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM approval_matrices WHERE id = ?");
        return $stmt->execute([$id]);
    }
}