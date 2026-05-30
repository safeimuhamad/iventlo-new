<?php

class StockController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $tanggalRental = $_GET['tanggal_rental'] ?? date('Y-m-d');
        $tanggalSelesai = $_GET['tanggal_selesai'] ?? $tanggalRental;

        $unitModel = new Unit();

        $availableUnits = $unitModel->getAvailableGrouped($tanggalRental, $tanggalSelesai);

        $bookedUnits = $unitModel->getBookedGrouped($tanggalRental, $tanggalSelesai);

        $this->view('stock/index', [
            'title' => 'Cek Stok Unit',
            'tanggalRental' => $tanggalRental,
            'tanggalSelesai' => $tanggalSelesai,
            'availableUnits' => $availableUnits,
            'bookedUnits' => $bookedUnits
        ]);
    }
}