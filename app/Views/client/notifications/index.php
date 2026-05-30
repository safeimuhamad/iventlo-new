<?php $portalSection = 'client-notifications'; require_once __DIR__ . '/../../frontend/member/layouts/portal-header.php'; ?>
<div class="member-heading"><h1>Notifikasi</h1><p>Pembaruan event, dokumen, milestone, dan approval Anda.</p></div>
<section class="member-card portal-notifications">
    <?php if ($notifications): foreach ($notifications as $notification): ?>
        <article>
            <div><h2><?= htmlspecialchars($notification['title']) ?></h2><p class="member-muted"><?= htmlspecialchars($notification['message']) ?></p><?php if ($notification['event_title']): ?><small><?= htmlspecialchars($notification['event_title']) ?></small><?php endif; ?></div>
            <time><?= date('d M Y H:i', strtotime($notification['created_at'])) ?></time>
        </article>
    <?php endforeach; else: ?><p class="member-muted">Belum ada notifikasi.</p><?php endif; ?>
</section>
<?php require_once __DIR__ . '/../../frontend/member/layouts/portal-footer.php'; ?>
