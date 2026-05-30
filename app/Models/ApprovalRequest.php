<?php

class ApprovalRequest
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function createFromMatrix($moduleName, $referenceId, $referenceNo, $amount, $departmentId = null, $documentType = null)
    {
        $matrices = $this->getMatchedMatrices($moduleName, $amount, $departmentId, $documentType);

        if (empty($matrices)) {
            return false;
        }

        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare("
                INSERT INTO approval_requests
                (
                    module_name,
                    reference_id,
                    reference_no,
                    amount,
                    current_level,
                    status,
                    requested_by,
                    requested_at
                )
                VALUES (?, ?, ?, ?, 1, 'waiting_approval', ?, NOW())
            ");

            $stmt->execute([
                $moduleName,
                $referenceId,
                $referenceNo,
                $amount,
                $_SESSION['user_id'] ?? null
            ]);

            $approvalRequestId = $this->db->lastInsertId();

            foreach ($matrices as $matrix) {
                $stepStatus = ((int) $matrix['approval_level'] === 1) ? 'pending' : 'waiting';

                $stepStmt = $this->db->prepare("
                    INSERT INTO approval_request_steps
                    (
                        approval_request_id,
                        approval_level,
                        approver_role_id,
                        approver_user_id,
                        status
                    )
                    VALUES (?, ?, ?, ?, ?)
                ");

                $stepStmt->execute([
                    $approvalRequestId,
                    $matrix['approval_level'],
                    $matrix['approver_role_id'],
                    $matrix['approver_user_id'],
                    $stepStatus
                ]);
            }

            $this->db->commit();

            return $approvalRequestId;

        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }



    public function countMyApprovalRequests($userId, $roleId, $search = '', $status = '')
    {
        $sql = "
        SELECT COUNT(DISTINCT ar.id) AS total
        FROM approval_requests ar
        INNER JOIN approval_request_steps ars 
        ON ars.approval_request_id = ar.id
        WHERE (
            ars.approver_user_id = ?
            OR ars.approved_by = ?
            OR (
                ars.approver_user_id IS NULL
                AND ars.approver_role_id = ?
                )
            )
        ";

        $params = [$userId, $userId, $roleId];

        if ($status !== '') {
            $sql .= " AND ar.status = ?";
            $params[] = $status;
        }

        if ($search !== '') {
            $sql .= " AND (ar.reference_no LIKE ? OR ar.module_name LIKE ?)";
            $keyword = "%{$search}%";
            $params[] = $keyword;
            $params[] = $keyword;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $row = $stmt->fetch();
        return (int) ($row['total'] ?? 0);
    }

    public function getMyApprovalRequests($userId, $roleId, $search = '', $status = '', $limit = 10, $offset = 0)
    {
        $sql = "
        SELECT DISTINCT
        ar.*,
        u.name AS requested_by_name
        FROM approval_requests ar
        INNER JOIN approval_request_steps ars 
        ON ars.approval_request_id = ar.id
        LEFT JOIN users u ON u.id = ar.requested_by
        WHERE (
            ars.approver_user_id = ?
            OR ars.approved_by = ?
            OR (
                ars.approver_user_id IS NULL
                AND ars.approver_role_id = ?
                )
            )
        ";

        $params = [$userId, $userId, $roleId];

        if ($status !== '') {
            $sql .= " AND ar.status = ?";
            $params[] = $status;
        }

        if ($search !== '') {
            $sql .= " AND (ar.reference_no LIKE ? OR ar.module_name LIKE ?)";
            $keyword = "%{$search}%";
            $params[] = $keyword;
            $params[] = $keyword;
        }

        $sql .= " ORDER BY ar.id DESC LIMIT {$limit} OFFSET {$offset}";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }
    

    public function getInbox($userId, $roleId, $search = '', $limit = 10, $offset = 0)
    {
        $sql = "
            SELECT
                ar.*,
                ars.id AS step_id,
                ars.approval_level,
                u.name AS requested_by_name
            FROM approval_requests ar
            INNER JOIN approval_request_steps ars 
                ON ars.approval_request_id = ar.id
            LEFT JOIN users u 
                ON u.id = ar.requested_by
            WHERE ars.status = 'pending'
            AND (
                ars.approver_user_id = ?
                OR (
                    ars.approver_user_id IS NULL
                    AND ars.approver_role_id = ?
                )
            )
        ";

        $params = [
            $userId,
            $roleId
        ];

        if (!empty($search)) {

            $sql .= "
                AND (
                    ar.reference_no LIKE ?
                    OR ar.module_name LIKE ?
                )
            ";

            $keyword = "%{$search}%";

            $params[] = $keyword;
            $params[] = $keyword;
        }

        $sql .= "
            ORDER BY ar.id DESC
            LIMIT {$limit} OFFSET {$offset}
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function countInbox($userId, $roleId, $search = '')
    {
        $sql = "
            SELECT COUNT(*) AS total
            FROM approval_requests ar
            INNER JOIN approval_request_steps ars 
                ON ars.approval_request_id = ar.id
            WHERE ars.status = 'pending'
            AND (
                ars.approver_user_id = ?
                OR (
                    ars.approver_user_id IS NULL
                    AND ars.approver_role_id = ?
                )
            )
        ";

        $params = [
            $userId,
            $roleId
        ];

        if (!empty($search)) {

            $sql .= "
                AND (
                    ar.reference_no LIKE ?
                    OR ar.module_name LIKE ?
                )
            ";

            $keyword = "%{$search}%";

            $params[] = $keyword;
            $params[] = $keyword;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $row = $stmt->fetch();

        return (int) ($row['total'] ?? 0);
    }

    public function canApprove($approvalRequestId, $userId, $roleId)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM approval_request_steps
            WHERE approval_request_id = ?
            AND status = 'pending'
            AND (
                approver_user_id = ?
                OR (
                    approver_user_id IS NULL
                    AND approver_role_id = ?
                )
            )
            LIMIT 1
        ");

        $stmt->execute([
            $approvalRequestId,
            $userId,
            $roleId
        ]);

        return $stmt->fetch();
    }

    public function countMyPending($userId, $roleId)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) AS total
            FROM approval_requests ar
            INNER JOIN approval_request_steps ars 
                ON ars.approval_request_id = ar.id
            WHERE ar.status = 'waiting_approval'
            AND ars.status = 'pending'
            AND (
                ars.approver_user_id = ?
                OR (
                    ars.approver_user_id IS NULL
                    AND ars.approver_role_id = ?
                )
            )
        ");

        $stmt->execute([
            $userId,
            $roleId
        ]);

        $row = $stmt->fetch();

        return (int) ($row['total'] ?? 0);
    }

    private function updateReferenceStatus($approvalRequest, $status)
    {
        $moduleName = $approvalRequest['module_name'] ?? '';
        $referenceId = $approvalRequest['reference_id'] ?? null;

        if (!$moduleName || !$referenceId) {
            return false;
        }

        $moduleMap = [
            'purchase_requests' => 'PurchaseRequest',
        ];

        if (!isset($moduleMap[$moduleName])) {
            return false;
        }

        $modelClass = $moduleMap[$moduleName];

        if (!class_exists($modelClass)) {
            return false;
        }

        $model = new $modelClass();

        if (!method_exists($model, 'updateApprovalStatus')) {
            return false;
        }

        return $model->updateApprovalStatus($referenceId, $status);
    }

    public function countAll($search = '', $status = '')
    {
        $sql = "
            SELECT COUNT(*) AS total
            FROM approval_requests
            WHERE 1=1
        ";

        $params = [];

        if (!empty($search)) {
            $sql .= " AND (module_name LIKE ? OR reference_no LIKE ?)";
            $keyword = "%{$search}%";
            $params[] = $keyword;
            $params[] = $keyword;
        }

        if (!empty($status)) {
            $sql .= " AND status = ?";
            $params[] = $status;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $row = $stmt->fetch();
        return (int) ($row['total'] ?? 0);
    }

    public function paginate($search = '', $status = '', $limit = 10, $offset = 0)
    {
        $sql = "
            SELECT 
                ar.*,
                u.name AS requested_by_name
            FROM approval_requests ar
            LEFT JOIN users u ON u.id = ar.requested_by
            WHERE 1=1
        ";

        $params = [];

        if (!empty($search)) {
            $sql .= " AND (ar.module_name LIKE ? OR ar.reference_no LIKE ?)";
            $keyword = "%{$search}%";
            $params[] = $keyword;
            $params[] = $keyword;
        }

        if (!empty($status)) {
            $sql .= " AND ar.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY ar.id DESC LIMIT {$limit} OFFSET {$offset}";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function getMatchedMatrices($moduleName, $amount, $departmentId = null, $documentType = null)
    {
        $sql = "
            SELECT *
            FROM approval_matrices
            WHERE module_name = ?
            AND is_active = 1
            AND min_amount <= ?
            AND (max_amount IS NULL OR max_amount >= ?)
            AND (department_id IS NULL OR department_id = ?)
            AND (document_type IS NULL OR document_type = '' OR document_type = ?)
            ORDER BY approval_level ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $moduleName,
            $amount,
            $amount,
            $departmentId,
            $documentType
        ]);

        return $stmt->fetchAll();
    }

    public function findByReference($moduleName, $referenceId)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM approval_requests
            WHERE module_name = ?
            AND reference_id = ?
            ORDER BY id DESC
            LIMIT 1
        ");

        $stmt->execute([$moduleName, $referenceId]);
        return $stmt->fetch();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT ar.*
            FROM approval_requests ar
            WHERE ar.id = ?
            LIMIT 1
        ");

        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getSteps($approvalRequestId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                ars.*,
                r.name AS approver_role_name,
                u.name AS approver_user_name,
                approvedUser.name AS approved_by_name
            FROM approval_request_steps ars
            LEFT JOIN roles r ON r.id = ars.approver_role_id
            LEFT JOIN users u ON u.id = ars.approver_user_id
            LEFT JOIN users approvedUser ON approvedUser.id = ars.approved_by
            WHERE ars.approval_request_id = ?
            ORDER BY ars.approval_level ASC
        ");

        $stmt->execute([$approvalRequestId]);
        return $stmt->fetchAll();
    }

    public function getCurrentPendingStep($approvalRequestId)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM approval_request_steps
            WHERE approval_request_id = ?
            AND status = 'pending'
            ORDER BY approval_level ASC
            LIMIT 1
        ");

        $stmt->execute([$approvalRequestId]);
        return $stmt->fetch();
    }

    public function approve($approvalRequestId, $notes = '')
    {
        $approvalRequest = $this->find($approvalRequestId);

        if (!$approvalRequest || $approvalRequest['status'] !== 'waiting_approval') {
            return false;
        }

        $currentStep = $this->getCurrentPendingStep($approvalRequestId);

        if (!$currentStep) {
            return false;
        }

        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare("
                UPDATE approval_request_steps SET
                    status = 'approved',
                    approved_by = ?,
                    approved_at = NOW(),
                    notes = ?
                WHERE id = ?
            ");

            $stmt->execute([
                $_SESSION['user_id'] ?? null,
                $notes,
                $currentStep['id']
            ]);

            $nextLevel = (int) $currentStep['approval_level'] + 1;

            $nextStmt = $this->db->prepare("
                SELECT *
                FROM approval_request_steps
                WHERE approval_request_id = ?
                AND approval_level = ?
                LIMIT 1
            ");

            $nextStmt->execute([$approvalRequestId, $nextLevel]);
            $nextStep = $nextStmt->fetch();

            if ($nextStep) {
                $updateNext = $this->db->prepare("
                    UPDATE approval_request_steps
                    SET status = 'pending'
                    WHERE id = ?
                ");

                $updateNext->execute([$nextStep['id']]);

                $updateRequest = $this->db->prepare("
                    UPDATE approval_requests SET
                        current_level = ?
                    WHERE id = ?
                ");

                $updateRequest->execute([$nextLevel, $approvalRequestId]);
            } else {
                $complete = $this->db->prepare("
                    UPDATE approval_requests SET
                        status = 'approved',
                        completed_at = NOW()
                    WHERE id = ?
                ");

                $complete->execute([$approvalRequestId]);

                $this->updateReferenceStatus($approvalRequest, 'approved');
            }

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function reject($approvalRequestId, $notes = '')
    {
        $approvalRequest = $this->find($approvalRequestId);

        if (!$approvalRequest || $approvalRequest['status'] !== 'waiting_approval') {
            return false;
        }

        $currentStep = $this->getCurrentPendingStep($approvalRequestId);

        if (!$currentStep) {
            return false;
        }

        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare("
                UPDATE approval_request_steps SET
                    status = 'rejected',
                    approved_by = ?,
                    approved_at = NOW(),
                    notes = ?
                WHERE id = ?
            ");

            $stmt->execute([
                $_SESSION['user_id'] ?? null,
                $notes,
                $currentStep['id']
            ]);

            $requestStmt = $this->db->prepare("
                UPDATE approval_requests SET
                    status = 'rejected',
                    completed_at = NOW()
                WHERE id = ?
            ");

            $requestStmt->execute([$approvalRequestId]);

            $this->updateReferenceStatus($approvalRequest, 'rejected');

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}