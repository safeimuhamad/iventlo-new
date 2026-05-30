<?php

class UnitMaintenanceController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $model = new UnitMaintenance();

        activity_log(
            'Operasional - Maintenance Unit',
            'view',
            'Melihat daftar unit maintenance due'
        );

        $this->view('unit-maintenances/index', [
            'title' => 'Maintenance Due',
            'items' => $model->getDueUnits()
        ]);
    }

    public function process()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $unitId = $_GET['unit_id'] ?? null;

        if (!$unitId) {
            $this->redirect('unit-maintenance');
        }

        $model = new UnitMaintenance();

        $model->setProcess($unitId);

        $unit = $model->getUnit($unitId);

        if (!$unit) {

            activity_log(
                'Operasional - Maintenance Unit',
                'process_failed',
                'Gagal membuka proses maintenance karena unit tidak ditemukan',
                $unitId
            );

            $this->redirect('unit-maintenance');
        }

        activity_log(
            'Operasional - Maintenance Unit',
            'process',
            'Memproses maintenance unit: ' . (($unit['kode_unit'] ?? '-') . ' - ' . ($unit['nama_unit'] ?? '-')),
            $unitId,
            $unit['kode_unit'] ?? null
        );

        $this->view('unit-maintenances/process', [
            'title' => 'Proses Maintenance Unit',
            'unit' => $unit,
            'checklists' => $model->defaultChecklists()
        ]);
    }

    public function store()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $unitId = $_POST['unit_id'] ?? null;

        if (!$unitId) {
            $this->redirect('unit-maintenance-due');
        }

        $model = new UnitMaintenance();

        $unit = $model->getUnit($unitId);

        $model->storeMaintenance($unitId, $_POST, $_FILES);

        activity_log(
            'Operasional - Maintenance Unit',
            'store',
            'Menyimpan hasil maintenance unit: ' . (($unit['kode_unit'] ?? '-') . ' - ' . ($unit['nama_unit'] ?? '-')),
            $unitId,
            $unit['kode_unit'] ?? null
        );

        $this->redirect('unit-maintenance-history');
    }

    public function history()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $model = new UnitMaintenance();

        activity_log(
            'Operasional - Maintenance Unit',
            'view',
            'Melihat history maintenance unit'
        );

        $this->view('unit-maintenances/history', [
            'title' => 'History Maintenance',
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
            $this->redirect('unit-maintenance-history');
        }

        $model = new UnitMaintenance();

        $item = $model->findMaintenance($id);

        if (!$item) {

            activity_log(
                'Operasional - Maintenance Unit',
                'view_failed',
                'Gagal membuka detail maintenance karena data tidak ditemukan',
                $id
            );

            $this->redirect('unit-maintenance-history');
        }

        activity_log(
            'Operasional - Maintenance Unit',
            'view',
            'Melihat detail maintenance unit: ' . (($item['kode_unit'] ?? '-') . ' - ' . ($item['nama_unit'] ?? '-')),
            $id,
            $item['kode_unit'] ?? null
        );

        $this->view('unit-maintenances/show', [
            'title' => 'Detail Maintenance Unit',
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

        $model = new UnitMaintenance();

        activity_log(
            'Operasional - Maintenance Unit',
            'report',
            'Melihat report maintenance unit periode: ' . (($startDate ?: '-') . ' s/d ' . ($endDate ?: '-'))
        );

        $this->view('unit-maintenances/report', [
            'title' => 'Report Maintenance',
            'items' => $model->report($startDate, $endDate),
            'summary' => $model->reportSummary($startDate, $endDate),
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }
}