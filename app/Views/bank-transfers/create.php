<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger mb-4">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Tambah Transfer Antar Rekening
            </h3>

            <p class="mb-0 text-body">
                Catat transfer dana antar rekening perusahaan untuk kebutuhan mutasi kas, bank, dan jurnal otomatis.
            </p>
        </div>

        <a href="<?= url('bank-transfers') ?>" class="btn btn-light erp-btn">
            <i class="ri-arrow-left-line me-1"></i>
            Kembali
        </a>

    </div>

</div>

<form method="POST" action="<?= url('bank-transfers-store') ?>">

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Informasi Transfer
            </h4>
        </div>

        <div class="p-20">

            <div class="row g-4">

                <div class="col-md-4">
                    <label class="erp-detail-label">
                        Tanggal Transfer <span class="text-danger">*</span>
                    </label>

                    <input
                        type="date"
                        name="transfer_date"
                        class="form-control"
                        value="<?= date('Y-m-d') ?>"
                        required
                    >
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">
                        Dari Rekening <span class="text-danger">*</span>
                    </label>

                    <select name="from_bank_account_id" class="form-control" required>
                        <option value="">-- Pilih Rekening Asal --</option>

                        <?php foreach ($bankAccounts as $account): ?>
                            <option value="<?= $account['id'] ?>">
                                <?= htmlspecialchars($account['account_code'] ?? '-') ?>
                                -
                                <?= htmlspecialchars($account['account_name'] ?? '-') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">
                        Ke Rekening <span class="text-danger">*</span>
                    </label>

                    <select name="to_bank_account_id" class="form-control" required>
                        <option value="">-- Pilih Rekening Tujuan --</option>

                        <?php foreach ($bankAccounts as $account): ?>
                            <option value="<?= $account['id'] ?>">
                                <?= htmlspecialchars($account['account_code'] ?? '-') ?>
                                -
                                <?= htmlspecialchars($account['account_name'] ?? '-') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">
                        Nominal Transfer <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        id="amount_display"
                        class="form-control"
                        value="0"
                        required
                    >

                    <input
                        type="hidden"
                        name="amount"
                        id="amount"
                        value="0"
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">
                        No Referensi
                    </label>

                    <input
                        type="text"
                        name="reference_no"
                        class="form-control"
                        placeholder="Optional"
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
                        Catatan
                    </h4>
                </div>

                <div class="p-20">
                    <textarea
                        name="notes"
                        class="form-control"
                        rows="8"
                        placeholder="Catatan transfer..."
                    ></textarea>
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
                        <span>Tanggal</span>
                        <strong><?= date('d M Y') ?></strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Tipe</span>
                        <strong>Transfer Bank</strong>
                    </div>

                    <hr>

                    <div class="text-body">
                        Transfer antar rekening akan mengurangi saldo rekening asal dan menambah saldo rekening tujuan.
                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white p-20">

        <div class="d-flex justify-content-end flex-wrap gap-3">

            <a href="<?= url('bank-transfers') ?>" class="btn btn-light erp-btn">
                <i class="ri-close-line me-1"></i>
                Batal
            </a>

            <button class="btn btn-primary text-white erp-btn">
                <i class="ri-save-line me-1"></i>
                Simpan
            </button>

        </div>

    </div>

</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const displayInput = document.getElementById('amount_display');
    const hiddenInput = document.getElementById('amount');

    function formatRupiah(value) {
        return Number(value || 0).toLocaleString('id-ID');
    }

    function parseRupiah(value) {
        return String(value || '').replace(/\./g, '').replace(/,/g, '');
    }

    if (displayInput) {
        displayInput.addEventListener('input', function () {
            const cleanValue = parseRupiah(this.value);

            hiddenInput.value = cleanValue;
            this.value = formatRupiah(cleanValue);
        });
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const displayInput = document.getElementById('amount_display');
    const hiddenInput = document.getElementById('amount');

    function formatRupiah(value) {
        return Number(value || 0).toLocaleString('id-ID');
    }

    function parseRupiah(value) {
        return String(value || '').replace(/\./g, '').replace(/,/g, '');
    }

    if (displayInput) {
        displayInput.addEventListener('input', function () {
            const cleanValue = parseRupiah(this.value);

            hiddenInput.value = cleanValue;
            this.value = formatRupiah(cleanValue);
        });
    }
});
</script>