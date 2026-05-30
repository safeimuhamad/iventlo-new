<?php

class PayrollPeriodController extends Controller
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

        $model = new PayrollPeriod();

        $totalRows = $model->countAll($search);
        $totalPages = ceil($totalRows / $limit);

        activity_log(
            'HRIS - Payroll Period',
            'view',
            'Melihat daftar periode payroll'
        );

        $this->view('payroll-periods/index', [
            'title' => 'Periode Payroll',
            'payrollPeriods' => $model->paginate($search, $limit, $offset),
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

        activity_log(
            'HRIS - Payroll Period',
            'create_form',
            'Membuka form tambah periode payroll'
        );

        $this->view('payroll-periods/create', [
            'title' => 'Tambah Periode Payroll'
        ]);
    }

    public function store()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        if (
            empty($_POST['period_name']) ||
            empty($_POST['start_date']) ||
            empty($_POST['end_date'])
        ) {

            activity_log(
                'HRIS - Payroll Period',
                'create_failed',
                'Gagal menambahkan periode payroll karena data wajib kosong'
            );

            echo "Nama periode dan tanggal wajib diisi.";
            exit;
        }

        $model = new PayrollPeriod();

        $periodId = $model->create([
            'period_name' => $_POST['period_name'],
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'payroll_date' => $_POST['payroll_date'] ?? null,
            'status' => $_POST['status'] ?? 'draft',
            'notes' => $_POST['notes'] ?? ''
        ]);

        activity_log(
            'HRIS - Payroll Period',
            'create',
            'Menambahkan periode payroll: ' . $_POST['period_name'],
            $periodId,
            $_POST['period_name'] ?? null
        );

        $this->redirect('payroll-periods');
    }

    public function show()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('payroll-periods');
        }

        $model = new PayrollPeriod();
        $payrollPeriod = $model->find($id);

        if (!$payrollPeriod) {

            activity_log(
                'HRIS - Payroll Period',
                'view_failed',
                'Gagal membuka detail periode payroll karena data tidak ditemukan',
                $id
            );

            $this->redirect('payroll-periods');
        }

        activity_log(
            'HRIS - Payroll Period',
            'view',
            'Melihat detail periode payroll: ' . ($payrollPeriod['period_name'] ?? '-'),
            $id,
            $payrollPeriod['period_name'] ?? null
        );

        $this->view('payroll-periods/show', [
            'title' => 'Detail Periode Payroll',
            'payrollPeriod' => $payrollPeriod
        ]);
    }

    public function edit()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('payroll-periods');
        }

        $model = new PayrollPeriod();
        $payrollPeriod = $model->find($id);

        if (!$payrollPeriod) {

            activity_log(
                'HRIS - Payroll Period',
                'edit_failed',
                'Gagal membuka form edit periode payroll karena data tidak ditemukan',
                $id
            );

            $this->redirect('payroll-periods');
        }

        activity_log(
            'HRIS - Payroll Period',
            'edit_form',
            'Membuka form edit periode payroll: ' . ($payrollPeriod['period_name'] ?? '-'),
            $id,
            $payrollPeriod['period_name'] ?? null
        );

        $this->view('payroll-periods/edit', [
            'title' => 'Edit Periode Payroll',
            'payrollPeriod' => $payrollPeriod
        ]);
    }

    public function update()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_POST['id'] ?? null;

        if (!$id) {
            $this->redirect('payroll-periods');
        }

        $model = new PayrollPeriod();

        $oldPeriod = $model->find($id);

        $model->update($id, [
            'period_name' => $_POST['period_name'],
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'payroll_date' => $_POST['payroll_date'] ?? null,
            'status' => $_POST['status'] ?? 'draft',
            'notes' => $_POST['notes'] ?? ''
        ]);

        activity_log(
            'HRIS - Payroll Period',
            'update',
            'Mengubah periode payroll: ' . $_POST['period_name'],
            $id,
            $_POST['period_name'] ?? ($oldPeriod['period_name'] ?? null)
        );

        $this->redirect('payroll-periods');
    }

    public function delete()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('payroll-periods');
        }

        $model = new PayrollPeriod();
        $payrollPeriod = $model->find($id);

        $model->delete($id);

        activity_log(
            'HRIS - Payroll Period',
            'delete',
            'Menghapus periode payroll: ' . ($payrollPeriod['period_name'] ?? '-'),
            $id,
            $payrollPeriod['period_name'] ?? null
        );

        $this->redirect('payroll-periods');
    }
}