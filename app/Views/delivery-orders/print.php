<?php
$isBongkar = ($deliveryOrder['sj_type'] ?? 'pasang') === 'bongkar';

$tanggalLabel = $isBongkar ? 'Tanggal Bongkar' : 'Tanggal Kirim';
$jamLabel     = $isBongkar ? 'Jam Bongkar' : 'Jam Kirim';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Print Surat Jalan</title>

    <style>
     body {
        font-family: Arial, sans-serif;
        color: #111;
        font-size: 11px;
        margin: 0;
        padding: 0;
    }

    .page {
        width: 210mm;
        min-height: 297mm;
        padding: 8mm 12mm;
        margin: auto;
        box-sizing: border-box;
    }

    .sj-copy {
        height: 138mm;
        box-sizing: border-box;
        border-bottom: 1px dashed #999;
        padding-bottom: 7mm;
        margin-bottom: 7mm;
    }

    .sj-copy:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        border-bottom: 2px solid #111;
        padding-bottom: 7px;
        margin-bottom: 10px;
    }

    .company {
        width: 70%;
    }

    .company img {
        width: 170px;
        height: auto;
        display: block;
        margin-bottom: 4px;
    }

    .company p {
        margin: 0;
        line-height: 1.35;
        font-size: 11px;
    }

    .doc-title {
        width: 30%;
        text-align: right;
    }

    .doc-title h1 {
        margin: 0;
        font-size: 24px;
        line-height: 1.05;
        letter-spacing: 1px;
    }

    .doc-title p {
        margin: 4px 0 0;
        font-size: 12px;
    }

    .copy-label {
        font-size: 10px !important;
        color: #555;
    }

    .info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
        margin-bottom: 10px;
    }

    .info table {
        width: 100%;
        border-collapse: collapse;
    }

    .info td {
        padding: 2px 0;
        vertical-align: top;
    }

.info td:last-child {
    line-height: 1.45;
}
    .items {
        width: 100%;
        border-collapse: collapse;
        margin-top: 6px;
    }

    .items th,
    .items td {
        border: 1px solid #111;
        padding: 5px 6px;
        vertical-align: top;
    }

    .items th {
        background: #f2f2f2;
        text-align: left;
    }

    .notes {
        border: 1px solid #111;
        min-height: 28px;
        padding: 6px;
        margin-top: 8px;
    }

    .signature {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
        text-align: center;
        margin-top: 22px;
    }

    .signature p {
        margin: 0 0 30px;
    }

    .sign-line {
        border-top: 1px solid #111;
        margin-bottom: 4px;
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
    }

    .btn-print {
        background: #0d6efd;
        color: #fff;
    }

    .btn-back {
        background: #6c757d;
        color: #fff;
        text-decoration: none;
    }

    @page {
        size: A4;
        margin: 8mm;
    }

    @media print {
        .print-actions {
            display: none !important;
        }

        .page {
            width: auto;
            min-height: auto;
            padding: 0;
            margin: 0;
        }

        body {
            margin: 0;
        }
    }
