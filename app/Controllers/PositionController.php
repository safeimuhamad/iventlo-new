<?php

class PositionController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $p = max(1, (int) ($_GET['p'] ?? 1));
        $limit = 10;
        $offset = ($p - 1) * $limit;

        $model = new Position();

        $totalRows = $model->countAll();
        $totalPages = ceil($totalRows / $limit);

        activity_log(
            'HRIS - Jabatan',
            'view',
            'Melihat daftar jabatan'
        );

        $this->view('positions/index', [
            'title' => 'Jabatan',
            'positions' => $model->paginate($limit, $offset),
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

        activity_log(
            'HRIS - Jabatan',
            'create_form',
            'Membuka form tambah jabatan'
        );

        $this->view('positions/create', [
            'title' => 'Tambah Jabatan',
            'departments' => $departmentModel->getActive()
        ]);
    }

    public function store()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        if (empty($_POST['name'])) {

            activity_log(
                'HRIS - Jabatan',
                'create_failed',
                'Gagal menambahkan jabatan karena nama kosong'
            );

            echo "Nama jabatan wajib diisi.";
            exit;
        }

        $model = new Position();

        $positionId = $model->create([
            'department_id' => $_POST['department_id'] ?? null,
            'name' => $_POST['name'],
            'description' => $_POST['description'] ?? '',
            'status' => $_POST['status'] ?? 'active'
        ]);

        activity_log(
            'HRIS - Jabatan',
            'create',
            'Menambahkan jabatan: ' . $_POST['name'],
            $positionId,
            $_POST['name'] ?? null
        );

        $this->redirect('positions');
    }

    public function edit()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('positions');
        }

        $model = new Position();

        $position = $model->find($id);

        if (!$position) {

            activity_log(
                'HRIS - Jabatan',
                'edit_failed',
                'Gagal membuka form edit jabatan karena data tidak ditemukan',
                $id
            );

            $this->redirect('positions');
        }

        $departmentModel = new Department();

        activity_log(
            'HRIS - Jabatan',
            'edit_form',
            'Membuka form edit jabatan: ' . ($position['name'] ?? '-'),
            $id,
            $position['name'] ?? null
        );

        $this->view('positions/edit', [
            'title' => 'Edit Jabatan',
            'position' => $position,
            'departments' => $departmentModel->getActive()
        ]);
    }

    public function update()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_POST['id'] ?? null;

        if (!$id) {
            $this->redirect('positions');
        }

        if (empty($_POST['name'])) {

            activity_log(
                'HRIS - Jabatan',
                'update_failed',
                'Gagal mengubah jabatan karena nama kosong',
                $id
            );

            echo "Nama jabatan wajib diisi.";
            exit;
        }

        $model = new Position();

        $oldPosition = $model->find($id);

        $model->update($id, [
            'department_id' => $_POST['department_id'] ?? null,
            'name' => $_POST['name'],
            'description' => $_POST['description'] ?? '',
            'status' => $_POST['status'] ?? 'active'
        ]);

        activity_log(
            'HRIS - Jabatan',
            'update',
            'Mengubah jabatan: ' . $_POST['name'],
            $id,
            $_POST['name'] ?? ($oldPosition['name'] ?? null)
        );

        $this->redirect('positions');
    }

    public function delete()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('positions');
        }

        $model = new Position();
        $position = $model->find($id);

        $model->delete($id);

        activity_log(
            'HRIS - Jabatan',
            'delete',
            'Menghapus jabatan: ' . ($position['name'] ?? 'ID #' . $id),
            $id,
            $position['name'] ?? null
        );

        $this->redirect('positions');
    }
}