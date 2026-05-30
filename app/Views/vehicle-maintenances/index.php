<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>

            <h3 class="mb-0">
                Service Due Kendaraan
            </h3>

            <p class="text-body fs-14 mb-0">
                Kendaraan yang sudah memasuki jadwal service berkala
            </p>

        </div>

    </div>

    <div class="default-table-area mx-minus-1">

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>Nama Kendaraan</th>
                        <th>Brand</th>
                        <th>No Polisi</th>
                        <th class="text-end">Total KM</th>
                        <th class="text-end">KM Setelah Service</th>
                        <th class="text-end">Interval</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($items)): ?>

                        <?php foreach ($items as $item): ?>

                            <?php
                            $status = $item['maintenance_status'] ?? 'normal';

                            $badgeClass = match ($status) {
                                'due'     => 'bg-danger bg-opacity-10 text-danger',
                                'process' => 'bg-warning bg-opacity-10 text-warning',
                                default   => 'bg-success bg-opacity-10 text-success'
                            };

                            $statusLabel = match ($status) {
                                'due'     => 'Wajib Service',
                                'process' => 'Proses Service',
                                default   => 'Normal'
                            };
                            ?>

                            <tr>

                                <td>

                                    <a
                                        href="<?= url('vehicle-maintenances-process') ?>?vehicle_id=<?= $item['id'] ?>"
                                        class="fw-semibold text-primary text-decoration-none"
                                    >
                                        <?= htmlspecialchars($item['vehicle_name'] ?? '-') ?>
                                    </a>

                                </td>

                                <td>

                                    <?= htmlspecialchars($item['brand'] ?? '-') ?>

                                </td>

                                <td>

                                    <?= htmlspecialchars($item['plate_number'] ?? '-') ?>

                                </td>

                                <td class="text-end">

                                    <?= number_format((int) ($item['total_km'] ?? 0), 0, ',', '.') ?> km

                                </td>

                                <td class="text-end">

                                    <?= number_format((int) ($item['km_after_service'] ?? 0), 0, ',', '.') ?> km

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
                            <td colspan="7" class="text-center text-muted py-4">
                                Tidak ada kendaraan wajib service.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>