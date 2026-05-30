<?php

class RentalController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $rentalModel = new Rental();

        $search = trim($_GET['search'] ?? '');

        $limit = 10;
        $currentPage = isset($_GET['p']) ? (int) $_GET['p'] : 1;
        $currentPage = max($currentPage, 1);

        $totalData = $rentalModel->countAll($search);
        $totalPages = ceil($totalData / $limit);
        $offset = ($currentPage - 1) * $limit;

        $rentals = $rentalModel->getPaginated($limit, $offset, $search);

        activity_log(
            'Operasional - Rental',
            'view',
            'Melihat daftar rental order'
        );

        $this->view('rentals/index', [
            'title' => 'Rental Order',
            'rentals' => $rentals,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalData' => $totalData,
            'limit' => $limit,
            'search' => $search
        ]);
    }

    public function create()
    {
        activity_log(
            'Operasional - Rental',
            'create_form',
            'Membuka form tambah rental'
        );

        $this->view('rentals/create', [
            'title' => 'Tambah Rental'
        ]);
    }

    public function store()
    {
        $db = Database::connect();

        $stmt = $db->prepare("
            INSERT INTO rentals 
            (
                no_rental,
                customer_name,
                customer_phone,
                lokasi,
                tanggal_rental,
                tanggal_selesai,
                jam_kirim,
                jam_bongkar,
                status_rental,
                catatan,
                created_by,
                created_at
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");

        $noRental = 'RNT-' . date('Ymd-His');

        $stmt->execute([
            $noRental,
            $_POST['customer_name'] ?? '',
            $_POST['customer_phone'] ?? '',
            $_POST['lokasi'] ?? '',
            $_POST['tanggal_rental'] ?? date('Y-m-d'),
            $_POST['tanggal_selesai'] ?? date('Y-m-d'),
            $_POST['jam_kirim'] ?? null,
            $_POST['jam_bongkar'] ?? null,
            'draft',
            $_POST['catatan'] ?? '',
            $_SESSION['user_id']
        ]);

        $rentalId = $db->lastInsertId();

        activity_log(
            'Operasional - Rental',
            'create',
            'Membuat rental order: ' . $noRental,
            $rentalId,
            $noRental
        );

        $this->redirect('rentals');
    }

    public function show()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('rentals');
        }

        $rentalModel = new Rental();

        $rental = $rentalModel->find($id);
        $items = $rentalModel->getItems($id);

        $technicianAssignModel = new RentalTechnician();
        $technicianAssignments = $technicianAssignModel->getByRental($id);

        if (!$rental) {

            activity_log(
                'Operasional - Rental',
                'view_failed',
                'Gagal membuka detail rental karena data tidak ditemukan',
                $id
            );

            $this->redirect('rentals');
        }

        $quotationItems = [];

        if (!empty($rental['quotation_id'])) {
            $quotationModel = new Quotation();
            $quotationItems = $quotationModel->getItems($rental['quotation_id']);
        }

        activity_log(
            'Operasional - Rental',
            'view',
            'Melihat detail rental: ' . ($rental['no_rental'] ?? '-'),
            $id,
            $rental['no_rental'] ?? null
        );

        $this->view('rentals/show', [
            'title' => 'Detail Rental',
            'rental' => $rental,
            'items' => $items,
            'quotationItems' => $quotationItems,
            'technicianAssignments' => $technicianAssignments
        ]);
    }

    public function createItem()
    {
        $rentalId = $_GET['rental_id'] ?? null;

        if (!$rentalId) {
            $this->redirect('rentals');
        }

        $rentalModel = new Rental();
        $rental = $rentalModel->find($rentalId);

        if (!$rental) {

            activity_log(
                'Operasional - Rental',
                'create_item_failed',
                'Gagal membuka form tambah unit karena rental tidak ditemukan',
                $rentalId
            );

            $this->redirect('rentals');
        }

        $unitModel = new Unit();
        $partnerModel = new Partner();

        $units = $unitModel->getAvailableByDate(
            $rental['tanggal_rental'],
            $rental['tanggal_selesai']
        );

        $partners = $partnerModel->getAll();

        activity_log(
            'Operasional - Rental',
            'create_item_form',
            'Membuka form tambah unit rental: ' . ($rental['no_rental'] ?? '-'),
            $rentalId,
            $rental['no_rental'] ?? null
        );

        $this->view('rentals/create-item', [
            'title' => 'Tambah Unit Rental',
            'rental_id' => $rentalId,
            'rental' => $rental,
            'units' => $units,
            'partners' => $partners
        ]);
    }

    public function storeItem()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $rentalId = $_POST['rental_id'] ?? null;
        $sourceType = $_POST['source_type'] ?? 'internal';

        if (!$rentalId) {
            $this->redirect('rentals');
        }

        $rentalModel = new Rental();
        $unitModel = new Unit();

        $rental = $rentalModel->find($rentalId);

        if ($sourceType === 'internal') {

            $unitId = $_POST['unit_id'] ?? null;

            if (!$unitId) {

                activity_log(
                    'Operasional - Rental',
                    'create_item_failed',
                    'Gagal menambahkan unit internal karena unit kosong',
                    $rentalId
                );

                $this->redirect('rental-items-create', ['rental_id' => $rentalId]);
            }

            $unit = $unitModel->find($unitId);

            $data = [
                'rental_id' => $rentalId,
                'source_type' => 'internal',
                'unit_id' => $unitId,
                'partner_id' => null,
                'partner_unit_name' => null,
                'partner_unit_brand' => null,
                'partner_unit_category' => null,
                'partner_cost' => 0
            ];

            $rentalModel->addItem($data);

            activity_log(
                'Operasional - Rental',
                'create_item',
                'Menambahkan unit internal ke rental: ' .
                ($unit['kode_unit'] ?? '-'),
                $rentalId,
                $rental['no_rental'] ?? null
            );

        } else {

            $data = [
                'rental_id' => $rentalId,
                'source_type' => 'partner',
                'unit_id' => null,
                'partner_id' => $_POST['partner_id'] ?? null,
                'partner_unit_name' => $_POST['partner_unit_name'] ?? '',
                'partner_unit_brand' => $_POST['partner_unit_brand'] ?? '',
                'partner_unit_category' => $_POST['partner_unit_category'] ?? '',
                'partner_cost' => $_POST['partner_cost'] ?? 0
            ];

            $rentalModel->addItem($data);

            activity_log(
                'Operasional - Rental',
                'create_item',
                'Menambahkan unit vendor ke rental: ' .
                ($_POST['partner_unit_name'] ?? '-'),
                $rentalId,
                $rental['no_rental'] ?? null
            );
        }

        $rentalModel->updateStatus($rentalId, 'scheduled');

        $this->redirect('rentals-show', ['id' => $rentalId]);
    }

    public function processOut()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('schedules');
        }

        $rentalModel = new Rental();
        $rental = $rentalModel->find($id);

        $rentalModel->markAsOut($id);

        activity_log(
            'Operasional - Rental',
            'process_out',
            'Proses unit keluar rental: ' . ($rental['no_rental'] ?? '-'),
            $id,
            $rental['no_rental'] ?? null
        );

        $this->redirect('rentals-show', ['id' => $id]);
    }

    public function processReturn()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('schedules');
        }

        $rentalModel = new Rental();
        $rental = $rentalModel->find($id);

        $rentalModel->markAsReturned($id);

        activity_log(
            'Operasional - Rental',
            'process_return',
            'Proses pengembalian rental: ' . ($rental['no_rental'] ?? '-'),
            $id,
            $rental['no_rental'] ?? null
        );

        $this->redirect('rentals-show', ['id' => $id]);
    }

    public function assignTechnician()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $rentalId = $_GET['rental_id'] ?? null;

        if (!$rentalId) {
            $this->redirect('rentals');
        }

        $rentalModel = new Rental();
        $technicianModel = new Technician();

        $rental = $rentalModel->find($rentalId);
        $technicians = $technicianModel->getActive();

        if (!$rental) {

            activity_log(
                'Operasional - Rental',
                'assign_technician_failed',
                'Gagal membuka assign teknisi karena rental tidak ditemukan',
                $rentalId
            );

            $this->redirect('rentals');
        }

        activity_log(
            'Operasional - Rental',
            'assign_technician_form',
            'Membuka assign teknisi rental: ' . ($rental['no_rental'] ?? '-'),
            $rentalId,
            $rental['no_rental'] ?? null
        );

        $this->view('rentals/assign-technician', [
            'title' => 'Assign Teknisi',
            'rental' => $rental,
            'technicians' => $technicians
        ]);
    }

    public function storeTechnician()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $rentalId = $_POST['rental_id'] ?? null;
        $technicianIds = $_POST['technician_ids'] ?? [];
        $taskType = $_POST['task_type'] ?? '';
        $scheduledDate = $_POST['scheduled_date'] ?? '';
        $scheduledTime = $_POST['scheduled_time'] ?? null;

        if (!$rentalId || empty($technicianIds) || !$taskType || !$scheduledDate) {

            activity_log(
                'Operasional - Rental',
                'assign_technician_failed',
                'Gagal assign teknisi karena data tidak lengkap',
                $rentalId
            );

            $this->redirect('rentals-show', ['id' => $rentalId]);
        }

        $model = new RentalTechnician();
        $rentalModel = new Rental();

        $rental = $rentalModel->find($rentalId);

        foreach ($technicianIds as $technicianId) {

            $model->create([
                'rental_id' => $rentalId,
                'technician_id' => $technicianId,
                'task_type' => $taskType,
                'scheduled_date' => $scheduledDate,
                'scheduled_time' => $scheduledTime
            ]);
        }

        activity_log(
            'Operasional - Rental',
            'assign_technician',
            'Assign teknisi untuk rental: ' . ($rental['no_rental'] ?? '-'),
            $rentalId,
            $rental['no_rental'] ?? null
        );

        $this->redirect('rentals-show', ['id' => $rentalId]);
    }

    public function deleteTechnician()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;
        $rentalId = $_GET['rental_id'] ?? null;

        if ($id) {

            $model = new RentalTechnician();

            $model->delete($id);

            activity_log(
                'Operasional - Rental',
                'delete_technician',
                'Menghapus assign teknisi rental',
                $rentalId
            );
        }

        $this->redirect('rentals-show', ['id' => $rentalId]);
    }

    public function createFromQuotation()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $quotationId = $_GET['id'] ?? null;

        if (!$quotationId) {
            $this->redirect('quotations');
        }

        $quotationModel = new Quotation();
        $quotation = $quotationModel->find($quotationId);

        if (!$quotation) {

            activity_log(
                'Operasional - Rental',
                'create_from_quotation_failed',
                'Gagal membuat rental dari quotation karena data tidak ditemukan',
                $quotationId
            );

            $this->redirect('quotations');
        }

        $rentalModel = new Rental();

        $rentalNumber = 'RNT' . date('mdHis') . rand(10, 99);

        $db = Database::connect();
        $db->beginTransaction();

        try {
        $rentalId = $rentalModel->create([
            'no_rental' => $rentalNumber,
            'customer_name' => $quotation['customer_name'] ?? '',
            'customer_phone' => $quotation['customer_phone'] ?? '',
            'lokasi' => $quotation['lokasi'] ?? '',
            'tanggal_rental' => $quotation['tanggal_mulai'] ?? null,
            'tanggal_selesai' => $quotation['tanggal_selesai'] ?? null,
            'status_rental' => 'waiting',
            'quotation_id' => $quotationId,
            'created_by' => $_SESSION['user_id'],
        ]);

        $quotationModel->markAsConverted($quotationId);

        $db->commit();
        } catch (Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            throw $e;
        }

        activity_log(
            'Operasional - Rental',
            'create_from_quotation',
            'Membuat rental dari quotation: ' . ($quotation['no_quotation'] ?? '-'),
            $rentalId,
            $rentalNumber
        );

        $this->redirect('rentals-show', ['id' => $rentalId]);
    }
}
