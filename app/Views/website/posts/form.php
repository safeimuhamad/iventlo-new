<?php
$isEdit = !empty($post);
$action = $isEdit ? url('website-posts-update') : url('website-posts-store');
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
                <?= $isEdit ? 'Edit Artikel' : 'Tambah Artikel' ?>
            </h3>

            <p class="mb-0 text-body">
                Kelola artikel bilingual untuk kebutuhan SEO dan publikasi website Iventlo.
            </p>
        </div>

        <div class="d-flex justify-content-between justify-content-md-end align-items-center flex-wrap gap-3">
            <a href="<?= url('website-posts') ?>" class="btn btn-light erp-btn">
                <i class="ri-arrow-left-line me-1"></i>
                Kembali
            </a>
        </div>

    </div>

</div>

<form action="<?= $action ?>" method="POST" enctype="multipart/form-data">

    <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= htmlspecialchars($post['id']) ?>">
    <?php endif; ?>

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Informasi Artikel
            </h4>
        </div>

        <div class="p-20">
            <div class="row g-4">

                <div class="col-md-6">
                    <label class="erp-detail-label">Title ID</label>
                    <input
                        type="text"
                        name="title_id"
                        class="form-control"
                        value="<?= htmlspecialchars($post['title_id'] ?? '') ?>"
                        required
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Title EN</label>
                    <input
                        type="text"
                        name="title_en"
                        class="form-control"
                        value="<?= htmlspecialchars($post['title_en'] ?? '') ?>"
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Slug ID</label>
                    <input
                        type="text"
                        name="slug_id"
                        class="form-control"
                        value="<?= htmlspecialchars($post['slug_id'] ?? '') ?>"
                        placeholder="otomatis dari title jika dikosongkan"
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Slug EN</label>
                    <input
                        type="text"
                        name="slug_en"
                        class="form-control"
                        value="<?= htmlspecialchars($post['slug_en'] ?? '') ?>"
                        placeholder="auto from English title if empty"
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Excerpt ID</label>
                    <textarea
                        name="excerpt_id"
                        class="form-control"
                        rows="4"
                    ><?= htmlspecialchars($post['excerpt_id'] ?? '') ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Excerpt EN</label>
                    <textarea
                        name="excerpt_en"
                        class="form-control"
                        rows="4"
                    ><?= htmlspecialchars($post['excerpt_en'] ?? '') ?></textarea>
                </div>

            </div>
        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Konten Artikel
            </h4>
        </div>

        <div class="p-20">
            <div class="row g-4">

                <div class="col-md-6">
                    <label class="erp-detail-label">Content ID</label>
                    <textarea
                        name="content_id"
                        class="form-control"
                        rows="12"
                    ><?= htmlspecialchars($post['content_id'] ?? '') ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Content EN</label>
                    <textarea
                        name="content_en"
                        class="form-control"
                        rows="12"
                    ><?= htmlspecialchars($post['content_en'] ?? '') ?></textarea>
                </div>

            </div>
        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                SEO
            </h4>
        </div>

        <div class="p-20">
            <div class="row g-4">

                <div class="col-md-12">
                    <label class="erp-detail-label">Meta Title</label>
                    <input
                        type="text"
                        name="meta_title"
                        class="form-control"
                        value="<?= htmlspecialchars($post['meta_title'] ?? '') ?>"
                    >
                </div>

                <div class="col-md-12">
                    <label class="erp-detail-label">Meta Keywords</label>
                    <textarea
                        name="meta_keywords"
                        class="form-control"
                        rows="3"
                    ><?= htmlspecialchars($post['meta_keywords'] ?? '') ?></textarea>
                </div>

                <div class="col-md-12">
                    <label class="erp-detail-label">Meta Description</label>
                    <textarea
                        name="meta_description"
                        class="form-control"
                        rows="4"
                    ><?= htmlspecialchars($post['meta_description'] ?? '') ?></textarea>
                </div>

            </div>
        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Publikasi
            </h4>
        </div>

        <div class="p-20">
            <div class="row g-4">

                <div class="col-md-4">
                    <label class="erp-detail-label">Featured Image</label>
                    <input
                        type="file"
                        name="featured_image"
                        class="form-control"
                        accept="image/*"
                    >

                    <?php if ($isEdit && !empty($post['featured_image'])): ?>
                        <div class="mt-3">
                            <img
                                src="<?= uploadAsset($post['featured_image']) ?>"
                                alt="Featured Image"
                                style="width:240px;height:140px;object-fit:cover;border-radius:10px;"
                            >
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-3">
                    <label class="erp-detail-label">Published At</label>
                    <input
                        type="datetime-local"
                        name="published_at"
                        class="form-control"
                        value="<?= !empty($post['published_at'])
                            ? date('Y-m-d\TH:i', strtotime($post['published_at']))
                            : '' ?>"
                    >
                </div>

                <div class="col-md-2">
                    <label class="erp-detail-label">Sort Order</label>
                    <input
                        type="number"
                        name="sort_order"
                        class="form-control"
                        value="<?= htmlspecialchars($post['sort_order'] ?? 0) ?>"
                    >
                </div>

                <div class="col-md-3">
                    <label class="erp-detail-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="draft" <?= (($post['status'] ?? 'draft') === 'draft') ? 'selected' : '' ?>>
                            Draft
                        </option>
                        <option value="published" <?= in_array(($post['status'] ?? ''), ['published', 'publish'], true) ? 'selected' : '' ?>>
                            Published
                        </option>
                    </select>
                </div>

            </div>
        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white p-20">

        <div class="d-flex justify-content-end flex-wrap gap-3">

            <a href="<?= url('website-posts') ?>" class="btn btn-light erp-btn">
                <i class="ri-close-line me-1"></i>
                Batal
            </a>

            <button type="submit" class="btn btn-primary text-white erp-btn">
                <i class="ri-save-line me-1"></i>
                <?= $isEdit ? 'Update Artikel' : 'Simpan Artikel' ?>
            </button>

        </div>

    </div>

</form>
