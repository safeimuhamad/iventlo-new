<?php

class BankAccountController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $model = new BankAccount();

        activity_log(
            'Keuangan - Rekening Bank',
            'view',
            'Melihat daftar rekening bank'
        );

        $this->view('bank-accounts/index', [
            'title' => 'Saldo Bank',
            'accounts' => $model->getActive()
        ]);
    }

    public function create()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $coaModel = new ChartOfAccount();

        activity_log(
            'Keuangan - Rekening Bank',
            'create_form',
            'Membuka form tambah rekening bank'
        );

        $this->view('bank-accounts/create', [
            'title' => 'Tambah Rekening',
            'accounts' => $coaModel->getActive()
        ]);
    }

    public function store()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $model = new BankAccount();

        $model->create([
            'account_code' => $_POST['account_code'],
            'account_name' => $_POST['account_name'],
            'bank_name' => $_POST['bank_name'],
            'account_number' => $_POST['account_number'],
            'account_holder' => $_POST['account_holder'],
            'current_balance' => $_POST['current_balance'] ?? 0,
            'coa_id' => $_POST['coa_id'] ?? null,
        ]);

        activity_log(
            'Keuangan - Rekening Bank',
            'create',
            'Menambahkan rekening bank: ' . ($_POST['bank_name'] ?? '-') . ' - ' . ($_POST['account_number'] ?? '-'),
            null,
            $_POST['account_number'] ?? null
        );

        $this->redirect('bank-accounts');
    }

    public function edit()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        $model = new BankAccount();
        $coaModel = new ChartOfAccount();

        $bank = $model->find($id);

        if (!$bank) {
            $this->redirect('bank-accounts');
        }

        activity_log(
            'Keuangan - Rekening Bank',
            'edit_form',
            'Membuka form edit rekening bank: ' . (($bank['bank_name'] ?? '-') . ' - ' . ($bank['account_number'] ?? '-')),
            $id,
            $bank['account_number'] ?? null
        );

        $this->view('bank-accounts/edit', [
            'title' => 'Edit Rekening',
            'bank' => $bank,
            'accounts' => $coaModel->getActive()
        ]);
    }

    public function update()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $model = new BankAccount();

        $id = $_POST['id'] ?? null;

        $oldBank = $id ? $model->find($id) : null;

        $model->update($id, [
            'account_code' => $_POST['account_code'],
            'account_name' => $_POST['account_name'],
            'bank_name' => $_POST['bank_name'],
            'account_number' => $_POST['account_number'],
            'account_holder' => $_POST['account_holder'],
            'current_balance' => $_POST['current_balance'] ?? 0,
            'coa_id' => $_POST['coa_id'] ?? null,
        ]);

        activity_log(
            'Keuangan - Rekening Bank',
            'update',
            'Mengubah rekening bank: ' . ($_POST['bank_name'] ?? '-') . ' - ' . ($_POST['account_number'] ?? '-'),
            $id,
            $_POST['account_number'] ?? ($oldBank['account_number'] ?? null)
        );

        $this->redirect('bank-accounts');
    }
}