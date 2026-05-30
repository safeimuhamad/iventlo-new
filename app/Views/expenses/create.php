<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger mb-4">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Tambah Pengeluaran
            </h3>

            <p class="mb-0 text-body">
                Catat pengeluaran operasional perusahaan yang dibayarkan melalui kas atau rekening bank.
            </p>
        </div>

        <div class="text-end">
            <div class="fs-20 fw-bold">
                Total
                <span id="grand-total-text">Rp 0</span>
            </div>
        </div>

    </div>

</div>

<form method="POST" action="<?= url('expenses-store') ?>">

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Informasi Pengeluaran
            </h4>
        </div>

        <div class="p-20">

            <div class="row g-4">

                <div class="col-md-4">
                    <label class="erp-detail-label">
                        Bayar Dari <span class="text-danger">*</span>
                    </label>

                    <select name="bank_account_id" class="form-control" required>
                        <option value="">-- Pilih Rekening --</option>

                        <?php foreach ($bankAccounts as $account): ?>
                            <option value="<?= $account['id'] ?>">
                                <?= htmlspecialchars($account['account_code'] ?? '-') ?>
                                -
                                <?= htmlspecialchars($account['account_name'] ?? '-') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="erp-detail-label">
                        Tanggal Transaksi <span class="text-danger">*</span>
                    </label>

                    <input
                        type="date"
                        name="expense_date"
                        class="form-control"
                        value="<?= date('Y-m-d') ?>"
                        required
                    >
                </div>

                <div class="col-md-3">
                    <label class="erp-detail-label">
                        Metode Pembayaran
                    </label>

                    <select name="payment_method" class="form-control">
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="cash">Cash</option>
                        <option value="qris">QRIS</option>
                        <option value="debit">Debit</option>
                    </select>
                </div>

                <div class="col-md-2">
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

                <div class="col-md-4">
                    <label class="erp-detail-label">
                        Penerima / Beneficiary
                    </label>

                    <input
                        type="text"
                        name="beneficiary"
                        class="form-control"
                        placeholder="Nama penerima/vendor"
                    >
                </div>

                <div class="col-md-8">
                    <label class="erp-detail-label">
                        Alamat / Catatan Penerima
                    </label>

                    <textarea
                        name="billing_address"
                        class="form-control"
                        rows="2"
                        placeholder="Optional"
                    ></textarea>
                </div>

            </div>

        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom d-flex justify-content-between align-items-center">

            <h4 class="erp-detail-section-title mb-0">
                Detail Pengeluaran
            </h4>

            <button
                type="button"
                id="add-expense-item"
                class="btn btn-outline-primary btn-sm"
            >
                + Tambah Baris
            </button>

        </div>

        <div class="p-20">

            <div id="expense-items-wrapper">

                <div class="expense-item-row border rounded-10 p-3 mb-3">

                    <div class="row align-items-end g-3">

                        <div class="col-md-3">

                            <label class="erp-detail-label">
                                Akun Biaya
                            </label>

                            <select
                                name="expense_account[]"
                                class="form-control"
                                required
                            >

                                <option value="">
                                    -- Pilih Akun Biaya --
                                </option>

                                <?php foreach ($expenseAccounts as $coa): ?>

                                    <?php if (in_array($coa['account_type'], ['expense', 'cost'])): ?>

                                        <option value="<?= $coa['id'] ?>">
                                            <?= htmlspecialchars($coa['account_code']) ?>
                                            -
                                            <?= htmlspecialchars($coa['account_name']) ?>
                                        </option>

                                    <?php endif; ?>

                                <?php endforeach; ?>

                            </select>

                        </div>

                        <div class="col-md-6">

                            <label class="erp-detail-label">
                                Deskripsi
                            </label>

                            <textarea
                                name="item_description[]"
                                class="form-control"
                                rows="1"
                                placeholder="Keterangan biaya..."
                            ></textarea>

                        </div>

                        <div class="col-md-2">

                            <label class="erp-detail-label">
                                Nominal
                            </label>

                            <input
                                type="text"
                                name="item_amount[]"
                                class="form-control item-amount text-end"
                                value="0"
                                required
                            >

                        </div>

                        <div class="col-md-1 text-end">

                            <label class="d-block">&nbsp;</label>

                            <button
                                type="button"
                                class="btn btn-outline-danger remove-item"
                            >
                                <i class="ri-delete-bin-line"></i>
                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="row g-4 mb-4">

        <div class="col-lg-8">

            <div class="card bg-white rounded-10 border border-white h-100">

                <div class="p-20 border-bottom">
                    <h4 class="erp-detail-section-title mb-0">
                        Catatan Umum
                    </h4>
                </div>

                <div class="p-20">

                    <textarea
                        name="description"
                        class="form-control"
                        rows="8"
                        placeholder="Catatan pengeluaran..."
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
                        <span>Status</span>
                        <strong>Draft</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Tipe</span>
                        <strong>Expense</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Total</span>
                        <strong id="summary-total">
                            Rp 0
                        </strong>
                    </div>

                    <hr>

                    <div class="text-body">
                        Pengeluaran akan otomatis membuat jurnal dan mengurangi saldo rekening yang dipilih.
                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white p-20">

        <div class="d-flex justify-content-end flex-wrap gap-3">

            <a
                href="<?= url('expenses') ?>"
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
                Simpan
            </button>

        </div>

    </div>

</form>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const wrapper = document.getElementById('expense-items-wrapper');
    const addBtn = document.getElementById('add-expense-item');
    const totalText = document.getElementById('grand-total-text');

    function formatRupiah(value) {
        return Number(value || 0).toLocaleString('id-ID');
    }

    function parseRupiah(value) {
        return String(value || '').replace(/\./g, '').replace(/,/g, '');
    }

    function rupiahText(value) {
        return 'Rp ' + formatRupiah(value);
    }

    function updateTotal() {
        let grandTotal = 0;

        document.querySelectorAll('.item-amount').forEach(input => {
            const cleanValue = parseRupiah(input.value);
            grandTotal += parseFloat(cleanValue || 0);
        });

        totalText.innerText = rupiahText(grandTotal);
    }

    function bindRow(row) {
        const amountInput = row.querySelector('.item-amount');

        amountInput.addEventListener('input', function () {
            const cleanValue = parseRupiah(this.value);

            this.value = formatRupiah(cleanValue);

            updateTotal();
        });

        row.querySelector('.remove-item').addEventListener('click', function () {
            if (document.querySelectorAll('.expense-item-row').length > 1) {
                row.remove();
                updateTotal();
            }
        });
    }

    document.querySelectorAll('.expense-item-row').forEach(bindRow);

    addBtn.addEventListener('click', function () {
        const firstRow = document.querySelector('.expense-item-row');
        const newRow = firstRow.cloneNode(true);

        newRow.querySelectorAll('input, textarea').forEach(input => {
            input.value = input.classList.contains('item-amount') ? '0' : '';
        });

        wrapper.appendChild(newRow);
        bindRow(newRow);
        updateTotal();
    });

    updateTotal();

});
</script>