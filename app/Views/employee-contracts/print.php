<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kontrak Karyawan</title>

    <style>
        body{
            font-family: Arial, sans-serif;
            color:#111;
            font-size:14px;
            line-height:1.6;
            padding:40px;
        }

        .text-center{
            text-align:center;
        }

        .mb-20{
            margin-bottom:20px;
        }

        .mb-30{
            margin-bottom:30px;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        td{
            padding:6px 0;
            vertical-align:top;
        }

        .signature{
            margin-top:80px;
        }

        .signature td{
            width:50%;
            text-align:center;
        }

        .title{
            font-size:22px;
            font-weight:bold;
        }

        .line{
            border-top:1px solid #000;
            width:200px;
            margin:60px auto 0;
        }
    </style>
</head>
<body>

<?php
$typeLabel = [
    'probation' => 'Probation',
    'contract' => 'Kontrak',
    'permanent' => 'Permanent',
    'freelance' => 'Freelance',
    'internship' => 'Internship',
];
?>

<div class="text-center mb-30">
    <div class="title">SURAT KONTRAK KERJA</div>

    <div>
        No:
        <?= htmlspecialchars($item['contract_number'] ?? '-') ?>
    </div>
</div>

<p>
    Pada hari ini dibuat perjanjian kerja antara:
</p>

<table class="mb-30">
    <tr>
        <td width="220">Nama Karyawan</td>
        <td width="20">:</td>
        <td><?= htmlspecialchars($item['employee_name'] ?? '-') ?></td>
    </tr>

    <tr>
        <td>NIK</td>
        <td>:</td>
        <td><?= htmlspecialchars($item['employee_code'] ?? '-') ?></td>
    </tr>

    <tr>
        <td>Jabatan</td>
        <td>:</td>
        <td><?= htmlspecialchars($item['job_title'] ?? '-') ?></td>
    </tr>

    <tr>
        <td>Lokasi Kerja</td>
        <td>:</td>
        <td><?= htmlspecialchars($item['work_location'] ?? '-') ?></td>
    </tr>

    <tr>
        <td>Tipe Kontrak</td>
        <td>:</td>
        <td><?= htmlspecialchars($typeLabel[$item['contract_type'] ?? ''] ?? '-') ?></td>
    </tr>

    <tr>
        <td>Tanggal Mulai</td>
        <td>:</td>
        <td><?= htmlspecialchars($item['start_date'] ?? '-') ?></td>
    </tr>

    <tr>
        <td>Tanggal Selesai</td>
        <td>:</td>
        <td><?= htmlspecialchars($item['end_date'] ?? '-') ?></td>
    </tr>

    <tr>
        <td>Salary / Gaji</td>
        <td>:</td>
        <td>
            Rp <?= number_format((float) ($item['salary'] ?? 0), 0, ',', '.') ?>
        </td>
    </tr>
</table>

<p>
    Dengan ini kedua belah pihak sepakat untuk menjalankan hubungan kerja sesuai dengan ketentuan perusahaan yang berlaku.
</p>

<?php if (!empty($item['notes'])): ?>
    <div class="mb-30">
        <strong>Catatan:</strong><br>
        <?= nl2br(htmlspecialchars($item['notes'])) ?>
    </div>
<?php endif; ?>

<table class="signature">
    <tr>
        <td>
            Pihak Perusahaan

            <div class="line"></div>
        </td>

        <td>
            Karyawan

            <div class="line"></div>
        </td>
    </tr>
</table>

<script>
window.onload = function() {
    window.print();
};
</script>

</body>
</html>