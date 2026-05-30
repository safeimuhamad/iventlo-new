<?php

date_default_timezone_set('Asia/Jakarta');

require_once __DIR__ . '/../app/Core/Database.php';

$db = Database::connect();
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function seedArticleSlug(string $text): string
{
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/i', '-', $text);
    $text = trim($text, '-');

    return $text !== '' ? $text : 'artikel-iventlo';
}

function seedArticleContentId(string $title, string $topic, string $angle): string
{
    return '<p>' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . ' membahas bagaimana perusahaan dapat menyiapkan acara yang lebih rapi, terukur, dan nyaman untuk peserta. Dalam konteks event perusahaan, detail kecil seperti alur registrasi, koordinasi vendor, kualitas komunikasi, dan kesiapan tim lapangan sering menentukan kesan akhir acara.</p>'
        . '<h2>Fokus perencanaan</h2><p>Topik ' . htmlspecialchars(strtolower($topic), ENT_QUOTES, 'UTF-8') . ' perlu dimulai dari tujuan yang jelas. Tentukan pesan utama, profil peserta, kebutuhan teknis, timeline kerja, serta indikator keberhasilan sejak awal. Dengan fondasi ini, tim internal dan event organizer dapat mengambil keputusan yang lebih konsisten.</p>'
        . '<h2>Area yang perlu diperhatikan</h2><ul><li>Rundown acara dan kebutuhan produksi.</li><li>Registrasi, check-in, dan alur peserta.</li><li>Koordinasi vendor, PIC, dan approval materi.</li><li>Dokumentasi, reporting, dan evaluasi setelah acara.</li></ul>'
        . '<h2>Peran Iventlo</h2><p>Iventlo membantu perusahaan merancang, mengelola, dan mengeksekusi acara secara end-to-end. Dengan dukungan sistem event management, progress persiapan dapat dipantau lebih transparan, sementara tim lapangan memastikan acara berjalan sesuai konsep dan timeline.</p>'
        . '<h2>Kesimpulan</h2><p>' . htmlspecialchars($angle, ENT_QUOTES, 'UTF-8') . ' akan lebih efektif jika didukung perencanaan yang sistematis, komunikasi yang jelas, dan dokumentasi yang lengkap. Pendekatan ini membuat event tidak hanya berjalan lancar, tetapi juga memberi pengalaman yang profesional bagi seluruh peserta.</p>';
}

function seedArticleContentEn(string $title, string $topic, string $angle): string
{
    return '<p>' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . ' discusses how companies can prepare more structured, measurable, and comfortable events for participants. In corporate events, details such as registration flow, vendor coordination, communication quality, and field team readiness often shape the final impression.</p>'
        . '<h2>Planning focus</h2><p>The topic of ' . htmlspecialchars(strtolower($topic), ENT_QUOTES, 'UTF-8') . ' should begin with a clear objective. Define the key message, participant profile, technical needs, working timeline, and success indicators early. With this foundation, internal teams and event organizers can make more consistent decisions.</p>'
        . '<h2>Areas to consider</h2><ul><li>Event rundown and production requirements.</li><li>Registration, check-in, and participant journey.</li><li>Vendor coordination, PIC assignment, and material approval.</li><li>Documentation, reporting, and post-event evaluation.</li></ul>'
        . '<h2>Iventlo support</h2><p>Iventlo helps companies design, manage, and execute events end-to-end. With event management system support, preparation progress can be monitored more transparently, while the field team keeps execution aligned with the concept and timeline.</p>'
        . '<h2>Conclusion</h2><p>' . htmlspecialchars($angle, ENT_QUOTES, 'UTF-8') . ' becomes more effective when supported by systematic planning, clear communication, and complete documentation. This approach helps events run smoothly while creating a professional experience for all participants.</p>';
}

$columns = $db->query('SHOW COLUMNS FROM website_posts')->fetchAll(PDO::FETCH_COLUMN);
$hasCreatedAt = in_array('created_at', $columns, true);
$hasUpdatedAt = in_array('updated_at', $columns, true);

