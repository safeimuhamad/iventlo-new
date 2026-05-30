<?php
$portalSection = 'client-events';
$statusLabels = ['waiting_approval' => 'Menunggu Approval', 'approved' => 'Disetujui', 'rejected' => 'Revisi Diminta / Ditolak', 'cancelled' => 'Dibatalkan'];
$canProcess = ($approval['access_level'] ?? '') === 'admin' && ($approval['status'] ?? '') === 'waiting_approval';
require_once __DIR__ . '/../../frontend/member/layouts/portal-header.php';
?>
<a href="<?= url('client/events/' . $approval['event_id'] . '/approvals') ?>" class="member-back">&larr; Kembali ke Approval</a>
<section class="member-card portal-event-overview">
    <div class="portal-card-head">
        <div>
            <span class="member-label"><?= htmlspecialchars($approval['event_code']) ?> / APPROVAL</span>
            <h1><?= htmlspecialchars($approval['reference_no'] ?: ('APP-' . $approval['id'])) ?></h1>
            <p class="member-muted"><?= htmlspecialchars($approval['event_title']) ?></p>
        </div>
        <span class="portal-badge"><?= htmlspecialchars($statusLabels[$approval['status']] ?? $approval['status']) ?></span>
    </div>
    <div class="portal-summary-grid">
        <div><span>Jenis</span><strong><?= htmlspecialchars(ucwords(str_replace('_', ' ', $approval['module_name']))) ?></strong></div>
        <div><span>Tanggal Pengajuan</span><strong><?= $approval['requested_at'] ? date('d M Y H:i', strtotime($approval['requested_at'])) : '-' ?></strong></div>
        <div><span>Level Saat Ini</span><strong><?= (int) $approval['current_level'] ?></strong></div>
    </div>
</section>
<?php if ($canProcess): ?>
<div class="portal-columns portal-section-gap">
    <section class="member-card portal-form-block">
        <h2>Setujui Approval</h2>
        <form method="POST" action="<?= url('client/approvals/' . $approval['id'] . '/approve') ?>">
            <label>Catatan (opsional)</label>
            <textarea name="notes" class="member-form-control portal-textarea" rows="3" placeholder="Catatan persetujuan"></textarea>
            <button type="submit" class="member-action member-submit-compact">Setujui</button>
        </form>
    </section>
    <section class="member-card portal-form-block">
        <h2>Minta Revisi</h2>
        <form method="POST" action="<?= url('client/approvals/' . $approval['id'] . '/revision') ?>">
            <label>Komentar Revisi <span class="portal-required">*</span></label>
            <textarea name="comment" class="member-form-control portal-textarea" rows="3" required placeholder="Jelaskan bagian yang perlu direvisi"></textarea>
            <button type="submit" class="member-event-action">Kirim Permintaan Revisi</button>
        </form>
    </section>
</div>
<?php elseif (($approval['access_level'] ?? '') === 'viewer'): ?>
<div class="member-alert success portal-section-gap">Akses viewer hanya dapat melihat detail dan riwayat approval.</div>
<?php endif; ?>
<div class="portal-columns portal-section-gap">
    <section class="member-card">
        <div class="portal-card-head"><h2>Riwayat Approval</h2></div>
        <div class="portal-table-wrap"><table class="portal-table"><thead><tr><th>Level</th><th>Status</th><th>Diproses Oleh</th><th>Catatan</th><th>Waktu</th></tr></thead><tbody>
        <?php if ($steps): foreach ($steps as $step): ?>
            <tr><td><?= (int) $step['approval_level'] ?></td><td><?= htmlspecialchars(ucfirst($step['status'])) ?></td><td><?= htmlspecialchars($step['approved_by_name'] ?: '-') ?></td><td><?= htmlspecialchars($step['notes'] ?: '-') ?></td><td><?= $step['approved_at'] ? date('d M Y H:i', strtotime($step['approved_at'])) : '-' ?></td></tr>
        <?php endforeach; else: ?><tr><td colspan="5" class="portal-empty">Belum ada riwayat proses.</td></tr><?php endif; ?>
        </tbody></table></div>
    </section>
    <section class="member-card">
        <div class="portal-card-head"><h2>Dokumen Event</h2></div>
        <?php if ($documents): foreach ($documents as $document): ?>
            <div class="portal-list-row"><div><strong><?= htmlspecialchars($document['title']) ?></strong><small><?= htmlspecialchars(ucfirst($document['category'])) ?></small></div><a href="<?= url('client/documents/' . $document['id'] . '/download') ?>">Unduh</a></div>
        <?php endforeach; else: ?><p class="member-muted">Tidak ada dokumen yang dibagikan untuk client.</p><?php endif; ?>
    </section>
</div>
<?php require_once __DIR__ . '/../../frontend/member/layouts/portal-footer.php'; ?>
