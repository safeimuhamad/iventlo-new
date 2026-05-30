<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>

            <h3 class="mb-0">
                Log Pemakaian Kendaraan
            </h3>

            <p class="text-body fs-14 mb-0">
                Riwayat penggunaan kendaraan operasional perusahaan
            </p>

        </div>

        <a
        href="<?= url('vehicle-usage-logs-create') ?>"
        class="btn btn-primary text-white erp-btn"
        >
        + Tambah Log
    </a>

</div>

<div class="default-table-area mx-minus-1">

    <div class="table-responsive">

        <table class="table align-middle">

            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kode Kendaraan</th>
                    <th>Nama Kendaraan</th>
                    <th>No Polisi</th>
                    <th>Aktivitas</th>
                    <th style="min-width:220px;">Tujuan</th>
                    <th class="text-end">KM Awal</th>
                    <th class="text-end">KM Akhir</th>
                    <th class="text-end">Jarak</th>
                    <th>Driver</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($items)): ?>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td>
                                <a
                                href="<?= url('vehicle-usage-logs-show') ?>?id=<?= $item['id'] ?>"
                                class="fw-semibold text-primary text-decoration-none"
                                >
                                <?= !empty($item['usage_date'])
                                ? date('d M Y', strtotime($item['usage_date']))
                                : '-' ?>
                            </a>
                        </td>

                        <td><?= htmlspecialchars($item['vehicle_code'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($item['vehicle_name'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($item['plate_number'] ?? '-') ?></td>
                        <td><?= ucfirst(htmlspecialchars($item['activity_type'] ?? '-')) ?></td>

                        <td class="text-wrap" style="min-width:220px; max-width:320px;">
                            <?= htmlspecialchars($item['destination'] ?? '-') ?>
                        </td>

                        <td class="text-end">
                            <?= number_format((int) ($item['km_start'] ?? 0), 0, ',', '.') ?> km
                        </td>

                        <td class="text-end">
                            <?= number_format((int) ($item['km_end'] ?? 0), 0, ',', '.') ?> km
                        </td>

                        <td class="text-end fw-semibold text-primary">
                            <?= number_format((int) ($item['distance_km'] ?? 0), 0, ',', '.') ?> km
                        </td>

                        <td><?= htmlspecialchars($item['driver_name'] ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10" class="text-center text-muted py-4">
                        Belum ada log pemakaian kendaraan.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>

    </table>
</div>

</div>

</div>