$dateSelects = ['SELECT DATE(published_at) AS article_date FROM website_posts WHERE published_at IS NOT NULL'];

if ($hasCreatedAt) {
    $dateSelects[] = 'SELECT DATE(created_at) AS article_date FROM website_posts WHERE created_at IS NOT NULL';
}

$existingDates = [];
$dateRows = $db->query(implode(' UNION ', $dateSelects))->fetchAll(PDO::FETCH_COLUMN);

foreach ($dateRows as $date) {
    if ($date) {
        $existingDates[$date] = true;
    }
}

$existingSlugs = [];
$slugRows = $db->query('SELECT slug_id FROM website_posts WHERE slug_id IS NOT NULL')->fetchAll(PDO::FETCH_COLUMN);

foreach ($slugRows as $slug) {
    $existingSlugs[$slug] = true;
}

$maxSortOrder = (int) $db->query('SELECT COALESCE(MAX(sort_order), 0) FROM website_posts')->fetchColumn();

$topics = [
    ['id' => 'Corporate Gathering', 'en' => 'Corporate Gathering'],
    ['id' => 'Employee Engagement', 'en' => 'Employee Engagement'],
    ['id' => 'Town Hall Meeting', 'en' => 'Town Hall Meeting'],
    ['id' => 'Product Launching', 'en' => 'Product Launch'],
    ['id' => 'Brand Activation', 'en' => 'Brand Activation'],
    ['id' => 'Awarding Night', 'en' => 'Awarding Night'],
    ['id' => 'Gala Dinner', 'en' => 'Gala Dinner'],
    ['id' => 'Conference Perusahaan', 'en' => 'Corporate Conference'],
    ['id' => 'Seminar Korporat', 'en' => 'Corporate Seminar'],
    ['id' => 'Workshop Internal', 'en' => 'Internal Workshop'],
    ['id' => 'Team Building', 'en' => 'Team Building'],
    ['id' => 'Registrasi Event Digital', 'en' => 'Digital Event Registration'],
    ['id' => 'QR Check-In Event', 'en' => 'Event QR Check-In'],
    ['id' => 'Event Documentation', 'en' => 'Event Documentation'],
    ['id' => 'Aftermovie Event', 'en' => 'Event Aftermovie'],
    ['id' => 'Vendor Management', 'en' => 'Vendor Management'],
    ['id' => 'Stage Production', 'en' => 'Stage Production'],
    ['id' => 'Lighting dan Sound System', 'en' => 'Lighting and Sound System'],
    ['id' => 'LED Multimedia', 'en' => 'LED Multimedia'],
    ['id' => 'Event Reporting', 'en' => 'Event Reporting'],
];

$angles = [
    ['id' => 'Checklist Praktis', 'en' => 'Practical Checklist'],
    ['id' => 'Panduan Perencanaan', 'en' => 'Planning Guide'],
    ['id' => 'Strategi Eksekusi', 'en' => 'Execution Strategy'],
    ['id' => 'Tips Meningkatkan Kualitas', 'en' => 'Tips to Improve Quality'],
    ['id' => 'Ide untuk Perusahaan Modern', 'en' => 'Ideas for Modern Companies'],
];

$baseColumns = [
    'title_id',
    'title_en',
    'slug_id',
    'slug_en',
    'excerpt_id',
    'excerpt_en',
    'content_id',
    'content_en',
    'featured_image',
    'meta_title',
    'meta_keywords',
    'meta_description',
    'published_at',
    'sort_order',
    'status',
];

if ($hasCreatedAt) {
    $baseColumns[] = 'created_at';
}

if ($hasUpdatedAt) {
    $baseColumns[] = 'updated_at';
}

$placeholders = array_map(static fn ($column) => ':' . $column, $baseColumns);
$stmt = $db->prepare(
    'INSERT INTO website_posts (' . implode(', ', $baseColumns) . ') VALUES (' . implode(', ', $placeholders) . ')'
);

