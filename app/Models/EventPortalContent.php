<?php

class EventPortalContent
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function forEvent($eventId)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM event_portal_contents
            WHERE event_id = ?
            ORDER BY FIELD(content_type, 'agenda', 'speaker', 'material', 'certificate', 'gallery', 'qna', 'polling', 'information'),
                sort_order ASC, scheduled_at ASC, id DESC
        ");
        $stmt->execute([(int) $eventId]);

        return $stmt->fetchAll();
    }

    public function visibleForEvent($eventId, $type = null)
    {
        $parameters = [(int) $eventId];
        $whereType = '';
        if ($type && in_array($type, self::types(), true)) {
            $whereType = ' AND content_type = ?';
            $parameters[] = $type;
        }

        $stmt = $this->db->prepare("
            SELECT *
            FROM event_portal_contents
            WHERE event_id = ? AND visible_to_member = 1{$whereType}
            ORDER BY sort_order ASC, scheduled_at ASC, id DESC
        ");
        $stmt->execute($parameters);

        return $stmt->fetchAll();
    }

    public function add($eventId, array $data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO event_portal_contents
                (event_id, content_type, title, subtitle, description, scheduled_at, location,
                 file_path, file_name, file_type, sort_order, visible_to_member, created_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            (int) $eventId,
            $data['content_type'],
            $data['title'],
            $data['subtitle'],
            $data['description'],
            $data['scheduled_at'] ?: null,
            $data['location'],
            $data['file_path'],
            $data['file_name'],
            $data['file_type'],
            (int) $data['sort_order'],
            (int) $data['visible_to_member'],
            (int) ($_SESSION['user_id'] ?? 0) ?: null
        ]);
    }

    public function deleteForEvent($id, $eventId)
    {
        $stmt = $this->db->prepare("DELETE FROM event_portal_contents WHERE id = ? AND event_id = ?");
        return $stmt->execute([(int) $id, (int) $eventId]);
    }

    public static function types()
    {
        return ['agenda', 'speaker', 'material', 'gallery', 'qna', 'polling', 'certificate', 'information'];
    }

    public static function labels()
    {
        return [
            'agenda' => 'Agenda',
            'speaker' => 'Pembicara',
            'material' => 'Materi',
            'gallery' => 'Galeri',
            'qna' => 'Q&A',
            'polling' => 'Polling',
            'certificate' => 'Sertifikat',
            'information' => 'Informasi'
        ];
    }
}
