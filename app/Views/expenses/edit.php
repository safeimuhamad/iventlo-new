<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger mb-4">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>
<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Edit Pengeluaran
            </h3>

            <p class="mb-0 text-body">
                Perbarui data pengeluaran operasional perusahaan dan detail biaya yang terkait dengan transaksi ini.
            </p>
        </div>

        <div class="text-end">

            <div class="fs-20 fw-bold">
                Total
                <span id="grand-total-text">
                    Rp <?= number_format((float)($expense['amount'] ?? 0), 0, ',', '.') ?>
                </span>
            </div>

        </div>

    </div>

</div>

<form method="POST" action="<?= url('expenses-update') ?>">

    <input
        type="hidden"
        name="id"
        value="<?= htmlspecialchars($expense['id']) ?>"
    >

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
                        Bayar Dari
                    </label>

                    <select name="bank_account_id" class="form-control" required>

                        <option value="">
                            -- Pilih Rekening --
                        </option>

                        <?php foreach ($bankAccounts as $account): ?>
                            <option
                                value="<?= $account['id'] ?>"
                                <?= ($expense['bank_account_id'] ?? '') == $account['id'] ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($account['account_code'] ?? '-') ?>
                                -
                                <?= htmlspecialchars($account['account_name'] ?? '-') ?>
                            </option>
                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="col-md-3">

                    <label class="erp-detail-label">
                        Tanggal Transaksi
                    </label>

                    <input
                        type="date"
                        name="expense_date"
                        class="form-control"
                        value="<?= htmlspecialchars($expense['expense_date'] ?? date('Y-m-d')) ?>"
                        required
                    >

                </div>

                <div class="col-md-3">

                    <label class="erp-detail-label">
                        Metode Pembayaran
                    </label>

                    <select name="payment_method" class="form-control">

                        <option value="bank_transfer" <?= ($expense['payment_method'] ?? '') === 'bank_transfer' ? 'selected' : '' ?>>
                            Bank Transfer
                        </option>

                        <option value="cash" <?= ($expense['payment_method'] ?? '') === 'cash' ? 'selected' : '' ?>>
                            Cash
                        </option>

                        <option value="qris" <?= ($expense['payment_method'] ?? '') === 'qris' ? 'selected' : '' ?>>
                            QRIS
                        </option>

                        <option value="debit" <?= ($expense['payment_method'] ?? '') === 'debit' ? 'selected' : '' ?>>
                            Debit
                        </option>

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
                        value="<?= htmlspecialchars($expense['reference_no'] ?? '') ?>"
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
                        value="<?= htmlspecialchars($expense['beneficiary'] ?? '') ?>"
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
                    ><?= htmlspecialchars($expense['billing_address'] ?? '') ?></textarea>

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

                <?php $editItems = !empty($items) ? $items : [[]]; ?>

                <?php foreach ($editItems as $item): ?>

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

                                            <option
                                                value="<?= $coa['id'] ?>"
                                                <?= (($item['account_id'] ?? '') == $coa['id']) ? 'selected' : '' ?>
                                            >
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
                                ><?= htmlspecialchars($item['description'] ?? '') ?></textarea>

                            </div>

                            <div class="col-md-2">

                                <label class="erp-detail-label">
                                    Nominal
                                </label>

                                <input
                                    type="text"
                                    name="item_amount[]"
                                    class="form-control item-amount text-end"
                                    value="<?= number_format((float)($item['amount'] ?? 0), 0, ',', '.') ?>"
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

                <?php endforeach; ?>

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
                    ><?= htmlspecialchars($expense['description'] ?? '') ?></textarea>

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
                        <strong>
                            <?= !empty($expense['expense_date']) ? date('d M Y', strtotime($expense['expense_date'])) : '-' ?>
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Metode</span>
                        <strong>
                            <?= ucfirst(str_replace('_', ' ', $expense['payment_method'] ?? '-')) ?>
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Total</span>
                        <strong id="summary-total">
                            Rp <?= number_format((float)($expense['amount'] ?? 0), 0, ',', '.') ?>
                        </strong>
                    </div>

                    <hr>

                    <div class="text-body">
                        Perubahan data pengeluaran akan memperbarui jurnal dan saldo rekening yang terkait.
                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white p-20">

        <div class="d-flex justify-content-end flex-wrap gap-3">

            <a
                href="<?= url('expenses-show') ?>?id=<?= $expense['id'] ?>"
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