$today = new DateTimeImmutable('today', new DateTimeZone('Asia/Jakarta'));
$dateCursor = $today->sub(new DateInterval('P1D'));
$inserted = 0;
$skippedSlugs = 0;
$articleNumber = 0;
$defaultImage = 'website/content/article-corporate.jpg';

$db->beginTransaction();

try {
    while ($inserted < 100 && $articleNumber < 500) {
        $topic = $topics[$articleNumber % count($topics)];
        $angle = $angles[(int) floor($articleNumber / count($topics)) % count($angles)];
        $series = (int) floor($articleNumber / (count($topics) * count($angles))) + 1;
        $suffixId = $series > 1 ? ' - Edisi ' . $series : '';
        $suffixEn = $series > 1 ? ' - Edition ' . $series : '';

        $titleId = $angle['id'] . ' ' . $topic['id'] . ' untuk Event Perusahaan' . $suffixId;
        $titleEn = $angle['en'] . ' for Corporate ' . $topic['en'] . $suffixEn;
        $slugId = seedArticleSlug($titleId);
        $slugEn = seedArticleSlug($titleEn);
        $articleNumber++;

        if (isset($existingSlugs[$slugId])) {
            $skippedSlugs++;
            continue;
        }

        while (isset($existingDates[$dateCursor->format('Y-m-d')])) {
            $dateCursor = $dateCursor->sub(new DateInterval('P1D'));
        }

        $date = $dateCursor->format('Y-m-d');
        $hour = str_pad((string) (8 + ($inserted % 10)), 2, '0', STR_PAD_LEFT);
        $minute = str_pad((string) (($inserted * 7) % 60), 2, '0', STR_PAD_LEFT);
        $timestamp = $date . ' ' . $hour . ':' . $minute . ':00';

        $params = [
            ':title_id' => $titleId,
            ':title_en' => $titleEn,
            ':slug_id' => $slugId,
            ':slug_en' => $slugEn,
            ':excerpt_id' => 'Ringkasan praktis tentang ' . strtolower($topic['id']) . ' agar acara perusahaan lebih terstruktur, profesional, dan mudah dievaluasi.',
            ':excerpt_en' => 'A practical summary about ' . strtolower($topic['en']) . ' to make corporate events more structured, professional, and easier to evaluate.',
            ':content_id' => seedArticleContentId($titleId, $topic['id'], $angle['id']),
            ':content_en' => seedArticleContentEn($titleEn, $topic['en'], $angle['en']),
            ':featured_image' => $defaultImage,
            ':meta_title' => $titleId . ' | Iventlo',
            ':meta_keywords' => 'iventlo, event organizer, corporate event, ' . strtolower($topic['id']),
            ':meta_description' => 'Artikel Iventlo tentang ' . strtolower($topic['id']) . ' untuk membantu perusahaan merancang event yang lebih profesional.',
            ':published_at' => $timestamp,
            ':sort_order' => $maxSortOrder + $inserted + 1,
            ':status' => 'published',
        ];

        if ($hasCreatedAt) {
            $params[':created_at'] = $timestamp;
        }

        if ($hasUpdatedAt) {
            $params[':updated_at'] = $timestamp;
        }

        $stmt->execute($params);

        $existingSlugs[$slugId] = true;
        $existingDates[$date] = true;
        $dateCursor = $dateCursor->sub(new DateInterval('P1D'));
        $inserted++;
    }

    if ($inserted !== 100) {
        throw new RuntimeException('Seeder hanya berhasil menyiapkan ' . $inserted . ' artikel. Target 100 artikel belum terpenuhi.');
    }

    $db->commit();
} catch (Throwable $exception) {
    $db->rollBack();
    throw $exception;
}

echo 'Inserted articles: ' . $inserted . PHP_EOL;
echo 'Skipped existing slugs: ' . $skippedSlugs . PHP_EOL;
echo 'Latest inserted date: ' . $today->sub(new DateInterval('P1D'))->format('Y-m-d') . PHP_EOL;
echo 'Default image: ' . $defaultImage . PHP_EOL;
