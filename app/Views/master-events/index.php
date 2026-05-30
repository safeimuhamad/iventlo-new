<?php $labels = ['planning' => 'Perencanaan', 'preparation' => 'Persiapan', 'on_going' => 'Berlangsung', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan']; ?>
<div class="card bg-white rounded-10 border border-white mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">
        <div>
            <h3 class="mb-0">Master Event Client</h3>
            <p class="text-body fs-14 mb-0">Kelola event, website publik, tiket, akses Client Portal, timeline, dokumen, dan approval.</p>
        </div>
        <a href="<?= url('master-events-create') ?>" class="btn btn-primary text-white erp-btn">+ Tambah Event</a>
    </div>
    <div class="default-table-area mx-minus-1">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead><tr><th>Kode</th><th>Event</th><th>Client</th><th>Tanggal</th><th>Status</th><th>Tiket</th><th>Kehadiran</th><th>Akses Client</th></tr></thead>
                <tbody>
                    <?php if ($events): foreach ($events as $event): ?>
                        <tr>
                            <td><?= htmlspecialchars($event['event_code']) ?></td>
                            <td><a href="<?= url('master-events-edit', ['id' => $event['id']]) ?>" class="fw-semibold text-primary text-decoration-none"><?= htmlspecialchars($event['title']) ?></a></td>
                            <td><?= htmlspecialchars($event['client_name']) ?></td>
                            <td><?= $event['event_date'] ? date('d M Y', strtotime($event['event_date'])) : '-' ?></td>
                            <td><?= htmlspecialchars($labels[$event['status']] ?? $event['status']) ?><small class="d-block text-body">Progress <?= (int) $event['progress'] ?>%</small></td>
                            <td><?= !empty($event['is_paid']) ? (int) $event['sold_tickets'] . ' / ' . (int) $event['participant_quota'] : '-' ?></td>
                            <td><?= !empty($event['is_paid']) ? (int) $event['attended_count'] . ' hadir' : '-' ?></td>
                            <td><?= (int) $event['client_count'] ?> user</td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="8" class="text-center py-4 text-body">Belum ada master event client.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
