<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20 border-bottom">
        <div>
            <h3 class="mb-1">Report Service Kendaraan</h3>
            <p class="text-muted mb-0">
                Laporan biaya dan aktivitas service kendaraan operasional.
            </p>
        </div>
    </div>
    <div class="p-20 border-top">

        <form method="GET" class="row g-3 align-items-end">

            <input type="hidden" name="page" value="vehicle-maintenances-report">

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
                        href="<?= url('vehicle-maintenances-report') ?>"
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

                    <p class="text-muted mb-1">Total Service</p>

                    <h4 class="mb-0">
                        <?= (int) ($summary['total_service'] ?? 0) ?>x
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
                        <th>Kendaraan</th>
                        <th>Jenis Service</th>
                        <th>Mekanik</th>
                        <th>Bengkel</th>
                        <th class="text-center">KM</th>
                        <th class="text-end">Biaya</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($items)): ?>

                        <?php foreach ($items as $item): ?>

                            <tr>

                                <td>
                                    <?= date('d/m/Y', strtotime($item['maintenance_date'])) ?>
                                </td>

                                <td>
                                    <strong><?= htmlspecialchars($item['vehicle_code']) ?></strong><br>

                                    <small class="text-muted">
                                        <?= htmlspecialchars($item['vehicle_name']) ?>
                                    </small>
                                </td>

                                <td>
                                    <?= ucfirst(str_replace('_', ' ', htmlspecialchars($item['maintenance_type']))) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($item['mechanic_name'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($item['workshop_name'] ?? '-') ?>
                                </td>

                                <td class="text-center">
                                    <?= number_format((int) ($item['km_at_maintenance'] ?? 0), 0, ',', '.') ?> km
                                </td>

                                <td class="text-end">
                                    Rp <?= number_format((float) ($item['cost'] ?? 0), 0, ',', '.') ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                Belum ada data report service kendaraan.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>