<?php
$portalSection = 'client-content-' . $selectedType;
$labels = EventPortalContent::labels();
require_once __DIR__ . '/../../frontend/member/layouts/portal-header.php';
?>
<a href="<?= url('client/events/' . $event['id']) ?>" class="member-back">&larr; Kembali ke Detail Event</a>
<div class="member-heading"><h1><?= htmlspecialchars($labels[$selectedType]) ?></h1><p><?= htmlspecialchars($event['title']) ?> - informasi yang tampil di portal member.</p></div>
<?php if ($canManage): ?>
<section class="member-card portal-form-block">
    <div class="portal-card-head"><h2>Tambah <?= htmlspecialchars($labels[$selectedType]) ?></h2></div>
    <form method="POST" enctype="multipart/form-data" action="<?= url('client/events/' . $event['id'] . '/konten/simpan') ?>">
        <div class="member-input-grid">
            <div class="member-form-group"><label>Modul</label><select name="content_type" class="member-form-control" required><?php foreach ($labels as $key => $label): ?><option value="<?= $key ?>" <?= $selectedType === $key ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option><?php endforeach; ?></select></div>
            <div class="member-form-group"><label>Judul</label><input name="title" class="member-form-control" required></div>
            <div class="member-form-group"><label>Subjudul / Nama Instansi</label><input name="subtitle" class="member-form-control"></div>
            <div class="member-form-group"><label>Waktu Agenda</label><input type="datetime-local" name="scheduled_at" class="member-form-control"></div>
            <div class="member-form-group"><label>Lokasi</label><input name="location" class="member-form-control"></div>
            <div class="member-form-group"><label>Lampiran / Foto / PDF</label><input type="file" name="content_file" class="member-form-control" accept=".pdf,.jpg,.jpeg,.png,.webp"></div>
        </div>
        <div class="member-form-group"><label>Deskripsi / Pertanyaan / Pilihan Polling</label><textarea name="description" class="member-form-control portal-textarea" rows="4"></textarea></div>
        <label class="member-muted"><input type="checkbox" name="visible_to_member" value="1" checked> Tampilkan kepada peserta</label>
        <button class="member-action member-submit-compact" type="submit">Simpan Konten</button>
    </form>
</section>
<?php else: ?><div class="member-alert success">Akses viewer hanya dapat melihat konten yang telah ditampilkan kepada peserta.</div><?php endif; ?>
<section class="member-card portal-section-gap">
    <div class="portal-card-head"><h2>Daftar <?= htmlspecialchars($labels[$selectedType]) ?></h2></div>
    <div class="portal-table-wrap">
        <table class="portal-table">
            <thead><tr><th>Modul</th><th>Judul</th><th>Detail</th><th>Visible</th><?php if ($canManage): ?><th>Aksi</th><?php endif; ?></tr></thead>
            <tbody>
            <?php if ($contents): foreach ($contents as $content): ?>
                <tr>
                    <td><?= htmlspecialchars($labels[$content['content_type']] ?? $content['content_type']) ?></td>
                    <td><strong><?= htmlspecialchars($content['title']) ?></strong><small><?= htmlspecialchars($content['subtitle'] ?: '-') ?></small></td>
                    <td><?= $content['scheduled_at'] ? date('d M Y H:i', strtotime($content['scheduled_at'])) : htmlspecialchars($content['location'] ?: '-') ?></td>
                    <td><?= $content['visible_to_member'] ? 'Peserta' : 'Internal' ?></td>
                    <?php if ($canManage): ?><td><form method="POST" action="<?= url('client/events/' . $event['id'] . '/konten/' . $content['id'] . '/hapus') ?>"><button type="submit" class="member-event-action">Hapus</button></form></td><?php endif; ?>
                </tr>
            <?php endforeach; else: ?><tr><td colspan="<?= $canManage ? 5 : 4 ?>" class="portal-empty">Belum ada konten peserta.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php require_once __DIR__ . '/../../frontend/member/layouts/portal-footer.php'; ?>
