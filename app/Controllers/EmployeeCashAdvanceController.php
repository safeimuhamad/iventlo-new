<?php

class EmployeeCashAdvanceController extends Controller
{
    private function ensureCanAccessItem($item)
    {
        $scope = $_SESSION['data_scope'] ?? 'own';
        $employeeId = $_SESSION['employee_id'] ?? null;

        if ($scope === 'all') {
            return;
        }

        if ((int) ($item['employee_id'] ?? 0) !== (int) $employeeId) {

            activity_log(
                'HRIS - Kasbon Karyawan',
                'access_denied',
                'Mencoba mengakses kasbon milik karyawan lain',
                $item['id'] ?? null,
                $item['cash_advance_number'] ?? null
            );

            $_SESSION['error'] = 'Anda tidak memiliki akses ke data tersebut.';
            $this->redirect('employee-cash-advances');
        }
    }

    private function ensureNotOwnRequest($item)
    {
        if ((int) ($item['created_by'] ?? 0) === (int) ($_SESSION['user_id'] ?? 0)) {

            activity_log(
                'HRIS - Kasbon Karyawan',
                'process_denied',
                'Mencoba memproses kasbon sendiri',
                $item['id'] ?? null,
                $item['cash_advance_number'] ?? null
            );

            $_SESSION['error'] = 'Anda tidak dapat memproses kasbon yang Anda ajukan sendiri.';
            $this->redirect('employee-cash-advances-show', ['id' => $item['id']]);
        }
    }

    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('employee_cash_advance.view');

        $model = new EmployeeCashAdvance();

        $limit = 10;
        $currentPage = max(1, (int) ($_GET['p'] ?? 1));
        $offset = ($currentPage - 1) * $limit;

        $totalData = $model->countAll();
        $totalPages = (int) ceil($totalData / $limit);

        activity_log(
            'HRIS - Kasbon Karyawan',
            'view',
            'Melihat daftar kasbon karyawan'
        );

