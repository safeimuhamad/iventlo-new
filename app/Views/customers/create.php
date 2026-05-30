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
                Tambah Customer
            </h3>

            <p class="mb-0 text-body">
                Lengkapi data customer untuk kebutuhan penawaran, invoice, dan order rental.
            </p>
        </div>

        <div class="d-flex justify-content-between justify-content-md-end align-items-center flex-wrap gap-3">

            <a
                href="<?= url('customers') ?>"
                class="btn btn-light erp-btn"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Kembali
            </a>

        </div>

    </div>

</div>

<form method="POST" action="<?= url('customers-store') ?>">

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Informasi Customer
            </h4>
        </div>

        <div class="p-20">

            <div class="row g-4">

                <div class="col-md-6">
                    <label class="erp-detail-label">
                        Nama Perusahaan / Customer <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="company_name"
                        class="form-control"
                        placeholder="Contoh: PT Micool Indonesia"
                        required
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">
                        Nama PIC
                    </label>

                    <input
                        type="text"
                        name="pic_name"
                        class="form-control"
                        placeholder="Nama kontak utama"
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">
                        No. HP
                    </label>

                    <input
                        type="text"
                        name="phone"
                        class="form-control"
                        placeholder="Contoh: 0812xxxxxxx"
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        placeholder="customer@email.com"
                    >
                </div>

            </div>

        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Detail Administrasi
            </h4>
        </div>

        <div class="p-20">

            <div class="row g-4">

                <div class="col-md-12">
                    <label class="erp-detail-label">
                        Alamat
                    </label>

                    <textarea
                        name="address"
                        class="form-control"
                        rows="4"
                        placeholder="Masukkan alamat lengkap customer"
                    ></textarea>
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">
                        NPWP
                    </label>

                    <input
                        type="text"
                        name="npwp"
                        class="form-control"
                        placeholder="Nomor NPWP customer"
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">
                        Status
                    </label>

                    <select name="status" class="form-control">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

            </div>

        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white p-20">

        <div class="d-flex justify-content-end flex-wrap gap-3">

            <a
                href="<?= url('customers') ?>"
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