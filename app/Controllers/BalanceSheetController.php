<?php

class BalanceSheetController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $endDate = $_GET['end_date'] ?? date('Y-m-d');

        $coaModel = new ChartOfAccount();

        $assets = $coaModel->getBalanceSheetAccounts('asset', $endDate);

        $liabilities = $coaModel->getBalanceSheetAccounts('liability', $endDate);

        $equities = $coaModel->getBalanceSheetAccounts('equity', $endDate);

        $profit = $coaModel->getNetProfitUntil($endDate);

        $this->view('balance-sheet/index', [
            'title' => 'Neraca',
            'endDate' => $endDate,
            'assets' => $assets,
            'liabilities' => $liabilities,
            'equities' => $equities,
            'profit' => $profit
        ]);
    }
}