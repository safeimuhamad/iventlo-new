<?php

class PurchaseRequestController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('purchase_request.view');

        $search = trim($_GET['search'] ?? '');

        $model = new PurchaseRequest();

        $limit = 10;
        $currentPage = max(1, (int) ($_GET['p'] ?? 1));
        $offset = ($currentPage - 1) * $limit;

        $status = $_GET['status'] ?? '';

        $totalData = $model->countAll($search, $status);
        $totalPages = (int) ceil($totalData / $limit);
        activity_log(
            'Purchasing - Purchase Request',
            'view',
            'Melihat daftar purchase request'
        );
        $this->view('purchase-requests/index', [
            'title' => 'Purchase Request',
            'items' => $model->getPaginated($limit, $offset, $search, $status),
            'search' => $search,
            'status' => $status,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalData' => $totalData,
            'limit' => $limit
        ]);
    }

    public function submitApproval()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('purchase_request.approve');

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('purchase-requests');
        }

        $purchaseRequestModel = new PurchaseRequest();
        $purchaseRequest = $purchaseRequestModel->find($id);

        if (!$purchaseRequest) {
            $_SESSION['error'] = 'Purchase request tidak ditemukan.';
            $this->redirect('purchase-requests');
        }

        if (!in_array(($purchaseRequest['status'] ?? ''), ['draft', 'rejected'])) {
            $_SESSION['error'] = 'Purchase Request ini tidak bisa disubmit karena status bukan draft/rejected.';
            $this->redirect('purchase-requests-show', ['id' => $id]);
        }

        $approvalModel = new ApprovalRequest();

        $existingApproval = $approvalModel->findByReference('purchase_requests', $id);

        if ($existingApproval && ($existingApproval['status'] ?? '') === 'waiting_approval') {
            $_SESSION['error'] = 'Purchase Request ini sudah dalam proses approval.';
            $this->redirect('approval-requests-show', ['id' => $existingApproval['id']]);
        }

        $amount = $purchaseRequestModel->getGrandTotal($id);

        $referenceNo = $purchaseRequest['pr_number'] 
            ?? $purchaseRequest['no_pr'] 
            ?? $purchaseRequest['request_no'] 
            ?? ('PR-' . $id);

        $departmentId = $purchaseRequest['department_id'] ?? null;

        $approvalRequestId = $approvalModel->createFromMatrix(
            'purchase_requests',
            $id,
            $referenceNo,
            $amount,
            $departmentId,
            null
        );

        if (!$approvalRequestId) {
            $_SESSION['error'] = 'DOA Matrix belum tersedia atau nominal PR tidak masuk range. Total PR: Rp ' . number_format($amount, 0, ',', '.');
            $this->redirect('purchase-requests-show', ['id' => $id]);
        }

        $purchaseRequestModel->updateApprovalStatus($id, 'waiting_approval');

        activity_log(
            'Purchase Request',
            'submit_approval',
            'Submit purchase request ke approval: ' . $referenceNo,
            $id,
            $referenceNo
        );
        
        $purchaseRequestModel->updateApprovalStatus($id, 'waiting_approval');

        $_SESSION['success'] = 'Purchase Request berhasil disubmit ke approval.';

        $this->redirect('purchase-requests');
    }
    public function create()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('purchase_request.create');

        $model = new PurchaseRequest();
        $departmentModel = new Department();

        activity_log(
            'Purchasing - Purchase Request',
            'create_form',
            'Membuka form tambah purchase request'
        );

        $this->view('purchase-requests/create', [
            'title' => 'Tambah Purchase Request',
            'prNumber' => $model->generateNumber(),
            'departments' => $departmentModel->getActive()
        ]);
    }

    public function store()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('purchase_request.create');

        $itemNames = $_POST['item_name'] ?? [];
        $descriptions = $_POST['description'] ?? [];
        $qtys = $_POST['qty'] ?? [];
        $unitNames = $_POST['unit_name'] ?? [];
        $prices = $_POST['estimated_price'] ?? [];

        $model = new PurchaseRequest();

        $prNumber = $_POST['pr_number'] ?? $model->generateNumber();

        $prId = $model->create([
            'pr_number' => $prNumber,
            'request_date' => $_POST['request_date'] ?? date('Y-m-d'),
            'requested_by' => $_SESSION['user_id'] ?? null,
            'department_id' => $_POST['department_id'] ?? null,
            'needed_date' => $_POST['needed_date'] ?? null,
            'purpose' => $_POST['purpose'] ?? '',
            'status' => $_POST['status'] ?? 'draft',
            'notes' => $_POST['notes'] ?? '',
            'created_by' => $_SESSION['user_id'] ?? null
        ]);

        foreach ($itemNames as $index => $itemName) {
            if (trim($itemName) === '') {
                continue;
            }

            $qty = (float) str_replace('.', '', $qtys[$index] ?? 0);
            $price = (float) str_replace('.', '', $prices[$index] ?? 0);
            $subtotal = $qty * $price;

            if ($qty <= 0) {
                continue;
            }

            $model->addItem($prId, [
                'item_name' => trim($itemName),
                'description' => $descriptions[$index] ?? '',
                'qty' => $qty,
                'unit_name' => $unitNames[$index] ?? 'unit',
                'estimated_price' => $price,
                'subtotal' => $subtotal
            ]);
        }

        activity_log(
            'Purchasing - Purchase Request',
            'create',
            'Membuat purchase request: ' . $prNumber,
            $prId,
            $prNumber
        );

        $_SESSION['success'] = 'Purchase request berhasil dibuat.';

        $this->redirect('purchase-requests');
    }

    public function show()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('purchase_request.view');

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('purchase-requests');
        }

        $model = new PurchaseRequest();

        $item = $model->find($id);

        if (!$item) {
            activity_log(
                'Purchasing - Purchase Request',
                'view_failed',
                'Gagal membuka detail purchase request karena data tidak ditemukan',
                $id
            );

            $_SESSION['error'] = 'Purchase request tidak ditemukan.';
            $this->redirect('purchase-requests');
        }

        activity_log(
            'Purchasing - Purchase Request',
            'view',
            'Melihat detail purchase request: ' . ($item['pr_number'] ?? '-'),
            $id,
            $item['pr_number'] ?? null
        );

        $this->view('purchase-requests/show', [
            'title' => 'Detail Purchase Request',
            'item' => $item,
            'items' => $model->getItems($id)
        ]);
    }

    public function edit()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('purchase_request.edit');

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('purchase-requests');
        }

        $model = new PurchaseRequest();

        $item = $model->find($id);

        if (!$item) {
            $_SESSION['error'] = 'Purchase request tidak ditemukan.';
            $this->redirect('purchase-requests');
        }

        if (!in_array(($item['status'] ?? ''), ['draft', 'rejected'])) {
            $_SESSION['error'] = 'Purchase request yang sudah diproses tidak bisa diedit.';
            $this->redirect('purchase-requests-show', ['id' => $id]);
        }

        $departmentModel = new Department();

        activity_log(
            'Purchasing - Purchase Request',
            'edit_form',
            'Membuka form edit purchase request: ' . ($item['pr_number'] ?? '-'),
            $id,
            $item['pr_number'] ?? null
        );

        $this->view('purchase-requests/edit', [
            'title' => 'Edit Purchase Request',
            'item' => $item,
            'items' => $model->getItems($id),
            'departments' => $departmentModel->getActive()
        ]);
    }

    public function update()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('purchase_request.edit');

        $id = $_POST['id'] ?? null;

        if (!$id) {
            $this->redirect('purchase-requests');
        }

        $model = new PurchaseRequest();

        $oldItem = $model->find($id);

        if (!$oldItem) {
            $_SESSION['error'] = 'Purchase request tidak ditemukan.';
            $this->redirect('purchase-requests');
        }

        if (!in_array(($oldItem['status'] ?? ''), ['draft', 'rejected'])) {
            $_SESSION['error'] = 'Purchase request yang sudah diproses tidak bisa diedit.';
            $this->redirect('purchase-requests-show', ['id' => $id]);
        }

        $model->update($id, [
            'request_date' => $_POST['request_date'] ?? date('Y-m-d'),
            'requested_by' => $oldItem['requested_by'] ?? ($_SESSION['user_id'] ?? null),
            'department_id' => $_POST['department_id'] ?? null,
            'needed_date' => $_POST['needed_date'] ?? null,
            'purpose' => $_POST['purpose'] ?? '',
            'status' => $_POST['status'] ?? 'draft',
            'notes' => $_POST['notes'] ?? ''
        ]);

        $model->deleteItems($id);

        $itemNames = $_POST['item_name'] ?? [];
        $descriptions = $_POST['description'] ?? [];
        $qtys = $_POST['qty'] ?? [];
        $unitNames = $_POST['unit_name'] ?? [];
        $prices = $_POST['estimated_price'] ?? [];

        foreach ($itemNames as $index => $itemName) {
            if (trim($itemName) === '') {
                continue;
            }

            $qty = (float) str_replace('.', '', $qtys[$index] ?? 0);
            $price = (float) str_replace('.', '', $prices[$index] ?? 0);
            $subtotal = $qty * $price;

            if ($qty <= 0) {
                continue;
            }

            $model->addItem($id, [
                'item_name' => trim($itemName),
                'description' => $descriptions[$index] ?? '',
                'qty' => $qty,
                'unit_name' => $unitNames[$index] ?? 'unit',
                'estimated_price' => $price,
                'subtotal' => $subtotal
            ]);
        }

        activity_log(
            'Purchasing - Purchase Request',
            'update',
            'Mengubah purchase request: ' . ($oldItem['pr_number'] ?? '-'),
            $id,
            $oldItem['pr_number'] ?? null
        );

        $_SESSION['success'] = 'Purchase request berhasil diperbarui.';

        $this->redirect('purchase-requests-show', ['id' => $id]);
    }

    public function approve()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('purchase_request.approve');

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('purchase-requests');
        }

        $model = new PurchaseRequest();

        $item = $model->find($id);

        if (!$item) {
            $_SESSION['error'] = 'Purchase request tidak ditemukan.';
            $this->redirect('purchase-requests');
        }

        $model->approve($id, $_SESSION['user_id']);

        activity_log(
            'Purchasing - Purchase Request',
            'approve',
            'Approve purchase request: ' . ($item['pr_number'] ?? '-'),
            $id,
            $item['pr_number'] ?? null
        );

        $_SESSION['success'] = 'Purchase request berhasil di-approve.';

        $this->redirect('purchase-requests-show', ['id' => $id]);
    }

    public function reject()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('purchase_request.approve');

        $id = $_POST['id'] ?? null;
        $reason = trim($_POST['rejected_reason'] ?? '');

        if (!$id) {
            $this->redirect('purchase-requests');
        }

        if ($reason === '') {
            $_SESSION['error'] = 'Alasan reject wajib diisi.';
            $this->redirect('purchase-requests-show', ['id' => $id]);
        }

        $model = new PurchaseRequest();

        $item = $model->find($id);

        if (!$item) {
            $_SESSION['error'] = 'Purchase request tidak ditemukan.';
            $this->redirect('purchase-requests');
        }

        $model->reject($id, $_SESSION['user_id'], $reason);

        activity_log(
            'Purchasing - Purchase Request',
            'reject',
            'Reject purchase request: ' . ($item['pr_number'] ?? '-') . '. Alasan: ' . $reason,
            $id,
            $item['pr_number'] ?? null
        );

        $_SESSION['success'] = 'Purchase request berhasil ditolak.';

        $this->redirect('purchase-requests-show', ['id' => $id]);
    }

    public function delete()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('purchase_request.delete');

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('purchase-requests');
        }

        $model = new PurchaseRequest();

        $item = $model->find($id);

        if (!$item) {
            $_SESSION['error'] = 'Purchase request tidak ditemukan.';
            $this->redirect('purchase-requests');
        }

        if (!in_array(($item['status'] ?? ''), ['draft', 'rejected'])) {
            $_SESSION['error'] = 'Purchase request yang sudah diproses tidak bisa dihapus.';
            $this->redirect('purchase-requests-show', ['id' => $id]);
        }

        $model->delete($id);

        activity_log(
            'Purchasing - Purchase Request',
            'delete',
            'Menghapus purchase request: ' . ($item['pr_number'] ?? '-'),
            $id,
            $item['pr_number'] ?? null
        );

        $_SESSION['success'] = 'Purchase request berhasil dihapus.';

        $this->redirect('purchase-requests');
    }
}