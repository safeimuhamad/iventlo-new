<?php
$portalSection = 'client-timeline';
$labels = ['pending' => 'Belum Mulai', 'progress' => 'Sedang Berjalan', 'completed' => 'Selesai'];
require_once __DIR__ . '/../../frontend/member/layouts/portal-header.php';
?>
<a href="<?= url('client/events/' . $event['id']) ?>" class="member-back">&larr; Kembali ke Detail Event</a>
<div class="member-heading"><h1>Timeline & Milestone</h1><p><?= htmlspecialchars($event['title']) ?></p></div>
<section class="member-card portal-timeline">
    <?php if ($milestones): foreach ($milestones as $milestone): ?>
        <article>
            <div><h2><?= htmlspecialchars($milestone['title']) ?></h2><?php if ($milestone['description']): ?><p class="member-muted"><?= nl2br(htmlspecialchars($milestone['description'])) ?></p><?php endif; ?></div>
            <div><span class="portal-badge"><?= htmlspecialchars($labels[$milestone['status']] ?? $milestone['status']) ?></span><small><?= $milestone['due_date'] ? date('d M Y', strtotime($milestone['due_date'])) : 'Tanggal menyusul' ?></small></div>
        </article>
    <?php endforeach; else: ?><p class="member-muted">Belum ada milestone yang ditampilkan untuk client.</p><?php endif; ?>
</section>
<?php require_once __DIR__ . '/../../frontend/member/layouts/portal-footer.php'; ?>
