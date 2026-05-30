<?php

class EmployeeContractController extends Controller
{
    private function authorize($permissionKey)
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission($permissionKey);
    }

    public function index()
    {
        $this->authorize('employee_contract.view');

        $model = new EmployeeContract();

        $search = trim($_GET['search'] ?? '');

        $limit = 10;
        $currentPage = max(1, (int) ($_GET['p'] ?? 1));
        $offset = ($currentPage - 1) * $limit;

        $totalData = $model->countAll($search);
        $totalPages = (int) ceil($totalData / $limit);

        activity_log(
            'HRIS - Kontrak Karyawan',
            'view',
            'Melihat daftar kontrak karyawan'
        );

        $this->view('employee-contracts/index', [
            'title' => 'Kontrak Karyawan',
            'items' => $model->getPaginated($limit, $offset, $search),
            'search' => $search,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalData' => $totalData,
            'limit' => $limit
        ]);
    }

    public function create()
    {
        $this->authorize('employee_contract.create');

        $employeeModel = new Employee();

        activity_log(
            'HRIS - Kontrak Karyawan',
            'create_form',
            'Membuka form tambah kontrak karyawan'
        );

        $this->view('employee-contracts/create', [
            'title' => 'Tambah Kontrak Karyawan',
            'employees' => $employeeModel->all()
        ]);
    }

    public function store()
    {
        $this->authorize('employee_contract.create');

        $model = new EmployeeContract();

        $employeeId = $_POST['employee_id'] ?? null;

        if (!$employeeId) {

            activity_log(
                'HRIS - Kontrak Karyawan',
                'create_failed',
                'Gagal membuat kontrak karena karyawan belum dipilih'
            );

            $_SESSION['error'] = 'Karyawan wajib dipilih.';
            $this->redirect('employee-contracts-create');
        }

        $contractNumber = $model->generateNumber();

        $contractId = $model->create([
            'employee_id' => $employeeId,
            'contract_number' => $contractNumber,
            'contract_type' => $_POST['contract_type'] ?? 'contract',
            'start_date' => $_POST['start_date'] ?? null,
            'end_date' => $_POST['end_date'] ?? null,
            'salary' => (float) ($_POST['salary'] ?? 0),
            'job_title' => trim($_POST['job_title'] ?? ''),
            'work_location' => trim($_POST['work_location'] ?? ''),
            'status' => $_POST['status'] ?? 'active',
            'notes' => trim($_POST['notes'] ?? ''),
            'document_file' => null,
            'contract_pdf_url' => trim($_POST['contract_pdf_url'] ?? ''),
            'created_by' => $_SESSION['user_id'] ?? null
        ]);

        activity_log(
            'HRIS - Kontrak Karyawan',
            'create',
            'Membuat kontrak karyawan: ' . $contractNumber,
            $contractId,
            $contractNumber
        );

        $_SESSION['success'] = 'Kontrak karyawan berhasil dibuat.';

        $this->redirect('employee-contracts');
    }

    public function show()
    {
        $this->authorize('employee_contract.view');

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('employee-contracts');
        }

        $model = new EmployeeContract();

        $item = $model->find($id);

        if (!$item) {

            activity_log(
                'HRIS - Kontrak Karyawan',
                'view_failed',
                'Gagal membuka detail kontrak karena data tidak ditemukan',
                $id
            );

            $_SESSION['error'] = 'Kontrak tidak ditemukan.';
            $this->redirect('employee-contracts');
        }

        activity_log(
            'HRIS - Kontrak Karyawan',
            'view',
            'Melihat detail kontrak karyawan',
            $id,
            $item['contract_number'] ?? null
        );

        $this->view('employee-contracts/show', [
            'title' => 'Detail Kontrak Karyawan',
            'item' => $item
        ]);
    }

    public function edit()
    {
        $this->authorize('employee_contract.edit');

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('employee-contracts');
        }

        $model = new EmployeeContract();

        $item = $model->find($id);

        if (!$item) {

            activity_log(
                'HRIS - Kontrak Karyawan',
                'edit_failed',
                'Gagal membuka form edit kontrak karena data tidak ditemukan',
                $id
            );

            $_SESSION['error'] = 'Kontrak tidak ditemukan.';
            $this->redirect('employee-contracts');
        }

        $employeeModel = new Employee();

        activity_log(
            'HRIS - Kontrak Karyawan',
            'edit_form',
            'Membuka form edit kontrak karyawan',
            $id,
            $item['contract_number'] ?? null
        );

        $this->view('employee-contracts/edit', [
            'title' => 'Edit Kontrak Karyawan',
            'item' => $item,
            'employees' => $employeeModel->all()
        ]);
    }

    public function update()
    {
        $this->authorize('employee_contract.edit');

        $id = $_POST['id'] ?? null;

        if (!$id) {
            $this->redirect('employee-contracts');
        }

        $model = new EmployeeContract();

        $oldItem = $model->find($id);

        $model->update($id, [
            'employee_id' => $_POST['employee_id'] ?? null,
            'contract_type' => $_POST['contract_type'] ?? 'contract',
            'start_date' => $_POST['start_date'] ?? null,
            'end_date' => $_POST['end_date'] ?? null,
            'salary' => (float) ($_POST['salary'] ?? 0),
            'job_title' => trim($_POST['job_title'] ?? ''),
            'work_location' => trim($_POST['work_location'] ?? ''),
            'status' => $_POST['status'] ?? 'active',
            'notes' => trim($_POST['notes'] ?? ''),
            'document_file' => null,
            'contract_pdf_url' => trim($_POST['contract_pdf_url'] ?? '')
        ]);

        activity_log(
            'HRIS - Kontrak Karyawan',
            'update',
            'Mengubah kontrak karyawan',
            $id,
            $oldItem['contract_number'] ?? null
        );

        $_SESSION['success'] = 'Kontrak berhasil diperbarui.';

        $this->redirect('employee-contracts-show', ['id' => $id]);
    }

    public function delete()
    {
        $this->authorize('employee_contract.delete');

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('employee-contracts');
        }

        $model = new EmployeeContract();

        $item = $model->find($id);

        $model->delete($id);

        activity_log(
            'HRIS - Kontrak Karyawan',
            'delete',
            'Menghapus kontrak karyawan',
            $id,
            $item['contract_number'] ?? null
        );

        $_SESSION['success'] = 'Kontrak berhasil dihapus.';

        $this->redirect('employee-contracts');
    }

    public function print()
    {
        $this->authorize('employee_contract.print');

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('employee-contracts');
        }

        $model = new EmployeeContract();

        $item = $model->find($id);

        if (!$item) {

            activity_log(
                'HRIS - Kontrak Karyawan',
                'print_failed',
                'Gagal print kontrak karena data tidak ditemukan',
                $id
            );

            $_SESSION['error'] = 'Kontrak tidak ditemukan.';
            $this->redirect('employee-contracts');
        }

        activity_log(
            'HRIS - Kontrak Karyawan',
            'print',
            'Print kontrak karyawan',
            $id,
            $item['contract_number'] ?? null
        );

        require __DIR__ . '/../Views/employee-contracts/print.php';
    }
}