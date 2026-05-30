<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger mb-4">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>
<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Tambah Unit Vendor
            </h3>

            <p class="mb-0 text-body">
                Tambahkan unit milik vendor untuk kebutuhan rental partner, perhitungan margin, dan ketersediaan unit eksternal.
            </p>
        </div>

        <a href="<?= url('partner-units') ?>" class="btn btn-light erp-btn">
            <i class="ri-arrow-left-line me-1"></i>
            Kembali
        </a>

    </div>

</div>

<form method="POST" action="<?= url('partner-units-store') ?>">

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Informasi Unit Vendor
            </h4>
        </div>

        <div class="p-20">

            <div class="row g-4">

                <div class="col-md-6">
                    <label class="erp-detail-label">
                        Vendor <span class="text-danger">*</span>
                    </label>

                    <select name="partner_id" class="form-control" required>
                        <option value="">-- Pilih Vendor --</option>

                        <?php foreach ($partners as $partner): ?>
                            <option value="<?= $partner['id'] ?>">
                                <?= htmlspecialchars($partner['partner_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">
                        Nama Unit <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="unit_name"
                        class="form-control"
                        required
                    >
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">
                        Kategori <span class="text-danger">*</span>
                    </label>

                    <select name="category" id="category" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="AC">AC</option>
                        <option value="Air Cooler">Air Cooler</option>
                        <option value="Kipas">Kipas</option>
                        <option value="Genset">Genset</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">
                        Brand
                    </label>

                    <select name="brand" id="brand" class="form-control">
                        <option value="">-- Pilih Brand --</option>

                        <?php foreach ($brands as $brand): ?>
                            <option
                                value="<?= htmlspecialchars($brand['name']) ?>"
                                data-category="<?= htmlspecialchars($brand['category']) ?>"
                            >
                                <?= htmlspecialchars($brand['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">
                        Kapasitas
                    </label>

                    <input
                        type="text"
                        name="capacity"
                        class="form-control"
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">
                        Modal Rental Vendor
                    </label>

                    <input
                        type="number"
                        name="rental_cost"
                        class="form-control"
                        min="0"
                        step="1000"
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
                        placeholder="Catatan unit vendor"
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
                        <span>Tipe</span>
                        <strong>Unit Vendor</strong>
                    </div>

                    <hr>

                    <div class="text-body">
                        Unit vendor digunakan sebagai unit eksternal untuk mendukung kebutuhan rental ketika stok internal tidak mencukupi.
                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white p-20">

        <div class="d-flex justify-content-end flex-wrap gap-3">

            <a href="<?= url('partner-units') ?>" class="btn btn-light erp-btn">
                <i class="ri-close-line me-1"></i>
                Batal
            </a>

            <button type="submit" class="btn btn-primary text-white erp-btn">
                <i class="ri-save-line me-1"></i>
                Simpan
            </button>

        </div>

    </div>

</form>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const categorySelect = document.getElementById('category');
    const brandSelect = document.getElementById('brand');

    if (!categorySelect || !brandSelect) return;

    const originalOptions = Array.from(brandSelect.options);

    function filterBrand() {
        const category = categorySelect.value;

        brandSelect.innerHTML = '';

        originalOptions.forEach(option => {
            if (option.value === '') {
                brandSelect.appendChild(option.cloneNode(true));
                return;
            }

            if (option.dataset.category === category) {
                brandSelect.appendChild(option.cloneNode(true));
            }
        });

        brandSelect.value = '';
    }

    categorySelect.addEventListener('change', filterBrand);
});
</script>