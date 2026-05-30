<?php
$department = $department ?? [];
?>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title mb-0">
            Informasi Divisi
        </h4>
    </div>

    <div class="p-20">

        <div class="row g-4">

            <div class="col-md-8">

                <label class="erp-detail-label">
                    Nama Divisi
                    <span class="text-danger">*</span>
                </label>

                <input
                    type="text"
                    name="name"
                    class="form-control"
                    value="<?= htmlspecialchars($department['name'] ?? '') ?>"
                    placeholder="Contoh: Operasional"
                    required
                >

            </div>

            <div class="col-md-4">

                <label class="erp-detail-label">
                    Status
                </label>

                <select
                    name="status"
                    class="form-select"
                >
                    <option
                        value="active"
                        <?= (($department['status'] ?? 'active') === 'active') ? 'selected' : '' ?>
                    >
                        Aktif
                    </option>

                    <option
                        value="inactive"
                        <?= (($department['status'] ?? '') === 'inactive') ? 'selected' : '' ?>
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
                    placeholder="Keterangan divisi"
                ><?= htmlspecialchars($department['description'] ?? '') ?></textarea>

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
                        <?= htmlspecialchars($department['status'] ?? 'active') ?>
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
                    Divisi digunakan untuk pengelompokan karyawan, struktur organisasi, payroll, dan pelaporan HRD.
                </div>

            </div>

        </div>

    </div>

</div>