<?php
header('X-Robots-Tag: noindex, nofollow, noarchive', true);
$companyName = website_setting('company_name') ?: 'Iventlo';
$logo = website_setting('logo_white') ?: website_setting('logo');
$logoSrc = $logo ? uploadAsset($logo) : frontAsset('images/logo.svg');
$coverSrc = uploadAsset('website/sliders/slider-1779775682-8712.jpg');
?>
<!DOCTYPE html>
<html lang="<?= isEnglish() ? 'en' : 'id' ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow, noarchive">
    <title><?= htmlspecialchars($title ?? 'Iventlo Member') ?></title>
    <link href="<?= frontAsset('css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= frontAsset('css/fontawesome.css') ?>" rel="stylesheet">
    <link href="<?= frontAsset('css/fonts.css') ?>" rel="stylesheet">
    <link href="<?= frontAsset('css/member-portal.css?v=20260527-1') ?>" rel="stylesheet">
</head>
<body class="member-page">
<main class="member-auth-shell">
    <aside class="member-auth-cover" style="background-image:url('<?= htmlspecialchars($coverSrc) ?>')">
        <a class="member-logo" href="<?= frontUrl('home') ?>"><img src="<?= $logoSrc ?>" alt="<?= htmlspecialchars($companyName) ?>"></a>
        <div class="member-auth-copy">
            <h1><?= t('Selamat Datang<br>di <span>Event Anda</span>', 'Welcome to<br><span>Your Event</span>') ?></h1>
            <p><?= t('Akses event, tiket digital, persetujuan, dan konfirmasi kehadiran dalam satu portal.', 'Access events, digital tickets, approvals, and attendance confirmation in one portal.') ?></p>
            <div class="member-feature-grid">
                <div class="member-feature-item"><i class="fa fa-ticket-alt"></i><?= t('E-tiket', 'E-ticket') ?></div>
                <div class="member-feature-item"><i class="fa fa-calendar-alt"></i><?= t('Event', 'Events') ?></div>
                <div class="member-feature-item"><i class="fa fa-file-invoice"></i><?= t('Bayar', 'Payment') ?></div>
                <div class="member-feature-item"><i class="fa fa-check-circle"></i><?= t('Check-in', 'Check-in') ?></div>
            </div>
        </div>
    </aside>
    <section class="member-auth-panel">
