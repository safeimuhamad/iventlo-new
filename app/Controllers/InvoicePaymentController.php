<?php

class InvoicePaymentController extends Controller
{
    public function create()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $invoiceId = $_GET['invoice_id'] ?? null;

        if (!$invoiceId) {
            $this->redirect('invoices');
        }

        $invoiceModel = new Invoice();
        $invoice = $invoiceModel->find($invoiceId);

        if (!$invoice) {

            activity_log(
                'Keuangan - Pembayaran Invoice',
                'create_form_failed',
                'Gagal membuka form pembayaran karena invoice tidak ditemukan',
                $invoiceId
            );

            $this->redirect('invoices');
        }

        $bankAccountModel = new BankAccount();

        activity_log(
            'Keuangan - Pembayaran Invoice',
            'create_form',
            'Membuka form pembayaran invoice',
            $invoiceId,
            $invoice['no_invoice'] ?? null
        );

        $this->view('invoice-payments/create', [
            'title' => 'Terima Pembayaran',
            'invoice' => $invoice,
            'bankAccounts' => $bankAccountModel->getActive()
        ]);
    }

    public function store()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $invoiceId = $_POST['invoice_id'] ?? null;

        if (!$invoiceId) {
            $this->redirect('invoices');
        }

        $invoiceModel = new Invoice();
        $paymentModel = new InvoicePayment();

        $invoice = $invoiceModel->find($invoiceId);

        if (!$invoice) {

            activity_log(
                'Keuangan - Pembayaran Invoice',
                'create_failed',
                'Gagal mencatat pembayaran karena invoice tidak ditemukan',
                $invoiceId
            );

            $this->redirect('invoices');
        }

        $paymentAmount = (float) str_replace('.', '', $_POST['payment_amount'] ?? 0);
        $bankAccountId = $_POST['bank_account_id'] ?? null;

        if ($paymentAmount <= 0) {

            activity_log(
                'Keuangan - Pembayaran Invoice',
                'create_failed',
                'Gagal mencatat pembayaran karena nominal tidak valid',
                $invoiceId,
                $invoice['no_invoice'] ?? null
            );

            $_SESSION['error'] = 'Nominal pembayaran harus lebih dari 0.';
            $this->redirect('invoice-payments-create', ['invoice_id' => $invoiceId]);
        }

        $bankTransactionModel = new BankTransaction();
        $bankAccountModel = new BankAccount();
        $coaModel = new ChartOfAccount();
        $journalModel = new JournalEntry();
        $bank = $bankAccountId ? $bankAccountModel->find($bankAccountId) : null;
        $bankAccountCoaId = $bank['coa_id'] ?? null;
        $receivableCoaId = $coaModel->getIdByCode('1-20000');

        if (!$bankAccountCoaId || !$receivableCoaId) {
            $_SESSION['error'] = 'COA rekening bank atau piutang belum disetting.';
            $this->redirect('invoice-payments-create', ['invoice_id' => $invoiceId]);
        }

        $db = Database::connect();
        $db->beginTransaction();

        try {
        $paymentId = $paymentModel->create([
            'invoice_id' => $invoiceId,
            'bank_account_id' => $bankAccountId,
            'payment_date' => $_POST['payment_date'] ?? date('Y-m-d'),
            'payment_amount' => $paymentAmount,
            'payment_method' => $_POST['payment_method'] ?? '',
            'bank_account' => $_POST['bank_account'] ?? '',
            'reference_no' => $_POST['reference_no'] ?? '',
            'notes' => $_POST['notes'] ?? '',
        ]);

        $bankTransactionModel->create([
                'bank_account_id' => $bankAccountId,
                'transaction_date' => $_POST['payment_date'] ?? date('Y-m-d'),
                'transaction_type' => 'in',
                'reference_type' => 'invoice_payment',
                'reference_id' => $paymentId ?: $invoiceId,
                'description' => 'Pembayaran invoice ' . ($invoice['no_invoice'] ?? ''),
                'amount' => $paymentAmount
        ]);

        $bankAccountModel->increaseBalance($bankAccountId, $paymentAmount);

        $totalPaid = $paymentModel->getTotalPaid($invoiceId);
        $grandTotal = (float) ($invoice['billing_total'] ?? 0);

        $invoiceModel->updatePaymentStatus($invoiceId, $totalPaid, $grandTotal);

        $journalModel->create([
                'journal_date' => $_POST['payment_date'] ?? date('Y-m-d'),
                'reference_type' => 'invoice_payment',
                'reference_id' => $paymentId ?: $invoiceId,
                'description' => 'Pembayaran invoice ' . ($invoice['no_invoice'] ?? '')
            ], [
                [
                    'account_id' => $bankAccountCoaId,
                    'debit' => $paymentAmount,
                    'credit' => 0,
                    'description' => 'Kas/Bank masuk'
                ],
                [
                    'account_id' => $receivableCoaId,
                    'debit' => 0,
                    'credit' => $paymentAmount,
                    'description' => 'Pelunasan piutang invoice'
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
            'Keuangan - Pembayaran Invoice',
            'create',
            'Mencatat pembayaran invoice sebesar Rp ' . number_format($paymentAmount, 0, ',', '.'),
            $paymentId ?: $invoiceId,
            $invoice['no_invoice'] ?? null
        );

        $_SESSION['success'] = 'Pembayaran invoice berhasil dicatat.';

        $this->redirect('invoices-show', ['id' => $invoiceId]);
    }
}
