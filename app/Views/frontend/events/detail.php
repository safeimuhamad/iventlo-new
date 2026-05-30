<?php
$titleText = t($event['title'], $event['title_en'] ?: $event['title']);
$venueText = t($event['venue'], $event['venue_en'] ?: $event['venue']);
$descriptionText = t($event['description'], $event['description_en'] ?: $event['description']);
$slug = isEnglish() ? ($event['public_slug_en'] ?: $event['public_slug']) : $event['public_slug'];
$available = max(0, (int) $event['participant_quota'] - (int) $event['reserved_tickets']);
$cover = !empty($event['cover_image']) ? uploadAsset($event['cover_image']) : frontAsset('images/resource/event-single-1.jpg');
?>
<?php if (isPublicMember()): ?>
<?php $portalSection = 'events'; require_once __DIR__ . '/../member/layouts/portal-header.php'; ?>
	<a href="<?= frontUrl('events') ?>" class="member-back">&larr; <?= t('Kembali ke event', 'Back to events') ?></a>
<div class="member-detail-grid member-event-detail">
    <figure class="member-event-cover"><img src="<?= $cover ?>" alt="<?= htmlspecialchars($titleText) ?>"></figure>
    <section class="member-card">
	        <span class="member-label"><?= t('Event berbayar Iventlo', 'Iventlo ticketed event') ?></span>
        <h1><?= htmlspecialchars($titleText) ?></h1>
        <p class="member-muted"><i class="fa fa-calendar-alt"></i> <?= $event['event_date'] ? date('d M Y', strtotime($event['event_date'])) : t('Tanggal akan diumumkan', 'Date to be announced') ?></p>
        <p class="member-muted"><i class="fa fa-map-marker-alt"></i> <?= htmlspecialchars($venueText ?: '-') ?></p>
        <p><?= nl2br(htmlspecialchars($descriptionText)) ?></p>
        <h3 class="member-price">Rp <?= number_format((float) $event['ticket_price'], 0, ',', '.') ?></h3>
        <p class="member-muted"><?= $available ?> / <?= (int) $event['participant_quota'] ?> <?= t('tiket masih dapat dipesan', 'tickets available for reservation') ?></p>
        <?php if ($available > 0 && $event['ticket_sales_status'] === 'open'): ?>
	            <a href="<?= frontUrl('event-purchase', ['slug' => $slug]) ?>" class="member-action"><?= t('Pesan tiket sekarang', 'Reserve tickets now') ?></a>
        <?php else: ?>
	            <span class="member-disabled-action"><?= t('Tiket habis', 'Sold out') ?></span>
        <?php endif; ?>
    </section>
</div>
<?php require_once __DIR__ . '/../member/layouts/portal-footer.php'; ?>
<?php else: ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<section class="page-title" style="background-image: url('<?= frontAsset('images/background/8.jpg') ?>');">
    <div class="auto-container"><div class="inner-container"><h1 class="title"><?= htmlspecialchars($titleText) ?></h1></div></div>
</section>

<section class="about-section pt-5 pb-5">
    <div class="auto-container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4">
                <figure class="image"><img src="<?= $cover ?>" alt="<?= htmlspecialchars($titleText) ?>" style="border-radius:24px;width:100%;max-height:560px;object-fit:cover;"></figure>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="sec-title">
	                    <span class="sub-title"><?= t('Event berbayar Iventlo', 'Iventlo ticketed event') ?></span>
	                    <h2><?= htmlspecialchars(sentenceCaseText($titleText)) ?></h2>
                </div>
                <p><i class="fa fa-calendar me-2"></i><?= $event['event_date'] ? date('d M Y', strtotime($event['event_date'])) : t('Tanggal akan diumumkan', 'Date to be announced') ?></p>
                <p><i class="fa fa-map-marker-alt me-2"></i><?= htmlspecialchars($venueText ?: '-') ?></p>
                <p><?= nl2br(htmlspecialchars($descriptionText)) ?></p>
                <h3 class="mt-4 mb-3">Rp <?= number_format((float) $event['ticket_price'], 0, ',', '.') ?></h3>
                <p><?= $available ?> / <?= (int) $event['participant_quota'] ?> <?= t('tiket masih dapat dipesan', 'tickets available for reservation') ?></p>
                <?php if ($available > 0 && $event['ticket_sales_status'] === 'open'): ?>
                    <?php if (isPublicMember()): ?>
	                        <a href="<?= frontUrl('event-purchase', ['slug' => $slug]) ?>" class="theme-btn btn-style-one bg-yellow"><span class="btn-title"><?= t('Pesan tiket sekarang', 'Reserve tickets now') ?></span></a>
                    <?php else: ?>
	                        <a href="<?= frontUrl('member-login') ?>" class="theme-btn btn-style-one bg-yellow"><span class="btn-title"><?= t('Masuk untuk beli tiket', 'Sign in to buy tickets') ?></span></a>
                        <p class="mt-3"><?= t('Belum punya akun?', 'New participant?') ?> <a href="<?= frontUrl('member-register') ?>"><?= t('Daftar member', 'Register as member') ?></a></p>
                    <?php endif; ?>
                <?php else: ?>
	                    <span class="theme-btn btn-style-one"><span class="btn-title"><?= t('Tiket habis', 'Sold out') ?></span></span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
<?php endif; ?>
