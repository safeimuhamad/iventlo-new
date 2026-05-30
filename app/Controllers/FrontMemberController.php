<?php

class FrontMemberController extends Controller
{
    public function register()
    {
        if (isPublicMember()) {
            header('Location: ' . frontUrl('events'));
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = strtolower(trim($_POST['email'] ?? ''));

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['member_error'] = t('Alamat email valid wajib diisi.', 'A valid email address is required.');
            } elseif (!consumeRateLimit('member_registration', 4, 900)) {
                $_SESSION['member_error'] = t('Terlalu banyak permintaan verifikasi. Silakan coba kembali nanti.', 'Too many verification requests. Please try again later.');
            } else {
                $token = bin2hex(random_bytes(32));
                $user = (new User())->requestMemberVerification($email, $token);

                if (!$user) {
                    $_SESSION['member_error'] = t('Email sudah terdaftar. Silakan masuk menggunakan akun Anda.', 'Email is already registered. Please sign in with your account.');
                } else {
                    $link = frontUrl('member-verify', ['slug' => $token]);
                    $sent = Mailer::sendActivationEmail($email, $email, $link);

                    if ($sent) {
                        clearRateLimit('member_registration');
                        $_SESSION['member_success'] = t(
                            'Link verifikasi sudah dikirim ke email Anda. Silakan buka email untuk melanjutkan pendaftaran.',
                            'A verification link has been sent to your email. Please open it to continue registration.'
                        );
                        header('Location: ' . frontUrl('member-register'));
                        exit;
                    }

                    $_SESSION['member_error'] = t(
                        'Email verifikasi belum berhasil dikirim. Silakan coba kembali atau hubungi tim Iventlo.',
                        'The verification email could not be sent. Please try again or contact the Iventlo team.'
                    );
                }
            }
        }

