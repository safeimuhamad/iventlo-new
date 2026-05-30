<?php

function asset($path)
{
    return baseUrl() . '/assets/' . ltrim($path, '/');
}

function frontAsset($path)
{
    return baseUrl() . '/frontend/' . ltrim($path, '/');
}

function uploadAsset($path)
{
    return baseUrl() . '/uploads/' . ltrim($path, '/');
}

function url($page, $params = [])
{
    $page = (string) $page;

    if (str_starts_with($page, '?')) {
        parse_str(ltrim($page, '?'), $legacyParams);
        $page = (string) ($legacyParams['page'] ?? '');
        unset($legacyParams['page']);
        $params = array_merge($legacyParams, $params);
    }

    $url = baseUrl() . '/' . trim($page, '/');

    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }

    return $url;
}

function frontRoutePaths()
{
    return [
        'id' => [
            'home' => '',
            'about' => 'tentang-kami',
            'services' => 'layanan',
            'service-detail' => 'layanan/{slug}',
            'products' => 'produk',
            'events' => 'event',
            'event-detail' => 'event/{slug}',
            'event-purchase' => 'event/{slug}/beli',
            'member-register' => 'member/daftar',
            'member-verify' => 'member/verifikasi/{slug}',
            'member-login' => 'member/masuk',
            'member-forgot' => 'member/lupa-password',
            'member-reset' => 'member/reset-password/{slug}',
            'member-dashboard' => 'member/dashboard',
            'member-order' => 'member/pesanan/{slug}',
            'member-event-content' => 'member/pesanan/{slug}/konten',
            'member-payment' => 'member/pesanan/{slug}/bukti-bayar',
            'member-proof' => 'member/pesanan/{slug}/bukti-bayar/file',
            'member-checkin' => 'member/pesanan/{slug}/check-in',
            'member-attendance-scan' => 'hadir/{slug}',
            'staff-ticket-checkin' => 'petugas/check-in/{slug}',
            'member-logout' => 'member/keluar',
            'portfolio' => 'portfolio',
            'portfolio-detail' => 'portfolio/{slug}',
            'blog' => 'artikel',
            'blog-detail' => 'artikel/{slug}',
            'contact' => 'kontak',
            'contact-send' => 'kontak/kirim',
            'vendor-register' => 'vendor',
            'vendor-register-store' => 'vendor/daftar',
        ],
        'en' => [
            'home' => 'en',
            'about' => 'en/about',
            'services' => 'en/services',
            'service-detail' => 'en/services/{slug}',
            'products' => 'en/product',
            'events' => 'en/events',
            'event-detail' => 'en/events/{slug}',
            'event-purchase' => 'en/events/{slug}/buy',
            'member-register' => 'en/member/register',
            'member-verify' => 'en/member/verify/{slug}',
            'member-login' => 'en/member/login',
            'member-forgot' => 'en/member/forgot-password',
            'member-reset' => 'en/member/reset-password/{slug}',
            'member-dashboard' => 'en/member/dashboard',
            'member-order' => 'en/member/orders/{slug}',
            'member-event-content' => 'en/member/orders/{slug}/content',
            'member-payment' => 'en/member/orders/{slug}/payment-proof',
            'member-proof' => 'en/member/orders/{slug}/payment-proof/file',
            'member-checkin' => 'en/member/orders/{slug}/check-in',
            'member-attendance-scan' => 'en/check-in/{slug}',
            'staff-ticket-checkin' => 'en/staff/check-in/{slug}',
            'member-logout' => 'en/member/logout',
            'portfolio' => 'en/portfolio',
            'portfolio-detail' => 'en/portfolio/{slug}',
            'blog' => 'en/blog',
            'blog-detail' => 'en/blog/{slug}',
            'contact' => 'en/contact',
            'contact-send' => 'en/contact/send',
            'vendor-register' => 'en/vendor',
            'vendor-register-store' => 'en/vendor/register',
        ],
    ];
}

