<?php

class ClientApproval
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function forEvent($eventId, $userId)
    {
        $stmt = $this->db->prepare("
            SELECT ar.id, ar.event_id, ar.module_name, ar.reference_no, ar.current_level,
                ar.status, ar.requested_at, ar.completed_at
            FROM approval_requests ar
            INNER JOIN event_client_access a ON a.event_id = ar.event_id
            WHERE ar.event_id = ?
            AND a.user_id = ?
            AND a.status = 'active'
            ORDER BY ar.requested_at DESC, ar.id DESC
        ");
        $stmt->execute([(int) $eventId, (int) $userId]);

        return $stmt->fetchAll();
    }

    public function findAccessible($approvalId, $userId)
    {
        $stmt = $this->db->prepare("
            SELECT ar.id, ar.event_id, ar.module_name, ar.reference_no, ar.current_level,
                ar.status, ar.requested_at, ar.completed_at,
                e.event_code, e.title AS event_title,
                a.access_level
            FROM approval_requests ar
            INNER JOIN master_events e ON e.id = ar.event_id
            INNER JOIN event_client_access a ON a.event_id = ar.event_id
            WHERE ar.id = ?
            AND a.user_id = ?
            AND a.status = 'active'
            LIMIT 1
        ");
        $stmt->execute([(int) $approvalId, (int) $userId]);

        return $stmt->fetch();
    }

    public function countPendingForUser($userId)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) AS total
            FROM approval_requests ar
            INNER JOIN event_client_access a ON a.event_id = ar.event_id
            WHERE a.user_id = ?
            AND a.status = 'active'
            AND ar.status = 'waiting_approval'
        ");
        $stmt->execute([(int) $userId]);

        return (int) ($stmt->fetch()['total'] ?? 0);
    }
}
