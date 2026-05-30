<?php

class UnitController extends Controller
{
    private function authorize()
    {
        if (!can_access(['super_admin', 'operasional'])) {

            activity_log(
                'Operasional - Unit',
                'access_denied',
                'Mencoba mengakses master unit tanpa izin'
            );

            $this->redirect('dashboard');
        }
    }

    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $this->authorize();

        $unitModel = new Unit();

        $limit = 10;
        $currentPage = isset($_GET['p']) ? (int) $_GET['p'] : 1;
        $currentPage = max($currentPage, 1);

        $totalData = $unitModel->countAll();
        $totalPages = ceil($totalData / $limit);

        $offset = ($currentPage - 1) * $limit;

        $units = $unitModel->getPaginated($limit, $offset);

        activity_log(
            'Operasional - Unit',
            'view',
            'Melihat daftar master unit'
        );

        $this->view('units/index', [
            'title' => 'Master Unit',
            'units' => $units,
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

        $brandModel = new Brand();
        $brands = $brandModel->getAll();

        activity_log(
            'Operasional - Unit',
            'create_form',
            'Membuka form tambah unit'
        );

        $this->view('units/create', [
            'title' => 'Tambah Unit',
            'brands' => $brands
        ]);
    }

    public function store()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $this->authorize();

        $kode = $_POST['kode_unit'] ?? '';
        $nama = $_POST['nama_unit'] ?? '';
        $tipe = $_POST['tipe_unit'] ?? '';
        $kategori = $_POST['kategori'] ?? '';
        $brand = $_POST['brand'] ?? '';

        $db = Database::connect();

        $stmt = $db->prepare("
            INSERT INTO units 
            (kode_unit, nama_unit, tipe_unit, kategori, brand, status_unit, lokasi_sekarang)
            VALUES (?, ?, ?, ?, ?, 'available', 'Gudang')
        ");

        $stmt->execute([
            $kode,
            $nama,
            $tipe,
            $kategori,
            $brand
        ]);

        $unitId = $db->lastInsertId();

        activity_log(
            'Operasional - Unit',
            'create',
            'Menambahkan unit: ' . ($kode ?: '-') . ' - ' . ($nama ?: '-'),
            $unitId,
            $kode ?: null
        );

        $this->redirect('units');
    }

    public function edit()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $this->authorize();

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('units');
        }

        $unitModel = new Unit();
        $brandModel = new Brand();

        $unit = $unitModel->find($id);
        $brands = $brandModel->getAll();

        if (!$unit) {

            activity_log(
                'Operasional - Unit',
                'edit_failed',
                'Gagal membuka form edit unit karena data tidak ditemukan',
                $id
            );

            $this->redirect('units');
        }

        activity_log(
            'Operasional - Unit',
            'edit_form',
            'Membuka form edit unit: ' . (($unit['kode_unit'] ?? '-') . ' - ' . ($unit['nama_unit'] ?? '-')),
            $id,
            $unit['kode_unit'] ?? null
        );

        $this->view('units/edit', [
            'title' => 'Edit Unit',
            'unit' => $unit,
            'brands' => $brands
        ]);
    }

    public function update()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $this->authorize();

        $id = $_POST['id'] ?? null;

        if (!$id) {
            $this->redirect('units');
        }

        $data = [
            'kode_unit' => $_POST['kode_unit'] ?? '',
            'nama_unit' => $_POST['nama_unit'] ?? '',
            'tipe_unit' => $_POST['tipe_unit'] ?? '',
            'kategori' => $_POST['kategori'] ?? '',
            'brand' => $_POST['brand'] ?? '',
            'kapasitas' => $_POST['kapasitas'] ?? '',
            'status_unit' => $_POST['status_unit'] ?? 'available',
            'lokasi_sekarang' => $_POST['lokasi_sekarang'] ?? 'Gudang',
        ];

        $unitModel = new Unit();

        $oldUnit = $unitModel->find($id);

        $unitModel->update($id, $data);

        activity_log(
            'Operasional - Unit',
            'update',
            'Mengubah unit: ' . (($data['kode_unit'] ?? '-') . ' - ' . ($data['nama_unit'] ?? '-')),
            $id,
            $data['kode_unit'] ?? ($oldUnit['kode_unit'] ?? null)
        );

        $this->redirect('units');
    }

    public function delete()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $this->authorize();

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('units');
        }

        $unitModel = new Unit();

        $unit = $unitModel->find($id);

        $unitModel->delete($id);

        activity_log(
            'Operasional - Unit',
            'delete',
            'Menghapus unit: ' . (($unit['kode_unit'] ?? '-') . ' - ' . ($unit['nama_unit'] ?? '-')),
            $id,
            $unit['kode_unit'] ?? null
        );

        $this->redirect('units');
    }
}
