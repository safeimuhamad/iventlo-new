<?php
$totalDebit = 0;
$totalCredit = 0;

foreach ($lines as $line) {
    $totalDebit += (float) ($line['debit'] ?? 0);
    $totalCredit += (float) ($line['credit'] ?? 0);
}

$refType = $journal['reference_type'] ?? '';
$refId = $journal['reference_id'] ?? '';

$refUrl = '#';

if ($refType === 'invoice') {
    $refUrl = url('invoices-show') . '?id=' . $refId;
} elseif ($refType === 'invoice_payment') {
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

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Detail Jurnal
            </h3>

            <p class="mb-0 text-body">

                <a
                    href="<?= $refUrl ?>"
                    class="<?= $refUrl !== '#' ? 'text-primary' : 'text-body' ?> text-decoration-none"
                >
                    <?= htmlspecialchars($refType ?: '-') ?>
                    #<?= htmlspecialchars($refId ?: '-') ?>
                </a>

            </p>

        </div>

        <a
            href="<?= url('journal-entries') ?>"
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
                Tanggal Jurnal
            </div>

            <div class="erp-detail-value">
                <?= !empty($journal['journal_date'])
                    ? date('d M Y', strtotime($journal['journal_date']))
                    : '-' ?>
            </div>

        </div>

        <div class="col-md-3">

            <div class="erp-detail-label">
                Referensi
            </div>

            <div class="erp-detail-value">

                <a
                    href="<?= $refUrl ?>"
                    class="<?= $refUrl !== '#' ? 'text-primary' : 'text-body' ?> text-decoration-none"
                >
                    <?= htmlspecialchars($refType ?: '-') ?>
                    #<?= htmlspecialchars($refId ?: '-') ?>
                </a>

            </div>

        </div>

        <div class="col-md-3">

            <div class="erp-detail-label">
                Total Debit
            </div>

            <div class="erp-detail-value text-success">
                Rp <?= number_format($totalDebit, 0, ',', '.') ?>
            </div>

        </div>

        <div class="col-md-3">

            <div class="erp-detail-label">
                Total Kredit
            </div>

            <div class="erp-detail-value text-danger">
                Rp <?= number_format($totalCredit, 0, ',', '.') ?>
            </div>

        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">

        <h4 class="erp-detail-section-title">
            Informasi Jurnal
        </h4>

    </div>

    <div class="p-20">

        <div class="row g-4">

            <div class="col-md-4">

                <div class="erp-detail-label">
                    Tanggal Jurnal
                </div>

                <div class="erp-detail-value">
                    <?= !empty($journal['journal_date'])
                        ? date('d M Y', strtotime($journal['journal_date']))
                        : '-' ?>
                </div>

            </div>

            <div class="col-md-4">

                <div class="erp-detail-label">
                    Referensi
                </div>

                <div class="erp-detail-value">

                    <a
                        href="<?= $refUrl ?>"
                        class="<?= $refUrl !== '#' ? 'text-primary' : 'text-body' ?> text-decoration-none"
                    >
                        <?= htmlspecialchars($refType ?: '-') ?>
                        #<?= htmlspecialchars($refId ?: '-') ?>
                    </a>

                </div>

            </div>

            <div class="col-md-4">

                <div class="erp-detail-label">
                    Total Debit
                </div>

                <div class="erp-detail-value text-success">
                    Rp <?= number_format($totalDebit, 0, ',', '.') ?>
                </div>

            </div>

            <div class="col-md-12">

                <div class="erp-detail-label">
                    Keterangan
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($journal['description'] ?? '-') ?>
                </div>

            </div>

        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">

        <h4 class="erp-detail-section-title">
            Detail Jurnal
        </h4>

    </div>

    <div class="default-table-area mx-minus-1">

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead>
                    <tr>
                        <th>Kode Akun</th>
                        <th>Nama Akun</th>
                        <th>Deskripsi</th>
                        <th class="text-end">Debit</th>
                        <th class="text-end">Kredit</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($lines)): ?>

                        <?php foreach ($lines as $line): ?>

                            <tr>

                                <td>
                                    <?= htmlspecialchars($line['account_code'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($line['account_name'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($line['description'] ?? '-') ?>
                                </td>

                                <td class="text-end fw-semibold">

                                    <?= ((float) ($line['debit'] ?? 0) > 0)
                                        ? 'Rp ' . number_format((float) $line['debit'], 0, ',', '.')
                                        : '-' ?>

                                </td>

                                <td class="text-end fw-semibold">

                                    <?= ((float) ($line['credit'] ?? 0) > 0)
                                        ? 'Rp ' . number_format((float) $line['credit'], 0, ',', '.')
                                        : '-' ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                        <tr class="table-light">

                            <td colspan="3" class="text-end fw-bold">
                                TOTAL
                            </td>

                            <td class="text-end fw-bold text-success">
                                Rp <?= number_format($totalDebit, 0, ',', '.') ?>
                            </td>

                            <td class="text-end fw-bold text-danger">
                                Rp <?= number_format($totalCredit, 0, ',', '.') ?>
                            </td>

                        </tr>

                    <?php else: ?>

                        <tr>
                            <td colspan="5" class="text-center text-body py-4">
                                Belum ada detail jurnal.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>