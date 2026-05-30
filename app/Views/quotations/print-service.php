<?php
$subtotalBeforeDiscount = 0;
$totalDiscount = 0;
$grandTotal = 0;

$hasRental = false;
$hasService = false;
$hasInstallation = false;

foreach ($items as $item) {
    if (($item['item_type'] ?? '') === 'rental_unit') {
        $hasRental = true;
    }

    if (($item['item_type'] ?? '') === 'service') {
        $hasService = true;
    }

    if (($item['item_type'] ?? '') === 'installation') {
        $hasInstallation = true;
    }
}

foreach ($items as $item) {
    $qty = (float) ($item['qty'] ?? 0);
    $duration = (float) ($item['duration'] ?? 0);
    $price = (float) ($item['unit_price'] ?? 0);
    $discount = (float) ($item['discount'] ?? 0);

    $beforeDiscount = $qty * $duration * $price;
    $total = max(0, $beforeDiscount - $discount);

    $subtotalBeforeDiscount += $beforeDiscount;
    $totalDiscount += $discount;
    $grandTotal += $total;
}

$hasDiscount = false;

foreach ($items as $item) {
    if ((float) ($item['discount'] ?? 0) > 0) {
        $hasDiscount = true;
        break;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>PENAWARAN</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            color: #111;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 12mm;
            margin: auto;
            box-sizing: border-box;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #111;
            padding-bottom: 10px;
            margin-bottom: 18px;
        }

        .company {
            width: 65%;
        }

        .company img {
            width: 180px;
            margin-bottom: 6px;
        }

        .company p {
            margin: 0;
            line-height: 1.4;
        }

        .doc-title {
            width: 35%;
            text-align: right;
        }

        .doc-title h1 {
            margin: 0;
            font-size: 28px;
            letter-spacing: 1px;
        }

        .doc-title p {
            margin: 6px 0 0;
            font-size: 14px;
        }

        .info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 18px;
        }

        .info table {
            width: 100%;
            border-collapse: collapse;
        }

        .info td {
            padding: 4px 0;
            vertical-align: top;
        }

        .info td:first-child {
            width: 120px;
            font-weight: bold;
        }

        .items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        .items th,
        .items td {
            border: 1px solid #111;
            padding: 8px 10px;
            vertical-align: top;
        }

        .items td:nth-child(2) {
            width: 320px;
            line-height: 1.4;
        }

        .items td:nth-child(3),
        .items td:nth-child(4),
        .items td:nth-child(5) {
            text-align: center;
        }

        .items td:nth-child(6),
        .items td:nth-child(7),
        .items td:nth-child(8) {
            text-align: right;
            white-space: nowrap;
        }

        .items th {
            background: #f2f2f2;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .summary {
            width: 100%;
            border-collapse: collapse;
        }

        .summary td {
            padding: 6px 8px;
        }

        .summary .line td {
            border-top: 1px solid #111;
            font-weight: bold;
            font-size: 14px;
        }

        .notes {
            border: 1px solid #111;
            padding: 8px;
            min-height: 70px;
        }

.signature {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 70px;
    text-align: center;
    margin-top: 90px;
}

