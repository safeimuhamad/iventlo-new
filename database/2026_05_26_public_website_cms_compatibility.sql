ALTER TABLE website_about
    ADD COLUMN IF NOT EXISTS image_2 VARCHAR(255) DEFAULT NULL AFTER image;

ALTER TABLE website_services
    ADD COLUMN IF NOT EXISTS sort_order INT NOT NULL DEFAULT 0 AFTER image;

ALTER TABLE website_posts
    ADD COLUMN IF NOT EXISTS sort_order INT NOT NULL DEFAULT 0 AFTER published_at,
    MODIFY COLUMN status ENUM('draft', 'publish', 'published') DEFAULT 'draft';

ALTER TABLE website_portfolios
    ADD COLUMN IF NOT EXISTS category_id VARCHAR(100) DEFAULT NULL AFTER category,
    ADD COLUMN IF NOT EXISTS category_en VARCHAR(100) DEFAULT NULL AFTER category_id,
    ADD COLUMN IF NOT EXISTS location_id VARCHAR(255) DEFAULT NULL AFTER event_date,
    ADD COLUMN IF NOT EXISTS location_en VARCHAR(255) DEFAULT NULL AFTER location_id,
    ADD COLUMN IF NOT EXISTS thumbnail VARCHAR(255) DEFAULT NULL AFTER cover_image,
    MODIFY COLUMN status ENUM('draft', 'publish', 'active', 'inactive') DEFAULT 'active';

UPDATE website_portfolios
SET category_id = COALESCE(NULLIF(category_id, ''), category),
    category_en = COALESCE(NULLIF(category_en, ''), category),
    thumbnail = COALESCE(NULLIF(thumbnail, ''), cover_image);
