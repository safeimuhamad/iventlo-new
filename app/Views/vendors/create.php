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
                Tambah Vendor
            </h3>

            <p class="mb-0 text-body">
                Tambahkan data vendor untuk kebutuhan pembelian, purchase order, dan administrasi supplier.
            </p>
        </div>

        <a
            href="<?= url('vendors') ?>"
            class="btn btn-light erp-btn"
        >
            <i class="ri-arrow-left-line me-1"></i>
            Kembali
        </a>

    </div>

</div>

<form method="POST" action="<?= url('vendors-store') ?>">

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Informasi Vendor
            </h4>
        </div>

        <div class="p-20">

            <div class="row g-4">

                <div class="col-md-3">
                    <label class="erp-detail-label">Kode Vendor</label>
                    <input
                        type="text"
                        name="vendor_code"
                        class="form-control"
                        value="<?= htmlspecialchars($vendorCode ?? '') ?>"
                        readonly
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">
                        Nama Vendor <span class="text-danger">*</span>
                    </label>
                    <input
                        type="text"
                        name="vendor_name"
                        class="form-control"
                        required
                    >
                </div>

                <div class="col-md-3">
                    <label class="erp-detail-label">Nama PIC</label>
                    <input
                        type="text"
                        name="pic_name"
                        class="form-control"
                    >
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">No Telepon</label>
                    <input
                        type="text"
                        name="phone"
                        class="form-control"
                    >
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">Email</label>
                    <input
                        type="email"
                        name="email"
                        class="form-control"
                    >
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">NPWP</label>
                    <input
                        type="text"
                        name="npwp"
                        class="form-control"
                    >
                </div>

                <div class="col-md-12">
                    <label class="erp-detail-label">Alamat</label>
                    <textarea
                        name="address"
                        class="form-control"
                        rows="3"
                    ></textarea>
                </div>

            </div>

        </div>

    </div>

    <div class="row g-4 mb-4">

        <div class="col-lg-8">
            <div class="card bg-white rounded-10 border border-white h-100">

                <div class="p-20 border-bottom">
                    <h4 class="erp-detail-section-title mb-0">
                        Catatan
                    </h4>
                </div>

                <div class="p-20">
                    <textarea
                        name="notes"
                        class="form-control"
                        rows="8"
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
                        <span>Status Awal</span>
                        <strong>Active</strong>
                    </div>

                    <hr>

                    <div class="text-body">
                        Vendor yang disimpan akan dapat digunakan pada Purchase Order.
                    </div>
                </div>

            </div>
        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white p-20">

        <div class="d-flex justify-content-end flex-wrap gap-3">

            <a
                href="<?= url('vendors') ?>"
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