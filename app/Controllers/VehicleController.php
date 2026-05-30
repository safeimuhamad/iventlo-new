<?php

class VehicleController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $model = new Vehicle();

        activity_log(
            'Operasional - Kendaraan',
            'view',
            'Melihat daftar kendaraan'
        );

        $this->view('vehicles/index', [
            'title' => 'Data Kendaraan',
            'items' => $model->all()
        ]);
    }

    public function create()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        activity_log(
            'Operasional - Kendaraan',
            'create_form',
            'Membuka form tambah kendaraan'
        );

        $this->view('vehicles/create', [
            'title' => 'Tambah Kendaraan'
        ]);
    }

    public function store()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $model = new Vehicle();

        $vehicleId = $model->create($_POST);

        activity_log(
            'Operasional - Kendaraan',
            'create',
            'Menambahkan kendaraan: ' . ($_POST['vehicle_name'] ?? $_POST['plate_number'] ?? '-'),
            $vehicleId,
            $_POST['plate_number'] ?? null
        );

        $this->redirect('vehicles');
    }

    public function edit()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('vehicles');
        }

        $model = new Vehicle();

        $item = $model->find($id);

        if (!$item) {

            activity_log(
                'Operasional - Kendaraan',
                'edit_failed',
                'Gagal membuka form edit kendaraan karena data tidak ditemukan',
                $id
            );

            $this->redirect('vehicles');
        }

        activity_log(
            'Operasional - Kendaraan',
            'edit_form',
            'Membuka form edit kendaraan: ' . (($item['vehicle_name'] ?? '-') . ' / ' . ($item['plate_number'] ?? '-')),
            $id,
            $item['plate_number'] ?? null
        );

        $this->view('vehicles/edit', [
            'title' => 'Edit Kendaraan',
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
            $this->redirect('vehicles');
        }

        $model = new Vehicle();

        $oldItem = $model->find($id);

        if (!$oldItem) {

            activity_log(
                'Operasional - Kendaraan',
                'update_failed',
                'Gagal update kendaraan karena data tidak ditemukan',
                $id
            );

            $this->redirect('vehicles');
        }

        $model->update($id, $_POST);

        activity_log(
            'Operasional - Kendaraan',
            'update',
            'Mengubah kendaraan: ' . (($_POST['vehicle_name'] ?? $oldItem['vehicle_name'] ?? '-') . ' / ' . ($_POST['plate_number'] ?? $oldItem['plate_number'] ?? '-')),
            $id,
            $_POST['plate_number'] ?? ($oldItem['plate_number'] ?? null)
        );

        $this->redirect('vehicles');
    }

    public function delete()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if ($id) {

            $model = new Vehicle();

            $item = $model->find($id);

            $model->delete($id);

            activity_log(
                'Operasional - Kendaraan',
                'delete',
                'Menghapus kendaraan: ' . (($item['vehicle_name'] ?? '-') . ' / ' . ($item['plate_number'] ?? '-')),
                $id,
                $item['plate_number'] ?? null
            );
        }

        $this->redirect('vehicles');
    }

    public function reminders()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $model = new Vehicle();

        activity_log(
            'Operasional - Kendaraan',
            'reminder',
            'Melihat reminder STNK & pajak kendaraan'
        );

        $this->view('vehicles/reminders', [
            'title' => 'Reminder STNK & Pajak',
            'items' => $model->reminders()
        ]);
    }
}