<div class="card bg-white rounded-10 border border-white mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20 border-bottom">
        <div>
            <h3 class="mb-1">Report Maintenance Unit</h3>
            <p class="text-muted mb-0">Laporan biaya dan aktivitas maintenance unit.</p>
        </div>
    </div>

<div class="p-20 border-top">

    <form method="GET" class="row g-3 align-items-end">

        <input type="hidden" name="page" value="unit-maintenance-report">

        <div class="col-md-3">

            <label class="form-label">Tanggal Awal</label>

            <input
                type="date"
                name="start_date"
                class="form-control erp-control erp-input"
                value="<?= htmlspecialchars($startDate ?? '') ?>"
            >

        </div>

        <div class="col-md-3">

            <label class="form-label">Tanggal Akhir</label>

            <input
                type="date"
                name="end_date"
                class="form-control erp-control erp-input"
                value="<?= htmlspecialchars($endDate ?? '') ?>"
            >

        </div>

        <div class="col-md-2">

            <div class="d-flex gap-2 filter-action-group">

                <button
                    type="submit"
                    class="btn btn-primary text-white erp-btn w-100"
                >
                    Filter
                </button>

                <a
                    href="<?= url('unit-maintenance-report') ?>"
                    class="btn btn-light erp-btn"
                >
                    Reset
                </a>

            </div>

        </div>

    </form>

</div>

    <div class="p-20">
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="border rounded-10 p-3 bg-light">
                    <p class="text-muted mb-1">Total Maintenance</p>
                    <h4 class="mb-0">
                        <?= (int) ($summary['total_maintenance'] ?? 0) ?>x
                    </h4>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="border rounded-10 p-3 bg-light">
                    <p class="text-muted mb-1">Total Biaya</p>
                    <h4 class="mb-0">
                        Rp <?= number_format((float) ($summary['total_cost'] ?? 0), 0, ',', '.') ?>
                    </h4>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="border rounded-10 p-3 bg-light">
                    <p class="text-muted mb-1">Rata-rata Biaya</p>
                    <h4 class="mb-0">
                        Rp <?= number_format((float) ($summary['average_cost'] ?? 0), 0, ',', '.') ?>
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <div class="default-table-area mx-minus-1">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Unit</th>
                        <th>Jenis</th>
                        <th>Teknisi</th>
                        <th class="text-center">Counter</th>
                        <th class="text-end">Biaya</th>
                        <th>Catatan</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($items)): ?>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td>
                                    <?= !empty($item['maintenance_date']) 
                                    ? date('d/m/Y', strtotime($item['maintenance_date'])) 
                                    : '-' ?>
                                </td>

                                <td>
                                    <strong><?= htmlspecialchars($item['kode_unit'] ?? '-') ?></strong><br>
                                    <small class="text-muted">
                                        <?= htmlspecialchars($item['nama_unit'] ?? '-') ?>
                                    </small>
                                </td>

                                <?php
                                $type = strtolower($item['maintenance_type'] ?? '');

                                $badgeClass = match ($type) {
                                    'ringan' => 'bg-success',
                                    'perbaikan' => 'bg-warning text-dark',
                                    'besar' => 'bg-danger',
                                    default => 'bg-secondary'
                                };
                                ?>

                                <td>
                                    <span class="badge <?= $badgeClass ?>">
                                        <?= ucfirst(htmlspecialchars($item['maintenance_type'] ?? '-')) ?>
                                    </span>
                                </td>

                                <td>
                                    <?= htmlspecialchars($item['technician_name'] ?? '-') ?>
                                </td>

                                <td class="text-center">
                                    <?= (int) ($item['rental_count_at_maintenance'] ?? 0) ?>x
                                </td>

                                <td class="text-end">
                                    Rp <?= number_format((float) ($item['cost'] ?? 0), 0, ',', '.') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($item['notes'] ?? '-') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                Belum ada data report maintenance.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>

                <?php if (!empty($items)): ?>
                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-end">Total</th>
                            <th class="text-end">
                                Rp <?= number_format((float) ($summary['total_cost'] ?? 0), 0, ',', '.') ?>
                            </th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>