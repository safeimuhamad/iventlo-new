<?php

class VendorPayableAgingController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $billModel = new VendorBill();

        $this->view('vendor-payable-aging/index', [
            'title' => 'Aging Hutang Vendor',
            'bills' => $billModel->getAgingPayables()
        ]);
    }
}