<?php

class MobileApiToken
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->ensureTable();
    }

    public function issue($userId, $deviceName = null)
    {
        $plainToken = $this->signedToken((int) $userId);

        try {
            $stmt = $this->db->prepare("
                INSERT INTO mobile_api_tokens (user_id, token_hash, device_name, expires_at)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                (int) $userId,
                hash('sha256', $plainToken),
                $deviceName ? substr((string) $deviceName, 0, 120) : null,
                date('Y-m-d H:i:s', strtotime('+60 days'))
            ]);
        } catch (Throwable $exception) {
            error_log('[Mobile API Token] ' . $exception->getMessage());
        }

        return $plainToken;
    }

    public function userForToken($plainToken)
    {
        $stmt = $this->db->prepare("
            SELECT u.*, r.name AS role_name, t.id AS token_id
            FROM mobile_api_tokens t
            INNER JOIN users u ON u.id = t.user_id
            LEFT JOIN roles r ON r.id = u.role_id
            WHERE t.token_hash = ?
            AND t.revoked_at IS NULL
            AND t.expires_at >= NOW()
            AND u.status = 'active'
            LIMIT 1
        ");
        $stmt->execute([hash('sha256', (string) $plainToken)]);
        $user = $stmt->fetch();

        if ($user) {
            $this->touch((int) $user['token_id']);
            return $user;
        }

        return $this->userFromSignedToken($plainToken);
    }

    public function revoke($plainToken)
    {
        $stmt = $this->db->prepare("
            UPDATE mobile_api_tokens
            SET revoked_at = NOW()
            WHERE token_hash = ? AND revoked_at IS NULL
        ");

        return $stmt->execute([hash('sha256', (string) $plainToken)]);
    }

    private function touch($id)
    {
        $stmt = $this->db->prepare("UPDATE mobile_api_tokens SET last_used_at = NOW() WHERE id = ?");
        $stmt->execute([(int) $id]);
    }

    private function ensureTable()
    {
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS mobile_api_tokens (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                token_hash CHAR(64) NOT NULL,
                device_name VARCHAR(120) NULL,
                last_used_at DATETIME NULL,
                expires_at DATETIME NOT NULL,
                revoked_at DATETIME NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY uk_mobile_api_token_hash (token_hash),
                KEY idx_mobile_api_tokens_user (user_id, revoked_at, expires_at),
                KEY idx_mobile_api_tokens_user_id (user_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }

    private function signedToken($userId)
    {
        $payload = [
            'uid' => (int) $userId,
            'exp' => time() + (60 * 24 * 60 * 60),
            'rnd' => bin2hex(random_bytes(16))
        ];

        $body = $this->base64UrlEncode(json_encode($payload, JSON_UNESCAPED_SLASHES));
        $signature = hash_hmac('sha256', $body, $this->secret());

        return 'm1.' . $body . '.' . $signature;
    }

    private function userFromSignedToken($plainToken)
    {
        $parts = explode('.', (string) $plainToken);
        if (count($parts) !== 3 || $parts[0] !== 'm1') {
            return null;
        }

        [$version, $body, $signature] = $parts;
        $expected = hash_hmac('sha256', $body, $this->secret());
        if (!hash_equals($expected, $signature)) {
            return null;
        }

        $payload = json_decode($this->base64UrlDecode($body), true);
        if (!is_array($payload) || empty($payload['uid']) || empty($payload['exp']) || (int) $payload['exp'] < time()) {
            return null;
        }

        $user = (new User())->find((int) $payload['uid']);

        return $user && ($user['status'] ?? '') === 'active' ? $user : null;
    }

    private function secret()
    {
        $config = require __DIR__ . '/../../config/app.php';
        return getenv('APP_KEY') ?: ($config['app_url'] ?? 'iventlo-mobile');
    }

    private function base64UrlEncode($value)
    {
        return rtrim(strtr(base64_encode((string) $value), '+/', '-_'), '=');
    }

    private function base64UrlDecode($value)
    {
        $padding = strlen((string) $value) % 4;
        if ($padding) {
            $value .= str_repeat('=', 4 - $padding);
        }

        return base64_decode(strtr((string) $value, '-_', '+/'));
    }
}
