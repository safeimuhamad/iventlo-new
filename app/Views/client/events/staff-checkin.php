<?php $portalSection = 'client-events'; require_once __DIR__ . '/../../frontend/member/layouts/portal-header.php'; ?>
<a href="<?= url('client/events/' . $event['id'] . '/peserta') ?>" class="member-back">&larr; Kembali ke Peserta</a>
<div class="member-heading"><h1>Scan Tiket Peserta</h1><p><?= htmlspecialchars($event['title']) ?></p></div>
<?php if (!empty($memberSuccess)): ?><div class="member-alert success"><?= htmlspecialchars($memberSuccess) ?></div><?php endif; ?>
<?php if (!empty($memberError)): ?><div class="member-alert error"><?= htmlspecialchars($memberError) ?></div><?php endif; ?>
<section class="member-card member-scan-card">
    <div class="member-confirmation-icon"><i class="fa fa-qrcode"></i></div>
    <h2><?= htmlspecialchars($attendee['attendee_name']) ?></h2>
    <p class="member-muted"><?= htmlspecialchars($attendee['ticket_code']) ?> &nbsp; | &nbsp; <?= htmlspecialchars($attendee['order_number']) ?></p>
    <div class="portal-summary-grid">
        <div><span>Pembayaran</span><strong><?= htmlspecialchars(ucfirst($attendee['payment_status'])) ?></strong></div>
        <div><span>Kehadiran</span><strong><?= $attendee['check_in_status'] === 'arrived' ? 'Sudah Hadir' : 'Belum Hadir' ?></strong></div>
        <div><span>Tanggal Event</span><strong><?= $attendee['event_date'] ? date('d M Y', strtotime($attendee['event_date'])) : '-' ?></strong></div>
    </div>
    <?php if ($attendee['check_in_status'] !== 'arrived' && $attendee['payment_status'] === 'paid'): ?>
        <form method="POST" action="<?= frontUrl('staff-ticket-checkin', ['slug' => $token]) ?>">
            <button type="submit" class="member-action member-submit-compact">Konfirmasi Kehadiran</button>
        </form>
    <?php else: ?>
        <p class="member-alert success"><?= $attendee['check_in_status'] === 'arrived' ? 'Kehadiran peserta sudah tercatat.' : 'Tiket belum berstatus lunas.' ?></p>
    <?php endif; ?>
</section>
<?php require_once __DIR__ . '/../../frontend/member/layouts/portal-footer.php'; ?>
