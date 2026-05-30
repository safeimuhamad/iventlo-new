<?php
$status = $leaveRequest['status'] ?? 'pending';

$statusClass = match ($status) {
    'approved' => 'bg-success bg-opacity-10 text-success',
    'rejected' => 'bg-danger bg-opacity-10 text-danger',
    'pending' => 'bg-warning bg-opacity-10 text-warning',
    default => 'bg-secondary bg-opacity-10 text-secondary'
};

$typeLabels = [
    'annual_leave' => 'Cuti Tahunan',
    'sick' => 'Sakit',
    'permission' => 'Izin',
    'unpaid_leave' => 'Cuti Tanpa Gaji',
    'other' => 'Lainnya'
];

$leaveTypeLabel = $typeLabels[$leaveRequest['leave_type'] ?? ''] ?? '-';
?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Detail Cuti / Izin
            </h3>

            <p class="mb-0 text-body">
                <?= htmlspecialchars($leaveRequest['full_name'] ?? '-') ?>
            </p>
        </div>

        <div class="d-flex justify-content-between justify-content-md-end align-items-center flex-wrap gap-3">

            <a
                href="<?= url('leave-requests') ?>"
                class="btn btn-light erp-btn"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Kembali
            </a>

            <a
                href="<?= url('leave-requests-edit') ?>?id=<?= $leaveRequest['id'] ?>"
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
                >
                    <i class="ri-settings-3-line me-1"></i>
                    Actions
                </button>

                <ul class="dropdown-menu dropdown-menu-end erp-dropdown-menu">

                    <?php if (($leaveRequest['status'] ?? '') !== 'approved'): ?>
                        <li>
                            <a
                                href="<?= url('leave-requests-approve') ?>?id=<?= $leaveRequest['id'] ?>"
                                class="dropdown-item erp-dropdown-item"
                                onclick="return confirm('Setujui pengajuan ini?')"
                            >
                                <div class="erp-dropdown-title text-success">
                                    <i class="ri-check-line me-2"></i>
                                    Approve Pengajuan
                                </div>

                                <div class="erp-dropdown-desc">
                                    Setujui pengajuan cuti / izin
                                </div>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (($leaveRequest['status'] ?? '') !== 'rejected'): ?>
                        <li>
                            <a
                                href="javascript:void(0)"
                                class="dropdown-item erp-dropdown-item"
                                data-bs-toggle="modal"
                                data-bs-target="#rejectModal"
                            >
                                <div class="erp-dropdown-title text-danger">
                                    <i class="ri-close-line me-2"></i>
                                    Reject Pengajuan
                                </div>

                                <div class="erp-dropdown-desc">
                                    Tolak pengajuan cuti / izin
                                </div>
                            </a>
                        </li>
                    <?php endif; ?>

                    <li>
                        <a
                            href="<?= url('leave-requests-delete') ?>?id=<?= $leaveRequest['id'] ?>"
                            class="dropdown-item erp-dropdown-item"
                            onclick="return confirm('Yakin ingin menghapus pengajuan ini?')"
                        >
                            <div class="erp-dropdown-title text-danger">
                                <i class="ri-delete-bin-line me-2"></i>
                                Hapus Pengajuan
                            </div>

                            <div class="erp-dropdown-desc">
                                Hapus data pengajuan cuti / izin
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
                    <?= ucfirst(htmlspecialchars($status)) ?>
                </span>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">
                Jenis Pengajuan
            </div>

            <div class="erp-detail-value">
                <?= htmlspecialchars($leaveTypeLabel) ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">
                Tanggal Mulai
            </div>

            <div class="erp-detail-value">
                <?= !empty($leaveRequest['start_date'])
                    ? date('d M Y', strtotime($leaveRequest['start_date']))
                    : '-' ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">
                Total Hari
            </div>

            <div class="erp-detail-value text-primary">
                <?= (int) ($leaveRequest['total_days'] ?? 0) ?> Hari
            </div>
        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title">
            Informasi Pengajuan
        </h4>
    </div>

    <div class="p-20">

        <div class="row g-4">

            <div class="col-md-4">
                <div class="erp-detail-label">
                    Karyawan
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($leaveRequest['full_name'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">
                    Kode Karyawan
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($leaveRequest['employee_code'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">
                    Jenis Pengajuan
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($leaveTypeLabel) ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">
                    Tanggal Mulai
                </div>

                <div class="erp-detail-value">
                    <?= !empty($leaveRequest['start_date'])
                        ? date('d M Y', strtotime($leaveRequest['start_date']))
                        : '-' ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">
                    Tanggal Selesai
                </div>

                <div class="erp-detail-value">
                    <?= !empty($leaveRequest['end_date'])
                        ? date('d M Y', strtotime($leaveRequest['end_date']))
                        : '-' ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">
                    Total Hari
                </div>

                <div class="erp-detail-value">
                    <?= (int) ($leaveRequest['total_days'] ?? 0) ?> Hari
                </div>
            </div>

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

            <div class="col-md-6">
                <div class="erp-detail-label">
                    Approved By
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($leaveRequest['approved_by_name'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="erp-detail-label">
                    Approved At
                </div>

                <div class="erp-detail-value">
                    <?= !empty($leaveRequest['approved_at'])
                        ? date('d M Y H:i', strtotime($leaveRequest['approved_at']))
                        : '-' ?>
                </div>
            </div>

            <div class="col-md-12">
                <div class="erp-detail-label">
                    Alasan Pengajuan
                </div>

                <div class="erp-detail-value">
                    <?= nl2br(htmlspecialchars($leaveRequest['reason'] ?? '-')) ?>
                </div>
            </div>

            <?php if (!empty($leaveRequest['rejected_reason'])): ?>
                <div class="col-md-12">

                    <div class="erp-detail-label text-danger">
                        Alasan Ditolak
                    </div>

                    <div class="erp-detail-value text-danger">
                        <?= nl2br(htmlspecialchars($leaveRequest['rejected_reason'])) ?>
                    </div>

                </div>
            <?php endif; ?>

        </div>

    </div>

</div>

<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog">

        <form
            action="<?= url('leave-requests-reject') ?>"
            method="POST"
            class="modal-content"
        >

            <input
                type="hidden"
                name="id"
                value="<?= $leaveRequest['id'] ?>"
            >

            <div class="modal-header">
                <h5 class="modal-title">
                    Reject Pengajuan
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>
            </div>

            <div class="modal-body">

                <label class="form-label">
                    Alasan Penolakan
                </label>

                <textarea
                    name="rejected_reason"
                    rows="4"
                    class="form-control"
                    required
                ></textarea>

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-light"
                    data-bs-dismiss="modal"
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="btn btn-danger text-white"
                >
                    Reject
                </button>

            </div>

        </form>

    </div>

</div>