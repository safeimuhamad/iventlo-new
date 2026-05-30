<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Slip Gaji</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            color: #111827;
            font-size: 13px;
            margin: 0;
            padding: 30px;
            background: #f3f4f6;
        }

        .slip {
            max-width: 760px;
            margin: 0 auto;
            background: #ffffff;
            padding: 28px;
            border: 1px solid #e5e7eb;
        }

        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #111827;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .company h2 {
            margin: 0 0 5px;
            font-size: 22px;
        }

        .company p,
        .meta p {
            margin: 2px 0;
            color: #4b5563;
        }

        .title {
            text-align: center;
            margin: 20px 0;
        }

        .title h3 {
            margin: 0;
            font-size: 20px;
            text-transform: uppercase;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px 30px;
            margin-bottom: 20px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px dashed #e5e7eb;
            padding-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th,
        table td {
            border: 1px solid #d1d5db;
            padding: 10px;
        }

        table th {
            background: #f9fafb;
            text-align: left;
        }

        .text-end {
            text-align: right;
        }

        .net {
            background: #ecfdf5;
            font-weight: bold;
        }

        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            text-align: center;
        }

        .sign {
            width: 220px;
        }

        .sign-space {
            height: 70px;
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }

            .slip {
                border: none;
                max-width: 100%;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="no-print" style="max-width:760px;margin:0 auto 15px;text-align:right;">
        <button onclick="window.print()" style="padding:8px 14px;cursor:pointer;">
            Print
        </button>
    </div>

    <div class="slip">
        <div class="header">
            <div class="company" style="display:flex;align-items:center;gap:5px;">

                <img 
                    src="<?= asset('images/logo.webp') ?>"
                    alt="Logo"
                    style="height:70px;width:auto;">

                <div>
                    <h2 style="margin:0 0 5px;">
                        PT Micool Berkah Bersama
                    </h2>

                    <p>Slip Gaji Karyawan</p>
                    <p>Dokumen internal perusahaan</p>
                </div>

            </div>

            <div class="meta">
                <p><strong>Periode:</strong> <?= htmlspecialchars($payroll['period_name'] ?? '-') ?></p>
                <p><strong>Tanggal Payroll:</strong> <?= htmlspecialchars($payroll['payroll_date'] ?? '-') ?></p>
            </div>
        </div>

        <div class="title">
            <h3>Slip Gaji</h3>
        </div>

        <div class="info-grid">
            <div class="info-row">
                <span>Nama</span>
                <strong><?= htmlspecialchars($payroll['full_name'] ?? '-') ?></strong>
            </div>

            <div class="info-row">
                <span>Kode</span>
                <strong><?= htmlspecialchars($payroll['employee_code'] ?? '-') ?></strong>
            </div>

            <div class="info-row">
                <span>Divisi</span>
                <strong><?= htmlspecialchars($payroll['department_name'] ?? '-') ?></strong>
            </div>

            <div class="info-row">
                <span>Jabatan</span>
                <strong><?= htmlspecialchars($payroll['position_name'] ?? '-') ?></strong>
            </div>

            <div class="info-row">
                <span>Hadir</span>
                <strong><?= (int) ($payroll['attendance_days'] ?? 0) ?> hari</strong>
            </div>

            <div class="info-row">
                <span>Terlambat</span>
                <strong><?= (int) ($payroll['late_minutes'] ?? 0) ?> menit</strong>
            </div>
        </div>

        <table>
            <tbody>
                <tr>
                    <th>Gaji Pokok</th>
                    <td class="text-end">Rp <?= number_format($payroll['basic_salary'] ?? 0, 0, ',', '.') ?></td>
                </tr>
                <tr>
                    <th>Tunjangan</th>
                    <td class="text-end">Rp <?= number_format($payroll['allowance_amount'] ?? 0, 0, ',', '.') ?></td>
                </tr>

                <tr>
                    <th>Lembur</th>
                    <td class="text-end">Rp <?= number_format($payroll['overtime_amount'] ?? 0, 0, ',', '.') ?></td>
                </tr>
                <tr>
                    <th>Bonus</th>
                    <td class="text-end">Rp <?= number_format($payroll['bonus_amount'] ?? 0, 0, ',', '.') ?></td>
                </tr>
                <tr>
                    <th>Potongan Lain</th>
                    <td class="text-end">
                        Rp <?= number_format(
                            max(0, ((float)($payroll['deduction_amount'] ?? 0) - (float)($payroll['cash_advance_deduction'] ?? 0))),
                            0,
                            ',',
                            '.'
                        ) ?>
                    </td>
                </tr>
                <?php if (($payroll['cash_advance_deduction'] ?? 0) > 0): ?>
                <tr>
                    <th>Potongan Kasbon</th>
                    <td class="text-end">
                        Rp <?= number_format((float)($payroll['cash_advance_deduction'] ?? 0), 0, ',', '.') ?>
                    </td>
                </tr>
                <?php endif; ?>

                <tr>
                    <th>BPJS</th>
                    <td class="text-end">Rp <?= number_format($payroll['bpjs_amount'] ?? 0, 0, ',', '.') ?></td>
                </tr>

                <tr>
                    <th>Pajak</th>
                    <td class="text-end">Rp <?= number_format($payroll['tax_amount'] ?? 0, 0, ',', '.') ?></td>
                </tr>

                <tr class="net">
                    <th>Gaji Bersih</th>
                    <td class="text-end">Rp <?= number_format($payroll['net_salary'] ?? 0, 0, ',', '.') ?></td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <div class="sign">
                <p>Dibuat Oleh,</p>
                <div class="sign-space"></div>
                <strong>HR / Finance</strong>
            </div>

            <div class="sign">
                <p>Diterima Oleh,</p>
                <div class="sign-space"></div>
                <strong><?= htmlspecialchars($payroll['full_name'] ?? '-') ?></strong>
            </div>
        </div>
    </div>

    <script>
        window.print();

        window.onafterprint = function () {
            window.close();
        };
    </script>

</body>
</html>