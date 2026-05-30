<?php

class VendorBillController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $search = trim($_GET['search'] ?? '');
        $status = $_GET['status'] ?? '';

        $limit = 10;
        $currentPage = max(1, (int) ($_GET['p'] ?? 1));
        $offset = ($currentPage - 1) * $limit;

        $model = new VendorBill();

        $totalData = $model->countAll($search, $status);
        $totalPages = (int) ceil($totalData / $limit);

        activity_log(
            'Finance - Vendor Bill',
            'view',
            'Melihat daftar hutang vendor'
        );

        $this->view('vendor-bills/index', [
            'title' => 'Hutang Vendor',
            'bills' => $model->getPaginated($limit, $offset, $search, $status),
            'search' => $search,
            'status' => $status,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalData' => $totalData,
            'limit' => $limit
        ]);
    }

    public function create()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $billModel = new VendorBill();
        $vendorModel = new Vendor();
        $coaModel = new ChartOfAccount();

        activity_log(
            'Finance - Vendor Bill',
            'create_form',
            'Membuka form tambah hutang vendor'
        );

        $this->view('vendor-bills/create', [
            'title' => 'Tambah Hutang Vendor',
            'billNo' => $billModel->generateNumber(),
            'vendors' => $vendorModel->getAll(),
            'accounts' => $coaModel->getActive()
        ]);
    }

    public function store()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $billModel = new VendorBill();

        $accountIds = $_POST['account_id'] ?? [];
        $descriptions = $_POST['description'] ?? [];
        $amounts = $_POST['amount'] ?? [];

        $subtotal = 0;

        foreach ($amounts as $amount) {
            $subtotal += (float) str_replace('.', '', $amount);
        }

        $taxAmount = (float) str_replace('.', '', $_POST['tax_amount'] ?? 0);
        $grandTotal = $subtotal + $taxAmount;

        $db = Database::connect();
        $db->beginTransaction();

        try {
        $billId = $billModel->create([
            'bill_no' => $_POST['bill_no'],
            'vendor_id' => $_POST['vendor_id'],
            'bill_date' => $_POST['bill_date'] ?? date('Y-m-d'),
            'due_date' => $_POST['due_date'] ?? null,
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'grand_total' => $grandTotal,
            'paid_amount' => 0,
            'status_payment' => 'unpaid',
            'notes' => $_POST['notes'] ?? '',
        ]);

        foreach ($accountIds as $index => $accountId) {

            $amount = (float) str_replace('.', '', $amounts[$index] ?? 0);

            if ($amount <= 0) {
                continue;
            }

            $billModel->addItem($billId, [
                'account_id' => $accountId,
                'description' => $descriptions[$index] ?? '',
                'amount' => $amount
            ]);
        }

        $this->createVendorBillJournal($billId);

        $db->commit();
        } catch (Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }

            throw $e;
        }

        activity_log(
            'Finance - Vendor Bill',
            'create',
            'Membuat hutang vendor: ' . ($_POST['bill_no'] ?? '-'),
            $billId,
            $_POST['bill_no'] ?? null
        );

        $this->redirect('vendor-bills');
    }

    public function show()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('vendor-bills');
        }

        $model = new VendorBill();

        $bill = $model->find($id);

        if (!$bill) {

            activity_log(
                'Finance - Vendor Bill',
                'view_failed',
                'Gagal membuka detail hutang vendor karena data tidak ditemukan',
                $id
            );

            $this->redirect('vendor-bills');
        }

        $paymentModel = new VendorBillPayment();

        activity_log(
            'Finance - Vendor Bill',
            'view',
            'Melihat detail hutang vendor: ' . ($bill['bill_no'] ?? '-'),
            $id,
            $bill['bill_no'] ?? null
        );

        $this->view('vendor-bills/show', [
            'title' => 'Detail Hutang Vendor',
            'bill' => $bill,
            'items' => $model->getItems($id),
            'payments' => $paymentModel->getByBill($id)
        ]);
    }

    private function createVendorBillJournal($billId)
    {
        $billModel = new VendorBill();
        $coaModel = new ChartOfAccount();
        $journalModel = new JournalEntry();

        $bill = $billModel->find($billId);
        $items = $billModel->getItems($billId);

        $payableCoaId = $coaModel->getIdByCode('2-10000');
        $taxInputCoaId = $coaModel->getIdByCode('1-30000');

        $journalLines = [];

        foreach ($items as $item) {

            $journalLines[] = [
                'account_id' => $item['account_id'],
                'debit' => (float) $item['amount'],
                'credit' => 0,
                'description' => $item['description'] ?? ''
            ];
        }

        if ((float) ($bill['tax_amount'] ?? 0) > 0 && $taxInputCoaId) {

            $journalLines[] = [
                'account_id' => $taxInputCoaId,
                'debit' => (float) $bill['tax_amount'],
                'credit' => 0,
                'description' => 'PPN Masukan'
            ];
        }

        $journalLines[] = [
            'account_id' => $payableCoaId,
            'debit' => 0,
            'credit' => (float) $bill['grand_total'],
            'description' => 'Hutang vendor ' . ($bill['bill_no'] ?? '')
        ];

        $journalModel->create([
            'journal_date' => $bill['bill_date'],
            'reference_type' => 'vendor_bill',
            'reference_id' => $billId,
            'description' => 'Vendor bill ' . ($bill['bill_no'] ?? '')
        ], $journalLines);

        activity_log(
            'Finance - Vendor Bill',
            'journal_create',
            'Membuat jurnal hutang vendor: ' . ($bill['bill_no'] ?? '-'),
            $billId,
            $bill['bill_no'] ?? null
        );
    }

    public function delete()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('vendor-bills');
        }

        $billModel = new VendorBill();
        $paymentModel = new VendorBillPayment();
        $journalModel = new JournalEntry();

        $bill = $billModel->find($id);

        if (!$bill) {

            activity_log(
                'Finance - Vendor Bill',
                'delete_failed',
                'Gagal menghapus hutang vendor karena data tidak ditemukan',
                $id
            );

            $this->redirect('vendor-bills');
        }

        if ($paymentModel->hasPayment($id)) {

            activity_log(
                'Finance - Vendor Bill',
                'delete_failed',
                'Gagal menghapus hutang vendor karena sudah memiliki pembayaran: ' . ($bill['bill_no'] ?? '-'),
                $id,
                $bill['bill_no'] ?? null
            );

            $_SESSION['error'] =
                'Bill vendor sudah memiliki pembayaran dan tidak bisa dihapus.';

            $this->redirect('vendor-bills-show', ['id' => $id]);
        }

        $db = Database::connect();
        $db->beginTransaction();

        try {
            $billModel->deleteItems($id);

            $journalModel->deleteByReference('vendor_bill', $id);

            $billModel->delete($id);
            $db->commit();
        } catch (Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }

            throw $e;
        }

        activity_log(
            'Finance - Vendor Bill',
            'delete',
            'Menghapus hutang vendor: ' . ($bill['bill_no'] ?? '-'),
            $id,
            $bill['bill_no'] ?? null
        );

        $_SESSION['success'] = 'Vendor bill berhasil dihapus.';

        $this->redirect('vendor-bills');
    }
}
