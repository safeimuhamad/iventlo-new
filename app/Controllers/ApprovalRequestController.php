<?php

class ApprovalRequestController extends Controller
{

    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        if (!can('approval_request.view')) {
            die('Akses ditolak');
        }

        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? 'waiting_approval';

        $p = max(1, (int) ($_GET['p'] ?? 1));
        $limit = 10;
        $offset = ($p - 1) * $limit;

        $model = new ApprovalRequest();

        $userId = $_SESSION['user_id'] ?? 0;
        $roleId = $_SESSION['role_id'] ?? 0;

        $isSuperAdmin = ((int) $roleId === 1);

        if ($isSuperAdmin) {

            $totalRows = $model->countAll($search, $status);

            $approvalRequests = $model->paginate(
                $search,
                $status,
                $limit,
                $offset
            );

        } else {

            $totalRows = $model->countMyApprovalRequests(
                $userId,
                $roleId,
                $search,
                $status
            );

            $approvalRequests = $model->getMyApprovalRequests(
                $userId,
                $roleId,
                $search,
                $status,
                $limit,
                $offset
            );
        }

        $totalPages = ceil($totalRows / $limit);

        activity_log(
            'Approval Request',
            'view',
            'Melihat daftar approval request'
        );

        $this->view('approval-requests/index', [
            'title' => 'Approval Request',
            'approvalRequests' => $approvalRequests,
            'search' => $search,
            'status' => $status,
            'limit' => $limit,
            'p' => $p,
            'totalPages' => $totalPages,
            'totalRows' => $totalRows
        ]);
    }


    public function show()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        if (!can('approval_request.view')) {
            die('Akses ditolak');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('approval-requests');
        }

        $model = new ApprovalRequest();
        $approvalRequest = $model->find($id);

        if (!$approvalRequest) {
            $this->redirect('approval-requests');
        }

        activity_log(
            'Approval Request',
            'view',
            'Melihat detail approval request: ' . ($approvalRequest['reference_no'] ?? '-'),
            $id
        );

        $userId = $_SESSION['user_id'] ?? 0;
        $roleId = $_SESSION['role_id'] ?? 0;

        $this->view('approval-requests/show', [
            'title' => 'Detail Approval Request',
            'approvalRequest' => $approvalRequest,
            'steps' => $model->getSteps($id),
            'canApproveThisRequest' => $model->canApprove($id, $userId, $roleId)
        ]);
    }

    public function approve()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        if (!can('approval_request.approve')) {
            die('Akses ditolak');
        }

        $id = $_POST['id'] ?? null;
        $notes = $_POST['notes'] ?? '';

        if (!$id) {
            $this->redirect('approval-requests');
        }

        $model = new ApprovalRequest();

        $userId = $_SESSION['user_id'] ?? 0;
        $roleId = $_SESSION['role_id'] ?? 0;

        if (!$model->canApprove($id, $userId, $roleId)) {
            $_SESSION['error'] = 'Anda tidak memiliki akses approval untuk dokumen ini.';
            $this->redirect('approval-requests-show', ['id' => $id]);
        }

        $model->approve($id, $notes);

        activity_log(
            'Approval Request',
            'approve',
            'Menyetujui approval request',
            $id
        );

        $_SESSION['success'] = 'Approval request berhasil disetujui.';

        $this->redirect('approval-requests-show', ['id' => $id]);
    }

    public function reject()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        if (!can('approval_request.reject')) {
            die('Akses ditolak');
        }

        $id = $_POST['id'] ?? null;
        $notes = trim($_POST['notes'] ?? '');

        if (!$id) {
            $this->redirect('approval-requests');
        }

        if ($notes === '') {
            $_SESSION['error'] = 'Alasan reject wajib diisi.';
            $this->redirect('approval-requests-show', ['id' => $id]);
        }

        $model = new ApprovalRequest();

        $userId = $_SESSION['user_id'] ?? 0;
        $roleId = $_SESSION['role_id'] ?? 0;

        if (!$model->canApprove($id, $userId, $roleId)) {
            $_SESSION['error'] = 'Anda tidak memiliki akses reject untuk dokumen ini.';
            $this->redirect('approval-requests-show', ['id' => $id]);
        }

        $model->reject($id, $notes);

        activity_log(
            'Approval Request',
            'reject',
            'Menolak approval request. Alasan: ' . $notes,
            $id
        );

        $_SESSION['success'] = 'Approval request berhasil ditolak.';

        $this->redirect('approval-requests-show', ['id' => $id]);
    }
}