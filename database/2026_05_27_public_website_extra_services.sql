INSERT INTO website_services
    (title_id, title_en, description_id, description_en, icon, image, slug, sort_order, status)
SELECT
    'Exhibition & expo',
    'Exhibition & expo',
    'Pengelolaan pameran, booth, tenant, sponsor, registrasi pengunjung, hingga kebutuhan operasional expo.',
    'Exhibition management covering booths, tenants, sponsors, visitor registration, and expo operations.',
    'fas fa-store-alt',
    NULL,
    'exhibition-expo',
    COALESCE((SELECT MAX(ws.sort_order) FROM website_services ws), 0) + 1,
    'active'
WHERE NOT EXISTS (
    SELECT 1 FROM website_services WHERE slug = 'exhibition-expo'
);

INSERT INTO website_services
    (title_id, title_en, description_id, description_en, icon, image, slug, sort_order, status)
SELECT
    'Event registration & ticketing',
    'Event registration & ticketing',
    'Dukungan registrasi online, ticketing, QR check-in, database peserta, dan laporan kehadiran event.',
    'Online registration, ticketing, QR check-in, participant database, and event attendance reporting support.',
    'fas fa-ticket-alt',
    NULL,
    'event-registration-ticketing',
    COALESCE((SELECT MAX(ws.sort_order) FROM website_services ws), 0) + 1,
    'active'
WHERE NOT EXISTS (
    SELECT 1 FROM website_services WHERE slug = 'event-registration-ticketing'
);

UPDATE website_services
SET icon = 'fas fa-store-alt',
    status = 'active'
WHERE slug = 'exhibition-expo';

UPDATE website_services
SET icon = 'fas fa-ticket-alt',
    status = 'active'
WHERE slug = 'event-registration-ticketing';
