<?php
$portalSection = 'content-' . $contentType;
$portalOrderNumber = $order['order_number'];
$labels = EventPortalContent::labels();
require_once __DIR__ . '/layouts/portal-header.php';
?>
	<a href="<?= frontUrl('member-order', ['slug' => $order['order_number']]) ?>" class="member-back">&larr; <?= t('Kembali ke tiket', 'Back to ticket') ?></a>
<div class="member-heading">
    <h1><?= htmlspecialchars($labels[$contentType]) ?></h1>
    <p><?= htmlspecialchars(t($order['event_title'], $order['event_title_en'] ?: $order['event_title'])) ?></p>
</div>
<div class="member-grid portal-content-grid">
<?php if ($contents): foreach ($contents as $content): ?>
    <article class="member-card portal-content-card">
        <?php if (!empty($content['file_path']) && in_array(strtolower(pathinfo($content['file_path'], PATHINFO_EXTENSION)), ['jpg','jpeg','png','webp'], true)): ?>
            <img class="portal-content-cover" src="<?= uploadAsset($content['file_path']) ?>" alt="<?= htmlspecialchars($content['title']) ?>">
        <?php endif; ?>
        <span class="member-label"><?= htmlspecialchars($labels[$contentType]) ?></span>
	        <h2><?= htmlspecialchars(sentenceCaseText($content['title'])) ?></h2>
        <?php if ($content['subtitle']): ?><p><strong><?= htmlspecialchars($content['subtitle']) ?></strong></p><?php endif; ?>
        <?php if ($content['scheduled_at']): ?><p class="member-muted"><i class="fa fa-clock"></i> <?= date('d M Y H:i', strtotime($content['scheduled_at'])) ?></p><?php endif; ?>
        <?php if ($content['location']): ?><p class="member-muted"><i class="fa fa-map-marker-alt"></i> <?= htmlspecialchars($content['location']) ?></p><?php endif; ?>
        <?php if ($content['description']): ?><p class="member-muted"><?= nl2br(htmlspecialchars($content['description'])) ?></p><?php endif; ?>
	        <?php if (!empty($content['file_path'])): ?><a class="member-event-action" href="<?= uploadAsset($content['file_path']) ?>" target="_blank" rel="noopener"><?= t('Buka lampiran', 'Open attachment') ?></a><?php endif; ?>
    </article>
<?php endforeach; else: ?>
    <section class="member-card member-empty-card"><h3><?= t('Informasi belum tersedia.', 'Information is not available yet.') ?></h3><p><?= t('Konten akan muncul setelah dipublikasikan penyelenggara.', 'Content will appear once published by the organizer.') ?></p></section>
<?php endif; ?>
</div>
<?php require_once __DIR__ . '/layouts/portal-footer.php'; ?>
