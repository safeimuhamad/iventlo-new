<?php

class VendorBillPaymentController extends Controller
{
    public function create()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $billId = $_GET['bill_id'] ?? null;

        if (!$billId) {
            $this->redirect('vendor-bills');
        }

        $billModel = new VendorBill();
        $bankModel = new BankAccount();

        $bill = $billModel->find($billId);

        if (!$bill) {

            activity_log(
                'Finance - Pembayaran Vendor Bill',
                'create_form_failed',
                'Gagal membuka form pembayaran vendor karena bill tidak ditemukan',
                $billId
            );

            $this->redirect('vendor-bills');
        }

        activity_log(
            'Finance - Pembayaran Vendor Bill',
            'create_form',
            'Membuka form pembayaran hutang vendor: ' . ($bill['bill_no'] ?? '-'),
            $billId,
            $bill['bill_no'] ?? null
        );

        $this->view('vendor-bill-payments/create', [
            'title' => 'Bayar Hutang Vendor',
            'bill' => $bill,
            'bankAccounts' => $bankModel->getActive()
        ]);
    }

    public function store()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $billId = $_POST['vendor_bill_id'] ?? null;

        if (!$billId) {
            $this->redirect('vendor-bills');
        }

        $billModel = new VendorBill();
        $paymentModel = new VendorBillPayment();
        $bankModel = new BankAccount();
        $trxModel = new BankTransaction();
        $journalModel = new JournalEntry();
        $coaModel = new ChartOfAccount();

        $bill = $billModel->find($billId);

        if (!$bill) {

            activity_log(
                'Finance - Pembayaran Vendor Bill',
                'create_failed',
                'Gagal mencatat pembayaran karena vendor bill tidak ditemukan',
                $billId
            );

            $this->redirect('vendor-bills');
        }

        $bankAccountId = $_POST['bank_account_id'] ?? null;
        $paymentAmount = (float) str_replace('.', '', $_POST['payment_amount'] ?? 0);

        if (!$bankAccountId || $paymentAmount <= 0) {

            activity_log(
                'Finance - Pembayaran Vendor Bill',
                'create_failed',
                'Gagal mencatat pembayaran vendor karena rekening/nominal tidak valid',
                $billId,
                $bill['bill_no'] ?? null
            );

            $_SESSION['error'] = 'Rekening dan nominal pembayaran wajib diisi.';
            $this->redirect('vendor-bill-payments-create', ['bill_id' => $billId]);
        }

        $bank = $bankModel->find($bankAccountId);
        $bankCoaId = $bank['coa_id'] ?? null;
        $payableCoaId = $coaModel->getIdByCode('2-10000');

        if (!$bankCoaId || !$payableCoaId) {
            $_SESSION['error'] = 'COA rekening bank atau Hutang Usaha belum disetting.';
            $this->redirect('vendor-bill-payments-create', ['bill_id' => $billId]);
        }

        $db = Database::connect();
        $db->beginTransaction();

        try {
        $paymentId = $paymentModel->create([
            'vendor_bill_id' => $billId,
            'bank_account_id' => $bankAccountId,
            'payment_date' => $_POST['payment_date'] ?? date('Y-m-d'),
            'payment_amount' => $paymentAmount,
            'payment_method' => $_POST['payment_method'] ?? 'bank_transfer',
            'reference_no' => $_POST['reference_no'] ?? '',
            'notes' => $_POST['notes'] ?? '',
        ]);

        $trxModel->create([
            'bank_account_id' => $bankAccountId,
            'transaction_date' => $_POST['payment_date'] ?? date('Y-m-d'),
            'transaction_type' => 'out',
            'reference_type' => 'vendor_bill_payment',
            'reference_id' => $paymentId ?: $billId,
            'description' => 'Pembayaran hutang vendor ' . ($bill['bill_no'] ?? ''),
            'amount' => $paymentAmount
        ]);

        $bankModel->decreaseBalance($bankAccountId, $paymentAmount);

        $totalPaid = $paymentModel->getTotalPaid($billId);
        $grandTotal = (float) ($bill['grand_total'] ?? 0);

        $billModel->updatePaymentStatus($billId, $totalPaid, $grandTotal);

        $journalModel->create([
            'journal_date' => $_POST['payment_date'] ?? date('Y-m-d'),
            'reference_type' => 'vendor_bill_payment',
            'reference_id' => $paymentId ?: $billId,
            'description' => 'Pembayaran hutang vendor ' . ($bill['bill_no'] ?? '')
        ], [
            [
                'account_id' => $payableCoaId,
                'debit' => $paymentAmount,
                'credit' => 0,
                'description' => 'Hutang vendor berkurang'
            ],
            [
                'account_id' => $bankCoaId,
                'debit' => 0,
                'credit' => $paymentAmount,
                'description' => 'Kas/Bank keluar'
            ]
        ]);

        $db->commit();
        } catch (Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }

            throw $e;
        }

        activity_log(
            'Finance - Pembayaran Vendor Bill',
            'create',
            'Mencatat pembayaran vendor bill sebesar Rp ' . number_format($paymentAmount, 0, ',', '.'),
            $paymentId ?: $billId,
            $bill['bill_no'] ?? null
        );

        $_SESSION['success'] = 'Pembayaran hutang vendor berhasil dicatat.';

        $this->redirect('vendor-bills-show', ['id' => $billId]);
    }
}
