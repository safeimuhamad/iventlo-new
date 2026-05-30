<?php
$isEdit = !empty($testimonial);
$action = $isEdit ? url('website-testimonials-update') : url('website-testimonials-store');
$services = $services ?? [];
$selectedCategoryId = $testimonial['category_id'] ?? '';
$selectedCategoryEn = $testimonial['category_en'] ?? '';
?>

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
                <?= $isEdit ? 'Edit Testimoni' : 'Tambah Testimoni' ?>
            </h3>

            <p class="mb-0 text-body">
                Kelola testimoni client dalam bahasa Indonesia dan English.
            </p>
        </div>

        <div class="d-flex justify-content-between justify-content-md-end align-items-center flex-wrap gap-3">
            <a href="<?= url('website-testimonials') ?>" class="btn btn-light erp-btn">
                <i class="ri-arrow-left-line me-1"></i>
                Kembali
            </a>
        </div>

    </div>

</div>

<form action="<?= $action ?>" method="POST" enctype="multipart/form-data">

    <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= htmlspecialchars($testimonial['id']) ?>">
    <?php endif; ?>

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Informasi Client
            </h4>
        </div>

        <div class="p-20">
            <div class="row g-4">

                <div class="col-md-4">
                    <label class="erp-detail-label">Nama Client</label>
                    <input type="text" name="name" class="form-control"
                           value="<?= htmlspecialchars($testimonial['name'] ?? '') ?>" required>
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">Perusahaan</label>
                    <input type="text" name="company_name" class="form-control"
                           value="<?= htmlspecialchars($testimonial['company_name'] ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">Jabatan / Posisi</label>
                    <input type="text" name="position" class="form-control"
                           value="<?= htmlspecialchars($testimonial['position'] ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">Kategori layanan</label>
                    <select
                        name="category_id"
                        class="form-control js-service-category"
                        data-target="#testimonial_category_en"
                    >
                        <option value="">-- Pilih kategori layanan --</option>
                        <?php foreach ($services as $service): ?>
                            <?php
                            $titleId = $service['title_id'] ?? '';
                            $titleEn = $service['title_en'] ?? $titleId;
                            $selected = $selectedCategoryId === $titleId || $selectedCategoryEn === $titleEn;
                            ?>
                            <option
                                value="<?= htmlspecialchars($titleId) ?>"
                                data-category-en="<?= htmlspecialchars($titleEn) ?>"
                                <?= $selected ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($titleId) ?>
                                <?php if ($titleEn !== '' && $titleEn !== $titleId): ?>
                                    / <?= htmlspecialchars($titleEn) ?>
                                <?php endif; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input
                        type="hidden"
                        name="category_en"
                        id="testimonial_category_en"
                        value="<?= htmlspecialchars($selectedCategoryEn) ?>"
                    >
                </div>

            </div>
        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Isi Testimoni
            </h4>
        </div>

        <div class="p-20">
            <div class="row g-4">

                <div class="col-md-6">
                    <label class="erp-detail-label">Testimonial ID</label>
                    <textarea name="testimonial_id" class="form-control" rows="6"><?= htmlspecialchars($testimonial['testimonial_id'] ?? '') ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Testimonial EN</label>
                    <textarea name="testimonial_en" class="form-control" rows="6"><?= htmlspecialchars($testimonial['testimonial_en'] ?? '') ?></textarea>
                </div>

            </div>
        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Foto & Pengaturan
            </h4>
        </div>

        <div class="p-20">
            <div class="row g-4">

                <div class="col-md-6">
                    <label class="erp-detail-label">Foto Client</label>
                    <input type="file" name="image" class="form-control" accept="image/*">

                    <?php if ($isEdit && !empty($testimonial['image'])): ?>
                        <div class="mt-3">
                            <img src="<?= uploadAsset($testimonial['image']) ?>"
                                 alt="Testimonial"
                                 style="width:120px;height:120px;object-fit:cover;border-radius:50%;">
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-3">
                    <label class="erp-detail-label">Rating</label>
                    <select name="rating" class="form-control">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <option value="<?= $i ?>" <?= ((int) ($testimonial['rating'] ?? 5) === $i) ? 'selected' : '' ?>>
                                <?= $i ?> Star
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="erp-detail-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="active" <?= (($testimonial['status'] ?? 'active') === 'active') ? 'selected' : '' ?>>
                            Active
                        </option>
                        <option value="inactive" <?= (($testimonial['status'] ?? '') === 'inactive') ? 'selected' : '' ?>>
                            Inactive
                        </option>
                    </select>
                </div>

            </div>
        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white p-20">

        <div class="d-flex justify-content-end flex-wrap gap-3">

            <a href="<?= url('website-testimonials') ?>" class="btn btn-light erp-btn">
                <i class="ri-close-line me-1"></i>
                Batal
            </a>

            <button type="submit" class="btn btn-primary text-white erp-btn">
                <i class="ri-save-line me-1"></i>
                <?= $isEdit ? 'Update Testimoni' : 'Simpan Testimoni' ?>
            </button>

        </div>

    </div>

</form>

<script>
document.querySelectorAll('.js-service-category').forEach(function (select) {
    var target = document.querySelector(select.dataset.target);

    function syncCategory() {
        if (!target) {
            return;
        }

        var option = select.options[select.selectedIndex];
        target.value = option ? (option.dataset.categoryEn || '') : '';
    }

    select.addEventListener('change', syncCategory);
    syncCategory();
});
</script>
