<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>
            <h3 class="mb-0">
                Data Kendaraan
            </h3>

            <p class="text-body fs-14 mb-0">
                Data kendaraan operasional dan jadwal service
            </p>
        </div>

        <a
            href="<?= url('vehicles-create') ?>"
            class="btn btn-primary text-white erp-btn"
        >
            + Tambah Kendaraan
        </a>

    </div>

    <div class="default-table-area mx-minus-1">

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Kendaraan</th>
                        <th>Brand</th>
                        <th>Tahun</th>
                        <th>No Polisi</th>
                        <th class="text-end">KM</th>
                        <th class="text-end">Interval Service</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($items)): ?>

                        <?php foreach ($items as $item): ?>

                            <?php
                            $maintenanceStatus = strtolower($item['maintenance_status'] ?? 'normal');

                            $badgeClass = match ($maintenanceStatus) {
                                'due'     => 'bg-danger bg-opacity-10 text-danger',
                                'process' => 'bg-warning bg-opacity-10 text-warning',
                                'done'    => 'bg-info bg-opacity-10 text-info',
                                default   => 'bg-success bg-opacity-10 text-success'
                            };

                            $statusLabel = match ($maintenanceStatus) {
                                'due'     => 'Wajib Service',
                                'process' => 'Proses Service',
                                'done'    => 'Selesai',
                                default   => 'Normal'
                            };
                            ?>

                            <tr>

                                <td>
                                    <a
                                        href="<?= url('vehicles-edit') ?>?id=<?= $item['id'] ?>"
                                        class="fw-semibold text-primary text-decoration-none"
                                    >
                                        <?= htmlspecialchars($item['vehicle_code'] ?? '-') ?>
                                    </a>
                                </td>

                                <td>
                                    <?= htmlspecialchars($item['vehicle_name'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($item['brand'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($item['year'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($item['plate_number'] ?? '-') ?>
                                </td>

                                <td class="text-end">
                                    <?= number_format((int) ($item['total_km'] ?? 0), 0, ',', '.') ?> km
                                </td>

                                <td class="text-end">
                                    <?= number_format((int) ($item['maintenance_interval_km'] ?? 0), 0, ',', '.') ?> km
                                </td>

                                <td>
                                    <span class="default-badge <?= $badgeClass ?>">
                                        <?= $statusLabel ?>
                                    </span>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                Belum ada data kendaraan.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>