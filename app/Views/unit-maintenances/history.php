<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>
            <h3 class="mb-0">
                History Maintenance Unit
            </h3>

            <p class="text-body fs-14 mb-0">
                Riwayat maintenance dan perbaikan unit
            </p>
        </div>

    </div>

    <div class="default-table-area mx-minus-1">

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kode Unit</th>
                        <th>Nama Unit</th>
                        <th>Jenis</th>
                        <th>Teknisi</th>
                        <th class="text-center">Counter</th>
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
                                'ringan' => 'bg-success bg-opacity-10 text-success',
                                'perbaikan' => 'bg-warning bg-opacity-10 text-warning',
                                'besar' => 'bg-danger bg-opacity-10 text-danger',
                                default => 'bg-secondary bg-opacity-10 text-secondary'
                            };
                            ?>

                            <tr>

                                <td>
                                    <a
                                        href="<?= url('unit-maintenance-show') ?>?id=<?= $item['id'] ?>"
                                        class="fw-semibold text-primary text-decoration-none"
                                    >
                                        <?= !empty($item['maintenance_date'])
                                            ? date('d M Y', strtotime($item['maintenance_date']))
                                            : '-' ?>
                                    </a>
                                </td>

                                <td>
                                    <?= htmlspecialchars($item['kode_unit'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($item['nama_unit'] ?? '-') ?>
                                </td>

                                <td>
                                    <span class="default-badge <?= $badgeClass ?>">
                                        <?= ucfirst(htmlspecialchars($item['maintenance_type'] ?? '-')) ?>
                                    </span>
                                </td>

                                <td>
                                    <?= htmlspecialchars($item['technician_name'] ?? '-') ?>
                                </td>

                                <td class="text-center">
                                    <?= (int) ($item['rental_count_at_maintenance'] ?? 0) ?>x
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
                            <td colspan="8" class="text-center text-muted py-4">
                                Belum ada history maintenance.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>