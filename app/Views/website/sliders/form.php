<?php
$isEdit = !empty($slider);
$action = $isEdit ? url('website-sliders-update') : url('website-sliders-store');
?>

<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger mb-4">
        <?= $_SESSION['error']; ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success mb-4">
        <?= $_SESSION['success']; ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h3 class="mb-1">
                <?= $isEdit ? 'Edit Slider' : 'Tambah Slider' ?>
            </h3>

            <p class="mb-0 text-body">
                Isi konten slider homepage dalam bahasa Indonesia dan English.
            </p>
        </div>

        <div class="d-flex justify-content-between justify-content-md-end align-items-center flex-wrap gap-3">
            <a href="<?= url('website-sliders') ?>" class="btn btn-light erp-btn">
                <i class="ri-arrow-left-line me-1"></i>
                Kembali
            </a>
        </div>
    </div>
</div>

<form action="<?= $action ?>" method="post" enctype="multipart/form-data">
    <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= htmlspecialchars($slider['id']) ?>">
    <?php endif; ?>

    <div class="card bg-white rounded-10 border border-white mb-4">
        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Konten Slider
            </h4>
        </div>

        <div class="p-20">
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="erp-detail-label">Subtitle ID</label>
                    <input
                        type="text"
                        name="subtitle_id"
                        class="form-control"
                        value="<?= htmlspecialchars($slider['subtitle_id'] ?? '') ?>"
                        placeholder="EVENT ORGANIZER"
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Subtitle EN</label>
                    <input
                        type="text"
                        name="subtitle_en"
                        class="form-control"
                        value="<?= htmlspecialchars($slider['subtitle_en'] ?? '') ?>"
                        placeholder="EVENT ORGANIZER"
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Title ID</label>
                    <input
                        type="text"
                        name="title_id"
                        class="form-control"
                        value="<?= htmlspecialchars($slider['title_id'] ?? '') ?>"
                        required
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Title EN</label>
                    <input
                        type="text"
                        name="title_en"
                        class="form-control"
                        value="<?= htmlspecialchars($slider['title_en'] ?? '') ?>"
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Description ID</label>
                    <textarea
                        name="description_id"
                        class="form-control"
                        rows="4"
                    ><?= htmlspecialchars($slider['description_id'] ?? '') ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Description EN</label>
                    <textarea
                        name="description_en"
                        class="form-control"
                        rows="4"
                    ><?= htmlspecialchars($slider['description_en'] ?? '') ?></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="card bg-white rounded-10 border border-white mb-4">
        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Tombol & Pengaturan
            </h4>
        </div>

        <div class="p-20">
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="erp-detail-label">Button Text ID</label>
                    <input
                        type="text"
                        name="button_text_id"
                        class="form-control"
                        value="<?= htmlspecialchars($slider['button_text_id'] ?? '') ?>"
                        placeholder="Konsultasi Sekarang"
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Button Text EN</label>
                    <input
                        type="text"
                        name="button_text_en"
                        class="form-control"
                        value="<?= htmlspecialchars($slider['button_text_en'] ?? '') ?>"
                        placeholder="Start Consultation"
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Button Link</label>
                    <input
                        type="text"
                        name="button_link"
                        class="form-control"
                        value="<?= htmlspecialchars($slider['button_link'] ?? '') ?>"
                        placeholder="<?= htmlspecialchars(frontUrl('contact', [], 'id')) ?>"
                    >
                </div>

                <div class="col-md-3">
                    <label class="erp-detail-label">Sort Order</label>
                    <input
                        type="number"
                        name="sort_order"
                        class="form-control"
                        value="<?= htmlspecialchars($slider['sort_order'] ?? 0) ?>"
                    >
                </div>

                <div class="col-md-3">
                    <label class="erp-detail-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="active" <?= (($slider['status'] ?? 'active') === 'active') ? 'selected' : '' ?>>
                            Active
                        </option>
                        <option value="inactive" <?= (($slider['status'] ?? '') === 'inactive') ? 'selected' : '' ?>>
                            Inactive
                        </option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card bg-white rounded-10 border border-white mb-4">
        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Gambar Slider
            </h4>
        </div>

        <div class="p-20">
            <div class="row g-4">
                <div class="col-md-12">
                    <label class="erp-detail-label">Image</label>
                    <input
                        type="file"
                        name="image"
                        class="form-control"
                        accept="image/*"
                    >

                    <p class="text-body mt-2 mb-0">
                        Rekomendasi ukuran gambar: 1920 x 900 px.
                    </p>

                    <?php if ($isEdit && !empty($slider['image'])): ?>
                        <div class="mt-3">
                            <img
                                src="<?= uploadAsset($slider['image']) ?>"
                                alt="Slider"
                                style="width: 240px; height: 120px; object-fit: cover; border-radius: 10px;"
                            >
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card bg-white rounded-10 border border-white p-20">
        <div class="d-flex justify-content-end flex-wrap gap-3">
            <a href="<?= url('website-sliders') ?>" class="btn btn-light erp-btn">
                <i class="ri-close-line me-1"></i>
                Batal
            </a>

            <button type="submit" class="btn btn-primary text-white erp-btn">
                <i class="ri-save-line me-1"></i>
                <?= $isEdit ? 'Update Slider' : 'Simpan Slider' ?>
            </button>
        </div>
    </div>
</form>
