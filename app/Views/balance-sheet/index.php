<?php
$totalAssets = 0;
$totalLiabilities = 0;
$totalEquity = 0;

function balanceAmount($row)
{
    $debit = (float)($row['total_debit'] ?? 0);
    $credit = (float)($row['total_credit'] ?? 0);

    return ($row['normal_balance'] ?? 'debit') === 'debit'
        ? ($debit - $credit)
        : ($credit - $debit);
}
?>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>
            <h3 class="mb-0">Laporan Neraca</h3>

            <p class="text-body fs-14 mb-0">
                Per <?= htmlspecialchars($endDate) ?>
            </p>
        </div>

    </div>

    <div class="p-20 border-top">

        <form method="GET" class="row g-3 align-items-end">

            <input type="hidden" name="page" value="balance-sheet">

            <div class="col-md-3">
                <label class="form-label">Tanggal</label>

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

                    <a href="<?= url('balance-sheet') ?>" class="btn btn-light erp-btn">
                        Reset
                    </a>

                </div>
            </div>

        </form>

    </div>

</div>

<div class="row">

    <!-- ASSETS -->
    <div class="col-md-4">

        <div class="card bg-white rounded-10 border border-white mb-4">

            <div class="p-20 border-bottom">
                <h4 class="mb-0">Aset</h4>
            </div>

            <div class="default-table-area mx-minus-1">
                <div class="table-responsive">

                    <table class="table align-middle">

                        <tbody>

                            <?php foreach ($assets as $row): ?>

                                <?php
                                $amount = balanceAmount($row);
                                $totalAssets += $amount;
                                ?>

                                <tr>
                                    <td>
                                        <?= htmlspecialchars($row['account_code']) ?>
                                        <br>
                                        <small class="text-body">
                                            <?= htmlspecialchars($row['account_name']) ?>
                                        </small>
                                    </td>

                                    <td class="text-end fw-semibold">
                                        <?= $amount != 0
                                            ? 'Rp ' . number_format($amount, 0, ',', '.')
                                            : '-'
                                        ?>
                                    </td>
                                </tr>

                            <?php endforeach; ?>

                            <tr>
                                <td class="fw-bold">Total Aset</td>

                                <td class="text-end fw-bold">
                                    Rp <?= number_format($totalAssets, 0, ',', '.') ?>
                                </td>
                            </tr>

                        </tbody>

                    </table>

                </div>
            </div>

        </div>

    </div>

    <!-- LIABILITIES -->
    <div class="col-md-4">

        <div class="card bg-white rounded-10 border border-white mb-4">

            <div class="p-20 border-bottom">
                <h4 class="mb-0">Kewajiban</h4>
            </div>

            <div class="default-table-area mx-minus-1">
                <div class="table-responsive">

                    <table class="table align-middle">

                        <tbody>

                            <?php foreach ($liabilities as $row): ?>

                                <?php
                                $amount = balanceAmount($row);
                                $totalLiabilities += $amount;
                                ?>

                                <tr>
                                    <td>
                                        <?= htmlspecialchars($row['account_code']) ?>
                                        <br>
                                        <small class="text-body">
                                            <?= htmlspecialchars($row['account_name']) ?>
                                        </small>
                                    </td>

                                    <td class="text-end fw-semibold">
                                        <?= $amount != 0
                                            ? 'Rp ' . number_format($amount, 0, ',', '.')
                                            : '-'
                                        ?>
                                    </td>
                                </tr>

                            <?php endforeach; ?>

                            <tr>
                                <td class="fw-bold">Total Kewajiban</td>

                                <td class="text-end fw-bold">
                                    Rp <?= number_format($totalLiabilities, 0, ',', '.') ?>
                                </td>
                            </tr>

                        </tbody>

                    </table>

                </div>
            </div>

        </div>

    </div>

    <!-- EQUITY -->
    <div class="col-md-4">

        <div class="card bg-white rounded-10 border border-white mb-4">

            <div class="p-20 border-bottom">
                <h4 class="mb-0">Modal</h4>
            </div>

            <div class="default-table-area mx-minus-1">
                <div class="table-responsive">

                    <table class="table align-middle">

                        <tbody>

                            <?php foreach ($equities as $row): ?>

                                <?php
                                $amount = balanceAmount($row);
                                $totalEquity += $amount;
                                ?>

                                <tr>
                                    <td>
                                        <?= htmlspecialchars($row['account_code']) ?>
                                        <br>
                                        <small class="text-body">
                                            <?= htmlspecialchars($row['account_name']) ?>
                                        </small>
                                    </td>

                                    <td class="text-end fw-semibold">
                                        <?= $amount != 0
                                            ? 'Rp ' . number_format($amount, 0, ',', '.')
                                            : '-'
                                        ?>
                                    </td>
                                </tr>

                            <?php endforeach; ?>

                            <tr>
                                <td>
                                    Laba Berjalan
                                </td>

                                <td class="text-end fw-semibold text-success">
                                    Rp <?= number_format($profit, 0, ',', '.') ?>
                                </td>
                            </tr>

                            <?php
                            $grandEquity = $totalEquity + $profit;
                            ?>

                            <tr>
                                <td class="fw-bold">Total Modal</td>

                                <td class="text-end fw-bold">
                                    Rp <?= number_format($grandEquity, 0, ',', '.') ?>
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
$totalRightSide = $totalLiabilities + $grandEquity;
$isBalanced = round($totalAssets, 2) === round($totalRightSide, 2);
?>

<div class="card bg-white rounded-10 border border-white p-20">

    <div class="row align-items-center">

        <div class="col-md-4">
            <h5>Total Aset</h5>
            <h3>
                Rp <?= number_format($totalAssets, 0, ',', '.') ?>
            </h3>
        </div>

        <div class="col-md-4">
            <h5>Total Kewajiban + Modal</h5>
            <h3>
                Rp <?= number_format($totalRightSide, 0, ',', '.') ?>
            </h3>
        </div>

        <div class="col-md-4 text-md-end">
            <?php if ($isBalanced): ?>

                <span class="default-badge bg-success bg-opacity-10 text-success">
                    Neraca Balance
                </span>

            <?php else: ?>

                <span class="default-badge bg-danger bg-opacity-10 text-danger">
                    Neraca Tidak Balance
                </span>

            <?php endif; ?>
        </div>

    </div>

</div>