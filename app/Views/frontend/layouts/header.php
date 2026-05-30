<?php
$companyName = website_setting('company_name') ?: 'Iventlo';
$tagline = t(
    website_setting('tagline') ?: 'Partner event organizer untuk acara yang terencana dengan baik.',
    'Your event organizer partner for thoughtfully planned occasions.'
);

$siteLogo = website_setting('logo');
$siteLogoWhite = website_setting('logo_white');
$siteFavicon = website_setting('favicon');

$sitePhone = website_setting('phone') ?: '+62 812-3456-7890';
$siteWhatsapp = website_setting('whatsapp') ?: $sitePhone;
$siteEmail = website_setting('email') ?: 'hello@iventlo.com';
$siteAddress = website_setting('address') ?: 'Indonesia';

$siteInstagram = website_setting('instagram');
$siteFacebook = website_setting('facebook');
$siteLinkedin = website_setting('linkedin');
$siteYoutube = website_setting('youtube');
$siteTiktok = website_setting('tiktok');

$logoSrc = !empty($siteLogo) ? uploadAsset($siteLogo) : frontAsset('images/logo.svg');
$logoWhiteSrc = !empty($siteLogoWhite) ? uploadAsset($siteLogoWhite) : frontAsset('images/logo.svg');
$faviconSrc = !empty($siteFavicon) ? uploadAsset($siteFavicon) : frontAsset('images/favicon.png');

$phoneClean = preg_replace('/[^0-9+]/', '', $sitePhone);

$currentPage = $_GET['page'] ?? 'home';
$currentSlug = $_GET['slug'] ?? null;
$languageParams = [];

if (!empty($currentSlug)) {
    $languageParams['slug'] = $currentSlug;
}

foreach (['q', 'category'] as $queryParameter) {
    if (!empty($_GET[$queryParameter])) {
        $languageParams[$queryParameter] = $_GET[$queryParameter];
    }
}

if ($currentPage === 'portfolio-detail' && !empty($portfolio)) {
    $languageParamsId = array_merge($languageParams, ['slug' => $portfolio['slug_id'] ?? $currentSlug]);
    $languageParamsEn = array_merge($languageParams, ['slug' => $portfolio['slug_en'] ?? $currentSlug]);
} elseif ($currentPage === 'blog-detail' && !empty($post)) {
    $languageParamsId = array_merge($languageParams, ['slug' => $post['slug_id'] ?? $currentSlug]);
    $languageParamsEn = array_merge($languageParams, ['slug' => $post['slug_en'] ?? $currentSlug]);
} elseif (in_array($currentPage, ['event-detail', 'event-purchase'], true) && !empty($event)) {
    $languageParamsId = array_merge($languageParams, ['slug' => $event['public_slug'] ?? $currentSlug]);
    $languageParamsEn = array_merge($languageParams, ['slug' => $event['public_slug_en'] ?? $event['public_slug'] ?? $currentSlug]);
} else {
    $languageParamsId = $languageParams;
    $languageParamsEn = $languageParams;
}

$indonesianUrl = frontUrl($currentPage, $languageParamsId, 'id');
$englishUrl = frontUrl($currentPage, $languageParamsEn, 'en');
$canonicalUrl = isEnglish() ? $englishUrl : $indonesianUrl;
$isPortalSignedIn = !empty($_SESSION['user_id']) && in_array(strtolower((string) role_name()), ['member', 'client'], true);
$portalDisplayName = trim((string) ($_SESSION['name'] ?? ''));
$portalDashboardUrl = strtolower((string) role_name()) === 'client' ? url('client/dashboard') : frontUrl('member-dashboard');
$defaultOgImage = frontAsset('images/resource/faq1.webp');
$pageDescription = $meta_description ?? t(
    website_setting('meta_description') ?: 'Iventlo Event Organizer profesional untuk corporate event, wedding, gathering, seminar, launching, dan creative event.',
    'Iventlo Event Organizer provides professional planning for corporate events, weddings, gatherings, seminars, launches, and creative events.'
);
$pageTitle = $title ?? website_setting('meta_title') ?? $companyName;
$pageOgImage = $og_image ?? $defaultOgImage;
$jsonLd = [
    [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => website_setting('company_name') ?: 'Iventlo',
        'url' => frontUrl('home', [], currentLang()),
        'logo' => $logoSrc,
        'email' => $siteEmail,
        'telephone' => $sitePhone,
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => trim((string) $siteAddress),
        ],
        'sameAs' => array_values(array_filter([
            !empty($siteInstagram) ? safeLinkUrl($siteInstagram) : null,
            !empty($siteFacebook) ? safeLinkUrl($siteFacebook) : null,
            !empty($siteLinkedin) ? safeLinkUrl($siteLinkedin) : null,
            !empty($siteYoutube) ? safeLinkUrl($siteYoutube) : null,
            !empty($siteTiktok) ? safeLinkUrl($siteTiktok) : null,
        ])),
    ],
    [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => website_setting('company_name') ?: 'Iventlo',
        'url' => frontUrl('home', [], currentLang()),
        'inLanguage' => currentLang() === 'en' ? 'en-US' : 'id-ID',
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => frontUrl('blog', ['q' => '{search_term_string}'], currentLang()),
            'query-input' => 'required name=search_term_string',
        ],
    ],
    [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => currentLang() === 'en' ? 'Home' : 'Beranda',
                'item' => frontUrl('home', [], currentLang()),
            ],
            [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => strip_tags((string) $pageTitle),
                'item' => $canonicalUrl,
            ],
        ],
    ],
];

