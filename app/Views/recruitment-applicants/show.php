<?php
$status = $item['status'] ?? 'new';

$statusLabel = [
    'new' => 'New',
    'screening' => 'Screening',
    'interview' => 'Interview',
    'test' => 'Test',
    'offering' => 'Offering',
    'hired' => 'Hired',
    'rejected' => 'Rejected',
];

$statusClass = [
    'new' => 'bg-primary bg-opacity-10 text-primary',
    'screening' => 'bg-info bg-opacity-10 text-info',
    'interview' => 'bg-warning bg-opacity-10 text-warning',
    'test' => 'bg-secondary bg-opacity-10 text-secondary',
    'offering' => 'bg-purple bg-opacity-10 text-purple',
    'hired' => 'bg-success bg-opacity-10 text-success',
    'rejected' => 'bg-danger bg-opacity-10 text-danger',
];
?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Detail Kandidat
            </h3>

            <p class="mb-0 text-body">
                <?= htmlspecialchars($item['applicant_number'] ?? '-') ?>
            </p>
        </div>

        <div class="d-flex justify-content-between justify-content-md-end align-items-center flex-wrap gap-3">

            <a
                href="<?= url('recruitment-applicants') ?>"
                class="btn btn-light erp-btn"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Kembali
            </a>

            <?php if (can('recruitment_applicant.edit')): ?>
                <a
                    href="<?= url('recruitment-applicants-edit') ?>?id=<?= $item['id'] ?>"
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
                >
                    <i class="ri-settings-3-line me-1"></i>
                    Actions
                </button>

                <ul class="dropdown-menu dropdown-menu-end erp-dropdown-menu">

                    <?php if (
                        can('recruitment_applicant.convert')
                        && empty($item['converted_employee_id'])
                        && ($item['status'] ?? '') === 'hired'
                    ): ?>
                        <li>

                            <a
                                href="<?= url('recruitment-applicants-convert') ?>?id=<?= $item['id'] ?>"
                                class="dropdown-item erp-dropdown-item"
                                onclick="return confirm('Convert kandidat menjadi karyawan?')"
                            >
                                <div class="erp-dropdown-title text-success">
                                    <i class="ri-user-add-line me-2"></i>
                                    Convert To Employee
                                </div>

                                <div class="erp-dropdown-desc">
                                    Buat data karyawan dari kandidat
                                </div>
                            </a>

                        </li>
                    <?php endif; ?>

                    <?php if (can('recruitment_applicant.delete') && empty($item['converted_employee_id'])): ?>
                        <li>

                            <a
                                href="<?= url('recruitment-applicants-delete') ?>?id=<?= $item['id'] ?>"
                                class="dropdown-item erp-dropdown-item"
                                onclick="return confirm('Hapus kandidat ini?')"
                            >
                                <div class="erp-dropdown-title text-danger">
                                    <i class="ri-delete-bin-line me-2"></i>
                                    Hapus Kandidat
                                </div>

                                <div class="erp-dropdown-desc">
                                    Hapus data kandidat rekrutmen
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
                <span class="default-badge <?= $statusClass[$status] ?? 'bg-secondary bg-opacity-10 text-secondary' ?>">
                    <?= $statusLabel[$status] ?? $status ?>
                </span>
            </div>

        </div>

        <div class="col-md-3">

            <div class="erp-detail-label">
                Kandidat
            </div>

            <div class="erp-detail-value">
                <?= htmlspecialchars($item['full_name'] ?? '-') ?>
            </div>

        </div>

        <div class="col-md-3">

            <div class="erp-detail-label">
                Posisi
            </div>

            <div class="erp-detail-value">
                <?= htmlspecialchars($item['position_name'] ?? '-') ?>
            </div>

        </div>

        <div class="col-md-3">

            <div class="erp-detail-label">
                Expected Salary
            </div>

            <div class="erp-detail-value text-primary">
                Rp <?= number_format((float) ($item['expected_salary'] ?? 0), 0, ',', '.') ?>
            </div>

        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">

        <h4 class="erp-detail-section-title">
            Informasi Kandidat
        </h4>

    </div>

    <div class="p-20">

        <div class="row g-4">

            <div class="col-md-4">
                <div class="erp-detail-label">No Kandidat</div>
                <div class="erp-detail-value">
                    <?= htmlspecialchars($item['applicant_number'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">Nama Kandidat</div>
                <div class="erp-detail-value">
                    <?= htmlspecialchars($item['full_name'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">Status</div>
                <div class="erp-detail-value">
                    <span class="default-badge <?= $statusClass[$status] ?? 'bg-secondary bg-opacity-10 text-secondary' ?>">
                        <?= $statusLabel[$status] ?? $status ?>
                    </span>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">No. HP</div>
                <div class="erp-detail-value">
                    <?= htmlspecialchars($item['phone'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">Email</div>
                <div class="erp-detail-value">
                    <?= htmlspecialchars($item['email'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">Source</div>
                <div class="erp-detail-value">
                    <?= htmlspecialchars($item['source'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">Department</div>
                <div class="erp-detail-value">
                    <?= htmlspecialchars($item['department_name'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">Position</div>
                <div class="erp-detail-value">
                    <?= htmlspecialchars($item['position_name'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">Expected Salary</div>
                <div class="erp-detail-value text-primary">
                    Rp <?= number_format((float) ($item['expected_salary'] ?? 0), 0, ',', '.') ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">Jadwal Interview</div>
                <div class="erp-detail-value">
                    <?= !empty($item['interview_date'])
                        ? date('d M Y H:i', strtotime($item['interview_date']))
                        : '-' ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">Dibuat Oleh</div>
                <div class="erp-detail-value">
                    <?= htmlspecialchars($item['created_by_name'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">Tanggal Dibuat</div>
                <div class="erp-detail-value">
                    <?= !empty($item['created_at'])
                        ? date('d M Y H:i', strtotime($item['created_at']))
                        : '-' ?>
                </div>
            </div>

            <?php if (!empty($item['converted_employee_id'])): ?>
                <div class="col-md-6">

                    <div class="erp-detail-label">
                        Converted Employee
                    </div>

                    <div class="erp-detail-value text-success">
                        <?= htmlspecialchars($item['converted_employee_name'] ?? '-') ?>
                    </div>

                </div>
            <?php endif; ?>

            <div class="col-md-12">

                <div class="erp-detail-label">
                    Google Drive Folder
                </div>

                <div class="erp-detail-value">

                    <?php if (!empty($item['google_drive_url'])): ?>

                        <a
                            href="<?= htmlspecialchars($item['google_drive_url']) ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="btn btn-outline-primary erp-btn"
                        >
                            <i class="ri-folder-open-line me-1"></i>
                            Open Google Drive Folder
                        </a>

                    <?php else: ?>

                        -

                    <?php endif; ?>

                </div>

            </div>

            <div class="col-md-12">

                <div class="erp-detail-label">
                    Alamat
                </div>

                <div class="erp-detail-value">
                    <?= nl2br(htmlspecialchars($item['address'] ?? '-')) ?>
                </div>

            </div>

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