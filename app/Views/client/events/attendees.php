<?php $portalSection = 'client-attendees'; require_once __DIR__ . '/../../frontend/member/layouts/portal-header.php'; ?>
<a href="<?= url('client/events/' . $event['id']) ?>" class="member-back">&larr; Kembali ke Detail Event</a>
<div class="member-heading">
    <h1>Peserta & Kehadiran</h1>
    <p><?= htmlspecialchars($event['title']) ?> - data pembeli tiket yang dapat dilihat oleh Admin Client.</p>
</div>
<div class="portal-kpi-grid portal-kpi-four">
    <div class="member-card portal-kpi"><span>Kuota</span><strong><?= (int) $event['participant_quota'] ?></strong></div>
    <div class="member-card portal-kpi"><span>Tiket Terjual</span><strong><?= (int) $stats['sold_tickets'] ?></strong></div>
    <div class="member-card portal-kpi"><span>Dalam Pemesanan</span><strong><?= (int) $stats['reserved_tickets'] ?></strong></div>
    <div class="member-card portal-kpi"><span>Hadir</span><strong><?= (int) $stats['attended_count'] ?></strong></div>
</div>
<section class="member-card portal-section-gap">
    <div class="portal-card-head">
        <div><h2>QR Code Kehadiran</h2><p class="member-muted">Cetak QR Code dan pasang di lokasi acara. Peserta memindai QR Code dari kamera ponsel untuk check-in.</p></div>
        <?php if (!empty($event['attendance_token']) && !empty($event['attendance_checkin_enabled'])): ?>
            <a class="member-action member-submit-compact" target="_blank" rel="noopener" href="<?= url('client/events/' . $event['id'] . '/peserta/qr-code') ?>"><i class="fa fa-print"></i>&nbsp; Cetak QR Code</a>
        <?php else: ?>
            <form method="POST" action="<?= url('client/events/' . $event['id'] . '/peserta/qr-code/aktifkan') ?>">
                <button class="member-action member-submit-compact" type="submit"><i class="fa fa-qrcode"></i>&nbsp; Aktifkan QR Code</button>
            </form>
        <?php endif; ?>
    </div>
    <p class="member-muted">Mode petugas: peserta menunjukkan QR pada tiket digitalnya, lalu petugas/client admin memindai QR tersebut dan mengonfirmasi kehadiran dari halaman validasi yang terbuka.</p>
</section>
<section class="member-card portal-section-gap">
    <div class="portal-card-head"><h2>Daftar Peserta</h2></div>
    <div class="portal-table-wrap">
        <table class="portal-table">
            <thead><tr><th>Kode Tiket</th><th>Peserta</th><th>Order</th><th>Pembayaran</th><th>Kehadiran</th><th>Waktu Check-in</th></tr></thead>
            <tbody>
            <?php if ($attendees): foreach ($attendees as $attendee): ?>
                <tr>
                    <td><?= htmlspecialchars($attendee['ticket_code']) ?></td>
                    <td><?= htmlspecialchars($attendee['attendee_name']) ?><small><?= htmlspecialchars($attendee['attendee_email'] ?: '-') ?></small></td>
                    <td><?= htmlspecialchars($attendee['order_number']) ?></td>
                    <td><?= htmlspecialchars(ucfirst($attendee['payment_status'])) ?></td>
                    <td><?= $attendee['check_in_status'] === 'arrived' ? 'Hadir' : 'Belum hadir' ?></td>
                    <td><?= $attendee['checked_in_at'] ? date('d M Y H:i', strtotime($attendee['checked_in_at'])) : '-' ?></td>
                </tr>
            <?php endforeach; else: ?><tr><td colspan="6" class="portal-empty">Belum ada peserta terdaftar.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php require_once __DIR__ . '/../../frontend/member/layouts/portal-footer.php'; ?>
