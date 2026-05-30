<?php

class ClientNotificationController extends ClientPortalController
{
    public function index()
    {
        $this->requirePortal();
        $model = new ClientNotification();
        $notifications = $model->forUser($this->currentUserId());
        $model->markReadForUser($this->currentUserId());

        $this->portalView('client/notifications/index', [
            'title' => 'Notifikasi',
            'notifications' => $notifications
        ]);
    }
}
