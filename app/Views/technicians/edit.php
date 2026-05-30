<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger mb-4">
        <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h3 class="mb-1">Edit Teknisi</h3>
            <p class="mb-0 text-body">Perbarui data teknisi dan status kesiapan operasional.</p>
        </div>
        <a href="<?= url('technicians') ?>" class="btn btn-light erp-btn">
            <i class="ri-arrow-left-line me-1"></i>Kembali
        </a>
    </div>
</div>

<form method="POST" action="<?= url('technicians-update') ?>">
    <input type="hidden" name="id" value="<?= htmlspecialchars($technician['id']) ?>">
    <?php include __DIR__ . '/form.php'; ?>
    <div class="card bg-white rounded-10 border border-white p-20">
        <div class="d-flex justify-content-end flex-wrap gap-3">
            <a href="<?= url('technicians') ?>" class="btn btn-light erp-btn">
                <i class="ri-close-line me-1"></i>Batal
            </a>
            <button type="submit" class="btn btn-primary text-white erp-btn">
                <i class="ri-save-line me-1"></i>Update
            </button>
        </div>
    </div>
</form>
