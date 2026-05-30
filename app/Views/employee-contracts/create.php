<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger mb-4">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Tambah Kontrak Karyawan
            </h3>

            <p class="mb-0 text-body">
                Tambahkan data kontrak kerja karyawan untuk kebutuhan administrasi HRD dan monitoring masa kontrak.
            </p>
        </div>

        <a
            href="<?= url('employee-contracts') ?>"
            class="btn btn-light erp-btn"
        >
            <i class="ri-arrow-left-line me-1"></i>
            Kembali
        </a>

    </div>

</div>

<form method="POST" action="<?= url('employee-contracts-store') ?>">

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Informasi Karyawan
            </h4>
        </div>

        <div class="p-20">

            <div class="row g-4">

                <div class="col-md-6">

                    <label class="erp-detail-label">
                        Karyawan
                    </label>

                    <select
                        name="employee_id"
                        class="form-select"
                        required
                    >
                        <option value="">
                            -- Pilih Karyawan --
                        </option>

                        <?php foreach ($employees as $employee): ?>
                            <option value="<?= $employee['id'] ?>">
                                <?= htmlspecialchars(
                                    ($employee['employee_code'] ?? '-') .
                                    ' - ' .
                                    ($employee['full_name'] ?? '-')
                                ) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="col-md-3">

                    <label class="erp-detail-label">
                        Tipe Kontrak
                    </label>

                    <select
                        name="contract_type"
                        class="form-select"
                    >
                        <option value="probation">Probation</option>
                        <option value="contract" selected>Kontrak</option>
                        <option value="permanent">Permanent</option>
                        <option value="freelance">Freelance</option>
                        <option value="internship">Internship</option>
                    </select>

                </div>

                <div class="col-md-3">

                    <label class="erp-detail-label">
                        Status
                    </label>

                    <select
                        name="status"
                        class="form-select"
                    >
                        <option value="active" selected>Active</option>
                        <option value="expired">Expired</option>
                        <option value="terminated">Terminated</option>
                        <option value="renewed">Renewed</option>
                    </select>

                </div>

            </div>

        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Periode Kontrak
            </h4>
        </div>

        <div class="p-20">

            <div class="row g-4">

                <div class="col-md-4">

                    <label class="erp-detail-label">
                        Tanggal Mulai
                    </label>

                    <input
                        type="date"
                        name="start_date"
                        class="form-control"
                        required
                    >

                </div>

                <div class="col-md-4">

                    <label class="erp-detail-label">
                        Tanggal Selesai
                    </label>

                    <input
                        type="date"
                        name="end_date"
                        class="form-control"
                    >

                </div>

                <div class="col-md-4">

                    <label class="erp-detail-label">
                        Lokasi Kerja
                    </label>

                    <input
                        type="text"
                        name="work_location"
                        class="form-control"
                        placeholder="Contoh: Jakarta"
                    >

                </div>

            </div>

        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Jabatan & Kompensasi
            </h4>
        </div>

        <div class="p-20">

            <div class="row g-4">

                <div class="col-md-6">

                    <label class="erp-detail-label">
                        Jabatan
                    </label>

                    <input
                        type="text"
                        name="job_title"
                        class="form-control"
                        placeholder="Contoh: Teknisi HVAC"
                    >

                </div>

                <div class="col-md-6">

                    <label class="erp-detail-label">
                        Salary / Gaji
                    </label>

                    <input
                        type="text"
                        class="form-control rupiah-input"
                        data-target="salary"
                        placeholder="0"
                    >

                    <input
                        type="hidden"
                        name="salary"
                        id="salary"
                        value="0"
                    >

                </div>

            </div>

        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Dokumen Kontrak
            </h4>
        </div>

        <div class="p-20">

            <label class="erp-detail-label">
                URL PDF Kontrak (Google Drive)
            </label>

            <input
                type="url"
                name="contract_pdf_url"
                class="form-control"
                placeholder="https://drive.google.com/file/d/..."
            >

            <small class="text-muted">
                Masukkan link file PDF kontrak dari Google Drive.
            </small>

        </div>

    </div>

    <div class="row g-4 mb-4">

        <div class="col-lg-8">

            <div class="card bg-white rounded-10 border border-white h-100">

                <div class="p-20 border-bottom">
                    <h4 class="erp-detail-section-title mb-0">
                        Catatan Tambahan
                    </h4>
                </div>

                <div class="p-20">

                    <textarea
                        name="notes"
                        class="form-control"
                        rows="8"
                        placeholder="Catatan kontrak karyawan"
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
                        <span>Status</span>
                        <strong>Active</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Tipe Kontrak</span>
                        <strong>Kontrak</strong>
                    </div>

                    <hr>

                    <div class="text-body">
                        Data kontrak digunakan untuk monitoring masa kerja, perpanjangan kontrak, dan administrasi HRD.
                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white p-20">

        <div class="d-flex justify-content-end flex-wrap gap-3">

            <a
                href="<?= url('employee-contracts') ?>"
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

<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.rupiah-input').forEach(input => {

        function formatRupiah() {

            let value = input.value.replace(/\D/g, '');

            const target = document.getElementById(input.dataset.target);

            if (target) {
                target.value = value || 0;
            }

            input.value = value
                ? new Intl.NumberFormat('id-ID').format(value)
                : '';
        }

        formatRupiah();

        input.addEventListener('input', formatRupiah);

    });

});
</script>