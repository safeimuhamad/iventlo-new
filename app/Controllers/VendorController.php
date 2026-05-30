<?php

class VendorController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $model = new Vendor();

        activity_log(
            'Finance - Vendor',
            'view',
            'Melihat daftar vendor'
        );

        $this->view('vendors/index', [
            'title' => 'Vendor',
            'vendors' => $model->getAll()
        ]);
    }

    public function create()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $model = new Vendor();

        activity_log(
            'Finance - Vendor',
            'create_form',
            'Membuka form tambah vendor'
        );

        $this->view('vendors/create', [
            'title' => 'Tambah Vendor',
            'vendorCode' => $model->generateCode()
        ]);
    }

    public function store()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $model = new Vendor();

        $vendorCode = $_POST['vendor_code'] ?? $model->generateCode();

        $vendorId = $model->create([
            'vendor_code' => $vendorCode,
            'vendor_name' => $_POST['vendor_name'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'email' => $_POST['email'] ?? '',
            'address' => $_POST['address'] ?? '',
            'npwp' => $_POST['npwp'] ?? '',
            'pic_name' => $_POST['pic_name'] ?? '',
            'notes' => $_POST['notes'] ?? '',
        ]);

        activity_log(
            'Finance - Vendor',
            'create',
            'Menambahkan vendor: ' . ($_POST['vendor_name'] ?? '-'),
            $vendorId,
            $vendorCode
        );

        $this->redirect('vendors');
    }

    public function edit()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('vendors');
        }

        $model = new Vendor();
        $vendor = $model->find($id);

        if (!$vendor) {

            activity_log(
                'Finance - Vendor',
                'edit_failed',
                'Gagal membuka form edit vendor karena data tidak ditemukan',
                $id
            );

            $this->redirect('vendors');
        }

        activity_log(
            'Finance - Vendor',
            'edit_form',
            'Membuka form edit vendor: ' . ($vendor['vendor_name'] ?? '-'),
            $id,
            $vendor['vendor_code'] ?? null
        );

        $this->view('vendors/edit', [
            'title' => 'Edit Vendor',
            'vendor' => $vendor
        ]);
    }

    public function update()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_POST['id'] ?? null;

        if (!$id) {
            $this->redirect('vendors');
        }

        $model = new Vendor();

        $oldVendor = $model->find($id);

        if (!$oldVendor) {

            activity_log(
                'Finance - Vendor',
                'update_failed',
                'Gagal update vendor karena data tidak ditemukan',
                $id
            );

            $this->redirect('vendors');
        }

        $model->update($id, [
            'vendor_name' => $_POST['vendor_name'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'email' => $_POST['email'] ?? '',
            'address' => $_POST['address'] ?? '',
            'npwp' => $_POST['npwp'] ?? '',
            'pic_name' => $_POST['pic_name'] ?? '',
            'notes' => $_POST['notes'] ?? '',
        ]);

        activity_log(
            'Finance - Vendor',
            'update',
            'Mengubah vendor: ' . ($_POST['vendor_name'] ?? '-'),
            $id,
            $oldVendor['vendor_code'] ?? null
        );

        $this->redirect('vendors');
    }

    public function delete()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if ($id) {

            $model = new Vendor();

            $vendor = $model->find($id);

            $model->softDelete($id);

            activity_log(
                'Finance - Vendor',
                'delete',
                'Menghapus vendor: ' . ($vendor['vendor_name'] ?? '-'),
                $id,
                $vendor['vendor_code'] ?? null
            );
        }

        $this->redirect('vendors');
    }
}