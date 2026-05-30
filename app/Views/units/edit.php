<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger mb-4">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>
<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Edit Unit
            </h3>

            <p class="mb-0 text-body">
                Perbarui data unit untuk kebutuhan stok rental, status operasional, lokasi unit, dan monitoring maintenance.
            </p>
        </div>

        <a href="<?= url('units') ?>" class="btn btn-light erp-btn">
            <i class="ri-arrow-left-line me-1"></i>
            Kembali
        </a>

    </div>

</div>

<form method="POST" action="<?= url('units-update') ?>">

    <input type="hidden" name="id" value="<?= htmlspecialchars($unit['id']) ?>">

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Informasi Unit
            </h4>
        </div>

        <div class="p-20">

            <div class="row g-4">

                <div class="col-md-4">
                    <label class="erp-detail-label">
                        Kode Unit <span class="text-danger">*</span>
                    </label>
                    <input
                        type="text"
                        name="kode_unit"
                        class="form-control"
                        value="<?= htmlspecialchars($unit['kode_unit'] ?? '') ?>"
                        required
                    >
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">
                        Nama Unit <span class="text-danger">*</span>
                    </label>
                    <input
                        type="text"
                        name="nama_unit"
                        class="form-control"
                        value="<?= htmlspecialchars($unit['nama_unit'] ?? '') ?>"
                        required
                    >
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">
                        Tipe Unit
                    </label>
                    <input
                        type="text"
                        name="tipe_unit"
                        class="form-control"
                        value="<?= htmlspecialchars($unit['tipe_unit'] ?? '') ?>"
                    >
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">
                        Kategori <span class="text-danger">*</span>
                    </label>
                    <select name="kategori" id="kategori" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>

                        <?php
                        $categories = ['Genset', 'AC', 'Air Cooler', 'Kipas'];
                        foreach ($categories as $category):
                        ?>
                            <option value="<?= $category ?>" <?= ($unit['kategori'] ?? '') === $category ? 'selected' : '' ?>>
                                <?= $category ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">
                        Brand <span class="text-danger">*</span>
                    </label>
                    <select name="brand" id="brand" class="form-control" required>
                        <option value="">-- Pilih Brand --</option>

                        <?php foreach ($brands as $brand): ?>
                            <option
                                value="<?= htmlspecialchars($brand['name']) ?>"
                                data-category="<?= htmlspecialchars($brand['category']) ?>"
                                <?= ($unit['brand'] ?? '') === $brand['name'] ? 'selected' : '' ?>
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
                        name="kapasitas"
                        class="form-control"
                        value="<?= htmlspecialchars($unit['kapasitas'] ?? '') ?>"
                    >
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">
                        Status Unit
                    </label>
                    <select name="status_unit" class="form-control">
                        <?php
                        $statuses = ['available', 'maintenance', 'broken', 'inactive'];
                        foreach ($statuses as $status):
                        ?>
                            <option value="<?= $status ?>" <?= ($unit['status_unit'] ?? '') === $status ? 'selected' : '' ?>>
                                <?= ucfirst($status) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-8">
                    <label class="erp-detail-label">
                        Lokasi Sekarang
                    </label>
                    <input
                        type="text"
                        name="lokasi_sekarang"
                        class="form-control"
                        value="<?= htmlspecialchars($unit['lokasi_sekarang'] ?? 'Gudang') ?>"
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
                        Catatan
                    </h4>
                </div>

                <div class="p-20">
                    <textarea
                        name="notes"
                        class="form-control"
                        rows="8"
                        placeholder="Catatan tambahan unit"
                    ><?= htmlspecialchars($unit['notes'] ?? '') ?></textarea>
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
                        <span>Status Unit</span>
                        <strong><?= htmlspecialchars($unit['status_unit'] ?? '-') ?></strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Lokasi</span>
                        <strong><?= htmlspecialchars($unit['lokasi_sekarang'] ?? 'Gudang') ?></strong>
                    </div>

                    <hr>

                    <div class="text-body">
                        Perubahan data unit akan memengaruhi stok rental, jadwal operasional, dan histori maintenance.
                    </div>
                </div>

            </div>
        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white p-20">

        <div class="d-flex justify-content-end flex-wrap gap-3">

            <a href="<?= url('units') ?>" class="btn btn-light erp-btn">
                <i class="ri-close-line me-1"></i>
                Batal
            </a>

            <button type="submit" class="btn btn-primary text-white erp-btn">
                <i class="ri-save-line me-1"></i>
                Update
            </button>

        </div>

    </div>

</form>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const kategoriSelect = document.getElementById('kategori');
    const brandSelect = document.getElementById('brand');
    const selectedBrand = "<?= htmlspecialchars($unit['brand'] ?? '') ?>";
    const originalOptions = Array.from(brandSelect.options);

    function filterBrand() {
        const kategori = kategoriSelect.value;

        brandSelect.innerHTML = '';

        originalOptions.forEach(option => {
            if (option.value === '') {
                brandSelect.appendChild(option.cloneNode(true));
                return;
            }

            if (option.dataset.category === kategori) {
                const cloned = option.cloneNode(true);

                if (cloned.value === selectedBrand) {
                    cloned.selected = true;
                }

                brandSelect.appendChild(cloned);
            }
        });
    }

    filterBrand();
    kategoriSelect.addEventListener('change', function () {
        filterBrand();
        brandSelect.value = '';
    });
});
</script>
