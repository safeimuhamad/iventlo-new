<?php

class DeliveryOrderController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $search = trim($_GET['search'] ?? '');

        $limit = 10;
        $currentPage = max(1, (int) ($_GET['p'] ?? 1));
        $offset = ($currentPage - 1) * $limit;

        $model = new DeliveryOrder();

        $totalData = $model->countAll($search);
        $totalPages = ceil($totalData / $limit);

        $items = $model->paginate($search, $limit, $offset);

        activity_log(
            'Operasional - Surat Jalan',
            'view',
            'Melihat daftar surat jalan'
        );

        $this->view('delivery-orders/index', [
            'title' => 'Surat Jalan',
            'items' => $items,
            'search' => $search,
            'limit' => $limit,
            'currentPage' => $currentPage,
            'totalData' => $totalData,
            'totalPages' => $totalPages
        ]);
    }

    public function create()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $rentalModel = new Rental();

        $model = new DeliveryOrder();
        $vehicleModel = new Vehicle();

        activity_log(
            'Operasional - Surat Jalan',
            'create_form',
            'Membuka form tambah surat jalan'
        );

        $this->view('delivery-orders/create', [
            'title' => 'Tambah Surat Jalan',
            'rentals' => $rentalModel->getWithoutDeliveryOrder(),
            'nomor' => $model->generateNumber(),
            'vehicles' => $vehicleModel->all()
        ]);
    }

    public function store()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $model = new DeliveryOrder();

        $rentalModel = new Rental();
        $rental = $rentalModel->find($_POST['rental_id']);

        $sjType = 'pasang';

        if (($rental['status_rental'] ?? '') === 'on_rent') {
            $sjType = 'bongkar';
        }

        $deliveryOrderId = $model->create([
            'rental_id' => $_POST['rental_id'],
            'no_surat_jalan' => $_POST['no_surat_jalan'],
            'sj_type' => $sjType,
            'tanggal_kirim' => $_POST['tanggal_kirim'],
            'jam_kirim' => $_POST['jam_kirim'],
            'vehicle_id' => $_POST['vehicle_id'] ?? null,
            'driver_name' => $_POST['driver_name'] ?? null,
            'km_start' => $_POST['km_start'] ?? 0,
            'catatan' => $_POST['catatan'] ?? '',
            'status_sj' => 'draft'
        ]);

        activity_log(
            'Operasional - Surat Jalan',
            'create',
            'Membuat surat jalan: ' . ($_POST['no_surat_jalan'] ?? '-'),
            $deliveryOrderId,
            $_POST['no_surat_jalan'] ?? null
        );

        $this->redirect('delivery-orders');
    }

    public function print()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('delivery-orders');
        }

        $model = new DeliveryOrder();

        $deliveryOrder = $model->find($id);

        if (!$deliveryOrder) {
            $this->redirect('delivery-orders');
        }

        $items = $model->getItems($deliveryOrder['rental_id']);

        activity_log(
            'Operasional - Surat Jalan',
            'print',
            'Print surat jalan: ' . ($deliveryOrder['no_surat_jalan'] ?? '-'),
            $id,
            $deliveryOrder['no_surat_jalan'] ?? null
        );

        require __DIR__ . '/../Views/delivery-orders/print.php';
    }

    public function delete()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if ($id) {

            $model = new DeliveryOrder();

            $deliveryOrder = $model->find($id);

            $model->delete($id);

            activity_log(
                'Operasional - Surat Jalan',
                'delete',
                'Menghapus surat jalan: ' . (($deliveryOrder['no_surat_jalan'] ?? '-')),
                $id,
                $deliveryOrder['no_surat_jalan'] ?? null
            );
        }

        $this->redirect('delivery-orders');
    }

    public function edit()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('delivery-orders');
        }

        $model = new DeliveryOrder();
        $vehicleModel = new Vehicle();

        $deliveryOrder = $model->find($id);
        $vehicles = $vehicleModel->all();

        if (!$deliveryOrder) {
            $this->redirect('delivery-orders');
        }

        activity_log(
            'Operasional - Surat Jalan',
            'edit_form',
            'Membuka form edit surat jalan: ' . (($deliveryOrder['no_surat_jalan'] ?? '-')),
            $id,
            $deliveryOrder['no_surat_jalan'] ?? null
        );

        $this->view('delivery-orders/edit', [
            'title' => 'Edit Surat Jalan',
            'deliveryOrder' => $deliveryOrder,
            'vehicles' => $vehicles
        ]);
    }

    public function update()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_POST['id'] ?? null;

        if (!$id) {
            $this->redirect('delivery-orders');
        }

        $model = new DeliveryOrder();

        $oldData = $model->find($id);

        $model->update($id, [
            'tanggal_kirim' => $_POST['tanggal_kirim'] ?? null,
            'jam_kirim' => $_POST['jam_kirim'] ?? null,
            'vehicle_id' => $_POST['vehicle_id'] ?? '',
            'catatan' => $_POST['catatan'] ?? '',
            'status_sj' => $_POST['status_sj'] ?? 'draft',
        ]);

        activity_log(
            'Operasional - Surat Jalan',
            'update',
            'Mengubah surat jalan: ' . ($_POST['no_surat_jalan'] ?? ($oldData['no_surat_jalan'] ?? '-')),
            $id,
            $_POST['no_surat_jalan'] ?? ($oldData['no_surat_jalan'] ?? null)
        );

        $this->redirect('delivery-orders');
    }

    public function show()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('delivery-orders');
        }

        $model = new DeliveryOrder();

        $deliveryOrder = $model->find($id);

        if (!$deliveryOrder) {
            $this->redirect('delivery-orders');
        }

        $items = $model->getItems($deliveryOrder['rental_id']);

        activity_log(
            'Operasional - Surat Jalan',
            'view',
            'Melihat detail surat jalan: ' . (($deliveryOrder['no_surat_jalan'] ?? '-')),
            $id,
            $deliveryOrder['no_surat_jalan'] ?? null
        );

        $this->view('delivery-orders/show', [
            'title' => 'Detail Surat Jalan',
            'deliveryOrder' => $deliveryOrder,
            'items' => $items
        ]);
    }
}