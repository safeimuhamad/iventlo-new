<?php

class MasterEvent
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function all()
    {
        return $this->db->query("
            SELECT e.*,
                (SELECT COUNT(*) FROM event_client_access a WHERE a.event_id = e.id AND a.status = 'active') AS client_count,
                COALESCE((SELECT SUM(o.quantity) FROM event_ticket_orders o WHERE o.event_id = e.id AND o.payment_status = 'paid'), 0) AS sold_tickets,
                COALESCE((SELECT COUNT(*) FROM event_ticket_attendees a INNER JOIN event_ticket_orders o ON o.id = a.ticket_order_id WHERE a.event_id = e.id AND a.check_in_status = 'arrived' AND o.payment_status = 'paid'), 0) AS attended_count
            FROM master_events e
            ORDER BY e.event_date DESC, e.id DESC
        ")->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM master_events WHERE id = ? LIMIT 1");
        $stmt->execute([(int) $id]);

        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO master_events
                (event_code, public_slug, public_slug_en, title, title_en, client_name, status, event_date, end_date,
                 venue, venue_en, description, description_en, cover_image, is_public, is_paid, ticket_price,
                 participant_quota, ticket_sales_status, progress, created_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['event_code'], $data['public_slug'], $data['public_slug_en'], $data['title'], $data['title_en'],
            $data['client_name'], $data['status'], $data['event_date'] ?: null, $data['end_date'] ?: null,
            $data['venue'], $data['venue_en'], $data['description'], $data['description_en'], $data['cover_image'],
            (int) $data['is_public'], (int) $data['is_paid'], $data['ticket_price'],
            (int) $data['participant_quota'], $data['ticket_sales_status'], (int) $data['progress'],
            $_SESSION['user_id'] ?? null
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE master_events SET event_code = ?, title = ?, client_name = ?, status = ?,
                event_date = ?, end_date = ?, venue = ?, description = ?, progress = ?,
                public_slug = ?, public_slug_en = ?, title_en = ?, venue_en = ?, description_en = ?,
                cover_image = ?, is_public = ?, is_paid = ?, ticket_price = ?,
                participant_quota = ?, ticket_sales_status = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['event_code'], $data['title'], $data['client_name'], $data['status'],
            $data['event_date'] ?: null, $data['end_date'] ?: null, $data['venue'],
            $data['description'], (int) $data['progress'], $data['public_slug'], $data['public_slug_en'],
            $data['title_en'], $data['venue_en'], $data['description_en'], $data['cover_image'],
            (int) $data['is_public'], (int) $data['is_paid'], $data['ticket_price'],
            (int) $data['participant_quota'], $data['ticket_sales_status'], (int) $id
        ]);
    }

    public function clientUsers()
    {
        return $this->db->query("
            SELECT u.id, u.name, u.email
            FROM users u
            INNER JOIN roles r ON r.id = u.role_id
            WHERE LOWER(r.name) = 'client' AND u.status = 'active'
            ORDER BY u.name ASC
        ")->fetchAll();
    }

    public function accessList($eventId)
    {
        $stmt = $this->db->prepare("
            SELECT a.*, u.name, u.email
            FROM event_client_access a
            INNER JOIN users u ON u.id = a.user_id
            WHERE a.event_id = ?
            ORDER BY u.name ASC
        ");
        $stmt->execute([(int) $eventId]);

        return $stmt->fetchAll();
    }

    public function isClientUser($userId)
    {
        $stmt = $this->db->prepare("
            SELECT u.id
            FROM users u
            INNER JOIN roles r ON r.id = u.role_id
            WHERE u.id = ? AND LOWER(r.name) = 'client' AND u.status = 'active'
            LIMIT 1
        ");
        $stmt->execute([(int) $userId]);

        return (bool) $stmt->fetch();
    }

    public function saveAccess($eventId, $userId, $accessLevel, $status)
    {
        $stmt = $this->db->prepare("
            INSERT INTO event_client_access (event_id, user_id, access_level, status)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE access_level = VALUES(access_level), status = VALUES(status)
        ");

        return $stmt->execute([(int) $eventId, (int) $userId, $accessLevel, $status]);
    }

    public function milestones($eventId)
    {
        $stmt = $this->db->prepare("SELECT * FROM event_client_milestones WHERE event_id = ? ORDER BY sort_order, due_date, id");
        $stmt->execute([(int) $eventId]);

        return $stmt->fetchAll();
    }

    public function addMilestone($eventId, $data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO event_client_milestones
                (event_id, title, description, due_date, status, sort_order, visible_to_client)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            (int) $eventId, $data['title'], $data['description'], $data['due_date'] ?: null,
            $data['status'], (int) $data['sort_order'], (int) $data['visible_to_client']
        ]);
    }

    public function documents($eventId)
    {
        $stmt = $this->db->prepare("SELECT * FROM event_documents WHERE event_id = ? ORDER BY created_at DESC, id DESC");
        $stmt->execute([(int) $eventId]);

        return $stmt->fetchAll();
    }

    public function addDocument($eventId, $data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO event_documents
                (event_id, title, category, file_path, file_name, file_type, visible_to_client, uploaded_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            (int) $eventId, $data['title'], $data['category'], $data['file_path'],
            $data['file_name'], $data['file_type'], (int) $data['visible_to_client'],
            $_SESSION['user_id'] ?? null
        ]);
    }

    public function availableApprovals($eventId)
    {
        $stmt = $this->db->prepare("
            SELECT id, reference_no, module_name, status, requested_at
            FROM approval_requests
            WHERE event_id IS NULL OR event_id = ?
            ORDER BY requested_at DESC, id DESC
        ");
        $stmt->execute([(int) $eventId]);

        return $stmt->fetchAll();
    }

    public function linkedApprovals($eventId)
    {
        $stmt = $this->db->prepare("
            SELECT id, reference_no, module_name, status, requested_at
            FROM approval_requests
            WHERE event_id = ?
            ORDER BY requested_at DESC, id DESC
        ");
        $stmt->execute([(int) $eventId]);

        return $stmt->fetchAll();
    }

    public function linkApproval($eventId, $approvalId)
    {
        $allowed = $this->db->prepare("
            SELECT id FROM approval_requests
            WHERE id = ? AND (event_id IS NULL OR event_id = ?)
            LIMIT 1
        ");
        $allowed->execute([(int) $approvalId, (int) $eventId]);
        if (!$allowed->fetch()) {
            return false;
        }

        $stmt = $this->db->prepare("
            UPDATE approval_requests
            SET event_id = ?
            WHERE id = ?
            AND (event_id IS NULL OR event_id = ?)
        ");

        return $stmt->execute([(int) $eventId, (int) $approvalId, (int) $eventId]);
    }

    public function activateAttendanceBarcode($eventId)
    {
        $event = $this->find($eventId);

        if (!$event) {
            return false;
        }

        $token = $event['attendance_token'] ?: bin2hex(random_bytes(16));
        $stmt = $this->db->prepare("
            UPDATE master_events
            SET attendance_token = ?, attendance_checkin_enabled = 1
            WHERE id = ?
        ");
        $stmt->execute([$token, (int) $eventId]);

        return $token;
    }
}
