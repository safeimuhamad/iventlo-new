<?php

class ScheduleController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $date = $_GET['date'] ?? date('Y-m-d');

        $rentalModel = new Rental();

        $deliveries = $rentalModel->getDeliverySchedule($date);
        $pickups = $rentalModel->getPickupSchedule($date);

        $this->view('schedules/index', [
            'title' => 'Jadwal Harian',
            'date' => $date,
            'deliveries' => $deliveries,
            'pickups' => $pickups
        ]);
    }
}