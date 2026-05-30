<?php
$totalEmployee = count($payrolls ?? []);

$totalBasicSalary = 0;
$totalOvertime = 0;
$totalNetSalary = 0;

foreach (($payrolls ?? []) as $payroll) {
    $totalBasicSalary += (float) ($payroll['basic_salary'] ?? 0);
    $totalOvertime += (float) ($payroll['overtime_amount'] ?? 0);
    $totalNetSalary += (float) ($payroll['net_salary'] ?? 0);
}

$statusLabels = [
    'draft' => 'Draft',
    'processed' => 'Diproses',
    'paid' => 'Dibayar'
];

$statusClass = [
    'draft' => 'bg-secondary bg-opacity-10 text-secondary',
    'processed' => 'bg-warning bg-opacity-10 text-warning',
    'paid' => 'bg-success bg-opacity-10 text-success'
];
?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Payroll Karyawan
            </h3>

            <p class="text-body fs-14 mb-0">
                <?= htmlspecialchars($period['period_name'] ?? '-') ?>
            </p>
        </div>

        <a
            href="<?= url('payroll-periods-show') ?>?id=<?= $periodId ?>"
            class="btn btn-light erp-btn"
        >
            <i class="ri-arrow-left-line me-1"></i>
            Kembali
        </a>

    </div>

</div>

