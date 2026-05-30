<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>
            <h3 class="mb-0">
                Jadwal Maintenance
            </h3>

            <p class="text-body fs-14 mb-0">
                Unit yang sudah masuk jadwal atau due maintenance
            </p>
        </div>

    </div>

    <div class="default-table-area mx-minus-1">

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>Kode Unit</th>
                        <th>Nama Unit</th>
                        <th class="text-center">Total Rental</th>
                        <th class="text-center">Last Maintenance</th>
                        <th class="text-center">Usage</th>
                        <th class="text-center">Interval</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($items)): ?>

                        <?php foreach ($items as $item): ?>

                            <?php
                            $totalRental = (int) ($item['total_rental_count'] ?? 0);
                            $lastMaintenance = (int) ($item['last_maintenance_count'] ?? 0);
                            $interval = (int) ($item['maintenance_interval'] ?? 0);
                            $usageAfterMaintenance = $totalRental - $lastMaintenance;
                            $maintenanceStatus = $item['maintenance_status'] ?? 'normal';

                            $statusClass = $maintenanceStatus === 'process'
                                ? 'bg-warning bg-opacity-10 text-warning'
                                : 'bg-danger bg-opacity-10 text-danger';

                            $statusLabel = $maintenanceStatus === 'process'
                                ? 'Proses Maintenance'
                                : 'Due Maintenance';
                            ?>

                            <tr>

                                <td>
                                    <a
                                        href="<?= url('unit-maintenance-process') ?>?unit_id=<?= $item['id'] ?>"
                                        class="fw-semibold text-primary text-decoration-none"
                                    >
                                        <?= htmlspecialchars($item['kode_unit'] ?? '-') ?>
                                    </a>
                                </td>

                                <td>
                                    <?= htmlspecialchars($item['nama_unit'] ?? '-') ?>
                                </td>

                                <td class="text-center">
                                    <?= $totalRental ?>x
                                </td>

                                <td class="text-center">
                                    <?= $lastMaintenance ?>x
                                </td>

                                <td class="text-center">
                                    <?= $usageAfterMaintenance ?>x
                                </td>

                                <td class="text-center">
                                    <?= $interval ?>x
                                </td>

                                <td class="text-center">
                                    <span class="default-badge <?= $statusClass ?>">
                                        <?= $statusLabel ?>
                                    </span>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Tidak ada unit yang perlu maintenance.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>