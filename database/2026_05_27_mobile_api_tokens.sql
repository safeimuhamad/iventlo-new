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
    CONSTRAINT fk_mobile_api_tokens_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
