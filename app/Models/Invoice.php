<?php



class Invoice
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
        FROM invoices
        WHERE 1=1
        ";

        if ($role === 'sales') {
            $sql .= " AND created_by = :user_id";
        }

        if (!empty($search)) {
            $sql .= "
            AND (
            no_invoice LIKE :search
            OR customer_name LIKE :search
            OR customer_phone LIKE :search
            OR invoice_type LIKE :search
            OR status_payment LIKE :search
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

        $row = $stmt->fetch();

        return (int)$row['total'];
    }

    public function getPaginated($limit, $offset, $search = '')
    {
        $userId = $_SESSION['user_id'] ?? null;
        $role = $_SESSION['user_role'] ?? '';

        $sql = "
        SELECT *
        FROM invoices
        WHERE 1=1
        ";

    /*
    |--------------------------------------------------------------------------
    | Restrict only sales
    |--------------------------------------------------------------------------
    */

    if ($role === 'sales') {
        $sql .= " AND created_by = :user_id";
    }

    if (!empty($search)) {
        $sql .= "
        AND (
        no_invoice LIKE :search
        OR customer_name LIKE :search
        OR customer_phone LIKE :search
        OR invoice_type LIKE :search
        OR status_payment LIKE :search
        )
        ";
    }

    $sql .= "
    ORDER BY id DESC
    LIMIT :limit OFFSET :offset
    ";

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
 return 'INV-' . date('mdHis') . rand(10,99);
}

public function create($data)
{
    $remainingAmount = max(0, $data['grand_total'] - $data['paid_amount']);

    $stmt = $this->db->prepare("
        INSERT INTO invoices
        (
            quotation_id,
            customer_id,
            rental_id,
            no_invoice,
            invoice_type,
            dp_type,
            dp_percentage,
            dp_nominal,
            customer_name,
            customer_phone,
            lokasi,
            invoice_date,
            due_date,
            subtotal,
            total_discount,
            tax_type,
            tax_percent,
            tax_amount,
            billing_total,
            grand_total,
            paid_amount,
            remaining_amount,
            remaining_bill,
            status_payment,
            payment_account,
            notes,
            created_by
            )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

    $stmt->execute([
        $data['quotation_id'] ?? null,
        $data['customer_id'] ?? null,
        $data['rental_id'] ?? null,
        $data['no_invoice'],
        $data['invoice_type'],
        $data['dp_type'],
        $data['dp_percentage'],
        $data['dp_nominal'],
        $data['customer_name'],
        $data['customer_phone'],
        $data['lokasi'],
        $data['invoice_date'],
        $data['due_date'],
        $data['subtotal'],
        $data['total_discount'],
        $data['tax_type'],
        $data['tax_percent'],
        $data['tax_amount'],
        $data['billing_total'],
        $data['grand_total'],
        $data['paid_amount'],
        $remainingAmount,
        $data['remaining_bill'],
        $data['status_payment'],
        $data['payment_account'],
        $data['notes'],
        $data['created_by'] ?? ($_SESSION['user_id'] ?? null)
    ]);

    return $this->db->lastInsertId();
}

public function addItem($invoiceId, $item)
{
    $stmt = $this->db->prepare("
        INSERT INTO invoice_items
        (
            invoice_id,
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
            subtotal
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    return $stmt->execute([
        $invoiceId,
        $item['product_id'] ?? null,
        $item['item_name'],
        $item['category'] ?? '',
        $item['item_type'] ?? 'rental_unit',
        $item['billing_type'] ?? 'daily',
        $item['qty'],
        $item['rental_period_type'] ?? 'daily',
        $item['duration'],
        $item['unit_price'],
        $item['discount'],
        $item['subtotal']
    ]);
}

public function find($id)
{
    $userId = $_SESSION['user_id'] ?? null;
    $role = $_SESSION['user_role'] ?? '';

    $sql = "
    SELECT *
    FROM invoices
    WHERE id = ?
    ";

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

    public function getItems($invoiceId)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM invoice_items
            WHERE invoice_id = ?
            ORDER BY id ASC
            ");

        $stmt->execute([$invoiceId]);
        return $stmt->fetchAll();
    }

    public function update($id, $data)
    {
        $remainingAmount = max(0, $data['grand_total'] - $data['paid_amount']);

        $stmt = $this->db->prepare("
            UPDATE invoices SET
            invoice_type = ?,
            dp_type = ?,
            dp_percentage = ?,
            dp_nominal = ?,
            tax_type = ?,
            tax_percent = ?,
            tax_amount = ?,
            payment_account = ?,
            customer_id = ?,
            customer_name = ?,
            customer_phone = ?,
            lokasi = ?,
            invoice_date = ?,
            due_date = ?,
            subtotal = ?,
            total_discount = ?,
            billing_total = ?,
            grand_total = ?,
            paid_amount = ?,
            remaining_amount = ?,
            remaining_bill = ?,
            status_payment = ?,
            notes = ?
            WHERE id = ?
            ");

        return $stmt->execute([
            $data['invoice_type'],
            $data['dp_type'],
            $data['dp_percentage'],
            $data['dp_nominal'],
            $data['tax_type'],
            $data['tax_percent'],
            $data['tax_amount'],
            $data['payment_account'],
            $data['customer_id'],
            $data['customer_name'],
            $data['customer_phone'],
            $data['lokasi'],
            $data['invoice_date'],
            $data['due_date'],
            $data['subtotal'],
            $data['total_discount'],
            $data['billing_total'],
            $data['grand_total'],
            $data['paid_amount'],
            $remainingAmount,
            $data['remaining_bill'],
            $data['status_payment'],
            $data['notes'],
            $id
        ]);
    }

    public function deleteItems($invoiceId)
    {
        $stmt = $this->db->prepare("
            DELETE FROM invoice_items
            WHERE invoice_id = ?
            ");

        return $stmt->execute([$invoiceId]);
    }


    public function delete($id)
    {
        $userId = $_SESSION['user_id'] ?? null;
        $role = $_SESSION['user_role'] ?? '';

        /*
        |--------------------------------------------------------------------------
        | Restrict only sales
        |--------------------------------------------------------------------------
        */

        if ($role === 'sales') {

            $stmt = $this->db->prepare("
                DELETE FROM invoices
                WHERE id = ?
                AND created_by = ?
                ");

            return $stmt->execute([$id, $userId]);
        }

        /*
        |--------------------------------------------------------------------------
        | Admin / finance / operational
        |--------------------------------------------------------------------------
        */

        $stmt = $this->db->prepare("
            DELETE FROM invoices
            WHERE id = ?
            ");

        return $stmt->execute([$id]);
    }

    public function deleteWithItems($id)
    {
        $ownsTransaction = !$this->db->inTransaction();

        if ($ownsTransaction) {
            $this->db->beginTransaction();
        }

        try {
            $this->deleteItems($id);
            $this->delete($id);

            if ($ownsTransaction) {
                $this->db->commit();
            }

            return true;
        } catch (Throwable $e) {
            if ($ownsTransaction && $this->db->inTransaction()) {
                $this->db->rollBack();
            }

            throw $e;
        }
    }

    public function updatePaymentStatus($id, $paidAmount, $grandTotal)
    {
        $remainingAmount = max(0, $grandTotal - $paidAmount);

        if ($paidAmount <= 0) {
            $status = 'waiting payment';
        } elseif ($paidAmount < $grandTotal) {
            $status = 'partial paid';
        } else {
            $status = 'paid';
        }

        $stmt = $this->db->prepare("
            UPDATE invoices SET
            paid_amount = ?,
            remaining_amount = ?,
            status_payment = ?
            WHERE id = ?
            ");

        return $stmt->execute([
            $paidAmount,
            $remainingAmount,
            $status,
            $id
        ]);
    }

    public function getOutstandingReceivable()
    {
        $stmt = $this->db->query("
            SELECT COALESCE(SUM(remaining_amount), 0) AS outstanding
            FROM invoices
            WHERE status_payment IN ('waiting payment', 'partial paid', 'overdue')
            ");

        $row = $stmt->fetch();

        return (float)($row['outstanding'] ?? 0);
    }

    public function getAgingReceivables()
    {
        $stmt = $this->db->query("
            SELECT *
            FROM invoices
            WHERE remaining_amount > 0
            AND status_payment IN ('waiting payment', 'partial paid', 'overdue')
            ORDER BY due_date ASC, id ASC
            ");

        return $stmt->fetchAll();
    }

    public function paidRevenueThisMonth()
    {
        $stmt = $this->db->query("
            SELECT 
            COALESCE(SUM(payment_amount), 0)

            FROM invoice_payments

            WHERE MONTH(payment_date) = MONTH(CURDATE())
            AND YEAR(payment_date) = YEAR(CURDATE())
            ");

        return (float) $stmt->fetchColumn();
    }

    public function overdueInvoices($limit = 8)
    {
        $stmt = $this->db->prepare("
            SELECT
            id,
            no_invoice,
            customer_name,
            due_date,
            grand_total,
            paid_amount,
            remaining_amount,
            DATEDIFF(CURDATE(), due_date) AS overdue_days
            FROM invoices
            WHERE remaining_amount > 0
            AND due_date < CURDATE()
            ORDER BY overdue_days DESC
            LIMIT ?
            ");

        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function monthlyReceivableChart($year = null)
    {
        $year = $year ?: date('Y');

        $stmt = $this->db->prepare("
            SELECT
                MONTH(invoice_date) AS month_number,
                COALESCE(SUM(remaining_amount), 0) AS total
            FROM invoices
            WHERE YEAR(invoice_date) = ?
            AND remaining_amount > 0
            GROUP BY MONTH(invoice_date)
        ");

        $stmt->execute([$year]);

        $data = array_fill(1, 12, 0);

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $data[(int) $row['month_number']] = (float) $row['total'];
        }

        return array_values($data);
    }


}
