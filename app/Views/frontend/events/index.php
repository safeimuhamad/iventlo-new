<?php if (isPublicMember()): ?>
<?php $portalSection = 'events'; require_once __DIR__ . '/../member/layouts/portal-header.php'; ?>
<div class="member-heading">
	    <h1><?= t('Event Berbayar', 'Ticketed Events') ?></h1>
    <p><?= t('Pilih event yang ingin Anda ikuti dan lakukan pemesanan tiket.', 'Select an event you would like to attend and reserve your tickets.') ?></p>
</div>
<?php if (!empty($events)): foreach ($events as $event): ?>
    <?php
    $slug = isEnglish() ? ($event['public_slug_en'] ?: $event['public_slug']) : $event['public_slug'];
    $available = max(0, (int) $event['participant_quota'] - (int) $event['reserved_tickets']);
    $cover = !empty($event['cover_image']) ? uploadAsset($event['cover_image']) : frontAsset('images/resource/event-single-1.jpg');
    ?>
    <article class="member-event-card">
        <img src="<?= $cover ?>" alt="<?= htmlspecialchars(t($event['title'], $event['title_en'] ?: $event['title'])) ?>">
        <div class="member-event-info">
	            <span class="member-label"><?= t('Event tersedia', 'Available event') ?></span>
	            <h3><?= htmlspecialchars(sentenceCaseText(t($event['title'], $event['title_en'] ?: $event['title']))) ?></h3>
            <div class="member-event-meta">
                <div><i class="fa fa-calendar-alt"></i> <?= $event['event_date'] ? date('d M Y', strtotime($event['event_date'])) : t('Segera', 'Soon') ?></div>
                <div><i class="fa fa-map-marker-alt"></i> <?= htmlspecialchars(t($event['venue'], $event['venue_en'] ?: $event['venue'])) ?></div>
                <div><i class="fa fa-ticket-alt"></i> Rp <?= number_format((float) $event['ticket_price'], 0, ',', '.') ?> &nbsp; | &nbsp; <?= $available ?> <?= t('tiket tersedia', 'tickets left') ?></div>
            </div>
        </div>
	        <a href="<?= frontUrl('event-detail', ['slug' => $slug]) ?>" class="member-event-action"><?= t('Lihat detail', 'View details') ?></a>
    </article>
<?php endforeach; else: ?>
    <div class="member-card member-empty-card"><h3><?= t('Belum ada event berbayar yang dibuka.', 'No ticketed events are open yet.') ?></h3></div>
<?php endif; ?>
<?php require_once __DIR__ . '/../member/layouts/portal-footer.php'; ?>
<?php else: ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<section class="page-title" style="background-image: url('<?= frontAsset('images/background/8.jpg') ?>');">
    <div class="shape-thirtyfour"></div>
    <div class="shape-thirtyfive"></div>
    <div class="auto-container">
        <div class="inner-container">
	            <h1 class="title"><?= t('Event & Tiket', 'Events & Tickets') ?></h1>
            <div class="breadcrumb-marquee">
	                <span class="title-two"><?= t('Event publik . Beli tiket . Iventlo', 'Public events . Buy tickets . Iventlo') ?></span>
            </div>
        </div>
    </div>
</section>

<section class="event-section">
    <div class="auto-container">
        <div class="sec-title text-center">
	            <span class="sub-title"><?= t('Event mendatang', 'Upcoming events') ?></span>
	            <h2 class="text-reveal-anim"><?= t('Temukan event dan pesan tiket Anda', 'Discover events and reserve your tickets') ?></h2>
        </div>

        <?php if (!empty($events)): foreach ($events as $event): ?>
            <?php
            $slug = isEnglish() ? ($event['public_slug_en'] ?: $event['public_slug']) : $event['public_slug'];
            $available = max(0, (int) $event['participant_quota'] - (int) $event['reserved_tickets']);
            ?>
            <div class="event-block">
                <div class="event-block-inner">
                    <div class="inner-box">
                        <div class="date-box">
                            <div class="date"><?= $event['event_date'] ? date('d', strtotime($event['event_date'])) : '--' ?></div>
                            <div class="year"><?= $event['event_date'] ? date('M Y', strtotime($event['event_date'])) : t('Segera', 'Soon') ?></div>
                        </div>
                        <div class="title-box">
	                            <h3 class="title"><a href="<?= frontUrl('event-detail', ['slug' => $slug]) ?>"><?= htmlspecialchars(sentenceCaseText(t($event['title'], $event['title_en'] ?: $event['title']))) ?></a></h3>
                            <ul class="location-box">
                                <li><i class="icon fa fa-map-marker-alt"></i><?= htmlspecialchars(t($event['venue'], $event['venue_en'] ?: $event['venue'])) ?></li>
                                <li><i class="icon fa fa-ticket-alt"></i>Rp <?= number_format((float) $event['ticket_price'], 0, ',', '.') ?> - <?= $available ?> <?= t('tiket tersedia', 'tickets left') ?></li>
                            </ul>
                        </div>
                        <div class="btn-box">
                            <a href="<?= frontUrl('event-detail', ['slug' => $slug]) ?>" class="theme-btn btn-style-one bg-yellow">
	                                <span class="btn-title"><?= t('Beli tiket', 'Buy tickets') ?></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; else: ?>
            <div class="text-center py-5"><p><?= t('Belum ada event berbayar yang dibuka.', 'No ticketed public events are open yet.') ?></p></div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
<?php endif; ?>
