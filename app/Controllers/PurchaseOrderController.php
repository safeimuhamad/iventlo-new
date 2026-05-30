<?php

class PurchaseOrderController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('purchase_order.view');

        $search = trim($_GET['search'] ?? '');

        $model = new PurchaseOrder();

        $limit = 10;
        $currentPage = max(1, (int) ($_GET['p'] ?? 1));
        $offset = ($currentPage - 1) * $limit;
        $status = $_GET['status'] ?? '';

        $totalData = $model->countAll($search, $status);
        $totalPages = (int) ceil($totalData / $limit);
                activity_log(
                    'Purchasing - Purchase Order',
                    'view',
                    'Melihat daftar purchase order'
                );
        $this->view('purchase-orders/index', [
            'title' => 'Purchase Order',
            'items' => $model->getPaginated($limit, $offset, $search, $status),
            'search' => $search,
            'status' => $status,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalData' => $totalData,
            'limit' => $limit
        ]);
    }

    public function createFromPr()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('purchase_order.create');

        $prId = $_GET['id'] ?? null;

        if (!$prId) {
            $this->redirect('purchase-requests');
        }

        $prModel = new PurchaseRequest();
        $poModel = new PurchaseOrder();
        $vendorModel = new Vendor();

        $pr = $prModel->find($prId);

        if (!$pr) {
            $_SESSION['error'] = 'Purchase request tidak ditemukan.';
            $this->redirect('purchase-requests');
        }

        if (($pr['status'] ?? '') !== 'approved') {
            $_SESSION['error'] = 'Hanya PR approved yang bisa dibuatkan PO.';
            $this->redirect('purchase-requests-show', ['id' => $prId]);
        }

        activity_log(
            'Purchasing - Purchase Order',
            'create_from_pr',
            'Membuka form PO dari PR: ' . ($pr['pr_number'] ?? '-'),
            $prId,
            $pr['pr_number'] ?? null
        );

        $this->view('purchase-orders/create', [
            'title' => 'Tambah Purchase Order',
            'poNumber' => $poModel->generateNumber(),
            'vendors' => $vendorModel->getAll(),
            'purchaseRequest' => $pr,
            'purchaseRequestItems' => $prModel->getItems($prId)
        ]);
    }

    public function create()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('purchase_order.create');

        $poModel = new PurchaseOrder();
        $vendorModel = new Vendor();

        activity_log(
            'Purchasing - Purchase Order',
            'create_form',
            'Membuka form tambah purchase order'
        );

        $this->view('purchase-orders/create', [
            'title' => 'Tambah Purchase Order',
            'poNumber' => $poModel->generateNumber(),
            'vendors' => $vendorModel->getAll()
        ]);
    }

    public function store()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('purchase_order.create');

        $vendorId = $_POST['vendor_id'] ?? null;

        if (!$vendorId) {
            $_SESSION['error'] = 'Vendor wajib dipilih.';
            $this->redirect('purchase-orders-create');
        }

        $parseMoney = function ($value) {
            $value = trim((string) ($value ?? '0'));

            if ($value === '') {
                return 0;
            }

            return (float) preg_replace('/[^0-9]/', '', $value);
        };

        $parseQty = function ($value) {
            $value = trim((string) ($value ?? '0'));

            if ($value === '') {
                return 0;
            }

            $value = str_replace(',', '.', $value);

            return (float) $value;
        };

        $itemNames = $_POST['item_name'] ?? [];
        $descriptions = $_POST['description'] ?? [];
        $qtys = $_POST['qty'] ?? [];
        $unitNames = $_POST['unit_name'] ?? [];
        $unitPrices = $_POST['unit_price'] ?? [];

        $subtotal = 0;

        foreach ($itemNames as $index => $itemName) {
            if (trim($itemName) === '') {
                continue;
            }

            $qty = $parseQty($qtys[$index] ?? 0);
            $unitPrice = $parseMoney($unitPrices[$index] ?? 0);

            $subtotal += $qty * $unitPrice;
        }

        $taxAmount = $parseMoney($_POST['tax_amount'] ?? 0);
        $grandTotal = $subtotal + $taxAmount;

        $model = new PurchaseOrder();

        $poNumber = $_POST['po_number'] ?? $model->generateNumber();

        $poId = $model->create([
            'po_number' => $poNumber,
            'purchase_request_id' => $_POST['purchase_request_id'] ?? null,
            'vendor_id' => $vendorId,
            'po_date' => $_POST['po_date'] ?? date('Y-m-d'),
            'expected_date' => $_POST['expected_date'] ?? null,
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'grand_total' => $grandTotal,
            'status' => $_POST['status'] ?? 'draft',
            'notes' => $_POST['notes'] ?? '',
            'created_by' => $_SESSION['user_id'] ?? null
        ]);

        foreach ($itemNames as $index => $itemName) {
            if (trim($itemName) === '') {
                continue;
            }

            $qty = $parseQty($qtys[$index] ?? 0);
            $unitPrice = $parseMoney($unitPrices[$index] ?? 0);
            $lineSubtotal = $qty * $unitPrice;

            if ($qty <= 0 || $unitPrice < 0) {
                continue;
            }

            $model->addItem($poId, [
                'item_name' => trim($itemName),
                'description' => $descriptions[$index] ?? '',
                'qty' => $qty,
                'unit_name' => $unitNames[$index] ?? 'unit',
                'unit_price' => $unitPrice,
                'subtotal' => $lineSubtotal,
                'received_qty' => 0
            ]);
        }

        if (!empty($_POST['purchase_request_id'])) {
            $prModel = new PurchaseRequest();

            $prModel->markAsClosed($_POST['purchase_request_id']);

            activity_log(
                'Purchasing - Purchase Request',
                'closed',
                'Purchase request ditutup karena sudah dibuatkan PO: ' . $poNumber,
                $_POST['purchase_request_id'],
                $poNumber
            );
        }

        activity_log(
            'Purchasing - Purchase Order',
            'create',
            'Membuat purchase order: ' . $poNumber,
            $poId,
            $poNumber
        );

        $_SESSION['success'] = 'Purchase order berhasil dibuat.';

        $this->redirect('purchase-orders');
    }

    public function show()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('purchase_order.view');

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('purchase-orders');
        }

        $model = new PurchaseOrder();

        $item = $model->find($id);

        if (!$item) {

            activity_log(
                'Purchasing - Purchase Order',
                'view_failed',
                'Gagal membuka detail purchase order karena data tidak ditemukan',
                $id
            );

            $_SESSION['error'] = 'Purchase order tidak ditemukan.';
            $this->redirect('purchase-orders');
        }

        $billModel = new VendorBill();
        $hasVendorBill = $billModel->findByPurchaseOrder($id);

        activity_log(
            'Purchasing - Purchase Order',
            'view',
            'Melihat detail purchase order: ' . ($item['po_number'] ?? '-'),
            $id,
            $item['po_number'] ?? null
        );

        $this->view('purchase-orders/show', [
            'title' => 'Detail Purchase Order',
            'item' => $item,
            'items' => $model->getItems($id),
            'hasVendorBill' => $hasVendorBill
        ]);
    }

    public function edit()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('purchase_order.edit');

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('purchase-orders');
        }

        $model = new PurchaseOrder();

        $item = $model->find($id);

        if (!$item) {

            activity_log(
                'Purchasing - Purchase Order',
                'edit_failed',
                'Gagal membuka form edit purchase order karena data tidak ditemukan',
                $id
            );

            $_SESSION['error'] = 'Purchase order tidak ditemukan.';
            $this->redirect('purchase-orders');
        }

        if (!in_array($item['status'], ['draft', 'rejected'])) {
            $_SESSION['error'] = 'Purchase order yang sudah diproses tidak bisa diedit.';
            $this->redirect('purchase-orders-show', ['id' => $id]);
        }

        $vendorModel = new Vendor();

        activity_log(
            'Purchasing - Purchase Order',
            'edit_form',
            'Membuka form edit purchase order: ' . ($item['po_number'] ?? '-'),
            $id,
            $item['po_number'] ?? null
        );

        $this->view('purchase-orders/edit', [
            'title' => 'Edit Purchase Order',
            'item' => $item,
            'items' => $model->getItems($id),
            'vendors' => $vendorModel->getAll()
        ]);
    }

    public function update()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('purchase_order.edit');

        $id = $_POST['id'] ?? null;

        if (!$id) {
            $this->redirect('purchase-orders');
        }

        $model = new PurchaseOrder();

        $oldItem = $model->find($id);

        if (!$oldItem) {
            $_SESSION['error'] = 'Purchase order tidak ditemukan.';
            $this->redirect('purchase-orders');
        }

        if (!in_array($oldItem['status'], ['draft', 'rejected'])) {
            $_SESSION['error'] = 'Purchase order yang sudah diproses tidak bisa diedit.';
            $this->redirect('purchase-orders-show', ['id' => $id]);
        }

        $itemNames = $_POST['item_name'] ?? [];
        $descriptions = $_POST['description'] ?? [];
        $qtys = $_POST['qty'] ?? [];
        $unitNames = $_POST['unit_name'] ?? [];
        $unitPrices = $_POST['unit_price'] ?? [];

        $subtotal = 0;

        foreach ($itemNames as $index => $itemName) {
            if (trim($itemName) === '') {
                continue;
            }

            $qty = (float) str_replace('.', '', $qtys[$index] ?? 0);
            $unitPrice = (float) str_replace('.', '', $unitPrices[$index] ?? 0);

            $subtotal += $qty * $unitPrice;
        }

        $taxAmount = (float) str_replace('.', '', $_POST['tax_amount'] ?? 0);
        $grandTotal = $subtotal + $taxAmount;

        $model->update($id, [
            'vendor_id' => $_POST['vendor_id'] ?? null,
            'po_date' => $_POST['po_date'] ?? date('Y-m-d'),
            'expected_date' => $_POST['expected_date'] ?? null,
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'grand_total' => $grandTotal,
            'status' => $_POST['status'] ?? 'draft',
            'notes' => $_POST['notes'] ?? ''
        ]);

        $model->deleteItems($id);

        foreach ($itemNames as $index => $itemName) {
            if (trim($itemName) === '') {
                continue;
            }

            $qty = (float) str_replace('.', '', $qtys[$index] ?? 0);
            $unitPrice = (float) str_replace('.', '', $unitPrices[$index] ?? 0);
            $lineSubtotal = $qty * $unitPrice;

            if ($qty <= 0 || $unitPrice < 0) {
                continue;
            }

            $model->addItem($id, [
                'item_name' => trim($itemName),
                'description' => $descriptions[$index] ?? '',
                'qty' => $qty,
                'unit_name' => $unitNames[$index] ?? 'unit',
                'unit_price' => $unitPrice,
                'subtotal' => $lineSubtotal,
                'received_qty' => 0
            ]);
        }

        activity_log(
            'Purchasing - Purchase Order',
            'update',
            'Mengubah purchase order: ' . ($oldItem['po_number'] ?? '-'),
            $id,
            $oldItem['po_number'] ?? null
        );

        $_SESSION['success'] = 'Purchase order berhasil diperbarui.';

        $this->redirect('purchase-orders-show', ['id' => $id]);
    }

    public function approve()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('purchase_order.approve');

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('purchase-orders');
        }

        $model = new PurchaseOrder();

        $item = $model->find($id);

        if (!$item) {
            $this->redirect('purchase-orders');
        }

        $model->approve($id);

        activity_log(
            'Purchasing - Purchase Order',
            'approve',
            'Approve purchase order: ' . ($item['po_number'] ?? '-'),
            $id,
            $item['po_number'] ?? null
        );

        $_SESSION['success'] = 'Purchase order berhasil di-approve.';

        $this->redirect('purchase-orders-show', ['id' => $id]);
    }

    public function markAsSent()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('purchase_order.edit');

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('purchase-orders');
        }

        $model = new PurchaseOrder();

        $item = $model->find($id);

        if (!$item) {
            $this->redirect('purchase-orders');
        }

        $model->markAsSent($id);

        activity_log(
            'Purchasing - Purchase Order',
            'sent',
            'Menandai purchase order sudah dikirim ke vendor: ' . ($item['po_number'] ?? '-'),
            $id,
            $item['po_number'] ?? null
        );

        $_SESSION['success'] = 'Purchase order ditandai sudah dikirim.';

        $this->redirect('purchase-orders-show', ['id' => $id]);
    }

    public function print()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('purchase_order.print');

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('purchase-orders');
        }

        $model = new PurchaseOrder();

        $item = $model->find($id);

        if (!$item) {
            $this->redirect('purchase-orders');
        }

        $items = $model->getItems($id);

        activity_log(
            'Purchasing - Purchase Order',
            'print',
            'Print purchase order: ' . ($item['po_number'] ?? '-'),
            $id,
            $item['po_number'] ?? null
        );

        require __DIR__ . '/../Views/purchase-orders/print.php';
    }

    public function delete()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('purchase_order.edit');

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('purchase-orders');
        }

        $model = new PurchaseOrder();

        $item = $model->find($id);

        if (!$item) {
            $this->redirect('purchase-orders');
        }

        if (!in_array($item['status'], ['draft', 'rejected'])) {
            $_SESSION['error'] = 'Purchase order yang sudah diproses tidak bisa dihapus.';
            $this->redirect('purchase-orders-show', ['id' => $id]);
        }

        $model->delete($id);

        activity_log(
            'Purchasing - Purchase Order',
            'delete',
            'Menghapus purchase order: ' . ($item['po_number'] ?? '-'),
            $id,
            $item['po_number'] ?? null
        );

        $_SESSION['success'] = 'Purchase order berhasil dihapus.';

        $this->redirect('purchase-orders');
    }


