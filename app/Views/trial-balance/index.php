<?php
$totalDebit = 0;
$totalCredit = 0;
?>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">
        <div>
            <h3 class="mb-1">Neraca Saldo</h3>
            <p class="mb-0 text-body">Ringkasan saldo debit dan kredit setiap akun</p>
        </div>
    </div>

    <div class="default-table-area mx-minus-1">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Kode Akun</th>
                        <th>Nama Akun</th>
                        <th>Tipe</th>
                        <th class="text-end">Debit</th>
                        <th class="text-end">Kredit</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($trialBalance)): ?>
                        <?php foreach ($trialBalance as $row): ?>
                            <?php
                            $debit = (float)($row['total_debit'] ?? 0);
                            $credit = (float)($row['total_credit'] ?? 0);

                            $totalDebit += $debit;
                            $totalCredit += $credit;
                            ?>

                            <tr>
                                <td><?= htmlspecialchars($row['account_code'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($row['account_name'] ?? '-') ?></td>
                                <td><?= htmlspecialchars(ucfirst($row['account_type'] ?? '-')) ?></td>

                                <td class="text-end">
                                    <?= $debit > 0 ? 'Rp ' . number_format($debit, 0, ',', '.') : '-' ?>
                                </td>

                                <td class="text-end">
                                    <?= $credit > 0 ? 'Rp ' . number_format($credit, 0, ',', '.') : '-' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <tr>
                            <td colspan="3" class="text-end fw-bold">Total</td>
                            <td class="text-end fw-bold">
                                Rp <?= number_format($totalDebit, 0, ',', '.') ?>
                            </td>
                            <td class="text-end fw-bold">
                                Rp <?= number_format($totalCredit, 0, ',', '.') ?>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="5" class="text-end">
                                <?php if (round($totalDebit, 2) === round($totalCredit, 2)): ?>
                                    <span class="default-badge bg-success bg-opacity-10 text-success">
                                        Balance
                                    </span>
                                <?php else: ?>
                                    <span class="default-badge bg-danger bg-opacity-10 text-danger">
                                        Tidak Balance
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-body">
                                Belum ada data neraca saldo.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>

            </table>
        </div>
    </div>

</div>