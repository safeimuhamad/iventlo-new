<?php
$subtotal = (float) ($item['subtotal'] ?? 0);
$taxAmount = (float) ($item['tax_amount'] ?? 0);
$grandTotal = (float) ($item['grand_total'] ?? 0);
?>

<!DOCTYPE html>
<html>
<head>

    <title>Print Purchase Order</title>

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

        .items th {
            background: #f2f2f2;
            text-align: left;
        }

        .items td:nth-child(3),
        .items td:nth-child(5),
        .items td:nth-child(6) {
            text-align: right;
            white-space: nowrap;
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
                18 Office Park, Jl. TB Simatupang No.18,
                RT.2/RW.1, Kebagusan, Kec. Ps. Minggu,
                Kota Jakarta Selatan, DKI Jakarta 12520
            </p>

        </div>

        <div class="doc-title">

            <h1>PURCHASE ORDER</h1>

            <p>
                <strong>No:</strong>
                <?= htmlspecialchars($item['po_number'] ?? '-') ?>
            </p>

        </div>

    </div>

    <div class="info">

        <table>

            <tr>
                <td>Vendor</td>
                <td>: <?= htmlspecialchars($item['vendor_name'] ?? '-') ?></td>
            </tr>

            <tr>
                <td>PIC</td>
                <td>: <?= htmlspecialchars($item['vendor_pic'] ?? '-') ?></td>
            </tr>

            <tr>
                <td>No. HP</td>
                <td>: <?= htmlspecialchars($item['vendor_phone'] ?? '-') ?></td>
            </tr>

            <tr>
                <td>Alamat</td>
                <td>: <?= nl2br(htmlspecialchars($item['vendor_address'] ?? '-')) ?></td>
            </tr>

        </table>

        <table>

            <tr>
                <td>Tanggal PO</td>
                <td>: <?= htmlspecialchars($item['po_date'] ?? '-') ?></td>
            </tr>

            <tr>
                <td>Estimasi Datang</td>
                <td>: <?= htmlspecialchars($item['expected_date'] ?? '-') ?></td>
            </tr>

            <tr>
                <td>Status</td>
                <td>: <?= ucwords(str_replace('_', ' ', $item['status'] ?? '-')) ?></td>
            </tr>

        </table>

    </div>

    <table class="items">

        <thead>

            <tr>
                <th width="35">No</th>
                <th>Item</th>
                <th width="80">Qty</th>
                <th width="70">Unit</th>
                <th width="140">Harga</th>
                <th width="160">Subtotal</th>
            </tr>

        </thead>

        <tbody>

            <?php if (!empty($items)): ?>

                <?php foreach ($items as $index => $row): ?>

                    <tr>

                        <td>
                            <?= $index + 1 ?>
                        </td>

                        <td>

                            <strong>
                                <?= htmlspecialchars($row['item_name'] ?? '-') ?>
                            </strong>

                            <?php if (!empty($row['description'])): ?>

                                <br>

                                <small>
                                    <?= nl2br(htmlspecialchars($row['description'])) ?>
                                </small>

                            <?php endif; ?>

                        </td>

                        <td class="text-right">
                            <?= number_format((float) ($row['qty'] ?? 0), 2, ',', '.') ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['unit_name'] ?? '-') ?>
                        </td>

                        <td class="text-right">
                            Rp <?= number_format((float) ($row['unit_price'] ?? 0), 0, ',', '.') ?>
                        </td>

                        <td class="text-right">
                            <strong>
                                Rp <?= number_format((float) ($row['subtotal'] ?? 0), 0, ',', '.') ?>
                            </strong>
                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="6" style="text-align:center;">
                        Belum ada item.
                    </td>
                </tr>

            <?php endif; ?>

        </tbody>

    </table>

    <div style="display:flex; gap:30px; align-items:flex-start; margin-top:18px;">

        <div style="flex:1;">

            <div class="notes">

                <strong>Catatan:</strong>

                <br><br>

                <?= !empty($item['notes'])
                    ? nl2br(htmlspecialchars($item['notes']))
                    : '-' ?>

            </div>

        </div>

        <div style="width:38%; padding-top:0;">

            <table class="summary">

                <tr>
                    <td>Subtotal</td>
                    <td class="text-right">
                        Rp <?= number_format($subtotal, 0, ',', '.') ?>
                    </td>
                </tr>

                <tr>
                    <td>Pajak</td>
                    <td class="text-right">
                        Rp <?= number_format($taxAmount, 0, ',', '.') ?>
                    </td>
                </tr>

                <tr class="line">
                    <td>Grand Total</td>
                    <td class="text-right">
                        Rp <?= number_format($grandTotal, 0, ',', '.') ?>
                    </td>
                </tr>

            </table>

        </div>

    </div>

    <div class="signature">

        <div>

            <p>Hormat Kami,</p>

            <div class="sign-line">
                Micool.id
            </div>

        </div>

        <div>

            <p>Disetujui Oleh,</p>

            <div class="sign-line">
                Vendor
            </div>

        </div>

    </div>

</div>

</body>
</html>