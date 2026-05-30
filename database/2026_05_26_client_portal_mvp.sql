CREATE TABLE IF NOT EXISTS master_events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_code VARCHAR(50) NOT NULL,
    title VARCHAR(200) NOT NULL,
    client_name VARCHAR(150) NOT NULL,
    status ENUM('planning', 'preparation', 'on_going', 'completed', 'cancelled') NOT NULL DEFAULT 'planning',
    event_date DATE NULL,
    end_date DATE NULL,
    venue VARCHAR(255) NULL,
    description TEXT NULL,
    progress TINYINT UNSIGNED NOT NULL DEFAULT 0,
    created_by INT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uk_master_events_code (event_code),
    KEY idx_master_events_date (event_date),
    KEY idx_master_events_status (status)
);

CREATE TABLE IF NOT EXISTS event_client_access (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    user_id INT NOT NULL,
    access_level ENUM('admin', 'viewer') NOT NULL DEFAULT 'viewer',
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uk_event_client_access (event_id, user_id),
    KEY idx_event_client_user_status (user_id, status),
    CONSTRAINT fk_event_client_access_event FOREIGN KEY (event_id) REFERENCES master_events(id) ON DELETE CASCADE,
    CONSTRAINT fk_event_client_access_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS event_client_milestones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT NULL,
    due_date DATE NULL,
    status ENUM('pending', 'progress', 'completed') NOT NULL DEFAULT 'pending',
    sort_order INT NOT NULL DEFAULT 0,
    visible_to_client TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_event_milestone_visible (event_id, visible_to_client, sort_order),
    CONSTRAINT fk_event_client_milestones_event FOREIGN KEY (event_id) REFERENCES master_events(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS event_documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    category ENUM('proposal', 'quotation', 'contract', 'invoice', 'rundown', 'layout', 'report', 'other') NOT NULL DEFAULT 'other',
    file_path VARCHAR(255) NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_type VARCHAR(100) NULL,
    visible_to_client TINYINT(1) NOT NULL DEFAULT 0,
    uploaded_by INT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_event_document_visible (event_id, visible_to_client),
    CONSTRAINT fk_event_documents_event FOREIGN KEY (event_id) REFERENCES master_events(id) ON DELETE CASCADE,
    CONSTRAINT fk_event_documents_uploader FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS event_client_notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NULL,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    type VARCHAR(40) NOT NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_client_notification_user (user_id, is_read, created_at),
    CONSTRAINT fk_event_notifications_event FOREIGN KEY (event_id) REFERENCES master_events(id) ON DELETE CASCADE,
    CONSTRAINT fk_event_notifications_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

ALTER TABLE approval_requests
    ADD COLUMN IF NOT EXISTS event_id INT NULL AFTER reference_id;

ALTER TABLE approval_requests
    ADD INDEX IF NOT EXISTS idx_approval_requests_event_id (event_id);

INSERT INTO roles (name, description, status)
SELECT 'Client', 'Akses client ke event yang ditugaskan pada Client Portal', 'active'
WHERE NOT EXISTS (SELECT 1 FROM roles WHERE LOWER(name) = 'client');

INSERT INTO permissions (module, action_name, permission_key)
SELECT 'client_portal', 'view', 'client_portal.view'
WHERE NOT EXISTS (SELECT 1 FROM permissions WHERE permission_key = 'client_portal.view');

INSERT INTO permissions (module, action_name, permission_key)
SELECT 'master_event', 'manage', 'master_event.manage'
WHERE NOT EXISTS (SELECT 1 FROM permissions WHERE permission_key = 'master_event.manage');

INSERT INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id
FROM roles r
JOIN permissions p ON p.permission_key = 'client_portal.view'
WHERE LOWER(r.name) = 'client'
AND NOT EXISTS (
    SELECT 1 FROM role_permissions rp
    WHERE rp.role_id = r.id AND rp.permission_id = p.id
);

DELETE rp
FROM role_permissions rp
INNER JOIN roles r ON r.id = rp.role_id
INNER JOIN permissions p ON p.id = rp.permission_id
WHERE LOWER(r.name) = 'client'
AND p.module <> 'client_portal';
