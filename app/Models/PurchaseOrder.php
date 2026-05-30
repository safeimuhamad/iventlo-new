<?php

class PurchaseOrder
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function generateNumber()
    {
        return 'PO-' . date('Ymd-His');
    }

    public function countAll($search = '', $status = '')
    {
        $keyword = '%' . $search . '%';

        $sql = "
            SELECT COUNT(*) AS total
            FROM purchase_orders po
            LEFT JOIN vendors v ON v.id = po.vendor_id
            LEFT JOIN purchase_requests pr ON pr.id = po.purchase_request_id
            WHERE (
                po.po_number LIKE ?
                OR v.vendor_name LIKE ?
                OR po.status LIKE ?
                OR pr.pr_number LIKE ?
            )
        ";

        $params = [$keyword, $keyword, $keyword, $keyword];

        if ($status !== '') {
            $sql .= " AND po.status = ?";
            $params[] = $status;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $row = $stmt->fetch();

        return (int) ($row['total'] ?? 0);
    }

    public function decreaseReceivedQty($itemId, $qty)
    {
        $stmt = $this->db->prepare("
            UPDATE purchase_order_items
            SET received_qty = GREATEST(received_qty - ?, 0)
            WHERE id = ?
        ");

        return $stmt->execute([
            $qty,
            $itemId
        ]);
    }

    public function getPaginated($limit = 10, $offset = 0, $search = '', $status = '')
    {
        $keyword = '%' . $search . '%';

        $sql = "
            SELECT
                po.*,
                v.vendor_name,
                pr.pr_number
            FROM purchase_orders po
            LEFT JOIN vendors v ON v.id = po.vendor_id
            LEFT JOIN purchase_requests pr ON pr.id = po.purchase_request_id
            WHERE (
                po.po_number LIKE ?
                OR v.vendor_name LIKE ?
                OR po.status LIKE ?
                OR pr.pr_number LIKE ?
            )
        ";

        $params = [$keyword, $keyword, $keyword, $keyword];

        if ($status !== '') {
            $sql .= " AND po.status = ?";
            $params[] = $status;
        }

        $sql .= "
            ORDER BY po.id DESC
            LIMIT {$limit} OFFSET {$offset}
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT
                po.*,
                v.vendor_name,
                v.vendor_code,
                v.phone AS vendor_phone,
                v.email AS vendor_email,
                v.address AS vendor_address,
                v.pic_name AS vendor_pic,
                pr.pr_number
            FROM purchase_orders po
            LEFT JOIN vendors v ON v.id = po.vendor_id
            LEFT JOIN purchase_requests pr ON pr.id = po.purchase_request_id
            WHERE po.id = ?
            LIMIT 1
        ");

        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO purchase_orders
            (
                po_number,
                purchase_request_id,
                vendor_id,
                po_date,
                expected_date,
                subtotal,
                tax_amount,
                grand_total,
                status,
                notes,
                created_by
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $data['po_number'],
            $data['purchase_request_id'] ?? null,
            $data['vendor_id'],
            $data['po_date'],
            $data['expected_date'] ?? null,
            $data['subtotal'] ?? 0,
            $data['tax_amount'] ?? 0,
            $data['grand_total'] ?? 0,
            $data['status'] ?? 'draft',
            $data['notes'] ?? '',
            $data['created_by'] ?? null
        ]);

        return $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE purchase_orders SET
                vendor_id = ?,
                po_date = ?,
                expected_date = ?,
                subtotal = ?,
                tax_amount = ?,
                grand_total = ?,
                status = ?,
                notes = ?,
                updated_at = NOW()
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['vendor_id'],
            $data['po_date'],
            $data['expected_date'] ?? null,
            $data['subtotal'] ?? 0,
            $data['tax_amount'] ?? 0,
            $data['grand_total'] ?? 0,
            $data['status'] ?? 'draft',
            $data['notes'] ?? '',
            $id
        ]);
    }

    public function delete($id)
    {
        $this->deleteItems($id);

        $stmt = $this->db->prepare("
            DELETE FROM purchase_orders
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }

    public function addItem($purchaseOrderId, $data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO purchase_order_items
            (
                purchase_order_id,
                item_name,
                description,
                qty,
                unit_name,
                unit_price,
                subtotal,
                received_qty
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $purchaseOrderId,
            $data['item_name'],
            $data['description'] ?? '',
            $data['qty'] ?? 0,
            $data['unit_name'] ?? 'unit',
            $data['unit_price'] ?? 0,
            $data['subtotal'] ?? 0,
            $data['received_qty'] ?? 0
        ]);
    }

    public function getItems($purchaseOrderId)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM purchase_order_items
            WHERE purchase_order_id = ?
            ORDER BY id ASC
        ");

        $stmt->execute([$purchaseOrderId]);

        return $stmt->fetchAll();
    }

    public function deleteItems($purchaseOrderId)
    {
        $stmt = $this->db->prepare("
            DELETE FROM purchase_order_items
            WHERE purchase_order_id = ?
        ");

        return $stmt->execute([$purchaseOrderId]);
    }

    public function approve($id)
    {
        $stmt = $this->db->prepare("
            UPDATE purchase_orders
            SET status = 'approved',
                updated_at = NOW()
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }

    public function markAsSent($id)
    {
        $stmt = $this->db->prepare("
            UPDATE purchase_orders
            SET status = 'sent',
                updated_at = NOW()
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }

    public function updateReceivedQty($itemId, $receivedQty)
    {
        $stmt = $this->db->prepare("
            UPDATE purchase_order_items
            SET received_qty = received_qty + ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $receivedQty,
            $itemId
        ]);
    }

    public function updateStatusByReceive($purchaseOrderId)
    {
        $stmt = $this->db->prepare("
            SELECT
                SUM(qty) AS total_qty,
                SUM(received_qty) AS total_received
            FROM purchase_order_items
            WHERE purchase_order_id = ?
        ");

        $stmt->execute([$purchaseOrderId]);

        $row = $stmt->fetch();

        $totalQty = (float) ($row['total_qty'] ?? 0);
        $totalReceived = (float) ($row['total_received'] ?? 0);

        if ($totalReceived <= 0) {
            $status = 'sent';
        } elseif ($totalReceived < $totalQty) {
            $status = 'partial_received';
        } else {
            $status = 'completed';
        }

        $update = $this->db->prepare("
            UPDATE purchase_orders
            SET status = ?,
                updated_at = NOW()
            WHERE id = ?
        ");

        return $update->execute([
            $status,
            $purchaseOrderId
        ]);
    }
}