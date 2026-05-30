<?php

class RecruitmentApplicant
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function generateNumber()
    {
        $prefix = 'APP-' . date('Ym') . '-';

        $stmt = $this->db->prepare("
            SELECT applicant_number
            FROM recruitment_applicants
            WHERE applicant_number LIKE ?
            ORDER BY id DESC
            LIMIT 1
        ");

        $stmt->execute([$prefix . '%']);
        $last = $stmt->fetch();

        $number = 1;

        if ($last) {
            $lastNumber = (int) substr($last['applicant_number'], -4);
            $number = $lastNumber + 1;
        }

        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function countAll($search = '')
    {
        $sql = "
            SELECT COUNT(*) AS total
            FROM recruitment_applicants ra
            LEFT JOIN departments d ON d.id = ra.department_id
            LEFT JOIN positions p ON p.id = ra.position_id
            WHERE 1=1
        ";

        $params = [];

        if ($search !== '') {
            $sql .= "
                AND (
                    ra.applicant_number LIKE ?
                    OR ra.full_name LIKE ?
                    OR ra.phone LIKE ?
                    OR ra.email LIKE ?
                    OR ra.status LIKE ?
                    OR d.name LIKE ?
                    OR p.name LIKE ?
                )
            ";

            $keyword = '%' . $search . '%';

            $params = [
                $keyword,
                $keyword,
                $keyword,
                $keyword,
                $keyword,
                $keyword,
                $keyword
            ];
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
                ra.*,
                d.name AS department_name,
                p.name AS position_name,
                u.name AS created_by_name
            FROM recruitment_applicants ra
            LEFT JOIN departments d ON d.id = ra.department_id
            LEFT JOIN positions p ON p.id = ra.position_id
            LEFT JOIN users u ON u.id = ra.created_by
            WHERE 1=1
        ";

        $params = [];

        if ($search !== '') {
            $sql .= "
                AND (
                    ra.applicant_number LIKE ?
                    OR ra.full_name LIKE ?
                    OR ra.phone LIKE ?
                    OR ra.email LIKE ?
                    OR ra.status LIKE ?
                    OR d.name LIKE ?
                    OR p.name LIKE ?
                )
            ";

            $keyword = '%' . $search . '%';

            $params = [
                $keyword,
                $keyword,
                $keyword,
                $keyword,
                $keyword,
                $keyword,
                $keyword
            ];
        }

        $sql .= "
            ORDER BY ra.id DESC
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
            INSERT INTO recruitment_applicants
            (
                applicant_number,
                full_name,
                phone,
                email,
                address,
                department_id,
                position_id,
                source,
                expected_salary,
                cv_file,
                portfolio_file,
                google_drive_url,
                status,
                notes,
                interview_date,
                created_by
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['applicant_number'],
            $data['full_name'],
            $data['phone'],
            $data['email'],
            $data['address'],
            $data['department_id'] ?? null,
            $data['position_id'] ?? null,
            $data['source'],
            $data['expected_salary'] ?? 0,
            $data['cv_file'] ?? null,
            $data['portfolio_file'] ?? null,
            $data['google_drive_url'] ?? null,
            $data['status'] ?? 'new',
            $data['notes'],
            $data['interview_date'] ?? null,
            $data['created_by'] ?? ($_SESSION['user_id'] ?? null)
        ]);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT
                ra.*,
                d.name AS department_name,
                p.name AS position_name,
                u.name AS created_by_name,
                e.full_name AS converted_employee_name
            FROM recruitment_applicants ra
            LEFT JOIN departments d ON d.id = ra.department_id
            LEFT JOIN positions p ON p.id = ra.position_id
            LEFT JOIN users u ON u.id = ra.created_by
            LEFT JOIN employees e ON e.id = ra.converted_employee_id
            WHERE ra.id = ?
            LIMIT 1
        ");

        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE recruitment_applicants SET
                full_name = ?,
                phone = ?,
                email = ?,
                address = ?,
                department_id = ?,
                position_id = ?,
                source = ?,
                expected_salary = ?,
                cv_file = ?,
                portfolio_file = ?,
                google_drive_url = ?,
                status = ?,
                notes = ?,
                interview_date = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['full_name'],
            $data['phone'],
            $data['email'],
            $data['address'],
            $data['department_id'] ?? null,
            $data['position_id'] ?? null,
            $data['source'],
            $data['expected_salary'] ?? 0,
            $data['cv_file'] ?? null,
            $data['portfolio_file'] ?? null,
            $data['google_drive_url'] ?? null,
            $data['status'] ?? 'new',
            $data['notes'],
            $data['interview_date'] ?? null,
            $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("
            DELETE FROM recruitment_applicants
            WHERE id = ?
            AND converted_employee_id IS NULL
        ");

        return $stmt->execute([$id]);
    }

    public function markAsConverted($id, $employeeId)
    {
        $stmt = $this->db->prepare("
            UPDATE recruitment_applicants SET
                status = 'hired',
                converted_employee_id = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $employeeId,
            $id
        ]);
    }
}