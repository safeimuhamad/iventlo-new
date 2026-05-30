<?php

class ApprovalMatrixController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        if (!can('approval_matrix.view')) {
            die('Akses ditolak');
        }
        $search = $_GET['search'] ?? '';
        $p = max(1, (int) ($_GET['p'] ?? 1));
        $limit = 10;
        $offset = ($p - 1) * $limit;

        $model = new ApprovalMatrix();

        $totalRows = $model->countAll($search);
        $totalPages = ceil($totalRows / $limit);

        activity_log(
            'Approval Matrix',
            'view',
            'Melihat daftar DOA Matrix'
        );

        $this->view('approval-matrices/index', [
            'title' => 'DOA Matrix',
            'approvalMatrices' => $model->paginate($search, $limit, $offset),
            'search' => $search,
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

        if (!can('approval_matrix.create')) {
            die('Akses ditolak');
        }
        $departmentModel = new Department();
        $roleModel = new Role();
        $userModel = new User();

        activity_log(
            'Approval Matrix',
            'create_form',
            'Membuka form tambah DOA Matrix'
        );

        $this->view('approval-matrices/create', [
            'title' => 'Tambah DOA Matrix',
            'departments' => $departmentModel->getActive(),
            'roles' => $roleModel->getAll(),
            'users' => $userModel->getAll()
        ]);
    }

    public function store()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        if (empty($_POST['module_name']) || empty($_POST['approval_level'])) {
            echo "Module dan approval level wajib diisi.";
            exit;
        }

        $model = new ApprovalMatrix();

        $model->create([
            'module_name' => $_POST['module_name'],
            'document_type' => $_POST['document_type'] ?? null,
            'min_amount' => $_POST['min_amount'] ?? 0,
            'max_amount' => $_POST['max_amount'] ?? null,
            'department_id' => $_POST['department_id'] ?? null,
            'approval_level' => $_POST['approval_level'] ?? 1,
            'approver_role_id' => $_POST['approver_role_id'] ?? null,
            'approver_user_id' => $_POST['approver_user_id'] ?? null,
            'is_active' => $_POST['is_active'] ?? 1
        ]);

        activity_log(
            'Approval Matrix',
            'create',
            'Menambahkan DOA Matrix untuk modul ' . ($_POST['module_name'] ?? '-')
        );

        $this->redirect('approval-matrices');
    }

    public function show()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        if (!can('approval_matrix.view')) {
            die('Akses ditolak');
        }
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('approval-matrices');
        }

        $model = new ApprovalMatrix();
        $approvalMatrix = $model->find($id);

        if (!$approvalMatrix) {
            $this->redirect('approval-matrices');
        }

        activity_log(
            'Approval Matrix',
            'view',
            'Melihat detail DOA Matrix: ' . ($approvalMatrix['module_name'] ?? '-'),
            $id
        );

        $this->view('approval-matrices/show', [
            'title' => 'Detail DOA Matrix',
            'approvalMatrix' => $approvalMatrix
        ]);
    }

    public function edit()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        if (!can('approval_matrix.edit')) {
            die('Akses ditolak');
        }
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('approval-matrices');
        }

        $model = new ApprovalMatrix();
        $approvalMatrix = $model->find($id);

        if (!$approvalMatrix) {
            $this->redirect('approval-matrices');
        }

        $departmentModel = new Department();
        $roleModel = new Role();
        $userModel = new User();

        activity_log(
            'Approval Matrix',
            'edit_form',
            'Membuka form edit DOA Matrix: ' . ($approvalMatrix['module_name'] ?? '-'),
            $id
        );

        $this->view('approval-matrices/edit', [
            'title' => 'Edit DOA Matrix',
            'approvalMatrix' => $approvalMatrix,
            'departments' => $departmentModel->getActive(),
            'roles' => $roleModel->getAll(),
            'users' => $userModel->getAll()
        ]);
    }

    public function update()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_POST['id'] ?? null;

        if (!$id) {
            $this->redirect('approval-matrices');
        }

        if (empty($_POST['module_name']) || empty($_POST['approval_level'])) {
            echo "Module dan approval level wajib diisi.";
            exit;
        }

        $model = new ApprovalMatrix();

        $model->update($id, [
            'module_name' => $_POST['module_name'],
            'document_type' => $_POST['document_type'] ?? null,
            'min_amount' => $_POST['min_amount'] ?? 0,
            'max_amount' => $_POST['max_amount'] ?? null,
            'department_id' => $_POST['department_id'] ?? null,
            'approval_level' => $_POST['approval_level'] ?? 1,
            'approver_role_id' => $_POST['approver_role_id'] ?? null,
            'approver_user_id' => $_POST['approver_user_id'] ?? null,
            'is_active' => $_POST['is_active'] ?? 1
        ]);

        activity_log(
            'Approval Matrix',
            'update',
            'Mengubah DOA Matrix untuk modul ' . ($_POST['module_name'] ?? '-'),
            $id
        );

        $this->redirect('approval-matrices');
    }

    public function delete()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        if (!can('approval_matrix.delete')) {
            die('Akses ditolak');
        }
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('approval-matrices');
        }

        $model = new ApprovalMatrix();
        $approvalMatrix = $model->find($id);

        $model->delete($id);

        activity_log(
            'Approval Matrix',
            'delete',
            'Menghapus DOA Matrix: ' . ($approvalMatrix['module_name'] ?? '-'),
            $id
        );

        $this->redirect('approval-matrices');
    }
}