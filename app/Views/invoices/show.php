<?php

function calculateInvoiceItemTotal($item)
{
    $qty = (float) ($item['qty'] ?? 0);
    $duration = (float) ($item['duration'] ?? 1);
    $price = (float) ($item['unit_price'] ?? 0);
    $discount = (float) ($item['discount'] ?? 0);

    $billingType = $item['billing_type']
        ?? $item['rental_period_type']
        ?? 'daily';

    if (in_array($billingType, ['daily', 'weekly', 'monthly'])) {
        $beforeDiscount = $qty * $duration * $price;
    } elseif (in_array($billingType, ['package', 'fixed'])) {
        $beforeDiscount = $price;
    } else {
        $beforeDiscount = $qty * $price;
    }

    return [
        'before_discount' => $beforeDiscount,
        'discount' => $discount,
        'total' => max(0, $beforeDiscount - $discount),
    ];
}

$subtotalBeforeDiscount = 0;
$totalDiscount = 0;
$grandTotal = 0;

foreach ($items as $item) {
    $calc = calculateInvoiceItemTotal($item);
    $subtotalBeforeDiscount += $calc['before_discount'];
    $totalDiscount += $calc['discount'];
    $grandTotal += $calc['total'];
}

$status = $computedStatus ?? 'waiting payment';

$statusClass = match ($status) {
    'waiting payment' => 'bg-info bg-opacity-10 text-info',
    'partial paid' => 'bg-warning bg-opacity-10 text-warning',
    'paid' => 'bg-success bg-opacity-10 text-success',
    'overdue' => 'bg-danger bg-opacity-10 text-danger',
    'cancelled' => 'bg-dark bg-opacity-10 text-dark',
    default => 'bg-secondary bg-opacity-10 text-secondary'
};

$type = $invoice['invoice_type'] ?? 'full';

$typeLabel = match ($type) {
    'dp' => 'DP',
    'final' => 'Pelunasan',
    'full' => 'Full',
    default => ucfirst($type)
};

$typeClass = match ($type) {
    'dp' => 'bg-warning bg-opacity-10 text-warning',
    'final' => 'bg-info bg-opacity-10 text-info',
    'full' => 'bg-primary bg-opacity-10 text-primary',
    default => 'bg-secondary bg-opacity-10 text-secondary'
};

$taxType = $invoice['tax_type'] ?? 'non_ppn';

$taxLabel = match ($taxType) {
    'include_ppn' => 'Include PPN',
    default => 'Non PPN'
};

$taxClass = match ($taxType) {
    'include_ppn' => 'bg-success bg-opacity-10 text-success',
    default => 'bg-secondary bg-opacity-10 text-secondary'
};

$totalPaid = (float) ($totalPaid ?? 0);
$remainingAmount = (float) ($remainingAmount ?? 0);
?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Detail Invoice
            </h3>

            <p class="mb-0 text-body">
                <?= htmlspecialchars($invoice['no_invoice'] ?? '-') ?>
            </p>
        </div>

        <div class="d-flex justify-content-between justify-content-md-end align-items-center flex-wrap gap-3">

            <a
                href="<?= url('invoices') ?>"
                class="btn btn-light erp-btn"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Kembali
            </a>

            <div class="d-flex flex-wrap align-items-center erp-action-group">

                <?php if (!in_array($status, ['paid', 'partial paid'])): ?>
                    <a
                        href="<?= url('invoices-edit') ?>?id=<?= $invoice['id'] ?>"
                        class="btn btn-outline-primary erp-btn"
                    >
                        <i class="ri-edit-line me-1"></i>
                        Edit
                    </a>
                <?php endif; ?>

                <div class="dropdown">

                    <button
                        class="btn btn-outline-primary dropdown-toggle erp-btn"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >
                        <i class="ri-printer-line me-1"></i>
                        Print
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end erp-dropdown-menu">

                        <li>
                            <a
                                class="dropdown-item erp-dropdown-item"
                                href="javascript:void(0)"
                                onclick="openPrint('<?= url('invoices-print') ?>?id=<?= $invoice['id'] ?>&format=rental')"
                            >
                                <div class="erp-dropdown-title">
                                    <i class="ri-file-text-line me-2"></i>
                                    Format Rental
                                </div>

                                <div class="erp-dropdown-desc">
                                    Cetak invoice rental unit
                                </div>
                            </a>
                        </li>

                        <li>
                            <a
                                class="dropdown-item erp-dropdown-item"
                                href="javascript:void(0)"
                                onclick="openPrint('<?= url('invoices-print') ?>?id=<?= $invoice['id'] ?>&format=service')"
                            >
                                <div class="erp-dropdown-title">
                                    <i class="ri-tools-line me-2"></i>
                                    Format Service / Instalasi
                                </div>

                                <div class="erp-dropdown-desc">
                                    Cetak invoice service dan instalasi
                                </div>
                            </a>
                        </li>

                    </ul>

                </div>

                <div class="dropdown">

                    <button
                        class="btn btn-primary text-white dropdown-toggle erp-btn"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >
                        <i class="ri-settings-3-line me-1"></i>
                        Actions
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end erp-dropdown-menu">

                        <?php if (!in_array($status, ['paid', 'cancelled'])): ?>
                            <li>
                                <a
                                    class="dropdown-item erp-dropdown-item"
                                    href="<?= url('invoice-payments-create') ?>?invoice_id=<?= $invoice['id'] ?>"
                                >
                                    <div class="erp-dropdown-title text-success">
                                        <i class="ri-money-dollar-circle-line me-2"></i>
                                        Terima Pembayaran
                                    </div>

                                    <div class="erp-dropdown-desc">
                                        Catat pembayaran customer
                                    </div>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (!in_array($status, ['paid', 'partial paid'])): ?>
                            <li>
                                <a
                                    class="dropdown-item erp-dropdown-item"
                                    href="<?= url('invoices-delete') ?>?id=<?= $invoice['id'] ?>"
                                    onclick="return confirm('Yakin ingin menghapus invoice ini?')"
                                >
                                    <div class="erp-dropdown-title text-danger">
                                        <i class="ri-delete-bin-line me-2"></i>
                                        Hapus Invoice
                                    </div>

                                    <div class="erp-dropdown-desc">
                                        Hapus dokumen invoice
                                    </div>
                                </a>
                            </li>
                        <?php endif; ?>

                    </ul>

                </div>

            </div>

        </div>

    </div>

