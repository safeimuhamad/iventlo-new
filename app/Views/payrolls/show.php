<?php
$status = strtolower($payroll['status'] ?? 'draft');

$statusClass = match ($status) {
    'draft' => 'bg-secondary bg-opacity-10 text-secondary',
    'generated' => 'bg-warning bg-opacity-10 text-warning',
    'paid' => 'bg-success bg-opacity-10 text-success',
    default => 'bg-primary bg-opacity-10 text-primary'
};

$minutes = (int) ($payroll['overtime_minutes'] ?? 0);
$hours = floor($minutes / 60);
$remainingMinutes = $minutes % 60;
?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Detail Payroll
            </h3>

            <p class="mb-0 text-body">
                <?= htmlspecialchars($payroll['full_name'] ?? '-') ?>
            </p>
        </div>

        <div class="d-flex justify-content-between justify-content-md-end align-items-center flex-wrap gap-3">

            <a
                href="<?= url('payrolls') ?>?period_id=<?= $payroll['payroll_period_id'] ?>"
                class="btn btn-light erp-btn"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Kembali
            </a>

            <a
                href="<?= url('payrolls-edit') ?>?id=<?= $payroll['id'] ?>"
                class="btn btn-outline-primary erp-btn"
            >
                <i class="ri-edit-line me-1"></i>
                Edit
            </a>

            <button
                type="button"
                class="btn btn-outline-primary erp-btn"
                onclick="window.open('<?= url('payrolls-print') ?>?id=<?= $payroll['id'] ?>','_blank')"
            >
                <i class="ri-printer-line me-1"></i>
                Print
            </button>

            <div class="dropdown">

                <button
                    class="btn btn-primary text-white dropdown-toggle erp-btn"
                    type="button"
                    data-bs-toggle="dropdown"
                >
                    <i class="ri-settings-3-line me-1"></i>
                    Actions
                </button>

                <ul class="dropdown-menu dropdown-menu-end erp-dropdown-menu">

                    <?php if (($payroll['status'] ?? '') !== 'paid'): ?>
                        <li>

                            <a
                                href="<?= url('payrolls-paid') ?>?id=<?= $payroll['id'] ?>"
                                class="dropdown-item erp-dropdown-item"
                                onclick="return confirm('Tandai payroll ini sudah dibayar?')"
                            >
                                <div class="erp-dropdown-title text-success">
                                    <i class="ri-check-double-line me-2"></i>
                                    Mark As Paid
                                </div>

                                <div class="erp-dropdown-desc">
                                    Tandai payroll sudah dibayarkan
                                </div>
                            </a>

                        </li>
                    <?php endif; ?>

                </ul>

            </div>

        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="row g-4">

        <div class="col-md-3">

            <div class="erp-detail-label">
                Status
            </div>

            <div class="erp-detail-value">
                <span class="default-badge <?= $statusClass ?>">
                    <?= ucfirst(htmlspecialchars($status)) ?>
                </span>
            </div>

        </div>

        <div class="col-md-3">

            <div class="erp-detail-label">
                Karyawan
            </div>

            <div class="erp-detail-value">
                <?= htmlspecialchars($payroll['employee_code'] ?? '-') ?>
            </div>

        </div>

        <div class="col-md-3">

            <div class="erp-detail-label">
                Hari Hadir
            </div>

            <div class="erp-detail-value">
                <?= (int) ($payroll['attendance_days'] ?? 0) ?> Hari
            </div>

        </div>

        <div class="col-md-3">

            <div class="erp-detail-label">
                Gaji Bersih
            </div>

            <div class="erp-detail-value text-success">
                Rp <?= number_format($payroll['net_salary'] ?? 0, 0, ',', '.') ?>
            </div>

        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">

        <h4 class="erp-detail-section-title">
            Informasi Payroll
        </h4>

    </div>

    <div class="p-20">

        <div class="row g-4">

            <div class="col-md-4">

                <div class="erp-detail-label">
                    Karyawan
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($payroll['full_name'] ?? '-') ?>
                </div>

            </div>

            <div class="col-md-4">

                <div class="erp-detail-label">
                    Kode Karyawan
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($payroll['employee_code'] ?? '-') ?>
                </div>

            </div>

            <div class="col-md-4">

                <div class="erp-detail-label">
                    Status Payroll
                </div>

                <div class="erp-detail-value">
                    <span class="default-badge <?= $statusClass ?>">
                        <?= ucfirst(htmlspecialchars($status)) ?>
                    </span>
                </div>

            </div>

            <div class="col-md-4">

                <div class="erp-detail-label">
                    Divisi
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($payroll['department_name'] ?? '-') ?>
                </div>

            </div>

            <div class="col-md-4">

                <div class="erp-detail-label">
                    Jabatan
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($payroll['position_name'] ?? '-') ?>
                </div>

            </div>

            <div class="col-md-4">

                <div class="erp-detail-label">
                    Hari Hadir
                </div>

                <div class="erp-detail-value">
                    <?= (int) ($payroll['attendance_days'] ?? 0) ?> Hari
                </div>

            </div>

            <div class="col-md-4">

                <div class="erp-detail-label">
                    Total Terlambat
                </div>

                <div class="erp-detail-value text-warning">
                    <?= (int) ($payroll['late_minutes'] ?? 0) ?> Menit
                </div>

            </div>

            <div class="col-md-4">

                <div class="erp-detail-label">
                    Total Lembur
                </div>

                <div class="erp-detail-value text-primary">
                    <?= $hours ?> Jam <?= $remainingMinutes ?> Menit
                </div>

            </div>

        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">

        <h4 class="erp-detail-section-title">
            Rincian Payroll
        </h4>

    </div>

    <div class="default-table-area mx-minus-1">

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <tbody>

                    <tr>
                        <td>Gaji Pokok</td>
                        <td class="text-end fw-semibold">
                            Rp <?= number_format($payroll['basic_salary'] ?? 0, 0, ',', '.') ?>
                        </td>
                    </tr>

                    <tr>
                        <td>Uang Lembur</td>
                        <td class="text-end fw-semibold">
                            Rp <?= number_format($payroll['overtime_amount'] ?? 0, 0, ',', '.') ?>
                        </td>
                    </tr>

                    <tr>
                        <td>Bonus</td>
                        <td class="text-end fw-semibold">
                            Rp <?= number_format($payroll['bonus_amount'] ?? 0, 0, ',', '.') ?>
                        </td>
                    </tr>

                    <tr>
                        <td>Potongan Lain</td>
                        <td class="text-end text-danger fw-semibold">
                            Rp <?= number_format(
                                max(
                                    0,
                                    ((float) ($payroll['deduction_amount'] ?? 0) -
                                    (float) ($payroll['cash_advance_deduction'] ?? 0))
                                ),
                                0,
                                ',',
                                '.'
                            ) ?>
                        </td>
                    </tr>

                    <?php if (($payroll['cash_advance_deduction'] ?? 0) > 0): ?>

                        <tr>
                            <td>Potongan Kasbon</td>
                            <td class="text-end text-danger fw-semibold">
                                Rp <?= number_format($payroll['cash_advance_deduction'], 0, ',', '.') ?>
                            </td>
                        </tr>

                    <?php endif; ?>

                    <tr>
                        <td>BPJS</td>
                        <td class="text-end text-danger fw-semibold">
                            Rp <?= number_format($payroll['bpjs_amount'] ?? 0, 0, ',', '.') ?>
                        </td>
                    </tr>

                    <tr>
                        <td>Pajak</td>
                        <td class="text-end text-danger fw-semibold">
                            Rp <?= number_format($payroll['tax_amount'] ?? 0, 0, ',', '.') ?>
                        </td>
                    </tr>

                    <tr class="table-success">

                        <td class="fw-bold">
                            GAJI BERSIH
                        </td>

                        <td class="text-end fw-bold">
                            Rp <?= number_format($payroll['net_salary'] ?? 0, 0, ',', '.') ?>
                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>