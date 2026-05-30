<?php
$position = $position ?? [];
?>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title mb-0">
            Informasi Jabatan
        </h4>
    </div>

    <div class="p-20">

        <div class="row g-4">

            <div class="col-md-6">

                <label class="erp-detail-label">
                    Divisi
                </label>

                <select name="department_id" class="form-select">

                    <option value="">
                        Pilih Divisi
                    </option>

                    <?php foreach ($departments as $department): ?>
                        <option
                            value="<?= $department['id'] ?>"
                            <?= (($position['department_id'] ?? '') == $department['id']) ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($department['name']) ?>
                        </option>
                    <?php endforeach; ?>

                </select>

            </div>

            <div class="col-md-6">

                <label class="erp-detail-label">
                    Nama Jabatan
                    <span class="text-danger">*</span>
                </label>

                <input
                    type="text"
                    name="name"
                    class="form-control"
                    value="<?= htmlspecialchars($position['name'] ?? '') ?>"
                    placeholder="Contoh: Teknisi HVAC"
                    required
                >

            </div>

            <div class="col-md-4">

                <label class="erp-detail-label">
                    Status
                </label>

                <select name="status" class="form-select">

                    <option
                        value="active"
                        <?= (($position['status'] ?? 'active') === 'active') ? 'selected' : '' ?>
                    >
                        Aktif
                    </option>

                    <option
                        value="inactive"
                        <?= (($position['status'] ?? '') === 'inactive') ? 'selected' : '' ?>
                    >
                        Nonaktif
                    </option>

                </select>

            </div>

        </div>

    </div>

</div>

<div class="row g-4 mb-4">

    <div class="col-lg-8">

        <div class="card bg-white rounded-10 border border-white h-100">

            <div class="p-20 border-bottom">
                <h4 class="erp-detail-section-title mb-0">
                    Deskripsi
                </h4>
            </div>

            <div class="p-20">

                <textarea
                    name="description"
                    rows="8"
                    class="form-control"
                    placeholder="Keterangan jabatan"
                ><?= htmlspecialchars($position['description'] ?? '') ?></textarea>

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
                    <span>Status</span>

                    <strong>
                        <?= htmlspecialchars($position['status'] ?? 'active') ?>
                    </strong>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Modul</span>

                    <strong>
                        HRD
                    </strong>
                </div>

                <hr>

                <div class="text-body">
                    Jabatan digunakan untuk struktur organisasi, payroll, absensi, cuti, lembur, dan pengelolaan SDM.
                </div>

            </div>

        </div>

    </div>

</div>