<?php

class Customer
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function countAll()
    {
        $userId = $_SESSION['user_id'] ?? null;
        $role = $_SESSION['user_role'] ?? '';

        $sql = "
        SELECT COUNT(*) AS total
        FROM customers
        WHERE 1=1
        ";

        $params = [];

        if ($role === 'sales') {
            $sql .= " AND created_by = ?";
            $params[] = $userId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $row = $stmt->fetch();

        return (int) $row['total'];
    }

    public function companyExists($companyName, $excludeId = null)
    {
        $companyName = trim($companyName);

        $sql = "
            SELECT id
            FROM customers
            WHERE LOWER(TRIM(company_name)) = LOWER(TRIM(?))
        ";

        $params = [$companyName];

        if (!empty($excludeId)) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }

        $sql .= " LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetch();
    }

    public function hasTransactions($id)
    {
        $stmt = $this->db->prepare("
            SELECT 
                (
                    SELECT COUNT(*) 
                    FROM quotations 
                    WHERE customer_id = ?
                ) AS total_quotations,

                (
                    SELECT COUNT(*) 
                    FROM invoices 
                    WHERE customer_id = ?
                ) AS total_invoices
        ");

        $stmt->execute([$id, $id]);

        $row = $stmt->fetch();

        return [
            'has_transaction' => (
                ($row['total_quotations'] ?? 0) > 0 ||
                ($row['total_invoices'] ?? 0) > 0
            ),
            'total_quotations' => (int) ($row['total_quotations'] ?? 0),
            'total_invoices' => (int) ($row['total_invoices'] ?? 0),
        ];
    }

    public function getPaginated($limit, $offset)
    {
        $userId = $_SESSION['user_id'] ?? null;
        $role = $_SESSION['user_role'] ?? '';

        $sql = "
        SELECT *
        FROM customers
        WHERE 1=1
        ";

        $params = [];

        if ($role === 'sales') {
            $sql .= " AND created_by = ?";
            $params[] = $userId;
        }

        $sql .= "
        ORDER BY id DESC
        LIMIT ? OFFSET ?
        ";

        $stmt = $this->db->prepare($sql);

        $index = 1;

        foreach ($params as $param) {
            $stmt->bindValue($index, $param, PDO::PARAM_INT);
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
            INSERT INTO customers
            (
                company_name,
                pic_name,
                phone,
                email,
                address,
                npwp,
                status,
                created_by
                )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");

        $stmt->execute([
            $data['company_name'],
            $data['pic_name'],
            $data['phone'],
            $data['email'],
            $data['address'],
            $data['npwp'],
            $data['status'],
            $data['created_by'] ?? ($_SESSION['user_id'] ?? null)
        ]);

        return $this->db->lastInsertId();
    }

    public function find($id)
    {
        $userId = $_SESSION['user_id'] ?? null;
        $role = $_SESSION['user_role'] ?? '';

        $sql = "
        SELECT *
        FROM customers
        WHERE id = ?
        ";

        $params = [$id];

        if ($role === 'sales') {
            $sql .= " AND created_by = ?";
            $params[] = $userId;
        }

        $sql .= " LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetch();
    }

    public function update($id, $data)
    {
        $userId = $_SESSION['user_id'] ?? null;
        $role = $_SESSION['user_role'] ?? '';

        $sql = "
        UPDATE customers SET
        company_name = ?,
        pic_name = ?,
        phone = ?,
        email = ?,
        address = ?,
        npwp = ?,
        status = ?
        WHERE id = ?
        ";

        $params = [
            $data['company_name'],
            $data['pic_name'],
            $data['phone'],
            $data['email'],
            $data['address'],
            $data['npwp'],
            $data['status'],
            $id
        ];

        if ($role === 'sales') {
            $sql .= " AND created_by = ?";
            $params[] = $userId;
        }

        $stmt = $this->db->prepare($sql);

        return $stmt->execute($params);
    }

    public function delete($id)
    {
        $userId = $_SESSION['user_id'] ?? null;
        $role = $_SESSION['user_role'] ?? '';

        $sql = "
        DELETE FROM customers
        WHERE id = ?
        ";

        $params = [$id];

        if ($role === 'sales') {
            $sql .= " AND created_by = ?";
            $params[] = $userId;
        }

        $stmt = $this->db->prepare($sql);

        return $stmt->execute($params);
    }

    public function getActive()
    {
        $userId = $_SESSION['user_id'] ?? null;
        $role = $_SESSION['user_role'] ?? '';

        $sql = "
        SELECT *
        FROM customers
        WHERE status = 'active'
        ";

        $params = [];

        if ($role === 'sales') {
            $sql .= " AND created_by = ?";
            $params[] = $userId;
        }

        $sql .= " ORDER BY company_name ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function searchAjax($keyword = '')
    {
        $userId = $_SESSION['user_id'] ?? null;
        $role = $_SESSION['user_role'] ?? '';

        $sql = "
        SELECT id, company_name, phone, address
        FROM customers
        WHERE status = 'active'
        ";

        $params = [];

        if ($role === 'sales') {
            $sql .= " AND created_by = ?";
            $params[] = $userId;
        }

        if ($keyword !== '') {

            $sql .= "
            AND (
            company_name LIKE ?
            OR phone LIKE ?
            OR pic_name LIKE ?
            )
            ";

            $search = "%{$keyword}%";

            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }

        $sql .= "
        ORDER BY company_name ASC
        LIMIT 20
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function latest($limit = 6)
    {
        $limit = (int) $limit;

        $stmt = $this->db->query("
            SELECT *
            FROM customers
            ORDER BY id DESC
            LIMIT {$limit}
            ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function topCustomers($limit = 6)
    {
        $limit = (int) $limit;

        $stmt = $this->db->query("
            SELECT
                customer_id,
                customer_name,
                COUNT(*) AS total_quotation,
                MAX(created_at) AS last_quotation_date
            FROM quotations
            WHERE customer_name IS NOT NULL
            AND customer_name != ''
            GROUP BY customer_id, customer_name
            ORDER BY total_quotation DESC, last_quotation_date DESC
            LIMIT {$limit}
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function totalNewCustomersThisMonth()
    {
        $stmt = $this->db->query("
            SELECT COUNT(*) AS total
            FROM customers
            WHERE MONTH(created_at) = MONTH(CURDATE())
            AND YEAR(created_at) = YEAR(CURDATE())
        ");

        return (int) $stmt->fetch()['total'];
    }

    public function latestCustomers($limit = 5)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM customers
            ORDER BY created_at DESC
            LIMIT ?
        ");

        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLastInsertId()
    {
        return $this->db->lastInsertId();
    }
}