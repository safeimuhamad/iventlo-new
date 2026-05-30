
<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger mb-4">
        <?= $_SESSION['error']; ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>
<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Proses Maintenance Unit
            </h3>

            <p class="mb-0 text-body">
                <?= htmlspecialchars($unit['kode_unit'] ?? '-') ?> -
                <?= htmlspecialchars($unit['nama_unit'] ?? '-') ?>
            </p>
        </div>

        <a
            href="<?= url('unit-maintenance') ?>"
            class="btn btn-light erp-btn"
        >
            <i class="ri-arrow-left-line me-1"></i>
            Kembali
        </a>

    </div>

</div>

<form
    action="<?= url('unit-maintenance-store') ?>"
    method="POST"
    enctype="multipart/form-data"
>

    <input
        type="hidden"
        name="unit_id"
        value="<?= $unit['id'] ?>"
    >

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Informasi Maintenance
            </h4>
        </div>

        <div class="p-20">

            <div class="row g-4">

                <div class="col-md-4">
                    <label class="erp-detail-label">
                        Tanggal Maintenance
                    </label>

                    <input
                        type="date"
                        name="maintenance_date"
                        class="form-control"
                        value="<?= date('Y-m-d') ?>"
                        required
                    >
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">
                        Jenis Maintenance
                    </label>

                    <select
                        name="maintenance_type"
                        class="form-select"
                        required
                    >
                        <option value="ringan">Ringan</option>
                        <option value="besar">Besar</option>
                        <option value="perbaikan">Perbaikan</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">
                        Nama Teknisi
                    </label>

                    <input
                        type="text"
                        name="technician_name"
                        class="form-control"
                        placeholder="Nama teknisi"
                    >
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">
                        Biaya Maintenance
                    </label>

                    <input
                        type="number"
                        name="cost"
                        class="form-control"
                        value="0"
                        min="0"
                    >
                </div>

                <div class="col-md-8">
                    <label class="erp-detail-label">
                        Upload Dokumentasi
                    </label>

                    <input
                        type="file"
                        name="documents[]"
                        class="form-control"
                        multiple
                        accept="image/*,.pdf"
                    >

                    <small class="text-muted">
                        Bisa upload foto hasil maintenance atau PDF dokumentasi.
                    </small>
                </div>

            </div>

        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Checklist Maintenance
            </h4>
        </div>

        <div class="p-20">

            <div class="table-responsive">

                <table class="table align-middle mb-0">

                    <thead>
                        <tr>
                            <th>Checklist</th>
                            <th width="180">Status</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($checklists as $checklist): ?>

                            <tr>

                                <td>
                                    <?= htmlspecialchars($checklist) ?>

                                    <input
                                        type="hidden"
                                        name="checklist_name[]"
                                        value="<?= htmlspecialchars($checklist) ?>"
                                    >
                                </td>

                                <td>

                                    <select
                                        name="checklist_status[]"
                                        class="form-select"
                                    >
                                        <option value="ok">
                                            OK
                                        </option>

                                        <option value="not_ok">
                                            Tidak OK
                                        </option>

                                        <option value="need_repair">
                                            Perlu Perbaikan
                                        </option>
                                    </select>

                                </td>

                                <td>

                                    <input
                                        type="text"
                                        name="checklist_notes[]"
                                        class="form-control"
                                        placeholder="Catatan"
                                    >

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <div class="row g-4 mb-4">

        <div class="col-lg-8">

            <div class="card bg-white rounded-10 border border-white h-100">

                <div class="p-20 border-bottom">
                    <h4 class="erp-detail-section-title mb-0">
                        Catatan Umum
                    </h4>
                </div>

                <div class="p-20">

                    <textarea
                        name="notes"
                        class="form-control"
                        rows="8"
                        placeholder="Catatan hasil maintenance"
                    ></textarea>

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
                        <span>Unit</span>

                        <strong>
                            <?= htmlspecialchars($unit['kode_unit'] ?? '-') ?>
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Status Saat Ini</span>

                        <strong>
                            <?= htmlspecialchars($unit['status_unit'] ?? '-') ?>
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Maintenance Status</span>

                        <strong>
                            <?= htmlspecialchars($unit['maintenance_status'] ?? 'normal') ?>
                        </strong>
                    </div>

                    <hr>

                    <div class="text-body">
                        Setelah maintenance disimpan, riwayat maintenance dan checklist akan tercatat pada unit.
                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white p-20">

        <div class="d-flex justify-content-end flex-wrap gap-3">

            <a
                href="<?= url('unit-maintenance') ?>"
                class="btn btn-light erp-btn"
            >
                <i class="ri-close-line me-1"></i>
                Batal
            </a>

            <button
                type="submit"
                class="btn btn-primary text-white erp-btn"
            >
                <i class="ri-save-line me-1"></i>
                Simpan
            </button>

        </div>

    </div>

</form>