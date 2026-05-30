ALTER TABLE master_events
    ADD COLUMN IF NOT EXISTS attendance_token VARCHAR(64) NULL AFTER ticket_sales_status,
    ADD COLUMN IF NOT EXISTS attendance_checkin_enabled TINYINT(1) NOT NULL DEFAULT 0 AFTER attendance_token;

ALTER TABLE master_events
    ADD UNIQUE INDEX IF NOT EXISTS uk_master_events_attendance_token (attendance_token);