public function createBill()
{
    if (empty($_SESSION['user_id'])) {
        $this->redirect('login');
    }

    requirePermission('vendor_bill.create');

    $id = $_GET['id'] ?? null;

    if (!$id) {
        $this->redirect('purchase-orders');
    }

    $poModel = new PurchaseOrder();
    $billModel = new VendorBill();

    $po = $poModel->find($id);

    if (!$po) {
        $_SESSION['error'] = 'Purchase order tidak ditemukan.';
        $this->redirect('purchase-orders');
    }

    $existingBill = $billModel->findByPurchaseOrder($id);

    if ($existingBill) {
        $_SESSION['error'] = 'Vendor bill untuk PO ini sudah pernah dibuat.';
        $this->redirect('vendor-bills-show', ['id' => $existingBill['id']]);
    }

    $items = $poModel->getItems($id);

    $billNo = $billModel->generateNumber();

    $coaModel = new ChartOfAccount();
    $defaultExpenseAccountId = $coaModel->getIdByCode('5-10000');

    if (!$defaultExpenseAccountId) {
        $_SESSION['error'] = 'Akun beban default untuk vendor bill belum dikonfigurasi.';
        $this->redirect('purchase-orders-show', ['id' => $id]);
    }

    $db = Database::connect();
    $db->beginTransaction();

    try {
    $billId = $billModel->create([
        'bill_no' => $billNo,
        'purchase_order_id' => $id,
        'vendor_id' => $po['vendor_id'],
        'bill_date' => date('Y-m-d'),
        'due_date' => null,
        'subtotal' => $po['subtotal'] ?? 0,
        'tax_amount' => $po['tax_amount'] ?? 0,
        'grand_total' => $po['grand_total'] ?? 0,
        'paid_amount' => 0,
        'status_payment' => 'unpaid',
        'notes' => 'Auto generated dari PO: ' . ($po['po_number'] ?? '-')
    ]);

    foreach ($items as $item) {
        $billModel->addItem($billId, [
            'account_id' => $defaultExpenseAccountId,
            'description' => $item['item_name'] ?? '',
            'amount' => $item['subtotal'] ?? 0
        ]);
    }

    $db->commit();
    } catch (Throwable $e) {
        if ($db->inTransaction()) {
            $db->rollBack();
        }
        throw $e;
    }

    activity_log(
        'Purchasing - Purchase Order',
        'create_vendor_bill',
        'Membuat vendor bill dari PO: ' . ($po['po_number'] ?? '-'),
        $id,
        $po['po_number'] ?? null
    );

    $_SESSION['success'] = 'Vendor bill berhasil dibuat dari purchase order.';

    $this->redirect('vendor-bills-show', ['id' => $billId]);
}
}
