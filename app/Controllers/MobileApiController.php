<?php

class MobileApiController extends Controller
{
    private $user;

    public function dispatch($path)
    {
        $this->jsonHeaders();
        $path = trim((string) $path, '/');
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        try {
            if ($method === 'POST' && $path === 'api/mobile/auth/register/request') {
                return $this->requestRegistration();
            }
            if ($method === 'POST' && $path === 'api/mobile/auth/register/complete') {
                return $this->completeRegistration();
            }
            if ($method === 'POST' && $path === 'api/mobile/auth/login') {
                return $this->login();
            }
            if ($method === 'POST' && $path === 'api/mobile/auth/forgot-password') {
                return $this->forgotPassword();
            }
            if ($method === 'POST' && $path === 'api/mobile/auth/reset-password') {
                return $this->resetPassword();
            }
            if ($method === 'POST' && $path === 'api/mobile/auth/logout') {
                return $this->logout();
            }
            if ($method === 'GET' && $path === 'api/mobile/events') {
                return $this->publicEvents();
            }
            if ($method === 'GET' && preg_match('#^api/mobile/events/([^/]+)$#', $path, $matches)) {
                return $this->publicEvent($matches[1]);
            }
            if ($method === 'POST' && preg_match('#^api/mobile/events/([^/]+)/purchase$#', $path, $matches)) {
                return $this->purchaseTicket($matches[1]);
            }

            $this->requireAuth();

            if ($method === 'GET' && $path === 'api/mobile/me') {
                return $this->respond(['user' => $this->publicUser($this->user)]);
            }
            if ($method === 'GET' && $path === 'api/mobile/member/dashboard') {
                return $this->memberDashboard();
            }
            if ($method === 'GET' && $path === 'api/mobile/member/orders') {
                return $this->memberOrders();
            }
            if ($method === 'GET' && preg_match('#^api/mobile/member/orders/([^/]+)$#', $path, $matches)) {
                return $this->memberOrder($matches[1]);
            }
            if ($method === 'POST' && preg_match('#^api/mobile/member/orders/([^/]+)/payment-proof$#', $path, $matches)) {
                return $this->uploadPaymentProof($matches[1]);
            }
            if ($method === 'GET' && preg_match('#^api/mobile/member/orders/([^/]+)/contents$#', $path, $matches)) {
                return $this->memberContents($matches[1]);
            }
            if ($method === 'POST' && preg_match('#^api/mobile/member/attendance/([^/]+)$#', $path, $matches)) {
                return $this->memberAttendance($matches[1]);
            }
            if ($method === 'POST' && preg_match('#^api/mobile/staff/check-in/([^/]+)$#', $path, $matches)) {
                return $this->staffCheckIn($matches[1]);
            }
            if ($method === 'GET' && $path === 'api/mobile/client/dashboard') {
                return $this->clientDashboard();
            }
            if ($method === 'GET' && $path === 'api/mobile/client/events') {
                return $this->clientEvents();
            }
            if ($method === 'GET' && preg_match('#^api/mobile/client/events/(\d+)$#', $path, $matches)) {
                return $this->clientEvent((int) $matches[1]);
            }
            if ($method === 'GET' && preg_match('#^api/mobile/client/events/(\d+)/attendees$#', $path, $matches)) {
                return $this->clientAttendees((int) $matches[1]);
            }
            if ($method === 'GET' && preg_match('#^api/mobile/client/events/(\d+)/timeline$#', $path, $matches)) {
                return $this->clientTimeline((int) $matches[1]);
            }
            if ($method === 'GET' && preg_match('#^api/mobile/client/events/(\d+)/documents$#', $path, $matches)) {
                return $this->clientDocuments((int) $matches[1]);
            }
            if ($method === 'GET' && preg_match('#^api/mobile/client/events/(\d+)/approvals$#', $path, $matches)) {
                return $this->clientApprovals((int) $matches[1]);
            }
            if ($method === 'GET' && preg_match('#^api/mobile/client/events/(\d+)/contents$#', $path, $matches)) {
                return $this->clientContents((int) $matches[1]);
            }
            if ($method === 'GET' && $path === 'api/mobile/client/notifications') {
                return $this->clientNotifications();
            }

            return $this->error('Endpoint tidak ditemukan.', 404);
        } catch (Throwable $exception) {
            error_log('[Mobile API] ' . $exception->getMessage() . ' in ' . $exception->getFile() . ':' . $exception->getLine());
            return $this->error('Terjadi kesalahan pada API mobile.', 500, [
                'debug' => filter_var(getenv('APP_DEBUG') ?: false, FILTER_VALIDATE_BOOLEAN) ? $exception->getMessage() : null
            ]);
        }
    }

