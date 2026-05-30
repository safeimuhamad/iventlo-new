<?php $portalSection = 'tickets'; require_once __DIR__ . '/../member/layouts/portal-header.php'; ?>
<section class="member-card member-confirmation">
    <div class="member-confirmation-icon"><i class="fa fa-check"></i></div>
	    <span class="member-label"><?= t('Menunggu konfirmasi pembayaran', 'Awaiting payment confirmation') ?></span>
    <h1><?= t('Pemesanan Diterima', 'Reservation Received') ?></h1>
    <h2><?= htmlspecialchars($order['order_number']) ?></h2>
    <p><?= t('Terima kasih, pesanan tiket Anda telah tercatat untuk', 'Thank you, your ticket reservation has been recorded for') ?> <strong><?= htmlspecialchars(t($event['title'], $event['title_en'] ?: $event['title'])) ?></strong>.</p>
    <h3 class="member-price">Rp <?= number_format((float) $order['total_amount'], 0, ',', '.') ?></h3>
	    <p><?= (int) $order['quantity'] ?> <?= t('tiket', 'tickets') ?> - <?= t('Status: menunggu pembayaran', 'Status: pending payment') ?></p>
	    <p class="member-muted"><?= t('Silakan buka tiket saya untuk mengunggah bukti pembayaran. Tiket aktif setelah bukti diverifikasi tim Iventlo.', 'Please open my tickets to upload payment proof. Tickets become active after verification by the Iventlo team.') ?></p>
	    <a href="<?= frontUrl('member-order', ['slug' => $order['order_number']]) ?>" class="member-action member-submit-compact"><?= t('Upload bukti bayar', 'Upload payment proof') ?></a>
</section>
<?php require_once __DIR__ . '/../member/layouts/portal-footer.php'; ?>
