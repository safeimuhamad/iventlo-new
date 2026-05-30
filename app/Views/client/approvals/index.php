<?php
$portalSection = 'client-approvals';
$labels = ['waiting_approval' => 'Menunggu Approval', 'approved' => 'Disetujui', 'rejected' => 'Perlu Revisi / Ditolak', 'cancelled' => 'Dibatalkan'];
require_once __DIR__ . '/../../frontend/member/layouts/portal-header.php';
?>
<a href="<?= url('client/events/' . $event['id']) ?>" class="member-back">&larr; Kembali ke Detail Event</a>
<div class="member-heading">
    <h1>Approval Event</h1>
    <p><?= htmlspecialchars($event['title']) ?> - <?= htmlspecialchars($event['event_code']) ?></p>
</div>
<section class="member-card">
    <div class="portal-table-wrap">
        <table class="portal-table">
            <thead><tr><th>Referensi</th><th>Tipe Dokumen</th><th>Diajukan</th><th>Status</th><th>Level</th></tr></thead>
            <tbody>
            <?php if ($approvals): foreach ($approvals as $approval): ?>
                <tr>
                    <td><a href="<?= url('client/approvals/' . $approval['id']) ?>"><?= htmlspecialchars($approval['reference_no'] ?: ('APP-' . $approval['id'])) ?></a></td>
                    <td><?= htmlspecialchars(ucwords(str_replace('_', ' ', $approval['module_name']))) ?></td>
                    <td><?= $approval['requested_at'] ? date('d M Y H:i', strtotime($approval['requested_at'])) : '-' ?></td>
                    <td><?= htmlspecialchars($labels[$approval['status']] ?? $approval['status']) ?></td>
                    <td><?= (int) $approval['current_level'] ?></td>
                </tr>
            <?php endforeach; else: ?><tr><td colspan="5" class="portal-empty">Belum ada approval yang terkait dengan event ini.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php require_once __DIR__ . '/../../frontend/member/layouts/portal-footer.php'; ?>
