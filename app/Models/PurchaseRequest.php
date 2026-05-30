<?php

class PurchaseRequest
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function generateNumber()
    {
        return 'PR-' . date('Ymd-His');
    }


    public function getGrandTotal($purchaseRequestId)
    {
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(subtotal), 0) AS grand_total
            FROM purchase_request_items
            WHERE purchase_request_id = ?
        ");

        $stmt->execute([$purchaseRequestId]);
        $row = $stmt->fetch();

        return (float) ($row['grand_total'] ?? 0);
    }

    public function updateApprovalStatus($id, $status)
    {
        $extraField = '';

        if ($status === 'waiting_approval') {

            $extraField = "
                submitted_at = NOW(),
                approved_by = NULL,
                approved_at = NULL,
                rejected_by = NULL,
                rejected_at = NULL,
                rejected_reason = NULL,
            ";

        } elseif ($status === 'approved') {

            $extraField = "
                approved_by = " . (int) ($_SESSION['user_id'] ?? 0) . ",
                approved_at = NOW(),
                rejected_by = NULL,
                rejected_at = NULL,
                rejected_reason = NULL,
            ";

        } elseif ($status === 'rejected') {

            $extraField = "
                rejected_by = " . (int) ($_SESSION['user_id'] ?? 0) . ",
                rejected_at = NOW(),
            ";
        }

        $sql = "
            UPDATE purchase_requests SET
                status = ?,
                {$extraField}
                updated_at = NOW()
            WHERE id = ?
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $status,
            $id
        ]);
    }

    public function countAll($search = '', $status = '')
    {
        $keyword = '%' . $search . '%';

        $sql = "
            SELECT COUNT(*) AS total
            FROM purchase_requests pr
            LEFT JOIN users u ON u.id = pr.requested_by
            LEFT JOIN departments d ON d.id = pr.department_id
            WHERE (
                pr.pr_number LIKE ?
                OR pr.purpose LIKE ?
                OR pr.status LIKE ?
                OR u.name LIKE ?
                OR d.name LIKE ?
            )
        ";

        $params = [
            $keyword,
            $keyword,
            $keyword,
            $keyword,
            $keyword
        ];

        if ($status !== '') {
            $sql .= " AND pr.status = ?";
            $params[] = $status;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $row = $stmt->fetch();

        return (int) ($row['total'] ?? 0);
    }

    public function markAsClosed($id)
    {
        $stmt = $this->db->prepare("
            UPDATE purchase_requests
            SET status = 'closed',
                updated_at = NOW()
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }
    
    public function getPaginated($limit = 10, $offset = 0, $search = '', $status = '')
    {
        $keyword = '%' . $search . '%';

        $sql = "
            SELECT
                pr.*,
                u.name AS requested_by_name,
                d.name AS department_name
            FROM purchase_requests pr
            LEFT JOIN users u ON u.id = pr.requested_by
            LEFT JOIN departments d ON d.id = pr.department_id
            WHERE (
                pr.pr_number LIKE ?
                OR pr.purpose LIKE ?
                OR pr.status LIKE ?
                OR u.name LIKE ?
                OR d.name LIKE ?
            )
        ";

        $params = [
            $keyword,
            $keyword,
            $keyword,
            $keyword,
            $keyword
        ];

        if ($status !== '') {
            $sql .= " AND pr.status = ?";
            $params[] = $status;
        }

        $sql .= "
            ORDER BY pr.id DESC
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
                pr.*,
                u.name AS requested_by_name,
                d.name AS department_name,
                approver.name AS approved_by_name,
                rejecter.name AS rejected_by_name
            FROM purchase_requests pr
            LEFT JOIN users u ON u.id = pr.requested_by
            LEFT JOIN departments d ON d.id = pr.department_id
            LEFT JOIN users approver ON approver.id = pr.approved_by
            LEFT JOIN users rejecter ON rejecter.id = pr.rejected_by
            WHERE pr.id = ?
            LIMIT 1
        ");

        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO purchase_requests
            (
                pr_number,
                request_date,
                requested_by,
                department_id,
                needed_date,
                purpose,
                status,
                notes,
                created_by
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $data['pr_number'],
            $data['request_date'],
            $data['requested_by'] ?? null,
            $data['department_id'] ?? null,
            $data['needed_date'] ?? null,
            $data['purpose'] ?? '',
            $data['status'] ?? 'draft',
            $data['notes'] ?? '',
            $data['created_by'] ?? null
        ]);

        return $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE purchase_requests SET
                request_date = ?,
                requested_by = ?,
                department_id = ?,
                needed_date = ?,
                purpose = ?,
                status = ?,
                notes = ?,
                updated_at = NOW()
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['request_date'],
            $data['requested_by'] ?? null,
            $data['department_id'] ?? null,
            $data['needed_date'] ?? null,
            $data['purpose'] ?? '',
            $data['status'] ?? 'draft',
            $data['notes'] ?? '',
            $id
        ]);
    }

    public function delete($id)
    {
        $this->deleteItems($id);

        $stmt = $this->db->prepare("
            DELETE FROM purchase_requests
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }

    public function addItem($purchaseRequestId, $data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO purchase_request_items
            (
                purchase_request_id,
                item_name,
                description,
                qty,
                unit_name,
                estimated_price,
                subtotal
            )
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $purchaseRequestId,
            $data['item_name'],
            $data['description'] ?? '',
            $data['qty'] ?? 0,
            $data['unit_name'] ?? 'unit',
            $data['estimated_price'] ?? 0,
            $data['subtotal'] ?? 0
        ]);
    }

    public function getItems($purchaseRequestId)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM purchase_request_items
            WHERE purchase_request_id = ?
            ORDER BY id ASC
        ");

        $stmt->execute([$purchaseRequestId]);

        return $stmt->fetchAll();
    }

    public function deleteItems($purchaseRequestId)
    {
        $stmt = $this->db->prepare("
            DELETE FROM purchase_request_items
            WHERE purchase_request_id = ?
        ");

        return $stmt->execute([$purchaseRequestId]);
    }

    public function approve($id, $userId)
    {
        $stmt = $this->db->prepare("
            UPDATE purchase_requests SET
                status = 'approved',
                approved_by = ?,
                approved_at = NOW(),
                rejected_by = NULL,
                rejected_at = NULL,
                rejected_reason = NULL,
                updated_at = NOW()
            WHERE id = ?
        ");

        return $stmt->execute([
            $userId,
            $id
        ]);
    }

    public function reject($id, $userId, $reason)
    {
        $stmt = $this->db->prepare("
            UPDATE purchase_requests SET
                status = 'rejected',
                rejected_by = ?,
                rejected_at = NOW(),
                rejected_reason = ?,
                updated_at = NOW()
            WHERE id = ?
        ");

        return $stmt->execute([
            $userId,
            $reason,
            $id
        ]);
    }

    public function getApprovedForPO()
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM purchase_requests
            WHERE status = 'approved'
            ORDER BY id DESC
        ");

        $stmt->execute();

        return $stmt->fetchAll();
    }
}