<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Detail Karyawan
            </h3>

            <p class="mb-0 text-body">
                <?= htmlspecialchars($employee['employee_code'] ?? '-') ?>
                -
                <?= htmlspecialchars($employee['full_name'] ?? '-') ?>
            </p>
        </div>

        <div class="d-flex justify-content-between justify-content-md-end align-items-center flex-wrap gap-3">

            <a
                href="<?= url('employees') ?>"
                class="btn btn-light erp-btn"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Kembali
            </a>

            <a
                href="<?= url('employees-edit') ?>?id=<?= $employee['id'] ?>"
                class="btn btn-outline-primary erp-btn"
            >
                <i class="ri-edit-line me-1"></i>
                Edit
            </a>

            <?php if (
                empty($employeeUser)
                && !empty($employee['email'])
                && can('user.create')
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

                        <li>
                            <a
                                href="<?= url('employees-create-user') ?>?id=<?= $employee['id'] ?>"
                                class="dropdown-item erp-dropdown-item"
                                onclick="return confirm('Buat akun ERP untuk karyawan ini?')"
                            >
                                <div class="erp-dropdown-title text-primary">
                                    <i class="ri-user-add-line me-2"></i>
                                    Buat Akun ERP
                                </div>

                                <div class="erp-dropdown-desc">
                                    Buat akses login ERP untuk karyawan
                                </div>
                            </a>
                        </li>

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
                Kode Karyawan
            </div>

            <div class="erp-detail-value">
                <?= htmlspecialchars($employee['employee_code'] ?? '-') ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">
                Nama Lengkap
            </div>

            <div class="erp-detail-value">
                <?= htmlspecialchars($employee['full_name'] ?? '-') ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">
                Divisi
            </div>

            <div class="erp-detail-value">
                <?= htmlspecialchars($employee['department_name'] ?? '-') ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">
                Jabatan
            </div>

            <div class="erp-detail-value">
                <?= htmlspecialchars($employee['position_name'] ?? '-') ?>
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

            <div class="col-md-4">
                <div class="erp-detail-label">
                    Kode Karyawan
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($employee['employee_code'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">
                    Nama Lengkap
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($employee['full_name'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">
                    Nama Panggilan
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($employee['nickname'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">
                    No. HP
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($employee['phone'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">
                    Email
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($employee['email'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">
                    Tanggal Masuk
                </div>

                <div class="erp-detail-value">
                    <?= !empty($employee['join_date'])
                        ? date('d M Y', strtotime($employee['join_date']))
                        : '-' ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">
                    Divisi
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($employee['department_name'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">
                    Jabatan
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($employee['position_name'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">
                    Gaji Pokok
                </div>

                <div class="erp-detail-value text-primary">
                    Rp <?= number_format((float) ($employee['basic_salary'] ?? 0), 0, ',', '.') ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">
                    Bank
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($employee['bank_name'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">
                    No. Rekening
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($employee['bank_account_number'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">
                    Nama Pemilik Rekening
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($employee['bank_account_name'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-12">
                <div class="erp-detail-label">
                    Alamat
                </div>

                <div class="erp-detail-value">
                    <?= nl2br(htmlspecialchars($employee['address'] ?? '-')) ?>
                </div>
            </div>

        </div>

    </div>

</div>