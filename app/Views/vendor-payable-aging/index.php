<?php
$total0to30 = 0;
$total31to60 = 0;
$total61to90 = 0;
$total90plus = 0;
$grandOutstanding = 0;

$agingRows = [];

foreach (($bills ?? []) as $bill) {
    $remaining = (float) ($bill['remaining_amount'] ?? 0);

    $dueDate = !empty($bill['due_date'])
        ? strtotime($bill['due_date'])
        : strtotime($bill['bill_date'] ?? date('Y-m-d'));

    $today = strtotime(date('Y-m-d'));

    $agingDays = floor(($today - $dueDate) / 86400);

    if ($agingDays < 0) {
        $agingDays = 0;
    }

    $bucket0to30 = 0;
    $bucket31to60 = 0;
    $bucket61to90 = 0;
    $bucket90plus = 0;

    if ($agingDays <= 30) {
        $bucket0to30 = $remaining;
        $total0to30 += $remaining;
    } elseif ($agingDays <= 60) {
        $bucket31to60 = $remaining;
        $total31to60 += $remaining;
    } elseif ($agingDays <= 90) {
        $bucket61to90 = $remaining;
        $total61to90 += $remaining;
    } else {
        $bucket90plus = $remaining;
        $total90plus += $remaining;
    }

    $grandOutstanding += $remaining;

    $agingRows[] = [
        'bill' => $bill,
        'aging_days' => $agingDays,
        'bucket_0_30' => $bucket0to30,
        'bucket_31_60' => $bucket31to60,
        'bucket_61_90' => $bucket61to90,
        'bucket_90_plus' => $bucket90plus,
        'remaining' => $remaining,
    ];
}
?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Aging Hutang Vendor
            </h3>

            <p class="mb-0 text-body">
                Monitoring umur hutang supplier / vendor
            </p>
        </div>

    </div>

</div>

<div class="row g-3 mb-4">

    <div class="col-md-3">
        <div class="card bg-white rounded-10 border border-white p-20 h-100">
            <div class="erp-detail-label">
                0 - 30 Hari
            </div>

            <div class="erp-detail-value text-primary">
                Rp <?= number_format($total0to30, 0, ',', '.') ?>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-white rounded-10 border border-white p-20 h-100">
            <div class="erp-detail-label">
                31 - 60 Hari
            </div>

            <div class="erp-detail-value text-warning">
                Rp <?= number_format($total31to60, 0, ',', '.') ?>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-white rounded-10 border border-white p-20 h-100">
            <div class="erp-detail-label">
                61 - 90 Hari
            </div>

            <div class="erp-detail-value text-danger">
                Rp <?= number_format($total61to90, 0, ',', '.') ?>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-white rounded-10 border border-white p-20 h-100">
            <div class="erp-detail-label">
                90+ Hari
            </div>

            <div class="erp-detail-value text-danger">
                Rp <?= number_format($total90plus, 0, ',', '.') ?>
            </div>
        </div>
    </div>

</div>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="row g-4">

        <div class="col-md-3">
            <div class="erp-detail-label">
                Total Outstanding
            </div>

            <div class="erp-detail-value text-danger">
                Rp <?= number_format($grandOutstanding, 0, ',', '.') ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">
                Total Bill
            </div>

            <div class="erp-detail-value">
                <?= number_format(count($agingRows), 0, ',', '.') ?>
            </div>
        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title">
            Detail Aging Hutang Vendor
        </h4>
    </div>

    <div class="default-table-area mx-minus-1">

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead>
                    <tr>
                        <th>Vendor</th>
                        <th>No Bill</th>
                        <th>Tanggal Bill</th>
                        <th>Jatuh Tempo</th>
                        <th class="text-center">Umur</th>
                        <th class="text-end">0-30 Hari</th>
                        <th class="text-end">31-60 Hari</th>
                        <th class="text-end">61-90 Hari</th>
                        <th class="text-end">90+ Hari</th>
                        <th class="text-end">Outstanding</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($agingRows)): ?>

                        <?php foreach ($agingRows as $row): ?>

                            <?php
                            $bill = $row['bill'];
                            $agingDays = $row['aging_days'];
                            ?>

                            <tr>

                                <td>
                                    <div class="fw-semibold">
                                        <?= htmlspecialchars($bill['vendor_name'] ?? '-') ?>
                                    </div>

                                    <small class="text-body">
                                        <?= htmlspecialchars($bill['vendor_code'] ?? '-') ?>
                                    </small>
                                </td>

                                <td>
                                    <a
                                        href="<?= url('vendor-bills-show') ?>?id=<?= $bill['id'] ?>"
                                        class="text-primary fw-semibold text-decoration-none"
                                    >
                                        <?= htmlspecialchars($bill['bill_no'] ?? '-') ?>
                                    </a>
                                </td>

                                <td>
                                    <?= !empty($bill['bill_date'])
                                        ? date('d M Y', strtotime($bill['bill_date']))
                                        : '-' ?>
                                </td>

                                <td>
                                    <?= !empty($bill['due_date'])
                                        ? date('d M Y', strtotime($bill['due_date']))
                                        : '-' ?>
                                </td>

                                <td class="text-center">
                                    <span class="default-badge bg-danger bg-opacity-10 text-danger">
                                        <?= (int) $agingDays ?> Hari
                                    </span>
                                </td>

                                <td class="text-end">
                                    <?= $row['bucket_0_30'] > 0
                                        ? 'Rp ' . number_format($row['bucket_0_30'], 0, ',', '.')
                                        : '-' ?>
                                </td>

                                <td class="text-end">
                                    <?= $row['bucket_31_60'] > 0
                                        ? 'Rp ' . number_format($row['bucket_31_60'], 0, ',', '.')
                                        : '-' ?>
                                </td>

                                <td class="text-end">
                                    <?= $row['bucket_61_90'] > 0
                                        ? 'Rp ' . number_format($row['bucket_61_90'], 0, ',', '.')
                                        : '-' ?>
                                </td>

                                <td class="text-end">
                                    <?= $row['bucket_90_plus'] > 0
                                        ? 'Rp ' . number_format($row['bucket_90_plus'], 0, ',', '.')
                                        : '-' ?>
                                </td>

                                <td class="text-end fw-bold text-danger">
                                    Rp <?= number_format($row['remaining'], 0, ',', '.') ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                        <tr class="table-light">

                            <td colspan="5" class="text-end fw-bold">
                                TOTAL
                            </td>

                            <td class="text-end fw-bold">
                                Rp <?= number_format($total0to30, 0, ',', '.') ?>
                            </td>

                            <td class="text-end fw-bold">
                                Rp <?= number_format($total31to60, 0, ',', '.') ?>
                            </td>

                            <td class="text-end fw-bold">
                                Rp <?= number_format($total61to90, 0, ',', '.') ?>
                            </td>

                            <td class="text-end fw-bold">
                                Rp <?= number_format($total90plus, 0, ',', '.') ?>
                            </td>

                            <td class="text-end fw-bold text-danger">
                                Rp <?= number_format($grandOutstanding, 0, ',', '.') ?>
                            </td>

                        </tr>

                    <?php else: ?>

                        <tr>
                            <td colspan="10" class="text-center text-body py-4">
                                Tidak ada hutang vendor outstanding.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>