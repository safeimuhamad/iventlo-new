<?php
$status = strtolower($payrollPeriod['status'] ?? 'draft');

$statusClass = match ($status) {
    'draft' => 'bg-secondary bg-opacity-10 text-secondary',
    'open' => 'bg-primary bg-opacity-10 text-primary',
    'generated' => 'bg-warning bg-opacity-10 text-warning',
    'paid' => 'bg-success bg-opacity-10 text-success',
    'closed' => 'bg-dark bg-opacity-10 text-dark',
    default => 'bg-secondary bg-opacity-10 text-secondary'
};
?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Detail Periode Payroll
            </h3>

            <p class="mb-0 text-body">
                <?= htmlspecialchars($payrollPeriod['period_name'] ?? '-') ?>
            </p>
        </div>

        <div class="d-flex justify-content-between justify-content-md-end align-items-center flex-wrap gap-3">

            <a
                href="<?= url('payroll-periods') ?>"
                class="btn btn-light erp-btn"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Kembali
            </a>

            <a
                href="<?= url('payroll-periods-edit') ?>?id=<?= $payrollPeriod['id'] ?>"
                class="btn btn-outline-primary erp-btn"
            >
                <i class="ri-edit-line me-1"></i>
                Edit
            </a>

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

                    <li>
                        <a
                            href="<?= url('payrolls-generate') ?>?period_id=<?= $payrollPeriod['id'] ?>"
                            class="dropdown-item erp-dropdown-item"
                            onclick="return confirm('Generate payroll untuk periode ini?')"
                        >
                            <div class="erp-dropdown-title text-primary">
                                <i class="ri-calculator-line me-2"></i>
                                Generate Payroll
                            </div>

                            <div class="erp-dropdown-desc">
                                Buat payroll berdasarkan periode ini
                            </div>
                        </a>
                    </li>

                    <li>
                        <a
                            href="<?= url('payroll-periods-delete') ?>?id=<?= $payrollPeriod['id'] ?>"
                            class="dropdown-item erp-dropdown-item"
                            onclick="return confirm('Yakin ingin menghapus periode payroll ini?')"
                        >
                            <div class="erp-dropdown-title text-danger">
                                <i class="ri-delete-bin-line me-2"></i>
                                Hapus Periode Payroll
                            </div>

                            <div class="erp-dropdown-desc">
                                Hapus data periode payroll
                            </div>
                        </a>
                    </li>

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
                    <?= htmlspecialchars(ucwords(str_replace('_', ' ', $status))) ?>
                </span>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">
                Nama Periode
            </div>

            <div class="erp-detail-value">
                <?= htmlspecialchars($payrollPeriod['period_name'] ?? '-') ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">
                Tanggal Payroll
            </div>

            <div class="erp-detail-value">
                <?= !empty($payrollPeriod['payroll_date'])
                    ? date('d M Y', strtotime($payrollPeriod['payroll_date']))
                    : '-' ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">
                Periode
            </div>

            <div class="erp-detail-value">
                <?= !empty($payrollPeriod['start_date'])
                    ? date('d M Y', strtotime($payrollPeriod['start_date']))
                    : '-' ?>
                -
                <?= !empty($payrollPeriod['end_date'])
                    ? date('d M Y', strtotime($payrollPeriod['end_date']))
                    : '-' ?>
            </div>
        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title">
            Informasi Periode Payroll
        </h4>
    </div>

    <div class="p-20">

        <div class="row g-4">

            <div class="col-md-4">
                <div class="erp-detail-label">
                    Nama Periode
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($payrollPeriod['period_name'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">
                    Status
                </div>

                <div class="erp-detail-value">
                    <span class="default-badge <?= $statusClass ?>">
                        <?= htmlspecialchars(ucwords(str_replace('_', ' ', $status))) ?>
                    </span>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">
                    Tanggal Payroll
                </div>

                <div class="erp-detail-value">
                    <?= !empty($payrollPeriod['payroll_date'])
                        ? date('d M Y', strtotime($payrollPeriod['payroll_date']))
                        : '-' ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">
                    Tanggal Mulai
                </div>

                <div class="erp-detail-value">
                    <?= !empty($payrollPeriod['start_date'])
                        ? date('d M Y', strtotime($payrollPeriod['start_date']))
                        : '-' ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">
                    Tanggal Selesai
                </div>

                <div class="erp-detail-value">
                    <?= !empty($payrollPeriod['end_date'])
                        ? date('d M Y', strtotime($payrollPeriod['end_date']))
                        : '-' ?>
                </div>
            </div>

            <div class="col-md-12">
                <div class="erp-detail-label">
                    Catatan
                </div>

                <div class="erp-detail-value">
                    <?= nl2br(htmlspecialchars($payrollPeriod['notes'] ?? '-')) ?>
                </div>
            </div>

        </div>

    </div>

</div>