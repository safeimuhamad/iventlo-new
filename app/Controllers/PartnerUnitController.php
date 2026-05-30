<?php

class PartnerUnitController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $model = new PartnerUnit();

        $limit = 10;
        $currentPage = isset($_GET['p']) ? (int) $_GET['p'] : 1;
        $currentPage = max($currentPage, 1);

        $totalData = $model->countAll();
        $totalPages = ceil($totalData / $limit);
        $offset = ($currentPage - 1) * $limit;

        $partnerUnits = $model->getPaginated($limit, $offset);

        activity_log(
            'Operasional - Unit Vendor',
            'view',
            'Melihat daftar unit vendor'
        );

        $this->view('partner-units/index', [
            'title' => 'Master Unit Vendor',
            'partnerUnits' => $partnerUnits,
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

        $partnerModel = new Partner();
        $brandModel = new Brand();

        $partners = $partnerModel->getAll();
        $brands = $brandModel->getAll();

        activity_log(
            'Operasional - Unit Vendor',
            'create_form',
            'Membuka form tambah unit vendor'
        );

        $this->view('partner-units/create', [
            'title' => 'Tambah Unit Vendor',
            'partners' => $partners,
            'brands' => $brands
        ]);
    }

    public function store()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $data = [
            'partner_id' => $_POST['partner_id'] ?? '',
            'unit_name' => $_POST['unit_name'] ?? '',
            'category' => $_POST['category'] ?? '',
            'brand' => $_POST['brand'] ?? '',
            'capacity' => $_POST['capacity'] ?? '',
            'rental_cost' => $_POST['rental_cost'] ?? 0,
            'status' => $_POST['status'] ?? 'active',
        ];

        $model = new PartnerUnit();
        $partnerUnitId = $model->create($data);

        activity_log(
            'Operasional - Unit Vendor',
            'create',
            'Menambahkan unit vendor: ' . ($data['unit_name'] ?: '-'),
            $partnerUnitId,
            $data['unit_name'] ?? null
        );

        $this->redirect('partner-units');
    }

    public function edit()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;
        $model = new PartnerUnit();
        $unit = $id ? $model->find($id) : null;

        if (!$unit) {
            $_SESSION['error'] = 'Unit vendor tidak ditemukan.';
            $this->redirect('partner-units');
        }

        $partners = (new Partner())->getAll();
        $brands = (new Brand())->getAll();

        activity_log(
            'Operasional - Unit Vendor',
            'edit_form',
            'Membuka form edit unit vendor: ' . ($unit['unit_name'] ?? '-'),
            $id,
            $unit['unit_name'] ?? null
        );

        $this->view('partner-units/edit', [
            'title' => 'Edit Unit Vendor',
            'unit' => $unit,
            'partners' => $partners,
            'brands' => $brands
        ]);
    }

    public function update()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_POST['id'] ?? null;
        $model = new PartnerUnit();
        $oldUnit = $id ? $model->find($id) : null;

        if (!$oldUnit) {
            $_SESSION['error'] = 'Unit vendor tidak ditemukan.';
            $this->redirect('partner-units');
        }

        $data = [
            'partner_id' => $_POST['partner_id'] ?? '',
            'unit_name' => trim($_POST['unit_name'] ?? ''),
            'category' => $_POST['category'] ?? '',
            'brand' => $_POST['brand'] ?? '',
            'capacity' => trim($_POST['capacity'] ?? ''),
            'rental_cost' => (float) ($_POST['rental_cost'] ?? 0),
            'status' => $_POST['status'] ?? 'active',
        ];

        $model->update($id, $data);

        activity_log(
            'Operasional - Unit Vendor',
            'update',
            'Mengubah unit vendor: ' . ($data['unit_name'] ?: '-'),
            $id,
            $data['unit_name'] ?: ($oldUnit['unit_name'] ?? null)
        );

        $_SESSION['success'] = 'Unit vendor berhasil diperbarui.';
        $this->redirect('partner-units');
    }
}