if ($currentPage === 'blog-detail' && !empty($post)) {
    $jsonLd[] = [
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => t($post['title_id'] ?? '', $post['title_en'] ?? ''),
        'description' => $pageDescription,
        'image' => $pageOgImage,
        'datePublished' => date('c', strtotime($post['published_at'] ?? $post['created_at'] ?? date('Y-m-d'))),
        'dateModified' => date('c', strtotime($post['updated_at'] ?? $post['published_at'] ?? $post['created_at'] ?? date('Y-m-d'))),
        'author' => [
            '@type' => 'Organization',
            'name' => website_setting('company_name') ?: 'Iventlo',
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name' => website_setting('company_name') ?: 'Iventlo',
            'logo' => [
                '@type' => 'ImageObject',
                'url' => $logoSrc,
            ],
        ],
        'mainEntityOfPage' => $canonicalUrl,
    ];
}
?>

<!DOCTYPE html>
<html lang="<?= currentLang() === 'en' ? 'en' : 'id' ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">

    <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
    <meta name="keywords" content="<?= htmlspecialchars($meta_keywords ?? website_setting('meta_keywords') ?? 'event organizer, event planner, corporate event, wedding organizer, seminar, gathering') ?>">
    <meta name="robots" content="<?= htmlspecialchars($meta_robots ?? 'index, follow, max-image-preview:large') ?>">

    <title><?= htmlspecialchars($pageTitle) ?></title>

    <link rel="shortcut icon" href="<?= $faviconSrc ?>" type="image/x-icon">

    <link href="<?= frontAsset('css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= frontAsset('css/master.css') ?>" rel="stylesheet">
    <link href="<?= frontAsset('style.css?v=20260528-3') ?>" rel="stylesheet">
    <link href="<?= frontAsset('css/language-switcher.css?v=20260528-1') ?>" rel="stylesheet">
    <link href="<?= frontAsset('css/color-switcher-design.css') ?>" rel="stylesheet">
    <meta property="og:title" content="<?= htmlspecialchars($og_title ?? $pageTitle) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($og_description ?? $pageDescription) ?>">
    <meta property="og:image" content="<?= htmlspecialchars($pageOgImage) ?>">
    <meta property="og:type" content="<?= $currentPage === 'blog-detail' ? 'article' : 'website' ?>">
    <meta property="og:site_name" content="<?= htmlspecialchars(website_setting('company_name') ?: 'Iventlo') ?>">
    <meta property="og:url" content="<?= htmlspecialchars($canonicalUrl) ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($og_title ?? $pageTitle) ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($og_description ?? $pageDescription) ?>">
    <meta name="twitter:image" content="<?= htmlspecialchars($pageOgImage) ?>">
    <link rel="canonical" href="<?= htmlspecialchars($canonicalUrl) ?>">

    <link rel="alternate" hreflang="id" href="<?= htmlspecialchars($indonesianUrl) ?>">
    <link rel="alternate" hreflang="en" href="<?= htmlspecialchars($englishUrl) ?>">
    <link rel="alternate" hreflang="x-default" href="<?= htmlspecialchars($indonesianUrl) ?>">
    <?php foreach ($jsonLd as $schemaItem): ?>
        <script type="application/ld+json"><?= json_encode($schemaItem, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
    <?php endforeach; ?>
</head>

<body>
<div class="page-wrapper">

    <div class="circle"></div>
    <div class="circle-follow"></div>

    <div class="preloader"></div>

    <!-- Main Header-->
    <header class="main-header header-style-one">
        <div class="auto-container">
            <div class="main-box">

                <div class="logo-box">
                    <div class="logo">
                        <a href="<?= frontUrl('home') ?>">
                            <img src="<?= $logoWhiteSrc ?>"
                                 alt="<?= htmlspecialchars($companyName) ?>"
                                 title="<?= htmlspecialchars($companyName) ?>">
                        </a>
                    </div>
                </div>

                <div class="header-navbar">
                    <div class="nav-outer">
                        <nav class="nav main-menu">
                            <ul class="navigation">
                                <li class="<?= isCurrentPage('home') ?>">
                                    <a href="<?= frontUrl('home') ?>"><?= t('Beranda', 'Home') ?></a>
                                </li>

                                <li class="<?= isCurrentPage('about') ?>">
                                    <a href="<?= frontUrl('about') ?>"><?= t('Tentang kami', 'About us') ?></a>
                                </li>

                                <li class="<?= in_array($currentPage, ['services', 'service-detail'], true) ? 'current' : '' ?>">
                                    <a href="<?= frontUrl('services') ?>"><?= t('Layanan', 'Services') ?></a>
                                </li>

                                <li class="<?= in_array($currentPage, ['events', 'event-detail', 'event-purchase'], true) ? 'current' : '' ?>">
                                    <a href="<?= frontUrl('events') ?>"><?= t('Event', 'Events') ?></a>
                                </li>

                                <li class="<?= in_array($currentPage, ['blog', 'blog-detail'], true) ? 'current' : '' ?>">
                                    <a href="<?= frontUrl('blog') ?>"><?= t('Artikel', 'Blog') ?></a>
                                </li>

                                <li class="<?= isCurrentPage('contact') ?>">
                                    <a href="<?= frontUrl('contact') ?>"><?= t('Kontak', 'Contact') ?></a>
                                </li>
                            </ul>
                        </nav>
                    </div>

                    <div class="outer-box">
                        <div class="language-switcher" aria-label="<?= t('Pilih bahasa', 'Choose language') ?>">
                            <a href="<?= htmlspecialchars($indonesianUrl) ?>" lang="id" class="<?= !isEnglish() ? 'active' : '' ?>">ID</a>
                            <span>/</span>
                            <a href="<?= htmlspecialchars($englishUrl) ?>" lang="en" class="<?= isEnglish() ? 'active' : '' ?>">EN</a>
                        </div>

                        <button type="button" class="ui-btn search-btn" aria-label="<?= t('Cari artikel', 'Search articles') ?>">
                            <i class="icon flaticon-search"></i>
                        </button>

                        <button type="button" class="ui-btn toggle-hidden-bar" aria-label="<?= t('Informasi kontak', 'Contact information') ?>">
                            <i class="icon flaticon-menu-2"></i>
                        </button>

                        <div class="public-member-actions">
                            <?php if ($isPortalSignedIn): ?>
                                <a href="<?= $portalDashboardUrl ?>" class="public-member-name">
                                    <?= htmlspecialchars($portalDisplayName ?: t('Member', 'Member')) ?>
                                </a>
                            <?php else: ?>
                                <a href="<?= frontUrl('member-register') ?>" class="public-member-link public-member-register">
                                    <?= t('Daftar', 'Register') ?>
                                </a>
                                <a href="<?= frontUrl('member-login') ?>" class="public-member-link public-member-login">
                                    <?= t('Masuk', 'Sign in') ?>
                                </a>
                            <?php endif; ?>
                        </div>

                        <div class="mobile-nav-toggler">
                            <i class="fa fa-bars"></i>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="mobile-menu">
            <div class="menu-backdrop"></div>

            <nav class="menu-box">
                <div class="upper-box">
                    <div class="logo-box">
                        <div class="nav-logo light">
                            <a href="<?= frontUrl('home') ?>">
                                <img src="<?= $logoSrc ?>"
                                     alt="<?= htmlspecialchars($companyName) ?>"
                                     title="<?= htmlspecialchars($companyName) ?>">
                            </a>
                        </div>

                        <div class="nav-logo dark">
                            <a href="<?= frontUrl('home') ?>">
                                <img src="<?= $logoWhiteSrc ?>"
                                     alt="<?= htmlspecialchars($companyName) ?>"
                                     title="<?= htmlspecialchars($companyName) ?>">
                            </a>
                        </div>
                    </div>

                    <div class="close-btn">
                        <i class="icon fa fa-times"></i>
                    </div>
                </div>

                <ul class="navigation clearfix">
                    <!-- Keep This Empty / Menu will come through Javascript -->
                </ul>

                <ul class="contact-list-one">
                    <li>
                        <i class="icon lnr-icon-phone-handset"></i>
	                    <span class="title"><?= t('Telepon / WhatsApp', 'Phone / WhatsApp') ?></span>
                        <div class="text">
                            <a href="tel:<?= htmlspecialchars($phoneClean) ?>">
                                <?= htmlspecialchars($sitePhone) ?>
                            </a>
                        </div>
                    </li>

                    <li>
                        <i class="icon lnr-icon-envelope1"></i>
                        <span class="title"><?= t('Email', 'Email') ?></span>
                        <div class="text">
                            <a href="mailto:<?= htmlspecialchars($siteEmail) ?>">
                                <?= htmlspecialchars($siteEmail) ?>
                            </a>
                        </div>
                    </li>

                    <li>
                        <i class="icon lnr-icon-map-marker"></i>
                        <span class="title"><?= t('Alamat', 'Address') ?></span>
                        <div class="text">
                            <?= nl2br(htmlspecialchars($siteAddress)) ?>
                        </div>
                    </li>
                </ul>

                <div class="language-switcher mobile-language-switcher" aria-label="<?= t('Pilih bahasa', 'Choose language') ?>">
                    <a href="<?= htmlspecialchars($indonesianUrl) ?>" lang="id" class="<?= !isEnglish() ? 'active' : '' ?>">Indonesia</a>
                    <span>/</span>
                    <a href="<?= htmlspecialchars($englishUrl) ?>" lang="en" class="<?= isEnglish() ? 'active' : '' ?>">English</a>
                </div>

                <ul class="social-links">
                    <?php if (!empty($siteInstagram)): ?>
                        <li><a href="<?= htmlspecialchars(safeLinkUrl($siteInstagram)) ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a></li>
                    <?php endif; ?>

                    <?php if (!empty($siteFacebook)): ?>
                        <li><a href="<?= htmlspecialchars(safeLinkUrl($siteFacebook)) ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook-f"></i></a></li>
                    <?php endif; ?>

                    <?php if (!empty($siteTiktok)): ?>
                        <li><a href="<?= htmlspecialchars(safeLinkUrl($siteTiktok)) ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-tiktok"></i></a></li>
                    <?php endif; ?>

                    <?php if (!empty($siteYoutube)): ?>
                        <li><a href="<?= htmlspecialchars(safeLinkUrl($siteYoutube)) ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-youtube"></i></a></li>
                    <?php endif; ?>

                    <?php if (!empty($siteLinkedin)): ?>
                        <li><a href="<?= htmlspecialchars(safeLinkUrl($siteLinkedin)) ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-linkedin-in"></i></a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
        <!-- End Mobile Menu -->

        <!-- Header Search -->
        <div class="search-popup">
            <span class="search-back-drop"></span>
            <button class="close-search"><span class="fa fa-times"></span></button>

            <div class="search-inner">
                <form method="get" action="<?= frontUrl('blog') ?>">
                    <div class="form-group">
                        <input type="search" name="q" placeholder="<?= t('Cari artikel...', 'Search articles...') ?>" required>
                        <button type="submit"><i class="fa fa-search"></i></button>
                    </div>
                </form>
            </div>
        </div>
        <!-- End Header Search -->

        <!-- Sticky Header -->
        <div class="sticky-header">
            <div class="auto-container">
                <div class="inner-container">

                    <div class="logo-box">
                        <div class="logo light">
                            <a href="<?= frontUrl('home') ?>" title="<?= htmlspecialchars($companyName) ?>">
                                <img src="<?= $logoSrc ?>" alt="<?= htmlspecialchars($companyName) ?>">
                            </a>
                        </div>

                        <div class="logo dark">
                            <a href="<?= frontUrl('home') ?>" title="<?= htmlspecialchars($companyName) ?>">
                                <img src="<?= $logoWhiteSrc ?>" alt="<?= htmlspecialchars($companyName) ?>">
                            </a>
                        </div>
                    </div>

                    <div class="nav-outer">
                        <nav class="main-menu">
                            <div class="navbar-collapse show collapse clearfix">
                                <ul class="navigation clearfix">
                                    <!-- Keep This Empty / Menu will come through Javascript -->
                                </ul>
                            </div>
                        </nav>

                        <div class="mobile-nav-toggler">
                            <span class="icon lnr-icon-bars"></span>
                        </div>
                    </div>

                    <div class="sticky-header-actions">
                        <div class="language-switcher sticky-language-switcher" aria-label="<?= t('Pilih bahasa', 'Choose language') ?>">
                            <a href="<?= htmlspecialchars($indonesianUrl) ?>" lang="id" class="<?= !isEnglish() ? 'active' : '' ?>">ID</a>
                            <span>/</span>
                            <a href="<?= htmlspecialchars($englishUrl) ?>" lang="en" class="<?= isEnglish() ? 'active' : '' ?>">EN</a>
                        </div>

                        <div class="public-member-actions sticky-member-actions">
                            <?php if ($isPortalSignedIn): ?>
                                <a href="<?= $portalDashboardUrl ?>" class="public-member-name">
                                    <?= htmlspecialchars($portalDisplayName ?: t('Member', 'Member')) ?>
                                </a>
                            <?php else: ?>
                                <a href="<?= frontUrl('member-register') ?>" class="public-member-link public-member-register">
                                    <?= t('Daftar', 'Register') ?>
                                </a>
                                <a href="<?= frontUrl('member-login') ?>" class="public-member-link public-member-login">
                                    <?= t('Masuk', 'Sign in') ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- End Sticky Menu -->
    </header>
    <!-- End Main Header -->

    <div class="hidden-bar-back-drop"></div>

    <section class="hidden-bar">
        <div class="inner-box">
            <div class="upper-box">
                <div class="logo-box">
                    <div class="nav-logo light">
                        <a href="<?= frontUrl('home') ?>">
                            <img src="<?= $logoWhiteSrc ?>" alt="<?= htmlspecialchars($companyName) ?>">
                        </a>
                    </div>

                    <div class="nav-logo dark">
                        <a href="<?= frontUrl('home') ?>">
                            <img src="<?= $logoSrc ?>" alt="<?= htmlspecialchars($companyName) ?>">
                        </a>
                    </div>
                </div>

                <div class="close-btn">
                    <i class="icon fa fa-times"></i>
                </div>
            </div>

            <div class="text-box">
                <h4 class="title">
                    <?= htmlspecialchars($companyName) ?>
                </h4>

                <div class="text">
                    <?= htmlspecialchars($tagline) ?>
                </div>
            </div>

            <ul class="contact-list-one">
                <li>
                    <i class="icon lnr-icon-phone-handset"></i>
	                    <span class="title"><?= t('Telepon / WhatsApp', 'Phone / WhatsApp') ?></span>
                    <div class="text">
                        <a href="tel:<?= htmlspecialchars($phoneClean) ?>">
                            <?= htmlspecialchars($sitePhone) ?>
                        </a>
                    </div>
                </li>

                <li>
                    <i class="icon lnr-icon-envelope1"></i>
                    <span class="title"><?= t('Email', 'Email') ?></span>
                    <div class="text">
                        <a href="mailto:<?= htmlspecialchars($siteEmail) ?>">
                            <?= htmlspecialchars($siteEmail) ?>
                        </a>
                    </div>
                </li>

                <li>
                    <i class="icon lnr-icon-map-marker"></i>
                    <span class="title"><?= t('Alamat', 'Address') ?></span>
                    <div class="text">
                        <?= nl2br(htmlspecialchars($siteAddress)) ?>
                    </div>
                </li>
            </ul>

            <ul class="social-links">
                <?php if (!empty($siteInstagram)): ?>
                    <li><a href="<?= htmlspecialchars(safeLinkUrl($siteInstagram)) ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a></li>
                <?php endif; ?>

                <?php if (!empty($siteFacebook)): ?>
                    <li><a href="<?= htmlspecialchars(safeLinkUrl($siteFacebook)) ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook-f"></i></a></li>
                <?php endif; ?>

                <?php if (!empty($siteTiktok)): ?>
                    <li><a href="<?= htmlspecialchars(safeLinkUrl($siteTiktok)) ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-tiktok"></i></a></li>
                <?php endif; ?>

                <?php if (!empty($siteYoutube)): ?>
                    <li><a href="<?= htmlspecialchars(safeLinkUrl($siteYoutube)) ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-youtube"></i></a></li>
                <?php endif; ?>

                <?php if (!empty($siteLinkedin)): ?>
                    <li><a href="<?= htmlspecialchars(safeLinkUrl($siteLinkedin)) ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-linkedin-in"></i></a></li>
                <?php endif; ?>
            </ul>
        </div>
    </section>
