<?php
$totalIncome = 0;
$totalExpense = 0;
?>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>
            <h3 class="mb-0">Laporan Laba Rugi</h3>

            <p class="text-body fs-14 mb-0">
                Periode <?= htmlspecialchars($startDate) ?>
                s/d
                <?= htmlspecialchars($endDate) ?>
            </p>
        </div>

    </div>

    <div class="p-20 border-top">

        <form method="GET" class="row g-3 align-items-end">

            <input type="hidden" name="page" value="profit-loss">

            <div class="col-md-3">

                <label class="form-label">Tanggal Mulai</label>

                <input
                    type="date"
                    name="start_date"
                    class="form-control erp-control erp-input"
                    value="<?= htmlspecialchars($startDate) ?>"
                >

            </div>

            <div class="col-md-3">

                <label class="form-label">Tanggal Selesai</label>

                <input
                    type="date"
                    name="end_date"
                    class="form-control erp-control erp-input"
                    value="<?= htmlspecialchars($endDate) ?>"
                >

            </div>

            <div class="col-md-2">

                <div class="d-flex gap-2 filter-action-group">

                    <button class="btn btn-primary text-white erp-btn w-100">
                        Filter
                    </button>

                    <a href="<?= url('profit-loss') ?>" class="btn btn-light erp-btn">
                        Reset
                    </a>

                </div>

            </div>

        </form>

    </div>

</div>

<div class="row">

    <div class="col-md-6">

        <div class="card bg-white rounded-10 border border-white mb-4">

            <div class="p-20 border-bottom">
                <h4 class="mb-0">Pendapatan</h4>
            </div>

            <div class="default-table-area mx-minus-1">
                <div class="table-responsive">

                    <table class="table align-middle">

                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Akun</th>
                                <th class="text-end">Nominal</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($incomeAccounts as $account): ?>

                                <?php
                                $amount = (float)($account['total_credit'] ?? 0)
                                        - (float)($account['total_debit'] ?? 0);

                                $totalIncome += $amount;
                                ?>

                                <tr>
                                    <td><?= htmlspecialchars($account['account_code']) ?></td>

                                    <td><?= htmlspecialchars($account['account_name']) ?></td>

                                    <td class="text-end fw-semibold text-success">
                                        <?= $amount > 0
                                            ? 'Rp ' . number_format($amount, 0, ',', '.')
                                            : '-'
                                        ?>
                                    </td>
                                </tr>

                            <?php endforeach; ?>

                            <tr>
                                <td colspan="2" class="text-end fw-bold">
                                    Total Pendapatan
                                </td>

                                <td class="text-end fw-bold text-success">
                                    Rp <?= number_format($totalIncome, 0, ',', '.') ?>
                                </td>
                            </tr>

                        </tbody>

                    </table>

                </div>
            </div>

        </div>

    </div>

    <div class="col-md-6">

        <div class="card bg-white rounded-10 border border-white mb-4">

            <div class="p-20 border-bottom">
                <h4 class="mb-0">Beban</h4>
            </div>

            <div class="default-table-area mx-minus-1">
                <div class="table-responsive">

                    <table class="table align-middle">

                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Akun</th>
                                <th class="text-end">Nominal</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($expenseAccounts as $account): ?>

                                <?php
                                $amount = (float)($account['total_debit'] ?? 0)
                                        - (float)($account['total_credit'] ?? 0);

                                $totalExpense += $amount;
                                ?>

                                <tr>
                                    <td><?= htmlspecialchars($account['account_code']) ?></td>

                                    <td><?= htmlspecialchars($account['account_name']) ?></td>

                                    <td class="text-end fw-semibold text-danger">
                                        <?= $amount > 0
                                            ? 'Rp ' . number_format($amount, 0, ',', '.')
                                            : '-'
                                        ?>
                                    </td>
                                </tr>

                            <?php endforeach; ?>

                            <tr>
                                <td colspan="2" class="text-end fw-bold">
                                    Total Beban
                                </td>

                                <td class="text-end fw-bold text-danger">
                                    Rp <?= number_format($totalExpense, 0, ',', '.') ?>
                                </td>
                            </tr>

                        </tbody>

                    </table>

                </div>
            </div>

        </div>

    </div>

</div>

<?php
$netProfit = $totalIncome - $totalExpense;
?>

<div class="card bg-white rounded-10 border border-white p-20">

    <div class="d-flex justify-content-between align-items-center">

        <h3 class="mb-0">
            Laba Bersih
        </h3>

        <h3 class="<?= $netProfit >= 0 ? 'text-success' : 'text-danger' ?> mb-0">
            Rp <?= number_format($netProfit, 0, ',', '.') ?>
        </h3>

    </div>

</div>