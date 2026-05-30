<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>

            <h3 class="mb-0">
                Reminder STNK, KIR & Pajak Kendaraan
            </h3>

            <p class="text-body fs-14 mb-0">
                Monitoring masa berlaku STNK, KIR, dan pajak kendaraan
            </p>

        </div>

    </div>

    <div class="default-table-area mx-minus-1">

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>Kode Kendaraan</th>
                        <th>Nama Kendaraan</th>
                        <th>No Polisi</th>
                        <th>STNK</th>
                        <th>Status STNK</th>
                        <th>Pajak</th>
                        <th>Status Pajak</th>
                        <th>KIR</th>
                        <th>Status KIR</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($items)): ?>

                        <?php foreach ($items as $item): ?>

                            <?php
                            $stnkDays = (int) ($item['stnk_remaining_days'] ?? 0);
                            $taxDays = (int) ($item['tax_remaining_days'] ?? 0);
                            $kirDays = (int) ($item['kir_remaining_days'] ?? 0);

                            $stnkClass = $stnkDays < 0
                                ? 'bg-danger bg-opacity-10 text-danger'
                                : ($stnkDays <= 30
                                    ? 'bg-warning bg-opacity-10 text-warning'
                                    : 'bg-success bg-opacity-10 text-success');

                            $taxClass = $taxDays < 0
                                ? 'bg-danger bg-opacity-10 text-danger'
                                : ($taxDays <= 30
                                    ? 'bg-warning bg-opacity-10 text-warning'
                                    : 'bg-success bg-opacity-10 text-success');

                            $kirClass = $kirDays < 0
                                ? 'bg-danger bg-opacity-10 text-danger'
                                : ($kirDays <= 30
                                    ? 'bg-warning bg-opacity-10 text-warning'
                                    : 'bg-success bg-opacity-10 text-success');

                            $stnkLabel = $stnkDays < 0
                                ? 'Expired'
                                : ($stnkDays <= 30 ? 'Segera Habis' : 'Aman');

                            $taxLabel = $taxDays < 0
                                ? 'Expired'
                                : ($taxDays <= 30 ? 'Segera Habis' : 'Aman');

                            $kirLabel = $kirDays < 0
                                ? 'Expired'
                                : ($kirDays <= 30 ? 'Segera Habis' : 'Aman');
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

                                    <?= htmlspecialchars($item['plate_number'] ?? '-') ?>

                                </td>

                                <td>

                                    <?= !empty($item['stnk_expired_date'])
                                        ? date('d M Y', strtotime($item['stnk_expired_date']))
                                        : '-' ?>

                                </td>

                                <td>

                                    <span class="default-badge <?= $stnkClass ?>">
                                        <?= $stnkLabel ?>
                                    </span>

                                    <?php if ($stnkDays >= 0): ?>
                                        <small class="d-block text-muted mt-1">
                                            <?= $stnkDays ?> hari
                                        </small>
                                    <?php endif; ?>

                                </td>

                                <td>

                                    <?= !empty($item['tax_expired_date'])
                                        ? date('d M Y', strtotime($item['tax_expired_date']))
                                        : '-' ?>

                                </td>

                                <td>

                                    <span class="default-badge <?= $taxClass ?>">
                                        <?= $taxLabel ?>
                                    </span>

                                    <?php if ($taxDays >= 0): ?>
                                        <small class="d-block text-muted mt-1">
                                            <?= $taxDays ?> hari
                                        </small>
                                    <?php endif; ?>

                                </td>

                                <td>

                                    <?= !empty($item['kir_expired_date'])
                                        ? date('d M Y', strtotime($item['kir_expired_date']))
                                        : '-' ?>

                                </td>

                                <td>

                                    <span class="default-badge <?= $kirClass ?>">
                                        <?= $kirLabel ?>
                                    </span>

                                    <?php if ($kirDays >= 0): ?>
                                        <small class="d-block text-muted mt-1">
                                            <?= $kirDays ?> hari
                                        </small>
                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                Tidak ada reminder STNK, pajak, atau KIR.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>
