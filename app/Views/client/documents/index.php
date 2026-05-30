<?php $portalSection = 'client-documents'; require_once __DIR__ . '/../../frontend/member/layouts/portal-header.php'; ?>
<a href="<?= url('client/events/' . $event['id']) ?>" class="member-back">&larr; Kembali ke Detail Event</a>
<div class="member-heading"><h1>Document Center</h1><p><?= htmlspecialchars($event['title']) ?></p></div>
<section class="member-card">
    <div class="portal-table-wrap"><table class="portal-table"><thead><tr><th>Judul</th><th>Kategori</th><th>File</th><th>Dibagikan</th><th>Aksi</th></tr></thead><tbody>
    <?php if ($documents): foreach ($documents as $document): ?>
        <tr><td><strong><?= htmlspecialchars($document['title']) ?></strong></td><td><?= htmlspecialchars(ucfirst($document['category'])) ?></td><td><?= htmlspecialchars($document['file_name']) ?></td><td><?= date('d M Y', strtotime($document['created_at'])) ?></td><td><a class="member-event-action" href="<?= url('client/documents/' . $document['id'] . '/download') ?>">Download</a></td></tr>
    <?php endforeach; else: ?><tr><td colspan="5" class="portal-empty">Belum ada dokumen yang dibagikan untuk client.</td></tr><?php endif; ?>
    </tbody></table></div>
</section>
<?php require_once __DIR__ . '/../../frontend/member/layouts/portal-footer.php'; ?>