</div>
<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="row g-4">

        <div class="col-md-3">
            <div class="erp-detail-label">Status Invoice</div>

            <div class="erp-detail-value">
                <span class="default-badge <?= $statusClass ?>">
                    <?= htmlspecialchars(ucwords(str_replace('_', ' ', $status))) ?>
                </span>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">Tipe Invoice</div>

            <div class="erp-detail-value">
                <span class="default-badge <?= $typeClass ?>">
                    <?= htmlspecialchars($typeLabel) ?>
                </span>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">Sudah Dibayar</div>

            <div class="erp-detail-value text-success">
                Rp <?= number_format($totalPaid, 0, ',', '.') ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">Sisa Tagihan</div>

            <div class="erp-detail-value text-danger">
                Rp <?= number_format($remainingAmount, 0, ',', '.') ?>
            </div>
        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title">
            Informasi Invoice
        </h4>
    </div>

    <div class="p-20">

        <div class="row g-4">

            <div class="col-md-3">
                <div class="erp-detail-label">Customer</div>
                <div class="erp-detail-value"><?= htmlspecialchars($invoice['customer_name'] ?? '-') ?></div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">No. HP</div>
                <div class="erp-detail-value"><?= htmlspecialchars($invoice['customer_phone'] ?? '-') ?></div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">Tanggal Invoice</div>
                <div class="erp-detail-value">
                    <?= !empty($invoice['invoice_date'])
                        ? date('d M Y', strtotime($invoice['invoice_date']))
                        : '-' ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">Jatuh Tempo</div>
                <div class="erp-detail-value">
                    <?= !empty($invoice['due_date'])
                        ? date('d M Y', strtotime($invoice['due_date']))
                        : '-' ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">PPN</div>

                <div class="erp-detail-value">
                    <span class="default-badge <?= $taxClass ?>">
                        <?= htmlspecialchars($taxLabel) ?>
                    </span>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">Tax Percent</div>
                <div class="erp-detail-value">
                    <?= number_format((float) ($invoice['tax_percent'] ?? 0), 0) ?>%
                </div>
            </div>

            <div class="col-md-6">
                <div class="erp-detail-label">Lokasi</div>
                <div class="erp-detail-value">
                    <?= nl2br(htmlspecialchars($invoice['lokasi'] ?? '-')) ?>
                </div>
            </div>

        </div>

    </div>

