<?php
$portalSection = 'tickets';
$portalOrderNumber = $order['order_number'];
require_once __DIR__ . '/layouts/portal-header.php';
$orderTitle = t($order['event_title'], $order['event_title_en'] ?: $order['event_title']);
?>
	<a href="<?= frontUrl('member-dashboard') ?>" class="member-back">&larr; <?= t('Kembali ke tiket saya', 'Back to my tickets') ?></a>
<div class="member-heading">
    <h1><?= t('Detail Tiket', 'Ticket Details') ?></h1>
    <p><?= htmlspecialchars($order['order_number']) ?></p>
</div>
<?php if (!empty($memberError)): ?><div class="member-alert error"><?= htmlspecialchars($memberError) ?></div><?php endif; ?>
<?php if (!empty($memberSuccess)): ?><div class="member-alert success"><?= htmlspecialchars($memberSuccess) ?></div><?php endif; ?>
<div class="member-detail-grid">
    <section class="member-card">
	        <span class="member-label"><?= t('Event Anda', 'Your event') ?></span>
	        <h2><?= htmlspecialchars(sentenceCaseText($orderTitle)) ?></h2>
        <p class="member-muted"><i class="fa fa-calendar-alt"></i> <?= $order['event_date'] ? date('d M Y', strtotime($order['event_date'])) : '-' ?> &nbsp; <i class="fa fa-map-marker-alt"></i> <?= htmlspecialchars(t($order['venue'], $order['venue_en'] ?: $order['venue'])) ?></p>
        <h3 class="member-price">Rp <?= number_format((float) $order['total_amount'], 0, ',', '.') ?></h3>
        <p><?= t('Status pembayaran', 'Payment status') ?>: <strong><?= htmlspecialchars(ucfirst($order['payment_status'])) ?></strong></p>
        <?php if (!empty($order['payment_proof'])): ?>
	            <a class="member-event-action" href="<?= frontUrl('member-proof', ['slug' => $order['order_number']]) ?>" target="_blank" rel="noopener"><?= t('Lihat bukti bayar', 'View payment proof') ?></a>
        <?php endif; ?>
    </section>
    <section class="member-card">
	        <h3><?= t('Bukti pembayaran', 'Payment proof') ?></h3>
        <?php if (in_array($order['payment_status'], ['pending', 'verification'], true)): ?>
            <p class="member-muted"><?= t('Upload bukti transfer agar tim Iventlo dapat melakukan verifikasi pembayaran.', 'Upload your transfer receipt for payment verification by the Iventlo team.') ?></p>
            <form method="POST" enctype="multipart/form-data" action="<?= frontUrl('member-payment', ['slug' => $order['order_number']]) ?>">
                <input type="file" name="payment_proof" class="member-form-control member-file-input" required accept=".jpg,.jpeg,.png,.webp,.pdf">
	                <button type="submit" class="member-submit"><?= t('Kirim bukti bayar', 'Submit payment proof') ?></button>
            </form>
        <?php elseif ($order['payment_status'] === 'paid'): ?>
            <p class="member-muted"><?= t('Pembayaran telah terverifikasi. Tiket Anda siap digunakan.', 'Payment verified. Your tickets are ready to use.') ?></p>
	            <a class="member-event-action" href="<?= frontUrl('member-event-content', ['slug' => $order['order_number'], 'section' => 'agenda']) ?>"><?= t('Lihat informasi acara', 'View event information') ?></a>
        <?php else: ?>
            <p class="member-muted"><?= t('Pesanan ini dibatalkan.', 'This order has been cancelled.') ?></p>
        <?php endif; ?>
    </section>
</div>
<section class="member-card member-ticket-list">
	    <h3><?= t('Daftar tiket & kedatangan', 'Tickets & arrival') ?></h3>
    <?php foreach ($order['tickets'] as $ticket): ?>
        <div class="member-ticket-row">
            <div>
                <strong><?= htmlspecialchars($ticket['ticket_code']) ?></strong>
                <p><?= htmlspecialchars($ticket['attendee_name']) ?></p>
                <small><?= $ticket['check_in_status'] === 'arrived' ? t('Sudah hadir', 'Arrived') : t('Belum hadir', 'Not arrived') ?></small>
            </div>
            <?php if ($order['payment_status'] === 'paid' && !empty($ticket['ticket_qr_token'])): ?>
                <div class="member-ticket-qr">
                    <?= qrCodeCheckInSvg(frontUrl('staff-ticket-checkin', ['slug' => $ticket['ticket_qr_token']]), 168) ?>
                    <small><?= t('Tunjukkan QR tiket ini kepada petugas check-in', 'Show this ticket QR Code to the check-in officer') ?></small>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</section>
<?php require_once __DIR__ . '/layouts/portal-footer.php'; ?>
