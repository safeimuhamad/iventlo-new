<?php
function calculateQuotationItemTotal($item)
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
    $calc = calculateQuotationItemTotal($item);
    $subtotalBeforeDiscount += $calc['before_discount'];
    $totalDiscount += $calc['discount'];
    $grandTotal += $calc['total'];
}

$status = $quotation['status'] ?? 'waiting approval';

$statusClass = match ($status) {
    'waiting approval' => 'bg-warning bg-opacity-10 text-warning',
    'order' => 'bg-primary bg-opacity-10 text-primary',
    'approved' => 'bg-success bg-opacity-10 text-success',
    'cancelled' => 'bg-dark bg-opacity-10 text-dark',
    default => 'bg-secondary bg-opacity-10 text-secondary'
};

$sourceLabel = !empty($quotation['lead_id'])
    ? 'Dari Lead'
    : 'Customer Existing';

$sourceClass = !empty($quotation['lead_id'])
    ? 'bg-info bg-opacity-10 text-info'
    : 'bg-success bg-opacity-10 text-success';
?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Detail Penawaran
            </h3>

            <p class="mb-0 text-body">
                <?= htmlspecialchars($quotation['no_quotation'] ?? '-') ?>
            </p>
        </div>

        <div class="d-flex justify-content-between justify-content-md-end align-items-center flex-wrap gap-3">

            <a
                href="<?= url('quotations') ?>"
                class="btn btn-light erp-btn"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Kembali
            </a>

            <div class="d-flex flex-wrap align-items-center erp-action-group">

                <a
                    href="<?= url('quotations-edit') ?>?id=<?= $quotation['id'] ?>"
                    class="btn btn-outline-primary erp-btn"
                >
                    <i class="ri-edit-line me-1"></i>
                    Edit
                </a>

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
                                onclick="openPrint('<?= url('quotations-print-rental') ?>?id=<?= $quotation['id'] ?>')"
                            >
                                <div class="erp-dropdown-title">
                                    <i class="ri-file-text-line me-2"></i>
                                    Format Rental
                                </div>

                                <div class="erp-dropdown-desc">
                                    Cetak penawaran format rental unit
                                </div>
                            </a>
                        </li>

                        <li>
                            <a
                                class="dropdown-item erp-dropdown-item"
                                href="javascript:void(0)"
                                onclick="openPrint('<?= url('quotations-print-service') ?>?id=<?= $quotation['id'] ?>')"
                            >
                                <div class="erp-dropdown-title">
                                    <i class="ri-tools-line me-2"></i>
                                    Format Jasa / Project
                                </div>

                                <div class="erp-dropdown-desc">
                                    Cetak penawaran format jasa atau project
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

                        <?php if (!in_array(($quotation['status'] ?? ''), ['approved', 'order'])): ?>
                            <li>
                                <a
                                    href="javascript:void(0)"
                                    class="dropdown-item erp-dropdown-item btn-create-invoice"
                                    data-url="<?= url('invoices-create-from-quotation') ?>?id=<?= $quotation['id'] ?>"
                                >
                                    <div class="erp-dropdown-title text-success">
                                        <i class="ri-file-list-3-line me-2"></i>
                                        Buat Invoice
                                    </div>

                                    <div class="erp-dropdown-desc">
                                        Generate invoice dari penawaran ini
                                    </div>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (($quotation['status'] ?? '') === 'approved'): ?>
                            <li>
                                <a
                                    href="javascript:void(0)"
                                    class="dropdown-item erp-dropdown-item btn-create-order"
                                    data-url="<?= url('rental-orders-create-from-quotation') ?>?id=<?= $quotation['id'] ?>"
                                >
                                    <div class="erp-dropdown-title text-primary">
                                        <i class="ri-truck-line me-2"></i>
                                        Buat Order
                                    </div>

                                    <div class="erp-dropdown-desc">
                                        Generate rental order dari penawaran
                                    </div>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (($quotation['status'] ?? '') !== 'order'): ?>
                            <li>
                                <a
                                    class="dropdown-item erp-dropdown-item"
                                    href="<?= url('quotations-delete') ?>?id=<?= $quotation['id'] ?>"
                                    onclick="return confirm('Yakin ingin menghapus penawaran ini?')"
                                >
                                    <div class="erp-dropdown-title text-danger">
                                        <i class="ri-delete-bin-line me-2"></i>
                                        Hapus Penawaran
                                    </div>

                                    <div class="erp-dropdown-desc">
                                        Hapus dokumen penawaran
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
            <div class="erp-detail-label">Status</div>

            <div class="erp-detail-value">
                <span class="default-badge <?= $statusClass ?>">
                    <?= htmlspecialchars(ucwords(str_replace('_', ' ', $status))) ?>
                </span>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">Sumber</div>

            <div class="erp-detail-value">
                <span class="default-badge <?= $sourceClass ?>">
                    <?= htmlspecialchars($sourceLabel) ?>
                </span>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">Total Item</div>

            <div class="erp-detail-value">
                <?= count($items) ?> Item
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">Grand Total</div>

            <div class="erp-detail-value text-primary">
                Rp <?= number_format($grandTotal, 0, ',', '.') ?>
            </div>
        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title">
            Informasi Penawaran
        </h4>
    </div>

    <div class="p-20">

        <div class="row g-4">

            <div class="col-md-3">
                <div class="erp-detail-label">Customer</div>
                <div class="erp-detail-value">
                    <?= htmlspecialchars($quotation['customer_name'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">No. HP</div>
                <div class="erp-detail-value">
                    <?= htmlspecialchars($quotation['customer_phone'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">Tanggal Mulai</div>
                <div class="erp-detail-value">
                    <?= !empty($quotation['tanggal_mulai'])
                        ? date('d M Y', strtotime($quotation['tanggal_mulai']))
                        : '-' ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">Tanggal Selesai</div>
                <div class="erp-detail-value">
                    <?= !empty($quotation['tanggal_selesai'])
                        ? date('d M Y', strtotime($quotation['tanggal_selesai']))
                        : '-' ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">No Lead</div>
                <div class="erp-detail-value">
                    <?= !empty($quotation['lead_id'])
                        ? htmlspecialchars($quotation['lead_number'] ?? '-')
                        : '-' ?>
                </div>
            </div>

            <div class="col-md-9">
                <div class="erp-detail-label">Lokasi</div>
                <div class="erp-detail-value">
                    <?= nl2br(htmlspecialchars($quotation['lokasi'] ?? '-')) ?>
                </div>
            </div>

        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title">
            Produk / Jasa
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
                            $qty = (float) ($item['qty'] ?? 0);
                            $duration = (float) ($item['duration'] ?? 1);
                            $price = (float) ($item['unit_price'] ?? 0);
                            $discount = (float) ($item['discount'] ?? 0);

                            $itemType = $item['item_type'] ?? 'rental_unit';

                            $billingType = $item['billing_type']
                                ?? $item['rental_period_type']
                                ?? 'daily';

                            $calc = calculateQuotationItemTotal($item);
                            $total = $calc['total'];

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
                                    Rp <?= number_format($total, 0, ',', '.') ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                        <tr>
                            <td colspan="7" class="text-end fw-semibold border-top">
                                Subtotal
                            </td>

                            <td class="text-end fw-bold border-top">
                                Rp <?= number_format($subtotalBeforeDiscount, 0, ',', '.') ?>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="7" class="text-end text-body">
                                Total Diskon
                            </td>

                            <td class="text-end fw-semibold">
                                Rp <?= number_format($totalDiscount, 0, ',', '.') ?>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="7" class="text-end fw-bold">
                                Grand Total
                            </td>

                            <td class="text-end fw-bold text-primary">
                                Rp <?= number_format($grandTotal, 0, ',', '.') ?>
                            </td>
                        </tr>

                    <?php else: ?>

                        <tr>
                            <td colspan="8" class="text-center text-body py-4">
                                Belum ada item penawaran.
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
            Catatan Penawaran
        </h4>
    </div>

    <div class="p-20">
        <p class="mb-0 text-body">
            <?= !empty($quotation['catatan'])
                ? nl2br(htmlspecialchars($quotation['catatan']))
                : '-' ?>
        </p>
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

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.btn-create-order').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();

                const url = this.dataset.url;

                if (confirm('Buat order dari penawaran ini?')) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    form.innerHTML = '<?= addslashes(csrfField()) ?>';
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });

        document.querySelectorAll('.btn-create-invoice').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();

                const url = this.dataset.url;

                if (confirm('Buat invoice dari penawaran ini?')) {
                    window.location.href = url;
                }
            });
        });
    });
</script>
