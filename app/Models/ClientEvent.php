<?php

class ClientEvent
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function accessibleForUser($userId, $limit = 20)
    {
        $stmt = $this->db->prepare("
            SELECT
                e.id, e.event_code, e.title, e.client_name, e.status,
                e.event_date, e.end_date, e.venue, e.progress, e.is_paid, e.participant_quota,
                COALESCE((SELECT SUM(o.quantity) FROM event_ticket_orders o WHERE o.event_id = e.id AND o.payment_status = 'paid'), 0) AS sold_tickets,
                COALESCE((SELECT COUNT(*) FROM event_ticket_attendees ta INNER JOIN event_ticket_orders ot ON ot.id = ta.ticket_order_id WHERE ta.event_id = e.id AND ta.check_in_status = 'arrived' AND ot.payment_status = 'paid'), 0) AS attended_count,
                a.access_level
            FROM master_events e
            INNER JOIN event_client_access a ON a.event_id = e.id
            WHERE a.user_id = ?
            AND a.status = 'active'
            ORDER BY COALESCE(e.event_date, '9999-12-31') ASC, e.id DESC
            LIMIT ?
        ");
        $stmt->bindValue(1, (int) $userId, PDO::PARAM_INT);
        $stmt->bindValue(2, (int) $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findAccessible($eventId, $userId)
    {
        $stmt = $this->db->prepare("
            SELECT
                e.id, e.event_code, e.title, e.client_name, e.status,
                e.event_date, e.end_date, e.venue, e.description, e.progress,
                e.is_public, e.is_paid, e.ticket_price, e.participant_quota, e.ticket_sales_status,
                e.attendance_token, e.attendance_checkin_enabled,
                COALESCE((SELECT SUM(o.quantity) FROM event_ticket_orders o WHERE o.event_id = e.id AND o.payment_status = 'paid'), 0) AS sold_tickets,
                COALESCE((SELECT COUNT(*) FROM event_ticket_attendees ta INNER JOIN event_ticket_orders ot ON ot.id = ta.ticket_order_id WHERE ta.event_id = e.id AND ta.check_in_status = 'arrived' AND ot.payment_status = 'paid'), 0) AS attended_count,
                a.access_level
            FROM master_events e
            INNER JOIN event_client_access a ON a.event_id = e.id
            WHERE e.id = ?
            AND a.user_id = ?
            AND a.status = 'active'
            LIMIT 1
        ");
        $stmt->execute([(int) $eventId, (int) $userId]);

        return $stmt->fetch();
    }

    public function countForUser($userId)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) AS total
            FROM master_events e
            INNER JOIN event_client_access a ON a.event_id = e.id
            WHERE a.user_id = ?
            AND a.status = 'active'
        ");
        $stmt->execute([(int) $userId]);

        return (int) ($stmt->fetch()['total'] ?? 0);
    }
}
