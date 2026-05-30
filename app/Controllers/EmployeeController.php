<?php

class EmployeeController extends Controller
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

        $model = new Employee();

        $totalRows = $model->countAll($search);
        $totalPages = ceil($totalRows / $limit);

        activity_log(
            'HRIS - Karyawan',
            'view',
            'Melihat daftar data karyawan'
        );

        $this->view('employees/index', [
            'title' => 'Data Karyawan',
            'employees' => $model->paginate($search, $limit, $offset),
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

        $departmentModel = new Department();
        $positionModel = new Position();

        activity_log(
            'HRIS - Karyawan',
            'create_form',
            'Membuka form tambah karyawan'
        );

        $this->view('employees/create', [
            'title' => 'Tambah Karyawan',
            'departments' => $departmentModel->getActive(),
            'positions' => $positionModel->getActive()
        ]);
    }

    public function store()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        if (empty($_POST['employee_code']) || empty($_POST['full_name'])) {

            activity_log(
                'HRIS - Karyawan',
                'create_failed',
                'Gagal menambahkan karyawan karena data wajib kosong'
            );

            echo "Kode karyawan dan nama lengkap wajib diisi.";
            exit;
        }

        $model = new Employee();

        $employeeId = $model->create([
            'employee_code' => $_POST['employee_code'],
            'full_name' => $_POST['full_name'],
            'nickname' => $_POST['nickname'] ?? '',
            'gender' => $_POST['gender'] ?? null,
            'birth_place' => $_POST['birth_place'] ?? '',
            'birth_date' => $_POST['birth_date'] ?? null,
            'phone' => $_POST['phone'] ?? '',
            'email' => $_POST['email'] ?? '',
            'address' => $_POST['address'] ?? '',
            'department_id' => $_POST['department_id'] ?? null,
            'position_id' => $_POST['position_id'] ?? null,
            'employment_status' => $_POST['employment_status'] ?? 'permanent',
            'join_date' => $_POST['join_date'] ?? null,
            'basic_salary' => (float) str_replace('.', '', $_POST['basic_salary'] ?? 0),
            'allowance_amount' => (float) str_replace('.', '', $_POST['allowance_amount'] ?? 0),
            'bank_name' => $_POST['bank_name'] ?? '',
            'bank_account_number' => $_POST['bank_account_number'] ?? '',
            'bank_account_name' => $_POST['bank_account_name'] ?? '',
            'status' => $_POST['status'] ?? 'active'
        ]);

        activity_log(
            'HRIS - Karyawan',
            'create',
            'Menambahkan data karyawan: ' . $_POST['full_name'],
            $employeeId,
            $_POST['employee_code'] ?? null
        );

        $this->redirect('employees');
    }

    public function edit()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('employees');
        }

        $model = new Employee();
        $employee = $model->find($id);

        if (!$employee) {

            activity_log(
                'HRIS - Karyawan',
                'edit_failed',
                'Gagal membuka form edit karyawan karena data tidak ditemukan',
                $id
            );

            $this->redirect('employees');
        }

        $departmentModel = new Department();
        $positionModel = new Position();

        activity_log(
            'HRIS - Karyawan',
            'edit_form',
            'Membuka form edit karyawan: ' . ($employee['full_name'] ?? '-'),
            $id,
            $employee['employee_code'] ?? null
        );

        $this->view('employees/edit', [
            'title' => 'Edit Karyawan',
            'employee' => $employee,
            'departments' => $departmentModel->getActive(),
            'positions' => $positionModel->getActive()
        ]);
    }

    public function update()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_POST['id'] ?? null;

        if (!$id) {
            $this->redirect('employees');
        }

        if (empty($_POST['employee_code']) || empty($_POST['full_name'])) {

            activity_log(
                'HRIS - Karyawan',
                'update_failed',
                'Gagal mengubah data karyawan karena data wajib kosong',
                $id
            );

            echo "Kode karyawan dan nama lengkap wajib diisi.";
            exit;
        }

        $model = new Employee();

        $oldEmployee = $model->find($id);

        $model->update($id, [
            'employee_code' => $_POST['employee_code'],
            'full_name' => $_POST['full_name'],
            'nickname' => $_POST['nickname'] ?? '',
            'gender' => $_POST['gender'] ?? null,
            'birth_place' => $_POST['birth_place'] ?? '',
            'birth_date' => $_POST['birth_date'] ?? null,
            'phone' => $_POST['phone'] ?? '',
            'email' => $_POST['email'] ?? '',
            'address' => $_POST['address'] ?? '',
            'department_id' => $_POST['department_id'] ?? null,
            'position_id' => $_POST['position_id'] ?? null,
            'employment_status' => $_POST['employment_status'] ?? 'permanent',
            'join_date' => $_POST['join_date'] ?? null,
            'basic_salary' => (float) str_replace('.', '', $_POST['basic_salary'] ?? 0),
            'bank_name' => $_POST['bank_name'] ?? '',
            'bank_account_number' => $_POST['bank_account_number'] ?? '',
            'bank_account_name' => $_POST['bank_account_name'] ?? '',
            'status' => $_POST['status'] ?? 'active'
        ]);

        activity_log(
            'HRIS - Karyawan',
            'update',
            'Mengubah data karyawan: ' . $_POST['full_name'],
            $id,
            $_POST['employee_code'] ?? ($oldEmployee['employee_code'] ?? null)
        );

        $this->redirect('employees');
    }

    public function show()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('employees');
        }

        $model = new Employee();

        $employee = $model->find($id);

        if (!$employee) {

            activity_log(
                'HRIS - Karyawan',
                'view_failed',
                'Gagal membuka detail karyawan karena data tidak ditemukan',
                $id
            );

            $this->redirect('employees');
        }

        $userModel = new User();

        $employeeUser = $userModel->findByEmployeeId($id);

        activity_log(
            'HRIS - Karyawan',
            'view',
            'Melihat detail karyawan: ' . ($employee['full_name'] ?? '-'),
            $id,
            $employee['employee_code'] ?? null
        );

        $this->view('employees/show', [
            'title' => 'Detail Karyawan',
            'employee' => $employee,
            'employeeUser' => $employeeUser
        ]);
    }

    public function delete()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('employees');
        }

        $model = new Employee();
        $employee = $model->find($id);

        $model->delete($id);

        activity_log(
            'HRIS - Karyawan',
            'delete',
            'Menghapus data karyawan: ' . ($employee['full_name'] ?? 'ID #' . $id),
            $id,
            $employee['employee_code'] ?? null
        );

        $this->redirect('employees');
    }

    public function createUserAccount()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('user.create');

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('employees');
        }

        $employeeModel = new Employee();
        $userModel = new User();

        $employee = $employeeModel->find($id);

        if (!$employee) {

            activity_log(
                'HRIS - Karyawan',
                'create_user_failed',
                'Gagal membuat akun ERP karena data karyawan tidak ditemukan',
                $id
            );

            $_SESSION['error'] = 'Data karyawan tidak ditemukan.';
            $this->redirect('employees');
        }

        if (empty($employee['email'])) {

            activity_log(
                'HRIS - Karyawan',
                'create_user_failed',
                'Gagal membuat akun ERP karena email karyawan kosong',
                $id,
                $employee['employee_code'] ?? null
            );

            $_SESSION['error'] = 'Email karyawan belum diisi.';
            $this->redirect('employees-edit', ['id' => $id]);
        }

        if ($userModel->findByEmail($employee['email'])) {

            activity_log(
                'HRIS - Karyawan',
                'create_user_failed',
                'Gagal membuat akun ERP karena email sudah digunakan',
                $id,
                $employee['employee_code'] ?? null
            );

            $_SESSION['error'] = 'User dengan email ini sudah ada.';
            $this->redirect('employees-edit', ['id' => $id]);
        }

        $token = bin2hex(random_bytes(32));

        $tempPassword = password_hash(
            bin2hex(random_bytes(16)),
            PASSWORD_DEFAULT
        );

        $username = explode('@', $employee['email'])[0] . rand(100, 999);

        $userModel->create([
            'name' => $employee['full_name'],
            'username' => $username,
            'email' => $employee['email'],
            'password' => $tempPassword,
            'role_id' => 9,
            'employee_id' => $employee['id'],
            'data_scope' => 'own',
            'status' => 'pending',
            'activation_token' => $token,
            'activation_expires_at' => date('Y-m-d H:i:s', strtotime('+24 hours'))
        ]);

        $activationLink = fullUrl('activate-account', [
            'token' => $token
        ]);

        $emailSent = Mailer::sendActivationEmail(
            $employee['email'],
            $employee['full_name'],
            $activationLink
        );

        activity_log(
            'HRIS - Karyawan',
            'create_user_account',
            'Membuat akun ERP untuk karyawan: ' . ($employee['full_name'] ?? '-'),
            $id,
            $employee['employee_code'] ?? null
        );

        $_SESSION['success'] = $emailSent
            ? 'Akun ERP berhasil dibuat dan email aktivasi berhasil dikirim ke email karyawan.'
            : 'Akun ERP berhasil dibuat, tetapi email aktivasi gagal dikirim.';

        $this->redirect('employees');
    }
}