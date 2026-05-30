<?php

class LeaveRequestController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $search = $_GET['search'] ?? '';
        $p = max(1, (int) ($_GET['p'] ?? 1));
        $limit = 10;
        $offset = ($p - 1) * $limit;

        $model = new LeaveRequest();

        $totalRows = $model->countAll($search);
        $totalPages = ceil($totalRows / $limit);

        activity_log(
            'HRIS - Cuti/Izin',
            'view',
            'Melihat daftar pengajuan cuti/izin'
        );

        $this->view('leave-requests/index', [
            'title' => 'Cuti / Izin',
            'leaveRequests' => $model->paginate($search, $limit, $offset),
            'search' => $search,
            'p' => $p,
            'currentPage' => $p,
            'limit' => $limit,
            'totalPages' => $totalPages,
            'totalRows' => $totalRows
        ]);
    }

    public function create()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $employeeModel = new Employee();

        activity_log(
            'HRIS - Cuti/Izin',
            'create_form',
            'Membuka form tambah cuti/izin'
        );

        $this->view('leave-requests/create', [
            'title' => 'Tambah Cuti / Izin',
            'employees' => $employeeModel->getActive()
        ]);
    }

    public function store()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        if (
            empty($_POST['employee_id']) ||
            empty($_POST['start_date']) ||
            empty($_POST['end_date'])
        ) {

            activity_log(
                'HRIS - Cuti/Izin',
                'create_failed',
                'Gagal menambahkan cuti/izin karena data wajib kosong'
            );

            echo "Karyawan, tanggal mulai, dan tanggal selesai wajib diisi.";
            exit;
        }

        $start = new DateTime($_POST['start_date']);
        $end = new DateTime($_POST['end_date']);

        $totalDays = $start->diff($end)->days + 1;

        $model = new LeaveRequest();

        $leaveRequestId = $model->create([
            'employee_id' => $_POST['employee_id'],
            'leave_type' => $_POST['leave_type'] ?? 'annual_leave',
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'total_days' => $totalDays,
            'reason' => $_POST['reason'] ?? '',
            'status' => $_POST['status'] ?? 'draft'
        ]);

        activity_log(
            'HRIS - Cuti/Izin',
            'create',
            'Menambahkan pengajuan cuti/izin tanggal ' .
            $_POST['start_date'] .
            ' s/d ' .
            $_POST['end_date'],
            $leaveRequestId,
            $_POST['start_date'] ?? null
        );

        $this->redirect('leave-requests');
    }

    public function show()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('leave-requests');
        }

        $model = new LeaveRequest();
        $leaveRequest = $model->find($id);

        if (!$leaveRequest) {

            activity_log(
                'HRIS - Cuti/Izin',
                'view_failed',
                'Gagal membuka detail cuti/izin karena data tidak ditemukan',
                $id
            );

            $this->redirect('leave-requests');
        }

        activity_log(
            'HRIS - Cuti/Izin',
            'view',
            'Melihat detail cuti/izin: ' .
            (($leaveRequest['full_name'] ?? '-') . ' / ' . ($leaveRequest['start_date'] ?? '-')),
            $id,
            $leaveRequest['start_date'] ?? null
        );

        $this->view('leave-requests/show', [
            'title' => 'Detail Cuti / Izin',
            'leaveRequest' => $leaveRequest
        ]);
    }

    public function edit()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('leave-requests');
        }

        $model = new LeaveRequest();
        $leaveRequest = $model->find($id);

        if (!$leaveRequest) {

            activity_log(
                'HRIS - Cuti/Izin',
                'edit_failed',
                'Gagal membuka form edit cuti/izin karena data tidak ditemukan',
                $id
            );

            $this->redirect('leave-requests');
        }

        $employeeModel = new Employee();

        activity_log(
            'HRIS - Cuti/Izin',
            'edit_form',
            'Membuka form edit cuti/izin: ' .
            (($leaveRequest['full_name'] ?? '-') . ' / ' . ($leaveRequest['start_date'] ?? '-')),
            $id,
            $leaveRequest['start_date'] ?? null
        );

        $this->view('leave-requests/edit', [
            'title' => 'Edit Cuti / Izin',
            'leaveRequest' => $leaveRequest,
            'employees' => $employeeModel->getActive()
        ]);
    }

    public function update()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_POST['id'] ?? null;

        if (!$id) {
            $this->redirect('leave-requests');
        }

        if (
            empty($_POST['employee_id']) ||
            empty($_POST['start_date']) ||
            empty($_POST['end_date'])
        ) {

            activity_log(
                'HRIS - Cuti/Izin',
                'update_failed',
                'Gagal mengubah cuti/izin karena data wajib kosong',
                $id
            );

            echo "Karyawan, tanggal mulai, dan tanggal selesai wajib diisi.";
            exit;
        }

        $start = new DateTime($_POST['start_date']);
        $end = new DateTime($_POST['end_date']);

        $totalDays = $start->diff($end)->days + 1;

        $model = new LeaveRequest();

        $oldLeaveRequest = $model->find($id);

        $model->update($id, [
            'employee_id' => $_POST['employee_id'],
            'leave_type' => $_POST['leave_type'] ?? 'annual_leave',
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'total_days' => $totalDays,
            'reason' => $_POST['reason'] ?? '',
            'status' => $_POST['status'] ?? 'draft'
        ]);

        activity_log(
            'HRIS - Cuti/Izin',
            'update',
            'Mengubah pengajuan cuti/izin tanggal ' .
            $_POST['start_date'] .
            ' s/d ' .
            $_POST['end_date'],
            $id,
            $_POST['start_date'] ?? ($oldLeaveRequest['start_date'] ?? null)
        );

        $this->redirect('leave-requests');
    }

    public function approve()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('leave-requests');
        }

        $model = new LeaveRequest();
        $leaveRequest = $model->find($id);

        if (!$leaveRequest) {

            activity_log(
                'HRIS - Cuti/Izin',
                'approve_failed',
                'Gagal approve cuti/izin karena data tidak ditemukan',
                $id
            );

            $this->redirect('leave-requests');
        }

        $model->approve($id, $_SESSION['user_id']);

        activity_log(
            'HRIS - Cuti/Izin',
            'approve',
            'Menyetujui pengajuan cuti/izin: ' .
            ($leaveRequest['full_name'] ?? '-') .
            ' tanggal ' .
            ($leaveRequest['start_date'] ?? '-') .
            ' s/d ' .
            ($leaveRequest['end_date'] ?? '-'),
            $id,
            $leaveRequest['start_date'] ?? null
        );

        $this->redirect('leave-requests-show', ['id' => $id]);
    }

    public function reject()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_POST['id'] ?? null;

        if (!$id) {
            $this->redirect('leave-requests');
        }

        $reason = $_POST['rejected_reason'] ?? '';

        $model = new LeaveRequest();
        $leaveRequest = $model->find($id);

        if (!$leaveRequest) {

            activity_log(
                'HRIS - Cuti/Izin',
                'reject_failed',
                'Gagal reject cuti/izin karena data tidak ditemukan',
                $id
            );

            $this->redirect('leave-requests');
        }

        $model->reject($id, $reason, $_SESSION['user_id']);

        activity_log(
            'HRIS - Cuti/Izin',
            'reject',
            'Menolak pengajuan cuti/izin: ' .
            ($leaveRequest['full_name'] ?? '-') .
            '. Alasan: ' .
            $reason,
            $id,
            $leaveRequest['start_date'] ?? null
        );

        $this->redirect('leave-requests-show', ['id' => $id]);
    }

    public function delete()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('leave-requests');
        }

        $model = new LeaveRequest();
        $leaveRequest = $model->find($id);

        $model->delete($id);

        activity_log(
            'HRIS - Cuti/Izin',
            'delete',
            'Menghapus pengajuan cuti/izin: ' .
            (($leaveRequest['full_name'] ?? '-') . ' / ' . ($leaveRequest['start_date'] ?? '-')),
            $id,
            $leaveRequest['start_date'] ?? null
        );

        $this->redirect('leave-requests');
    }
}