function matchFrontRoute($route)
{
    $route = trim((string) $route, '/');

    foreach (frontRoutePaths() as $lang => $routes) {
        foreach ($routes as $page => $path) {
            if (strpos($path, '{slug}') === false && $route === $path) {
                return ['page' => $page, 'lang' => $lang];
            }

            if (strpos($path, '{slug}') !== false) {
                $pattern = '#^' . str_replace('\{slug\}', '([^/]+)', preg_quote($path, '#')) . '$#';

                if (preg_match($pattern, $route, $matches)) {
                    return [
                        'page' => $page,
                        'lang' => $lang,
                        'slug' => rawurldecode($matches[1]),
                    ];
                }
            }
        }
    }

    return null;
}

function frontUrl($page, $params = [], $lang = null)
{
    $lang = in_array($lang, ['id', 'en'], true) ? $lang : currentLang();
    $routes = frontRoutePaths();
    $path = $routes[$lang][$page] ?? null;

    if ($path === null) {
        $params['lang'] = $lang;
        return url($page, $params);
    }

    if (strpos($path, '{slug}') !== false) {
        $slug = trim((string) ($params['slug'] ?? ''), '/');
        $path = str_replace('{slug}', rawurlencode($slug), $path);
        unset($params['slug']);
    }

    unset($params['lang'], $params['page'], $params['route']);

    $target = baseUrl() . '/' . $path;

    if (!empty($params)) {
        $target .= '?' . http_build_query($params);
    }

    return $target;
}

function frontAudienceSegments()
{
    return [
        [
            'icon' => 'fas fa-building',
            'label_id' => 'Perusahaan',
            'label_en' => 'Companies',
            'detail_id' => 'Town hall & gathering',
            'detail_en' => 'Town halls & gatherings',
        ],
        [
            'icon' => 'fas fa-rocket',
            'label_id' => 'Brand',
            'label_en' => 'Brands',
            'detail_id' => 'Launching & aktivasi',
            'detail_en' => 'Launches & activations',
        ],
        [
            'icon' => 'fas fa-microphone',
            'label_id' => 'Instansi',
            'label_en' => 'Institutions',
            'detail_id' => 'Seminar & seremoni',
            'detail_en' => 'Seminars & ceremonies',
        ],
        [
            'icon' => 'fas fa-users',
            'label_id' => 'Komunitas',
            'label_en' => 'Communities',
            'detail_id' => 'Meetup & festival',
            'detail_en' => 'Meetups & festivals',
        ],
        [
            'icon' => 'fas fa-heart',
            'label_id' => 'Acara privat',
            'label_en' => 'Private events',
            'detail_id' => 'Celebration & wedding',
            'detail_en' => 'Celebrations & weddings',
        ],
        [
            'icon' => 'fas fa-graduation-cap',
            'label_id' => 'Edukasi',
            'label_en' => 'Education',
            'detail_id' => 'Workshop & konferensi',
            'detail_en' => 'Workshops & conferences',
        ],
        [
            'icon' => 'fas fa-map-marker-alt',
            'label_id' => 'Venue',
            'label_en' => 'Venues',
            'detail_id' => 'Layout & hospitality',
            'detail_en' => 'Layouts & hospitality',
        ],
        [
            'icon' => 'fas fa-camera',
            'label_id' => 'Produksi',
            'label_en' => 'Production',
            'detail_id' => 'Visual & dokumentasi',
            'detail_en' => 'Visuals & documentation',
        ],
    ];
}

function website_setting($key = null)
{
    static $setting = null;

    if ($setting === null) {

        $model = new WebsiteSetting();

        $setting = $model->first();
    }

    if ($key === null) {
        return $setting;
    }

    return $setting[$key] ?? null;
}

function baseUrl()
{
    $config = require __DIR__ . '/../../config/app.php';
    $configuredUrl = rtrim($config['app_url'] ?? '', '/');
    $host = strtolower(preg_replace('/:\d+$/', '', $_SERVER['HTTP_HOST'] ?? ''));

    if ($host !== '' && in_array($host, $config['allowed_hosts'] ?? [], true)) {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            ? 'https'
            : 'http';

        $path = $config['host_paths'][$host] ?? '';

        return $protocol . '://' . ($_SERVER['HTTP_HOST'] ?? $host) . $path;
    }

    return $configuredUrl;
}

function fullUrl($page, $params = [])
{
    return url($page, $params);
}

function currentPage()
{
    return $_GET['page'] ?? 'dashboard';
}

function isCurrentPage($page)
{
    $current = $_GET['page'] ?? 'home';

    return $current === $page ? 'current' : '';
}

