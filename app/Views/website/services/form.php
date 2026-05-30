<?php
$isEdit = !empty($service);
$action = $isEdit ? url('website-services-update') : url('website-services-store');
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
                <?= $isEdit ? 'Edit Layanan' : 'Tambah Layanan' ?>
            </h3>

            <p class="mb-0 text-body">
                Isi konten layanan dalam bahasa Indonesia dan English.
            </p>
        </div>

        <div class="d-flex justify-content-between justify-content-md-end align-items-center flex-wrap gap-3">
            <a href="<?= url('website-services') ?>" class="btn btn-light erp-btn">
                <i class="ri-arrow-left-line me-1"></i>
                Kembali
            </a>
        </div>

    </div>

</div>

<form action="<?= $action ?>" method="POST" enctype="multipart/form-data">

    <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= htmlspecialchars($service['id']) ?>">
    <?php endif; ?>

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Konten Layanan
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
                        value="<?= htmlspecialchars($service['title_id'] ?? '') ?>"
                        required
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Title EN</label>
                    <input
                        type="text"
                        name="title_en"
                        class="form-control"
                        value="<?= htmlspecialchars($service['title_en'] ?? '') ?>"
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Description ID</label>
                    <textarea
                        name="description_id"
                        class="form-control"
                        rows="6"
                    ><?= htmlspecialchars($service['description_id'] ?? '') ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Description EN</label>
                    <textarea
                        name="description_en"
                        class="form-control"
                        rows="6"
                    ><?= htmlspecialchars($service['description_en'] ?? '') ?></textarea>
                </div>

            </div>

        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                SEO Layanan
            </h4>
        </div>

        <div class="p-20">

            <div class="row g-4">

                <div class="col-md-6">
                    <label class="erp-detail-label">Meta Title ID</label>
                    <input
                        type="text"
                        name="meta_title_id"
                        class="form-control"
                        value="<?= htmlspecialchars($service['meta_title_id'] ?? '') ?>"
                        placeholder="Contoh: Corporate event organizer profesional"
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Meta Title EN</label>
                    <input
                        type="text"
                        name="meta_title_en"
                        class="form-control"
                        value="<?= htmlspecialchars($service['meta_title_en'] ?? '') ?>"
                        placeholder="Example: Professional corporate event organizer"
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Meta Description ID</label>
                    <textarea
                        name="meta_description_id"
                        class="form-control"
                        rows="4"
                        placeholder="Ringkasan singkat halaman layanan untuk hasil pencarian Google."
                    ><?= htmlspecialchars($service['meta_description_id'] ?? '') ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Meta Description EN</label>
                    <textarea
                        name="meta_description_en"
                        class="form-control"
                        rows="4"
                        placeholder="Short service page summary for search result snippets."
                    ><?= htmlspecialchars($service['meta_description_en'] ?? '') ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Meta Keywords ID</label>
                    <textarea
                        name="meta_keywords_id"
                        class="form-control"
                        rows="3"
                        placeholder="event organizer, corporate event, layanan event"
                    ><?= htmlspecialchars($service['meta_keywords_id'] ?? '') ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Meta Keywords EN</label>
                    <textarea
                        name="meta_keywords_en"
                        class="form-control"
                        rows="3"
                        placeholder="event organizer, corporate event, event services"
                    ><?= htmlspecialchars($service['meta_keywords_en'] ?? '') ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">OG Title ID</label>
                    <input
                        type="text"
                        name="og_title_id"
                        class="form-control"
                        value="<?= htmlspecialchars($service['og_title_id'] ?? '') ?>"
                        placeholder="Judul saat dibagikan ke media sosial"
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">OG Title EN</label>
                    <input
                        type="text"
                        name="og_title_en"
                        class="form-control"
                        value="<?= htmlspecialchars($service['og_title_en'] ?? '') ?>"
                        placeholder="Social sharing title"
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">OG Description ID</label>
                    <textarea
                        name="og_description_id"
                        class="form-control"
                        rows="4"
                        placeholder="Deskripsi saat link layanan dibagikan."
                    ><?= htmlspecialchars($service['og_description_id'] ?? '') ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">OG Description EN</label>
                    <textarea
                        name="og_description_en"
                        class="form-control"
                        rows="4"
                        placeholder="Description when the service link is shared."
                    ><?= htmlspecialchars($service['og_description_en'] ?? '') ?></textarea>
                </div>

                <div class="col-md-12">
                    <label class="erp-detail-label">Meta Robots</label>
                    <input
                        type="text"
                        name="meta_robots"
                        class="form-control"
                        value="<?= htmlspecialchars($service['meta_robots'] ?? 'index, follow, max-image-preview:large') ?>"
                        placeholder="index, follow, max-image-preview:large"
                    >
                    <p class="text-body fs-14 mt-2 mb-0">
                        Gunakan default agar halaman layanan public bisa di-index. Halaman admin, client, dan member tetap noindex dari konfigurasi terpisah.
                    </p>
                </div>

            </div>

        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Tampilan & Pengaturan
            </h4>
        </div>

        <div class="p-20">

            <div class="row g-4">

                <div class="col-md-6">
                    <label class="erp-detail-label">Icon Class</label>
                    <input
                        type="text"
                        name="icon"
                        class="form-control"
                        value="<?= htmlspecialchars($service['icon'] ?? '') ?>"
                        placeholder="flaticon-speech"
                    >
                    <p class="text-body fs-14 mt-2 mb-0">
                        Contoh: flaticon-speech, flaticon-team, flaticon-creativity
                    </p>
                </div>

                <div class="col-md-3">
                    <label class="erp-detail-label">Sort Order</label>
                    <input
                        type="number"
                        name="sort_order"
                        class="form-control"
                        value="<?= htmlspecialchars($service['sort_order'] ?? 0) ?>"
                    >
                </div>

                <div class="col-md-3">
                    <label class="erp-detail-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="active" <?= (($service['status'] ?? 'active') === 'active') ? 'selected' : '' ?>>
                            Active
                        </option>
                        <option value="inactive" <?= (($service['status'] ?? '') === 'inactive') ? 'selected' : '' ?>>
                            Inactive
                        </option>
                    </select>
                </div>

                <div class="col-md-12">
                    <label class="erp-detail-label">Image</label>
                    <input
                        type="file"
                        name="image"
                        class="form-control"
                        accept="image/*"
                    >

                    <?php if ($isEdit && !empty($service['image'])): ?>
                        <div class="mt-3">
                            <img
                                src="<?= uploadAsset($service['image']) ?>"
                                alt="Service"
                                style="width:240px;height:140px;object-fit:cover;border-radius:10px;"
                            >
                        </div>
                    <?php endif; ?>
                </div>

            </div>

        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white p-20">

        <div class="d-flex justify-content-end flex-wrap gap-3">

            <a href="<?= url('website-services') ?>" class="btn btn-light erp-btn">
                <i class="ri-close-line me-1"></i>
                Batal
            </a>

            <button type="submit" class="btn btn-primary text-white erp-btn">
                <i class="ri-save-line me-1"></i>
                <?= $isEdit ? 'Update Layanan' : 'Simpan Layanan' ?>
            </button>

        </div>

    </div>

</form>
