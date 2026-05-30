<?php
$status = $overtimeRequest['status'] ?? 'pending';

$statusClass = match ($status) {
    'approved' => 'bg-success bg-opacity-10 text-success',
    'rejected' => 'bg-danger bg-opacity-10 text-danger',
    'pending' => 'bg-warning bg-opacity-10 text-warning',
    default => 'bg-secondary bg-opacity-10 text-secondary'
};

$minutes = (int) ($overtimeRequest['total_minutes'] ?? 0);
$hours = floor($minutes / 60);
$remainingMinutes = $minutes % 60;
?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Detail Lembur
            </h3>

            <p class="mb-0 text-body">
                <?= htmlspecialchars($overtimeRequest['full_name'] ?? '-') ?>
            </p>
        </div>

        <div class="d-flex justify-content-between justify-content-md-end align-items-center flex-wrap gap-3">

            <a
                href="<?= url('overtime-requests') ?>"
                class="btn btn-light erp-btn"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Kembali
            </a>

            <a
                href="<?= url('overtime-requests-edit') ?>?id=<?= $overtimeRequest['id'] ?>"
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

                    <?php if (($overtimeRequest['status'] ?? '') !== 'approved'): ?>
                        <li>
                            <a
                                href="<?= url('overtime-requests-approve') ?>?id=<?= $overtimeRequest['id'] ?>"
                                class="dropdown-item erp-dropdown-item"
                                onclick="return confirm('Setujui pengajuan lembur ini?')"
                            >
                                <div class="erp-dropdown-title text-success">
                                    <i class="ri-check-line me-2"></i>
                                    Approve Lembur
                                </div>

                                <div class="erp-dropdown-desc">
                                    Setujui pengajuan lembur
                                </div>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (($overtimeRequest['status'] ?? '') !== 'rejected'): ?>
                        <li>
                            <a
                                href="javascript:void(0)"
                                class="dropdown-item erp-dropdown-item"
                                data-bs-toggle="modal"
                                data-bs-target="#rejectModal"
                            >
                                <div class="erp-dropdown-title text-danger">
                                    <i class="ri-close-line me-2"></i>
                                    Reject Lembur
                                </div>

                                <div class="erp-dropdown-desc">
                                    Tolak pengajuan lembur
                                </div>
                            </a>
                        </li>
                    <?php endif; ?>

                    <li>
                        <a
                            href="<?= url('overtime-requests-delete') ?>?id=<?= $overtimeRequest['id'] ?>"
                            class="dropdown-item erp-dropdown-item"
                            onclick="return confirm('Yakin ingin menghapus pengajuan lembur ini?')"
                        >
                            <div class="erp-dropdown-title text-danger">
                                <i class="ri-delete-bin-line me-2"></i>
                                Hapus Pengajuan
                            </div>

                            <div class="erp-dropdown-desc">
                                Hapus data pengajuan lembur
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
                Tanggal Lembur
            </div>

            <div class="erp-detail-value">
                <?= !empty($overtimeRequest['overtime_date'])
                    ? date('d M Y', strtotime($overtimeRequest['overtime_date']))
                    : '-' ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">
                Durasi
            </div>

            <div class="erp-detail-value text-primary">
                <?= $hours ?> Jam <?= $remainingMinutes ?> Menit
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">
                Karyawan
            </div>

            <div class="erp-detail-value">
                <?= htmlspecialchars($overtimeRequest['employee_code'] ?? '-') ?>
            </div>
        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title">
            Informasi Lembur
        </h4>
    </div>

    <div class="p-20">

        <div class="row g-4">

            <div class="col-md-4">
                <div class="erp-detail-label">
                    Karyawan
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($overtimeRequest['full_name'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">
                    Kode Karyawan
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($overtimeRequest['employee_code'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-4">
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
                    Tanggal Lembur
                </div>

                <div class="erp-detail-value">
                    <?= !empty($overtimeRequest['overtime_date'])
                        ? date('d M Y', strtotime($overtimeRequest['overtime_date']))
                        : '-' ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">
                    Jam Mulai
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($overtimeRequest['start_time'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">
                    Jam Selesai
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($overtimeRequest['end_time'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">
                    Durasi
                </div>

                <div class="erp-detail-value">
                    <?= $hours ?> Jam <?= $remainingMinutes ?> Menit
                </div>
            </div>

            <div class="col-md-6">
                <div class="erp-detail-label">
                    Approved By
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($overtimeRequest['approved_by_name'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="erp-detail-label">
                    Approved At
                </div>

                <div class="erp-detail-value">
                    <?= !empty($overtimeRequest['approved_at'])
                        ? date('d M Y H:i', strtotime($overtimeRequest['approved_at']))
                        : '-' ?>
                </div>
            </div>

            <div class="col-md-12">
                <div class="erp-detail-label">
                    Alasan Lembur
                </div>

                <div class="erp-detail-value">
                    <?= nl2br(htmlspecialchars($overtimeRequest['reason'] ?? '-')) ?>
                </div>
            </div>

            <?php if (!empty($overtimeRequest['rejected_reason'])): ?>
                <div class="col-md-12">

                    <div class="erp-detail-label text-danger">
                        Alasan Ditolak
                    </div>

                    <div class="erp-detail-value text-danger">
                        <?= nl2br(htmlspecialchars($overtimeRequest['rejected_reason'])) ?>
                    </div>

                </div>
            <?php endif; ?>

        </div>

    </div>

</div>

<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog">

        <form
            action="<?= url('overtime-requests-reject') ?>"
            method="POST"
            class="modal-content"
        >

            <input
                type="hidden"
                name="id"
                value="<?= $overtimeRequest['id'] ?>"
            >

            <div class="modal-header">
                <h5 class="modal-title">
                    Reject Pengajuan Lembur
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