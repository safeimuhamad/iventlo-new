ALTER TABLE master_events
    ADD COLUMN IF NOT EXISTS title_en VARCHAR(200) NULL AFTER title,
    ADD COLUMN IF NOT EXISTS description_en TEXT NULL AFTER description,
    ADD COLUMN IF NOT EXISTS venue_en VARCHAR(255) NULL AFTER venue,
    ADD COLUMN IF NOT EXISTS public_slug VARCHAR(190) NULL AFTER event_code,
    ADD COLUMN IF NOT EXISTS public_slug_en VARCHAR(190) NULL AFTER public_slug,
    ADD COLUMN IF NOT EXISTS cover_image VARCHAR(255) NULL AFTER description_en,
    ADD COLUMN IF NOT EXISTS is_public TINYINT(1) NOT NULL DEFAULT 0 AFTER cover_image,
    ADD COLUMN IF NOT EXISTS is_paid TINYINT(1) NOT NULL DEFAULT 0 AFTER is_public,
    ADD COLUMN IF NOT EXISTS ticket_price DECIMAL(15,2) NOT NULL DEFAULT 0 AFTER is_paid,
    ADD COLUMN IF NOT EXISTS participant_quota INT UNSIGNED NOT NULL DEFAULT 0 AFTER ticket_price,
    ADD COLUMN IF NOT EXISTS ticket_sales_status ENUM('closed', 'open', 'sold_out') NOT NULL DEFAULT 'closed' AFTER participant_quota;

ALTER TABLE master_events
    ADD UNIQUE INDEX IF NOT EXISTS uk_master_events_public_slug (public_slug),
    ADD UNIQUE INDEX IF NOT EXISTS uk_master_events_public_slug_en (public_slug_en);

CREATE TABLE IF NOT EXISTS event_ticket_orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    order_number VARCHAR(40) NOT NULL,
    buyer_name VARCHAR(150) NOT NULL,
    buyer_email VARCHAR(150) NOT NULL,
    buyer_phone VARCHAR(50) NOT NULL,
    quantity INT UNSIGNED NOT NULL DEFAULT 1,
    unit_price DECIMAL(15,2) NOT NULL DEFAULT 0,
    total_amount DECIMAL(15,2) NOT NULL DEFAULT 0,
    payment_status ENUM('pending', 'paid', 'cancelled') NOT NULL DEFAULT 'pending',
    payment_note TEXT NULL,
    ordered_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    paid_at DATETIME NULL,
    verified_by INT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uk_event_ticket_order_number (order_number),
    KEY idx_event_ticket_orders_event_payment (event_id, payment_status),
    CONSTRAINT fk_event_ticket_orders_event FOREIGN KEY (event_id) REFERENCES master_events(id) ON DELETE CASCADE,
    CONSTRAINT fk_event_ticket_orders_verifier FOREIGN KEY (verified_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS event_ticket_attendees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    ticket_order_id INT NOT NULL,
    ticket_code VARCHAR(50) NOT NULL,
    attendee_name VARCHAR(150) NOT NULL,
    attendee_email VARCHAR(150) NULL,
    check_in_status ENUM('not_arrived', 'arrived') NOT NULL DEFAULT 'not_arrived',
    checked_in_at DATETIME NULL,
    checked_in_by INT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uk_event_ticket_code (ticket_code),
    KEY idx_event_attendees_event_checkin (event_id, check_in_status),
    CONSTRAINT fk_event_attendees_event FOREIGN KEY (event_id) REFERENCES master_events(id) ON DELETE CASCADE,
    CONSTRAINT fk_event_attendees_order FOREIGN KEY (ticket_order_id) REFERENCES event_ticket_orders(id) ON DELETE CASCADE,
    CONSTRAINT fk_event_attendees_checker FOREIGN KEY (checked_in_by) REFERENCES users(id) ON DELETE SET NULL
);
