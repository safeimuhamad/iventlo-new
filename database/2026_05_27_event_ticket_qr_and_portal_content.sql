ALTER TABLE event_ticket_attendees
    ADD COLUMN IF NOT EXISTS ticket_qr_token VARCHAR(64) NULL AFTER ticket_code;

UPDATE event_ticket_attendees
SET ticket_qr_token = LOWER(MD5(CONCAT(UUID(), '-', id, '-', NOW())))
WHERE ticket_qr_token IS NULL OR ticket_qr_token = '';

ALTER TABLE event_ticket_attendees
    ADD UNIQUE INDEX IF NOT EXISTS uk_event_ticket_qr_token (ticket_qr_token);

CREATE TABLE IF NOT EXISTS event_portal_contents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    content_type ENUM('agenda', 'speaker', 'material', 'gallery', 'qna', 'polling', 'certificate', 'information') NOT NULL,
    title VARCHAR(200) NOT NULL,
    subtitle VARCHAR(200) NULL,
    description TEXT NULL,
    scheduled_at DATETIME NULL,
    location VARCHAR(255) NULL,
    file_path VARCHAR(255) NULL,
    file_name VARCHAR(255) NULL,
    file_type VARCHAR(100) NULL,
    sort_order INT NOT NULL DEFAULT 0,
    visible_to_member TINYINT(1) NOT NULL DEFAULT 1,
    created_by INT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_event_portal_content_visible (event_id, content_type, visible_to_member, sort_order),
    CONSTRAINT fk_event_portal_contents_event FOREIGN KEY (event_id) REFERENCES master_events(id) ON DELETE CASCADE,
    CONSTRAINT fk_event_portal_contents_creator FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);
