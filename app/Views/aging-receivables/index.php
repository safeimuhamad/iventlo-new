<?php
$totalOutstanding = 0;

$bucketTotals = [
    '0-30' => 0,
    '31-60' => 0,
    '61-90' => 0,
    '90+' => 0,
];

$today = new DateTime(date('Y-m-d'));
?>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">
        <div>
            <h3 class="mb-1">Aging Piutang</h3>
            <p class="mb-0 text-body">Daftar invoice yang masih memiliki sisa tagihan.</p>
        </div>
    </div>

    <div class="default-table-area mx-minus-1">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Customer</th>
                        <th>Tanggal Invoice</th>
                        <th>Jatuh Tempo</th>
                        <th>Umur</th>
                        <th>Kategori</th>
                        <th class="text-end">Sisa Tagihan</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($invoices)): ?>
                        <?php foreach ($invoices as $invoice): ?>
                            <?php
                            $remaining = (float)($invoice['remaining_amount'] ?? 0);
                            $totalOutstanding += $remaining;

                            $dueDateValue = $invoice['due_date'] ?? null;
                            $ageDays = 0;

                            if (!empty($dueDateValue)) {
                                $dueDate = new DateTime($dueDateValue);
                                $ageDays = (int)$dueDate->diff($today)->format('%r%a');
                                $ageDays = max(0, $ageDays);
                            }

                            if ($ageDays <= 30) {
                                $bucket = '0-30';
                                $badgeClass = 'bg-success bg-opacity-10 text-success';
                            } elseif ($ageDays <= 60) {
                                $bucket = '31-60';
                                $badgeClass = 'bg-warning bg-opacity-10 text-warning';
                            } elseif ($ageDays <= 90) {
                                $bucket = '61-90';
                                $badgeClass = 'bg-danger bg-opacity-10 text-danger';
                            } else {
                                $bucket = '90+';
                                $badgeClass = 'bg-dark bg-opacity-10 text-dark';
                            }

                            $bucketTotals[$bucket] += $remaining;
                            ?>

                            <tr>
                                <td>
                                    <a 
                                        href="<?= url('invoices-show') ?>?id=<?= $invoice['id'] ?>"
                                        class="text-primary fw-semibold text-decoration-none"
                                    >
                                        <?= htmlspecialchars($invoice['no_invoice'] ?? '-') ?>
                                    </a>
                                </td>

                                <td><?= htmlspecialchars($invoice['customer_name'] ?? '-') ?></td>

                                <td><?= htmlspecialchars($invoice['invoice_date'] ?? '-') ?></td>

                                <td><?= htmlspecialchars($invoice['due_date'] ?? '-') ?></td>

                                <td><?= $ageDays ?> hari</td>

                                <td>
                                    <span class="default-badge <?= $badgeClass ?>">
                                        <?= $bucket ?> hari
                                    </span>
                                </td>

                                <td class="text-end fw-semibold text-danger">
                                    Rp <?= number_format($remaining, 0, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <tr>
                            <td colspan="6" class="text-end fw-bold">Total Outstanding</td>
                            <td class="text-end fw-bold text-danger">
                                Rp <?= number_format($totalOutstanding, 0, ',', '.') ?>
                            </td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-body">
                                Tidak ada piutang outstanding.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <?php foreach ($bucketTotals as $bucket => $amount): ?>
        <div class="col-md-3 mb-4">
            <div class="card bg-white rounded-10 border border-white p-20">
                <span class="text-body"><?= $bucket ?> hari</span>
                <h4 class="mt-2 mb-0">
                    Rp <?= number_format($amount, 0, ',', '.') ?>
                </h4>
            </div>
        </div>
    <?php endforeach; ?>
</div>