<?php
$portalSection = 'client-dashboard';
$eventStatus = ['planning' => 'Perencanaan', 'preparation' => 'Persiapan', 'on_going' => 'Berlangsung', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'];
require_once __DIR__ . '/../frontend/member/layouts/portal-header.php';
?>
<div class="member-heading">
    <h1>Dashboard Client</h1>
    <p>Pantau event, milestone, dokumen, approval, dan kehadiran peserta Anda.</p>
</div>
<div class="portal-kpi-grid">
    <div class="member-card portal-kpi"><i class="fa fa-calendar-alt"></i><span>Event Aktif</span><strong><?= (int) $totalEvents ?></strong></div>
    <div class="member-card portal-kpi"><i class="fa fa-clipboard-check"></i><span>Menunggu Approval</span><strong><?= (int) $pendingApprovals ?></strong></div>
    <div class="member-card portal-kpi"><i class="fa fa-bell"></i><span>Notifikasi Baru</span><strong><?= (int) $unreadNotifications ?></strong></div>
</div>
<div class="portal-columns">
    <section class="member-card">
        <div class="portal-card-head"><h2>Event Saya</h2><a href="<?= url('client/events') ?>" class="member-event-action">Semua Event</a></div>
        <div class="portal-table-wrap">
            <table class="portal-table">
                <thead><tr><th>Event</th><th>Tanggal</th><th>Status</th><th>Progress</th></tr></thead>
                <tbody>
                <?php if ($events): foreach ($events as $event): ?>
                    <tr>
                        <td><a href="<?= url('client/events/' . $event['id']) ?>"><?= htmlspecialchars($event['title']) ?></a><small><?= htmlspecialchars($event['event_code']) ?></small></td>
                        <td><?= $event['event_date'] ? date('d M Y', strtotime($event['event_date'])) : '-' ?></td>
                        <td><?= htmlspecialchars($eventStatus[$event['status']] ?? $event['status']) ?></td>
                        <td><?= (int) $event['progress'] ?>%</td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="4" class="portal-empty">Belum ada event yang ditugaskan.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
    <section class="member-card">
        <div class="portal-card-head"><h2>Upcoming Milestone</h2></div>
        <?php if ($upcomingMilestones): foreach ($upcomingMilestones as $milestone): ?>
            <a class="portal-list-item" href="<?= url('client/events/' . $milestone['event_id'] . '/timeline') ?>">
                <strong><?= htmlspecialchars($milestone['title']) ?></strong>
                <span><?= htmlspecialchars($milestone['event_title']) ?></span>
                <small><?= $milestone['due_date'] ? date('d M Y', strtotime($milestone['due_date'])) : 'Tanggal menyusul' ?></small>
            </a>
        <?php endforeach; else: ?>
            <p class="member-muted">Tidak ada milestone mendatang yang ditampilkan.</p>
        <?php endif; ?>
    </section>
</div>
<?php require_once __DIR__ . '/../frontend/member/layouts/portal-footer.php'; ?>
