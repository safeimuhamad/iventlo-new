<?php

$invoiceType = $invoice['invoice_type'] ?? 'full';

$dpPercentage = (float) ($invoice['dp_percentage'] ?? 0);
$dpNominal = (float) ($invoice['dp_nominal'] ?? 0);
$remainingBill = (float) ($invoice['remaining_bill'] ?? 0);
$billingTotal = (float) ($invoice['billing_total'] ?? 0);

$subtotalBeforeDiscount = 0;
$totalDiscount = 0;
$grandTotal = 0;

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
$taxType = $invoice['tax_type'] ?? 'non_ppn';
$taxPercent = (float) ($invoice['tax_percent'] ?? 0);
$taxAmount = (float) ($invoice['tax_amount'] ?? 0);
$finalGrandTotal = (float) ($invoice['billing_total'] ?? $grandTotal);

$isPpn = $taxType === 'include_ppn';

$bankName = 'BCA';
$bankBranch = $isPpn ? 'ARKADIA' : '-';
$accountName = $isPpn ? 'PT. Micool Berkah Bersama' : 'LINA ANGGREANI';
$accountNumber = $isPpn ? '5405197984' : '1660867313';
$paymentReference = $invoice['no_invoice'] ?? '-';
foreach ($items as $item) {
    if ((float) ($item['discount'] ?? 0) > 0) {
        $hasDiscount = true;
        break;
    }
}


$invoiceTotal = (float) $finalGrandTotal;

$paymentModel = new InvoicePayment();
$paidAmount = $paymentModel->getTotalPaid($invoice['id']);

$remainingAmount = max(0, $invoiceTotal - $paidAmount);

if ($paidAmount <= 0) {
    $paymentLabel = 'BELUM BAYAR';
} elseif ($remainingAmount > 0) {
    $paymentLabel = 'BAYAR SEBAGIAN';
} else {
    $paymentLabel = 'LUNAS';
}

