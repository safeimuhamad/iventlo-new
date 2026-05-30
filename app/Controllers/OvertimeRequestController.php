<?php

class OvertimeRequestController extends Controller
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

        $model = new OvertimeRequest();

        $totalRows = $model->countAll($search);
        $totalPages = ceil($totalRows / $limit);

        activity_log(
            'HRIS - Lembur',
            'view',
            'Melihat daftar pengajuan lembur'
        );

        $this->view('overtime-requests/index', [
            'title' => 'Lembur',
            'overtimeRequests' => $model->paginate($search, $limit, $offset),
            'search' => $search,
            'currentPage' => $p,
            'limit' => $limit,
            'p' => $p,
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
            'HRIS - Lembur',
            'create_form',
            'Membuka form tambah lembur'
        );

        $this->view('overtime-requests/create', [
            'title' => 'Tambah Lembur',
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
            empty($_POST['overtime_date']) ||
            empty($_POST['start_time']) ||
            empty($_POST['end_time'])
        ) {

            activity_log(
                'HRIS - Lembur',
                'create_failed',
                'Gagal membuat pengajuan lembur karena data wajib kosong'
            );

            echo "Karyawan, tanggal, jam mulai, dan jam selesai wajib diisi.";
            exit;
        }

        $totalMinutes = $this->calculateMinutes(
            $_POST['start_time'],
            $_POST['end_time']
        );

        $model = new OvertimeRequest();

        $overtimeId = $model->create([
            'employee_id' => $_POST['employee_id'],
            'overtime_date' => $_POST['overtime_date'],
            'start_time' => $_POST['start_time'],
            'end_time' => $_POST['end_time'],
            'total_minutes' => $totalMinutes,
            'reason' => $_POST['reason'] ?? '',
            'status' => $_POST['status'] ?? 'draft'
        ]);

        activity_log(
            'HRIS - Lembur',
            'create',
            'Menambahkan pengajuan lembur tanggal ' . $_POST['overtime_date'],
            $overtimeId,
            $_POST['overtime_date'] ?? null
        );

        $this->redirect('overtime-requests');
    }

    public function show()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('overtime-requests');
        }

        $model = new OvertimeRequest();
        $overtimeRequest = $model->find($id);

        if (!$overtimeRequest) {

            activity_log(
                'HRIS - Lembur',
                'view_failed',
                'Gagal membuka detail lembur karena data tidak ditemukan',
                $id
            );

            $this->redirect('overtime-requests');
        }

        activity_log(
            'HRIS - Lembur',
            'view',
            'Melihat detail lembur: ' .
            (($overtimeRequest['full_name'] ?? '-') . ' / ' . ($overtimeRequest['overtime_date'] ?? '-')),
            $id,
            $overtimeRequest['overtime_date'] ?? null
        );

        $this->view('overtime-requests/show', [
            'title' => 'Detail Lembur',
            'overtimeRequest' => $overtimeRequest
        ]);
    }

    public function edit()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('overtime-requests');
        }

        $model = new OvertimeRequest();
        $overtimeRequest = $model->find($id);

        if (!$overtimeRequest) {

            activity_log(
                'HRIS - Lembur',
                'edit_failed',
                'Gagal membuka form edit lembur karena data tidak ditemukan',
                $id
            );

            $this->redirect('overtime-requests');
        }

        $employeeModel = new Employee();

        activity_log(
            'HRIS - Lembur',
            'edit_form',
            'Membuka form edit lembur: ' .
            (($overtimeRequest['full_name'] ?? '-') . ' / ' . ($overtimeRequest['overtime_date'] ?? '-')),
            $id,
            $overtimeRequest['overtime_date'] ?? null
        );

        $this->view('overtime-requests/edit', [
            'title' => 'Edit Lembur',
            'overtimeRequest' => $overtimeRequest,
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
            $this->redirect('overtime-requests');
        }

        if (
            empty($_POST['employee_id']) ||
            empty($_POST['overtime_date']) ||
            empty($_POST['start_time']) ||
            empty($_POST['end_time'])
        ) {

            activity_log(
                'HRIS - Lembur',
                'update_failed',
                'Gagal mengubah pengajuan lembur karena data wajib kosong',
                $id
            );

            echo "Karyawan, tanggal, jam mulai, dan jam selesai wajib diisi.";
            exit;
        }

        $totalMinutes = $this->calculateMinutes(
            $_POST['start_time'],
            $_POST['end_time']
        );

        $model = new OvertimeRequest();

        $oldData = $model->find($id);

        $model->update($id, [
            'employee_id' => $_POST['employee_id'],
            'overtime_date' => $_POST['overtime_date'],
            'start_time' => $_POST['start_time'],
            'end_time' => $_POST['end_time'],
            'total_minutes' => $totalMinutes,
            'reason' => $_POST['reason'] ?? '',
            'status' => $_POST['status'] ?? 'draft'
        ]);

        activity_log(
            'HRIS - Lembur',
            'update',
            'Mengubah pengajuan lembur tanggal ' . $_POST['overtime_date'],
            $id,
            $_POST['overtime_date'] ?? ($oldData['overtime_date'] ?? null)
        );

        $this->redirect('overtime-requests');
    }

    public function approve()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('overtime-requests');
        }

        $model = new OvertimeRequest();
        $overtimeRequest = $model->find($id);

        if (!$overtimeRequest) {

            activity_log(
                'HRIS - Lembur',
                'approve_failed',
                'Gagal approve lembur karena data tidak ditemukan',
                $id
            );

            $this->redirect('overtime-requests');
        }

        $model->approve($id, $_SESSION['user_id']);

        activity_log(
            'HRIS - Lembur',
            'approve',
            'Menyetujui pengajuan lembur: ' .
            ($overtimeRequest['full_name'] ?? '-') .
            ' tanggal ' .
            ($overtimeRequest['overtime_date'] ?? '-'),
            $id,
            $overtimeRequest['overtime_date'] ?? null
        );

        $this->redirect('overtime-requests-show', ['id' => $id]);
    }

    public function reject()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_POST['id'] ?? null;

        if (!$id) {
            $this->redirect('overtime-requests');
        }

        $reason = $_POST['rejected_reason'] ?? '';

        $model = new OvertimeRequest();
        $overtimeRequest = $model->find($id);

        if (!$overtimeRequest) {

            activity_log(
                'HRIS - Lembur',
                'reject_failed',
                'Gagal reject lembur karena data tidak ditemukan',
                $id
            );

            $this->redirect('overtime-requests');
        }

        $model->reject($id, $reason, $_SESSION['user_id']);

        activity_log(
            'HRIS - Lembur',
            'reject',
            'Menolak pengajuan lembur: ' .
            ($overtimeRequest['full_name'] ?? '-') .
            '. Alasan: ' .
            $reason,
            $id,
            $overtimeRequest['overtime_date'] ?? null
        );

        $this->redirect('overtime-requests-show', ['id' => $id]);
    }

    public function delete()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('overtime-requests');
        }

        $model = new OvertimeRequest();
        $overtimeRequest = $model->find($id);

        $model->delete($id);

        activity_log(
            'HRIS - Lembur',
            'delete',
            'Menghapus pengajuan lembur: ' .
            (($overtimeRequest['full_name'] ?? '-') . ' / ' . ($overtimeRequest['overtime_date'] ?? '-')),
            $id,
            $overtimeRequest['overtime_date'] ?? null
        );

        $this->redirect('overtime-requests');
    }

    private function calculateMinutes($startTime, $endTime)
    {
        $start = strtotime($startTime);
        $end = strtotime($endTime);

        if ($end < $start) {
            $end += 86400;
        }

        return (int) (($end - $start) / 60);
    }
}