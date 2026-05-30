<?php
$totalIn = 0;
$totalOut = 0;
?>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>
            <h3 class="mb-0">Laporan Arus Kas</h3>

            <p class="text-body fs-14 mb-0">
                Periode <?= htmlspecialchars($startDate) ?>
                s/d
                <?= htmlspecialchars($endDate) ?>
            </p>
        </div>

    </div>

    <div class="p-20 border-top">

        <form method="GET" class="row g-3 align-items-end">

            <input type="hidden" name="page" value="cash-flow">

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

                    <a href="<?= url('cash-flow') ?>" class="btn btn-light erp-btn">
                        Reset
                    </a>

                </div>

            </div>

        </form>

    </div>

</div>

<?php foreach ($transactions as $trx): ?>
    <?php
    $amount = (float)($trx['amount'] ?? 0);

    if (($trx['transaction_type'] ?? '') === 'in') {
        $totalIn += $amount;
    } else {
        $totalOut += $amount;
    }
    ?>
<?php endforeach; ?>

<div class="row mb-4">

    <div class="col-md-4">
        <div class="card bg-white rounded-10 border border-white p-20">
            <span class="text-body">Kas Masuk</span>
            <h3 class="mt-2 mb-0 text-success">
                Rp <?= number_format($totalIn, 0, ',', '.') ?>
            </h3>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-white rounded-10 border border-white p-20">
            <span class="text-body">Kas Keluar</span>
            <h3 class="mt-2 mb-0 text-danger">
                Rp <?= number_format($totalOut, 0, ',', '.') ?>
            </h3>
        </div>
    </div>

    <div class="col-md-4">
        <?php $netCash = $totalIn - $totalOut; ?>

        <div class="card bg-white rounded-10 border border-white p-20">
            <span class="text-body">Net Cash Flow</span>
            <h3 class="mt-2 mb-0 <?= $netCash >= 0 ? 'text-success' : 'text-danger' ?>">
                Rp <?= number_format($netCash, 0, ',', '.') ?>
            </h3>
        </div>
    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="mb-0">Detail Arus Kas</h4>
    </div>

    <div class="default-table-area mx-minus-1">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Rekening</th>
                        <th>Keterangan</th>
                        <th>Referensi</th>
                        <th class="text-end">Masuk</th>
                        <th class="text-end">Keluar</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($transactions)): ?>
                        <?php foreach ($transactions as $trx): ?>
                            <?php
                            $type = $trx['transaction_type'] ?? 'in';
                            $amount = (float)($trx['amount'] ?? 0);

                            $refType = $trx['reference_type'] ?? '';
                            $refId = $trx['reference_id'] ?? '';
                            $refUrl = '#';

                            if ($refType === 'invoice_payment') {
                                $refUrl = url('invoices-show') . '?id=' . $refId;
                            } elseif ($refType === 'expense') {
                                $refUrl = url('expenses-show') . '?id=' . $refId;
                            } elseif ($refType === 'bank_transfer') {
                                $refUrl = url('bank-transfers-show') . '?id=' . $refId;
                            } elseif ($refType === 'vendor_bill') {
                                $refUrl = url('vendor-bills-show') . '?id=' . $refId;

                            } elseif ($refType === 'vendor_bill_payment') {
                                $refUrl = url('vendor-bills-show') . '?id=' . $refId;
                            }
                            ?>

                            <tr>
                                <td><?= htmlspecialchars($trx['transaction_date'] ?? '-') ?></td>

                                <td>
                                    <span class="fw-semibold">
                                        <?= htmlspecialchars($trx['account_code'] ?? '-') ?>
                                        -
                                        <?= htmlspecialchars($trx['account_name'] ?? '-') ?>
                                    </span><br>
                                    <small class="text-body">
                                        <?= htmlspecialchars($trx['bank_name'] ?? '-') ?>
                                        <?= htmlspecialchars($trx['account_number'] ?? '') ?>
                                    </small>
                                </td>

                                <td><?= htmlspecialchars($trx['description'] ?? '-') ?></td>

                                <td>
                                    <a 
                                    href="<?= $refUrl ?>"
                                    class="<?= $refUrl !== '#' ? 'text-primary' : 'text-body' ?> fw-semibold text-decoration-none"
                                    >
                                    <?= htmlspecialchars($refType ?: '-') ?>
                                    #<?= htmlspecialchars($refId ?: '-') ?>
                                </a>
                            </td>

                            <td class="text-end fw-semibold text-success">
                                <?= $type === 'in'
                                ? 'Rp ' . number_format($amount, 0, ',', '.')
                                : '-'
                                ?>
                            </td>

                            <td class="text-end fw-semibold text-danger">
                                <?= $type === 'out'
                                ? 'Rp ' . number_format($amount, 0, ',', '.')
                                : '-'
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-body">
                            Belum ada transaksi arus kas pada periode ini.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>

        </table>
    </div>
</div>

</div>