function isActiveMenu($pages = [])
{
    return in_array(currentPage(), (array) $pages, true) ? 'active' : '';
}

function isOpenMenu($pages = [])
{
    return in_array(currentPage(), (array) $pages, true) ? 'open' : '';
}

function user_role()
{
    return $_SESSION['user_role'] ?? null;
}

function role_name()
{
    return $_SESSION['role_name'] ?? null;
}

function is_super_admin()
{
    return user_role() === 'super_admin';
}

function isClientPortalUser()
{
    return strtolower((string) role_name()) === 'client' && can('client_portal.view');
}

function canOpenClientPortal()
{
    return !empty($_SESSION['user_id']) && can('client_portal.view');
}

function isPublicMember()
{
    return !empty($_SESSION['user_id']) && strtolower((string) role_name()) === 'member';
}

function requirePublicMemberLogin()
{
    if (!isPublicMember()) {
        $_SESSION['member_error'] = t('Silakan masuk sebagai member untuk melanjutkan.', 'Please sign in as a member to continue.');
        header('Location: ' . frontUrl('member-login'));
        exit;
    }
}

function requireClientPortalLogin()
{
    if (empty($_SESSION['user_id'])) {
        $_SESSION['member_error'] = 'Silakan masuk untuk mengakses portal client.';
        header('Location: ' . frontUrl('member-login'));
        exit;
    }

    if (!canOpenClientPortal()) {
        $_SESSION['member_error'] = 'Akun Anda tidak memiliki akses Client Portal.';
        header('Location: ' . frontUrl('member-login'));
        exit;
    }
}

function can_access($roles = [])
{
    return in_array(user_role(), (array) $roles, true);
}

function can($permissionKey)
{
    if (is_super_admin()) {
        return true;
    }

    if (empty($_SESSION['permissions']) || !is_array($_SESSION['permissions'])) {
        return false;
    }

    return in_array($permissionKey, $_SESSION['permissions'], true);
}

function cannot($permissionKey)
{
    return !can($permissionKey);
}

function canAny(array $permissions)
{
    if (is_super_admin()) {
        return true;
    }

    foreach ($permissions as $permission) {
        if (can($permission)) {
            return true;
        }
    }

    return false;
}

function currentLang()
{
    $lang = $_SESSION['lang'] ?? 'id';

    return in_array($lang, ['id', 'en'], true) ? $lang : 'id';
}

function current_lang()
{
    return currentLang();
}

function sanitizeRichHtml($html)
{
    $html = (string) $html;
    $allowedTags = ['p', 'br', 'strong', 'b', 'em', 'i', 'u', 's', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'ul', 'ol', 'li', 'blockquote', 'a', 'img'];

    if (!class_exists('DOMDocument')) {
        $html = preg_replace('#<(script|iframe|object|embed|style|svg|form)\b[^>]*>.*?</\1>#is', '', $html);
        $html = preg_replace('/\son\w+\s*=\s*([\'"]).*?\1/is', '', $html);
        $html = preg_replace('/\s(href|src)\s*=\s*([\'"])\s*(javascript|data):.*?\2/is', '', $html);
        return strip_tags($html, '<' . implode('><', $allowedTags) . '>');
    }

    $document = new DOMDocument('1.0', 'UTF-8');
    libxml_use_internal_errors(true);
    $document->loadHTML(
        '<?xml encoding="UTF-8"><div id="rich-content-root">' . $html . '</div>',
        LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
    );
    libxml_clear_errors();

    $root = $document->getElementById('rich-content-root');

    if (!$root) {
        return '';
    }

    sanitizeRichNode($root, $allowedTags);

    $safeHtml = '';
    foreach ($root->childNodes as $child) {
        $safeHtml .= $document->saveHTML($child);
    }

    return $safeHtml;
}

function safeLinkUrl($url, $fallback = '#')
{
    $url = trim((string) $url);

    if ($url !== '' && preg_match('~^(https?://|mailto:|tel:|/|#)~i', $url)) {
        return $url;
    }

    return $fallback;
}

