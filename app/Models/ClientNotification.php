<?php

class ClientNotification
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function forUser($userId)
    {
        $stmt = $this->db->prepare("
            SELECT n.*, e.title AS event_title
            FROM event_client_notifications n
            LEFT JOIN master_events e ON e.id = n.event_id
            WHERE n.user_id = ?
            ORDER BY n.created_at DESC, n.id DESC
            LIMIT 50
        ");
        $stmt->execute([(int) $userId]);

        return $stmt->fetchAll();
    }

    public function countUnread($userId)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) AS total
            FROM event_client_notifications
            WHERE user_id = ?
            AND is_read = 0
        ");
        $stmt->execute([(int) $userId]);

        return (int) ($stmt->fetch()['total'] ?? 0);
    }

    public function createForEvent($eventId, $title, $message, $type)
    {
        $stmt = $this->db->prepare("
            INSERT INTO event_client_notifications (event_id, user_id, title, message, type)
            SELECT a.event_id, a.user_id, ?, ?, ?
            FROM event_client_access a
            WHERE a.event_id = ?
            AND a.status = 'active'
        ");

        return $stmt->execute([$title, $message, $type, (int) $eventId]);
    }

    public function markReadForUser($userId)
    {
        $stmt = $this->db->prepare("UPDATE event_client_notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0");

        return $stmt->execute([(int) $userId]);
    }
}
