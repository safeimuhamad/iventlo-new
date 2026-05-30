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
                Edit Lembur
            </h3>

            <p class="mb-0 text-body">
                Perbarui data pengajuan lembur karyawan termasuk jadwal, alasan, dan status persetujuan.
            </p>
        </div>

        <a
            href="<?= url('overtime-requests-show') ?>?id=<?= $overtimeRequest['id'] ?>"
            class="btn btn-light erp-btn"
        >
            <i class="ri-arrow-left-line me-1"></i>
            Kembali
        </a>

    </div>

</div>

<form action="<?= url('overtime-requests-update') ?>" method="POST">

    <input
        type="hidden"
        name="id"
        value="<?= $overtimeRequest['id'] ?>"
    >

    <?php include __DIR__ . '/form.php'; ?>

    <div class="card bg-white rounded-10 border border-white p-20">

        <div class="d-flex justify-content-end flex-wrap gap-3">

            <a
                href="<?= url('overtime-requests-show') ?>?id=<?= $overtimeRequest['id'] ?>"
                class="btn btn-light erp-btn"
            >
                <i class="ri-close-line me-1"></i>
                Batal
            </a>

            <button
                type="submit"
                class="btn btn-primary text-white erp-btn"
            >
                <i class="ri-save-line me-1"></i>
                Update
            </button>

        </div>

    </div>

</form>