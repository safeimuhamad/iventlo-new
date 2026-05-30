<?php
header('X-Robots-Tag: noindex, nofollow, noarchive', true);
$companyName = website_setting('company_name') ?: 'Iventlo';
$logo = website_setting('logo_white') ?: website_setting('logo');
$logoSrc = $logo ? uploadAsset($logo) : frontAsset('images/logo.svg');
$portalSection = $portalSection ?? 'events';
$clientPortal = isClientPortalUser();
$contentIcons = ['agenda'=>'calendar-alt','speaker'=>'user','material'=>'file-alt','certificate'=>'award','gallery'=>'image','qna'=>'comments','polling'=>'chart-bar','information'=>'info-circle'];
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
    <link href="<?= frontAsset('css/member-portal.css?v=20260527-3') ?>" rel="stylesheet">
</head>
<body class="member-page">
<div class="member-portal-shell">
    <aside class="member-sidebar<?= $clientPortal ? ' client-sidebar' : '' ?>">
        <a class="member-logo" href="<?= $clientPortal ? url('client/dashboard') : frontUrl('events') ?>"><img src="<?= $logoSrc ?>" alt="<?= htmlspecialchars($companyName) ?>"></a>
        <ul class="member-menu">
            <?php if ($clientPortal): ?>
            <li class="<?= $portalSection === 'client-dashboard' ? 'active' : '' ?>"><a href="<?= url('client/dashboard') ?>"><i class="fa fa-home"></i><span>Dashboard</span></a></li>
	            <li class="<?= $portalSection === 'client-events' ? 'active' : '' ?>"><a href="<?= url('client/events') ?>"><i class="fa fa-calendar-alt"></i><span>Event saya</span></a></li>
            <?php if (!empty($clientMenuEvent)): ?>
	            <li class="<?= $portalSection === 'client-summary' ? 'active' : '' ?>"><a href="<?= url('client/events/' . $clientMenuEvent['id']) ?>"><i class="fa fa-tasks"></i><span>Ringkasan event</span></a></li>
            <?php if (($clientMenuEvent['access_level'] ?? '') === 'admin' && !empty($clientMenuEvent['is_paid'])): ?>
	            <li class="<?= $portalSection === 'client-attendees' ? 'active' : '' ?>"><a href="<?= url('client/events/' . $clientMenuEvent['id'] . '/peserta') ?>"><i class="fa fa-qrcode"></i><span>Peserta & check-in</span></a></li>
            <?php endif; ?>
            <li class="<?= $portalSection === 'client-timeline' ? 'active' : '' ?>"><a href="<?= url('client/events/' . $clientMenuEvent['id'] . '/timeline') ?>"><i class="fa fa-stream"></i><span>Timeline</span></a></li>
            <li class="<?= $portalSection === 'client-documents' ? 'active' : '' ?>"><a href="<?= url('client/events/' . $clientMenuEvent['id'] . '/documents') ?>"><i class="fa fa-file-alt"></i><span>Dokumen</span></a></li>
            <li class="<?= $portalSection === 'client-approvals' ? 'active' : '' ?>"><a href="<?= url('client/events/' . $clientMenuEvent['id'] . '/approvals') ?>"><i class="fa fa-clipboard-check"></i><span>Approval</span></a></li>
                <?php foreach (EventPortalContent::labels() as $key => $label): ?>
                    <li class="<?= ($portalSection === 'client-content-' . $key) ? 'active' : '' ?>"><a href="<?= url('client/events/' . $clientMenuEvent['id'] . '/konten', ['jenis' => $key]) ?>"><i class="fa fa-<?= $contentIcons[$key] ?>"></i><span><?= htmlspecialchars($label) ?></span></a></li>
                <?php endforeach; ?>
            <?php endif; ?>
            <li class="<?= $portalSection === 'client-notifications' ? 'active' : '' ?>"><a href="<?= url('client/notifications') ?>"><i class="fa fa-bell"></i><span>Notifikasi</span></a></li>
            <?php else: ?>
            <li class="<?= $portalSection === 'events' ? 'active' : '' ?>"><a href="<?= frontUrl('events') ?>"><i class="fa fa-calendar-alt"></i><span><?= t('Event', 'Events') ?></span></a></li>
	            <li class="<?= $portalSection === 'tickets' ? 'active' : '' ?>"><a href="<?= frontUrl('member-dashboard') ?>"><i class="fa fa-ticket-alt"></i><span><?= t('Tiket saya', 'My tickets') ?></span></a></li>
            <?php if (!empty($portalOrderNumber)): ?>
                <?php foreach (EventPortalContent::labels() as $key => $label): ?>
                    <li class="<?= ($portalSection === 'content-' . $key) ? 'active' : '' ?>"><a href="<?= frontUrl('member-event-content', ['slug' => $portalOrderNumber, 'section' => $key]) ?>"><i class="fa fa-<?= $contentIcons[$key] ?>"></i><span><?= htmlspecialchars($label) ?></span></a></li>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php endif; ?>
	            <li><a href="<?= frontUrl('home') ?>"><i class="fa fa-globe"></i><span><?= t('Website publik', 'Public website') ?></span></a></li>
        </ul>
        <div class="member-sidebar-bottom">
            <div class="member-help">
	                <strong><?= t('Butuh bantuan?', 'Need help?') ?></strong>
                <p><?= t('Informasi bantuan tersedia melalui website publik Iventlo.', 'Support information is available on the Iventlo public website.') ?></p>
            </div>
            <form class="member-logout" method="POST" action="<?= frontUrl('member-logout') ?>">
	                <button type="submit"><i class="fa fa-sign-out-alt"></i><span><?= t('Keluar', 'Sign out') ?></span></button>
            </form>
        </div>
    </aside>
    <div class="member-portal-body">
        <header class="member-topbar">
            <div class="member-user-chip">
                <span class="member-user-avatar"><i class="fa fa-user"></i></span>
                <span><?= htmlspecialchars($_SESSION['name'] ?? '') ?><small><?= $clientPortal ? 'Client' : t('Peserta', 'Participant') ?></small></span>
            </div>
        </header>
        <main class="member-content">
            <?php if (!empty($_SESSION['error'])): ?><div class="member-alert error"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div><?php endif; ?>
            <?php if (!empty($_SESSION['success'])): ?><div class="member-alert success"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div><?php endif; ?>
