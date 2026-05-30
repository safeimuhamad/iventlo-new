<?php

class ClientEventController extends ClientPortalController
{
    public function index()
    {
        $this->requirePortal();
        $events = (new ClientEvent())->accessibleForUser($this->currentUserId(), 100);

        activity_log('Client Portal Event', 'view', 'Melihat daftar event client');

        $this->portalView('client/events/index', [
            'title' => 'Event Saya',
            'events' => $events
        ]);
    }

    public function show()
    {
        $this->requirePortal();
        $event = $this->accessibleEvent($_GET['id'] ?? 0);
        $userId = $this->currentUserId();

        activity_log('Client Portal Event', 'view', 'Melihat event: ' . ($event['event_code'] ?? '-'), $event['id']);

        $this->portalView('client/events/show', [
            'title' => 'Detail Event',
            'event' => $event,
            'milestones' => (new ClientMilestone())->visibleForEvent($event['id'], $userId),
            'documents' => (new ClientDocument())->visibleForEvent($event['id'], $userId),
            'approvals' => (new ClientApproval())->forEvent($event['id'], $userId)
        ]);
    }

    public function attendees()
    {
        $this->requirePortal();
        $event = $this->manageableEvent($_GET['event_id'] ?? 0);
        $tickets = new EventTicket();

        activity_log('Client Portal Attendance', 'view', 'Melihat data peserta event client', $event['id']);

        $this->portalView('client/events/attendees', [
            'title' => 'Peserta & Kehadiran',
            'event' => $event,
            'stats' => $tickets->stats($event['id']),
            'orders' => $tickets->ordersForEvent($event['id']),
            'attendees' => $tickets->attendeesForEvent($event['id'])
        ]);
    }

    public function activateBarcode()
    {
        $this->requirePortal();
        $event = $this->manageableEvent($_GET['event_id'] ?? 0);
        (new MasterEvent())->activateAttendanceBarcode($event['id']);

        activity_log('Client Portal Attendance', 'activate_barcode', 'Mengaktifkan QR Code kehadiran event', $event['id']);
        $_SESSION['success'] = 'QR Code check-in berhasil diaktifkan dan siap dicetak.';
        $this->redirect('client/events/' . $event['id'] . '/peserta');
    }

    public function barcode()
    {
        $this->requirePortal();
        $event = $this->manageableEvent($_GET['event_id'] ?? 0);

        if (empty($event['attendance_token']) || empty($event['attendance_checkin_enabled'])) {
            $_SESSION['error'] = 'Aktifkan QR Code check-in terlebih dahulu.';
            $this->redirect('client/events/' . $event['id'] . '/peserta');
        }

        $this->portalView('client/events/barcode', [
            'title' => 'Cetak QR Code Check-in',
            'event' => $event,
            'scanUrl' => frontUrl('member-attendance-scan', ['slug' => $event['attendance_token']])
        ]);
    }
}
