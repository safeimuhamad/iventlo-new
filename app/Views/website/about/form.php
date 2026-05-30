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
    <div>
        <h3 class="mb-1">Tentang Kami</h3>
        <p class="mb-0 text-body">
            Kelola konten halaman Tentang Kami website Iventlo.
        </p>
    </div>
</div>

<form method="POST" action="<?= url('website-about-update') ?>" enctype="multipart/form-data">

    <div class="card bg-white rounded-10 border border-white mb-4">
        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">Konten Utama</h4>
        </div>

        <div class="p-20">
            <div class="row g-4">

                <div class="col-md-6">
                    <label class="erp-detail-label">Title ID</label>
                    <input type="text" name="title_id" class="form-control"
                           value="<?= htmlspecialchars($about['title_id'] ?? '') ?>">
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Title EN</label>
                    <input type="text" name="title_en" class="form-control"
                           value="<?= htmlspecialchars($about['title_en'] ?? '') ?>">
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Content ID</label>
                    <textarea name="content_id" class="form-control" rows="7"><?= htmlspecialchars($about['content_id'] ?? '') ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Content EN</label>
                    <textarea name="content_en" class="form-control" rows="7"><?= htmlspecialchars($about['content_en'] ?? '') ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Gambar 1</label>
                    <input type="file" name="image" class="form-control" accept="image/*">

                    <?php if (!empty($about['image'])): ?>
                        <div class="mt-3">
                            <img src="<?= uploadAsset($about['image']) ?>"
                                 alt="Preview gambar 1 Tentang Kami"
                                 style="width:240px;height:140px;object-fit:cover;border-radius:10px;">
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Gambar 2</label>
                    <input type="file" name="image_2" class="form-control" accept="image/*">

                    <?php if (!empty($about['image_2'])): ?>
                        <div class="mt-3">
                            <img src="<?= uploadAsset($about['image_2']) ?>"
                                 alt="Preview gambar 2 Tentang Kami"
                                 style="width:240px;height:140px;object-fit:cover;border-radius:10px;">
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>

    <div class="card bg-white rounded-10 border border-white mb-4">
        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">Visi & Misi</h4>
        </div>

        <div class="p-20">
            <div class="row g-4">

                <div class="col-md-6">
                    <label class="erp-detail-label">Vision ID</label>
                    <textarea name="vision_id" class="form-control" rows="5"><?= htmlspecialchars($about['vision_id'] ?? '') ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Vision EN</label>
                    <textarea name="vision_en" class="form-control" rows="5"><?= htmlspecialchars($about['vision_en'] ?? '') ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Mission ID</label>
                    <textarea name="mission_id" class="form-control" rows="7"><?= htmlspecialchars($about['mission_id'] ?? '') ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Mission EN</label>
                    <textarea name="mission_en" class="form-control" rows="7"><?= htmlspecialchars($about['mission_en'] ?? '') ?></textarea>
                </div>

            </div>
        </div>
    </div>

    <div class="card bg-white rounded-10 border border-white p-20">
        <div class="d-flex justify-content-end flex-wrap gap-3">

            <button type="submit" class="btn btn-primary text-white erp-btn">
                <i class="ri-save-line me-1"></i>
                Simpan Tentang Kami
            </button>

        </div>
    </div>

</form>
