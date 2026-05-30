<?php

class VehicleMaintenanceController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $model = new VehicleMaintenance();

        activity_log(
            'Operasional - Service Kendaraan',
            'view',
            'Melihat daftar service due kendaraan'
        );

        $this->view('vehicle-maintenances/index', [
            'title' => 'Service Due Kendaraan',
            'items' => $model->dueVehicles()
        ]);
    }

    public function process()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $vehicleId = $_GET['vehicle_id'] ?? null;

        if (!$vehicleId) {
            $this->redirect('vehicle-maintenances-due');
        }

        $model = new VehicleMaintenance();

        $model->setProcess($vehicleId);

        $vehicle = $model->findVehicle($vehicleId);

        activity_log(
            'Operasional - Service Kendaraan',
            'process',
            'Memproses service kendaraan: ' . (($vehicle['vehicle_name'] ?? '-') . ' / ' . ($vehicle['plate_number'] ?? '-')),
            $vehicleId,
            $vehicle['plate_number'] ?? null
        );

        $this->view('vehicle-maintenances/process', [
            'title' => 'Proses Service Kendaraan',
            'vehicle' => $vehicle,
            'checklists' => $model->defaultChecklists()
        ]);
    }

    public function store()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $model = new VehicleMaintenance();

        $vehicleId = $_POST['vehicle_id'] ?? null;
        $vehicle = $vehicleId ? $model->findVehicle($vehicleId) : null;

        $model->store($_POST, $_FILES);

        activity_log(
            'Operasional - Service Kendaraan',
            'store',
            'Menyimpan hasil service kendaraan: ' . (($vehicle['vehicle_name'] ?? '-') . ' / ' . ($vehicle['plate_number'] ?? '-')),
            $vehicleId,
            $vehicle['plate_number'] ?? null
        );

        $this->redirect('vehicle-maintenances');
    }

    public function history()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $model = new VehicleMaintenance();

        activity_log(
            'Operasional - Service Kendaraan',
            'view',
            'Melihat history service kendaraan'
        );

        $this->view('vehicle-maintenances/history', [
            'title' => 'History Service Kendaraan',
            'items' => $model->history()
        ]);
    }

    public function show()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('vehicle-maintenances-history');
        }

        $model = new VehicleMaintenance();

        $item = $model->findMaintenance($id);

        activity_log(
            'Operasional - Service Kendaraan',
            'view',
            'Melihat detail service kendaraan: ' . (($item['vehicle_name'] ?? '-') . ' / ' . ($item['plate_number'] ?? '-')),
            $id,
            $item['plate_number'] ?? null
        );

        $this->view('vehicle-maintenances/show', [
            'title' => 'Detail Service Kendaraan',
            'item' => $item,
            'checklists' => $model->getChecklists($id),
            'documents' => $model->getDocuments($id)
        ]);
    }

    public function report()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $startDate = $_GET['start_date'] ?? null;
        $endDate = $_GET['end_date'] ?? null;

        $model = new VehicleMaintenance();

        activity_log(
            'Operasional - Service Kendaraan',
            'report',
            'Melihat report service kendaraan periode: ' . (($startDate ?: '-') . ' s/d ' . ($endDate ?: '-'))
        );

        $this->view('vehicle-maintenances/report', [
            'title' => 'Report Service Kendaraan',
            'items' => $model->report($startDate, $endDate),
            'summary' => $model->reportSummary($startDate, $endDate),
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }
}