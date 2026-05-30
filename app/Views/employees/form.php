<?php
$employee = $employee ?? [];
?>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title mb-0">
            Informasi Personal
        </h4>
    </div>

    <div class="p-20">

        <div class="row g-4">

            <div class="col-md-4">
                <label class="erp-detail-label">
                    Kode Karyawan <span class="text-danger">*</span>
                </label>
                <input
                    type="text"
                    name="employee_code"
                    class="form-control"
                    value="<?= htmlspecialchars($employee['employee_code'] ?? '') ?>"
                    required
                >
            </div>

            <div class="col-md-4">
                <label class="erp-detail-label">
                    Nama Lengkap <span class="text-danger">*</span>
                </label>
                <input
                    type="text"
                    name="full_name"
                    class="form-control"
                    value="<?= htmlspecialchars($employee['full_name'] ?? '') ?>"
                    required
                >
            </div>

            <div class="col-md-4">
                <label class="erp-detail-label">Nama Panggilan</label>
                <input
                    type="text"
                    name="nickname"
                    class="form-control"
                    value="<?= htmlspecialchars($employee['nickname'] ?? '') ?>"
                >
            </div>

            <div class="col-md-4">
                <label class="erp-detail-label">Jenis Kelamin</label>
                <select name="gender" class="form-select">
                    <option value="">Pilih</option>
                    <option value="male" <?= (($employee['gender'] ?? '') === 'male') ? 'selected' : '' ?>>
                        Laki-laki
                    </option>
                    <option value="female" <?= (($employee['gender'] ?? '') === 'female') ? 'selected' : '' ?>>
                        Perempuan
                    </option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="erp-detail-label">Tempat Lahir</label>
                <input
                    type="text"
                    name="birth_place"
                    class="form-control"
                    value="<?= htmlspecialchars($employee['birth_place'] ?? '') ?>"
                >
            </div>

            <div class="col-md-4">
                <label class="erp-detail-label">Tanggal Lahir</label>
                <input
                    type="date"
                    name="birth_date"
                    class="form-control"
                    value="<?= htmlspecialchars($employee['birth_date'] ?? '') ?>"
                >
            </div>

            <div class="col-md-4">
                <label class="erp-detail-label">No. HP</label>
                <input
                    type="text"
                    name="phone"
                    class="form-control"
                    value="<?= htmlspecialchars($employee['phone'] ?? '') ?>"
                >
            </div>

            <div class="col-md-4">
                <label class="erp-detail-label">Email</label>
                <input
                    type="email"
                    name="email"
                    class="form-control"
                    value="<?= htmlspecialchars($employee['email'] ?? '') ?>"
                >
            </div>

        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title mb-0">
            Informasi Pekerjaan
        </h4>
    </div>

    <div class="p-20">

        <div class="row g-4">

            <div class="col-md-4">
                <label class="erp-detail-label">Status Karyawan</label>
                <select name="employment_status" class="form-select">
                    <option value="permanent" <?= (($employee['employment_status'] ?? 'permanent') === 'permanent') ? 'selected' : '' ?>>
                        Tetap
                    </option>
                    <option value="contract" <?= (($employee['employment_status'] ?? '') === 'contract') ? 'selected' : '' ?>>
                        Kontrak
                    </option>
                    <option value="daily" <?= (($employee['employment_status'] ?? '') === 'daily') ? 'selected' : '' ?>>
                        Harian
                    </option>
                    <option value="intern" <?= (($employee['employment_status'] ?? '') === 'intern') ? 'selected' : '' ?>>
                        Magang
                    </option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="erp-detail-label">Divisi</label>
                <select name="department_id" class="form-select">
                    <option value="">Pilih Divisi</option>

                    <?php foreach (($departments ?? []) as $department): ?>
                        <option
                            value="<?= $department['id'] ?>"
                            <?= (($employee['department_id'] ?? '') == $department['id']) ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($department['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4">
                <label class="erp-detail-label">Jabatan</label>
                <select name="position_id" class="form-select">
                    <option value="">Pilih Jabatan</option>

                    <?php foreach (($positions ?? []) as $position): ?>
                        <option
                            value="<?= $position['id'] ?>"
                            <?= (($employee['position_id'] ?? '') == $position['id']) ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($position['name']) ?>
                            <?php if (!empty($position['department_name'])): ?>
                                - <?= htmlspecialchars($position['department_name']) ?>
                            <?php endif; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4">
                <label class="erp-detail-label">Tanggal Masuk</label>
                <input
                    type="date"
                    name="join_date"
                    class="form-control"
                    value="<?= htmlspecialchars($employee['join_date'] ?? '') ?>"
                >
            </div>

            <div class="col-md-4">
                <label class="erp-detail-label">Status Data</label>
                <select name="status" class="form-select">
                    <option value="active" <?= (($employee['status'] ?? 'active') === 'active') ? 'selected' : '' ?>>
                        Aktif
                    </option>
                    <option value="inactive" <?= (($employee['status'] ?? '') === 'inactive') ? 'selected' : '' ?>>
                        Nonaktif
                    </option>
                </select>
            </div>

        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title mb-0">
            Payroll & Rekening
        </h4>
    </div>

    <div class="p-20">

        <div class="row g-4">

            <div class="col-md-4">
                <label class="erp-detail-label">Gaji Pokok</label>
                <input
                    type="text"
                    name="basic_salary"
                    class="form-control"
                    value="<?= !empty($employee['basic_salary']) ? number_format($employee['basic_salary'], 0, ',', '.') : '' ?>"
                    placeholder="Contoh: 5000000"
                >
            </div>

            <div class="col-md-4">
                <label class="erp-detail-label">Tunjangan</label>
                <input
                    type="text"
                    name="allowance_amount"
                    class="form-control"
                    value="<?= !empty($employee['allowance_amount']) ? number_format($employee['allowance_amount'], 0, ',', '.') : '' ?>"
                    placeholder="Contoh: 5000000"
                >
            </div>

            <div class="col-md-4">
                <label class="erp-detail-label">Nama Bank</label>
                <input
                    type="text"
                    name="bank_name"
                    class="form-control"
                    value="<?= htmlspecialchars($employee['bank_name'] ?? '') ?>"
                    placeholder="Contoh: BCA"
                >
            </div>

            <div class="col-md-4">
                <label class="erp-detail-label">No. Rekening</label>
                <input
                    type="text"
                    name="bank_account_number"
                    class="form-control"
                    value="<?= htmlspecialchars($employee['bank_account_number'] ?? '') ?>"
                >
            </div>

            <div class="col-md-4">
                <label class="erp-detail-label">Nama Pemilik Rekening</label>
                <input
                    type="text"
                    name="bank_account_name"
                    class="form-control"
                    value="<?= htmlspecialchars($employee['bank_account_name'] ?? '') ?>"
                >
            </div>

        </div>

    </div>

</div>

<div class="row g-4 mb-4">

    <div class="col-lg-8">

        <div class="card bg-white rounded-10 border border-white h-100">

            <div class="p-20 border-bottom">
                <h4 class="erp-detail-section-title mb-0">
                    Alamat
                </h4>
            </div>

            <div class="p-20">
                <textarea
                    name="address"
                    rows="8"
                    class="form-control"
                    placeholder="Alamat lengkap karyawan"
                ><?= htmlspecialchars($employee['address'] ?? '') ?></textarea>
            </div>

        </div>

    </div>

    <div class="col-lg-4">

        <div class="card bg-white rounded-10 border border-white h-100">

            <div class="p-20 border-bottom">
                <h4 class="erp-detail-section-title mb-0">
                    Ringkasan
                </h4>
            </div>

            <div class="p-20">

                <div class="d-flex justify-content-between mb-2">
                    <span>Kode</span>
                    <strong>
                        <?= htmlspecialchars($employee['employee_code'] ?? '-') ?>
                    </strong>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Status Karyawan</span>
                    <strong>
                        <?= htmlspecialchars($employee['employment_status'] ?? 'permanent') ?>
                    </strong>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Status Data</span>
                    <strong>
                        <?= htmlspecialchars($employee['status'] ?? 'active') ?>
                    </strong>
                </div>

                <hr>

                <div class="text-body">
                    Data karyawan digunakan untuk absensi, cuti, lembur, payroll, dan administrasi HRD.
                </div>

            </div>

        </div>

    </div>

</div>