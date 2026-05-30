<?php

class GoodsReceipt
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function generateNumber()
    {
        return 'GR-' . date('Ymd-His');
    }

    public function countAll($search = '', $status = '')
    {
        $keyword = '%' . $search . '%';

        $sql = "
            SELECT COUNT(*) AS total
            FROM goods_receipts gr
            LEFT JOIN purchase_orders po ON po.id = gr.purchase_order_id
            LEFT JOIN vendors v ON v.id = po.vendor_id
            WHERE (
                gr.receipt_number LIKE ?
                OR po.po_number LIKE ?
                OR v.vendor_name LIKE ?
                OR gr.status LIKE ?
            )
        ";

        $params = [$keyword, $keyword, $keyword, $keyword];

        if ($status !== '') {
            $sql .= " AND gr.status = ?";
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
                gr.*,
                po.po_number,
                v.vendor_name
            FROM goods_receipts gr
            LEFT JOIN purchase_orders po ON po.id = gr.purchase_order_id
            LEFT JOIN vendors v ON v.id = po.vendor_id
            WHERE (
                gr.receipt_number LIKE ?
                OR po.po_number LIKE ?
                OR v.vendor_name LIKE ?
                OR gr.status LIKE ?
            )
        ";

        $params = [$keyword, $keyword, $keyword, $keyword];

        if ($status !== '') {
            $sql .= " AND gr.status = ?";
            $params[] = $status;
        }

        $sql .= "
            ORDER BY gr.id DESC
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
                gr.*,
                po.po_number,
                po.po_date,
                po.expected_date,
                po.status AS po_status,
                v.vendor_name,
                v.vendor_code,
                v.phone AS vendor_phone,
                v.email AS vendor_email,
                v.address AS vendor_address,
                v.pic_name AS vendor_pic
            FROM goods_receipts gr
            LEFT JOIN purchase_orders po ON po.id = gr.purchase_order_id
            LEFT JOIN vendors v ON v.id = po.vendor_id
            WHERE gr.id = ?
            LIMIT 1
        ");

        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO goods_receipts
            (
                receipt_number,
                purchase_order_id,
                receipt_date,
                status,
                notes,
                created_by
            )
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $data['receipt_number'],
            $data['purchase_order_id'],
            $data['receipt_date'],
            $data['status'] ?? 'received',
            $data['notes'] ?? '',
            $data['created_by'] ?? null
        ]);

        return $this->db->lastInsertId();
    }

    public function addItem($goodsReceiptId, $data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO goods_receipt_items
            (
                goods_receipt_id,
                purchase_order_item_id,
                item_name,
                qty_ordered,
                qty_received,
                unit_name,
                notes
            )
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $goodsReceiptId,
            $data['purchase_order_item_id'],
            $data['item_name'],
            $data['qty_ordered'] ?? 0,
            $data['qty_received'] ?? 0,
            $data['unit_name'] ?? 'unit',
            $data['notes'] ?? ''
        ]);
    }

    public function getItems($goodsReceiptId)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM goods_receipt_items
            WHERE goods_receipt_id = ?
            ORDER BY id ASC
        ");

        $stmt->execute([$goodsReceiptId]);

        return $stmt->fetchAll();
    }

    public function deleteItems($goodsReceiptId)
    {
        $stmt = $this->db->prepare("
            DELETE FROM goods_receipt_items
            WHERE goods_receipt_id = ?
        ");

        return $stmt->execute([$goodsReceiptId]);
    }

    public function delete($id)
    {
        $this->deleteItems($id);

        $stmt = $this->db->prepare("
            DELETE FROM goods_receipts
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }

    public function getReceivablePurchaseOrders()
    {
        $stmt = $this->db->prepare("
            SELECT
                po.*,
                v.vendor_name
            FROM purchase_orders po
            LEFT JOIN vendors v ON v.id = po.vendor_id
            WHERE po.status IN ('approved', 'sent', 'partial_received')
            ORDER BY po.id DESC
        ");

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getPurchaseOrderItems($purchaseOrderId)
    {
        $stmt = $this->db->prepare("
            SELECT
                poi.*,
                (poi.qty - poi.received_qty) AS remaining_qty
            FROM purchase_order_items poi
            WHERE poi.purchase_order_id = ?
            AND (poi.qty - poi.received_qty) > 0
            ORDER BY poi.id ASC
        ");

        $stmt->execute([$purchaseOrderId]);

        return $stmt->fetchAll();
    }
}