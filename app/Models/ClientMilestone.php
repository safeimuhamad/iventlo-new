<?php

class ClientMilestone
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function visibleForEvent($eventId, $userId)
    {
        $stmt = $this->db->prepare("
            SELECT m.id, m.event_id, m.title, m.description, m.due_date, m.status, m.sort_order
            FROM event_client_milestones m
            INNER JOIN event_client_access a ON a.event_id = m.event_id
            WHERE m.event_id = ?
            AND a.user_id = ?
            AND a.status = 'active'
            AND m.visible_to_client = 1
            ORDER BY m.sort_order ASC, m.due_date ASC, m.id ASC
        ");
        $stmt->execute([(int) $eventId, (int) $userId]);

        return $stmt->fetchAll();
    }

    public function upcomingForUser($userId, $limit = 5)
    {
        $stmt = $this->db->prepare("
            SELECT m.title, m.due_date, m.status, e.id AS event_id, e.title AS event_title
            FROM event_client_milestones m
            INNER JOIN master_events e ON e.id = m.event_id
            INNER JOIN event_client_access a ON a.event_id = m.event_id
            WHERE a.user_id = ?
            AND a.status = 'active'
            AND m.visible_to_client = 1
            AND m.status <> 'completed'
            ORDER BY COALESCE(m.due_date, '9999-12-31') ASC, m.sort_order ASC
            LIMIT ?
        ");
        $stmt->bindValue(1, (int) $userId, PDO::PARAM_INT);
        $stmt->bindValue(2, (int) $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
