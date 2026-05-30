<?php

class EmployeeContract
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function generateNumber()
    {
        $prefix = 'CTR-' . date('Ym') . '-';

        $stmt = $this->db->prepare("
            SELECT contract_number
            FROM employee_contracts
            WHERE contract_number LIKE ?
            ORDER BY id DESC
            LIMIT 1
        ");

        $stmt->execute([$prefix . '%']);
        $last = $stmt->fetch();

        $number = 1;

        if ($last) {
            $lastNumber = (int) substr($last['contract_number'], -4);
            $number = $lastNumber + 1;
        }

        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function countAll($search = '')
    {
        $sql = "
            SELECT COUNT(*) AS total
            FROM employee_contracts ec
            LEFT JOIN employees e ON e.id = ec.employee_id
            WHERE 1=1
        ";

        $params = [];

        if ($search !== '') {
            $sql .= "
                AND (
                    ec.contract_number LIKE ?
                    OR e.full_name LIKE ?
                    OR e.employee_code LIKE ?
                    OR ec.contract_type LIKE ?
                    OR ec.status LIKE ?
                )
            ";

            $keyword = '%' . $search . '%';
            $params = [$keyword, $keyword, $keyword, $keyword, $keyword];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $row = $stmt->fetch();

        return (int) ($row['total'] ?? 0);
    }

    public function getPaginated($limit, $offset, $search = '')
    {
        $sql = "
            SELECT
                ec.*,
                e.full_name AS employee_name,
                e.employee_code,
                u.name AS created_by_name
            FROM employee_contracts ec
            LEFT JOIN employees e ON e.id = ec.employee_id
            LEFT JOIN users u ON u.id = ec.created_by
            WHERE 1=1
        ";

        $params = [];

        if ($search !== '') {
            $sql .= "
                AND (
                    ec.contract_number LIKE ?
                    OR e.full_name LIKE ?
                    OR e.employee_code LIKE ?
                    OR ec.contract_type LIKE ?
                    OR ec.status LIKE ?
                )
            ";

            $keyword = '%' . $search . '%';
            $params = [$keyword, $keyword, $keyword, $keyword, $keyword];
        }

        $sql .= "
            ORDER BY ec.id DESC
            LIMIT ? OFFSET ?
        ";

        $stmt = $this->db->prepare($sql);

        $index = 1;

        foreach ($params as $param) {
            $stmt->bindValue($index, $param);
            $index++;
        }

        $stmt->bindValue($index, (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue($index + 1, (int) $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO employee_contracts
            (
                employee_id,
                contract_number,
                contract_type,
                start_date,
                end_date,
                salary,
                job_title,
                work_location,
                status,
                notes,
                document_file,
                contract_pdf_url,
                created_by
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['employee_id'],
            $data['contract_number'],
            $data['contract_type'],
            $data['start_date'],
            $data['end_date'] ?? null,
            $data['salary'] ?? 0,
            $data['job_title'] ?? '',
            $data['work_location'] ?? '',
            $data['status'] ?? 'active',
            $data['notes'] ?? '',
            $data['document_file'] ?? null,
            $data['contract_pdf_url'] ?? null,
            $data['created_by'] ?? ($_SESSION['user_id'] ?? null)
        ]);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT
                ec.*,
                e.full_name AS employee_name,
                e.employee_code,
                e.phone AS employee_phone,
                e.email AS employee_email,
                u.name AS created_by_name
            FROM employee_contracts ec
            LEFT JOIN employees e ON e.id = ec.employee_id
            LEFT JOIN users u ON u.id = ec.created_by
            WHERE ec.id = ?
            LIMIT 1
        ");

        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE employee_contracts SET
                employee_id = ?,
                contract_type = ?,
                start_date = ?,
                end_date = ?,
                salary = ?,
                job_title = ?,
                work_location = ?,
                status = ?,
                notes = ?,
                document_file = ?,
                contract_pdf_url = ?,
                updated_at = NOW()
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['employee_id'],
            $data['contract_type'],
            $data['start_date'],
            $data['end_date'] ?? null,
            $data['salary'] ?? 0,
            $data['job_title'] ?? '',
            $data['work_location'] ?? '',
            $data['status'] ?? 'active',
            $data['notes'] ?? '',
            $data['document_file'] ?? null,
            $data['contract_pdf_url'] ?? null,
            $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("
            DELETE FROM employee_contracts
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }
}