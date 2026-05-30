<?php

class BankTransactionController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $bankAccountId = $_GET['bank_account_id'] ?? null;
        $model = new BankTransaction();
        $transactions = $model->getAll($bankAccountId);
        $bankAccountModel = new BankAccount();

        $account = null;

        if ($bankAccountId) {
            $account = $bankAccountModel->find($bankAccountId);
        }

        $this->view('bank-transactions/index', [
            'title' => 'Buku Kas / Bank',
            'account' => $account,
            'transactions' => $transactions
        ]);
    }
}