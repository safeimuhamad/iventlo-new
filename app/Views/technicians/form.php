<div class="card bg-white rounded-10 border border-white mb-4">
    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title mb-0">Informasi Teknisi</h4>
    </div>
    <div class="p-20">
        <div class="row g-4">
            <div class="col-md-6">
                <label class="erp-detail-label">Nama Teknisi <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($technician['name'] ?? '') ?>" required>
            </div>
            <div class="col-md-6">
                <label class="erp-detail-label">No. HP</label>
                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($technician['phone'] ?? '') ?>">
            </div>
            <div class="col-md-6">
                <label class="erp-detail-label">Role / Keahlian</label>
                <input type="text" name="role_type" class="form-control" value="<?= htmlspecialchars($technician['role_type'] ?? 'Teknisi') ?>">
            </div>
            <div class="col-md-6">
                <label class="erp-detail-label">Status</label>
                <select name="status" class="form-control">
                    <option value="active" <?= ($technician['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= ($technician['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
        </div>
    </div>
</div>
