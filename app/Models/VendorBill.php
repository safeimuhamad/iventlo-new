<?php

class VendorBill
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function generateNumber()
    {
        return 'VB-' . date('Ymd-His');
    }

    public function getAll()
    {
        $stmt = $this->db->query("
            SELECT 
                vb.*,
                v.vendor_name,
                po.po_number
            FROM vendor_bills vb
            LEFT JOIN vendors v ON v.id = vb.vendor_id
            LEFT JOIN purchase_orders po ON po.id = vb.purchase_order_id
            ORDER BY vb.id DESC
        ");

        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT 
                vb.*,
                v.vendor_name,
                v.vendor_code,
                v.phone AS vendor_phone,
                v.email AS vendor_email,
                v.address AS vendor_address,
                v.pic_name AS vendor_pic,
                po.po_number
            FROM vendor_bills vb
            LEFT JOIN vendors v ON v.id = vb.vendor_id
            LEFT JOIN purchase_orders po ON po.id = vb.purchase_order_id
            WHERE vb.id = ?
            LIMIT 1
        ");

        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function hasVendorBill($purchaseOrderId)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) AS total
            FROM vendor_bills
            WHERE purchase_order_id = ?
        ");

        $stmt->execute([$purchaseOrderId]);

        $row = $stmt->fetch();

        return (int) ($row['total'] ?? 0) > 0;
    }

    public function getItems($billId)
    {
        $stmt = $this->db->prepare("
            SELECT 
            vbi.*,
            coa.account_code,
            coa.account_name
            FROM vendor_bill_items vbi
            LEFT JOIN chart_of_accounts coa ON coa.id = vbi.account_id
            WHERE vbi.vendor_bill_id = ?
            ORDER BY vbi.id ASC
            ");

        $stmt->execute([$billId]);

        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO vendor_bills
            (
                bill_no,
                purchase_order_id,
                vendor_id,
                bill_date,
                due_date,
                subtotal,
                tax_amount,
                grand_total,
                paid_amount,
                status_payment,
                notes
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $data['bill_no'],
            $data['purchase_order_id'] ?? null,
            $data['vendor_id'],
            $data['bill_date'],
            $data['due_date'] ?? null,
            $data['subtotal'] ?? 0,
            $data['tax_amount'] ?? 0,
            $data['grand_total'] ?? 0,
            $data['paid_amount'] ?? 0,
            $data['status_payment'] ?? 'unpaid',
            $data['notes'] ?? ''
        ]);

        return $this->db->lastInsertId();
    }

    public function findByPurchaseOrder($purchaseOrderId)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM vendor_bills
            WHERE purchase_order_id = ?
            LIMIT 1
        ");

        $stmt->execute([$purchaseOrderId]);

        return $stmt->fetch();
    }

    public function addItem($billId, $item)
    {
        $stmt = $this->db->prepare("
            INSERT INTO vendor_bill_items
            (
                vendor_bill_id,
                account_id,
                description,
                amount
                )
            VALUES (?, ?, ?, ?)
            ");

        return $stmt->execute([
            $billId,
            $item['account_id'],
            $item['description'],
            $item['amount']
        ]);
    }

    public function updatePaymentStatus($id, $paidAmount, $grandTotal)
    {
        $remainingAmount = max(0, $grandTotal - $paidAmount);

        if ($paidAmount <= 0) {
            $status = 'unpaid';
        } elseif ($paidAmount < $grandTotal) {
            $status = 'partial paid';
        } else {
            $status = 'paid';
        }

        $stmt = $this->db->prepare("
            UPDATE vendor_bills SET
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

    public function getAgingPayables()
    {
        $stmt = $this->db->query("
            SELECT 
            vb.*,
            v.vendor_code,
            v.vendor_name
            FROM vendor_bills vb
            LEFT JOIN vendors v ON v.id = vb.vendor_id
            WHERE vb.remaining_amount > 0
            AND vb.status_payment IN ('unpaid', 'partial paid')
            ORDER BY vb.due_date ASC, vb.id ASC
            ");

        return $stmt->fetchAll();
    }

    public function deleteItems($billId)
    {
        $stmt = $this->db->prepare("
            DELETE FROM vendor_bill_items
            WHERE vendor_bill_id = ?
            ");

        return $stmt->execute([$billId]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("
            DELETE FROM vendor_bills
            WHERE id = ?
            ");

        return $stmt->execute([$id]);
    }

    public function getOutstandingPayable()
    {
        $stmt = $this->db->query("
            SELECT COALESCE(SUM(remaining_amount), 0)
            FROM vendor_bills
            WHERE remaining_amount > 0
            ");

        return (float) $stmt->fetchColumn();
    }

    public function monthlyPayableChart($year = null)
    {
        $year = $year ?: date('Y');

        $stmt = $this->db->prepare("
            SELECT
                MONTH(bill_date) AS month_number,
                COALESCE(SUM(remaining_amount), 0) AS total
            FROM vendor_bills
            WHERE YEAR(bill_date) = ?
            AND remaining_amount > 0
            GROUP BY MONTH(bill_date)
        ");

        $stmt->execute([$year]);

        $data = array_fill(1, 12, 0);

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $data[(int) $row['month_number']] = (float) $row['total'];
        }

        return array_values($data);
    }

    public function countAll($search = '', $status = '')
    {
        $keyword = '%' . $search . '%';

        $sql = "
            SELECT COUNT(*) AS total
            FROM vendor_bills vb
            LEFT JOIN vendors v ON v.id = vb.vendor_id
            LEFT JOIN purchase_orders po ON po.id = vb.purchase_order_id
            WHERE (
                vb.bill_no LIKE ?
                OR v.vendor_name LIKE ?
                OR po.po_number LIKE ?
                OR vb.status_payment LIKE ?
            )
        ";

        $params = [$keyword, $keyword, $keyword, $keyword];

        if ($status !== '') {
            $sql .= " AND vb.status_payment = ?";
            $params[] = $status;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $row = $stmt->fetch();

        return (int) ($row['total'] ?? 0);
    }

    public function getPaginated($limit = 10, $offset = 0, $search = '', $status = '')
    {
        $keyword = '%' . $search . '%';

        $sql = "
            SELECT 
                vb.*,
                v.vendor_name,
                po.po_number
            FROM vendor_bills vb
            LEFT JOIN vendors v ON v.id = vb.vendor_id
            LEFT JOIN purchase_orders po ON po.id = vb.purchase_order_id
            WHERE (
                vb.bill_no LIKE ?
                OR v.vendor_name LIKE ?
                OR po.po_number LIKE ?
                OR vb.status_payment LIKE ?
            )
        ";

        $params = [$keyword, $keyword, $keyword, $keyword];

        if ($status !== '') {
            $sql .= " AND vb.status_payment = ?";
            $params[] = $status;
        }

        $sql .= "
            ORDER BY vb.id DESC
            LIMIT {$limit} OFFSET {$offset}
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

}