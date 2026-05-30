<?php

class InvoiceController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $search = trim($_GET['search'] ?? '');

        $model = new Invoice();

        $limit = 10;
        $currentPage = isset($_GET['p']) ? (int) $_GET['p'] : 1;
        $currentPage = max($currentPage, 1);

        $totalData = $model->countAll($search);
        $totalPages = ceil($totalData / $limit);
        $offset = ($currentPage - 1) * $limit;

        $invoices = $model->getPaginated($limit, $offset, $search);
        $paymentModel = new InvoicePayment();

        foreach ($invoices as &$invoice) {

            $invoiceTotal = (float) ($invoice['billing_total'] ?? 0);

            $totalPaid = $paymentModel->getTotalPaid($invoice['id']);

            $remainingAmount = max(0, $invoiceTotal - $totalPaid);

            if ($totalPaid <= 0) {
                $computedStatus = 'waiting payment';
            } elseif ($remainingAmount > 0) {
                $computedStatus = 'partial paid';
            } else {
                $computedStatus = 'paid';
            }

            $invoice['computed_total'] = $invoiceTotal;
            $invoice['computed_paid'] = $totalPaid;
            $invoice['computed_remaining'] = $remainingAmount;
            $invoice['computed_status'] = $computedStatus;
        }

        unset($invoice);

        activity_log(
            'Keuangan - Invoice',
            'view',
            'Melihat daftar invoice'
        );

        $this->view('invoices/index', [
            'title' => 'Invoice',
            'invoices' => $invoices,
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

        $model = new Invoice();
        $customerModel = new Customer();
        $productModel = new QuotationProduct();

        $quotation = null;
        $quotationItems = [];

        $quotationId = (int) ($_GET['quotation_id'] ?? 0);

        if ($quotationId > 0) {
            $quotationModel = new Quotation();

            $quotation = $quotationModel->find($quotationId);

            if ($quotation) {
                $quotationItems = $quotationModel->getItems($quotationId);
            }
        }

        activity_log(
            'Keuangan - Invoice',
            'create_form',
            $quotation
                ? 'Membuka form tambah invoice dari quotation: ' . ($quotation['no_quotation'] ?? '-')
                : 'Membuka form tambah invoice'
        );

        $this->view('invoices/create', [
            'title' => 'Tambah Invoice',
            'customers' => $customerModel->getActive(),
            'products' => $productModel->getActive(),
            'nomor' => $model->generateNumber(),
            'quotation' => $quotation,
            'quotationItems' => $quotationItems,
        ]);
    }

    public function store()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $model = new Invoice();
        $itemNames = $_POST['item_name'] ?? [];

        $subtotalBeforeDiscount = 0;
        $totalDiscount = 0;
        $grandTotal = 0;

        foreach ($itemNames as $index => $itemName) {

            if (trim($itemName) === '') {
                continue;
            }

            $qty = (int) ($_POST['qty'][$index] ?? 1);
            $duration = (int) ($_POST['duration'][$index] ?? 1);
            $unitPrice = (float) ($_POST['unit_price'][$index] ?? 0);
            $discount = (float) ($_POST['discount'][$index] ?? 0);

            $billingType = $_POST['billing_type'][$index] ?? 'unit';

            if (in_array($billingType, ['daily', 'weekly', 'monthly'])) {

                $beforeDiscount = $qty * $duration * $unitPrice;

            } elseif (in_array($billingType, ['package', 'fixed'])) {

                $beforeDiscount = $unitPrice;

            } else {

                $beforeDiscount = $qty * $unitPrice;
            }

            $subtotal = max(0, $beforeDiscount - $discount);

            $subtotalBeforeDiscount += $beforeDiscount;
            $totalDiscount += $discount;
            $grandTotal += $subtotal;
        }

        $paidAmount = (float) ($_POST['paid_amount'] ?? 0);

        $invoiceType = $_POST['invoice_type'] ?? 'full';
        $taxType = $_POST['tax_type'] ?? 'non_ppn';

        $totals = $this->calculateInvoiceTotals($grandTotal, $taxType);
        $dpType = $_POST['dp_type'] ?? 'percentage';
        $dpPercentage = (float) ($_POST['dp_percentage'] ?? 0);
        $dpNominal = (float) ($_POST['dp_nominal'] ?? 0);
        $remainingBill = (float) ($_POST['remaining_bill'] ?? 0);

        if ($invoiceType !== 'dp') {
            $dpType = null;
            $dpPercentage = 0;
            $dpNominal = 0;
            $remainingBill = 0;
        }

        $paymentAccount = $taxType === 'include_ppn'
        ? "Bank BCA\nCabang ARKADIA\n5405197984\nPT. Micool Berkah Bersama"
        : "Bank BCA\n1660867313\nLINA ANGGREANI";

        $db = Database::connect();
        $db->beginTransaction();

        try {
        $invoiceId = $model->create([
            'quotation_id' => !empty($_POST['quotation_id'])
                ? (int) $_POST['quotation_id']
                : null,
            'customer_id' => !empty($_POST['customer_id'])
                ? (int) $_POST['customer_id']
                : null,
            'rental_id' => null,
            'no_invoice' => $_POST['no_invoice'],
            'invoice_type' => $invoiceType,
            'dp_type' => $dpType,
            'dp_percentage' => $dpPercentage,
            'dp_nominal' => $dpNominal,
            'tax_type' => $taxType,
            'tax_percent' => $totals['tax_percent'],
            'tax_amount' => $totals['tax_amount'],
            'billing_total' => $totals['billing_total'],
            'grand_total' => $invoiceType === 'dp'
                ? $dpNominal
                : $totals['grand_total'],

            'remaining_bill' => $invoiceType === 'dp'
                ? $remainingBill
                : 0,
            'payment_account' => $paymentAccount,
            'bank_account_id' => $_POST['bank_account_id'] ?? null,
            'customer_name' => $_POST['customer_name'] ?? '',
            'customer_phone' => $_POST['customer_phone'] ?? '',
            'lokasi' => $_POST['lokasi'] ?? '',
            'invoice_date' => !empty($_POST['invoice_date']) ? $_POST['invoice_date'] : date('Y-m-d'),
            'due_date' => !empty($_POST['due_date']) ? $_POST['due_date'] : null,
            'subtotal' => $subtotalBeforeDiscount,
            'total_discount' => $totalDiscount,
            'paid_amount' => $paidAmount,
            'status_payment' => $_POST['status_payment'] ?? 'waiting payment',
            'notes' => $_POST['notes'] ?? '',
            'created_by' => $_SESSION['user_id'],
        ]);

        foreach ($itemNames as $index => $itemName) {

            if (trim($itemName) === '') {
                continue;
            }

            $qty = (int) ($_POST['qty'][$index] ?? 1);
            $duration = (int) ($_POST['duration'][$index] ?? 1);
            $unitPrice = (float) ($_POST['unit_price'][$index] ?? 0);
            $discount = (float) ($_POST['discount'][$index] ?? 0);

            $billingType = $_POST['billing_type'][$index] ?? 'unit';

            $rentalPeriodType = in_array($billingType, ['daily', 'weekly', 'monthly'])
            ? $billingType
            : 'daily';

            if (in_array($billingType, ['daily', 'weekly', 'monthly'])) {

                $beforeDiscount = $qty * $duration * $unitPrice;

            } elseif (in_array($billingType, ['package', 'fixed'])) {

                $beforeDiscount = $unitPrice;

            } else {

                $beforeDiscount = $qty * $unitPrice;
            }

            $subtotal = max(0, $beforeDiscount - $discount);

            $model->addItem($invoiceId, [
                'product_id' => $_POST['product_id'][$index] ?? null,
                'item_name' => $itemName,
                'category' => $_POST['category'][$index] ?? '',
                'item_type' => $_POST['item_type'][$index] ?? 'rental_unit',
                'billing_type' => $billingType,
                'rental_period_type' => $rentalPeriodType,
                'qty' => $qty,
                'duration' => $duration,
                'unit_price' => $unitPrice,
                'discount' => $discount,
                'subtotal' => $subtotal
            ]);
        }

        $invoice = $model->find($invoiceId);

        $this->createInvoiceJournal($invoice);

        activity_log(
            'Keuangan - Invoice',
            'create',
            'Membuat invoice: ' . ($_POST['no_invoice'] ?? '-'),
            $invoiceId,
            $_POST['no_invoice'] ?? null
        );
        if (!empty($_POST['quotation_id'])) {
    $quotationModel = new Quotation();
    $quotationModel->markAsConvertedInvoice($_POST['quotation_id']);
    }
        $db->commit();
        } catch (Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            throw $e;
        }

        $_SESSION['success'] = 'Invoice berhasil dibuat.';
        $this->redirect('invoices');
    }

    public function show()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('invoices');
        }

        $model = new Invoice();

        $invoice = $model->find($id);

        if (!$invoice) {

            activity_log(
                'Keuangan - Invoice',
                'view_failed',
                'Gagal membuka detail invoice karena data tidak ditemukan',
                $id
            );

            $this->redirect('invoices');
        }

        $items = $model->getItems($id);

        $paymentModel = new InvoicePayment();

        $invoiceTotal = (float) ($invoice['billing_total'] ?? 0);
        $totalPaid = $paymentModel->getTotalPaid($id);
        $remainingAmount = $paymentModel->getRemainingAmount($id, $invoiceTotal);
        $computedStatus = $paymentModel->getComputedStatus($id, $invoiceTotal);

        activity_log(
            'Keuangan - Invoice',
            'view',
            'Melihat detail invoice',
            $id,
            $invoice['no_invoice'] ?? null
        );

        $this->view('invoices/show', [
            'title' => 'Detail Invoice',
            'invoice' => $invoice,
            'items' => $items,
            'payments' => $paymentModel->getByInvoice($id),
            'invoiceTotal' => $invoiceTotal,
            'totalPaid' => $totalPaid,
            'remainingAmount' => $remainingAmount,
            'computedStatus' => $computedStatus,
        ]);
    }

    public function print()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;
        $format = $_GET['format'] ?? 'rental';

        if (!$id) {
            die('Invoice tidak ditemukan');
        }

        $model = new Invoice();

        $invoice = $model->find($id);
        $items = $model->getItems($id);

        if (!$invoice) {

            activity_log(
                'Keuangan - Invoice',
                'print_failed',
                'Gagal print invoice karena data tidak ditemukan',
                $id
            );

            die('Invoice tidak ditemukan');
        }

        activity_log(
            'Keuangan - Invoice',
            'print',
            'Print invoice',
            $id,
            $invoice['no_invoice'] ?? null
        );

        if ($format === 'service') {
            require __DIR__ . '/../Views/invoices/print-service.php';
            return;
        }

        require __DIR__ . '/../Views/invoices/print-rental.php';
    }

    public function edit()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('invoices');
        }

        $model = new Invoice();
        $customerModel = new Customer();
        $productModel = new QuotationProduct();

        $invoice = $model->find($id);

        if (!$invoice) {

            activity_log(
                'Keuangan - Invoice',
                'edit_failed',
                'Gagal membuka form edit invoice karena data tidak ditemukan',
                $id
            );

            $this->redirect('invoices');
        }

        activity_log(
            'Keuangan - Invoice',
            'edit_form',
            'Membuka form edit invoice',
            $id,
            $invoice['no_invoice'] ?? null
        );

        $this->view('invoices/edit', [
            'title' => 'Edit Invoice',
            'invoice' => $invoice,
            'items' => $model->getItems($id),
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
            $this->redirect('invoices');
        }

        $model = new Invoice();

        $oldInvoice = $model->find($id);

        if (!$oldInvoice) {

            activity_log(
                'Keuangan - Invoice',
                'update_failed',
                'Gagal update invoice karena data tidak ditemukan',
                $id
            );

            $this->redirect('invoices');
        }

        $itemNames = $_POST['item_name'] ?? [];

        $subtotalBeforeDiscount = 0;
        $totalDiscount = 0;
        $grandTotal = 0;

        foreach ($itemNames as $index => $itemName) {

            if (trim($itemName) === '') {
                continue;
            }

            $qty = (int) ($_POST['qty'][$index] ?? 1);
            $duration = (int) ($_POST['duration'][$index] ?? 1);
            $unitPrice = (float) ($_POST['unit_price'][$index] ?? 0);
            $discount = (float) ($_POST['discount'][$index] ?? 0);

            $billingType = $_POST['billing_type'][$index] ?? 'daily';

            if (in_array($billingType, ['daily', 'weekly', 'monthly'])) {

                $beforeDiscount = $qty * $duration * $unitPrice;

            } elseif (in_array($billingType, ['package', 'fixed'])) {

                $beforeDiscount = $unitPrice;

            } else {

                $beforeDiscount = $qty * $unitPrice;
            }

            $subtotal = max(0, $beforeDiscount - $discount);

            $subtotalBeforeDiscount += $beforeDiscount;
            $totalDiscount += $discount;
            $grandTotal += $subtotal;
        }

        $paidAmount = (float) ($_POST['paid_amount'] ?? 0);

        $invoiceType = $_POST['invoice_type'] ?? 'full';
        $taxType = $_POST['tax_type'] ?? 'non_ppn';

        $totals = $this->calculateInvoiceTotals($grandTotal, $taxType);
        $dpType = $_POST['dp_type'] ?? 'percentage';
        $dpPercentage = (float) ($_POST['dp_percentage'] ?? 0);
        $dpNominal = (float) ($_POST['dp_nominal'] ?? 0);
        $remainingBill = (float) ($_POST['remaining_bill'] ?? 0);

        if ($invoiceType !== 'dp') {
            $dpType = null;
            $dpPercentage = 0;
            $dpNominal = 0;
            $remainingBill = 0;
        }

        $paymentAccount = $taxType === 'include_ppn'
        ? "Bank BCA\nCabang ARKADIA\n5405197984\nPT. Micool Berkah Bersama"
        : "Bank BCA\n1660867313\nLINA ANGGREANI";

        $db = Database::connect();
        $db->beginTransaction();

        try {
        $model->update($id, [
            'invoice_type' => $invoiceType,
            'customer_id' => $_POST['customer_id'] ?? '',
            'dp_type' => $dpType,
            'dp_percentage' => $dpPercentage,
            'dp_nominal' => $dpNominal,
            'tax_type' => $taxType,
            'tax_percent' => $totals['tax_percent'],
            'tax_amount' => $totals['tax_amount'],
            'billing_total' => $totals['billing_total'],
            'grand_total' => $invoiceType === 'dp'
                ? $dpNominal
                : $totals['grand_total'],

            'remaining_bill' => $invoiceType === 'dp'
                ? $remainingBill
                : 0,
            'payment_account' => $paymentAccount,
            'customer_name' => $_POST['customer_name'] ?? '',
            'customer_phone' => $_POST['customer_phone'] ?? '',
            'lokasi' => $_POST['lokasi'] ?? '',
            'invoice_date' => !empty($_POST['invoice_date']) ? $_POST['invoice_date'] : date('Y-m-d'),
            'due_date' => !empty($_POST['due_date']) ? $_POST['due_date'] : null,
            'subtotal' => $subtotalBeforeDiscount,
            'total_discount' => $totalDiscount,
            'paid_amount' => $paidAmount,
            'status_payment' => $_POST['status_payment'] ?? 'waiting payment',
            'notes' => $_POST['notes'] ?? '',
        ]);

        $model->deleteItems($id);

        foreach ($itemNames as $index => $itemName) {

            if (trim($itemName) === '') {
                continue;
            }

            $qty = (int) ($_POST['qty'][$index] ?? 1);
            $duration = (int) ($_POST['duration'][$index] ?? 1);
            $unitPrice = (float) ($_POST['unit_price'][$index] ?? 0);
            $discount = (float) ($_POST['discount'][$index] ?? 0);

            $billingType = $_POST['billing_type'][$index] ?? 'unit';

            if (in_array($billingType, ['daily', 'weekly', 'monthly'])) {
                $beforeDiscount = $qty * $duration * $unitPrice;
            } elseif (in_array($billingType, ['package', 'fixed'])) {
                $beforeDiscount = $unitPrice;
            } else {
                $beforeDiscount = $qty * $unitPrice;
            }

            $subtotal = max(0, $beforeDiscount - $discount);

            $model->addItem($id, [
                'product_id' => $_POST['product_id'][$index] ?? null,
                'item_name' => $itemName,
                'category' => $_POST['category'][$index] ?? '',
                'item_type' => $_POST['item_type'][$index] ?? 'rental_unit',
                'billing_type' => $billingType,
                'rental_period_type' => in_array($billingType, ['daily', 'weekly', 'monthly']) ? $billingType : 'daily',
                'qty' => $qty,
                'duration' => $duration,
                'unit_price' => $unitPrice,
                'discount' => $discount,
                'subtotal' => $subtotal
            ]);
        }

        $journalModel = new JournalEntry();

        $journalModel->deleteByReference('invoice', $id);

        $updatedInvoice = $model->find($id);

        $this->createInvoiceJournal($updatedInvoice);

        $db->commit();
        } catch (Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            throw $e;
        }

        activity_log(
            'Keuangan - Invoice',
            'update',
            'Mengubah invoice',
            $id,
            $oldInvoice['no_invoice'] ?? null
        );

        $this->redirect('invoices');
    }

    public function delete()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('invoices');
        }

        $model = new Invoice();

        $invoice = $model->find($id);

        if (!$invoice) {

            activity_log(
                'Keuangan - Invoice',
                'delete_failed',
                'Gagal menghapus invoice karena data tidak ditemukan',
                $id
            );

            $this->redirect('invoices');
        }

        if (($invoice['paid_amount'] ?? 0) > 0) {

            activity_log(
                'Keuangan - Invoice',
                'delete_failed',
                'Gagal menghapus invoice karena sudah memiliki pembayaran',
                $id,
                $invoice['no_invoice'] ?? null
            );

            echo "Invoice sudah memiliki pembayaran dan tidak bisa dihapus.";
            exit;
        }

        $journalModel = new JournalEntry();

        $db = Database::connect();
        $db->beginTransaction();

        try {
            $journalModel->deleteByReference('invoice', $id);
            $journalModel->deleteByReference('invoice_payment', $id);

            $model->deleteWithItems($id);
            $db->commit();
        } catch (Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            throw $e;
        }

        activity_log(
            'Keuangan - Invoice',
            'delete',
            'Menghapus invoice',
            $id,
            $invoice['no_invoice'] ?? null
        );

        $this->redirect('invoices');
    }

    public function createFromQuotation()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $quotationId = (int) ($_GET['id'] ?? $_GET['quotation_id'] ?? 0);

        if (!$quotationId) {
            $_SESSION['error'] = 'Quotation tidak ditemukan.';
            $this->redirect('quotations');
        }

        $this->redirect('invoices-create', ['quotation_id' => $quotationId]);
    }

    private function calculateInvoiceTotals($grandTotalBeforeTax, $taxType)
    {
        $grandTotalBeforeTax = (float) $grandTotalBeforeTax;

        $taxPercent = $taxType === 'include_ppn' ? 11 : 0;

        $taxAmount = $taxType === 'include_ppn'
            ? round($grandTotalBeforeTax * 0.11)
            : 0;

        $billingTotal = $grandTotalBeforeTax;
        $grandTotal = $billingTotal + $taxAmount;

        return [
            'tax_percent'   => $taxPercent,
            'tax_amount'    => $taxAmount,
            'billing_total' => $billingTotal,
            'grand_total'   => $grandTotal,
        ];
    }
    private function createInvoiceJournal($invoice)
    {
        $journal = new JournalEntry();

        $receivableAccountId = $this->getAccountIdByCode('1-20000'); // Piutang Usaha
        $incomeAccountId     = $this->getAccountIdByCode('4-10000'); // Pendapatan umum sementara
        $advanceAccountId    = $this->getAccountIdByCode('2-20000'); // Uang Muka Customer
        $taxAccountId        = $this->getAccountIdByCode('2-30000'); // PPN Keluaran

        $grandTotal  = (float) ($invoice['grand_total'] ?? 0);
        $taxAmount   = (float) ($invoice['tax_amount'] ?? 0);
        $netAmount   = max(0, $grandTotal - $taxAmount);
        $invoiceType = $invoice['invoice_type'] ?? 'full';

        $lines = [];

        $lines[] = [
            'account_id'  => $receivableAccountId,
            'debit'       => $grandTotal,
            'credit'      => 0,
            'description' => 'Piutang Invoice ' . ($invoice['no_invoice'] ?? '-')
        ];

        if ($invoiceType === 'dp') {
            $lines[] = [
                'account_id'  => $advanceAccountId,
                'debit'       => 0,
                'credit'      => $netAmount,
                'description' => 'Uang Muka Customer ' . ($invoice['no_invoice'] ?? '-')
            ];
        } else {
            $lines[] = [
                'account_id'  => $incomeAccountId,
                'debit'       => 0,
                'credit'      => $netAmount,
                'description' => 'Pendapatan Invoice ' . ($invoice['no_invoice'] ?? '-')
            ];
        }

        if ($taxAmount > 0) {
            $lines[] = [
                'account_id'  => $taxAccountId,
                'debit'       => 0,
                'credit'      => $taxAmount,
                'description' => 'PPN Keluaran ' . ($invoice['no_invoice'] ?? '-')
            ];
        }

        return $journal->create([
            'journal_date'   => $invoice['invoice_date'] ?? date('Y-m-d'),
            'reference_type' => 'invoice',
            'reference_id'   => $invoice['id'],
            'description'    => 'Invoice ' . ($invoice['no_invoice'] ?? '-')
        ], $lines);
    }

    private function getAccountIdByCode($accountCode)
    {
        $db = Database::connect();

        $stmt = $db->prepare("
            SELECT id
            FROM chart_of_accounts
            WHERE account_code = ?
            LIMIT 1
        ");

        $stmt->execute([$accountCode]);

        $accountId = $stmt->fetchColumn();

        if (!$accountId) {
            throw new Exception('COA tidak ditemukan: ' . $accountCode);
        }

        return $accountId;
    }
}
