<?php

class VehicleUsageLogController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $model = new VehicleUsageLog();

        activity_log(
            'Operasional - Log Kendaraan',
            'view',
            'Melihat log pemakaian kendaraan'
        );

        $this->view('vehicle-usage-logs/index', [
            'title' => 'Log Pemakaian Kendaraan',
            'items' => $model->all()
        ]);
    }

    public function create()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $model = new VehicleUsageLog();

        activity_log(
            'Operasional - Log Kendaraan',
            'create_form',
            'Membuka form tambah log pemakaian kendaraan'
        );

        $this->view('vehicle-usage-logs/create', [
            'title' => 'Tambah Log Pemakaian Kendaraan',
            'vehicles' => $model->vehicles()
        ]);
    }

    public function store()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $model = new VehicleUsageLog();

        $logId = $model->create($_POST);

        activity_log(
            'Operasional - Log Kendaraan',
            'create',
            'Menambahkan log pemakaian kendaraan',
            $logId,
            $_POST['usage_date'] ?? null
        );

        $this->redirect('vehicle-usage-logs');
    }

    public function show()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;
        $item = $id ? (new VehicleUsageLog())->find($id) : null;

        if (!$item) {
            $_SESSION['error'] = 'Log pemakaian kendaraan tidak ditemukan.';
            $this->redirect('vehicle-usage-logs');
        }

        activity_log(
            'Operasional - Log Kendaraan',
            'view_detail',
            'Melihat detail log pemakaian kendaraan',
            $id,
            $item['vehicle_code'] ?? null
        );

        $this->view('vehicle-usage-logs/show', [
            'title' => 'Detail Log Pemakaian Kendaraan',
            'item' => $item
        ]);
    }
}
