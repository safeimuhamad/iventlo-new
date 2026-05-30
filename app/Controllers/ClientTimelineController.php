<?php

class ClientTimelineController extends ClientPortalController
{
    public function index()
    {
        $this->requirePortal();
        $event = $this->accessibleEvent($_GET['event_id'] ?? 0);

        $this->portalView('client/timeline/index', [
            'title' => 'Timeline Event',
            'event' => $event,
            'milestones' => (new ClientMilestone())->visibleForEvent($event['id'], $this->currentUserId())
        ]);
    }
}
