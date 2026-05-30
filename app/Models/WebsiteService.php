<?php

class WebsiteService
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->ensureSeoColumns();
    }

    public function all()
    {
        $stmt = $this->db->query("
            SELECT *
            FROM website_services
            ORDER BY id DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function paginate($limit, $offset)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM website_services
            ORDER BY sort_order ASC, id DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->bindValue(1, (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(2, (int) $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll()
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM website_services")->fetchColumn();
    }

    public function active()
    {
        $this->ensureExtraPublicServices();

        $stmt = $this->db->query("
            SELECT *
            FROM website_services
            WHERE status = 'active'
            ORDER BY sort_order ASC, id DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function ensureExtraPublicServices()
    {
        $services = [
            [
                'title_id' => 'Exhibition & expo',
                'title_en' => 'Exhibition & expo',
                'description_id' => 'Pengelolaan pameran, booth, tenant, sponsor, registrasi pengunjung, hingga kebutuhan operasional expo.',
                'description_en' => 'Exhibition management covering booths, tenants, sponsors, visitor registration, and expo operations.',
                'icon' => 'fas fa-store-alt',
                'slug' => 'exhibition-expo',
            ],
            [
                'title_id' => 'Event registration & ticketing',
                'title_en' => 'Event registration & ticketing',
                'description_id' => 'Dukungan registrasi online, ticketing, QR check-in, database peserta, dan laporan kehadiran event.',
                'description_en' => 'Online registration, ticketing, QR check-in, participant database, and event attendance reporting support.',
                'icon' => 'fas fa-ticket-alt',
                'slug' => 'event-registration-ticketing',
            ],
        ];

        foreach ($services as $service) {
            $stmt = $this->db->prepare("
                INSERT INTO website_services
                    (title_id, title_en, description_id, description_en, icon, image, slug, sort_order, status)
                SELECT
                    :title_id,
                    :title_en,
                    :description_id,
                    :description_en,
                    :icon,
                    NULL,
                    :slug,
                    COALESCE((SELECT MAX(ws.sort_order) FROM website_services ws), 0) + 1,
                    'active'
                WHERE NOT EXISTS (
                    SELECT 1 FROM website_services WHERE slug = :existing_slug
                )
            ");

            $stmt->execute([
                ':title_id' => $service['title_id'],
                ':title_en' => $service['title_en'],
                ':description_id' => $service['description_id'],
                ':description_en' => $service['description_en'],
                ':icon' => $service['icon'],
                ':slug' => $service['slug'],
                ':existing_slug' => $service['slug'],
            ]);

            $update = $this->db->prepare("
                UPDATE website_services
                SET icon = :icon,
                    sort_order = CASE
                        WHEN sort_order = 0 THEN COALESCE((SELECT max_order FROM (SELECT MAX(sort_order) AS max_order FROM website_services) current_order), 0) + 1
                        ELSE sort_order
                    END,
                    status = 'active'
                WHERE slug = :slug
            ");
            $update->execute([
                ':icon' => $service['icon'],
                ':slug' => $service['slug'],
            ]);
        }
    }

    private function ensureSeoColumns()
    {
        $columns = [
            'meta_title_id' => "ALTER TABLE website_services ADD COLUMN meta_title_id VARCHAR(255) NULL AFTER description_en",
            'meta_title_en' => "ALTER TABLE website_services ADD COLUMN meta_title_en VARCHAR(255) NULL AFTER meta_title_id",
            'meta_description_id' => "ALTER TABLE website_services ADD COLUMN meta_description_id TEXT NULL AFTER meta_title_en",
            'meta_description_en' => "ALTER TABLE website_services ADD COLUMN meta_description_en TEXT NULL AFTER meta_description_id",
            'meta_keywords_id' => "ALTER TABLE website_services ADD COLUMN meta_keywords_id TEXT NULL AFTER meta_description_en",
            'meta_keywords_en' => "ALTER TABLE website_services ADD COLUMN meta_keywords_en TEXT NULL AFTER meta_keywords_id",
            'og_title_id' => "ALTER TABLE website_services ADD COLUMN og_title_id VARCHAR(255) NULL AFTER meta_keywords_en",
            'og_title_en' => "ALTER TABLE website_services ADD COLUMN og_title_en VARCHAR(255) NULL AFTER og_title_id",
            'og_description_id' => "ALTER TABLE website_services ADD COLUMN og_description_id TEXT NULL AFTER og_title_en",
            'og_description_en' => "ALTER TABLE website_services ADD COLUMN og_description_en TEXT NULL AFTER og_description_id",
            'meta_robots' => "ALTER TABLE website_services ADD COLUMN meta_robots VARCHAR(120) NULL AFTER og_description_en",
        ];

        foreach ($columns as $column => $sql) {
            $stmt = $this->db->prepare("SHOW COLUMNS FROM website_services LIKE ?");
            $stmt->execute([$column]);

            if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
                $this->db->exec($sql);
            }
        }
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM website_services
            WHERE id = ?
        ");

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findActiveBySlug($slug)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM website_services
            WHERE slug = ?
            AND status = 'active'
            LIMIT 1
        ");

        $stmt->execute([$slug]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO website_services
            (
                title_id,
                title_en,
                description_id,
                description_en,
                meta_title_id,
                meta_title_en,
                meta_description_id,
                meta_description_en,
                meta_keywords_id,
                meta_keywords_en,
                og_title_id,
                og_title_en,
                og_description_id,
                og_description_en,
                meta_robots,
                icon,
                image,
                sort_order,
                status
            )
            VALUES
            (
                :title_id,
                :title_en,
                :description_id,
                :description_en,
                :meta_title_id,
                :meta_title_en,
                :meta_description_id,
                :meta_description_en,
                :meta_keywords_id,
                :meta_keywords_en,
                :og_title_id,
                :og_title_en,
                :og_description_id,
                :og_description_en,
                :meta_robots,
                :icon,
                :image,
                :sort_order,
                :status
            )
        ");

        return $stmt->execute([
            ':title_id' => $data['title_id'],
            ':title_en' => $data['title_en'],
            ':description_id' => $data['description_id'],
            ':description_en' => $data['description_en'],
            ':meta_title_id' => $data['meta_title_id'] ?? '',
            ':meta_title_en' => $data['meta_title_en'] ?? '',
            ':meta_description_id' => $data['meta_description_id'] ?? '',
            ':meta_description_en' => $data['meta_description_en'] ?? '',
            ':meta_keywords_id' => $data['meta_keywords_id'] ?? '',
            ':meta_keywords_en' => $data['meta_keywords_en'] ?? '',
            ':og_title_id' => $data['og_title_id'] ?? '',
            ':og_title_en' => $data['og_title_en'] ?? '',
            ':og_description_id' => $data['og_description_id'] ?? '',
            ':og_description_en' => $data['og_description_en'] ?? '',
            ':meta_robots' => $data['meta_robots'] ?? 'index, follow, max-image-preview:large',
            ':icon' => $data['icon'],
            ':image' => $data['image'],
            ':sort_order' => $data['sort_order'],
            ':status' => $data['status'],
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE website_services SET
                title_id = :title_id,
                title_en = :title_en,
                description_id = :description_id,
                description_en = :description_en,
                meta_title_id = :meta_title_id,
                meta_title_en = :meta_title_en,
                meta_description_id = :meta_description_id,
                meta_description_en = :meta_description_en,
                meta_keywords_id = :meta_keywords_id,
                meta_keywords_en = :meta_keywords_en,
                og_title_id = :og_title_id,
                og_title_en = :og_title_en,
                og_description_id = :og_description_id,
                og_description_en = :og_description_en,
                meta_robots = :meta_robots,
                icon = :icon,
                image = :image,
                sort_order = :sort_order,
                status = :status
            WHERE id = :id
        ");

        return $stmt->execute([
            ':id' => $id,
            ':title_id' => $data['title_id'],
            ':title_en' => $data['title_en'],
            ':description_id' => $data['description_id'],
            ':description_en' => $data['description_en'],
            ':meta_title_id' => $data['meta_title_id'] ?? '',
            ':meta_title_en' => $data['meta_title_en'] ?? '',
            ':meta_description_id' => $data['meta_description_id'] ?? '',
            ':meta_description_en' => $data['meta_description_en'] ?? '',
            ':meta_keywords_id' => $data['meta_keywords_id'] ?? '',
            ':meta_keywords_en' => $data['meta_keywords_en'] ?? '',
            ':og_title_id' => $data['og_title_id'] ?? '',
            ':og_title_en' => $data['og_title_en'] ?? '',
            ':og_description_id' => $data['og_description_id'] ?? '',
            ':og_description_en' => $data['og_description_en'] ?? '',
            ':meta_robots' => $data['meta_robots'] ?? 'index, follow, max-image-preview:large',
            ':icon' => $data['icon'],
            ':image' => $data['image'],
            ':sort_order' => $data['sort_order'],
            ':status' => $data['status'],
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("
            DELETE FROM website_services
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }
}
