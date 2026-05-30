<?php

class GeneralLedgerController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $coaModel = new ChartOfAccount();
        $journalModel = new JournalEntry();

        $accountId = $_GET['account_id'] ?? null;

        $accounts = $coaModel->getActive();

        $ledger = [];

        $selectedAccount = null;

        if ($accountId) {
            $ledger = $journalModel->getLedgerByAccount($accountId);

            foreach ($accounts as $account) {
                if ($account['id'] == $accountId) {
                    $selectedAccount = $account;
                    break;
                }
            }
        }

        $this->view('general-ledger/index', [
            'title' => 'Buku Besar',
            'accounts' => $accounts,
            'ledger' => $ledger,
            'selectedAccount' => $selectedAccount
        ]);
    }
}