<?php
$status = $item['status'] ?? 'active';

$statusClass = [
    'active' => 'bg-success bg-opacity-10 text-success',
    'expired' => 'bg-danger bg-opacity-10 text-danger',
    'terminated' => 'bg-dark bg-opacity-10 text-dark',
    'renewed' => 'bg-info bg-opacity-10 text-info',
];

$typeLabel = [
    'probation' => 'Probation',
    'contract' => 'Kontrak',
    'permanent' => 'Permanent',
    'freelance' => 'Freelance',
    'internship' => 'Internship',
];
?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Detail Kontrak Karyawan
            </h3>

            <p class="mb-0 text-body">
                <?= htmlspecialchars($item['contract_number'] ?? '-') ?>
            </p>
        </div>

        <div class="d-flex justify-content-between justify-content-md-end align-items-center flex-wrap gap-3">

            <a
                href="<?= url('employee-contracts') ?>"
                class="btn btn-light erp-btn"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Kembali
            </a>

            <?php if (can('employee_contract.edit')): ?>
                <a
                    href="<?= url('employee-contracts-edit') ?>?id=<?= $item['id'] ?>"
                    class="btn btn-outline-primary erp-btn"
                >
                    <i class="ri-edit-line me-1"></i>
                    Edit
                </a>
            <?php endif; ?>

            <?php if (can('employee_contract.print') || can('employee_contract.delete')): ?>

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

                        <?php if (can('employee_contract.print')): ?>
                            <li>

                                <a
                                    href="<?= url('employee-contracts-print') ?>?id=<?= $item['id'] ?>"
                                    target="_blank"
                                    class="dropdown-item erp-dropdown-item"
                                >
                                    <div class="erp-dropdown-title text-primary">
                                        <i class="ri-printer-line me-2"></i>
                                        Print Kontrak
                                    </div>

                                    <div class="erp-dropdown-desc">
                                        Cetak kontrak karyawan
                                    </div>
                                </a>

                            </li>
                        <?php endif; ?>

                        <?php if (can('employee_contract.delete')): ?>
                            <li>

                                <a
                                    href="<?= url('employee-contracts-delete') ?>?id=<?= $item['id'] ?>"
                                    class="dropdown-item erp-dropdown-item"
                                    onclick="return confirm('Hapus kontrak ini?')"
                                >
                                    <div class="erp-dropdown-title text-danger">
                                        <i class="ri-delete-bin-line me-2"></i>
                                        Hapus Kontrak
                                    </div>

                                    <div class="erp-dropdown-desc">
                                        Hapus data kontrak karyawan
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

            <div class="erp-detail-label">
                Status
            </div>

            <div class="erp-detail-value">
                <span class="default-badge <?= $statusClass[$status] ?? 'bg-secondary bg-opacity-10 text-secondary' ?>">
                    <?= ucfirst($status) ?>
                </span>
            </div>

        </div>

        <div class="col-md-3">

            <div class="erp-detail-label">
                Tipe Kontrak
            </div>

            <div class="erp-detail-value">
                <?= htmlspecialchars($typeLabel[$item['contract_type'] ?? ''] ?? '-') ?>
            </div>

        </div>

        <div class="col-md-3">

            <div class="erp-detail-label">
                Karyawan
            </div>

            <div class="erp-detail-value">
                <?= htmlspecialchars($item['employee_code'] ?? '-') ?>
            </div>

        </div>

        <div class="col-md-3">

            <div class="erp-detail-label">
                Gaji
            </div>

            <div class="erp-detail-value text-primary">
                Rp <?= number_format((float) ($item['salary'] ?? 0), 0, ',', '.') ?>
            </div>

        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">

        <h4 class="erp-detail-section-title">
            Informasi Karyawan
        </h4>

    </div>

    <div class="p-20">

        <div class="row g-4">

            <div class="col-md-6">

                <div class="erp-detail-label">
                    Nama Karyawan
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($item['employee_name'] ?? '-') ?>
                </div>

            </div>

            <div class="col-md-6">

                <div class="erp-detail-label">
                    NIK / Kode Karyawan
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($item['employee_code'] ?? '-') ?>
                </div>

            </div>

            <div class="col-md-6">

                <div class="erp-detail-label">
                    No. HP
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($item['employee_phone'] ?? '-') ?>
                </div>

            </div>

            <div class="col-md-6">

                <div class="erp-detail-label">
                    Email
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($item['employee_email'] ?? '-') ?>
                </div>

            </div>

        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">

        <h4 class="erp-detail-section-title">
            Informasi Kontrak
        </h4>

    </div>

    <div class="p-20">

        <div class="row g-4">

            <div class="col-md-4">

                <div class="erp-detail-label">
                    Nomor Kontrak
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($item['contract_number'] ?? '-') ?>
                </div>

            </div>

            <div class="col-md-4">

                <div class="erp-detail-label">
                    Tipe Kontrak
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($typeLabel[$item['contract_type'] ?? ''] ?? '-') ?>
                </div>

            </div>

            <div class="col-md-4">

                <div class="erp-detail-label">
                    Status
                </div>

                <div class="erp-detail-value">
                    <span class="default-badge <?= $statusClass[$status] ?? 'bg-secondary bg-opacity-10 text-secondary' ?>">
                        <?= ucfirst($status) ?>
                    </span>
                </div>

            </div>

            <div class="col-md-4">

                <div class="erp-detail-label">
                    Tanggal Mulai
                </div>

                <div class="erp-detail-value">
                    <?= !empty($item['start_date'])
                        ? date('d M Y', strtotime($item['start_date']))
                        : '-' ?>
                </div>

            </div>

            <div class="col-md-4">

                <div class="erp-detail-label">
                    Tanggal Selesai
                </div>

                <div class="erp-detail-value">
                    <?= !empty($item['end_date'])
                        ? date('d M Y', strtotime($item['end_date']))
                        : '-' ?>
                </div>

            </div>

            <div class="col-md-4">

                <div class="erp-detail-label">
                    Salary / Gaji
                </div>

                <div class="erp-detail-value text-primary">
                    Rp <?= number_format((float) ($item['salary'] ?? 0), 0, ',', '.') ?>
                </div>

            </div>

            <div class="col-md-6">

                <div class="erp-detail-label">
                    Jabatan
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($item['job_title'] ?? '-') ?>
                </div>

            </div>

            <div class="col-md-6">

                <div class="erp-detail-label">
                    Lokasi Kerja
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($item['work_location'] ?? '-') ?>
                </div>

            </div>

            <div class="col-md-6">

                <div class="erp-detail-label">
                    Dibuat Oleh
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($item['created_by_name'] ?? '-') ?>
                </div>

            </div>

            <?php if (!empty($item['contract_pdf_url'])): ?>

                <div class="col-md-6">

                    <div class="erp-detail-label">
                        Dokumen Kontrak
                    </div>

                    <div class="erp-detail-value">

                        <a
                            href="<?= htmlspecialchars($item['contract_pdf_url']) ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="btn btn-outline-danger erp-btn"
                        >
                            <i class="ri-file-pdf-line me-1"></i>
                            Open PDF Contract
                        </a>

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