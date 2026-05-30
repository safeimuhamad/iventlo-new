<?php
$totalIn = 0;
$totalOut = 0;

foreach (($transactions ?? []) as $trx) {
    $type = $trx['transaction_type'] ?? 'in';
    $amount = (float) ($trx['amount'] ?? 0);

    if ($type === 'in') {
        $totalIn += $amount;
    } else {
        $totalOut += $amount;
    }
}

$currentBalance = (float) ($account['current_balance'] ?? 0);
?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Detail Mutasi Kas / Bank
            </h3>

            <p class="mb-0 text-body">
                <?= htmlspecialchars($account['account_code'] ?? '-') ?>
                -
                <?= htmlspecialchars($account['account_name'] ?? '-') ?>
            </p>
        </div>

        <a
            href="<?= url('bank-accounts') ?>"
            class="btn btn-light erp-btn"
        >
            <i class="ri-arrow-left-line me-1"></i>
            Kembali
        </a>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="row g-4">

        <div class="col-md-3">
            <div class="erp-detail-label">
                Rekening
            </div>

            <div class="erp-detail-value">
                <?= htmlspecialchars($account['account_name'] ?? '-') ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">
                Total Masuk
            </div>

            <div class="erp-detail-value text-success">
                Rp <?= number_format($totalIn, 0, ',', '.') ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">
                Total Keluar
            </div>

            <div class="erp-detail-value text-danger">
                Rp <?= number_format($totalOut, 0, ',', '.') ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">
                Saldo Saat Ini
            </div>

            <div class="erp-detail-value text-primary">
                Rp <?= number_format($currentBalance, 0, ',', '.') ?>
            </div>
        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title">
            Informasi Rekening
        </h4>
    </div>

    <div class="p-20">

        <div class="row g-4">

            <div class="col-md-3">
                <div class="erp-detail-label">
                    Kode Akun
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($account['account_code'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">
                    Nama Akun
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($account['account_name'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">
                    Bank
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($account['bank_name'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">
                    No Rekening
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($account['account_number'] ?? '-') ?>
                </div>
            </div>

        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title">
            Riwayat Transaksi Rekening
        </h4>
    </div>

    <div class="default-table-area mx-minus-1">

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Rekening</th>
                        <th>Bank</th>
                        <th>No Rekening</th>
                        <th>Keterangan</th>
                        <th>Referensi</th>
                        <th>Tipe</th>
                        <th class="text-end">Masuk</th>
                        <th class="text-end">Keluar</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($transactions)): ?>

                        <?php foreach ($transactions as $trx): ?>

                            <?php
                            $type = $trx['transaction_type'] ?? 'in';
                            $amount = (float) ($trx['amount'] ?? 0);

                            $typeClass = $type === 'in'
                                ? 'bg-success bg-opacity-10 text-success'
                                : 'bg-danger bg-opacity-10 text-danger';

                            $typeLabel = $type === 'in' ? 'Masuk' : 'Keluar';

                            $refType = $trx['reference_type'] ?? '';
                            $refId = $trx['reference_id'] ?? '';

                            $refUrl = '#';

                            if ($refType === 'expense') {
                                $refUrl = url('expenses-show') . '?id=' . $refId;
                            } elseif ($refType === 'invoice_payment') {
                                $refUrl = url('invoices-show') . '?id=' . $refId;
                            } elseif ($refType === 'bank_transfer') {
                                $refUrl = url('bank-transfers-show') . '?id=' . $refId;
                            } elseif ($refType === 'vendor_bill') {
                                $refUrl = url('vendor-bills-show') . '?id=' . $refId;
                            } elseif ($refType === 'vendor_bill_payment') {
                                $refUrl = url('vendor-bills-show') . '?id=' . $refId;
                            }
                            ?>

                            <tr>
                                <td>
                                    <?= !empty($trx['transaction_date'])
                                        ? date('d M Y', strtotime($trx['transaction_date']))
                                        : '-' ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($trx['account_name'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($trx['bank_name'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($trx['account_number'] ?? '-') ?>
                                </td>

                                <td class="text-wrap" style="min-width:220px; max-width:420px;">
                                    <?= htmlspecialchars($trx['description'] ?? '-') ?>
                                </td>

                                <td>
                                    <?php if ($refUrl !== '#'): ?>
                                        <a
                                            href="<?= $refUrl ?>"
                                            class="text-primary text-decoration-none fw-semibold"
                                        >
                                            <?= htmlspecialchars($refType ?: '-') ?>
                                            #<?= htmlspecialchars($refId ?: '-') ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-body">
                                            <?= htmlspecialchars($refType ?: '-') ?>
                                            #<?= htmlspecialchars($refId ?: '-') ?>
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <span class="default-badge <?= $typeClass ?>">
                                        <?= $typeLabel ?>
                                    </span>
                                </td>

                                <td class="text-end fw-semibold text-success">
                                    <?= $type === 'in'
                                        ? 'Rp ' . number_format($amount, 0, ',', '.')
                                        : '-' ?>
                                </td>

                                <td class="text-end fw-semibold text-danger">
                                    <?= $type === 'out'
                                        ? 'Rp ' . number_format($amount, 0, ',', '.')
                                        : '-' ?>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                        <tr>
                            <td colspan="7" class="text-end fw-bold border-top">
                                Total
                            </td>

                            <td class="text-end fw-bold text-success border-top">
                                Rp <?= number_format($totalIn, 0, ',', '.') ?>
                            </td>

                            <td class="text-end fw-bold text-danger border-top">
                                Rp <?= number_format($totalOut, 0, ',', '.') ?>
                            </td>
                        </tr>

                    <?php else: ?>

                        <tr>
                            <td colspan="9" class="text-center text-body py-4">
                                Belum ada mutasi kas/bank.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>