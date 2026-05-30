<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Detail Maintenance Unit
            </h3>

            <p class="mb-0 text-body">
                <?= htmlspecialchars($item['kode_unit'] ?? '-') ?>
                -
                <?= htmlspecialchars($item['nama_unit'] ?? '-') ?>
            </p>
        </div>

        <a
            href="<?= url('unit-maintenance-history') ?>"
            class="btn btn-light erp-btn"
        >
            <i class="ri-arrow-left-line me-1"></i>
            Kembali
        </a>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="row g-4">

        <div class="col-md-3">
            <div class="erp-detail-label">Kode Unit</div>
            <div class="erp-detail-value">
                <?= htmlspecialchars($item['kode_unit'] ?? '-') ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">Tanggal Maintenance</div>
            <div class="erp-detail-value">
                <?= !empty($item['maintenance_date'])
                    ? date('d M Y', strtotime($item['maintenance_date']))
                    : '-' ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">Teknisi</div>
            <div class="erp-detail-value">
                <?= htmlspecialchars($item['technician_name'] ?? '-') ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">Counter</div>
            <div class="erp-detail-value text-primary">
                <?= (int) ($item['rental_count_at_maintenance'] ?? 0) ?>x
            </div>
        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title">
            Informasi Maintenance
        </h4>
    </div>

    <div class="p-20">

        <div class="row g-4">

            <div class="col-md-3">
                <div class="erp-detail-label">Kode Unit</div>
                <div class="erp-detail-value">
                    <?= htmlspecialchars($item['kode_unit'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">Nama Unit</div>
                <div class="erp-detail-value">
                    <?= htmlspecialchars($item['nama_unit'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">Tanggal</div>
                <div class="erp-detail-value">
                    <?= !empty($item['maintenance_date'])
                        ? date('d M Y', strtotime($item['maintenance_date']))
                        : '-' ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">Jenis Maintenance</div>
                <div class="erp-detail-value">
                    <?= ucfirst(htmlspecialchars($item['maintenance_type'] ?? '-')) ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">Teknisi</div>
                <div class="erp-detail-value">
                    <?= htmlspecialchars($item['technician_name'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">Counter Saat Maintenance</div>
                <div class="erp-detail-value">
                    <?= (int) ($item['rental_count_at_maintenance'] ?? 0) ?>x
                </div>
            </div>

            <div class="col-md-12">
                <div class="erp-detail-label">Catatan</div>
                <div class="erp-detail-value">
                    <?= nl2br(htmlspecialchars($item['notes'] ?? '-')) ?>
                </div>
            </div>

        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title">
            Checklist Maintenance
        </h4>
    </div>

    <div class="default-table-area mx-minus-1">

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead>
                    <tr>
                        <th>Checklist</th>
                        <th>Status</th>
                        <th style="min-width:220px;">Catatan</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($checklists)): ?>
                        <?php foreach ($checklists as $checklist): ?>
                            <?php
                            $status = $checklist['checklist_status'] ?? 'ok';

                            $badgeClass = match ($status) {
                                'ok' => 'bg-success bg-opacity-10 text-success',
                                'not_ok' => 'bg-danger bg-opacity-10 text-danger',
                                'need_repair' => 'bg-warning bg-opacity-10 text-warning',
                                default => 'bg-secondary bg-opacity-10 text-secondary'
                            };

                            $label = match ($status) {
                                'ok' => 'OK',
                                'not_ok' => 'Tidak OK',
                                'need_repair' => 'Perlu Perbaikan',
                                default => $status
                            };
                            ?>

                            <tr>
                                <td class="fw-semibold">
                                    <?= htmlspecialchars($checklist['checklist_name'] ?? '-') ?>
                                </td>

                                <td>
                                    <span class="default-badge <?= $badgeClass ?>">
                                        <?= htmlspecialchars($label) ?>
                                    </span>
                                </td>

                                <td class="text-wrap" style="min-width:220px; max-width:420px;">
                                    <?= htmlspecialchars($checklist['notes'] ?? '-') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center text-body py-4">
                                Tidak ada checklist.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>

            </table>

        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title">
            Dokumentasi Maintenance
        </h4>
    </div>

    <div class="p-20">

        <div class="row g-3">
            <?php if (!empty($documents)): ?>
                <?php foreach ($documents as $doc): ?>
                    <div class="col-md-3">
                        <div class="border rounded-10 p-2 h-100">

                            <?php if (str_starts_with($doc['file_type'] ?? '', 'image/')): ?>
                                <img
                                    src="<?= asset($doc['file_path']) ?>"
                                    alt="Dokumentasi Maintenance"
                                    class="img-fluid rounded-10 mb-2"
                                >
                            <?php else: ?>
                                <div class="p-4 text-center bg-body-bg rounded-10 mb-2">
                                    File
                                </div>
                            <?php endif; ?>

                            <a
                                href="<?= asset($doc['file_path']) ?>"
                                target="_blank"
                                class="btn btn-outline-primary erp-btn w-100"
                            >
                                <i class="ri-eye-line me-1"></i>
                                Lihat File
                            </a>

                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="text-center text-body py-4">
                        Belum ada dokumentasi.
                    </div>
                </div>
            <?php endif; ?>
        </div>

    </div>

</div>