.signature p {
    margin: 0 0 90px;
}

        .sign-line {
            border-top: 1px solid #111;
            padding-top: 6px;
            font-weight: bold;
        }

        .print-actions {
            width: 210mm;
            margin: 15px auto;
            text-align: right;
        }

        .btn {
            border: 0;
            padding: 10px 16px;
            cursor: pointer;
            border-radius: 6px;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-print {
            background: #0d6efd;
            color: #fff;
        }

        .btn-back {
            background: #6c757d;
            color: #fff;
        }

        @page {
            size: A4;
            margin: 8mm;
        }

        @media print {
            .print-actions {
                display: none;
            }

            .page {
                width: auto;
                min-height: auto;
                padding: 0;
            }
        }
    </style>
</head>
<script>
    window.onload = function () {

        setTimeout(() => {
            window.print();
        }, 300);

    };

    window.onafterprint = function () {

        setTimeout(() => {
            window.open('', '_self');
            window.close();
        }, 100);

    };

// fallback safari/mac
    window.addEventListener('focus', function () {

        setTimeout(() => {

            window.open('', '_self');
            window.close();

        }, 500);

    });
</script>
<body>

    <div class="page">

        <div class="header">
            <div class="company">
                <img src="<?= asset('images/logo.webp') ?>" alt="Micool Logo">

                <p>
                    18 Office Park, Jl. TB Simatupang No.18, RT.2/RW.1, Kebagusan,
                    Kec. Ps. Minggu, Kota Jakarta Selatan, DKI Jakarta 12520
                </p>
            </div>

            <div class="doc-title">
                <h1>PENAWARAN</h1>
                <p><strong>No:</strong> <?= htmlspecialchars($quotation['no_quotation'] ?? '-') ?></p>
            </div>
        </div>

        <div class="info">
            <table>
                <tr>
                    <td>Customer</td>
                    <td>: <?= htmlspecialchars($quotation['customer_name'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>No. HP</td>
                    <td>: <?= htmlspecialchars($quotation['customer_phone'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>Lokasi</td>
                    <td>: <?= nl2br(htmlspecialchars($quotation['lokasi'] ?? '-')) ?></td>
                </tr>
            </table>

            <table>
                <tr>
                    <td>Tanggal Mulai</td>
                    <td>: <?= htmlspecialchars($quotation['tanggal_mulai'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>Tanggal Selesai</td>
                    <td>: <?= htmlspecialchars($quotation['tanggal_selesai'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>Tanggal Penawaran</td>
                    <td>: <?= date('Y-m-d', strtotime($quotation['created_at'])) ?></td>
                </tr>
            </table>
        </div>

<table class="items">
    <thead>
        <tr>
            <th width="35">No</th>
            <th width="300">Produk / Jasa</th>
            <th width="45">Qty</th>
            <th width="85">Satuan</th>
            <th width="110">Harga</th>

            <?php if ($hasDiscount): ?>
                <th width="90">Diskon</th>
            <?php endif; ?>

            <th width="120">Total</th>
        </tr>
    </thead>

    <tbody>
        <?php if (!empty($items)): ?>
            <?php foreach ($items as $index => $item): ?>

                <?php
                $qty = (float) ($item['qty'] ?? 0);
                $duration = (float) ($item['duration'] ?? 1);
                $price = (float) ($item['unit_price'] ?? 0);
                $discount = (float) ($item['discount'] ?? 0);

                $billingType = $item['billing_type']
                    ?? $item['rental_period_type']
                    ?? 'daily';

                $itemType = $item['item_type'] ?? 'rental_unit';

                if (in_array($billingType, ['daily', 'weekly', 'monthly'])) {
                    $beforeDiscount = $qty * $duration * $price;
                } elseif (in_array($billingType, ['package', 'fixed'])) {
                    $beforeDiscount = $price;
                } else {
                    $beforeDiscount = $qty * $price;
                }

                $total = max(0, $beforeDiscount - $discount);

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

                $itemTypeLabel = match ($itemType) {
                    'rental_unit' => 'Rental',
                    'service' => 'Service',
                    'installation' => 'Instalasi',
                    'material' => 'Material',
                    'sparepart' => 'Sparepart',
                    'transport' => 'Transport',
                    default => 'Lainnya'
                };
                ?>

                <tr>
                    <td><?= $index + 1 ?></td>

                    <td>
                        <strong>
                            <?= htmlspecialchars($item['item_name'] ?? '-') ?>
                        </strong>
                    </td>

                    <td style="text-align:center;">
                        <?= number_format($qty, 0, ',', '.') ?>
                    </td>

                    <td style="text-align:center;">
                        <?= $billingLabel ?>

                        <?php if (in_array($billingType, ['daily', 'weekly', 'monthly'])): ?>
                            <br>
                            <small style="font-size:10px; color:#666;">
                                <?= number_format($duration, 0, ',', '.') ?>
                                <?= match ($billingType) {
                                    'daily' => 'Hari',
                                    'weekly' => 'Minggu',
                                    'monthly' => 'Bulan',
                                    default => ''
                                } ?>
                            </small>
                        <?php endif; ?>
                    </td>

                    <td class="text-right">
                        Rp <?= number_format($price, 0, ',', '.') ?>
                    </td>

                    <?php if ($hasDiscount): ?>
                        <td class="text-right">
                            Rp <?= number_format($discount, 0, ',', '.') ?>
                        </td>
                    <?php endif; ?>

                    <td class="text-right">
                        <strong>
                            Rp <?= number_format($total, 0, ',', '.') ?>
                        </strong>
                    </td>
                </tr>

            <?php endforeach; ?>

        <?php else: ?>

            <tr>
                <td colspan="<?= $hasDiscount ? 8 : 7 ?>" style="text-align:center;">
                    Belum ada item.
                </td>
            </tr>

        <?php endif; ?>
    </tbody>
</table>

        <div style="display:flex; gap:30px; align-items:flex-start; margin-top:18px;">

            <div style="flex:1;">
                <div class="notes">
                    <strong>Catatan:</strong><br>
                    <?= !empty($quotation['catatan']) 
                    ? nl2br(htmlspecialchars($quotation['catatan'])) 
                    : '-' ?>
                </div>
            </div>

            <div style="width:38%; padding-top:0;">
                <table class="summary">
                    <tr>
                        <td>Subtotal</td>
                        <td class="text-right">Rp <?= number_format($subtotalBeforeDiscount, 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td>Total Diskon</td>
                        <td class="text-right">Rp <?= number_format($totalDiscount, 0, ',', '.') ?></td>
                    </tr>
                    <tr class="line">
                        <td>Grand Total</td>
                        <td class="text-right">Rp <?= number_format($grandTotal, 0, ',', '.') ?></td>
                    </tr>
                </table>
            </div>

        </div>

        <div class="signature">
            <div>
                <p>Hormat Kami,</p>
                <div class="sign-line">Micool.id</div>
            </div>

            <div>
                <p>Disetujui Oleh,</p>
                <div class="sign-line">Customer</div>
            </div>
        </div>

    </div>
</body>
</html>