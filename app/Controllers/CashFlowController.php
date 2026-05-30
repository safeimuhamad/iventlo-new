<?php

class CashFlowController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-d');

        $model = new BankTransaction();

        $transactions = $model->getCashFlow($startDate, $endDate);

        $this->view('cash-flow/index', [
            'title' => 'Arus Kas',
            'transactions' => $transactions,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }
}