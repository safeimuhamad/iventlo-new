<?php

class ClientDashboardController extends ClientPortalController
{
    public function index()
    {
        $this->requirePortal();
        $userId = $this->currentUserId();

        activity_log('Client Portal', 'view', 'Melihat dashboard client');

        $this->portalView('client/dashboard', [
            'title' => 'Client Portal Dashboard',
            'events' => (new ClientEvent())->accessibleForUser($userId, 5),
            'totalEvents' => (new ClientEvent())->countForUser($userId),
            'pendingApprovals' => (new ClientApproval())->countPendingForUser($userId),
            'upcomingMilestones' => (new ClientMilestone())->upcomingForUser($userId),
            'unreadNotifications' => (new ClientNotification())->countUnread($userId)
        ]);
    }
}
