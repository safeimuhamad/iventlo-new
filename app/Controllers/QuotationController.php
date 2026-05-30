<?php

class QuotationController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $search = trim($_GET['search'] ?? '');

        $model = new Quotation();

        $limit = 10;
        $currentPage = isset($_GET['p']) ? (int) $_GET['p'] : 1;
        $currentPage = max($currentPage, 1);

        $totalData = $model->countAll($search);
        $totalPages = ceil($totalData / $limit);
        $offset = ($currentPage - 1) * $limit;

        $quotations = $model->getPaginated($limit, $offset, $search);

        activity_log(
            'Sales - Penawaran',
            'view',
            'Melihat daftar penawaran rental'
        );

        $this->view('quotations/index', [
            'title' => 'Penawaran Rental',
            'quotations' => $quotations,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalData' => $totalData,
            'search' => $search,
            'limit' => $limit
        ]);
    }

    public function create()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $quotationModel = new Quotation();
        $customerModel = new Customer();
        $leadModel = new MarketingLead();
        $productModel = new QuotationProduct();

        activity_log(
            'Sales - Penawaran',
            'create_form',
            'Membuka form tambah penawaran'
        );

        $this->view('quotations/create', [
            'title' => 'Tambah Penawaran',
            'nomor' => $quotationModel->generateNumber(),
            'customers' => $customerModel->getActive(),
            'leads' => $leadModel->getOpenLeads(),
            'created_by' => $_SESSION['user_id'],
            'products' => $productModel->getActive()
        ]);
    }

    public function store()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $sourceType = $_POST['source_type'] ?? 'customer';

        $customerId = null;
        $leadId = null;

        $customerName = $_POST['customer_name'] ?? '';
        $customerPhone = $_POST['customer_phone'] ?? '';
        $lokasi = $_POST['lokasi'] ?? '';

        if ($sourceType === 'customer') {

            $customerId = $_POST['customer_id'] ?? null;

            if (!empty($customerId)) {

                $customerModel = new Customer();
                $customer = $customerModel->find($customerId);

                if ($customer) {
                    $customerName = $customer['company_name'] ?? $customerName;
                    $customerPhone = $customer['phone'] ?? $customerPhone;
                    $lokasi = $customer['address'] ?? $lokasi;
                }
            }
        }

        if ($sourceType === 'lead') {

            $leadId = $_POST['lead_id'] ?? null;

            if (!empty($leadId)) {

                $leadModel = new MarketingLead();
                $lead = $leadModel->find($leadId);

                if ($lead) {
                    $customerName = $lead['company_name'] ?: $lead['pic_name'];
                    $customerPhone = $lead['phone'] ?? '';
                    $lokasi = $lead['address'] ?? '';
                }
            }
        }

        if (!empty($customerId)) {

            $customerModel = new Customer();
            $customer = $customerModel->find($customerId);

            if ($customer) {
                $customerName = $customer['company_name'] ?? $customerName;
                $customerPhone = $customer['phone'] ?? $customerPhone;
                $lokasi = $customer['address'] ?? $lokasi;
            }
        }

        $model = new Quotation();

        $quotationId = $model->create([
            'no_quotation' => $_POST['no_quotation'],
            'customer_id' => $customerId,
            'lead_id' => $leadId,
            'customer_name' => $customerName,
            'customer_phone' => $customerPhone,
            'lokasi' => $lokasi,
            'tanggal_mulai' => $_POST['tanggal_mulai'] ?? null,
            'tanggal_selesai' => $_POST['tanggal_selesai'] ?? null,
            'catatan' => $_POST['catatan'] ?? '',
            'created_by' => $_SESSION['user_id'],
        ]);

        if (!empty($leadId)) {

            $leadModel = new MarketingLead();

            $leadModel->updateStatus(
                $leadId,
                'quotation'
            );
        }

        $itemNames = $_POST['item_name'] ?? [];

        foreach ($itemNames as $index => $itemName) {

            if (trim($itemName) === '') {
                continue;
            }

            $qty = (int) ($_POST['qty'][$index] ?? 1);
            $duration = (int) ($_POST['duration'][$index] ?? 1);
            $unitPrice = (float) ($_POST['unit_price'][$index] ?? 0);
            $discount = (float) ($_POST['discount'][$index] ?? 0);

            $itemType = $_POST['item_type'][$index] ?? 'rental_unit';

            $billingType = $_POST['billing_type'][$index]
                ?? $_POST['rental_period_type'][$index]
                ?? 'daily';

            if (in_array($billingType, ['daily', 'weekly', 'monthly'])) {

                $subtotalBeforeDiscount = $qty * $duration * $unitPrice;
                $rentalPeriodType = $billingType;

            } elseif (in_array($billingType, ['package', 'fixed'])) {

                $subtotalBeforeDiscount = $unitPrice;
                $rentalPeriodType = 'daily';

            } else {

                $subtotalBeforeDiscount = $qty * $unitPrice;
                $rentalPeriodType = 'daily';
            }

            $subtotal = max(0, $subtotalBeforeDiscount - $discount);

            $model->addItem($quotationId, [
                'product_id' => $_POST['product_id'][$index] ?? null,
                'item_name' => $itemName,
                'category' => $_POST['category'][$index] ?? '',
                'item_type' => $itemType,
                'billing_type' => $billingType,
                'qty' => $qty,
                'rental_period_type' => $rentalPeriodType,
                'duration' => $duration,
                'unit_price' => $unitPrice,
                'discount' => $discount,
                'subtotal' => $subtotal,
                'notes' => $_POST['item_notes'][$index] ?? ''
            ]);
        }

        activity_log(
            'Sales - Penawaran',
            'create',
            'Membuat penawaran: ' . ($_POST['no_quotation'] ?? '-'),
            $quotationId,
            $_POST['no_quotation'] ?? null
        );

        $this->redirect('quotations');
    }

    public function edit()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('quotations');
        }

        $quotationModel = new Quotation();
        $customerModel = new Customer();
        $productModel = new QuotationProduct();

        $quotation = $quotationModel->find($id);

        if (!$quotation) {

            activity_log(
                'Sales - Penawaran',
                'edit_failed',
                'Gagal membuka form edit penawaran karena data tidak ditemukan',
                $id
            );

            $this->redirect('quotations');
        }

        activity_log(
            'Sales - Penawaran',
            'edit_form',
            'Membuka form edit penawaran: ' . ($quotation['no_quotation'] ?? '-'),
            $id,
            $quotation['no_quotation'] ?? null
        );

        $this->view('quotations/edit', [
            'title' => 'Edit Penawaran',
            'quotation' => $quotation,
            'items' => $quotationModel->getItems($id),
            'customers' => $customerModel->getActive(),
            'products' => $productModel->getActive()
        ]);
    }

    public function update()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_POST['id'] ?? null;

        if (!$id) {
            $this->redirect('quotations');
        }

        $customerId = $_POST['customer_id'] ?? null;

        $customerName = $_POST['customer_name'] ?? '';
        $customerPhone = $_POST['customer_phone'] ?? '';
        $lokasi = $_POST['lokasi'] ?? '';

        if (!empty($customerId)) {

            $customerModel = new Customer();
            $customer = $customerModel->find($customerId);

            if ($customer) {
                $customerName = $customer['company_name'] ?? $customerName;
                $customerPhone = $customer['phone'] ?? $customerPhone;
                $lokasi = $customer['address'] ?? $lokasi;
            }
        }

        $model = new Quotation();

        $oldQuotation = $model->find($id);

        if (!$oldQuotation) {

            activity_log(
                'Sales - Penawaran',
                'update_failed',
                'Gagal update penawaran karena data tidak ditemukan',
                $id
            );

            $this->redirect('quotations');
        }

        $model->update($id, [
            'customer_id' => $customerId,
            'lead_id' => $_POST['lead_id'] ?? null,
            'customer_name' => $customerName,
            'customer_phone' => $customerPhone,
            'lokasi' => $lokasi,
            'tanggal_mulai' => $_POST['tanggal_mulai'] ?? null,
            'tanggal_selesai' => $_POST['tanggal_selesai'] ?? null,
            'catatan' => $_POST['catatan'] ?? '',
        ]);

        $model->deleteItems($id);

        $itemNames = $_POST['item_name'] ?? [];

        foreach ($itemNames as $index => $itemName) {

            if (trim($itemName) === '') {
                continue;
            }

            $qty = (int) ($_POST['qty'][$index] ?? 1);
            $duration = (int) ($_POST['duration'][$index] ?? 1);
            $unitPrice = (float) ($_POST['unit_price'][$index] ?? 0);
            $discount = (float) ($_POST['discount'][$index] ?? 0);

            $itemType = $_POST['item_type'][$index] ?? 'rental_unit';

            $billingType = $_POST['billing_type'][$index]
                ?? $_POST['rental_period_type'][$index]
                ?? 'daily';

            if (in_array($billingType, ['daily', 'weekly', 'monthly'])) {

                $subtotalBeforeDiscount = $qty * $duration * $unitPrice;
                $rentalPeriodType = $billingType;

            } elseif (in_array($billingType, ['package', 'fixed'])) {

                $subtotalBeforeDiscount = $unitPrice;
                $rentalPeriodType = 'daily';

            } else {

                $subtotalBeforeDiscount = $qty * $unitPrice;
                $rentalPeriodType = 'daily';
            }

            $subtotal = max(0, $subtotalBeforeDiscount - $discount);

            $model->addItem($id, [
                'product_id' => $_POST['product_id'][$index] ?? null,
                'item_name' => $itemName,
                'category' => $_POST['category'][$index] ?? '',
                'item_type' => $itemType,
                'billing_type' => $billingType,
                'qty' => $qty,
                'rental_period_type' => $rentalPeriodType,
                'duration' => $duration,
                'unit_price' => $unitPrice,
                'discount' => $discount,
                'subtotal' => $subtotal,
                'notes' => $_POST['item_notes'][$index] ?? ''
            ]);
        }

        activity_log(
            'Sales - Penawaran',
            'update',
            'Mengubah penawaran: ' . ($oldQuotation['no_quotation'] ?? '-'),
            $id,
            $oldQuotation['no_quotation'] ?? null
        );

        $this->redirect('quotations');
    }

    public function show()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('quotations');
        }

        $model = new Quotation();

        $quotation = $model->find($id);

        if (!$quotation) {

            activity_log(
                'Sales - Penawaran',
                'view_failed',
                'Gagal membuka detail penawaran karena data tidak ditemukan',
                $id
            );

            $this->redirect('quotations');
        }

        $items = $model->getItems($id);

        activity_log(
            'Sales - Penawaran',
            'view',
            'Melihat detail penawaran: ' . ($quotation['no_quotation'] ?? '-'),
            $id,
            $quotation['no_quotation'] ?? null
        );

        $this->view('quotations/show', [
            'title' => 'Detail Penawaran',
            'quotation' => $quotation,
            'items' => $items
        ]);
    }

    public function printRental()
    {
        activity_log(
            'Sales - Penawaran',
            'print',
            'Print quotation rental',
            $_GET['id'] ?? null
        );

        $this->printWithTemplate('quotations/print-rental');
    }

    public function printService()
    {
        activity_log(
            'Sales - Penawaran',
            'print',
            'Print quotation service',
            $_GET['id'] ?? null
        );

        $this->printWithTemplate('quotations/print-service');
    }

    private function printWithTemplate($view)
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('quotations');
        }

        $model = new Quotation();

        $quotation = $model->find($id);

        if (!$quotation) {

            activity_log(
                'Sales - Penawaran',
                'print_failed',
                'Gagal print penawaran karena data tidak ditemukan',
                $id
            );

            $this->redirect('quotations');
        }

        $items = $model->getItems($id);

        require __DIR__ . '/../Views/' . $view . '.php';
    }

    public function delete()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('quotations');
        }

        $model = new Quotation();

        $quotation = $model->find($id);

        $model->delete($id);

        activity_log(
            'Sales - Penawaran',
            'delete',
            'Menghapus penawaran: ' . ($quotation['no_quotation'] ?? '-'),
            $id,
            $quotation['no_quotation'] ?? null
        );

        $this->redirect('quotations');
    }

    public function createFromLead()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $leadId = $_GET['id'] ?? null;

        if (!$leadId) {
            $this->redirect('marketing-leads');
        }

        $leadModel = new MarketingLead();
        $quotationModel = new Quotation();
        $productModel = new QuotationProduct();

        $lead = $leadModel->find($leadId);

        if (!$lead) {

            activity_log(
                'Sales - Penawaran',
                'create_from_lead_failed',
                'Gagal membuat quotation dari lead karena data lead tidak ditemukan',
                $leadId
            );

            $_SESSION['error'] = 'Lead tidak ditemukan.';
            $this->redirect('marketing-leads');
        }

        activity_log(
            'Sales - Penawaran',
            'create_from_lead',
            'Membuka form quotation dari lead: ' . ($lead['company_name'] ?? '-'),
            $leadId,
            $lead['lead_number'] ?? null
        );

        $this->view('quotations/create', [
            'title' => 'Tambah Penawaran',
            'nomor' => $quotationModel->generateNumber(),
            'lead' => $lead,
            'leads' => [$lead],
            'customers' => [],
            'created_by' => $_SESSION['user_id'],
            'products' => $productModel->getActive(),
            'fromLead' => true
        ]);
    }
}