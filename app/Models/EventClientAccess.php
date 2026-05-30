<?php

class EventClientAccess
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function findActive($eventId, $userId)
    {
        $stmt = $this->db->prepare("
            SELECT id, event_id, user_id, access_level, status
            FROM event_client_access
            WHERE event_id = ?
            AND user_id = ?
            AND status = 'active'
            LIMIT 1
        ");
        $stmt->execute([(int) $eventId, (int) $userId]);

        return $stmt->fetch();
    }

    public function canManage($eventId, $userId)
    {
        $access = $this->findActive($eventId, $userId);

        return !empty($access) && $access['access_level'] === 'admin';
    }
}
