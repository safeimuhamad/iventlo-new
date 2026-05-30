<?php

class Employee
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
        FROM employees e
        LEFT JOIN departments d ON d.id = e.department_id
        LEFT JOIN positions p ON p.id = e.position_id
        WHERE 1=1
        ";

        $params = [];

        if (!empty($search)) {
            $sql .= "
            AND (
            e.employee_code LIKE ?
            OR e.full_name LIKE ?
            OR e.phone LIKE ?
            OR d.name LIKE ?
            OR p.name LIKE ?
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
        e.*,
        d.name AS department_name,
        p.name AS position_name
        FROM employees e
        LEFT JOIN departments d ON d.id = e.department_id
        LEFT JOIN positions p ON p.id = e.position_id
        WHERE 1=1
        ";

        $params = [];

        if (!empty($search)) {
            $sql .= "
            AND (
            e.employee_code LIKE ?
            OR e.full_name LIKE ?
            OR e.phone LIKE ?
            OR d.name LIKE ?
            OR p.name LIKE ?
            )
            ";

            $keyword = "%{$search}%";
            $params = [$keyword, $keyword, $keyword, $keyword, $keyword];
        }

        $sql .= " ORDER BY e.id DESC LIMIT {$limit} OFFSET {$offset}";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT 
            e.*,
            d.name AS department_name,
            p.name AS position_name
            FROM employees e
            LEFT JOIN departments d ON d.id = e.department_id
            LEFT JOIN positions p ON p.id = e.position_id
            WHERE e.id = ?
            LIMIT 1
            ");

        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO employees
            (
                employee_code,
                full_name,
                nickname,
                gender,
                birth_place,
                birth_date,
                phone,
                email,
                address,
                department_id,
                position_id,
                employment_status,
                join_date,
                basic_salary,
                bank_name,
                bank_account_number,
                bank_account_name,
                status
                )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

        return $stmt->execute([
            $data['employee_code'],
            $data['full_name'],
            $data['nickname'] ?? null,
            $data['gender'] ?? null,
            $data['birth_place'] ?? null,
            $data['birth_date'] ?? null,
            $data['phone'] ?? null,
            $data['email'] ?? null,
            $data['address'] ?? null,
            $data['department_id'] ?? null,
            $data['position_id'] ?? null,
            $data['employment_status'] ?? 'permanent',
            $data['join_date'] ?? null,
            $data['basic_salary'] ?? 0,
            $data['bank_name'] ?? null,
            $data['bank_account_number'] ?? null,
            $data['bank_account_name'] ?? null,
            $data['status'] ?? 'active'
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE employees SET
            employee_code = ?,
            full_name = ?,
            nickname = ?,
            gender = ?,
            birth_place = ?,
            birth_date = ?,
            phone = ?,
            email = ?,
            address = ?,
            department_id = ?,
            position_id = ?,
            employment_status = ?,
            join_date = ?,
            basic_salary = ?,
            bank_name = ?,
            bank_account_number = ?,
            bank_account_name = ?,
            status = ?
            WHERE id = ?
            ");

        return $stmt->execute([
            $data['employee_code'],
            $data['full_name'],
            $data['nickname'] ?? null,
            $data['gender'] ?? null,
            $data['birth_place'] ?? null,
            $data['birth_date'] ?? null,
            $data['phone'] ?? null,
            $data['email'] ?? null,
            $data['address'] ?? null,
            $data['department_id'] ?? null,
            $data['position_id'] ?? null,
            $data['employment_status'] ?? 'permanent',
            $data['join_date'] ?? null,
            $data['basic_salary'] ?? 0,
            $data['bank_name'] ?? null,
            $data['bank_account_number'] ?? null,
            $data['bank_account_name'] ?? null,
            $data['status'] ?? 'active',
            $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("
            DELETE FROM employees
            WHERE id = ?
            ");

        return $stmt->execute([$id]);
    }

    public function getActive()
    {
        $stmt = $this->db->query("
            SELECT 
                e.*,
                d.name AS department_name,
                p.name AS position_name
            FROM employees e
            LEFT JOIN departments d ON d.id = e.department_id
            LEFT JOIN positions p ON p.id = e.position_id
            WHERE e.status = 'active'
            ORDER BY e.full_name ASC
        ");

        return $stmt->fetchAll();
    }

    public function createFromApplicant($applicant)
    {
        $employeeCode = $this->generateEmployeeCode();

        $stmt = $this->db->prepare("
            INSERT INTO employees
            (
                employee_code,
                full_name,
                phone,
                email,
                address,
                department_id,
                position_id,
                basic_salary,
                status,
                created_at
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");

        $stmt->execute([
            $employeeCode,
            $applicant['full_name'] ?? '',
            $applicant['phone'] ?? '',
            $applicant['email'] ?? '',
            $applicant['address'] ?? '',
            $applicant['department_id'] ?? null,
            $applicant['position_id'] ?? null,
            $applicant['expected_salary'] ?? 0,
            'active'
        ]);

        return $this->db->lastInsertId();
    }

    public function generateEmployeeCode()
    {
        $prefix = date('Ymd');

        $stmt = $this->db->prepare("
            SELECT employee_code
            FROM employees
            WHERE employee_code LIKE ?
            ORDER BY id DESC
            LIMIT 1
        ");

        $stmt->execute([$prefix . '%']);

        $last = $stmt->fetch();

        $number = 1;

        if ($last && !empty($last['employee_code'])) {
            $lastNumber = (int) substr($last['employee_code'], -3);
            $number = $lastNumber + 1;
        }

        return $prefix . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    public function all()
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM employees
            WHERE status = 'active'
            ORDER BY full_name ASC
        ");

        $stmt->execute();

        return $stmt->fetchAll();
    }
}