        $this->frontView('frontend/member/register', [
            'title' => t('Daftar member Iventlo', 'Register as Iventlo member'),
            'memberError' => $this->pullMessage('member_error'),
            'memberSuccess' => $this->pullMessage('member_success')
        ]);
    }

    public function verify()
    {
        if (isPublicMember()) {
            header('Location: ' . frontUrl('events'));
            exit;
        }

        $token = trim((string) ($_GET['slug'] ?? ''));
        $model = new User();
        $user = $model->findMemberByActivationToken($token);

        if (!$user) {
            $_SESSION['member_error'] = t('Link verifikasi tidak valid atau sudah kedaluwarsa. Silakan daftar kembali.', 'Verification link is invalid or expired. Please register again.');
            header('Location: ' . frontUrl('member-register'));
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $birthDate = trim($_POST['birth_date'] ?? '');
            $gender = $_POST['gender'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmation = $_POST['password_confirmation'] ?? '';
            $validDate = $this->validBirthDate($birthDate);

            if ($name === '' || !$validDate || !in_array($gender, ['male', 'female'], true)) {
                $_SESSION['member_error'] = t('Nama, tanggal lahir, dan jenis kelamin wajib diisi dengan benar.', 'Name, date of birth, and gender must be completed correctly.');
            } elseif (strlen($password) < 8 || $password !== $confirmation) {
                $_SESSION['member_error'] = t('Password minimal 8 karakter dan konfirmasi harus sama.', 'Password must be at least 8 characters and confirmation must match.');
            } else {
                $model->activateMember((int) $user['id'], [
                    'name' => $name,
                    'birth_date' => $birthDate,
                    'gender' => $gender,
                    'password' => $password
                ]);
                $_SESSION['member_success'] = t('Akun Anda berhasil diaktifkan. Silakan masuk untuk melihat event yang tersedia.', 'Your account has been activated. Please sign in to browse available events.');
                header('Location: ' . frontUrl('member-login'));
                exit;
            }
        }

        $this->frontView('frontend/member/verify', [
            'title' => t('Lengkapi profil member', 'Complete member profile'),
            'token' => $token,
            'user' => $user,
            'memberError' => $this->pullMessage('member_error')
        ]);
    }

    public function login()
    {
        if (isPublicMember()) {
            header('Location: ' . frontUrl('events'));
            exit;
        }
        if (isClientPortalUser()) {
            header('Location: ' . url('client/dashboard'));
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = strtolower(trim($_POST['email'] ?? ''));
            $password = $_POST['password'] ?? '';

            if (!consumeRateLimit('member_login', 8, 300)) {
                $_SESSION['member_error'] = t('Terlalu banyak percobaan masuk. Silakan coba kembali nanti.', 'Too many sign-in attempts. Please try again later.');
            } else {
                $model = new User();
                $user = $model->findByEmail($email);
                $permissions = $user && !empty($user['role_id']) ? $model->getPermissionsByRoleId($user['role_id']) : [];
                $role = strtolower((string) ($user['role_name'] ?? ''));
                $allowedPortal = $role === 'member' || ($role === 'client' && in_array('client_portal.view', $permissions, true));

                if (!$user || !$allowedPortal || !password_verify($password, $user['password']) || $user['status'] !== 'active') {
                    $_SESSION['member_error'] = t('Email atau password portal tidak sesuai.', 'Portal email or password is incorrect.');
                } else {
                    clearRateLimit('member_login');
                    $this->establishSession($user, $permissions);
                    if ($role === 'client') {
                        $redirect = $_SESSION['staff_return_after_login'] ?? url('client/dashboard');
                        unset($_SESSION['staff_return_after_login']);
                        header('Location: ' . $redirect);
                        exit;
                    }
                    $redirect = $_SESSION['member_return_after_login'] ?? frontUrl('events');
                    unset($_SESSION['member_return_after_login']);
                    header('Location: ' . $redirect);
                    exit;
                }
            }
        }

        $this->frontView('frontend/member/login', [
            'title' => t('Masuk member Iventlo', 'Iventlo member sign in'),
            'memberError' => $this->pullMessage('member_error'),
            'memberSuccess' => $this->pullMessage('member_success')
        ]);
    }

    public function forgotPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = strtolower(trim($_POST['email'] ?? ''));

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['member_error'] = t('Alamat email valid wajib diisi.', 'A valid email address is required.');
            } elseif (!consumeRateLimit('member_password_reset', 3, 900)) {
                $_SESSION['member_error'] = t('Terlalu banyak permintaan reset password. Silakan coba kembali nanti.', 'Too many password reset requests. Please try again later.');
            } else {
                $model = new User();
                $user = $model->findByEmail($email);

                $role = strtolower((string) ($user['role_name'] ?? ''));
                $portalRole = $role === 'member' || $role === 'client';
                if ($user && $portalRole && ($user['status'] ?? '') === 'active') {
                    $token = bin2hex(random_bytes(32));
                    $model->saveResetToken((int) $user['id'], $token);
                    Mailer::sendResetPasswordEmail(
                        $user['email'],
                        $user['name'],
                        frontUrl('member-reset', ['slug' => $token])
                    );
                }

                $_SESSION['member_success'] = t(
                    'Jika email terdaftar sebagai member, link reset password akan dikirimkan.',
                    'If the email is registered as a member, a password reset link will be sent.'
                );
                header('Location: ' . frontUrl('member-forgot'));
                exit;
            }
        }

        $this->frontView('frontend/member/forgot-password', [
            'title' => t('Lupa password member', 'Forgot member password'),
            'memberError' => $this->pullMessage('member_error'),
            'memberSuccess' => $this->pullMessage('member_success')
        ]);
    }

    public function resetPassword()
    {
        $token = trim((string) ($_GET['slug'] ?? ''));
        $model = new User();
        $user = $model->findActivePortalByResetToken($token);

        if (!$user) {
            $_SESSION['member_error'] = t('Link reset password tidak valid atau sudah kedaluwarsa.', 'Password reset link is invalid or expired.');
            header('Location: ' . frontUrl('member-forgot'));
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'] ?? '';
            $confirmation = $_POST['password_confirmation'] ?? '';

            if (strlen($password) < 8 || $password !== $confirmation) {
                $_SESSION['member_error'] = t('Password minimal 8 karakter dan konfirmasi harus sama.', 'Password must be at least 8 characters and confirmation must match.');
            } else {
                $model->updatePasswordByResetToken((int) $user['id'], $password);
                $_SESSION['member_success'] = t('Password berhasil diperbarui. Silakan masuk.', 'Password updated successfully. Please sign in.');
                header('Location: ' . frontUrl('member-login'));
                exit;
            }
        }

        $this->frontView('frontend/member/reset-password', [
            'title' => t('Buat password baru', 'Create new password'),
            'token' => $token,
            'memberError' => $this->pullMessage('member_error')
        ]);
    }

    public function dashboard()
    {
        requirePublicMemberLogin();

        $this->frontView('frontend/member/dashboard', [
            'title' => t('Tiket saya', 'My tickets'),
            'orders' => (new EventTicket())->ordersForMember((int) $_SESSION['user_id']),
            'memberSuccess' => $this->pullMessage('member_success'),
            'memberError' => $this->pullMessage('member_error')
        ]);
    }

    public function order()
    {
        requirePublicMemberLogin();
        $order = $this->ownedOrder();

        $this->frontView('frontend/member/order', [
            'title' => t('Detail pesanan tiket', 'Ticket order details'),
            'order' => $order,
            'memberSuccess' => $this->pullMessage('member_success'),
            'memberError' => $this->pullMessage('member_error')
        ]);
    }

    public function eventContent()
    {
        requirePublicMemberLogin();
        $order = $this->ownedOrder();
        if (($order['payment_status'] ?? '') !== 'paid') {
            $_SESSION['member_error'] = t('Konten event tersedia setelah pembayaran tiket terverifikasi.', 'Event content is available after ticket payment is verified.');
            header('Location: ' . frontUrl('member-order', ['slug' => $order['order_number']]));
            exit;
        }
        $type = $_GET['section'] ?? 'agenda';
        if (!in_array($type, EventPortalContent::types(), true)) {
            $type = 'agenda';
        }
        $this->frontView('frontend/member/event-content', [
            'title' => EventPortalContent::labels()[$type] . ' Event',
            'order' => $order,
            'contentType' => $type,
            'contents' => (new EventPortalContent())->visibleForEvent((int) $order['event_id'], $type)
        ]);
    }

    public function uploadProof()
    {
        requirePublicMemberLogin();
        $order = $this->ownedOrder();

        if (!in_array($order['payment_status'], ['pending', 'verification'], true)) {
            $_SESSION['member_error'] = t('Bukti bayar tidak dapat diperbarui untuk status pesanan ini.', 'Payment proof cannot be updated for this order status.');
            header('Location: ' . frontUrl('member-order', ['slug' => $order['order_number']]));
            exit;
        }

        $file = $_FILES['payment_proof'] ?? null;
        $path = $this->storePaymentProof($file);

        if (!$path) {
            $_SESSION['member_error'] = t('Bukti bayar harus berupa JPG, PNG, WEBP, atau PDF maksimal 5 MB.', 'Payment proof must be JPG, PNG, WEBP, or PDF up to 5 MB.');
        } elseif (!(new EventTicket())->submitPaymentProof($order['id'], (int) $_SESSION['user_id'], $path)) {
            $_SESSION['member_error'] = t('Bukti bayar tidak dapat diperbarui untuk status pesanan ini.', 'Payment proof cannot be updated for this order status.');
        } else {
            $_SESSION['member_success'] = t('Bukti pembayaran berhasil dikirim dan menunggu verifikasi.', 'Payment proof has been submitted and is awaiting verification.');
        }

        header('Location: ' . frontUrl('member-order', ['slug' => $order['order_number']]));
        exit;
    }

    public function proof()
    {
        requirePublicMemberLogin();
        $order = $this->ownedOrder();

        $this->streamPaymentProof($order['payment_proof'] ?? null);
    }

    public function checkIn()
    {
        requirePublicMemberLogin();
        $order = $this->ownedOrder();
        $_SESSION['member_error'] = t('Konfirmasi hadir dilakukan dengan memindai QR Code di lokasi acara.', 'Attendance confirmation is completed by scanning the QR Code at the venue.');
        header('Location: ' . frontUrl('member-order', ['slug' => $order['order_number']]));
        exit;
    }

    public function attendanceScan()
    {
        $token = trim((string) ($_GET['slug'] ?? ''));
        $tickets = new EventTicket();
        $event = $tickets->eventForAttendanceToken($token);

        if (!$event) {
            http_response_code(404);
            $this->frontView('frontend/member/attendance-scan', [
                'title' => t('QR Code tidak valid', 'Invalid QR Code'),
                'invalidBarcode' => true
            ]);
            return;
        }

        if (!isPublicMember()) {
            $_SESSION['member_return_after_login'] = frontUrl('member-attendance-scan', ['slug' => $token]);
            $_SESSION['member_error'] = t('Silakan masuk untuk menyelesaikan konfirmasi kehadiran.', 'Please sign in to complete attendance confirmation.');
            header('Location: ' . frontUrl('member-login'));
            exit;
        }

        $order = $tickets->findPaidMemberOrderForEvent($event['id'], (int) $_SESSION['user_id']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $order) {
            $attendeeId = (int) ($_POST['attendee_id'] ?? 0);
            if ($tickets->memberBarcodeCheckIn($event['id'], $token, $attendeeId, (int) $_SESSION['user_id'])) {
                $_SESSION['member_success'] = t('Kedatangan berhasil dikonfirmasi. Selamat datang di acara.', 'Arrival confirmed. Welcome to the event.');
            } else {
                $_SESSION['member_error'] = t('Check-in belum tersedia, tiket belum valid, atau kedatangan sudah tercatat.', 'Check-in is unavailable, the ticket is invalid, or attendance is already recorded.');
            }
            header('Location: ' . frontUrl('member-attendance-scan', ['slug' => $token]));
            exit;
        }

        $this->frontView('frontend/member/attendance-scan', [
            'title' => t('Konfirmasi kehadiran', 'Confirm attendance'),
            'event' => $event,
            'order' => $order,
            'token' => $token,
            'memberSuccess' => $this->pullMessage('member_success'),
            'memberError' => $this->pullMessage('member_error')
        ]);
    }

    public function logout()
    {
        if (isPublicMember() || isClientPortalUser()) {
            $language = currentLang();
            session_unset();
            session_regenerate_id(true);
            $_SESSION['lang'] = $language;
        }

        header('Location: ' . frontUrl('member-login'));
        exit;
    }

    private function ownedOrder()
    {
        $number = trim((string) ($_GET['slug'] ?? ''));
        $order = (new EventTicket())->findMemberOrderByNumber($number, (int) $_SESSION['user_id']);

        if (!$order) {
            $_SESSION['member_error'] = t('Pesanan tiket tidak ditemukan.', 'Ticket order was not found.');
            header('Location: ' . frontUrl('member-dashboard'));
            exit;
        }

        return $order;
    }

    private function establishSession($user, $permissions = [])
    {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['user_role'] = null;
        $_SESSION['role_id'] = $user['role_id'];
        $_SESSION['role_name'] = $user['role_name'];
        $_SESSION['data_scope'] = 'own';
        $_SESSION['employee_id'] = null;
        $_SESSION['permissions'] = $permissions;
        (new User())->updateLastLogin($user['id']);
    }

    private function validBirthDate($value)
    {
        $date = DateTime::createFromFormat('Y-m-d', $value);

        return $date && $date->format('Y-m-d') === $value && $value <= date('Y-m-d');
    }

    private function storePaymentProof($file)
    {
        if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK || ($file['size'] ?? 0) > 5 * 1024 * 1024) {
            return null;
        }

        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
        $types = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'application/pdf' => 'pdf'];

        if (!isset($types[$mime])) {
            return null;
        }

        $directory = __DIR__ . '/../../storage/payment-proofs';
        if (!is_dir($directory)) {
            mkdir($directory, 0775, true);
        }

        $name = 'proof-' . (int) $_SESSION['user_id'] . '-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . $types[$mime];
        if (!move_uploaded_file($file['tmp_name'], $directory . '/' . $name)) {
            return null;
        }

        return 'payment-proofs/' . $name;
    }

    private function streamPaymentProof($relativePath)
    {
        $file = __DIR__ . '/../../storage/' . ltrim((string) $relativePath, '/');

        if (!$relativePath || !is_file($file)) {
            http_response_code(404);
            exit(t('Bukti pembayaran tidak ditemukan.', 'Payment proof not found.'));
        }

        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file) ?: 'application/octet-stream';
        header('Content-Type: ' . $mime);
        header('Content-Disposition: inline; filename="' . basename($file) . '"');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }

    private function pullMessage($key)
    {
        $message = $_SESSION[$key] ?? null;
        unset($_SESSION[$key]);

        return $message;
    }
}
