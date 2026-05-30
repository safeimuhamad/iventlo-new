<?php

class TechnicianController extends Controller
{

    private function authorize()
    {
        if (!can_access(['super_admin', 'operasional'])) {
            $this->redirect('dashboard');
        }
    }

    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $model = new Technician();

        $limit = 10;
        $currentPage = isset($_GET['p']) ? (int) $_GET['p'] : 1;
        $currentPage = max($currentPage, 1);

        $totalData = $model->countAll();
        $totalPages = ceil($totalData / $limit);
        $offset = ($currentPage - 1) * $limit;

        $this->view('technicians/index', [
            'title' => 'Master Teknisi',
            'technicians' => $model->getPaginated($limit, $offset),
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
        $this->authorize();

        $this->view('technicians/create', [
            'title' => 'Tambah Teknisi'
        ]);
    }

    public function store()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        $this->authorize();

        $data = $this->payload();

        if ($data['name'] === '') {
            $_SESSION['error'] = 'Nama teknisi wajib diisi.';
            $this->redirect('technicians-create');
        }

        $id = (new Technician())->create($data);

        activity_log(
            'Operasional - Teknisi',
            'create',
            'Menambahkan teknisi: ' . $data['name'],
            $id,
            $data['name']
        );

        $_SESSION['success'] = 'Teknisi berhasil ditambahkan.';
        $this->redirect('technicians');
    }

    public function edit()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        $this->authorize();

        $id = $_GET['id'] ?? null;
        $technician = $id ? (new Technician())->find($id) : null;

        if (!$technician) {
            $_SESSION['error'] = 'Teknisi tidak ditemukan.';
            $this->redirect('technicians');
        }

        $this->view('technicians/edit', [
            'title' => 'Edit Teknisi',
            'technician' => $technician
        ]);
    }

    public function update()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        $this->authorize();

        $id = $_POST['id'] ?? null;
        $model = new Technician();
        $oldTechnician = $id ? $model->find($id) : null;

        if (!$oldTechnician) {
            $_SESSION['error'] = 'Teknisi tidak ditemukan.';
            $this->redirect('technicians');
        }

        $data = $this->payload();

        if ($data['name'] === '') {
            $_SESSION['error'] = 'Nama teknisi wajib diisi.';
            $this->redirect('technicians-edit', ['id' => $id]);
        }

        $model->update($id, $data);

        activity_log(
            'Operasional - Teknisi',
            'update',
            'Mengubah teknisi: ' . $data['name'],
            $id,
            $data['name'] ?: ($oldTechnician['name'] ?? null)
        );

        $_SESSION['success'] = 'Teknisi berhasil diperbarui.';
        $this->redirect('technicians');
    }

    public function schedules()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        $this->authorize();

        $date = $_GET['date'] ?? date('Y-m-d');

        $model = new Technician();

        $available = $model->getAvailableByDate($date);
        $assigned = $model->getAssignedByDate($date);

        $this->view('technicians/schedules', [
            'title' => 'Jadwal Teknisi',
            'date' => $date,
            'available' => $available,
            'assigned' => $assigned
        ]);
    }

    private function payload()
    {
        return [
            'name' => trim($_POST['name'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'role_type' => trim($_POST['role_type'] ?? 'Teknisi'),
            'status' => $_POST['status'] ?? 'active'
        ];
    }

}