<div class="row g-3 mb-4">

    <div class="col-md-3">
        <div class="card bg-white rounded-10 border border-white p-20 h-100">
            <div class="erp-detail-label">
                Total Karyawan
            </div>

            <div class="erp-detail-value text-primary">
                <?= number_format($totalEmployee, 0, ',', '.') ?>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-white rounded-10 border border-white p-20 h-100">
            <div class="erp-detail-label">
                Total Gaji Pokok
            </div>

            <div class="erp-detail-value text-primary">
                Rp <?= number_format($totalBasicSalary, 0, ',', '.') ?>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-white rounded-10 border border-white p-20 h-100">
            <div class="erp-detail-label">
                Total Lembur
            </div>

            <div class="erp-detail-value text-warning">
                Rp <?= number_format($totalOvertime, 0, ',', '.') ?>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-white rounded-10 border border-white p-20 h-100">
            <div class="erp-detail-label">
                Total Payroll
            </div>

            <div class="erp-detail-value text-success">
                Rp <?= number_format($totalNetSalary, 0, ',', '.') ?>
            </div>
        </div>
    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title">
            Daftar Payroll Karyawan
        </h4>
    </div>

    <div class="default-table-area mx-minus-1">

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead>
                    <tr>
                        <th>Nama Karyawan</th>
                        <th>Kode</th>
                        <th>Divisi</th>
                        <th>Jabatan</th>
                        <th>Hadir</th>
                        <th>Terlambat</th>
                        <th>Lembur</th>
                        <th class="text-end">Gaji Pokok</th>
                        <th class="text-end">Lembur</th>
                        <th class="text-end">Gaji Bersih</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($payrolls)): ?>

                        <?php foreach ($payrolls as $payroll): ?>

                            <?php
                            $status = $payroll['status'] ?? '';

                            $minutes = (int) ($payroll['overtime_minutes'] ?? 0);
                            $hours = floor($minutes / 60);
                            $remainingMinutes = $minutes % 60;
                            ?>

                            <tr>

                                <td>
                                    <a
                                        href="<?= url('payrolls-show') ?>?id=<?= $payroll['id'] ?>"
                                        class="fw-semibold text-primary text-decoration-none"
                                    >
                                        <?= htmlspecialchars($payroll['full_name'] ?? '-') ?>
                                    </a>
                                </td>

                                <td>
                                    <?= htmlspecialchars($payroll['employee_code'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($payroll['department_name'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($payroll['position_name'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= (int) ($payroll['attendance_days'] ?? 0) ?> hari
                                </td>

                                <td>
                                    <?= (int) ($payroll['late_minutes'] ?? 0) ?> menit
                                </td>

                                <td>
                                    <?= $hours ?> jam
                                    <?php if ($remainingMinutes > 0): ?>
                                        <?= $remainingMinutes ?> menit
                                    <?php endif; ?>
                                </td>

                                <td class="text-end">
                                    <?php if (($payroll['basic_salary'] ?? 0) > 0): ?>
                                        Rp <?= number_format($payroll['basic_salary'] ?? 0, 0, ',', '.') ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>

                                <td class="text-end">
                                    <?php if (($payroll['overtime_amount'] ?? 0) > 0): ?>
                                        Rp <?= number_format($payroll['overtime_amount'] ?? 0, 0, ',', '.') ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>

                                <td class="text-end fw-semibold text-success">
                                    <?php if (($payroll['net_salary'] ?? 0) > 0): ?>
                                        Rp <?= number_format($payroll['net_salary'] ?? 0, 0, ',', '.') ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <span class="default-badge <?= $statusClass[$status] ?? 'bg-secondary bg-opacity-10 text-secondary' ?>">
                                        <?= $statusLabels[$status] ?? '-' ?>
                                    </span>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                        <tr class="table-light">

                            <td colspan="7" class="text-end fw-bold">
                                TOTAL
                            </td>

                            <td class="text-end fw-bold text-primary">
                                Rp <?= number_format($totalBasicSalary, 0, ',', '.') ?>
                            </td>

                            <td class="text-end fw-bold text-warning">
                                Rp <?= number_format($totalOvertime, 0, ',', '.') ?>
                            </td>

                            <td class="text-end fw-bold text-success">
                                Rp <?= number_format($totalNetSalary, 0, ',', '.') ?>
                            </td>

                            <td></td>

                        </tr>

                    <?php else: ?>

                        <tr>
                            <td colspan="11" class="text-center text-muted py-4">
                                Belum ada data payroll.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

    <div class="d-flex justify-content-center justify-content-sm-between align-items-center text-center flex-wrap gap-2 showing-wrap pt-15 p-20 border-top">

        <span class="fs-15">

            Showing

            <?= ($totalRows ?? 0) > 0 ? (($currentPage - 1) * $limit + 1) : 0 ?>

            to

            <?= min($currentPage * $limit, $totalRows ?? 0) ?>

            of

            <?= (int) ($totalRows ?? 0) ?> entries

        </span>

        <?php
        $queryString = '&period_id=' . urlencode($periodId ?? '');

        $startPage = max(1, $currentPage - 2);
        $endPage = min($totalPages ?? 1, $currentPage + 2);
        ?>

        <?php if (($totalPages ?? 1) > 1): ?>

            <nav class="custom-pagination">

                <ul class="pagination mb-0 justify-content-center">

                    <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">

                        <a
                            class="page-link icon"
                            href="<?= url('payrolls') ?>?p=<?= ($currentPage - 1) . $queryString ?>"
                        >
                            <i class="material-symbols-outlined">west</i>
                        </a>

                    </li>

                    <?php if ($startPage > 1): ?>

                        <li class="page-item">
                            <a
                                class="page-link"
                                href="<?= url('payrolls') ?>?p=1<?= $queryString ?>"
                            >
                                1
                            </a>
                        </li>

                        <?php if ($startPage > 2): ?>
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        <?php endif; ?>

                    <?php endif; ?>

                    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>

                        <li class="page-item">
                            <a
                                class="page-link <?= $currentPage == $i ? 'active' : '' ?>"
                                href="<?= url('payrolls') ?>?p=<?= $i . $queryString ?>"
                            >
                                <?= $i ?>
                            </a>
                        </li>

                    <?php endfor; ?>

                    <?php if ($endPage < ($totalPages ?? 1)): ?>

                        <?php if ($endPage < ($totalPages ?? 1) - 1): ?>
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        <?php endif; ?>

                        <li class="page-item">
                            <a
                                class="page-link"
                                href="<?= url('payrolls') ?>?p=<?= $totalPages . $queryString ?>"
                            >
                                <?= $totalPages ?>
                            </a>
                        </li>

                    <?php endif; ?>

                    <li class="page-item <?= $currentPage >= ($totalPages ?? 1) ? 'disabled' : '' ?>">

                        <a
                            class="page-link icon"
                            href="<?= url('payrolls') ?>?p=<?= ($currentPage + 1) . $queryString ?>"
                        >
                            <i class="material-symbols-outlined">east</i>
                        </a>

                    </li>

                </ul>

            </nav>

        <?php endif; ?>

    </div>

</div>