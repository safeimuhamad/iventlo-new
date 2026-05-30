<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>

            <h3 class="mb-0">
                Chart of Accounts
            </h3>

            <p class="text-body fs-14 mb-0">
                Data master akun keuangan perusahaan
            </p>

        </div>

        <a
            href="<?= url('chart-of-accounts-create') ?>"
            class="btn btn-primary text-white erp-btn"
        >
            + Tambah Akun
        </a>

    </div>

    <div class="default-table-area mx-minus-1">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Kode Akun</th>
                        <th>Nama Akun</th>
                        <th>Tipe</th>
                        <th>Normal Balance</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($accounts)): ?>
                        <?php foreach ($accounts as $account): ?>
                            <tr>
                                <td class="fw-semibold"><?= htmlspecialchars($account['account_code'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($account['account_name'] ?? '-') ?></td>
                                <td><?= htmlspecialchars(ucfirst($account['account_type'] ?? '-')) ?></td>
                                <td><?= htmlspecialchars(ucfirst($account['normal_balance'] ?? '-')) ?></td>

                                <td class="text-end">
                                    <a href="<?= url('chart-of-accounts-edit') ?>?id=<?= $account['id'] ?>"
                                       class="text-primary me-2">
                                        <i class="ri-edit-line"></i>
                                    </a>

                                    <a href="<?= url('chart-of-accounts-delete') ?>?id=<?= $account['id'] ?>"
                                       class="text-danger"
                                       onclick="return confirm('Yakin ingin menonaktifkan akun ini?')">
                                        <i class="ri-delete-bin-line"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-body">
                                Belum ada akun.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>

            </table>
        </div>
    </div>

</div>