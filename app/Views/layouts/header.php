<!DOCTYPE html>
<html lang="id">
<?php
    header('X-Robots-Tag: noindex, nofollow, noarchive', true);
    $siteLogo = website_setting('logo');
    $siteLogoWhite = website_setting('logo_white');
    $siteFavicon = website_setting('favicon');
    $logoSrc = !empty($siteLogo) ? uploadAsset($siteLogo) : frontAsset('images/logo.svg');
    $logoWhiteSrc = !empty($siteLogoWhite) ? uploadAsset($siteLogoWhite) : frontAsset('images/logo-white.png');
    $faviconSrc = !empty($siteFavicon) ? uploadAsset($siteFavicon) : frontAsset('images/favicon.png');
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="noindex, nofollow, noarchive">
    <title><?= $title ?? 'Rental AC System'; ?></title>
    <link rel="stylesheet" href="<?= asset('css/sidebar-menu.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/simplebar.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/prism.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/quill.snow.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/remixicon.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/swiper-bundle.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/jsvectormap.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>?v=20260526-2">
    <link rel="stylesheet" href="<?= asset('css/tom-select.css') ?>?v=20260526-2">
    <link rel="icon" type="image/png" href="<?= $faviconSrc ?>">
</head>
<body class="bg-body-bg">
<div class="preloader" id="preloader">
    <div class="preloader">
        <div class="waviy position-relative">
        </div>
    </div>
</div>