        $this->view('employee-cash-advances/index', [
            'title' => 'Kasbon Karyawan',
            'items' => $model->getPaginated($limit, $offset),
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalData' => $totalData,
            'limit' => $limit
        ]);
    }

    public function create()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('employee_cash_advance.create');

        $employeeModel = new Employee();

        activity_log(
            'HRIS - Kasbon Karyawan',
            'create_form',
            'Membuka form pengajuan kasbon'
        );

        $this->view('employee-cash-advances/create', [
            'title' => 'Tambah Kasbon',
            'employees' => $employeeModel->getActive()
        ]);
    }

    public function store()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('employee_cash_advance.create');

        $model = new EmployeeCashAdvance();

        $data = [
            'cash_advance_number' => $model->generateNumber(),
            'employee_id' => $_SESSION['employee_id'] ?? null,
            'request_date' => date('Y-m-d'),
            'purpose' => trim($_POST['purpose'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'amount' => (float) ($_POST['amount'] ?? 0),
            'status' => 'waiting_supervisor_approval',
            'created_by' => $_SESSION['user_id'] ?? null
        ];

        if (
            empty($data['employee_id']) ||
            $data['purpose'] === '' ||
            $data['amount'] <= 0
        ) {

            activity_log(
                'HRIS - Kasbon Karyawan',
                'create_failed',
                'Gagal membuat pengajuan kasbon karena data tidak lengkap'
            );

            $_SESSION['error'] = 'Data karyawan, keperluan, dan nominal wajib diisi.';
            $this->redirect('employee-cash-advances-create');
        }

        $cashAdvanceId = $model->create($data);

        activity_log(
            'HRIS - Kasbon Karyawan',
            'create',
            'Membuat pengajuan kasbon sebesar Rp ' . number_format($data['amount'], 0, ',', '.'),
            $cashAdvanceId,
            $data['cash_advance_number']
        );

        $_SESSION['success'] = 'Pengajuan kasbon berhasil dibuat.';
        $this->redirect('employee-cash-advances');
    }

    public function show()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('employee_cash_advance.view');

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('employee-cash-advances');
        }

        $model = new EmployeeCashAdvance();
        $item = $model->find($id);

        if (!$item) {

            activity_log(
                'HRIS - Kasbon Karyawan',
                'view_failed',
                'Gagal membuka detail kasbon karena data tidak ditemukan',
                $id
            );

            $_SESSION['error'] = 'Data kasbon tidak ditemukan.';
            $this->redirect('employee-cash-advances');
        }

        $this->ensureCanAccessItem($item);

        $bankModel = new BankAccount();

        activity_log(
            'HRIS - Kasbon Karyawan',
            'view',
            'Melihat detail kasbon',
            $id,
            $item['cash_advance_number'] ?? null
        );

        $this->view('employee-cash-advances/show', [
            'title' => 'Detail Kasbon',
            'item' => $item,
            'bankAccounts' => $bankModel->getActive()
        ]);
    }

    public function edit()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('employee_cash_advance.edit');

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('employee-cash-advances');
        }

        $model = new EmployeeCashAdvance();
        $item = $model->find($id);

        if (!$item) {
            $_SESSION['error'] = 'Data kasbon tidak ditemukan.';
            $this->redirect('employee-cash-advances');
        }

        $this->ensureCanAccessItem($item);

        if (($item['status'] ?? '') !== 'waiting_supervisor_approval') {

            activity_log(
                'HRIS - Kasbon Karyawan',
                'edit_denied',
                'Mencoba edit kasbon yang sudah diproses',
                $id,
                $item['cash_advance_number'] ?? null
            );

            $_SESSION['error'] = 'Kasbon yang sudah diproses tidak bisa diedit.';
            $this->redirect('employee-cash-advances-show', ['id' => $id]);
        }

        $employeeModel = new Employee();

        activity_log(
            'HRIS - Kasbon Karyawan',
            'edit_form',
            'Membuka form edit kasbon',
            $id,
            $item['cash_advance_number'] ?? null
        );

        $this->view('employee-cash-advances/edit', [
            'title' => 'Edit Kasbon',
            'item' => $item,
            'employees' => $employeeModel->getActive()
        ]);
    }

    public function update()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('employee_cash_advance.edit');

        $id = $_POST['id'] ?? null;

        if (!$id) {
            $this->redirect('employee-cash-advances');
        }

        $model = new EmployeeCashAdvance();
        $item = $model->find($id);

        if (!$item) {
            $_SESSION['error'] = 'Data kasbon tidak ditemukan.';
            $this->redirect('employee-cash-advances');
        }

        $this->ensureCanAccessItem($item);

        $model->update($id, [
            'employee_id' => $_POST['employee_id'] ?? null,
            'request_date' => $_POST['request_date'] ?? date('Y-m-d'),
            'purpose' => trim($_POST['purpose'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'amount' => (float) ($_POST['amount'] ?? 0)
        ]);

        activity_log(
            'HRIS - Kasbon Karyawan',
            'update',
            'Mengubah pengajuan kasbon',
            $id,
            $item['cash_advance_number'] ?? null
        );

        $_SESSION['success'] = 'Data kasbon berhasil diperbarui.';
        $this->redirect('employee-cash-advances-show', ['id' => $id]);
    }

    public function supervisorApprove()
    {
        requirePermission('employee_cash_advance.supervisor_approve');

        $id = $_POST['id'] ?? null;
        $note = trim($_POST['supervisor_note'] ?? '');

        if (!$id) {
            $this->redirect('employee-cash-advances');
        }

        $model = new EmployeeCashAdvance();
        $item = $model->find($id);

        if (!$item) {
            $_SESSION['error'] = 'Data kasbon tidak ditemukan.';
            $this->redirect('employee-cash-advances');
        }

        $this->ensureNotOwnRequest($item);

        $model->supervisorApprove($id, $note);

        activity_log(
            'HRIS - Kasbon Karyawan',
            'supervisor_approve',
            'Supervisor menyetujui pengajuan kasbon',
            $id,
            $item['cash_advance_number'] ?? null
        );

        $_SESSION['success'] = 'Kasbon disetujui atasan.';
        $this->redirect('employee-cash-advances-show', ['id' => $id]);
    }

    public function financeApprove()
    {
        requirePermission('employee_cash_advance.finance_approve');

        $id = $_POST['id'] ?? null;
        $approvedAmount = (float) ($_POST['approved_amount'] ?? 0);
        $note = trim($_POST['finance_note'] ?? '');

        if (!$id || $approvedAmount <= 0) {

            activity_log(
                'HRIS - Kasbon Karyawan',
                'finance_approve_failed',
                'Gagal approve finance karena nominal tidak valid',
                $id
            );

            $_SESSION['error'] = 'Nominal disetujui wajib diisi.';
            $this->redirect('employee-cash-advances-show', ['id' => $id]);
        }

        $model = new EmployeeCashAdvance();
        $item = $model->find($id);

        if (!$item) {
            $_SESSION['error'] = 'Data kasbon tidak ditemukan.';
            $this->redirect('employee-cash-advances');
        }

        $this->ensureNotOwnRequest($item);

        $model->financeApprove($id, $approvedAmount, $note);

        activity_log(
            'HRIS - Kasbon Karyawan',
            'finance_approve',
            'Finance menyetujui kasbon sebesar Rp ' . number_format($approvedAmount, 0, ',', '.'),
            $id,
            $item['cash_advance_number'] ?? null
        );

        $_SESSION['success'] = 'Kasbon disetujui keuangan.';
        $this->redirect('employee-cash-advances-show', ['id' => $id]);
    }

    public function disburse()
    {
        requirePermission('employee_cash_advance.disburse');

        $id = $_POST['id'] ?? null;
        $paymentAccountId = $_POST['payment_account_id'] ?? null;
        $note = trim($_POST['disbursement_note'] ?? '');

        if (!$id || !$paymentAccountId) {

            activity_log(
                'HRIS - Kasbon Karyawan',
                'disburse_failed',
                'Gagal mencairkan kasbon karena akun pembayaran kosong',
                $id
            );

            $_SESSION['error'] = 'Akun pembayaran wajib dipilih.';
            $this->redirect('employee-cash-advances-show', ['id' => $id]);
        }

        $model = new EmployeeCashAdvance();
        $item = $model->find($id);

        if (!$item) {
            $_SESSION['error'] = 'Data kasbon tidak ditemukan.';
            $this->redirect('employee-cash-advances');
        }

        $this->ensureNotOwnRequest($item);

        $success = $model->disburse($id, $paymentAccountId, $note);

        if ($success) {

            activity_log(
                'HRIS - Kasbon Karyawan',
                'disburse',
                'Mencairkan kasbon karyawan',
                $id,
                $item['cash_advance_number'] ?? null
            );

            $_SESSION['success'] = 'Kasbon berhasil dicairkan';

        } else {

            activity_log(
                'HRIS - Kasbon Karyawan',
                'disburse_failed',
                'Gagal mencairkan kasbon karena saldo tidak cukup/status tidak valid',
                $id,
                $item['cash_advance_number'] ?? null
            );

            $_SESSION['error'] = 'Kasbon gagal dicairkan. Pastikan status sudah menunggu pencairan dan saldo kas/bank mencukupi.';
        }

        $this->redirect('employee-cash-advances-show', ['id' => $id]);
    }

    public function reject()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_POST['id'] ?? null;
        $note = trim($_POST['reject_note'] ?? '');

        if (!$id) {
            $this->redirect('employee-cash-advances');
        }

        $model = new EmployeeCashAdvance();
        $item = $model->find($id);

        if (!$item) {
            $_SESSION['error'] = 'Data kasbon tidak ditemukan.';
            $this->redirect('employee-cash-advances');
        }

        $this->ensureNotOwnRequest($item);

        $status = $item['status'] ?? '';

        if ($status === 'waiting_supervisor_approval') {

            requirePermission('employee_cash_advance.supervisor_approve');

        } elseif ($status === 'waiting_finance_approval') {

            requirePermission('employee_cash_advance.finance_approve');

        } else {

            activity_log(
                'HRIS - Kasbon Karyawan',
                'reject_failed',
                'Gagal menolak kasbon karena status tidak valid',
                $id,
                $item['cash_advance_number'] ?? null
            );

            $_SESSION['error'] = 'Kasbon dengan status ini tidak bisa ditolak.';
            $this->redirect('employee-cash-advances-show', ['id' => $id]);
        }

        if ($note === '') {

            activity_log(
                'HRIS - Kasbon Karyawan',
                'reject_failed',
                'Gagal menolak kasbon karena catatan kosong',
                $id,
                $item['cash_advance_number'] ?? null
            );

            $_SESSION['error'] = 'Catatan penolakan wajib diisi.';
            $this->redirect('employee-cash-advances-show', ['id' => $id]);
        }

        $model->reject($id, $note);

        activity_log(
            'HRIS - Kasbon Karyawan',
            'reject',
            'Menolak pengajuan kasbon',
            $id,
            $item['cash_advance_number'] ?? null
        );

        $_SESSION['success'] = 'Kasbon berhasil ditolak.';
        $this->redirect('employee-cash-advances-show', ['id' => $id]);
    }
}