function qrCodeCheckInSvg($value, $displaySize = 440)
{
    require_once __DIR__ . '/Vendor/TcpdfQrCode.php';
    $barcode = (new QRcode((string) $value, 'H'))->getBarcodeArray();
    $border = 4;
    $side = (int) $barcode['num_cols'] + ($border * 2);
    $path = '';

    foreach ($barcode['bcode'] as $y => $row) {
        foreach ($row as $x => $module) {
            if ($module) {
                $path .= 'M' . ($x + $border) . ',' . ($y + $border) . 'h1v1h-1z';
            }
        }
    }

    $size = max(180, (int) $displaySize);
    return '<svg class="event-qr-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ' . $side . ' ' . $side . '" width="' . $size . '" height="' . $size . '" shape-rendering="crispEdges" role="img" aria-label="QR Code check-in peserta"><rect width="100%" height="100%" fill="#fff"/><path d="' . $path . '" fill="#000"/></svg>';
}

function consumeRateLimit($key, $maximumAttempts, $windowSeconds)
{
    $now = time();
    $attempts = $_SESSION['_rate_limits'][$key] ?? [];
    $attempts = array_values(array_filter($attempts, function ($attempt) use ($now, $windowSeconds) {
        return (int) $attempt > $now - $windowSeconds;
    }));

    if (count($attempts) >= $maximumAttempts) {
        $_SESSION['_rate_limits'][$key] = $attempts;
        return false;
    }

    $attempts[] = $now;
    $_SESSION['_rate_limits'][$key] = $attempts;
    return true;
}

function clearRateLimit($key)
{
    unset($_SESSION['_rate_limits'][$key]);
}

function sanitizeRichNode($parent, array $allowedTags)
{
    $blockedTags = ['script', 'iframe', 'object', 'embed', 'style', 'svg', 'math', 'form', 'input', 'button', 'link', 'meta'];

    foreach (iterator_to_array($parent->childNodes) as $node) {
        if ($node->nodeType !== XML_ELEMENT_NODE) {
            continue;
        }

        $tag = strtolower($node->nodeName);

        if (in_array($tag, $blockedTags, true)) {
            $parent->removeChild($node);
            continue;
        }

        if (!in_array($tag, $allowedTags, true)) {
            while ($node->firstChild) {
                $parent->insertBefore($node->firstChild, $node);
            }
            $parent->removeChild($node);
            sanitizeRichNode($parent, $allowedTags);
            continue;
        }

        $attributes = [];
        foreach ($node->attributes as $attribute) {
            $attributes[] = $attribute->nodeName;
        }

        foreach ($attributes as $attribute) {
            $allowedAttribute = ($tag === 'a' && in_array($attribute, ['href', 'title', 'target'], true))
                || ($tag === 'img' && in_array($attribute, ['src', 'alt', 'title'], true));

            if (!$allowedAttribute) {
                $node->removeAttribute($attribute);
            }
        }

        foreach (['href', 'src'] as $urlAttribute) {
            if (!$node->hasAttribute($urlAttribute)) {
                continue;
            }

            $value = trim($node->getAttribute($urlAttribute));
            if (!preg_match('~^(https?://|mailto:|/|#)~i', $value)) {
                $node->removeAttribute($urlAttribute);
            }
        }

        if ($tag === 'a' && strtolower($node->getAttribute('target')) === '_blank') {
            $node->setAttribute('rel', 'noopener noreferrer');
        }

        sanitizeRichNode($node, $allowedTags);
    }
}

function isEnglish()
{
    return currentLang() === 'en';
}

function t($id, $en)
{
    $id = (string) $id;
    $en = (string) $en;

    if (isEnglish()) {
        return trim($en) !== '' ? $en : $id;
    }

    return trim($id) !== '' ? $id : $en;
}

