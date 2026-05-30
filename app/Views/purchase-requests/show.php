<?php
$status = strtolower($item['status'] ?? 'draft');

$statusClass = match ($status) {
    'draft' => 'bg-secondary bg-opacity-10 text-secondary',
    'waiting_approval' => 'bg-warning bg-opacity-10 text-warning',
    'approved' => 'bg-success bg-opacity-10 text-success',
    'rejected' => 'bg-danger bg-opacity-10 text-danger',
    'closed' => 'bg-dark bg-opacity-10 text-dark',
    default => 'bg-secondary bg-opacity-10 text-secondary'
};

$grandTotal = 0;

if (!empty($items)) {
    foreach ($items as $row) {
        $grandTotal += (float) ($row['subtotal'] ?? 0);
    }
}

$approvalModel = new ApprovalRequest();
$approvalRequest = $approvalModel->findByReference('purchase_requests', $item['id']);
?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Detail Purchase Request
            </h3>

            <p class="text-body mb-0">
                <?= htmlspecialchars($item['pr_number'] ?? '-') ?>
            </p>
        </div>

        <div class="d-flex justify-content-between justify-content-md-end align-items-center flex-wrap gap-3">

            <a
                href="<?= url('purchase-requests') ?>"
                class="btn btn-light erp-btn"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Kembali
            </a>

            <?php if (
                can('purchase_request.edit') &&
                in_array(($item['status'] ?? ''), ['draft', 'rejected'])
            ): ?>
                <a
                    href="<?= url('purchase-requests-edit') ?>?id=<?= $item['id'] ?>"
                    class="btn btn-outline-primary erp-btn"
                >
                    <i class="ri-edit-line me-1"></i>
                    Edit
                </a>
            <?php endif; ?>

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

                    <?php if (
                        can('purchase_request.approve') &&
                        in_array(($item['status'] ?? 'draft'), ['draft', 'rejected'])
                    ): ?>
                        <li>
                            <a
                                href="<?= url('purchase-requests-submit-approval') ?>?id=<?= $item['id'] ?>"
                                class="dropdown-item erp-dropdown-item"
                            >
                                <div class="erp-dropdown-title text-primary">
                                    <i class="ri-send-plane-line me-2"></i>
                                    Submit Approval
                                </div>

                                <div class="erp-dropdown-desc">
                                    Kirim PR ke workflow approval
                                </div>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if ($approvalRequest): ?>
                        <li>
                            <a
                                href="<?= url('approval-requests-show') ?>?id=<?= $approvalRequest['id'] ?>"
                                class="dropdown-item erp-dropdown-item"
                            >
                                <div class="erp-dropdown-title text-info">
                                    <i class="ri-git-branch-line me-2"></i>
                                    Lihat Approval
                                </div>

                                <div class="erp-dropdown-desc">
                                    Lihat progress approval request
                                </div>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (
                        can('purchase_order.create') &&
                        ($item['status'] ?? '') === 'approved'
                    ): ?>
                        <li>
                            <a
                                href="<?= url('purchase-orders-create-from-pr') ?>?id=<?= $item['id'] ?>"
                                class="dropdown-item erp-dropdown-item"
                            >
                                <div class="erp-dropdown-title text-success">
                                    <i class="ri-file-list-3-line me-2"></i>
                                    Buat Purchase Order
                                </div>

                                <div class="erp-dropdown-desc">
                                    Generate PO dari Purchase Request
                                </div>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (
                        can('purchase_request.delete') &&
                        in_array(($item['status'] ?? ''), ['draft', 'rejected'])
                    ): ?>
                        <li>
                            <a
                                href="<?= url('purchase-requests-delete') ?>?id=<?= $item['id'] ?>"
                                class="dropdown-item erp-dropdown-item"
                                onclick="return confirm('Hapus purchase request ini?')"
                            >
                                <div class="erp-dropdown-title text-danger">
                                    <i class="ri-delete-bin-line me-2"></i>
                                    Hapus Purchase Request
                                </div>

                                <div class="erp-dropdown-desc">
                                    Hapus dokumen purchase request
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
                    <?= htmlspecialchars(ucwords(str_replace('_', ' ', $status))) ?>
                </span>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">
                Request By
            </div>

            <div class="erp-detail-value">
                <?= htmlspecialchars($item['requested_by_name'] ?? '-') ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">
                Divisi
            </div>

            <div class="erp-detail-value">
                <?= htmlspecialchars($item['department_name'] ?? '-') ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">
                Total Estimasi
            </div>

            <div class="erp-detail-value text-primary">
                Rp <?= number_format($grandTotal, 0, ',', '.') ?>
            </div>
        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title">
            Informasi Purchase Request
        </h4>
    </div>

    <div class="p-20">

        <div class="row g-4">

            <div class="col-md-3">
                <div class="erp-detail-label">
                    No. PR
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($item['pr_number'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">
                    Tanggal Request
                </div>

                <div class="erp-detail-value">
                    <?= !empty($item['request_date'])
                        ? date('d M Y', strtotime($item['request_date']))
                        : '-' ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">
                    Tanggal Dibutuhkan
                </div>

                <div class="erp-detail-value">
                    <?= !empty($item['needed_date'])
                        ? date('d M Y', strtotime($item['needed_date']))
                        : '-' ?>
                </div>
            </div>

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

            <div class="col-md-4">
                <div class="erp-detail-label">
                    Request By
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($item['requested_by_name'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">
                    Divisi
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($item['department_name'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">
                    Kebutuhan
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($item['purpose'] ?? '-') ?>
                </div>
            </div>

            <?php if (!empty($item['approved_by'])): ?>

                <div class="col-md-4">
                    <div class="erp-detail-label">
                        Approved By
                    </div>

                    <div class="erp-detail-value">
                        <?= htmlspecialchars($item['approved_by_name'] ?? '-') ?>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="erp-detail-label">
                        Approved At
                    </div>

                    <div class="erp-detail-value">
                        <?= !empty($item['approved_at'])
                            ? date('d M Y H:i', strtotime($item['approved_at']))
                            : '-' ?>
                    </div>
                </div>

            <?php endif; ?>

            <?php if (($item['status'] ?? '') === 'rejected'): ?>

                <div class="col-md-4">
                    <div class="erp-detail-label">
                        Rejected By
                    </div>

                    <div class="erp-detail-value">
                        <?= htmlspecialchars($item['rejected_by_name'] ?? '-') ?>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="erp-detail-label">
                        Rejected At
                    </div>

                    <div class="erp-detail-value">
                        <?= !empty($item['rejected_at'])
                            ? date('d M Y H:i', strtotime($item['rejected_at']))
                            : '-' ?>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="erp-detail-label">
                        Alasan Reject
                    </div>

                    <div class="erp-detail-value text-danger">
                        <?= nl2br(htmlspecialchars($item['rejected_reason'] ?? '-')) ?>
                    </div>
                </div>

            <?php endif; ?>

            <div class="col-md-12">
                <div class="erp-detail-label">
                    Catatan
                </div>

                <div class="erp-detail-value">
                    <?= nl2br(htmlspecialchars($item['notes'] ?? '-')) ?>
                </div>
            </div>

        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title">
            Item Request
        </h4>
    </div>

    <div class="default-table-area mx-minus-1">

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead>
                    <tr>
                        <th>Item</th>
                        <th style="min-width:220px;">Deskripsi</th>
                        <th class="text-end">Qty</th>
                        <th>Unit</th>
                        <th class="text-end">Estimasi Harga</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($items)): ?>

                        <?php foreach ($items as $row): ?>

                            <tr>
                                <td class="fw-semibold">
                                    <?= htmlspecialchars($row['item_name'] ?? '-') ?>
                                </td>

                                <td class="text-wrap" style="min-width:220px; max-width:420px;">
                                    <?= htmlspecialchars($row['description'] ?? '-') ?>
                                </td>

                                <td class="text-end">
                                    <?= number_format((float) ($row['qty'] ?? 0), 2, ',', '.') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['unit_name'] ?? '-') ?>
                                </td>

                                <td class="text-end">
                                    Rp <?= number_format((float) ($row['estimated_price'] ?? 0), 0, ',', '.') ?>
                                </td>

                                <td class="text-end fw-semibold">
                                    Rp <?= number_format((float) ($row['subtotal'] ?? 0), 0, ',', '.') ?>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                        <tr>
                            <td colspan="5" class="text-end fw-bold border-top">
                                Total Estimasi
                            </td>

                            <td class="text-end fw-bold text-primary border-top">
                                Rp <?= number_format($grandTotal, 0, ',', '.') ?>
                            </td>
                        </tr>

                    <?php else: ?>

                        <tr>
                            <td colspan="6" class="text-center text-body py-4">
                                Belum ada item request.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>