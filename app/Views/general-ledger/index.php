<?php
$runningBalance = 0;
$normalBalance = $selectedAccount['normal_balance'] ?? 'debit';
?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h3 class="mb-1">Buku Besar</h3>
            <p class="mb-0 text-body">Mutasi debit/kredit per akun</p>
        </div>
    </div>

    <form method="GET" class="row g-3">
        <input type="hidden" name="page" value="general-ledger">

        <div class="col-md-6">
            <label>Pilih Akun</label>
            <select name="account_id" class="form-control" onchange="this.form.submit()">
                <option value="">-- Pilih Akun --</option>

                <?php foreach ($accounts as $account): ?>
                    <option 
                        value="<?= $account['id'] ?>"
                        <?= (($selectedAccount['id'] ?? '') == $account['id']) ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($account['account_code']) ?>
                        -
                        <?= htmlspecialchars($account['account_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>

</div>

<?php if (!empty($selectedAccount)): ?>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="mb-1">
            <?= htmlspecialchars($selectedAccount['account_code']) ?>
            -
            <?= htmlspecialchars($selectedAccount['account_name']) ?>
        </h4>
        <p class="mb-0 text-body">
            Normal Balance: <?= htmlspecialchars(ucfirst($normalBalance)) ?>
        </p>
    </div>

    <div class="default-table-area mx-minus-1">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Referensi</th>
                        <th class="text-end">Debit</th>
                        <th class="text-end">Kredit</th>
                        <th class="text-end">Saldo</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($ledger)): ?>
                        <?php foreach ($ledger as $row): ?>
                            <?php
                            $debit = (float)($row['debit'] ?? 0);
                            $credit = (float)($row['credit'] ?? 0);

                            if ($normalBalance === 'debit') {
                                $runningBalance += ($debit - $credit);
                            } else {
                                $runningBalance += ($credit - $debit);
                            }
                            ?>

                            <tr>
                                <td><?= htmlspecialchars($row['journal_date'] ?? '-') ?></td>

                                <td>
                                    <span class="fw-semibold">
                                        <?= htmlspecialchars($row['journal_description'] ?? '-') ?>
                                    </span><br>
                                    <small class="text-body">
                                        <?= htmlspecialchars($row['line_description'] ?? '-') ?>
                                    </small>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['reference_type'] ?? '-') ?>
                                    #<?= htmlspecialchars($row['reference_id'] ?? '-') ?>
                                </td>

                                <td class="text-end">
                                    <?= $debit > 0 ? 'Rp ' . number_format($debit, 0, ',', '.') : '-' ?>
                                </td>

                                <td class="text-end">
                                    <?= $credit > 0 ? 'Rp ' . number_format($credit, 0, ',', '.') : '-' ?>
                                </td>

                                <td class="text-end fw-semibold">
                                    Rp <?= number_format($runningBalance, 0, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-body">
                                Belum ada mutasi jurnal untuk akun ini.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>

            </table>
        </div>
    </div>

</div>

<?php endif; ?>