$isDpInvoice = $dpNominal > 0;
$displayRemainingAmount = $paidAmount > 0
? $remainingAmount
: max(0, $billingTotal - $dpNominal);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Print Invoice</title>

    <style>

        body{
            font-family: Arial, sans-serif;
            color:#111;
            margin:40px;
        }

        .print-buttons{
            text-align:right;
            margin-bottom:20px;
        }

        .btn{
            display:inline-block;
            padding:10px 18px;
            border-radius:6px;
            text-decoration:none;
            font-size:14px;
            font-weight:bold;
        }

        .btn-primary{
            background:#0d6efd;
            color:#fff;
        }

        .btn-secondary{
            background:#6c757d;
            color:#fff;
        }

        .header{
            display:flex;
            justify-content:space-between;
            align-items:flex-start;
            margin-bottom:1px;
        }

        .logo img{
            max-height:70px;
        }

        .company{
            margin-top:2px;
            margin-bottom:1px;
            font-size:13px;
            line-height:1.5;
        }

        .title{
            text-align:right;
        }

        .title h1{
            margin:0;
            font-size:42px;
        }

        .title p{
            margin-top:8px;
            font-size:16px;
        }

        hr{
            border:none;
            border-top:2px solid #222;
            margin:4px 0 10px;
        }

        .info-table{
            width:100%;
            margin-bottom:20px;
        }

        .info-table td{
            padding:4px 0;
            font-size:14px;
            vertical-align:top;
        }

        .items-table{
            width:100%;
            border-collapse:collapse;
            margin-top:10px;
        }

        .items-table th,
        .items-table td{
            border:1px solid #444;
            padding:10px;
            font-size:14px;
        }

        .items-table th{
            background:#f1f1f1;
            text-align:left;
        }

        .text-right{
            text-align:right;
        }

        .summary-wrapper{
            margin-top:20px;
            display:flex;
            justify-content:space-between;
            align-items:stretch;
            gap:24px;
        }

        .notes-box{
            width:60%;
            min-height:120px;
            border:1px solid #444;
            padding:10px;
        }

        .summary-box{
            width:38%;
        }

        .summary-table{
            width:100%;
        }

        .summary-table td{
            padding:8px 0;
            font-size:15px;
        }

        .summary-total{
            border-top:1px solid #444;
            font-weight:bold;
            font-size:18px;
        }

        .signature{
            margin-top:100px;
            display:flex;
            justify-content:space-between;
            text-align:center;
        }

        .signature-box{
            width:35%;
        }

        .signature-line{
            margin-top:150px;
            border-top:1px solid #444;
            padding-top:10px;
            font-weight:bold;
        }

        @media print{

            .print-buttons{
                display:none;
            }

            body{
                margin:20px;
            }

        }
        .payment-table {
            margin-top: 6px;
            border-collapse: collapse;
            width: 100%;
        }

        .payment-table td {
            padding: 2px 8px 2px 0;
            font-size: 13px;
            vertical-align: top;
        }

        .payment-table td:first-child {
            width: 120px;
        }
        .notes-full{
            margin-top:30px;
            border:1px solid #444;
            padding:14px;
            min-height:120px;
            font-size:14px;
            line-height:1.7;
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

    window.addEventListener('focus', function () {
        setTimeout(() => {
            window.open('', '_self');
            window.close();
        }, 500);
    });
</script>
<body>

    <div class="header">
        <div class="logo">
            <img src="<?= asset('images/logo.webp') ?>" alt="Micool Logo">
            <div class="company">
                18 Office Park, Jl. TB Simatupang No.18<br>
                Jakarta Selatan, DKI Jakarta
            </div>
        </div>
        <div class="title">
            <h1>INVOICE</h1>

            <p>
                <strong>No:</strong>
                <?= htmlspecialchars($invoice['no_invoice'] ?? '-') ?>
            </p>
        </div>
    </div>

    <hr>

    <table class="info-table">
        <tr>
            <td width="18%"><strong>Customer</strong></td>
            <td width="32%">: <?= htmlspecialchars($invoice['customer_name'] ?? '-') ?></td>

            <td width="18%"><strong>Tanggal Invoice</strong></td>
            <td width="32%">: <?= htmlspecialchars($invoice['invoice_date'] ?? '-') ?></td>
        </tr>

        <tr>
            <td><strong>No. HP</strong></td>
            <td>: <?= htmlspecialchars($invoice['customer_phone'] ?? '-') ?></td>

            <td><strong>Jatuh Tempo</strong></td>
            <td>: <?= htmlspecialchars($invoice['due_date'] ?? '-') ?></td>
        </tr>

        <tr>
            <td><strong>Lokasi</strong></td>
            <td>: <?= htmlspecialchars($invoice['lokasi'] ?? '-') ?></td>

            <td><strong>Tipe</strong></td>
            <td>: <?= strtoupper(htmlspecialchars($invoice['invoice_type'] ?? 'FULL')) ?></td>
        </tr>
    </table>

    <table class="items-table">

        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Produk / Jasa</th>
                <th width="8%">Qty</th>
                <th width="12%">Satuan</th>
                <th width="10%">Harga</th>
                <?php if ($hasDiscount): ?>
                    <th width="10%">Diskon</th>
                <?php endif; ?>
                <th width="15%">Total</th>
            </tr>
        </thead>

        <tbody>

            <?php foreach ($items as $index => $item): ?>

                <?php
                $qty = (float) ($item['qty'] ?? 0);
                $duration = (float) ($item['duration'] ?? 0);
                $price = (float) ($item['unit_price'] ?? 0);
                $discount = (float) ($item['discount'] ?? 0);

                $total = max(0, ($qty * $duration * $price) - $discount);

                $periodLabel = match ($item['rental_period_type'] ?? 'daily') {
                    'weekly' => 'Mingguan',
                    'monthly' => 'Bulanan',
                    default => 'Harian'
                };
                ?>

                <tr>
                    <td><?= $index + 1 ?></td>

                    <td>
                        <?= htmlspecialchars($item['item_name'] ?? '-') ?>
                    </td>

                    <td><?= $qty ?></td>

                    <td><?= htmlspecialchars($item['billing_type'] ?? '-') ?></td>

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

        </tbody>

    </table>

    <div class="summary-wrapper">

        <div class="notes-box">

            <strong>Detail Pembayaran:</strong>

            <table class="payment-table">
                <tr>
                    <td>Nama Bank</td>
                    <td>: <?= htmlspecialchars($bankName) ?></td>
                </tr>
                <tr>
                    <td>Cabang Bank</td>
                    <td>: <?= htmlspecialchars($bankBranch) ?></td>
                </tr>
                <tr>
                    <td>Nama Akun</td>
                    <td>: <?= htmlspecialchars($accountName) ?></td>
                </tr>
                <tr>
                    <td>Nomor Akun</td>
                    <td>: <?= htmlspecialchars($accountNumber) ?></td>
                </tr>
                <tr>
                    <td>Referensi</td>
                    <td>: <?= htmlspecialchars($paymentReference) ?></td>
                </tr>
            </table>

        </div>

        <div class="summary-box">
            <table class="summary-table">

                <tr class="summary-total">
                    <td><strong>Sub Total</strong></td>
                    <td class="text-right">
                        <strong>
                            Rp <?= number_format($subtotalBeforeDiscount, 0, ',', '.') ?>
                        </strong>
                    </td>
                </tr>
                <?php if ($hasDiscount): ?>
                    <tr>
                        <td><strong>Total Diskon</strong></td>
                        <td class="text-right">
                            <strong>
                                Rp <?= number_format($totalDiscount, 0, ',', '.') ?>
                            </strong>
                        </td>
                    </tr>
                <?php endif; ?>
                <?php if ($taxAmount): ?>
                    <tr>
                        <td><strong>PPN</strong></td>
                        <td class="text-right">
                            <strong>
                                Rp <?= number_format($taxAmount, 0, ',', '.') ?>
                            </strong>
                        </td>
                    </tr>
                <?php endif; ?>
                <tr class="summary-total">
                    <td><strong>Grand Total</strong></td>
                    <td class="text-right">
                        <strong>
                            Rp <?= number_format($billingTotal, 0, ',', '.') ?>
                        </strong>
                    </td>
                </tr>
                <?php if ($isDpInvoice): ?>
                    <tr>
                        <td>
                            <strong>
                                Tagihan DP <?= number_format($dpPercentage, 0) ?>%
                            </strong>
                        </td>

                        <td class="text-right">
                            <strong>
                                Rp <?= number_format($dpNominal, 0, ',', '.') ?>
                            </strong>
                        </td>
                    </tr>
                <?php endif; ?>

                <?php if ($paidAmount > 0): ?>
                    <tr>
                        <td><strong>Sudah Dibayar</strong></td>

                        <td class="text-right">
                            <strong>
                                Rp <?= number_format($paidAmount, 0, ',', '.') ?>
                            </strong>
                        </td>
                    </tr>
                <?php endif; ?>

                <tr class="summary-total">
                    <td><strong>Sisa Tagihan</strong></td>

                    <td class="text-right">
                        <strong>
                            Rp <?= number_format($displayRemainingAmount, 0, ',', '.') ?>
                        </strong>
                    </td>
                </tr>

            </table>
        </div>


    </div>

    <div class="notes-full">
        <strong>Catatan:</strong><br><br>

        <?= !empty($invoice['notes']) ? nl2br(htmlspecialchars($invoice['notes'])) : '-' ?>
    </div>
    <div class="signature">

        <div class="signature-box">
            Hormat Kami,

            <div class="signature-line">
                Micool.id
            </div>
        </div>

    </div>
</body>
</html>