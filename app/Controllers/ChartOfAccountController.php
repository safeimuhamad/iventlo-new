<?php

class ChartOfAccountController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $model = new ChartOfAccount();

        activity_log(
            'Keuangan - Chart of Accounts',
            'view',
            'Melihat daftar Chart of Accounts'
        );

        $this->view('chart-of-accounts/index', [
            'title' => 'Chart of Accounts',
            'accounts' => $model->getActive()
        ]);
    }

    public function create()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        activity_log(
            'Keuangan - Chart of Accounts',
            'create_form',
            'Membuka form tambah akun COA'
        );

        $this->view('chart-of-accounts/create', [
            'title' => 'Tambah Akun'
        ]);
    }

    public function store()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $model = new ChartOfAccount();

        $model->create([
            'account_code' => $_POST['account_code'],
            'account_name' => $_POST['account_name'],
            'account_type' => $_POST['account_type'],
            'normal_balance' => $_POST['normal_balance'],
        ]);

        activity_log(
            'Keuangan - Chart of Accounts',
            'create',
            'Menambahkan akun COA: ' . ($_POST['account_code'] ?? '-') . ' - ' . ($_POST['account_name'] ?? '-'),
            null,
            $_POST['account_code'] ?? null
        );

        $this->redirect('chart-of-accounts');
    }

    public function edit()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        $model = new ChartOfAccount();
        $account = $model->find($id);

        if (!$account) {
            $this->redirect('chart-of-accounts');
        }

        activity_log(
            'Keuangan - Chart of Accounts',
            'edit_form',
            'Membuka form edit akun COA: ' . (($account['account_code'] ?? '-') . ' - ' . ($account['account_name'] ?? '-')),
            $id,
            $account['account_code'] ?? null
        );

        $this->view('chart-of-accounts/edit', [
            'title' => 'Edit Akun',
            'account' => $account
        ]);
    }

    public function update()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $model = new ChartOfAccount();

        $id = $_POST['id'] ?? null;
        $oldAccount = $id ? $model->find($id) : null;

        $model->update($id, [
            'account_code' => $_POST['account_code'],
            'account_name' => $_POST['account_name'],
            'account_type' => $_POST['account_type'],
            'normal_balance' => $_POST['normal_balance'],
        ]);

        activity_log(
            'Keuangan - Chart of Accounts',
            'update',
            'Mengubah akun COA: ' . ($_POST['account_code'] ?? '-') . ' - ' . ($_POST['account_name'] ?? '-'),
            $id,
            $_POST['account_code'] ?? ($oldAccount['account_code'] ?? null)
        );

        $this->redirect('chart-of-accounts');
    }

    public function delete()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        $model = new ChartOfAccount();
        $account = $id ? $model->find($id) : null;

        $model->softDelete($id);

        activity_log(
            'Keuangan - Chart of Accounts',
            'delete',
            'Menghapus akun COA: ' . (($account['account_code'] ?? '-') . ' - ' . ($account['account_name'] ?? '-')),
            $id,
            $account['account_code'] ?? null
        );

        $this->redirect('chart-of-accounts');
    }
}