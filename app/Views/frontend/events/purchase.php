<?php
$titleText = t($event['title'], $event['title_en'] ?: $event['title']);
$available = max(0, (int) $event['participant_quota'] - (int) $event['reserved_tickets']);
$portalSection = 'events';
require_once __DIR__ . '/../member/layouts/portal-header.php';
?>
	<a href="<?= frontUrl('event-detail', ['slug' => isEnglish() ? ($event['public_slug_en'] ?: $event['public_slug']) : $event['public_slug']]) ?>" class="member-back">&larr; <?= t('Kembali ke detail event', 'Back to event details') ?></a>
<div class="member-heading">
    <h1><?= t('Pesan Tiket', 'Reserve Tickets') ?></h1>
    <p><?= t('Lengkapi informasi pesanan untuk event pilihan Anda.', 'Complete your reservation information for the selected event.') ?></p>
</div>
<section class="member-card member-form-card">
	    <span class="member-label"><?= t('Form pemesanan tiket', 'Ticket reservation form') ?></span>
	    <h2><?= htmlspecialchars(sentenceCaseText($titleText)) ?></h2>
    <p class="member-muted">Rp <?= number_format((float) $event['ticket_price'], 0, ',', '.') ?> / <?= t('tiket', 'ticket') ?> - <?= $available ?> <?= t('tersedia', 'available') ?></p>
    <?php if (!empty($frontError)): ?><div class="member-alert error"><?= htmlspecialchars($frontError) ?></div><?php endif; ?>
    <form method="POST" action="<?= frontUrl('event-purchase', ['slug' => isEnglish() ? ($event['public_slug_en'] ?: $event['public_slug']) : $event['public_slug']]) ?>">
        <div class="member-input-grid">
	            <div class="member-form-group"><label><?= t('Nama member', 'Member name') ?></label><input class="member-form-control" value="<?= htmlspecialchars($memberName) ?>" readonly></div>
            <div class="member-form-group"><label>Email</label><input type="email" class="member-form-control" value="<?= htmlspecialchars($memberEmail) ?>" readonly></div>
	            <div class="member-form-group"><label><?= t('Nomor WhatsApp', 'WhatsApp number') ?></label><input class="member-form-control" name="buyer_phone" required placeholder="<?= t('Nomor WhatsApp', 'WhatsApp number') ?>"></div>
	            <div class="member-form-group"><label><?= t('Jumlah tiket', 'Ticket quantity') ?></label><input type="number" class="member-form-control" name="quantity" required min="1" max="<?= min(10, $available) ?>" value="1"></div>
        </div>
	        <button class="member-submit member-submit-compact" type="submit"><?= t('Buat pesanan', 'Place order') ?></button>
    </form>
	    <p class="member-note"><?= t('Setelah memesan, unggah bukti pembayaran melalui halaman tiket saya untuk diverifikasi tim Iventlo.', 'After ordering, upload your payment proof in my tickets for verification by the Iventlo team.') ?></p>
</section>
<?php require_once __DIR__ . '/../member/layouts/portal-footer.php'; ?>
