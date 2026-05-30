<?php

class ProductController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $search = trim($_GET['search'] ?? '');

        $model = new Product();

        $limit = 10;
        $currentPage = isset($_GET['p']) ? (int) $_GET['p'] : 1;
        $currentPage = max($currentPage, 1);

        $totalData = $model->countAll($search);
        $totalPages = ceil($totalData / $limit);
        $offset = ($currentPage - 1) * $limit;

        activity_log(
            'Master - Produk & Jasa',
            'view',
            'Melihat daftar produk & jasa'
        );

        $this->view('products-service/index', [
            'title' => 'Master Produk & Jasa',
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
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        activity_log(
            'Master - Produk & Jasa',
            'create_form',
            'Membuka form tambah produk & jasa'
        );

        $this->view('products-service/create', [
            'title' => 'Tambah Produk & Jasa'
        ]);
    }

    public function store()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $data = $this->payload();

        $model = new Product();

        $productId = $model->create($data);

        activity_log(
            'Master - Produk & Jasa',
            'create',
            'Menambahkan produk/jasa: ' . ($data['name'] ?: '-'),
            $productId,
            $data['name'] ?? null
        );

        $this->redirect('products-service');
    }

    public function edit()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('products-service');
        }

        $model = new Product();
        $item = $model->find($id);

        if (!$item) {

            activity_log(
                'Master - Produk & Jasa',
                'edit_failed',
                'Gagal membuka form edit produk/jasa karena data tidak ditemukan',
                $id
            );

            $this->redirect('products-service');
        }

        activity_log(
            'Master - Produk & Jasa',
            'edit_form',
            'Membuka form edit produk/jasa: ' . ($item['name'] ?? '-'),
            $id,
            $item['name'] ?? null
        );

        $this->view('products-service/edit', [
            'title' => 'Edit Produk & Jasa',
            'item' => $item
        ]);
    }

    public function update()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_POST['id'] ?? null;

        if (!$id) {
            $this->redirect('products-service');
        }

        $model = new Product();

        $oldItem = $model->find($id);

        if (!$oldItem) {

            activity_log(
                'Master - Produk & Jasa',
                'update_failed',
                'Gagal update produk/jasa karena data tidak ditemukan',
                $id
            );

            $this->redirect('products-service');
        }

        $data = $this->payload();

        $model->update($id, $data);

        activity_log(
            'Master - Produk & Jasa',
            'update',
            'Mengubah produk/jasa: ' . ($data['name'] ?: '-'),
            $id,
            $data['name'] ?? ($oldItem['name'] ?? null)
        );

        $this->redirect('products-service');
    }

    public function delete()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if ($id) {
            $model = new Product();
            $item = $model->find($id);

            $model->delete($id);

            activity_log(
                'Master - Produk & Jasa',
                'delete',
                'Menghapus produk/jasa: ' . ($item['name'] ?? 'ID #' . $id),
                $id,
                $item['name'] ?? null
            );
        }

        $this->redirect('products-service');
    }

    private function payload()
    {
        return [
            'name' => trim($_POST['name'] ?? ''),
            'category' => trim($_POST['category'] ?? ''),
            'item_type' => $_POST['item_type'] ?? 'service',
            'unit_name' => trim($_POST['unit_name'] ?? 'unit'),
            'default_period_type' => $_POST['default_period_type'] ?? 'unit',

            'daily_price' => (float) ($_POST['daily_price'] ?? 0),
            'weekly_price' => (float) ($_POST['weekly_price'] ?? 0),
            'monthly_price' => (float) ($_POST['monthly_price'] ?? 0),

            'unit_price' => (float) ($_POST['unit_price'] ?? 0),
            'meter_price' => (float) ($_POST['meter_price'] ?? 0),
            'package_price' => (float) ($_POST['package_price'] ?? 0),

            'description' => trim($_POST['description'] ?? ''),
            'status' => $_POST['status'] ?? 'active',
        ];
    }
}