function sentenceCaseText($text)
{
    $text = trim((string) $text);

    if ($text === '') {
        return $text;
    }

    $lower = function_exists('mb_strtolower')
        ? mb_strtolower($text, 'UTF-8')
        : strtolower($text);

    $first = function_exists('mb_substr')
        ? mb_strtoupper(mb_substr($lower, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($lower, 1, null, 'UTF-8')
        : ucfirst($lower);

    $preserve = [
        'iventlo' => 'Iventlo',
        'whatsapp' => 'WhatsApp',
        'qr code' => 'QR Code',
        'q&a' => 'Q&A',
        'faq' => 'FAQ',
        'cms' => 'CMS',
    ];

    return str_ireplace(array_keys($preserve), array_values($preserve), $first);
}

function requirePermission($permissionKey)
{
    if (empty($_SESSION['user_id'])) {
        header('Location: ' . url('login'));
        exit;
    }

    if (!can($permissionKey)) {
        $_SESSION['error'] = 'Anda tidak memiliki akses ke halaman tersebut.';
        header('Location: ' . url('dashboard'));
        exit;
    }
}

function csrfToken()
{
    if (empty($_SESSION['_csrf_token'])) {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['_csrf_token'];
}

function csrfField()
{
    return '<input type="hidden" name="_csrf" value="' .
        htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8') . '">';
}

function protectPostForms($html)
{
    return preg_replace_callback('/<form\b[^>]*>/i', function ($match) {
        if (!preg_match('/\bmethod\s*=\s*([\'"]?)post\1/i', $match[0])) {
            return $match[0];
        }

        return $match[0] . csrfField();
    }, $html);
}

function verifyCsrfRequest()
{
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
        return;
    }

    $route = trim((string) ($_GET['route'] ?? $_GET['page'] ?? ''), '/');
    if (str_starts_with($route, 'api/mobile')) {
        return;
    }

    $submitted = $_POST['_csrf'] ?? '';
    $expected = $_SESSION['_csrf_token'] ?? '';

    if ($expected === '' || $submitted === '' || !hash_equals($expected, $submitted)) {
        http_response_code(403);
        echo 'Permintaan tidak valid atau sesi telah berakhir. Silakan muat ulang halaman.';
        exit;
    }
}

function requirePost()
{
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
        http_response_code(405);
        header('Allow: POST');
        echo 'Metode request tidak diizinkan.';
        exit;
    }
}

function validatedImageExtension($file)
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK ||
        empty($file['tmp_name']) ||
        !is_uploaded_file($file['tmp_name'])) {
        return null;
    }

    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $types = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif'
    ];

    return $types[$mime] ?? null;
}

function uploadWebsiteImageAsWebp($file, $subDirectory, $prefix, $quality = 82)
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK ||
        empty($file['tmp_name']) ||
        !is_uploaded_file($file['tmp_name'])) {
        return null;
    }

    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $loaders = [
        'image/jpeg' => 'imagecreatefromjpeg',
        'image/png' => 'imagecreatefrompng',
        'image/webp' => 'imagecreatefromwebp',
        'image/gif' => 'imagecreatefromgif',
    ];

    if (empty($loaders[$mime]) || !function_exists($loaders[$mime]) || !function_exists('imagewebp')) {
        return null;
    }

    $source = @$loaders[$mime]($file['tmp_name']);

    if (!$source) {
        return null;
    }

    if (!imageistruecolor($source)) {
        imagepalettetotruecolor($source);
    }

    imagealphablending($source, true);
    imagesavealpha($source, true);

    $relativeDirectory = 'website/' . trim($subDirectory, '/') . '/';
    $uploadDir = dirname(__DIR__, 2) . '/public/uploads/' . $relativeDirectory;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $filename = $prefix . '-' . time() . '-' . rand(1000, 9999) . '.webp';
    $target = $uploadDir . $filename;
    $saved = imagewebp($source, $target, max(1, min(100, (int) $quality)));

    imagedestroy($source);

    if (!$saved) {
        return null;
    }

    return $relativeDirectory . $filename;
}

function validatedDocumentUpload($file)
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK ||
        empty($file['tmp_name']) ||
        !is_uploaded_file($file['tmp_name'])) {
        return null;
    }

    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $types = [
        'application/pdf' => 'pdf',
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp'
    ];

    if (!isset($types[$mime])) {
        return null;
    }

    return ['extension' => $types[$mime], 'mime' => $mime];
}

function activity_log($module, $action, $description, $referenceId = null, $referenceNumber = null)
{
    if (empty($_SESSION['user_id'])) {
        return;
    }

    if (!class_exists('ActivityLog')) {
        return;
    }

    $model = new ActivityLog();

    $model->create([
        'user_id' => $_SESSION['user_id'],
        'module' => $module,
        'action' => $action,
        'reference_id' => $referenceId,
        'reference_number' => $referenceNumber,
        'description' => $description
    ]);
}
