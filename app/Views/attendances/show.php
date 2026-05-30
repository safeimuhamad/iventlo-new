<?php
$status = $attendance['status'] ?? '';

$statusLabels = [
    'present' => 'Hadir',
    'late' => 'Terlambat',
    'permission' => 'Izin',
    'sick' => 'Sakit',
    'leave' => 'Cuti',
    'absent' => 'Alpa'
];

$statusClass = match ($status) {
    'present' => 'bg-success bg-opacity-10 text-success',
    'late' => 'bg-warning bg-opacity-10 text-warning',
    'permission' => 'bg-info bg-opacity-10 text-info',
    'sick' => 'bg-primary bg-opacity-10 text-primary',
    'leave' => 'bg-secondary bg-opacity-10 text-secondary',
    'absent' => 'bg-danger bg-opacity-10 text-danger',
    default => 'bg-secondary bg-opacity-10 text-secondary'
};

$statusLabel = $statusLabels[$status] ?? '-';
?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Detail Absensi
            </h3>

            <p class="mb-0 text-body">
                <?= htmlspecialchars($attendance['full_name'] ?? '-') ?>
            </p>
        </div>

        <div class="d-flex justify-content-between justify-content-md-end align-items-center flex-wrap gap-3">

            <a
                href="<?= url('attendances') ?>"
                class="btn btn-light erp-btn"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Kembali
            </a>

            <a
                href="<?= url('attendances-edit') ?>?id=<?= $attendance['id'] ?>"
                class="btn btn-outline-primary erp-btn"
            >
                <i class="ri-edit-line me-1"></i>
                Edit
            </a>

            <a
                href="<?= url('attendances-delete') ?>?id=<?= $attendance['id'] ?>"
                class="btn btn-outline-danger erp-btn"
                onclick="return confirm('Yakin ingin menghapus data absensi ini?')"
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
                Tanggal Absensi
            </div>

            <div class="erp-detail-value">
                <?= !empty($attendance['attendance_date'])
                    ? date('d M Y', strtotime($attendance['attendance_date']))
                    : '-' ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">
                Check In
            </div>

            <div class="erp-detail-value">
                <?= htmlspecialchars($attendance['check_in'] ?? '-') ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">
                Check Out
            </div>

            <div class="erp-detail-value">
                <?= htmlspecialchars($attendance['check_out'] ?? '-') ?>
            </div>
        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title">
            Informasi Absensi
        </h4>
    </div>

    <div class="p-20">

        <div class="row g-4">

            <div class="col-md-4">
                <div class="erp-detail-label">
                    Tanggal Absensi
                </div>

                <div class="erp-detail-value">
                    <?= !empty($attendance['attendance_date'])
                        ? date('d M Y', strtotime($attendance['attendance_date']))
                        : '-' ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">
                    Karyawan
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($attendance['full_name'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">
                    Kode Karyawan
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($attendance['employee_code'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">
                    Status
                </div>

                <div class="erp-detail-value">
                    <span class="default-badge <?= $statusClass ?>">
                        <?= htmlspecialchars($statusLabel) ?>
                    </span>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">
                    Check In
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($attendance['check_in'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">
                    Check Out
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($attendance['check_out'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">
                    Terlambat
                </div>

                <div class="erp-detail-value text-warning">
                    <?= (int) ($attendance['late_minutes'] ?? 0) ?> menit
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">
                    Lembur
                </div>

                <div class="erp-detail-value text-primary">
                    <?= (int) ($attendance['overtime_minutes'] ?? 0) ?> menit
                </div>
            </div>

            <div class="col-md-12">
                <div class="erp-detail-label">
                    Catatan
                </div>

                <div class="erp-detail-value">
                    <?= nl2br(htmlspecialchars($attendance['notes'] ?? '-')) ?>
                </div>
            </div>

        </div>

    </div>

</div>