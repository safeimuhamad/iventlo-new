<?php if (!empty($invalidBarcode)): ?>
<?php require_once __DIR__ . '/layouts/auth-header.php'; ?>
<div class="member-auth-form">
	    <h2><?= t('QR Code tidak valid', 'Invalid QR Code') ?></h2>
    <p class="intro"><?= t('QR Code check-in tidak ditemukan atau sudah tidak aktif.', 'This check-in QR Code was not found or is no longer active.') ?></p>
	    <a href="<?= frontUrl('member-login') ?>" class="member-submit"><?= t('Kembali ke login', 'Back to login') ?></a>
</div>
<?php require_once __DIR__ . '/layouts/auth-footer.php'; ?>
<?php else: ?>
<?php
$portalSection = 'tickets';
$eventReady = ($event['status'] ?? '') === 'on_going'
    || (!empty($event['event_date']) && date('Y-m-d') >= $event['event_date'] && date('Y-m-d') <= ($event['end_date'] ?: $event['event_date']));
require_once __DIR__ . '/layouts/portal-header.php';
?>
<div class="member-heading">
    <h1><?= t('Konfirmasi Kehadiran', 'Confirm Attendance') ?></h1>
    <p><?= htmlspecialchars(t($event['title'], $event['title_en'] ?: $event['title'])) ?></p>
</div>
<?php if (!empty($memberError)): ?><div class="member-alert error"><?= htmlspecialchars($memberError) ?></div><?php endif; ?>
<?php if (!empty($memberSuccess)): ?><div class="member-alert success"><?= htmlspecialchars($memberSuccess) ?></div><?php endif; ?>
<section class="member-card member-scan-card">
    <div class="member-confirmation-icon"><i class="fa fa-qrcode"></i></div>
	    <h2><?= t('QR Code berhasil dipindai', 'QR Code scanned successfully') ?></h2>
    <p class="member-muted"><i class="fa fa-map-marker-alt"></i> <?= htmlspecialchars(t($event['venue'], $event['venue_en'] ?: $event['venue'])) ?> &nbsp; <i class="fa fa-calendar-alt"></i> <?= $event['event_date'] ? date('d M Y', strtotime($event['event_date'])) : '-' ?></p>
    <?php if (!$order): ?>
        <div class="member-alert error"><?= t('Tidak ditemukan tiket lunas untuk akun Anda pada event ini.', 'No paid ticket was found for your account at this event.') ?></div>
    <?php elseif (!$eventReady): ?>
        <div class="member-alert error"><?= t('Konfirmasi kehadiran baru tersedia pada hari pelaksanaan event.', 'Attendance confirmation becomes available on the event date.') ?></div>
    <?php else: ?>
        <p><?= t('Pilih tiket yang hadir untuk menyelesaikan check-in.', 'Select the attending ticket to complete check-in.') ?></p>
        <?php foreach ($order['tickets'] as $ticket): ?>
            <div class="member-ticket-row member-scan-ticket">
                <div><strong><?= htmlspecialchars($ticket['attendee_name']) ?></strong><p><?= htmlspecialchars($ticket['ticket_code']) ?></p></div>
                <?php if ($ticket['check_in_status'] === 'arrived'): ?>
	                    <span class="portal-badge"><?= t('Sudah hadir', 'Arrived') ?></span>
                <?php else: ?>
                    <form method="POST" action="<?= frontUrl('member-attendance-scan', ['slug' => $token]) ?>">
                        <input type="hidden" name="attendee_id" value="<?= (int) $ticket['id'] ?>">
	                        <button type="submit" class="member-event-action"><?= t('Konfirmasi hadir', 'Confirm attendance') ?></button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>
<?php require_once __DIR__ . '/layouts/portal-footer.php'; ?>
<?php endif; ?>
