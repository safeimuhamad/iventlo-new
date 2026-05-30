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
                Tambah Hutang Vendor
            </h3>

            <p class="mb-0 text-body">
                Catat tagihan vendor untuk pembelian barang, jasa, aset, maupun biaya operasional perusahaan.
            </p>
        </div>

        <a
            href="<?= url('vendor-bills') ?>"
            class="btn btn-light erp-btn"
        >
            <i class="ri-arrow-left-line me-1"></i>
            Kembali
        </a>

    </div>

</div>

<form method="POST" action="<?= url('vendor-bills-store') ?>">

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Informasi Hutang Vendor
            </h4>
        </div>

        <div class="p-20">

            <div class="row g-4">

                <div class="col-md-3">
                    <label class="erp-detail-label">No Bill</label>
                    <input
                        type="text"
                        name="bill_no"
                        class="form-control"
                        value="<?= htmlspecialchars($billNo ?? '') ?>"
                        readonly
                    >
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">
                        Vendor <span class="text-danger">*</span>
                    </label>

                    <select
                        name="vendor_id"
                        class="form-control"
                        required
                    >
                        <option value="">
                            -- Pilih Vendor --
                        </option>

                        <?php foreach ($vendors as $vendor): ?>
                            <option value="<?= $vendor['id'] ?>">
                                <?= htmlspecialchars($vendor['vendor_code'] ?? '-') ?>
                                -
                                <?= htmlspecialchars($vendor['vendor_name'] ?? '-') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="erp-detail-label">Tanggal Bill</label>
                    <input
                        type="date"
                        name="bill_date"
                        class="form-control"
                        value="<?= date('Y-m-d') ?>"
                        required
                    >
                </div>

                <div class="col-md-2">
                    <label class="erp-detail-label">Jatuh Tempo</label>
                    <input
                        type="date"
                        name="due_date"
                        class="form-control"
                    >
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">PPN Masukan</label>
                    <input
                        type="text"
                        name="tax_amount"
                        id="tax_amount"
                        class="form-control text-end"
                        value="0"
                    >
                </div>

            </div>

        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                <h4 class="erp-detail-section-title mb-0">
                    Detail Tagihan
                </h4>

                <button
                    type="button"
                    id="add-item"
                    class="btn btn-outline-primary erp-btn"
                >
                    <i class="ri-add-line me-1"></i>
                    Tambah Baris
                </button>

            </div>

        </div>

        <div class="p-20">

            <div id="bill-items-wrapper">

                <div class="bill-item-row border rounded-10 p-3 mb-3">

                    <div class="row align-items-end g-3">

                        <div class="col-md-4">

                            <label class="erp-detail-label">
                                Akun Biaya / Asset
                            </label>

                            <select
                                name="account_id[]"
                                class="form-control"
                                required
                            >
                                <option value="">
                                    -- Pilih Akun --
                                </option>

                                <?php foreach ($accounts as $account): ?>

                                    <?php if (in_array($account['account_type'], ['expense', 'cost', 'asset'])): ?>

                                        <option value="<?= $account['id'] ?>">
                                            <?= htmlspecialchars($account['account_code']) ?>
                                            -
                                            <?= htmlspecialchars($account['account_name']) ?>
                                        </option>

                                    <?php endif; ?>

                                <?php endforeach; ?>

                            </select>

                        </div>

                        <div class="col-md-5">

                            <label class="erp-detail-label">
                                Deskripsi
                            </label>

                            <input
                                type="text"
                                name="description[]"
                                class="form-control"
                                placeholder="Keterangan tagihan"
                            >

                        </div>

                        <div class="col-md-2">

                            <label class="erp-detail-label">
                                Nominal
                            </label>

                            <input
                                type="text"
                                name="amount[]"
                                class="form-control item-amount text-end"
                                value="0"
                                required
                            >

                        </div>

                        <div class="col-md-1 text-end">

                            <label class="erp-detail-label d-block">
                                &nbsp;
                            </label>

                            <button
                                type="button"
                                class="btn btn-outline-danger erp-btn remove-item"
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
                        Catatan
                    </h4>
                </div>

                <div class="p-20">

                    <textarea
                        name="notes"
                        class="form-control"
                        rows="8"
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
                        <span>Subtotal</span>
                        <strong id="subtotal-text">
                            Rp 0
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>PPN Masukan</span>
                        <strong id="tax-text">
                            Rp 0
                        </strong>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">

                        <span>Grand Total</span>

                        <strong id="grand-total-text">
                            Rp 0
                        </strong>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white p-20">

        <div class="d-flex justify-content-end flex-wrap gap-3">

            <a
                href="<?= url('vendor-bills') ?>"
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
    const wrapper = document.getElementById('bill-items-wrapper');
    const addBtn = document.getElementById('add-item');
    const totalText = document.getElementById('grand-total-text');
    const taxInput = document.getElementById('tax_amount');

    function formatRupiah(value) {
        return Number(value || 0).toLocaleString('id-ID');
    }

    function parseRupiah(value) {
        return String(value || '').replace(/\./g, '').replace(/,/g, '');
    }

    function updateTotal() {
        let subtotal = 0;

        document.querySelectorAll('.item-amount').forEach(input => {
            subtotal += parseFloat(parseRupiah(input.value) || 0);
        });

        const tax = parseFloat(parseRupiah(taxInput.value) || 0);
        totalText.innerText = 'Rp ' + formatRupiah(subtotal + tax);
    }

    function bindRow(row) {
        row.querySelector('.item-amount').addEventListener('input', function () {
            const cleanValue = parseRupiah(this.value);
            this.value = formatRupiah(cleanValue);
            updateTotal();
        });

        row.querySelector('.remove-item').addEventListener('click', function () {
            if (document.querySelectorAll('.bill-item-row').length > 1) {
                row.remove();
                updateTotal();
            }
        });
    }

    document.querySelectorAll('.bill-item-row').forEach(bindRow);

    taxInput.addEventListener('input', function () {
        const cleanValue = parseRupiah(this.value);
        this.value = formatRupiah(cleanValue);
        updateTotal();
    });

    addBtn.addEventListener('click', function () {
        const firstRow = document.querySelector('.bill-item-row');
        const newRow = firstRow.cloneNode(true);

        newRow.querySelectorAll('input').forEach(input => {
            input.value = input.classList.contains('item-amount') ? '0' : '';
        });

        newRow.querySelectorAll('select').forEach(select => {
            select.selectedIndex = 0;
        });

        wrapper.appendChild(newRow);
        bindRow(newRow);
        updateTotal();
    });

    updateTotal();
});
</script>