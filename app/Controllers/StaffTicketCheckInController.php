<?php

class StaffTicketCheckInController extends Controller
{
    public function show()
    {
        $token = trim((string) ($_GET['slug'] ?? ''));
        $tickets = new EventTicket();
        $attendee = $tickets->attendeeForStaffQrToken($token);

        if (!$attendee) {
            http_response_code(404);
            $this->frontView('frontend/member/staff-checkin', [
                'title' => 'QR Tiket Tidak Valid',
                'invalidTicket' => true
            ]);
            return;
        }

        if (isClientPortalUser()) {
            $event = (new ClientEvent())->findAccessible((int) $attendee['event_id'], (int) $_SESSION['user_id']);
            if (!$event || ($event['access_level'] ?? '') !== 'admin') {
                http_response_code(403);
                $this->frontView('frontend/member/staff-checkin', [
                    'title' => 'Akses Ditolak',
                    'denied' => true
                ]);
                return;
            }

            $this->processCheckIn($tickets, $attendee, $token);
            $this->frontView('client/events/staff-checkin', [
                'title' => 'Scan Tiket Peserta',
                'event' => $event,
                'attendee' => $tickets->attendeeForStaffQrToken($token),
                'token' => $token,
                'memberSuccess' => $this->pullMessage('success'),
                'memberError' => $this->pullMessage('error')
            ]);
            return;
        }

        if (!empty($_SESSION['user_id']) && can('master_event.manage')) {
            $this->processCheckIn($tickets, $attendee, $token);
            $this->view('master-events/staff-checkin', [
                'title' => 'Scan Tiket Peserta',
                'attendee' => $tickets->attendeeForStaffQrToken($token),
                'token' => $token
            ]);
            return;
        }

        if (empty($_SESSION['user_id'])) {
            $_SESSION['staff_return_after_login'] = frontUrl('staff-ticket-checkin', ['slug' => $token]);
        }

        http_response_code(403);
        $this->frontView('frontend/member/staff-checkin', [
            'title' => 'Login Petugas Dibutuhkan',
            'loginRequired' => true
        ]);
    }

    private function processCheckIn($tickets, $attendee, $token)
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            return;
        }

        if ($tickets->staffQrCheckIn($token, (int) $attendee['event_id'], (int) $_SESSION['user_id'])) {
            activity_log('Event Attendance', 'qr_check_in', 'Petugas mengonfirmasi QR tiket peserta', (int) $attendee['id']);
            $_SESSION['success'] = 'Tiket valid. Kehadiran peserta berhasil dicatat.';
        } else {
            $_SESSION['error'] = 'Tiket belum dapat check-in, belum dibayar, di luar tanggal acara, atau sudah tercatat.';
        }

        header('Location: ' . frontUrl('staff-ticket-checkin', ['slug' => $token]));
        exit;
    }

    private function pullMessage($key)
    {
        $message = $_SESSION[$key] ?? '';
        unset($_SESSION[$key]);
        return $message;
    }
}
