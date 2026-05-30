<?php
$isEdit = !empty($faq);
$action = $isEdit ? url('website-faqs-update') : url('website-faqs-store');
$services = $services ?? [];
$selectedCategoryId = $faq['category_id'] ?? '';
$selectedCategoryEn = $faq['category_en'] ?? '';
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
                <?= $isEdit ? 'Edit FAQ' : 'Tambah FAQ' ?>
            </h3>

            <p class="mb-0 text-body">
                Kelola pertanyaan umum dalam bahasa Indonesia dan English.
            </p>
        </div>

        <div class="d-flex justify-content-between justify-content-md-end align-items-center flex-wrap gap-3">
            <a href="<?= url('website-faqs') ?>" class="btn btn-light erp-btn">
                <i class="ri-arrow-left-line me-1"></i>
                Kembali
            </a>
        </div>

    </div>

</div>

<form action="<?= $action ?>" method="POST">

    <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= htmlspecialchars($faq['id']) ?>">
    <?php endif; ?>

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Pertanyaan FAQ
            </h4>
        </div>

        <div class="p-20">
            <div class="row g-4">

                <div class="col-md-12">
                    <label class="erp-detail-label">Kategori layanan</label>
                    <select
                        name="category_id"
                        class="form-control js-service-category"
                        data-target="#faq_category_en"
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
                        id="faq_category_en"
                        value="<?= htmlspecialchars($selectedCategoryEn) ?>"
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Question ID</label>
                    <input
                        type="text"
                        name="question_id"
                        class="form-control"
                        value="<?= htmlspecialchars($faq['question_id'] ?? '') ?>"
                        required
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Question EN</label>
                    <input
                        type="text"
                        name="question_en"
                        class="form-control"
                        value="<?= htmlspecialchars($faq['question_en'] ?? '') ?>"
                    >
                </div>

            </div>
        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Jawaban FAQ
            </h4>
        </div>

        <div class="p-20">
            <div class="row g-4">

                <div class="col-md-6">
                    <label class="erp-detail-label">Answer ID</label>
                    <textarea
                        name="answer_id"
                        class="form-control"
                        rows="8"
                    ><?= htmlspecialchars($faq['answer_id'] ?? '') ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Answer EN</label>
                    <textarea
                        name="answer_en"
                        class="form-control"
                        rows="8"
                    ><?= htmlspecialchars($faq['answer_en'] ?? '') ?></textarea>
                </div>

            </div>
        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Pengaturan
            </h4>
        </div>

        <div class="p-20">
            <div class="row g-4">

                <div class="col-md-4">
                    <label class="erp-detail-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="active" <?= (($faq['status'] ?? 'active') === 'active') ? 'selected' : '' ?>>
                            Active
                        </option>
                        <option value="inactive" <?= (($faq['status'] ?? '') === 'inactive') ? 'selected' : '' ?>>
                            Inactive
                        </option>
                    </select>
                </div>

            </div>
        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white p-20">

        <div class="d-flex justify-content-end flex-wrap gap-3">

            <a href="<?= url('website-faqs') ?>" class="btn btn-light erp-btn">
                <i class="ri-close-line me-1"></i>
                Batal
            </a>

            <button type="submit" class="btn btn-primary text-white erp-btn">
                <i class="ri-save-line me-1"></i>
                <?= $isEdit ? 'Update FAQ' : 'Simpan FAQ' ?>
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
