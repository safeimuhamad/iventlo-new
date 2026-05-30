<?php

class ClientPortalController extends Controller
{
    protected function requirePortal()
    {
        requireClientPortalLogin();
    }

    protected function currentUserId()
    {
        return (int) ($_SESSION['user_id'] ?? 0);
    }

    protected function portalView($view, $data = [])
    {
        if (!isset($data['clientMenuEvent'])) {
            if (!empty($data['event'])) {
                $data['clientMenuEvent'] = $data['event'];
            } elseif (!empty($data['events'])) {
                $data['clientMenuEvent'] = $data['events'][0] ?? null;
            } else {
                $events = (new ClientEvent())->accessibleForUser($this->currentUserId(), 1);
                $data['clientMenuEvent'] = $events[0] ?? null;
            }
        }

        $this->frontView($view, $data);
    }

    protected function accessibleEvent($eventId)
    {
        $event = (new ClientEvent())->findAccessible((int) $eventId, $this->currentUserId());

        if (!$event) {
            $_SESSION['error'] = 'Event tidak ditemukan atau tidak ditugaskan untuk akun Anda.';
            $this->redirect('client/events');
        }

        return $event;
    }

    protected function manageableEvent($eventId)
    {
        $event = $this->accessibleEvent($eventId);

        if (($event['access_level'] ?? '') !== 'admin') {
            $_SESSION['error'] = 'Akses viewer hanya dapat melihat data event.';
            $this->redirect('client/events/' . (int) $eventId);
        }

        return $event;
    }
}