</div>
<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title">
            Rincian Produk / Jasa
        </h4>
    </div>

    <div class="default-table-area mx-minus-1">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Produk / Jasa</th>
                        <th>Jenis</th>
                        <th class="text-center">Qty</th>
                        <th>Billing</th>
                        <th class="text-center">Durasi</th>
                        <th class="text-end">Harga</th>
                        <th class="text-end">Diskon</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($items)): ?>
                        <?php foreach ($items as $item): ?>
                            <?php
                            $calc = calculateInvoiceItemTotal($item);

                            $qty = (float) ($item['qty'] ?? 0);
                            $duration = (float) ($item['duration'] ?? 1);
                            $price = (float) ($item['unit_price'] ?? 0);
                            $discount = (float) ($item['discount'] ?? 0);

                            $itemType = $item['item_type'] ?? 'rental_unit';

                            $billingType = $item['billing_type']
                                ?? $item['rental_period_type']
                                ?? 'daily';

                            $itemTypeLabel = match ($itemType) {
                                'rental_unit' => 'Rental',
                                'service' => 'Service',
                                'installation' => 'Instalasi',
                                'material' => 'Material',
                                'sparepart' => 'Sparepart',
                                'transport' => 'Transport',
                                default => 'Lainnya'
                            };

                            $billingLabel = match ($billingType) {
                                'daily' => 'Harian',
                                'weekly' => 'Mingguan',
                                'monthly' => 'Bulanan',
                                'unit' => 'Per Unit',
                                'meter' => 'Per Meter',
                                'package' => 'Paket',
                                'fixed' => 'Fixed',
                                default => ucfirst($billingType)
                            };

                            $durationDisplay = in_array($billingType, ['daily', 'weekly', 'monthly'])
                                ? number_format($duration, 0, ',', '.')
                                : '-';
                            ?>

                            <tr>
                                <td class="fw-semibold">
                                    <?= htmlspecialchars($item['item_name'] ?? '-') ?>
                                </td>

                                <td>
                                    <span class="default-badge bg-primary bg-opacity-10 text-primary">
                                        <?= htmlspecialchars($itemTypeLabel) ?>
                                    </span>
                                </td>

                                <td class="text-center">
                                    <?= number_format($qty, 0, ',', '.') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($billingLabel) ?>
                                </td>

                                <td class="text-center">
                                    <?= $durationDisplay ?>
                                </td>

                                <td class="text-end">
                                    Rp <?= number_format($price, 0, ',', '.') ?>
                                </td>

                                <td class="text-end">
                                    <?= $discount > 0
                                        ? 'Rp ' . number_format($discount, 0, ',', '.')
                                        : '-' ?>
                                </td>

                                <td class="text-end fw-bold">
                                    Rp <?= number_format($calc['total'], 0, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <tr>
                            <td colspan="7" class="text-end fw-semibold border-top">
                                Total Tagihan
                            </td>
                            <td class="text-end fw-bold border-top">
                                Rp <?= number_format((float) ($subtotalBeforeDiscount ?? 0), 0, ',', '.') ?>
                            </td>
                        </tr>

                        <?php if (($invoice['tax_type'] ?? 'non_ppn') === 'include_ppn'): ?>
                            <tr>
                                <td colspan="7" class="text-end text-body">
                                    PPN <?= number_format((float) ($invoice['tax_percent'] ?? 11), 0) ?>%
                                </td>
                                <td class="text-end fw-semibold">
                                    Rp <?= number_format((float) ($invoice['tax_amount'] ?? 0), 0, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php if ((float) ($invoice['dp_nominal'] ?? 0) > 0): ?>
                            <tr>
                                <td colspan="7" class="text-end text-body">
                                    Tagihan DP <?= number_format((float) ($invoice['dp_percentage'] ?? 0), 0) ?>%
                                </td>
                                <td class="text-end fw-semibold">
                                    Rp <?= number_format((float) ($invoice['dp_nominal'] ?? 0), 0, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <tr>
                            <td colspan="7" class="text-end text-body">
                                Sudah Dibayar
                            </td>
                            <td class="text-end fw-semibold text-success">
                                Rp <?= number_format((float) ($totalPaid ?? 0), 0, ',', '.') ?>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="7" class="text-end fw-bold">
                                Sisa Tagihan
                            </td>
                            <td class="text-end fw-bold text-danger">
                                Rp <?= number_format((float) ($remainingAmount ?? 0), 0, ',', '.') ?>
                            </td>
                        </tr>

                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-body py-4">
                                Belum ada item invoice.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title">
            Riwayat Pembayaran
        </h4>
    </div>

    <div class="default-table-area mx-minus-1">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kode Akun</th>
                        <th>Nama Akun</th>
                        <th>Bank</th>
                        <th>No Rekening</th>
                        <th>Metode</th>
                        <th style="min-width:220px;">Keterangan</th>
                        <th class="text-end">Nominal</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($payments)): ?>
                        <?php
                        $paymentTotal = 0;
                        ?>

                        <?php foreach ($payments as $payment): ?>
                            <?php
                            $amount = (float) ($payment['payment_amount'] ?? 0);
                            $paymentTotal += $amount;
                            ?>

                            <tr>
                                <td>
                                    <?= !empty($payment['payment_date'])
                                        ? date('d M Y', strtotime($payment['payment_date']))
                                        : '-' ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($payment['account_code'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($payment['account_name'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($payment['bank_name'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($payment['account_number'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($payment['payment_method'] ?? '-') ?>
                                </td>

                                <td class="text-wrap" style="min-width:220px; max-width:320px;">
                                    <?= htmlspecialchars($payment['notes'] ?? '-') ?>
                                </td>

                                <td class="text-end fw-semibold text-success">
                                    Rp <?= number_format($amount, 0, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <tr>
                            <td colspan="7" class="text-end fw-semibold border-top">
                                Total Pembayaran
                            </td>

                            <td class="text-end fw-bold text-success border-top">
                                Rp <?= number_format($paymentTotal, 0, ',', '.') ?>
                            </td>
                        </tr>

                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-body py-4">
                                Belum ada pembayaran.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    function openPrint(url)
    {
        const printWindow = window.open(
            url,
            '_blank',
            'width=1200,height=900'
        );

        if (!printWindow) {
            alert('Popup diblokir browser.');
            return;
        }
    }
</script>