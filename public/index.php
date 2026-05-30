<?php

$debug = filter_var(getenv('APP_DEBUG') ?: false, FILTER_VALIDATE_BOOLEAN);
error_reporting(E_ALL);
ini_set('display_errors', $debug ? '1' : '0');

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
header_remove('X-Powered-By');

$requestHost = strtolower(preg_replace('/:\d+$/', '', $_SERVER['HTTP_HOST'] ?? ''));
$isHttps = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';

if ($isHttps && $requestHost !== 'localhost') {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
}

date_default_timezone_set('Asia/Jakarta');

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'httponly' => true,
        'secure' => $isHttps,
        'samesite' => 'Lax'
    ]);
    session_start();
}

spl_autoload_register(function ($class) {

    $paths = [
        __DIR__ . '/../app/Core/' . $class . '.php',
        __DIR__ . '/../app/Controllers/' . $class . '.php',
        __DIR__ . '/../app/Models/' . $class . '.php',
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

require_once __DIR__ . '/../app/Core/Helper.php';

/*
|--------------------------------------------------------------------------
| CLEAN URL PARSER
|--------------------------------------------------------------------------
*/

$route = trim((string) ($_GET['route'] ?? ''), '/');
$legacyFrontRedirects = [
    'paket' => ['page' => 'products', 'lang' => 'id'],
    'en/packages' => ['page' => 'products', 'lang' => 'en'],
];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($legacyFrontRedirects[$route])) {
    $legacyRedirect = $legacyFrontRedirects[$route];
    $params = $_GET;
    unset($params['page'], $params['route'], $params['lang']);
    header('Location: ' . frontUrl($legacyRedirect['page'], $params, $legacyRedirect['lang']), true, 301);
    exit;
}

$isFrontRoot = $route === '' && empty($_GET['page']);
$matchedFrontRoute = ($route !== '' || $isFrontRoot) ? matchFrontRoute($route) : null;

if ($matchedFrontRoute || $isFrontRoot) {
    $matchedFrontRoute = $matchedFrontRoute ?: ['page' => 'home', 'lang' => 'id'];
    $_GET['page'] = $matchedFrontRoute['page'];
    $_SESSION['lang'] = $matchedFrontRoute['lang'];

    if (!empty($matchedFrontRoute['slug'])) {
        $_GET['slug'] = $matchedFrontRoute['slug'];
    }
} elseif (!empty($route)) {
    $_GET['page'] = $route;
}

$queryLang = $_GET['lang'] ?? null;
$validQueryLang = in_array($queryLang, ['id', 'en'], true);

if (!$matchedFrontRoute && !$isFrontRoot && $validQueryLang) {
    $_SESSION['lang'] = $_GET['lang'];
}

$legacyFrontPages = [
    'home',
    'about',
    'services',
    'service-detail',
    'products',
    'portfolio',
    'portfolio-detail',
    'blog',
    'blog-detail',
    'contact',
];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $isFrontRoot && $validQueryLang) {
    $params = $_GET;
    unset($params['page'], $params['route'], $params['lang']);
    header('Location: ' . frontUrl('home', $params, $queryLang), true, 301);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $matchedFrontRoute && $validQueryLang) {
    $params = $_GET;
    unset($params['page'], $params['route'], $params['lang'], $params['slug']);
    header('Location: ' . frontUrl(
        $matchedFrontRoute['page'],
        array_merge($params, isset($matchedFrontRoute['slug']) ? ['slug' => $matchedFrontRoute['slug']] : []),
        $matchedFrontRoute['lang']
    ), true, 301);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET'
    && !$matchedFrontRoute
    && !$isFrontRoot
    && in_array($_GET['page'] ?? '', $legacyFrontPages, true)
) {
    $params = $_GET;
    unset($params['page'], $params['route'], $params['lang']);
    header('Location: ' . frontUrl($_GET['page'], $params), true, 301);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET'
    && $route === ''
    && !$matchedFrontRoute
    && !$isFrontRoot
    && !empty($_GET['page'])
    && preg_match('/^[a-z0-9-]+$/i', (string) $_GET['page'])
) {
    $params = $_GET;
    $legacyPage = (string) $params['page'];
    unset($params['page'], $params['route']);
    header('Location: ' . url($legacyPage, $params), true, 301);
    exit;
}

verifyCsrfRequest();

require_once __DIR__ . '/../routes/web.php';
