<?php

class BankTransferController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $model = new BankTransfer();

        activity_log(
            'Keuangan - Transfer Bank',
            'view',
            'Melihat daftar transfer antar rekening'
        );

        $this->view('bank-transfers/index', [
            'title' => 'Transfer Antar Rekening',
            'transfers' => $model->getAll()
        ]);
    }

    public function create()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $bankModel = new BankAccount();

        activity_log(
            'Keuangan - Transfer Bank',
            'create_form',
            'Membuka form transfer antar rekening'
        );

        $this->view('bank-transfers/create', [
            'title' => 'Tambah Transfer',
            'bankAccounts' => $bankModel->getActive()
        ]);
    }

    public function store()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $fromBankId = $_POST['from_bank_account_id'] ?? null;
        $toBankId = $_POST['to_bank_account_id'] ?? null;

        if (!$fromBankId || !$toBankId || $fromBankId == $toBankId) {
            echo "Rekening asal dan tujuan tidak valid.";
            exit;
        }

        $amount = (float) str_replace('.', '', $_POST['amount'] ?? 0);

        if ($amount <= 0) {
            echo "Nominal transfer harus lebih dari 0.";
            exit;
        }

        $transferModel = new BankTransfer();
        $bankModel = new BankAccount();
        $trxModel = new BankTransaction();
        $journalModel = new JournalEntry();
        $db = Database::connect();

        $fromBank = $bankModel->find($fromBankId);
        $toBank = $bankModel->find($toBankId);
        $fromCoaId = $fromBank['coa_id'] ?? null;
        $toCoaId = $toBank['coa_id'] ?? null;

        if (!$fromBank || !$toBank || !$fromCoaId || !$toCoaId) {
            $_SESSION['error'] = 'Rekening atau COA rekening asal/tujuan belum disetting.';
            $this->redirect('bank-transfers-create');
        }

        $db->beginTransaction();

        try {

            $transferId = $transferModel->create([
            'transfer_date' => $_POST['transfer_date'] ?? date('Y-m-d'),
            'from_bank_account_id' => $fromBankId,
            'to_bank_account_id' => $toBankId,
            'amount' => $amount,
            'reference_no' => $_POST['reference_no'] ?? '',
            'notes' => $_POST['notes'] ?? '',
            ]);

        // Update saldo bank
            $bankModel->decreaseBalance($fromBankId, $amount);
            $bankModel->increaseBalance($toBankId, $amount);

        // Mutasi keluar
            $trxModel->create([
            'bank_account_id' => $fromBankId,
            'transaction_date' => $_POST['transfer_date'] ?? date('Y-m-d'),
            'transaction_type' => 'out',
            'reference_type' => 'bank_transfer',
            'reference_id' => $transferId,
            'description' => 'Transfer antar rekening keluar',
            'amount' => $amount
            ]);

        // Mutasi masuk
            $trxModel->create([
            'bank_account_id' => $toBankId,
            'transaction_date' => $_POST['transfer_date'] ?? date('Y-m-d'),
            'transaction_type' => 'in',
            'reference_type' => 'bank_transfer',
            'reference_id' => $transferId,
            'description' => 'Transfer antar rekening masuk',
            'amount' => $amount
            ]);

            $journalModel->create([
            'journal_date' => $_POST['transfer_date'] ?? date('Y-m-d'),
            'reference_type' => 'bank_transfer',
            'reference_id' => $transferId,
            'description' => 'Transfer antar rekening'
        ], [
            [
                'account_id' => $toCoaId,
                'debit' => $amount,
                'credit' => 0,
                'description' => 'Kas/Bank tujuan bertambah'
            ],
            [
                'account_id' => $fromCoaId,
                'debit' => 0,
                'credit' => $amount,
                'description' => 'Kas/Bank asal berkurang'
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
            'Keuangan - Transfer Bank',
            'create',
            'Membuat transfer antar rekening sebesar Rp ' . number_format($amount, 0, ',', '.'),
            $transferId,
            $_POST['reference_no'] ?? null
        );

        $this->redirect('bank-transfers');
    }

    public function show()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('bank-transfers');
        }

        $model = new BankTransfer();

        $transfer = $model->find($id);

        if (!$transfer) {
            $this->redirect('bank-transfers');
        }

        activity_log(
            'Keuangan - Transfer Bank',
            'view',
            'Melihat detail transfer bank',
            $id,
            $transfer['reference_no'] ?? null
        );

        $this->view('bank-transfers/show', [
            'title' => 'Detail Transfer Bank',
            'transfer' => $transfer
        ]);
    }

    public function delete()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('bank-transfers');
        }

        $transferModel = new BankTransfer();
        $bankModel = new BankAccount();
        $trxModel = new BankTransaction();
        $journalModel = new JournalEntry();
        $db = Database::connect();

        $transfer = $transferModel->find($id);

        if (!$transfer) {
            $this->redirect('bank-transfers');
        }

        $amount = (float) ($transfer['amount'] ?? 0);

        $db->beginTransaction();

        try {
            // rollback saldo
            $bankModel->increaseBalance($transfer['from_bank_account_id'], $amount);
            $bankModel->decreaseBalance($transfer['to_bank_account_id'], $amount);

            // hapus mutasi & jurnal
            $trxModel->deleteByReference('bank_transfer', $id);
            $journalModel->deleteByReference('bank_transfer', $id);

            // hapus transfer
            $transferModel->delete($id);

            $db->commit();
        } catch (Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }

            throw $e;
        }

        activity_log(
            'Keuangan - Transfer Bank',
            'delete',
            'Menghapus transfer antar rekening sebesar Rp ' . number_format($amount, 0, ',', '.'),
            $id,
            $transfer['reference_no'] ?? null
        );

        $this->redirect('bank-transfers');
    }
}
