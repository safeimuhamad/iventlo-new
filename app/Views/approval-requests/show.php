<?php
$statusLabels = [
    'waiting_approval' => 'Menunggu Approval',
    'approved' => 'Disetujui',
    'rejected' => 'Ditolak',
    'cancelled' => 'Dibatalkan'
];

$statusClass = [
    'waiting_approval' => 'bg-warning bg-opacity-10 text-warning',
    'approved' => 'bg-success bg-opacity-10 text-success',
    'rejected' => 'bg-danger bg-opacity-10 text-danger',
    'cancelled' => 'bg-dark bg-opacity-10 text-dark'
];

$stepLabels = [
    'waiting' => 'Menunggu Giliran',
    'pending' => 'Menunggu Approval',
    'approved' => 'Disetujui',
    'rejected' => 'Ditolak',
    'skipped' => 'Dilewati'
];

$stepClass = [
    'waiting' => 'bg-secondary bg-opacity-10 text-secondary',
    'pending' => 'bg-warning bg-opacity-10 text-warning',
    'approved' => 'bg-success bg-opacity-10 text-success',
    'rejected' => 'bg-danger bg-opacity-10 text-danger',
    'skipped' => 'bg-dark bg-opacity-10 text-dark'
];

$status = $approvalRequest['status'] ?? '';
?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Detail Approval Request
            </h3>

            <p class="mb-0 text-body">
                <?php if (($approvalRequest['module_name'] ?? '') === 'purchase_requests'): ?>
                    <a
                        href="<?= url('purchase-requests-show') ?>?id=<?= $approvalRequest['reference_id'] ?>"
                        class="text-primary text-decoration-none fw-semibold"
                    >
                        <?= htmlspecialchars($approvalRequest['reference_no'] ?? '-') ?>
                    </a>
                <?php else: ?>
                    <?= htmlspecialchars($approvalRequest['reference_no'] ?? '-') ?>
                <?php endif; ?>
            </p>
        </div>

        <div class="d-flex justify-content-between justify-content-md-end align-items-center flex-wrap gap-3">

            <a
                href="<?= url('approval-requests') ?>"
                class="btn btn-light erp-btn"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Kembali
            </a>

            <?php if (
                ($approvalRequest['status'] ?? '') === 'waiting_approval' &&
                !empty($canApproveThisRequest)
            ): ?>

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

                        <?php if (can('approval_request.approve')): ?>
                            <li>
                                <a
                                    href="#approveModal"
                                    data-bs-toggle="modal"
                                    class="dropdown-item erp-dropdown-item"
                                >
                                    <div class="erp-dropdown-title text-success">
                                        <i class="ri-check-line me-2"></i>
                                        Approve Request
                                    </div>

                                    <div class="erp-dropdown-desc">
                                        Setujui approval request
                                    </div>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (can('approval_request.reject')): ?>
                            <li>
                                <a
                                    href="#rejectModal"
                                    data-bs-toggle="modal"
                                    class="dropdown-item erp-dropdown-item"
                                >
                                    <div class="erp-dropdown-title text-danger">
                                        <i class="ri-close-line me-2"></i>
                                        Reject Request
                                    </div>

                                    <div class="erp-dropdown-desc">
                                        Tolak approval request
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
                    <?= $statusLabels[$status] ?? '-' ?>
                </span>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">Module</div>

            <div class="erp-detail-value">
                <?= htmlspecialchars($approvalRequest['module_name'] ?? '-') ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">Nominal</div>

            <div class="erp-detail-value text-primary">
                Rp <?= number_format((float) ($approvalRequest['amount'] ?? 0), 0, ',', '.') ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">Current Level</div>

            <div class="erp-detail-value">
                <span class="default-badge bg-info bg-opacity-10 text-info">
                    Level <?= (int) ($approvalRequest['current_level'] ?? 1) ?>
                </span>
            </div>
        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title">
            Informasi Approval Request
        </h4>
    </div>

    <div class="p-20">

        <div class="row g-4">

            <div class="col-md-3">
                <div class="erp-detail-label">Module</div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($approvalRequest['module_name'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">Reference ID</div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($approvalRequest['reference_id'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">Reference No</div>

                <div class="erp-detail-value">
                    <?php if (($approvalRequest['module_name'] ?? '') === 'purchase_requests'): ?>
                        <a
                            href="<?= url('purchase-requests-show') ?>?id=<?= $approvalRequest['reference_id'] ?>"
                            class="text-primary text-decoration-none fw-semibold"
                        >
                            <?= htmlspecialchars($approvalRequest['reference_no'] ?? '-') ?>
                        </a>
                    <?php else: ?>
                        <?= htmlspecialchars($approvalRequest['reference_no'] ?? '-') ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">Nominal</div>

                <div class="erp-detail-value text-primary">
                    Rp <?= number_format((float) ($approvalRequest['amount'] ?? 0), 0, ',', '.') ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">Current Level</div>

                <div class="erp-detail-value">
                    Level <?= (int) ($approvalRequest['current_level'] ?? 1) ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">Requested At</div>

                <div class="erp-detail-value">
                    <?= !empty($approvalRequest['requested_at'])
                        ? date('d M Y H:i', strtotime($approvalRequest['requested_at']))
                        : '-' ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">Completed At</div>

                <div class="erp-detail-value">
                    <?= !empty($approvalRequest['completed_at'])
                        ? date('d M Y H:i', strtotime($approvalRequest['completed_at']))
                        : '-' ?>
                </div>
            </div>

        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title">
            Approval Steps
        </h4>
    </div>

    <div class="default-table-area mx-minus-1">

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead>
                    <tr>
                        <th>Level</th>
                        <th>Approver User</th>
                        <th>Approver Role</th>
                        <th>Status</th>
                        <th>Approved By</th>
                        <th>Approved At</th>
                        <th style="min-width:220px;">Catatan</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($steps)): ?>

                        <?php foreach ($steps as $step): ?>

                            <?php
                            $stepStatus = $step['status'] ?? '';
                            ?>

                            <tr>

                                <td>
                                    <span class="default-badge bg-info bg-opacity-10 text-info">
                                        Level <?= (int) ($step['approval_level'] ?? 1) ?>
                                    </span>
                                </td>

                                <td>
                                    <?= htmlspecialchars($step['approver_user_name'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($step['approver_role_name'] ?? '-') ?>
                                </td>

                                <td>
                                    <span class="default-badge <?= $stepClass[$stepStatus] ?? 'bg-secondary bg-opacity-10 text-secondary' ?>">
                                        <?= $stepLabels[$stepStatus] ?? '-' ?>
                                    </span>
                                </td>

                                <td>
                                    <?= htmlspecialchars($step['approved_by_name'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= !empty($step['approved_at'])
                                        ? date('d M Y H:i', strtotime($step['approved_at']))
                                        : '-' ?>
                                </td>

                                <td class="text-wrap" style="min-width:220px; max-width:320px;">
                                    <?= nl2br(htmlspecialchars($step['notes'] ?? '-')) ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="7" class="text-center text-body py-4">
                                Belum ada approval step.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php if (
    ($approvalRequest['status'] ?? '') === 'waiting_approval' &&
    !empty($canApproveThisRequest)
): ?>

    <?php if (can('approval_request.approve')): ?>

        <div class="modal fade" id="approveModal" tabindex="-1" aria-hidden="true">

            <div class="modal-dialog">

                <div class="modal-content">

                    <form action="<?= url('approval-requests-approve') ?>" method="POST">

                        <input type="hidden" name="id" value="<?= $approvalRequest['id'] ?>">

                        <div class="modal-header">
                            <h5 class="modal-title">
                                Approve Request
                            </h5>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close"
                            ></button>
                        </div>

                        <div class="modal-body">

                            <label class="form-label">
                                Catatan Approval
                            </label>

                            <textarea
                                name="notes"
                                rows="4"
                                class="form-control"
                                placeholder="Catatan opsional"
                            ></textarea>

                        </div>

                        <div class="modal-footer">

                            <button
                                type="button"
                                class="btn btn-light erp-btn"
                                data-bs-dismiss="modal"
                            >
                                Batal
                            </button>

                            <button
                                type="submit"
                                class="btn btn-success text-white erp-btn">
                                <i class="ri-check-line me-1"></i>
                                Approve
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    <?php endif; ?>

    <?php if (can('approval_request.reject')): ?>

        <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">

            <div class="modal-dialog">

                <div class="modal-content">

                    <form action="<?= url('approval-requests-reject') ?>" method="POST">

                        <input type="hidden" name="id" value="<?= $approvalRequest['id'] ?>">

                        <div class="modal-header">
                            <h5 class="modal-title">
                                Reject Request
                            </h5>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close"
                            ></button>
                        </div>

                        <div class="modal-body">

                            <label class="form-label">
                                Alasan Reject
                            </label>

                            <textarea
                                name="notes"
                                rows="4"
                                class="form-control"
                                placeholder="Wajib isi alasan reject"
                                required
                            ></textarea>

                        </div>

                        <div class="modal-footer">

                            <button
                                type="button"
                                class="btn btn-light erp-btn"
                                data-bs-dismiss="modal"
                            >
                                Batal
                            </button>

                            <button
                                type="submit"
                                class="btn btn-danger text-white erp-btn">
                                <i class="ri-close-line me-1"></i>
                                Reject
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    <?php endif; ?>

<?php endif; ?>