</style>
</head>
<body>


    <div class="page">

        <?php
        $copies = [
            'Micool Copy',
            'Customer Copy'
        ];
        ?>

        <?php foreach ($copies as $copyLabel): ?>

            <div class="sj-copy">

                <div class="header">
                    <div class="company">
                        <img src="<?= asset('images/logo.webp') ?>" alt="Micool Logo">

                        <p>
                            18 Office Park, Jl. TB Simatupang No.18, RT.2/RW.1, Kebagusan,
                            Kec. Ps. Minggu, Kota Jakarta Selatan, DKI Jakarta 12520
                        </p>
                    </div>

                    <div class="doc-title">
                        <h1>SURAT<br>JALAN</h1>
                        <p class="copy-label">
                            <?= ($deliveryOrder['sj_type'] ?? 'pasang') === 'bongkar'
                            ? 'BONGKAR'
                            : 'PASANG / KIRIM'
                            ?>
                        </p>
                        <p class="copy-label"><?= $copyLabel ?></p>
                        <p><strong>No:</strong> <?= htmlspecialchars($deliveryOrder['no_surat_jalan'] ?? '-') ?></p>
                    </div>
                </div>

                <div class="info">
                    <table>
                        <tr>
                            <td>No Rental</td>
                            <td>: <?= htmlspecialchars($deliveryOrder['no_rental'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td>Customer</td>
                            <td>: <?= htmlspecialchars($deliveryOrder['customer_name'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td>No HP</td>
                            <td>: <?= htmlspecialchars($deliveryOrder['customer_phone'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td>Lokasi</td>
                            <td>: <?= nl2br(htmlspecialchars($deliveryOrder['lokasi'] ?? '-')) ?></td>
                        </tr>
                    </table>

                    <table>
                        <tr>
                            <td><?= $tanggalLabel ?></td>
                            <td>: <?= htmlspecialchars($deliveryOrder['tanggal_kirim'] ?? '-') ?></td>
                            <td><?php
                            $isBongkar = ($deliveryOrder['sj_type'] ?? 'pasang') === 'bongkar';
                            $tanggalLabel = $isBongkar ? 'Tanggal Bongkar' : 'Tanggal Kirim';
                            $jamLabel = $isBongkar ? 'Jam Bongkar' : 'Jam Kirim';
                            $teknisiTask = $isBongkar ? 'bongkar' : 'kirim_pasang';
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?= $jamLabel ?></td>
                        <td>: <?= htmlspecialchars($deliveryOrder['jam_kirim'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td>Teknisi</td>
                        <td>: <?= htmlspecialchars($deliveryOrder['technician_names'] ?? '-') ?></td>
                    </tr>
<tr>
    <td>Kendaraan</td>

    <td>
        :
        <?php if (!empty($deliveryOrder['vehicle_id'])): ?>

            <?= htmlspecialchars($deliveryOrder['vehicle_code'] ?? '-') ?>
            -
            <?= htmlspecialchars($deliveryOrder['vehicle_name'] ?? '-') ?>

            <?php if (!empty($deliveryOrder['plate_number'])): ?>
                (<?= htmlspecialchars($deliveryOrder['plate_number']) ?>)
            <?php endif; ?>

        <?php else: ?>

            -

        <?php endif; ?>
    </td>
</tr>

<tr>
    <td>Driver</td>
    <td>: <?= htmlspecialchars($deliveryOrder['driver_name'] ?? '-') ?></td>
</tr>

<tr>
    <td>KM Awal</td>
    <td>
        :
        <?= number_format((int) ($deliveryOrder['km_start'] ?? 0), 0, ',', '.') ?> KM
    </td>
</tr>
                </table>
            </div>

            <table class="items">
                <thead>
                    <tr>
                        <th width="35">No</th>
                        <th>Nama Unit</th>
                        <th width="120">Brand</th>
                        <th width="120">Kategori</th>
                        <th width="70">Jumlah</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($items)): ?>
                        <?php foreach ($items as $index => $item): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>

                                <td><?= htmlspecialchars($item['unit_name'] ?? '-') ?></td>

                                <td><?= htmlspecialchars($item['brand'] ?? '-') ?></td>

                                <td><?= htmlspecialchars($item['kategori'] ?? '-') ?></td>

                                <td><?= htmlspecialchars($item['jumlah'] ?? 0) ?> Unit</td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align:center;">Tidak ada unit.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="notes">
                <strong>Catatan:</strong>
                <?= nl2br(htmlspecialchars($deliveryOrder['catatan'] ?? '-')) ?>
            </div>

            <div class="signature">
                <div>
                    <p>Disiapkan Oleh,</p>
                    <div class="sign-line"></div>
                    <strong>Micool.id</strong>
                </div>

                <div>
                    <p>Teknisi,</p>
                    <div class="sign-line"></div>
                    <strong>Teknisi Micool</strong>
                </div>

                <div>
                    <p>Diterima Oleh,</p>
                    <div class="sign-line"></div>
                    <strong>Customer</strong>
                </div>
            </div>

        </div>

    <?php endforeach; ?>

</div>
<script>
window.onload = function () {
    setTimeout(function () {
        window.print();
    }, 500);
};

window.onafterprint = function () {
    setTimeout(function () {
        window.close();
    }, 300);
};
</script>
</body>
</html>