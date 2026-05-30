INSERT INTO roles (name, description, status)
SELECT 'Member', 'Peserta publik yang mendaftar untuk membeli dan mengelola tiket event', 'active'
WHERE NOT EXISTS (SELECT 1 FROM roles WHERE LOWER(name) = 'member');

ALTER TABLE event_ticket_orders
    ADD COLUMN IF NOT EXISTS member_user_id INT NULL AFTER event_id,
    ADD COLUMN IF NOT EXISTS payment_proof VARCHAR(255) NULL AFTER payment_note,
    ADD COLUMN IF NOT EXISTS proof_uploaded_at DATETIME NULL AFTER payment_proof,
    ADD COLUMN IF NOT EXISTS payment_submitted_at DATETIME NULL AFTER proof_uploaded_at;

ALTER TABLE event_ticket_orders
    MODIFY COLUMN payment_status ENUM('pending', 'verification', 'paid', 'cancelled') NOT NULL DEFAULT 'pending',
    ADD INDEX IF NOT EXISTS idx_event_ticket_member (member_user_id, ordered_at),
    ADD CONSTRAINT fk_event_ticket_member FOREIGN KEY (member_user_id) REFERENCES users(id) ON DELETE SET NULL;
