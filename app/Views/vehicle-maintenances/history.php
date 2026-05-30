<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>
            <h3 class="mb-0">History Service Kendaraan</h3>

            <p class="text-body fs-14 mb-0">
                Riwayat service, perbaikan, dan biaya kendaraan operasional
            </p>
        </div>

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
                        <th>Jenis</th>
                        <th>Mekanik</th>
                        <th>Bengkel</th>
                        <th class="text-end">KM</th>
                        <th class="text-end">Biaya</th>
                        <th style="min-width:220px;">Catatan</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($items)): ?>
                        <?php foreach ($items as $item): ?>
                            <?php
                            $type = strtolower($item['maintenance_type'] ?? '');

                            $badgeClass = match ($type) {
                                'rutin' => 'bg-success bg-opacity-10 text-success',
                                'ganti_oli' => 'bg-info bg-opacity-10 text-info',
                                'ban' => 'bg-warning bg-opacity-10 text-warning',
                                'rem' => 'bg-danger bg-opacity-10 text-danger',
                                'mesin' => 'bg-danger bg-opacity-10 text-danger',
                                'kelistrikan' => 'bg-primary bg-opacity-10 text-primary',
                                'darurat' => 'bg-dark bg-opacity-10 text-dark',
                                default => 'bg-secondary bg-opacity-10 text-secondary'
                            };

                            $typeLabel = match ($type) {
                                'rutin' => 'Rutin',
                                'ganti_oli' => 'Ganti Oli',
                                'ban' => 'Ban',
                                'rem' => 'Rem',
                                'mesin' => 'Mesin',
                                'kelistrikan' => 'Kelistrikan',
                                'darurat' => 'Darurat',
                                default => ucfirst($type)
                            };
                            ?>

                            <tr>
                                <td>
                                    <a
                                        href="<?= url('vehicle-maintenances-show') ?>?id=<?= $item['id'] ?>"
                                        class="fw-semibold text-primary text-decoration-none"
                                    >
                                        <?= !empty($item['maintenance_date'])
                                            ? date('d M Y', strtotime($item['maintenance_date']))
                                            : '-' ?>
                                    </a>
                                </td>

                                <td><?= htmlspecialchars($item['vehicle_code'] ?? '-') ?></td>

                                <td><?= htmlspecialchars($item['vehicle_name'] ?? '-') ?></td>

                                <td><?= htmlspecialchars($item['plate_number'] ?? '-') ?></td>

                                <td>
                                    <span class="default-badge <?= $badgeClass ?>">
                                        <?= htmlspecialchars($typeLabel ?: '-') ?>
                                    </span>
                                </td>

                                <td><?= htmlspecialchars($item['mechanic_name'] ?? '-') ?></td>

                                <td><?= htmlspecialchars($item['workshop_name'] ?? '-') ?></td>

                                <td class="text-end">
                                    <?= number_format((int) ($item['km_at_maintenance'] ?? 0), 0, ',', '.') ?> km
                                </td>

                                <td class="text-end fw-semibold">
                                    <?php if (($item['cost'] ?? 0) > 0): ?>
                                        Rp <?= number_format((float) ($item['cost'] ?? 0), 0, ',', '.') ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>

                                <td class="text-wrap" style="min-width:220px; max-width:320px;">
                                    <?= htmlspecialchars($item['notes'] ?? '-') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">
                                Belum ada history service kendaraan.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>

            </table>

        </div>

    </div>

</div>