<?php
$isEdit = !empty($portfolio);
$action = $isEdit ? url('website-portfolios-update') : url('website-portfolios-store');
$services = $services ?? [];
$selectedCategoryId = $portfolio['category_id'] ?? '';
$selectedCategoryEn = $portfolio['category_en'] ?? '';
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
                <?= $isEdit ? 'Edit Portfolio' : 'Tambah Portfolio' ?>
            </h3>

            <p class="mb-0 text-body">
                Kelola portfolio event dalam bahasa Indonesia dan English.
            </p>
        </div>

        <div class="d-flex justify-content-between justify-content-md-end align-items-center flex-wrap gap-3">
            <a href="<?= url('website-portfolios') ?>" class="btn btn-light erp-btn">
                <i class="ri-arrow-left-line me-1"></i>
                Kembali
            </a>
        </div>

    </div>

</div>

<form action="<?= $action ?>" method="POST" enctype="multipart/form-data">

    <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= htmlspecialchars($portfolio['id']) ?>">
    <?php endif; ?>

    <div class="card bg-white rounded-10 border border-white mb-4">
        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Informasi Event
            </h4>
        </div>

        <div class="p-20">
            <div class="row g-4">

                <div class="col-md-6">
                    <label class="erp-detail-label">Title ID</label>
                    <input type="text" name="title_id" class="form-control"
                           value="<?= htmlspecialchars($portfolio['title_id'] ?? '') ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Title EN</label>
                    <input type="text" name="title_en" class="form-control"
                           value="<?= htmlspecialchars($portfolio['title_en'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="erp-detail-label">Slug ID</label>
                    <input
                        type="text"
                        name="slug_id"
                        class="form-control"
                        value="<?= htmlspecialchars($portfolio['slug_id'] ?? '') ?>"
                        placeholder="auto generate jika kosong"
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Slug EN</label>
                    <input
                        type="text"
                        name="slug_en"
                        class="form-control"
                        value="<?= htmlspecialchars($portfolio['slug_en'] ?? '') ?>"
                        placeholder="auto generate if empty"
                    >
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">Kategori layanan</label>
                    <select
                        name="category_id"
                        class="form-control js-service-category"
                        data-target="#portfolio_category_en"
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
                        id="portfolio_category_en"
                        value="<?= htmlspecialchars($selectedCategoryEn) ?>"
                    >
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">Client Name</label>
                    <input type="text" name="client_name" class="form-control"
                           value="<?= htmlspecialchars($portfolio['client_name'] ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">Event Date</label>
                    <input type="date" name="event_date" class="form-control"
                           value="<?= htmlspecialchars($portfolio['event_date'] ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">Location ID</label>
                    <input type="text" name="location_id" class="form-control"
                           value="<?= htmlspecialchars($portfolio['location_id'] ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">Location EN</label>
                    <input type="text" name="location_en" class="form-control"
                           value="<?= htmlspecialchars($portfolio['location_en'] ?? '') ?>">
                </div>

            </div>
        </div>
    </div>

    <div class="card bg-white rounded-10 border border-white mb-4">
        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Deskripsi Portfolio
            </h4>
        </div>

        <div class="p-20">
            <div class="row g-4">

                <div class="col-md-6">
                    <label class="erp-detail-label">Description ID</label>
                    <textarea name="description_id" class="form-control" rows="8"><?= htmlspecialchars($portfolio['description_id'] ?? '') ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Description EN</label>
                    <textarea name="description_en" class="form-control" rows="8"><?= htmlspecialchars($portfolio['description_en'] ?? '') ?></textarea>
                </div>

            </div>
        </div>
    </div>

    <div class="card bg-white rounded-10 border border-white mb-4">
        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Media & Pengaturan
            </h4>
        </div>

        <div class="p-20">
            <div class="row g-4">

                <div class="col-md-8">
                    <label class="erp-detail-label">Thumbnail</label>
                    <input type="file" name="thumbnail" class="form-control" accept="image/*">

                    <?php if ($isEdit && !empty($portfolio['thumbnail'])): ?>
                        <div class="mt-3">
                            <img src="<?= uploadAsset($portfolio['thumbnail']) ?>"
                                 alt="Portfolio"
                                 style="width:240px;height:140px;object-fit:cover;border-radius:10px;">
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="active" <?= in_array(($portfolio['status'] ?? 'active'), ['active', 'publish'], true) ? 'selected' : '' ?>>
                            Active
                        </option>
                        <option value="inactive" <?= (($portfolio['status'] ?? '') === 'inactive') ? 'selected' : '' ?>>
                            Inactive
                        </option>
                    </select>
                </div>

            </div>
        </div>
    </div>

    <div class="card bg-white rounded-10 border border-white p-20">

        <div class="d-flex justify-content-end flex-wrap gap-3">

            <a href="<?= url('website-portfolios') ?>" class="btn btn-light erp-btn">
                <i class="ri-close-line me-1"></i>
                Batal
            </a>

            <button type="submit" class="btn btn-primary text-white erp-btn">
                <i class="ri-save-line me-1"></i>
                <?= $isEdit ? 'Update Portfolio' : 'Simpan Portfolio' ?>
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
