<?php
$portalSection = 'client-summary';
$labels = ['planning' => 'Perencanaan', 'preparation' => 'Persiapan', 'on_going' => 'Berlangsung', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'];
$canManage = ($event['access_level'] ?? '') === 'admin';
require_once __DIR__ . '/../../frontend/member/layouts/portal-header.php';
?>
<a href="<?= url('client/events') ?>" class="member-back">&larr; Kembali ke Event Saya</a>
<section class="member-card portal-event-overview">
    <div class="portal-card-head">
        <div><span class="member-label"><?= htmlspecialchars($event['event_code']) ?></span><h1><?= htmlspecialchars($event['title']) ?></h1><p class="member-muted"><?= htmlspecialchars($event['client_name']) ?></p></div>
        <span class="portal-badge"><?= htmlspecialchars($labels[$event['status']] ?? $event['status']) ?></span>
    </div>
    <div class="portal-summary-grid">
        <div><span>Tanggal Event</span><strong><?= $event['event_date'] ? date('d M Y', strtotime($event['event_date'])) : '-' ?></strong></div>
        <div><span>Venue</span><strong><?= htmlspecialchars($event['venue'] ?: '-') ?></strong></div>
        <div><span>Progress</span><strong><?= (int) $event['progress'] ?>%</strong></div>
    </div>
    <?php if (!empty($event['description'])): ?><p class="member-muted"><?= nl2br(htmlspecialchars($event['description'])) ?></p><?php endif; ?>
</section>
<?php if (!empty($event['is_paid'])): ?>
<section class="member-card portal-section-gap">
    <div class="portal-card-head">
        <h2>Peserta & Kehadiran</h2>
        <?php if ($canManage): ?><a href="<?= url('client/events/' . $event['id'] . '/peserta') ?>" class="member-event-action">Lihat Peserta</a><?php endif; ?>
    </div>
    <div class="portal-summary-grid portal-summary-four">
        <div><span>Kuota Peserta</span><strong><?= (int) $event['participant_quota'] ?></strong></div>
        <div><span>Tiket Terjual</span><strong><?= (int) $event['sold_tickets'] ?></strong></div>
        <div><span>Sudah Hadir</span><strong><?= (int) $event['attended_count'] ?></strong></div>
        <div><span>Sisa Kuota</span><strong><?= max(0, (int) $event['participant_quota'] - (int) $event['sold_tickets']) ?></strong></div>
    </div>
    <?php if (!$canManage): ?><p class="member-muted">Akses viewer dapat melihat ringkasan. Detail peserta dan QR Code check-in tersedia untuk Admin Client.</p><?php endif; ?>
</section>
<?php endif; ?>
<nav class="portal-action-nav">
    <a href="<?= url('client/events/' . $event['id'] . '/konten') ?>"><i class="fa fa-th-large"></i> Konten Peserta</a>
    <a href="<?= url('client/events/' . $event['id'] . '/timeline') ?>"><i class="fa fa-stream"></i> Timeline</a>
    <a href="<?= url('client/events/' . $event['id'] . '/documents') ?>"><i class="fa fa-file-alt"></i> Dokumen</a>
    <a href="<?= url('client/events/' . $event['id'] . '/approvals') ?>"><i class="fa fa-clipboard-check"></i> Approval</a>
</nav>
<div class="portal-columns">
    <section class="member-card">
        <div class="portal-card-head"><h2>Milestone Terlihat</h2></div>
        <?php if ($milestones): foreach (array_slice($milestones, 0, 4) as $milestone): ?>
            <div class="portal-list-item"><strong><?= htmlspecialchars($milestone['title']) ?></strong><span><?= htmlspecialchars(ucfirst($milestone['status'])) ?><?= $milestone['due_date'] ? ' - ' . date('d M Y', strtotime($milestone['due_date'])) : '' ?></span></div>
        <?php endforeach; else: ?><p class="member-muted">Belum ada milestone yang dibagikan.</p><?php endif; ?>
    </section>
    <section class="member-card">
        <div class="portal-card-head"><h2>Dokumen Terbaru</h2></div>
        <?php if ($documents): foreach (array_slice($documents, 0, 4) as $document): ?>
            <div class="portal-list-row"><div><strong><?= htmlspecialchars($document['title']) ?></strong><small><?= htmlspecialchars(ucfirst($document['category'])) ?></small></div><a href="<?= url('client/documents/' . $document['id'] . '/download') ?>">Unduh</a></div>
        <?php endforeach; else: ?><p class="member-muted">Belum ada dokumen yang dibagikan.</p><?php endif; ?>
    </section>
</div>
<?php require_once __DIR__ . '/../../frontend/member/layouts/portal-footer.php'; ?>
