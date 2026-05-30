<?php

class ClientDocument
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function visibleForEvent($eventId, $userId)
    {
        $stmt = $this->db->prepare("
            SELECT d.id, d.event_id, d.title, d.category, d.file_name, d.file_type, d.created_at
            FROM event_documents d
            INNER JOIN event_client_access a ON a.event_id = d.event_id
            WHERE d.event_id = ?
            AND a.user_id = ?
            AND a.status = 'active'
            AND d.visible_to_client = 1
            ORDER BY d.created_at DESC, d.id DESC
        ");
        $stmt->execute([(int) $eventId, (int) $userId]);

        return $stmt->fetchAll();
    }

    public function findDownloadable($documentId, $userId)
    {
        $stmt = $this->db->prepare("
            SELECT d.id, d.event_id, d.title, d.file_path, d.file_name, d.file_type
            FROM event_documents d
            INNER JOIN event_client_access a ON a.event_id = d.event_id
            WHERE d.id = ?
            AND a.user_id = ?
            AND a.status = 'active'
            AND d.visible_to_client = 1
            LIMIT 1
        ");
        $stmt->execute([(int) $documentId, (int) $userId]);

        return $stmt->fetch();
    }
}
