<?php

class Quotation
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function countAll($search = '')
    {
        $userId = $_SESSION['user_id'] ?? null;
        $role = $_SESSION['user_role'] ?? '';

        $sql = "
        SELECT COUNT(*) AS total
        FROM quotations q
        LEFT JOIN customers c ON c.id = q.customer_id
        WHERE 1=1
        ";

    /*
    |--------------------------------------------------------------------------
    | Restrict only sales
    |--------------------------------------------------------------------------
    */

    if ($role === 'sales') {
        $sql .= " AND q.created_by = :user_id";
    }

    if (!empty($search)) {
        $sql .= "
        AND (
        q.no_quotation LIKE :search
        OR q.customer_name LIKE :search
        OR q.customer_phone LIKE :search
        OR q.status LIKE :search
        OR c.company_name LIKE :search
        )
        ";
    }

    $stmt = $this->db->prepare($sql);

    if ($role === 'sales') {
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    }

    if (!empty($search)) {
        $stmt->bindValue(':search', '%' . $search . '%');
    }

    $stmt->execute();

    return (int) $stmt->fetch()['total'];
}

public function getPaginated($limit, $offset, $search = '')
{
    $userId = $_SESSION['user_id'] ?? null;
    $role = $_SESSION['user_role'] ?? '';

    $sql = "
    SELECT 
        q.*,
        ml.lead_number,
        ml.company_name AS lead_company_name,
        ml.pic_name AS lead_pic_name
    FROM quotations q
    LEFT JOIN marketing_leads ml ON ml.id = q.lead_id
    WHERE 1=1
    ";

        /*
        |--------------------------------------------------------------------------
        | Restrict only sales
        |--------------------------------------------------------------------------
        */

        if ($role === 'sales') {
            $sql .= " AND q.created_by = :user_id";
        }

        if (!empty($search)) {
            $sql .= "
                AND (
                    q.no_quotation LIKE :search
                    OR q.customer_name LIKE :search
                    OR q.customer_phone LIKE :search
                    OR q.status LIKE :search
                    OR ml.lead_number LIKE :search
                    OR ml.company_name LIKE :search
                    OR ml.pic_name LIKE :search
                )
            ";
        }

        $sql .= " ORDER BY id DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);

        if ($role === 'sales') {
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        }

        if (!empty($search)) {
            $stmt->bindValue(':search', '%' . $search . '%');
        }

        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll();
    }


    public function generateNumber()
    {
        return 'Q' . date('mdHis') . rand(10,99);
    }


    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO quotations
            (
                no_quotation,
                customer_id,
                lead_id,
                customer_name,
                customer_phone,
                lokasi,
                tanggal_mulai,
                tanggal_selesai,
                catatan,
                created_by,
                status
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'waiting approval')
            ");

        $stmt->execute([
            $data['no_quotation'],
            $data['customer_id'],
            $data['lead_id'] ?? null,
            $data['customer_name'],
            $data['customer_phone'],
            $data['lokasi'],
            $data['tanggal_mulai'],
            $data['tanggal_selesai'],
            $data['catatan'],
            $data['created_by']
        ]);

        return $this->db->lastInsertId();
    }

    public function addItem($quotationId, $item)
    {
        $stmt = $this->db->prepare("
            INSERT INTO quotation_items
            (
                quotation_id,
                product_id,
                item_name,
                category,
                item_type,
                billing_type,
                qty,
                rental_period_type,
                duration,
                unit_price,
                discount,
                subtotal,
                notes
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $quotationId,
            $item['product_id'] ?? null,
            $item['item_name'],
            $item['category'],
            $item['item_type'] ?? 'rental_unit',
            $item['billing_type'] ?? 'daily',
            $item['qty'],
            $item['rental_period_type'] ?? ($item['billing_type'] ?? 'daily'),
            $item['duration'],
            $item['unit_price'],
            $item['discount'],
            $item['subtotal'],
            $item['notes']
        ]);
    }

    public function find($id)
    {
        $userId = $_SESSION['user_id'] ?? null;
        $role = $_SESSION['user_role'] ?? '';

        $sql = "
        SELECT 
            q.*,
            ml.lead_number,
            ml.company_name AS lead_company_name,
            ml.pic_name AS lead_pic_name
        FROM quotations q
        LEFT JOIN marketing_leads ml ON ml.id = q.lead_id
        WHERE q.id = ? ";

        $params = [$id];

        /*
        |--------------------------------------------------------------------------
        | Restrict only sales
        |--------------------------------------------------------------------------
        */

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
        $stmt = $this->db->prepare("
            UPDATE quotations SET
                customer_id = ?,
                lead_id = ?,
                customer_name = ?,
                customer_phone = ?,
                lokasi = ?,
                tanggal_mulai = ?,
                tanggal_selesai = ?,
                catatan = ?
            WHERE id = ?
            ");

        return $stmt->execute([
            $data['customer_id'],
            $data['lead_id'] ?? null,
            $data['customer_name'],
            $data['customer_phone'],
            $data['lokasi'],
            $data['tanggal_mulai'],
            $data['tanggal_selesai'],
            $data['catatan'],
            $id
        ]);
    }

    public function getItems($quotationId)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM quotation_items
            WHERE quotation_id = ?
            ORDER BY id ASC
            ");

        $stmt->execute([$quotationId]);
        return $stmt->fetchAll();
    }

    public function deleteItems($quotationId)
    {
        $stmt = $this->db->prepare("
            DELETE FROM quotation_items
            WHERE quotation_id = ?
            ");

        return $stmt->execute([$quotationId]);
    }

    public function delete($id)
    {
        $userId = $_SESSION['user_id'] ?? null;
        $role = $_SESSION['user_role'] ?? '';

        if ($role === 'sales') {
            $stmt = $this->db->prepare("
                DELETE FROM quotations
                WHERE id = ?
                AND created_by = ?
                ");

            return $stmt->execute([$id, $userId]);
        }

        $stmt = $this->db->prepare("
            DELETE FROM quotations
            WHERE id = ?
            ");

        return $stmt->execute([$id]);
    }

    public function markAsConverted($id)
    {
        $stmt = $this->db->prepare("
            UPDATE quotations
            SET status = 'order'
            WHERE id = ?
            ");

        return $stmt->execute([$id]);
    }

    public function markAsConvertedInvoice($id)
    {
        $stmt = $this->db->prepare("
            UPDATE quotations
            SET status = 'approved'
            WHERE id = ?
            ");

        return $stmt->execute([$id]);
    }

    public function countThisMonth()
    {
        $stmt = $this->db->query("
            SELECT COUNT(*) 
            FROM quotations
            WHERE MONTH(created_at) = MONTH(CURDATE())
            AND YEAR(created_at) = YEAR(CURDATE())
            ");

        return (int) $stmt->fetchColumn();
    }

    public function countByStatus($status)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*)
            FROM quotations
            WHERE status = ?
            ");

        $stmt->execute([$status]);

        return (int) $stmt->fetchColumn();
    }

    public function latest($limit = 10)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM quotations 
            ORDER BY id DESC
            LIMIT {$limit}
            ");

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function followUpToday($limit = 10)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM quotations
            WHERE status IN ('waiting approval')
            ORDER BY created_at DESC
            LIMIT {$limit}
            ");

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function recentActivities($limit = 6)
    {
        $limit = (int) $limit;

        $stmt = $this->db->query("
            SELECT
            id,
            no_quotation,
            customer_name,
            lokasi,
            status,
            created_at
            FROM quotations
            ORDER BY created_at DESC
            LIMIT {$limit}
            ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function totalQuotationThisMonth()
    {
        $stmt = $this->db->query("
            SELECT 
            COALESCE(SUM(qi.subtotal), 0) AS total
            FROM quotations q
            LEFT JOIN quotation_items qi 
            ON qi.quotation_id = q.id
            WHERE MONTH(q.created_at) = MONTH(CURDATE())
            AND YEAR(q.created_at) = YEAR(CURDATE())
            ");

        return (float) $stmt->fetchColumn();
    }

    public function totalDealQuotationThisMonth()
    {
        $stmt = $this->db->query("
            SELECT 
            COALESCE(SUM(qi.subtotal), 0) AS total
            FROM quotations q
            LEFT JOIN quotation_items qi 
            ON qi.quotation_id = q.id
            WHERE MONTH(q.created_at) = MONTH(CURDATE())
            AND YEAR(q.created_at) = YEAR(CURDATE())
            AND q.status = 'approved'
            ");

        return (float) $stmt->fetchColumn();
    }

    public function totalDealThisMonth()
    {
        $stmt = $this->db->query("
            SELECT 
            COALESCE(SUM(qi.subtotal), 0) AS total

            FROM quotations q

            LEFT JOIN quotation_items qi
            ON qi.quotation_id = q.id

            WHERE MONTH(q.created_at) = MONTH(CURDATE())
            AND YEAR(q.created_at) = YEAR(CURDATE())

            AND q.status IN (
                'approved',
                'order'
                )
            ");

        return (float) $stmt->fetchColumn();
    }

    public function topLocations($limit = 6)
    {
        $limit = (int) $limit;

        $stmt = $this->db->query("
            SELECT
            lokasi,
            COUNT(*) AS total_quotation
            FROM quotations
            WHERE lokasi IS NOT NULL
            AND lokasi != ''
            GROUP BY lokasi
            ORDER BY total_quotation DESC
            LIMIT {$limit}
            ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function monthlyQuotationChart($year = null)
    {
        $year = $year ?: date('Y');

        $stmt = $this->db->prepare("
            SELECT
            MONTH(created_at) AS month_number,

            COUNT(*) AS total_quotation,

            SUM(
                CASE 
                WHEN status IN ('waiting approval', 'approved', 'order') 
                THEN 1 
                ELSE 0 
                END
                ) AS total_deal

            FROM quotations

            WHERE YEAR(created_at) = ?

            GROUP BY MONTH(created_at)

            ORDER BY MONTH(created_at) ASC
            ");

        $stmt->execute([$year]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $months = [];
        $quotations = [];
        $deals = [];

        $monthNames = [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'May',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Aug',
            9 => 'Sep',
            10 => 'Oct',
            11 => 'Nov',
            12 => 'Dec'
        ];

        foreach ($rows as $row) {
            $monthNumber = (int) $row['month_number'];

            $months[] = $monthNames[$monthNumber] ?? '-';
            $quotations[] = (int) $row['total_quotation'];
            $deals[] = (int) $row['total_deal'];
        }

        return [
            'months' => $months,
            'quotations' => $quotations,
            'deals' => $deals,
        ];
    }

    public function dailyQuotationChart($days = 7)
    {
        $days = (int) $days;

        $stmt = $this->db->prepare("
            SELECT
            DATE(created_at) AS quotation_date,
            COUNT(*) AS total_quotation
            FROM quotations
            WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL {$days} DAY)
            GROUP BY DATE(created_at)
            ORDER BY DATE(created_at) ASC
            ");

        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $labels = [];
        $data = [];

        foreach ($rows as $row) {
            $labels[] = date('d M', strtotime($row['quotation_date']));
            $data[] = (int) $row['total_quotation'];
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    public function topQuotationsThisMonth($limit = 6)
    {
        $limit = (int) $limit;

        $stmt = $this->db->query("
            SELECT
            q.id,
            q.no_quotation,
            q.customer_name,
            q.lokasi,
            q.status,
            q.created_at,
            COALESCE(SUM(qi.subtotal), 0) AS total_value
            FROM quotations q
            LEFT JOIN quotation_items qi
            ON qi.quotation_id = q.id
            WHERE MONTH(q.created_at) = MONTH(CURDATE())
            AND YEAR(q.created_at) = YEAR(CURDATE())
            GROUP BY
            q.id,
            q.no_quotation,
            q.customer_name,
            q.lokasi,
            q.status,
            q.created_at
            ORDER BY total_value DESC
            LIMIT {$limit}
            ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function updateCustomer($quotationId, $customerId)
    {
        $stmt = $this->db->prepare("
            UPDATE quotations
            SET customer_id = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $customerId,
            $quotationId
        ]);
    }

}