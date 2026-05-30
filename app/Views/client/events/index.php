<?php
$portalSection = 'client-events';
$labels = ['planning' => 'Perencanaan', 'preparation' => 'Persiapan', 'on_going' => 'Berlangsung', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'];
require_once __DIR__ . '/../../frontend/member/layouts/portal-header.php';
?>
<div class="member-heading">
    <h1>Event Saya</h1>
    <p>Event yang telah ditugaskan kepada akun client Anda.</p>
</div>
<section class="member-card">
    <div class="portal-table-wrap">
        <table class="portal-table">
            <thead><tr><th>Event</th><th>Tanggal</th><th>Status</th><th>Progress</th><th>Tiket / Kuota</th><th>Hadir</th><th>Akses</th></tr></thead>
            <tbody>
            <?php if ($events): foreach ($events as $event): ?>
                <tr>
                    <td><a href="<?= url('client/events/' . $event['id']) ?>"><?= htmlspecialchars($event['title']) ?></a><small><?= htmlspecialchars($event['event_code']) ?></small></td>
                    <td><?= $event['event_date'] ? date('d M Y', strtotime($event['event_date'])) : '-' ?></td>
                    <td><?= htmlspecialchars($labels[$event['status']] ?? $event['status']) ?></td>
                    <td><?= (int) $event['progress'] ?>%</td>
                    <td><?= !empty($event['is_paid']) ? (int) $event['sold_tickets'] . ' / ' . (int) $event['participant_quota'] : '-' ?></td>
                    <td><?= !empty($event['is_paid']) ? (int) $event['attended_count'] : '-' ?></td>
                    <td><?= $event['access_level'] === 'admin' ? 'Admin Client' : 'Viewer' ?></td>
                </tr>
            <?php endforeach; else: ?>
                <tr><td colspan="7" class="portal-empty">Belum ada event yang dapat dilihat.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php require_once __DIR__ . '/../../frontend/member/layouts/portal-footer.php'; ?>
