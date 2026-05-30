<?php
$isEdit = !empty($product);
$action = $isEdit ? url('website-products-update') : url('website-products-store');
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
                <?= $isEdit ? 'Edit Produk' : 'Tambah Produk' ?>
            </h3>

            <p class="mb-0 text-body">
                Kelola produk, paket, atau penawaran event dalam bahasa Indonesia dan English.
            </p>
        </div>

        <div class="d-flex justify-content-between justify-content-md-end align-items-center flex-wrap gap-3">
            <a href="<?= url('website-products') ?>" class="btn btn-light erp-btn">
                <i class="ri-arrow-left-line me-1"></i>
                Kembali
            </a>
        </div>

    </div>

</div>

<form action="<?= $action ?>" method="POST" enctype="multipart/form-data">

    <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">
    <?php endif; ?>

    <div class="card bg-white rounded-10 border border-white mb-4">
        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Informasi Produk
            </h4>
        </div>

        <div class="p-20">
            <div class="row g-4">

                <div class="col-md-6">
                    <label class="erp-detail-label">Title ID</label>
                    <input type="text" name="title_id" class="form-control"
                           value="<?= htmlspecialchars($product['title_id'] ?? '') ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Title EN</label>
                    <input type="text" name="title_en" class="form-control"
                           value="<?= htmlspecialchars($product['title_en'] ?? '') ?>">
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Category</label>
                    <input type="text" name="category" class="form-control"
                           value="<?= htmlspecialchars($product['category'] ?? '') ?>"
                           placeholder="Corporate Event / Wedding / Launching">
                </div>

                <div class="col-md-3">
                    <label class="erp-detail-label">Price Label ID</label>
                    <input type="text" name="price_label_id" class="form-control"
                           value="<?= htmlspecialchars($product['price_label_id'] ?? '') ?>"
                           placeholder="Custom Quote">
                </div>

                <div class="col-md-3">
                    <label class="erp-detail-label">Price Label EN</label>
                    <input type="text" name="price_label_en" class="form-control"
                           value="<?= htmlspecialchars($product['price_label_en'] ?? '') ?>"
                           placeholder="Custom Quote">
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Description ID</label>
                    <textarea name="description_id" class="form-control" rows="7"><?= htmlspecialchars($product['description_id'] ?? '') ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Description EN</label>
                    <textarea name="description_en" class="form-control" rows="7"><?= htmlspecialchars($product['description_en'] ?? '') ?></textarea>
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
                    <label class="erp-detail-label">Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">

                    <?php if ($isEdit && !empty($product['image'])): ?>
                        <div class="mt-3">
                            <img src="<?= uploadAsset($product['image']) ?>"
                                 alt="Product"
                                 style="width:240px;height:140px;object-fit:cover;border-radius:10px;">
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="active" <?= (($product['status'] ?? 'active') === 'active') ? 'selected' : '' ?>>
                            Active
                        </option>
                        <option value="inactive" <?= (($product['status'] ?? '') === 'inactive') ? 'selected' : '' ?>>
                            Inactive
                        </option>
                    </select>
                </div>

            </div>
        </div>
    </div>

    <div class="card bg-white rounded-10 border border-white p-20">

        <div class="d-flex justify-content-end flex-wrap gap-3">

            <a href="<?= url('website-products') ?>" class="btn btn-light erp-btn">
                <i class="ri-close-line me-1"></i>
                Batal
            </a>

            <button type="submit" class="btn btn-primary text-white erp-btn">
                <i class="ri-save-line me-1"></i>
                <?= $isEdit ? 'Update Produk' : 'Simpan Produk' ?>
            </button>

        </div>

    </div>

</form>