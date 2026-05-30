<?php
$portalSection = 'tickets';
$portalOrderNumber = null;
foreach (($orders ?? []) as $portalOrder) {
    if (($portalOrder['payment_status'] ?? '') === 'paid') {
        $portalOrderNumber = $portalOrder['order_number'];
        break;
    }
}
require_once __DIR__ . '/layouts/portal-header.php';
?>
<div class="member-heading">
	    <h1><?= t('Tiket Saya', 'My Tickets') ?></h1>
    <p><?= t('Pantau pesanan, status pembayaran, dan konfirmasi kehadiran Anda.', 'Track your orders, payment status, and event attendance.') ?></p>
</div>
<?php if (!empty($memberError)): ?><div class="member-alert error"><?= htmlspecialchars($memberError) ?></div><?php endif; ?>
<?php if (!empty($memberSuccess)): ?><div class="member-alert success"><?= htmlspecialchars($memberSuccess) ?></div><?php endif; ?>
<div class="member-grid">
    <?php if (!empty($orders)): foreach ($orders as $order): ?>
        <article class="member-card member-order-card">
            <span class="member-label"><?= htmlspecialchars($order['order_number']) ?></span>
	            <h3><?= htmlspecialchars(sentenceCaseText(t($order['event_title'], $order['event_title_en'] ?: $order['event_title']))) ?></h3>
            <p class="member-muted"><i class="fa fa-calendar-alt"></i> <?= $order['event_date'] ? date('d M Y', strtotime($order['event_date'])) : '-' ?> &nbsp; <i class="fa fa-map-marker-alt"></i> <?= htmlspecialchars($order['venue'] ?: '-') ?></p>
            <p><strong>Rp <?= number_format((float) $order['total_amount'], 0, ',', '.') ?></strong> / <?= (int) $order['ticket_count'] ?> <?= t('tiket', 'tickets') ?></p>
            <div class="member-stats">
                <span><?= t('Pembayaran', 'Payment') ?><strong><?= htmlspecialchars(ucfirst($order['payment_status'])) ?></strong></span>
	                <span><?= t('Sudah hadir', 'Arrived') ?><strong><?= (int) $order['checked_in_count'] ?></strong></span>
            </div>
	            <a href="<?= frontUrl('member-order', ['slug' => $order['order_number']]) ?>" class="member-event-action"><?= t('Lihat detail', 'View details') ?></a>
        </article>
    <?php endforeach; else: ?>
        <div class="member-card member-empty-card">
            <i class="fa fa-ticket-alt"></i>
            <h3><?= t('Belum ada tiket.', 'No tickets yet.') ?></h3>
            <p><?= t('Pilih event berbayar untuk membuat pesanan tiket pertama Anda.', 'Choose a ticketed event to place your first ticket order.') ?></p>
	            <a href="<?= frontUrl('events') ?>" class="member-action"><?= t('Lihat event', 'Browse events') ?></a>
        </div>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/layouts/portal-footer.php'; ?>
