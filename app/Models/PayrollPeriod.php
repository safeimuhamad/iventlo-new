<?php

class PayrollPeriod
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
            FROM payroll_periods
            WHERE 1=1
        ";

        $params = [];

        if (!empty($search)) {
            $sql .= "
                AND (
                    period_name LIKE ?
                    OR status LIKE ?
                )
            ";

            $keyword = "%{$search}%";
            $params = [$keyword, $keyword];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $row = $stmt->fetch();

        return (int) ($row['total'] ?? 0);
    }

    public function paginate($search = '', $limit = 10, $offset = 0)
    {
        $sql = "
            SELECT *
            FROM payroll_periods
            WHERE 1=1
        ";

        $params = [];

        if (!empty($search)) {
            $sql .= "
                AND (
                    period_name LIKE ?
                    OR status LIKE ?
                )
            ";

            $keyword = "%{$search}%";
            $params = [$keyword, $keyword];
        }

        $sql .= " ORDER BY id DESC LIMIT {$limit} OFFSET {$offset}";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM payroll_periods
            WHERE id = ?
            LIMIT 1
        ");

        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO payroll_periods
            (
                period_name,
                start_date,
                end_date,
                payroll_date,
                status,
                notes
            )
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['period_name'],
            $data['start_date'],
            $data['end_date'],
            $data['payroll_date'] ?? null,
            $data['status'] ?? 'draft',
            $data['notes'] ?? ''
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE payroll_periods SET
                period_name = ?,
                start_date = ?,
                end_date = ?,
                payroll_date = ?,
                status = ?,
                notes = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['period_name'],
            $data['start_date'],
            $data['end_date'],
            $data['payroll_date'] ?? null,
            $data['status'] ?? 'draft',
            $data['notes'] ?? '',
            $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("
            DELETE FROM payroll_periods
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }
}