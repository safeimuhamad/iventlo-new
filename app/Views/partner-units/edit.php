<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger mb-4">
        <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h3 class="mb-1">Edit Unit Vendor</h3>
            <p class="mb-0 text-body">
                Perbarui unit milik vendor untuk kebutuhan rental eksternal dan perhitungan biaya.
            </p>
        </div>
        <a href="<?= url('partner-units') ?>" class="btn btn-light erp-btn">
            <i class="ri-arrow-left-line me-1"></i>Kembali
        </a>
    </div>
</div>

<form method="POST" action="<?= url('partner-units-update') ?>">
    <input type="hidden" name="id" value="<?= htmlspecialchars($unit['id']) ?>">

    <div class="card bg-white rounded-10 border border-white mb-4">
        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">Informasi Unit Vendor</h4>
        </div>
        <div class="p-20">
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="erp-detail-label">Vendor <span class="text-danger">*</span></label>
                    <select name="partner_id" class="form-control" required>
                        <option value="">-- Pilih Vendor --</option>
                        <?php foreach ($partners as $partner): ?>
                            <option value="<?= $partner['id'] ?>" <?= (string) $unit['partner_id'] === (string) $partner['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($partner['partner_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="erp-detail-label">Nama Unit <span class="text-danger">*</span></label>
                    <input type="text" name="unit_name" class="form-control" value="<?= htmlspecialchars($unit['unit_name'] ?? '') ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="erp-detail-label">Kategori <span class="text-danger">*</span></label>
                    <select name="category" id="category" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php foreach (['AC', 'Air Cooler', 'Kipas', 'Genset'] as $category): ?>
                            <option value="<?= $category ?>" <?= ($unit['category'] ?? '') === $category ? 'selected' : '' ?>><?= $category ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="erp-detail-label">Brand</label>
                    <select name="brand" id="brand" class="form-control">
                        <option value="">-- Pilih Brand --</option>
                        <?php foreach ($brands as $brand): ?>
                            <option value="<?= htmlspecialchars($brand['name']) ?>" data-category="<?= htmlspecialchars($brand['category']) ?>" <?= ($unit['brand'] ?? '') === $brand['name'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($brand['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="erp-detail-label">Kapasitas</label>
                    <input type="text" name="capacity" class="form-control" value="<?= htmlspecialchars($unit['capacity'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="erp-detail-label">Modal Rental Vendor</label>
                    <input type="number" name="rental_cost" class="form-control" min="0" step="1000" value="<?= htmlspecialchars($unit['rental_cost'] ?? 0) ?>">
                </div>
                <div class="col-md-6">
                    <label class="erp-detail-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="active" <?= ($unit['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= ($unit['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card bg-white rounded-10 border border-white p-20">
        <div class="d-flex justify-content-end flex-wrap gap-3">
            <a href="<?= url('partner-units') ?>" class="btn btn-light erp-btn">
                <i class="ri-close-line me-1"></i>Batal
            </a>
            <button type="submit" class="btn btn-primary text-white erp-btn">
                <i class="ri-save-line me-1"></i>Update
            </button>
        </div>
    </div>
</form>
