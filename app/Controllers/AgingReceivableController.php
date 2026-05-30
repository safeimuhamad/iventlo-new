<?php

class AgingReceivableController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $invoiceModel = new Invoice();

        $this->view('aging-receivables/index', [
            'title' => 'Aging Piutang',
            'invoices' => $invoiceModel->getAgingReceivables()
        ]);
    }
}