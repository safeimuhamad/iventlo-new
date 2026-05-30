<div class="card bg-white rounded-10 border border-white mb-4">
    <div class="p-20 border-bottom d-flex justify-content-between align-items-center">
        <div><h3 class="mb-1">Scan Tiket Peserta</h3><p class="text-body mb-0"><?= htmlspecialchars($attendee['event_title']) ?></p></div>
        <a class="btn btn-light erp-btn" href="<?= url('master-events-edit', ['id' => (int) $attendee['event_id']]) ?>">Kembali</a>
    </div>
    <div class="p-20 text-center">
        <div class="mb-3"><i class="material-symbols-outlined text-primary" style="font-size:64px">qr_code_scanner</i></div>
        <h3><?= htmlspecialchars($attendee['attendee_name']) ?></h3>
        <p><?= htmlspecialchars($attendee['ticket_code']) ?> / <?= htmlspecialchars($attendee['order_number']) ?></p>
        <p>Pembayaran: <strong><?= htmlspecialchars(ucfirst($attendee['payment_status'])) ?></strong> &nbsp; Kehadiran: <strong><?= $attendee['check_in_status'] === 'arrived' ? 'Sudah Hadir' : 'Belum Hadir' ?></strong></p>
        <?php if ($attendee['check_in_status'] !== 'arrived' && $attendee['payment_status'] === 'paid'): ?>
            <form method="POST" action="<?= frontUrl('staff-ticket-checkin', ['slug' => $token]) ?>">
                <button class="btn btn-primary text-white erp-btn" type="submit">Konfirmasi Kehadiran</button>
            </form>
        <?php else: ?>
            <div class="alert alert-info"><?= $attendee['check_in_status'] === 'arrived' ? 'Kehadiran peserta sudah tercatat.' : 'Tiket belum berstatus lunas.' ?></div>
        <?php endif; ?>
    </div>
</div>
