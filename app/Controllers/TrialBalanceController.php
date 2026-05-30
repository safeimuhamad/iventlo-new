<?php

class TrialBalanceController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $coaModel = new ChartOfAccount();

        $trialBalance = $coaModel->getTrialBalance();

        $this->view('trial-balance/index', [
            'title' => 'Neraca Saldo',
            'trialBalance' => $trialBalance
        ]);
    }
}