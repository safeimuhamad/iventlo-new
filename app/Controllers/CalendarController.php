<?php

class CalendarController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $rentalModel = new Rental();
        $rentals = $rentalModel->getCalendarEvents();

        $events = [];

        foreach ($rentals as $rental) {
            $color = match ($rental['status_rental']) {
                'draft' => '#6c757d',
                'scheduled' => '#0d6efd',
                'on_rent' => '#ffc107',
                'completed' => '#198754',
                default => '#6c757d'
            };

            $events[] = [
                'title' => $rental['no_rental'] . ' - ' . $rental['customer_name'],
                'start' => $rental['tanggal_rental'],
                'end' => date('Y-m-d', strtotime($rental['tanggal_selesai'] . ' +1 day')),
                'url' => url('rentals-show', ['id' => $rental['id']]),
                'backgroundColor' => $color,
                'borderColor' => $color
            ];
        }

        $this->view('calendar/index', [
            'title' => 'Kalender Jadwal Rental',
            'eventsJson' => json_encode($events)
        ]);
    }
}
