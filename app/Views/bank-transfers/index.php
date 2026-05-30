<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>

            <h3 class="mb-0">
                Transfer Antar Rekening
            </h3>

            <p class="text-body fs-14 mb-0">
                Data transfer saldo antar rekening kas dan bank
            </p>

        </div>

        <a
            href="<?= url('bank-transfers-create') ?>"
            class="btn btn-primary text-white erp-btn"
        >
            + Tambah Transfer
        </a>

    </div>

    <div class="default-table-area mx-minus-1">

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Dari Rekening</th>
                        <th>Ke Rekening</th>
                        <th>No Referensi</th>
                        <th style="min-width:220px;">Catatan</th>
                        <th class="text-end">Nominal</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($transfers)): ?>

                        <?php foreach ($transfers as $transfer): ?>

                            <tr>

                                <td>

                                    <a
                                        href="<?= url('bank-transfers-show') ?>?id=<?= $transfer['id'] ?>"
                                        class="fw-semibold text-primary text-decoration-none"
                                    >
                                        <?= !empty($transfer['transfer_date'])
                                            ? date('d M Y', strtotime($transfer['transfer_date']))
                                            : '-' ?>
                                    </a>

                                </td>

                                <td>

                                    <span class="fw-semibold">
                                        <?= htmlspecialchars($transfer['from_account_code'] ?? '-') ?>
                                    </span>

                                    <br>

                                    <small class="text-muted">
                                        <?= htmlspecialchars($transfer['from_account_name'] ?? '-') ?>
                                    </small>

                                </td>

                                <td>

                                    <span class="fw-semibold">
                                        <?= htmlspecialchars($transfer['to_account_code'] ?? '-') ?>
                                    </span>

                                    <br>

                                    <small class="text-muted">
                                        <?= htmlspecialchars($transfer['to_account_name'] ?? '-') ?>
                                    </small>

                                </td>

                                <td>

                                    <?= htmlspecialchars($transfer['reference_no'] ?? '-') ?>

                                </td>

                                <td class="text-wrap" style="min-width:220px; max-width:320px;">

                                    <?= htmlspecialchars($transfer['notes'] ?? '-') ?>

                                </td>

                                <td class="text-end fw-semibold text-success">

                                    Rp <?= number_format((float) ($transfer['amount'] ?? 0), 0, ',', '.') ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="6" class="text-center text-body py-4">
                                Belum ada transfer antar rekening.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>