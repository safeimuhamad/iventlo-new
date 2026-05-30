<?php
$isActive = (int) ($approvalMatrix['is_active'] ?? 1) === 1;

$statusClass = $isActive
    ? 'bg-success bg-opacity-10 text-success'
    : 'bg-secondary bg-opacity-10 text-secondary';

$statusLabel = $isActive ? 'Aktif' : 'Nonaktif';

$minAmount = (float) ($approvalMatrix['min_amount'] ?? 0);
$maxAmount = $approvalMatrix['max_amount'] ?? null;

$amountRange = 'Rp ' . number_format($minAmount, 0, ',', '.') . ' - ';

$amountRange .= !empty($maxAmount)
    ? 'Rp ' . number_format((float) $maxAmount, 0, ',', '.')
    : 'Unlimited';
?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Detail DOA Matrix
            </h3>

            <p class="mb-0 text-body">
                <?= htmlspecialchars($approvalMatrix['module_name'] ?? '-') ?>
            </p>
        </div>

        <div class="d-flex justify-content-between justify-content-md-end align-items-center flex-wrap gap-3">

            <a
                href="<?= url('approval-matrices') ?>"
                class="btn btn-light erp-btn"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Kembali
            </a>

            <a
                href="<?= url('approval-matrices-edit') ?>?id=<?= $approvalMatrix['id'] ?>"
                class="btn btn-outline-primary erp-btn"
            >
                <i class="ri-edit-line me-1"></i>
                Edit
            </a>

            <a
                href="<?= url('approval-matrices-delete') ?>?id=<?= $approvalMatrix['id'] ?>"
                class="btn btn-outline-danger erp-btn"
                onclick="return confirm('Yakin ingin menghapus DOA Matrix ini?')"
            >
                <i class="ri-delete-bin-line me-1"></i>
                Hapus
            </a>

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
                    <?= htmlspecialchars($statusLabel) ?>
                </span>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">
                Module
            </div>

            <div class="erp-detail-value">
                <?= htmlspecialchars($approvalMatrix['module_name'] ?? '-') ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">
                Approval Level
            </div>

            <div class="erp-detail-value">
                <span class="default-badge bg-info bg-opacity-10 text-info">
                    Level <?= (int) ($approvalMatrix['approval_level'] ?? 1) ?>
                </span>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">
                Range Nominal
            </div>

            <div class="erp-detail-value text-primary">
                <?= htmlspecialchars($amountRange) ?>
            </div>
        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title">
            Informasi DOA Matrix
        </h4>
    </div>

    <div class="p-20">

        <div class="row g-4">

            <div class="col-md-6">
                <div class="erp-detail-label">
                    Module
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($approvalMatrix['module_name'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="erp-detail-label">
                    Tipe Dokumen
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($approvalMatrix['document_type'] ?? 'Semua tipe dokumen') ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="erp-detail-label">
                    Department
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($approvalMatrix['department_name'] ?? 'Semua Department') ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="erp-detail-label">
                    Approval Level
                </div>

                <div class="erp-detail-value">
                    <span class="default-badge bg-info bg-opacity-10 text-info">
                        Level <?= (int) ($approvalMatrix['approval_level'] ?? 1) ?>
                    </span>
                </div>
            </div>

            <div class="col-md-6">
                <div class="erp-detail-label">
                    Range Nominal
                </div>

                <div class="erp-detail-value text-primary">
                    <?= htmlspecialchars($amountRange) ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="erp-detail-label">
                    Status
                </div>

                <div class="erp-detail-value">
                    <span class="default-badge <?= $statusClass ?>">
                        <?= htmlspecialchars($statusLabel) ?>
                    </span>
                </div>
            </div>

            <div class="col-md-6">
                <div class="erp-detail-label">
                    Approver Role
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($approvalMatrix['approver_role_name'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="erp-detail-label">
                    Approver User
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($approvalMatrix['approver_user_name'] ?? '-') ?>
                </div>
            </div>

        </div>

    </div>

</div>