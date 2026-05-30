<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger mb-4">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Edit Kasbon Karyawan
            </h3>

            <p class="mb-0 text-body">
                Perbarui informasi pengajuan kasbon sebelum proses approval atau pencairan dana dilakukan.
            </p>
        </div>

        <a
            href="<?= url('employee-cash-advances-show') ?>?id=<?= $item['id'] ?>"
            class="btn btn-light erp-btn"
        >
            <i class="ri-arrow-left-line me-1"></i>
            Kembali
        </a>

    </div>

</div>

<form method="POST" action="<?= url('employee-cash-advances-update') ?>">

    <input
        type="hidden"
        name="id"
        value="<?= $item['id'] ?>"
    >

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Informasi Pengajuan
            </h4>
        </div>

        <div class="p-20">

            <div class="row g-4">

                <div class="col-md-6">

                    <label class="erp-detail-label">
                        Karyawan
                    </label>

                    <select
                        name="employee_id"
                        class="form-select"
                        disabled
                    >
                        <option value="">
                            -- Pilih Karyawan --
                        </option>

                        <?php foreach ($employees as $employee): ?>
                            <option
                                value="<?= $employee['id'] ?>"
                                <?= ($item['employee_id'] ?? '') == $employee['id'] ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($employee['full_name'] ?? '-') ?>

                                <?php if (!empty($employee['employee_code'])): ?>
                                    - <?= htmlspecialchars($employee['employee_code']) ?>
                                <?php endif; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                </div>

                <div class="col-md-6">

                    <label class="erp-detail-label">
                        Tanggal Pengajuan
                    </label>

                    <input
                        type="date"
                        name="request_date"
                        class="form-control"
                        value="<?= htmlspecialchars($item['request_date'] ?? date('Y-m-d')) ?>"
                        readonly
                    >

                </div>

                <div class="col-md-6">

                    <label class="erp-detail-label">
                        Nominal Kasbon <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        id="amount_display"
                        class="form-control"
                        placeholder="0"
                        autocomplete="off"
                        value="<?= !empty($item['amount']) ? number_format((float)$item['amount'], 0, ',', '.') : '' ?>"
                        required
                    >

                    <input
                        type="hidden"
                        name="amount"
                        id="amount"
                        value="<?= htmlspecialchars($item['amount'] ?? '') ?>"
                    >

                </div>

                <div class="col-md-6">

                    <label class="erp-detail-label">
                        Keperluan <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="purpose"
                        class="form-control"
                        value="<?= htmlspecialchars($item['purpose'] ?? '') ?>"
                        required
                    >

                </div>

            </div>

        </div>

    </div>

    <div class="row g-4 mb-4">

        <div class="col-lg-8">

            <div class="card bg-white rounded-10 border border-white h-100">

                <div class="p-20 border-bottom">
                    <h4 class="erp-detail-section-title mb-0">
                        Keterangan
                    </h4>
                </div>

                <div class="p-20">

                    <textarea
                        name="description"
                        rows="8"
                        class="form-control"
                        placeholder="Jelaskan kebutuhan penggunaan kasbon"
                    ><?= htmlspecialchars($item['description'] ?? '') ?></textarea>

                </div>

            </div>

        </div>

        <div class="col-lg-4">

            <div class="card bg-white rounded-10 border border-white h-100">

                <div class="p-20 border-bottom">
                    <h4 class="erp-detail-section-title mb-0">
                        Ringkasan
                    </h4>
                </div>

                <div class="p-20">

                    <div class="d-flex justify-content-between mb-2">
                        <span>Karyawan</span>
                        <strong>
                            <?= htmlspecialchars($item['employee_name'] ?? '-') ?>
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Tanggal</span>
                        <strong>
                            <?= !empty($item['request_date']) ? date('d M Y', strtotime($item['request_date'])) : '-' ?>
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Status</span>
                        <strong>
                            <?= ucfirst(str_replace('_', ' ', $item['status'] ?? 'draft')) ?>
                        </strong>
                    </div>

                    <hr>

                    <div class="text-body">
                        Perubahan data kasbon dapat mempengaruhi proses approval yang sedang berjalan sesuai DOA Matrix.
                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white p-20">

        <div class="d-flex justify-content-end flex-wrap gap-3">

            <a
                href="<?= url('employee-cash-advances-show') ?>?id=<?= $item['id'] ?>"
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    const displayInput = document.getElementById('amount_display');
    const hiddenInput  = document.getElementById('amount');

    if (!displayInput || !hiddenInput) return;

    if (hiddenInput.value) {
        displayInput.value = new Intl.NumberFormat('id-ID').format(hiddenInput.value);
    }

    displayInput.addEventListener('input', function () {
        let value = this.value.replace(/\D/g, '');
        hiddenInput.value = value;
        this.value = new Intl.NumberFormat('id-ID').format(value);
    });
});
</script>