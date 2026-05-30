<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>

            <h3 class="mb-0">
                Kas & Bank
            </h3>

            <p class="text-body fs-14 mb-0">
                Data rekening kas dan bank perusahaan
            </p>

        </div>

        <a
            href="<?= url('bank-accounts-create') ?>"
            class="btn btn-primary text-white erp-btn"
        >
            + Tambah Rekening
        </a>

    </div>

    <div class="default-table-area mx-minus-1">

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>Kode Akun</th>
                        <th>Nama Akun</th>
                        <th class="text-end">Saldo Awal</th>
                        <th class="text-end">Saldo Saat Ini</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($accounts)): ?>

                        <?php foreach ($accounts as $account): ?>

                            <tr>

                                <td>

                                    <?= htmlspecialchars($account['account_code'] ?? '-') ?>

                                </td>

                                <td>

                                    <a
                                        href="<?= url('bank-transactions') ?>?bank_account_id=<?= $account['id'] ?>"
                                        class="fw-semibold text-primary text-decoration-none"
                                    >
                                        <?= htmlspecialchars($account['account_name'] ?? '-') ?>
                                    </a>

                                </td>

                                <td class="text-end">

                                    Rp <?= number_format((float) ($account['opening_balance'] ?? 0), 0, ',', '.') ?>

                                </td>

                                <td class="text-end fw-semibold text-success">

                                    Rp <?= number_format((float) ($account['current_balance'] ?? 0), 0, ',', '.') ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="4" class="text-center text-body py-4">
                                Belum ada rekening bank.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>