<?php

class ProfitLossController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-d');

        $coaModel = new ChartOfAccount();

        $this->view('profit-loss/index', [
            'title' => 'Laba Rugi',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'incomeAccounts' => $coaModel->getProfitLossByType('income', $startDate, $endDate),
            'expenseAccounts' => $coaModel->getProfitLossByType('expense', $startDate, $endDate),
        ]);
    }
}