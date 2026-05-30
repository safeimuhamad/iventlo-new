<?php

class EventTicket
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function publicEvents()
    {
        return $this->db->query("
            SELECT e.*, " . $this->statsSql('e') . "
            FROM master_events e
            WHERE e.is_public = 1
            AND e.is_paid = 1
            AND e.ticket_sales_status IN ('open', 'sold_out')
            AND e.status <> 'cancelled'
            ORDER BY COALESCE(e.event_date, '9999-12-31') ASC, e.id DESC
        ")->fetchAll();
    }

    public function upcomingPublic($limit = 3)
    {
        $stmt = $this->db->prepare("
            SELECT e.*, " . $this->statsSql('e') . "
            FROM master_events e
            WHERE e.is_public = 1
            AND e.is_paid = 1
            AND e.ticket_sales_status IN ('open', 'sold_out')
            AND e.status <> 'cancelled'
            ORDER BY COALESCE(e.event_date, '9999-12-31') ASC, e.id DESC
            LIMIT ?
        ");
        $stmt->bindValue(1, (int) $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findPublishedBySlug($slug)
    {
        $stmt = $this->db->prepare("
            SELECT e.*, " . $this->statsSql('e') . "
            FROM master_events e
            WHERE (e.public_slug = ? OR e.public_slug_en = ?)
            AND e.is_public = 1
            AND e.is_paid = 1
            AND e.ticket_sales_status IN ('open', 'sold_out')
            AND e.status <> 'cancelled'
            LIMIT 1
        ");
        $stmt->execute([$slug, $slug]);

        return $stmt->fetch();
    }

    public function stats($eventId)
    {
        $stmt = $this->db->prepare("
            SELECT
                (SELECT COUNT(*) FROM event_ticket_orders o WHERE o.event_id = ? AND o.payment_status = 'paid') AS paid_orders,
                COALESCE((SELECT SUM(o.quantity) FROM event_ticket_orders o WHERE o.event_id = ? AND o.payment_status = 'paid'), 0) AS sold_tickets,
                COALESCE((SELECT SUM(o.quantity) FROM event_ticket_orders o WHERE o.event_id = ? AND o.payment_status IN ('pending', 'verification', 'paid')), 0) AS reserved_tickets,
                (SELECT COUNT(*) FROM event_ticket_attendees a INNER JOIN event_ticket_orders o ON o.id = a.ticket_order_id
                    WHERE a.event_id = ? AND a.check_in_status = 'arrived' AND o.payment_status = 'paid') AS attended_count
        ");
        $stmt->execute([(int) $eventId, (int) $eventId, (int) $eventId, (int) $eventId]);

        return $stmt->fetch() ?: [
            'paid_orders' => 0,
            'sold_tickets' => 0,
            'reserved_tickets' => 0,
            'attended_count' => 0
        ];
    }

    public function ordersForEvent($eventId)
    {
        $stmt = $this->db->prepare("
            SELECT o.*,
                COALESCE(SUM(CASE WHEN a.check_in_status = 'arrived' THEN 1 ELSE 0 END), 0) AS checked_in_count
            FROM event_ticket_orders o
            LEFT JOIN event_ticket_attendees a ON a.ticket_order_id = o.id
            WHERE o.event_id = ?
            GROUP BY o.id
            ORDER BY o.ordered_at DESC, o.id DESC
        ");
        $stmt->execute([(int) $eventId]);

        return $stmt->fetchAll();
    }

    public function attendeesForEvent($eventId)
    {
        $stmt = $this->db->prepare("
            SELECT a.*, o.order_number, o.payment_status, o.buyer_phone
            FROM event_ticket_attendees a
            INNER JOIN event_ticket_orders o ON o.id = a.ticket_order_id
            WHERE a.event_id = ?
            ORDER BY o.ordered_at DESC, a.id ASC
        ");
        $stmt->execute([(int) $eventId]);

        return $stmt->fetchAll();
    }

    public function createOrder($event, $data, $memberUserId)
    {
        $quantity = max(1, min(10, (int) ($data['quantity'] ?? 1)));
        $orderNumber = 'TKT-' . date('ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));

        $this->db->beginTransaction();

        try {
            $lockedEvent = $this->db->prepare("
                SELECT ticket_price, participant_quota, ticket_sales_status
                FROM master_events
                WHERE id = ?
                FOR UPDATE
            ");
            $lockedEvent->execute([(int) $event['id']]);
            $sales = $lockedEvent->fetch();

            $reserved = $this->db->prepare("
                SELECT COALESCE(SUM(quantity), 0) AS total
                FROM event_ticket_orders
                WHERE event_id = ?
                AND payment_status IN ('pending', 'verification', 'paid')
            ");
            $reserved->execute([(int) $event['id']]);
            $reservedTickets = (int) ($reserved->fetch()['total'] ?? 0);
            $available = max(0, (int) ($sales['participant_quota'] ?? 0) - $reservedTickets);

            if (!$sales || $sales['ticket_sales_status'] !== 'open' || $quantity > $available) {
                $this->db->rollBack();
                return null;
            }

            $unitPrice = (float) $sales['ticket_price'];
            $totalAmount = $unitPrice * $quantity;

            $insert = $this->db->prepare("
                INSERT INTO event_ticket_orders
                    (event_id, member_user_id, order_number, buyer_name, buyer_email, buyer_phone, quantity, unit_price, total_amount)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $insert->execute([
                (int) $event['id'], (int) $memberUserId, $orderNumber, $data['buyer_name'], $data['buyer_email'],
                $data['buyer_phone'], $quantity, $unitPrice, $totalAmount
            ]);
            $orderId = (int) $this->db->lastInsertId();

            $attendee = $this->db->prepare("
                INSERT INTO event_ticket_attendees
                    (event_id, ticket_order_id, ticket_code, ticket_qr_token, attendee_name, attendee_email)
                VALUES (?, ?, ?, ?, ?, ?)
            ");

            for ($index = 1; $index <= $quantity; $index++) {
                $name = $quantity === 1 ? $data['buyer_name'] : $data['buyer_name'] . ' - Peserta ' . $index;
                $ticketCode = 'IVT-' . strtoupper(bin2hex(random_bytes(5)));
                $ticketToken = bin2hex(random_bytes(16));
                $attendee->execute([(int) $event['id'], $orderId, $ticketCode, $ticketToken, $name, $data['buyer_email']]);
            }

            $this->db->commit();

            return $this->findOrder($orderId);
        } catch (Throwable $exception) {
            $this->db->rollBack();
            throw $exception;
        }
    }

    public function findOrder($orderId)
    {
        $stmt = $this->db->prepare("
            SELECT o.*, e.title AS event_title, e.event_date, e.venue
            FROM event_ticket_orders o
            INNER JOIN master_events e ON e.id = o.event_id
            WHERE o.id = ?
            LIMIT 1
        ");
        $stmt->execute([(int) $orderId]);

        return $stmt->fetch();
    }

    public function ordersForMember($memberUserId)
    {
        $stmt = $this->db->prepare("
            SELECT o.*, e.title AS event_title, e.title_en AS event_title_en, e.event_date, e.end_date, e.venue,
                COUNT(a.id) AS ticket_count,
                SUM(CASE WHEN a.check_in_status = 'arrived' THEN 1 ELSE 0 END) AS checked_in_count
            FROM event_ticket_orders o
            INNER JOIN master_events e ON e.id = o.event_id
            LEFT JOIN event_ticket_attendees a ON a.ticket_order_id = o.id
            WHERE o.member_user_id = ?
            GROUP BY o.id
            ORDER BY o.ordered_at DESC, o.id DESC
        ");
        $stmt->execute([(int) $memberUserId]);

        return $stmt->fetchAll();
    }

    public function findMemberOrderByNumber($number, $memberUserId)
    {
        $stmt = $this->db->prepare("
            SELECT o.*, e.title AS event_title, e.title_en AS event_title_en, e.event_date, e.end_date,
                e.venue, e.venue_en, e.status AS event_status
            FROM event_ticket_orders o
            INNER JOIN master_events e ON e.id = o.event_id
            WHERE o.order_number = ? AND o.member_user_id = ?
            LIMIT 1
        ");
        $stmt->execute([$number, (int) $memberUserId]);
        $order = $stmt->fetch();

        if (!$order) {
            return null;
        }

        $tickets = $this->db->prepare("
            SELECT id, ticket_code, ticket_qr_token, attendee_name, check_in_status, checked_in_at
            FROM event_ticket_attendees
            WHERE ticket_order_id = ?
            ORDER BY id ASC
        ");
        $tickets->execute([(int) $order['id']]);
        $order['tickets'] = $tickets->fetchAll();

        return $order;
    }

    public function findPaidMemberOrderForEvent($eventId, $memberUserId)
    {
        $stmt = $this->db->prepare("
            SELECT o.*, e.title AS event_title, e.title_en AS event_title_en, e.event_date, e.end_date,
                e.venue, e.venue_en, e.status AS event_status
            FROM event_ticket_orders o
            INNER JOIN master_events e ON e.id = o.event_id
            WHERE o.event_id = ? AND o.member_user_id = ? AND o.payment_status = 'paid'
            ORDER BY o.id DESC
            LIMIT 1
        ");
        $stmt->execute([(int) $eventId, (int) $memberUserId]);
        $order = $stmt->fetch();

        if (!$order) {
            return null;
        }

        $tickets = $this->db->prepare("
            SELECT id, ticket_code, ticket_qr_token, attendee_name, check_in_status, checked_in_at
            FROM event_ticket_attendees
            WHERE ticket_order_id = ?
            ORDER BY id ASC
        ");
        $tickets->execute([(int) $order['id']]);
        $order['tickets'] = $tickets->fetchAll();

        return $order;
    }

    public function eventForAttendanceToken($token)
    {
        $stmt = $this->db->prepare("
            SELECT id, title, title_en, event_date, end_date, venue, venue_en, status, attendance_token
            FROM master_events
            WHERE attendance_token = ? AND attendance_checkin_enabled = 1
            LIMIT 1
        ");
        $stmt->execute([$token]);

        return $stmt->fetch();
    }

    public function memberBarcodeCheckIn($eventId, $token, $attendeeId, $memberUserId)
    {
        $stmt = $this->db->prepare("
            UPDATE event_ticket_attendees a
            INNER JOIN event_ticket_orders o ON o.id = a.ticket_order_id
            INNER JOIN master_events e ON e.id = a.event_id
            SET a.check_in_status = 'arrived', a.checked_in_at = NOW(), a.checked_in_by = ?
            WHERE a.id = ?
            AND a.event_id = ?
            AND o.member_user_id = ?
            AND o.payment_status = 'paid'
            AND a.check_in_status = 'not_arrived'
            AND e.attendance_token = ?
            AND e.attendance_checkin_enabled = 1
            AND (e.status = 'on_going' OR (CURDATE() BETWEEN e.event_date AND COALESCE(e.end_date, e.event_date)))
        ");
        $stmt->execute([(int) $memberUserId, (int) $attendeeId, (int) $eventId, (int) $memberUserId, $token]);

        return $stmt->rowCount() > 0;
    }

    public function attendeeForStaffQrToken($token)
    {
        $stmt = $this->db->prepare("
            SELECT a.*, o.order_number, o.payment_status, o.member_user_id,
                e.title AS event_title, e.event_date, e.end_date, e.venue, e.status AS event_status
            FROM event_ticket_attendees a
            INNER JOIN event_ticket_orders o ON o.id = a.ticket_order_id
            INNER JOIN master_events e ON e.id = a.event_id
            WHERE a.ticket_qr_token = ?
            LIMIT 1
        ");
        $stmt->execute([$token]);

        return $stmt->fetch();
    }

    public function staffQrCheckIn($token, $eventId, $userId)
    {
        $stmt = $this->db->prepare("
            UPDATE event_ticket_attendees a
            INNER JOIN event_ticket_orders o ON o.id = a.ticket_order_id
            INNER JOIN master_events e ON e.id = a.event_id
            SET a.check_in_status = 'arrived', a.checked_in_at = NOW(), a.checked_in_by = ?
            WHERE a.ticket_qr_token = ?
            AND a.event_id = ?
            AND o.payment_status = 'paid'
            AND a.check_in_status = 'not_arrived'
            AND (e.status = 'on_going' OR (CURDATE() BETWEEN e.event_date AND COALESCE(e.end_date, e.event_date)))
        ");
        $stmt->execute([(int) $userId, $token, (int) $eventId]);

        return $stmt->rowCount() > 0;
    }

    public function submitPaymentProof($orderId, $memberUserId, $filePath)
    {
        $stmt = $this->db->prepare("
            UPDATE event_ticket_orders
            SET payment_proof = ?, proof_uploaded_at = NOW(), payment_submitted_at = NOW(),
                payment_status = 'verification'
            WHERE id = ? AND member_user_id = ? AND payment_status IN ('pending', 'verification')
        ");
        $stmt->execute([$filePath, (int) $orderId, (int) $memberUserId]);

        return $stmt->rowCount() > 0;
    }

    public function memberCheckIn($orderNumber, $attendeeId, $memberUserId)
    {
        $stmt = $this->db->prepare("
            UPDATE event_ticket_attendees a
            INNER JOIN event_ticket_orders o ON o.id = a.ticket_order_id
            INNER JOIN master_events e ON e.id = a.event_id
            SET a.check_in_status = 'arrived', a.checked_in_at = NOW(), a.checked_in_by = ?
            WHERE a.id = ?
            AND o.order_number = ?
            AND o.member_user_id = ?
            AND o.payment_status = 'paid'
            AND a.check_in_status = 'not_arrived'
            AND (e.status = 'on_going' OR (CURDATE() BETWEEN e.event_date AND COALESCE(e.end_date, e.event_date)))
        ");
        $stmt->execute([(int) $memberUserId, (int) $attendeeId, $orderNumber, (int) $memberUserId]);

        return $stmt->rowCount() > 0;
    }

    public function updatePaymentStatus($eventId, $orderId, $status, $note, $userId)
    {
        $status = in_array($status, ['paid', 'cancelled'], true) ? $status : 'pending';
        $stmt = $this->db->prepare("
            UPDATE event_ticket_orders
            SET payment_status = ?, payment_note = ?, paid_at = CASE WHEN ? = 'paid' THEN NOW() ELSE NULL END,
                verified_by = ?
            WHERE id = ? AND event_id = ?
        ");

        return $stmt->execute([$status, $note, $status, (int) $userId, (int) $orderId, (int) $eventId]);
    }

    public function checkIn($eventId, $attendeeId, $userId)
    {
        $stmt = $this->db->prepare("
            UPDATE event_ticket_attendees a
            INNER JOIN event_ticket_orders o ON o.id = a.ticket_order_id
            SET a.check_in_status = 'arrived', a.checked_in_at = NOW(), a.checked_in_by = ?
            WHERE a.id = ? AND a.event_id = ? AND o.payment_status = 'paid'
        ");
        $stmt->execute([(int) $userId, (int) $attendeeId, (int) $eventId]);

        return $stmt->rowCount() > 0;
    }

    private function statsSql($eventAlias)
    {
        return "
            COALESCE((SELECT SUM(o.quantity) FROM event_ticket_orders o
                WHERE o.event_id = {$eventAlias}.id AND o.payment_status = 'paid'), 0) AS sold_tickets,
            COALESCE((SELECT SUM(o.quantity) FROM event_ticket_orders o
                WHERE o.event_id = {$eventAlias}.id AND o.payment_status IN ('pending', 'verification', 'paid')), 0) AS reserved_tickets,
            COALESCE((SELECT COUNT(*) FROM event_ticket_attendees a
                INNER JOIN event_ticket_orders oi ON oi.id = a.ticket_order_id
                WHERE a.event_id = {$eventAlias}.id AND a.check_in_status = 'arrived' AND oi.payment_status = 'paid'), 0) AS attended_count
        ";
    }
}
