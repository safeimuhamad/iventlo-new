<?php
$status = $item['status'] ?? '';

$statusLabel = [
    'waiting_supervisor_approval' => 'Menunggu Persetujuan Atasan',
    'waiting_finance_approval' => 'Menunggu Persetujuan Keuangan',
    'waiting_disbursement' => 'Menunggu Pencairan',
    'paid' => 'Paid',
    'rejected' => 'Rejected',
    'cancelled' => 'Cancelled',
];

$statusClass = [
    'waiting_supervisor_approval' => 'bg-warning bg-opacity-10 text-warning',
    'waiting_finance_approval' => 'bg-info bg-opacity-10 text-info',
    'waiting_disbursement' => 'bg-primary bg-opacity-10 text-primary',
    'paid' => 'bg-success bg-opacity-10 text-success',
    'rejected' => 'bg-danger bg-opacity-10 text-danger',
    'cancelled' => 'bg-secondary bg-opacity-10 text-secondary',
];

$isOwnRequest =
    (int) ($item['created_by'] ?? 0) ===
    (int) ($_SESSION['user_id'] ?? 0);
?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Detail Kasbon Karyawan
            </h3>

            <p class="mb-0 text-body">
                <?= htmlspecialchars($item['cash_advance_number'] ?? '-') ?>
            </p>
        </div>

        <div class="d-flex justify-content-between justify-content-md-end align-items-center flex-wrap gap-3">

            <a
                href="<?= url('employee-cash-advances') ?>"
                class="btn btn-light erp-btn"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Kembali
            </a>

            <?php if (
                can('employee_cash_advance.edit') &&
                $status === 'waiting_supervisor_approval'
            ): ?>
                <a
                    href="<?= url('employee-cash-advances-edit') ?>?id=<?= $item['id'] ?>"
                    class="btn btn-outline-primary erp-btn"
                >
                    <i class="ri-edit-line me-1"></i>
                    Edit
                </a>
            <?php endif; ?>

            <?php if (
                (
                    can('employee_cash_advance.supervisor_approve') ||
                    can('employee_cash_advance.finance_approve') ||
                    can('employee_cash_advance.disburse')
                ) &&
                !$isOwnRequest
            ): ?>

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

                        <?php if (
                            can('employee_cash_advance.supervisor_approve') &&
                            $status === 'waiting_supervisor_approval'
                        ): ?>
                            <li>
                                <a
                                    href="#approval-section"
                                    class="dropdown-item erp-dropdown-item"
                                >
                                    <div class="erp-dropdown-title text-success">
                                        <i class="ri-check-line me-2"></i>
                                        Persetujuan Atasan
                                    </div>

                                    <div class="erp-dropdown-desc">
                                        Approve pengajuan kasbon
                                    </div>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (
                            can('employee_cash_advance.finance_approve') &&
                            $status === 'waiting_finance_approval'
                        ): ?>
                            <li>
                                <a
                                    href="#approval-section"
                                    class="dropdown-item erp-dropdown-item"
                                >
                                    <div class="erp-dropdown-title text-success">
                                        <i class="ri-money-dollar-circle-line me-2"></i>
                                        Persetujuan Keuangan
                                    </div>

                                    <div class="erp-dropdown-desc">
                                        Approve nominal kasbon
                                    </div>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (
                            can('employee_cash_advance.disburse') &&
                            $status === 'waiting_disbursement'
                        ): ?>
                            <li>
                                <a
                                    href="#disbursement-section"
                                    class="dropdown-item erp-dropdown-item"
                                >
                                    <div class="erp-dropdown-title text-primary">
                                        <i class="ri-bank-card-line me-2"></i>
                                        Pencairan Kasbon
                                    </div>

                                    <div class="erp-dropdown-desc">
                                        Cairkan dana kasbon
                                    </div>
                                </a>
                            </li>
                        <?php endif; ?>

                    </ul>

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="row g-4">

        <div class="col-md-3">
            <div class="erp-detail-label">Status</div>
            <div class="erp-detail-value">
                <span class="default-badge <?= $statusClass[$status] ?? 'bg-secondary bg-opacity-10 text-secondary' ?>">
                    <?= $statusLabel[$status] ?? $status ?>
                </span>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">Karyawan</div>
            <div class="erp-detail-value">
                <?= htmlspecialchars($item['employee_code'] ?? '-') ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">Nominal Pengajuan</div>
            <div class="erp-detail-value text-primary">
                Rp <?= number_format((float) ($item['amount'] ?? 0), 0, ',', '.') ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">Nominal Disetujui</div>
            <div class="erp-detail-value text-success">
                Rp <?= number_format((float) ($item['approved_amount'] ?? 0), 0, ',', '.') ?>
            </div>
        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title">
            Informasi Kasbon
        </h4>
    </div>

    <div class="p-20">

        <div class="row g-4">

            <div class="col-md-4">
                <div class="erp-detail-label">Nomor Kasbon</div>
                <div class="erp-detail-value">
                    <?= htmlspecialchars($item['cash_advance_number'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">Karyawan</div>
                <div class="erp-detail-value">
                    <?= htmlspecialchars($item['employee_name'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">Kode Karyawan</div>
                <div class="erp-detail-value">
                    <?= htmlspecialchars($item['employee_code'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">Tanggal Pengajuan</div>
                <div class="erp-detail-value">
                    <?= !empty($item['request_date'])
                        ? date('d M Y', strtotime($item['request_date']))
                        : '-' ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">Nominal Pengajuan</div>
                <div class="erp-detail-value text-primary">
                    Rp <?= number_format((float) ($item['amount'] ?? 0), 0, ',', '.') ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">Nominal Disetujui</div>
                <div class="erp-detail-value text-success">
                    Rp <?= number_format((float) ($item['approved_amount'] ?? 0), 0, ',', '.') ?>
                </div>
            </div>

            <div class="col-md-12">
                <div class="erp-detail-label">Keperluan</div>
                <div class="erp-detail-value">
                    <?= htmlspecialchars($item['purpose'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-12">
                <div class="erp-detail-label">Keterangan</div>
                <div class="erp-detail-value">
                    <?= nl2br(htmlspecialchars($item['description'] ?? '-')) ?>
                </div>
            </div>

        </div>

    </div>

</div>