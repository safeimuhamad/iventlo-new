<?php

class DepartmentController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $p = max(1, (int) ($_GET['p'] ?? 1));
        $limit = 10;
        $offset = ($p - 1) * $limit;

        $model = new Department();

        $totalRows = $model->countAll();
        $totalPages = ceil($totalRows / $limit);

        activity_log(
            'HRIS - Divisi',
            'view',
            'Melihat daftar divisi'
        );

        $this->view('departments/index', [
            'title' => 'Divisi',
            'departments' => $model->paginate($limit, $offset),
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
            'HRIS - Divisi',
            'create_form',
            'Membuka form tambah divisi'
        );

        $this->view('departments/create', [
            'title' => 'Tambah Divisi'
        ]);
    }

    public function store()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        if (empty($_POST['name'])) {
            echo "Nama divisi wajib diisi.";
            exit;
        }

        $model = new Department();

        $departmentId = $model->create([
            'name' => $_POST['name'],
            'description' => $_POST['description'] ?? '',
            'status' => $_POST['status'] ?? 'active'
        ]);

        activity_log(
            'HRIS - Divisi',
            'create',
            'Menambahkan divisi: ' . $_POST['name'],
            $departmentId,
            $_POST['name']
        );

        $this->redirect('departments');
    }

    public function edit()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('departments');
        }

        $model = new Department();
        $department = $model->find($id);

        if (!$department) {
            $this->redirect('departments');
        }

        activity_log(
            'HRIS - Divisi',
            'edit_form',
            'Membuka form edit divisi: ' . ($department['name'] ?? '-'),
            $id,
            $department['name'] ?? null
        );

        $this->view('departments/edit', [
            'title' => 'Edit Divisi',
            'department' => $department
        ]);
    }

    public function update()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_POST['id'] ?? null;

        if (!$id) {
            $this->redirect('departments');
        }

        if (empty($_POST['name'])) {
            echo "Nama divisi wajib diisi.";
            exit;
        }

        $model = new Department();

        $oldDepartment = $model->find($id);

        $model->update($id, [
            'name' => $_POST['name'],
            'description' => $_POST['description'] ?? '',
            'status' => $_POST['status'] ?? 'active'
        ]);

        activity_log(
            'HRIS - Divisi',
            'update',
            'Mengubah divisi: ' . $_POST['name'],
            $id,
            $_POST['name'] ?? ($oldDepartment['name'] ?? null)
        );

        $this->redirect('departments');
    }

    public function delete()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('departments');
        }

        $model = new Department();
        $department = $model->find($id);

        $model->delete($id);

        activity_log(
            'HRIS - Divisi',
            'delete',
            'Menghapus divisi: ' . ($department['name'] ?? 'ID #' . $id),
            $id,
            $department['name'] ?? null
        );

        $this->redirect('departments');
    }
}