    private function requestRegistration()
    {
        $input = $this->input();
        $email = strtolower(trim($input['email'] ?? ''));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->error('Alamat email valid wajib diisi.', 422);
        }

        $token = bin2hex(random_bytes(32));
        $user = (new User())->requestMemberVerification($email, $token);

        if (!$user) {
            return $this->error('Email sudah terdaftar. Silakan masuk menggunakan akun Anda.', 422);
        }

        Mailer::sendActivationEmail($email, $email, frontUrl('member-verify', ['slug' => $token]));

        return $this->respond([
            'message' => 'Link verifikasi sudah dikirim ke email Anda.',
            'verification_token_hint' => filter_var(getenv('APP_DEBUG') ?: false, FILTER_VALIDATE_BOOLEAN) ? $token : null
        ]);
    }

    private function completeRegistration()
    {
        $input = $this->input();
        $token = trim((string) ($input['token'] ?? ''));
        $userModel = new User();
        $user = $userModel->findMemberByActivationToken($token);

        if (!$user) {
            return $this->error('Token verifikasi tidak valid atau sudah kedaluwarsa.', 422);
        }

        $name = trim($input['name'] ?? '');
        $birthDate = trim($input['birth_date'] ?? '');
        $gender = $input['gender'] ?? '';
        $password = (string) ($input['password'] ?? '');

        if ($name === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $birthDate) || !in_array($gender, ['male', 'female'], true)) {
            return $this->error('Nama, tanggal lahir, dan jenis kelamin wajib diisi dengan benar.', 422);
        }
        if (strlen($password) < 8 || $password !== (string) ($input['password_confirmation'] ?? '')) {
            return $this->error('Password minimal 8 karakter dan konfirmasi harus sama.', 422);
        }

        $userModel->activateMember((int) $user['id'], [
            'name' => $name,
            'birth_date' => $birthDate,
            'gender' => $gender,
            'password' => $password
        ]);

        return $this->respond(['message' => 'Akun berhasil diaktifkan. Silakan masuk.']);
    }

    private function login()
    {
        $input = $this->input();
        $email = strtolower(trim($input['email'] ?? ''));
        $password = (string) ($input['password'] ?? '');
        $userModel = new User();
        $user = $userModel->findByEmail($email);
        $role = strtolower((string) ($user['role_name'] ?? ''));
        $permissions = $user && !empty($user['role_id']) ? $userModel->getPermissionsByRoleId($user['role_id']) : [];
        $allowed = $role === 'member' || ($role === 'client' && in_array('client_portal.view', $permissions, true));

        if (!$user || !$allowed || ($user['status'] ?? '') !== 'active' || !password_verify($password, $user['password'])) {
            return $this->error('Email atau password tidak sesuai.', 401);
        }

        $token = (new MobileApiToken())->issue((int) $user['id'], $input['device_name'] ?? null);

        return $this->respond([
            'token' => $token,
            'user' => $this->publicUser($user, $permissions),
            'home' => $role === 'client' ? 'client' : 'member'
        ]);
    }

    private function forgotPassword()
    {
        $input = $this->input();
        $email = strtolower(trim($input['email'] ?? ''));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->error('Alamat email valid wajib diisi.', 422);
        }

        $model = new User();
        $user = $model->findByEmail($email);
        $role = strtolower((string) ($user['role_name'] ?? ''));
        if ($user && in_array($role, ['member', 'client'], true) && ($user['status'] ?? '') === 'active') {
            $token = bin2hex(random_bytes(32));
            $model->saveResetToken((int) $user['id'], $token);
            Mailer::sendResetPasswordEmail($user['email'], $user['name'], frontUrl('member-reset', ['slug' => $token]));
        }

        return $this->respond(['message' => 'Jika email terdaftar, link reset password akan dikirimkan.']);
    }

    private function resetPassword()
    {
        $input = $this->input();
        $token = trim((string) ($input['token'] ?? ''));
        $password = (string) ($input['password'] ?? '');
        $userModel = new User();
        $user = $userModel->findActivePortalByResetToken($token);

        if (!$user) {
            return $this->error('Token reset password tidak valid atau sudah kedaluwarsa.', 422);
        }
        if (strlen($password) < 8 || $password !== (string) ($input['password_confirmation'] ?? '')) {
            return $this->error('Password minimal 8 karakter dan konfirmasi harus sama.', 422);
        }

        $userModel->updatePasswordByResetToken((int) $user['id'], $password);

        return $this->respond(['message' => 'Password berhasil diperbarui. Silakan masuk.']);
    }

    private function logout()
    {
        $token = $this->bearerToken();
        if ($token) {
            (new MobileApiToken())->revoke($token);
        }

        return $this->respond(['message' => 'Berhasil keluar.']);
    }

    private function publicEvents()
    {
        return $this->respond(['events' => $this->eventList((new EventTicket())->publicEvents())]);
    }

    private function publicEvent($slug)
    {
        $event = (new EventTicket())->findPublishedBySlug(rawurldecode($slug));
        if (!$event) {
            return $this->error('Event tidak ditemukan.', 404);
        }

        return $this->respond(['event' => $this->eventPayload($event)]);
    }

    private function purchaseTicket($slug)
    {
        $this->requireRole(['member']);
        $event = (new EventTicket())->findPublishedBySlug(rawurldecode($slug));
        if (!$event) {
            return $this->error('Event tidak ditemukan.', 404);
        }

        $input = $this->input();
        $phone = trim($input['buyer_phone'] ?? '');
        if ($phone === '') {
            return $this->error('Nomor WhatsApp wajib diisi.', 422);
        }

        $order = (new EventTicket())->createOrder($event, [
            'buyer_name' => $this->user['name'],
            'buyer_email' => $this->user['email'],
            'buyer_phone' => $phone,
            'quantity' => $input['quantity'] ?? 1
        ], (int) $this->user['id']);

        if (!$order) {
            return $this->error('Kuota tidak tersedia atau penjualan tiket sudah ditutup.', 422);
        }

        return $this->respond(['message' => 'Pesanan tiket berhasil dibuat.', 'order' => $order], 201);
    }

    private function memberDashboard()
    {
        $this->requireRole(['member']);
        $orders = (new EventTicket())->ordersForMember((int) $this->user['id']);

        return $this->respond([
            'orders' => $orders,
            'summary' => [
                'orders' => count($orders),
                'paid' => count(array_filter($orders, fn($order) => ($order['payment_status'] ?? '') === 'paid')),
                'checked_in' => array_sum(array_map(fn($order) => (int) ($order['checked_in_count'] ?? 0), $orders))
            ]
        ]);
    }

    private function memberOrders()
    {
        $this->requireRole(['member']);
        return $this->respond(['orders' => (new EventTicket())->ordersForMember((int) $this->user['id'])]);
    }

    private function memberOrder($number)
    {
        $this->requireRole(['member']);
        $order = (new EventTicket())->findMemberOrderByNumber(rawurldecode($number), (int) $this->user['id']);
        if (!$order) {
            return $this->error('Pesanan tidak ditemukan.', 404);
        }

        return $this->respond(['order' => $order]);
    }

    private function uploadPaymentProof($number)
    {
        $this->requireRole(['member']);
        $ticket = new EventTicket();
        $order = $ticket->findMemberOrderByNumber(rawurldecode($number), (int) $this->user['id']);
        if (!$order) {
            return $this->error('Pesanan tidak ditemukan.', 404);
        }

        $extension = validatedImageExtension($_FILES['payment_proof'] ?? []);
        if (!$extension) {
            return $this->error('Bukti bayar wajib berupa JPG, PNG, atau WebP.', 422);
        }

        $dir = __DIR__ . '/../../storage/payment-proofs';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $fileName = 'proof-' . (int) $order['id'] . '-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . $extension;
        $relativePath = 'payment-proofs/' . $fileName;
        if (!move_uploaded_file($_FILES['payment_proof']['tmp_name'], $dir . '/' . $fileName)) {
            return $this->error('Bukti bayar gagal diunggah.', 500);
        }

        $ticket->submitPaymentProof((int) $order['id'], (int) $this->user['id'], $relativePath);

        return $this->respond(['message' => 'Bukti bayar berhasil dikirim untuk verifikasi.']);
    }

    private function memberContents($number)
    {
        $this->requireRole(['member']);
        $order = (new EventTicket())->findMemberOrderByNumber(rawurldecode($number), (int) $this->user['id']);
        if (!$order || ($order['payment_status'] ?? '') !== 'paid') {
            return $this->error('Konten event tersedia setelah pembayaran tiket terverifikasi.', 403);
        }

        return $this->respond(['contents' => $this->contentList((new EventPortalContent())->visibleForEvent((int) $order['event_id']))]);
    }

    private function memberAttendance($eventToken)
    {
        $this->requireRole(['member']);
        $input = $this->input();
        $attendeeId = (int) ($input['attendee_id'] ?? 0);
        $event = (new EventTicket())->eventForAttendanceToken($eventToken);
        if (!$event || !$attendeeId) {
            return $this->error('QR Code kehadiran tidak valid.', 404);
        }

        $success = (new EventTicket())->memberBarcodeCheckIn((int) $event['id'], $eventToken, $attendeeId, (int) $this->user['id']);

        return $success
            ? $this->respond(['message' => 'Kehadiran berhasil dikonfirmasi.'])
            : $this->error('Tiket tidak valid, belum dibayar, atau sudah check-in.', 422);
    }

    private function staffCheckIn($ticketQrToken)
    {
        $this->requireRole(['client']);
        $ticket = new EventTicket();
        $attendee = $ticket->attendeeForStaffQrToken($ticketQrToken);
        if (!$attendee) {
            return $this->error('QR tiket tidak valid.', 404);
        }
        $event = (new ClientEvent())->findAccessible((int) $attendee['event_id'], (int) $this->user['id']);
        if (!$event) {
            return $this->error('Anda tidak memiliki akses ke event ini.', 403);
        }

        $success = $ticket->staffQrCheckIn($ticketQrToken, (int) $attendee['event_id'], (int) $this->user['id']);

        return $success
            ? $this->respond(['message' => 'Peserta berhasil di-check-in.', 'attendee' => $attendee])
            : $this->error('Tiket belum dibayar, bukan jadwal event, atau sudah check-in.', 422);
    }

    private function clientDashboard()
    {
        $this->requireRole(['client']);
        $userId = (int) $this->user['id'];

        return $this->respond([
            'events' => (new ClientEvent())->accessibleForUser($userId, 5),
            'summary' => [
                'events' => (new ClientEvent())->countForUser($userId),
                'pending_approvals' => (new ClientApproval())->countPendingForUser($userId),
                'unread_notifications' => (new ClientNotification())->countUnread($userId)
            ],
            'upcoming_milestones' => (new ClientMilestone())->upcomingForUser($userId)
        ]);
    }

    private function clientEvents()
    {
        $this->requireRole(['client']);
        return $this->respond(['events' => (new ClientEvent())->accessibleForUser((int) $this->user['id'], 100)]);
    }

    private function clientEvent($eventId)
    {
        $this->requireRole(['client']);
        $event = (new ClientEvent())->findAccessible($eventId, (int) $this->user['id']);
        if (!$event) {
            return $this->error('Event tidak ditemukan atau tidak ditugaskan untuk akun Anda.', 404);
        }

        return $this->respond(['event' => $event]);
    }

    private function clientAttendees($eventId)
    {
        $this->requireRole(['client']);
        if (!(new ClientEvent())->findAccessible($eventId, (int) $this->user['id'])) {
            return $this->error('Event tidak ditemukan atau tidak ditugaskan untuk akun Anda.', 404);
        }

        return $this->respond(['attendees' => (new EventTicket())->attendeesForEvent($eventId)]);
    }

    private function clientTimeline($eventId)
    {
        $this->requireRole(['client']);
        return $this->respond(['timeline' => (new ClientMilestone())->visibleForEvent($eventId, (int) $this->user['id'])]);
    }

    private function clientDocuments($eventId)
    {
        $this->requireRole(['client']);
        return $this->respond(['documents' => (new ClientDocument())->visibleForEvent($eventId, (int) $this->user['id'])]);
    }

    private function clientApprovals($eventId)
    {
        $this->requireRole(['client']);
        return $this->respond(['approvals' => (new ClientApproval())->forEvent($eventId, (int) $this->user['id'])]);
    }

    private function clientContents($eventId)
    {
        $this->requireRole(['client']);
        if (!(new ClientEvent())->findAccessible($eventId, (int) $this->user['id'])) {
            return $this->error('Event tidak ditemukan atau tidak ditugaskan untuk akun Anda.', 404);
        }

        return $this->respond(['contents' => $this->contentList((new EventPortalContent())->visibleForEvent($eventId))]);
    }

    private function clientNotifications()
    {
        $this->requireRole(['client']);
        return $this->respond(['notifications' => (new ClientNotification())->forUser((int) $this->user['id'])]);
    }

    private function requireAuth()
    {
        $token = $this->bearerToken();
        $user = $token ? (new MobileApiToken())->userForToken($token) : null;

        if (!$user) {
            $this->error('Token tidak valid atau sesi mobile sudah berakhir.', 401);
            exit;
        }

        $this->user = $user;
    }

    private function requireRole(array $roles)
    {
        if (!$this->user) {
            $this->requireAuth();
        }

        $role = strtolower((string) ($this->user['role_name'] ?? ''));
        $permissions = !empty($this->user['role_id']) ? (new User())->getPermissionsByRoleId($this->user['role_id']) : [];
        $isAllowedClient = $role === 'client' && in_array('client_portal.view', $permissions, true);

        if (!in_array($role, $roles, true) || ($role === 'client' && !$isAllowedClient)) {
            $this->error('Akun Anda tidak memiliki akses ke fitur ini.', 403);
            exit;
        }
    }

    private function input()
    {
        $raw = file_get_contents('php://input');
        $json = json_decode($raw ?: '', true);
        return is_array($json) ? $json : $_POST;
    }

    private function bearerToken()
    {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
        if (preg_match('/Bearer\s+(.+)/i', $header, $matches)) {
            return trim($matches[1]);
        }

        return null;
    }

    private function publicUser($user, $permissions = null)
    {
        $permissions = $permissions ?? (!empty($user['role_id']) ? (new User())->getPermissionsByRoleId($user['role_id']) : []);
        return [
            'id' => (int) $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => strtolower((string) ($user['role_name'] ?? '')),
            'permissions' => $permissions
        ];
    }

    private function eventList($events)
    {
        return array_map(fn($event) => $this->eventPayload($event), $events);
    }

    private function eventPayload($event)
    {
        $slug = $event['public_slug'] ?: ($event['public_slug_en'] ?? '');
        return [
            'id' => (int) $event['id'],
            'slug' => $slug,
            'title' => $event['title'],
            'title_en' => $event['title_en'] ?? null,
            'description' => $event['description'] ?? null,
            'event_date' => $event['event_date'] ?? null,
            'end_date' => $event['end_date'] ?? null,
            'venue' => $event['venue'] ?? null,
            'cover_image_url' => !empty($event['cover_image']) ? uploadAsset($event['cover_image']) : null,
            'ticket_price' => (float) ($event['ticket_price'] ?? 0),
            'participant_quota' => (int) ($event['participant_quota'] ?? 0),
            'sold_tickets' => (int) ($event['sold_tickets'] ?? 0),
            'reserved_tickets' => (int) ($event['reserved_tickets'] ?? 0),
            'attended_count' => (int) ($event['attended_count'] ?? 0),
            'ticket_sales_status' => $event['ticket_sales_status'] ?? 'closed'
        ];
    }

    private function contentList($contents)
    {
        return array_map(function ($item) {
            $item['file_url'] = !empty($item['file_path']) ? uploadAsset($item['file_path']) : null;
            return $item;
        }, $contents);
    }

    private function respond($payload, $status = 200)
    {
        http_response_code($status);
        echo json_encode(['success' => true] + $payload, JSON_UNESCAPED_SLASHES);
        return null;
    }

    private function error($message, $status = 400, $extra = [])
    {
        http_response_code($status);
        echo json_encode(['success' => false, 'message' => $message] + array_filter($extra, fn($value) => $value !== null), JSON_UNESCAPED_SLASHES);
        return null;
    }

    private function jsonHeaders()
    {
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'OPTIONS') {
            http_response_code(204);
            exit;
        }
    }
}
