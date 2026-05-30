
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
                Edit Purchase Order
            </h3>

            <p class="mb-0 text-body">
                Perbarui purchase order, vendor, item pembelian, pajak, catatan, dan total pembelian.
            </p>
        </div>

        <a
            href="<?= url('purchase-orders-show') ?>?id=<?= htmlspecialchars($item['id']) ?>"
            class="btn btn-light erp-btn"
        >
            <i class="ri-arrow-left-line me-1"></i>
            Kembali
        </a>

    </div>

</div>

<form method="POST" action="<?= url('purchase-orders-update') ?>">

    <input type="hidden" name="id" value="<?= htmlspecialchars($item['id']) ?>">

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Informasi Purchase Order
            </h4>
        </div>

        <div class="p-20">

            <div class="row g-4">

                <div class="col-md-3">
                    <label class="erp-detail-label">No. PO</label>
                    <input
                        type="text"
                        class="form-control"
                        value="<?= htmlspecialchars($item['po_number'] ?? '-') ?>"
                        readonly
                    >
                </div>

                <div class="col-md-3">
                    <label class="erp-detail-label">Tanggal PO</label>
                    <input
                        type="date"
                        name="po_date"
                        class="form-control"
                        value="<?= htmlspecialchars($item['po_date'] ?? date('Y-m-d')) ?>"
                        required
                    >
                </div>

                <div class="col-md-3">
                    <label class="erp-detail-label">Estimasi Datang</label>
                    <input
                        type="date"
                        name="expected_date"
                        class="form-control"
                        value="<?= htmlspecialchars($item['expected_date'] ?? '') ?>"
                        required
                    >
                </div>

                <div class="col-md-3">
                    <label class="erp-detail-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="draft" <?= ($item['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="approved" <?= ($item['status'] ?? '') === 'approved' ? 'selected' : '' ?>>Approved</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Vendor</label>
                    <select name="vendor_id" class="form-control" required>
                        <option value="">-- Pilih Vendor --</option>

                        <?php foreach ($vendors as $vendor): ?>
                            <option
                                value="<?= $vendor['id'] ?>"
                                <?= (int) ($item['vendor_id'] ?? 0) === (int) $vendor['id'] ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($vendor['vendor_name'] ?? '-') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>

        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                <h4 class="erp-detail-section-title mb-0">
                    Item Pembelian
                </h4>

                <button
                    type="button"
                    id="add-item"
                    class="btn btn-outline-primary erp-btn"
                >
                    <i class="ri-add-line me-1"></i>
                    Tambah Item
                </button>

            </div>

        </div>

        <div class="p-20">

            <div id="items-wrapper">

                <?php $editItems = !empty($items) ? $items : [[]]; ?>

                <?php foreach ($editItems as $row): ?>

                    <div class="item-row border rounded-10 p-3 mb-3">

                        <div class="row align-items-end g-3">

                            <div class="col-md-4">
                                <label class="erp-detail-label">Nama Item</label>
                                <input
                                    type="text"
                                    name="item_name[]"
                                    class="form-control item-name"
                                    value="<?= htmlspecialchars($row['item_name'] ?? '') ?>"
                                    required
                                >
                            </div>

                            <div class="col-md-2">
                                <label class="erp-detail-label">Qty</label>
                                <input
                                    type="number"
                                    name="qty[]"
                                    class="form-control qty"
                                    value="<?= htmlspecialchars($row['qty'] ?? 1) ?>"
                                    min="1"
                                    step="0.01"
                                >
                            </div>

                            <div class="col-md-2">
                                <label class="erp-detail-label">Unit</label>
                                <input
                                    type="text"
                                    name="unit_name[]"
                                    class="form-control"
                                    value="<?= htmlspecialchars($row['unit_name'] ?? 'unit') ?>"
                                >
                            </div>

                            <div class="col-md-2">
                                <label class="erp-detail-label">Harga</label>
                                <input
                                    type="text"
                                    class="form-control unit-price-display"
                                    value="<?= number_format((float) ($row['unit_price'] ?? 0), 0, ',', '.') ?>"
                                >

                                <input
                                    type="hidden"
                                    name="unit_price[]"
                                    class="unit-price"
                                    value="<?= (float) ($row['unit_price'] ?? 0) ?>"
                                >
                            </div>

                            <div class="col-md-1">
                                <label class="erp-detail-label">Total</label>
                                <input
                                    type="text"
                                    class="form-control subtotal"
                                    value="<?= number_format((float) ($row['subtotal'] ?? 0), 0, ',', '.') ?>"
                                    readonly
                                >
                            </div>

                            <div class="col-md-1 text-end">
                                <label class="erp-detail-label d-block">&nbsp;</label>

                                <button
                                    type="button"
                                    class="btn btn-outline-danger erp-btn remove-item"
                                >
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>

                            <div class="col-md-12">
                                <label class="erp-detail-label">Deskripsi</label>
                                <textarea
                                    name="description[]"
                                    class="form-control"
                                    rows="2"
                                ><?= htmlspecialchars($row['description'] ?? '') ?></textarea>
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
                        Catatan
                    </h4>
                </div>

                <div class="p-20">

                    <textarea
                        name="notes"
                        class="form-control"
                        rows="8"
                    ><?= htmlspecialchars($item['notes'] ?? '') ?></textarea>

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
                        <strong id="summary-subtotal">Rp 0</strong>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Pajak</span>

                        <div style="width: 150px;">
                            <input
                                type="number"
                                name="tax_amount"
                                id="tax_amount"
                                class="form-control"
                                value="<?= htmlspecialchars($item['tax_amount'] ?? 0) ?>"
                                min="0"
                            >
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <span>Grand Total</span>
                        <strong id="summary-grand-total">Rp 0</strong>
                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white p-20">

        <div class="d-flex justify-content-end flex-wrap gap-3">

            <a
                href="<?= url('purchase-orders-show') ?>?id=<?= htmlspecialchars($item['id']) ?>"
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
    const wrapper = document.getElementById('items-wrapper');
    const addBtn = document.getElementById('add-item');

    function formatRupiah(value) {
        return Number(value || 0).toLocaleString('id-ID');
    }

    function parseRupiah(value) {

        return Number(
            String(value || '').replace(/[^\d]/g, '')
        );

    }

    function rupiahText(value) {
        return 'Rp ' + formatRupiah(value);
    }

    function updateSummary() {
        let subtotal = 0;

        document.querySelectorAll('.item-row').forEach(row => {
            const qty = parseFloat(row.querySelector('.qty')?.value || 0);
            const price = parseFloat(row.querySelector('.unit-price')?.value || 0);
            const total = qty * price;

            subtotal += total;
            row.querySelector('.subtotal').value = formatRupiah(total);
        });

        const tax = parseFloat(document.getElementById('tax_amount')?.value || 0);
        const grandTotal = subtotal + tax;

        document.getElementById('summary-subtotal').innerText = rupiahText(subtotal);
        document.getElementById('summary-grand-total').innerText = rupiahText(grandTotal);
    }

    function bindRow(row) {
        row.querySelector('.qty').addEventListener('input', updateSummary);

        row.querySelector('.unit-price-display').addEventListener('input', function () {
            const cleanValue = parseRupiah(this.value);

            row.querySelector('.unit-price').value = cleanValue;
            this.value = formatRupiah(cleanValue);

            updateSummary();
        });

        row.querySelector('.remove-item').addEventListener('click', function () {
            if (document.querySelectorAll('.item-row').length <= 1) return;

            row.remove();
            updateSummary();
        });
    }

      document.querySelectorAll('.item-row').forEach(row => {

        const displayPriceInput = row.querySelector('.unit-price-display');
        const hiddenPriceInput = row.querySelector('.unit-price');

        hiddenPriceInput.value = parseFloat(hiddenPriceInput.value || 0);

        displayPriceInput.value = formatRupiah(hiddenPriceInput.value);

        bindRow(row);

    });

    addBtn.addEventListener('click', function () {
        const firstRow = document.querySelector('.item-row');
        const newRow = firstRow.cloneNode(true);

        newRow.querySelectorAll('input').forEach(input => {
            if (input.classList.contains('qty')) {
                input.value = 1;
            } else if (
                input.classList.contains('unit-price') ||
                input.classList.contains('unit-price-display') ||
                input.classList.contains('subtotal')
            ) {
                input.value = '0';
            } else {
                input.value = '';
            }
        });

        newRow.querySelectorAll('textarea').forEach(textarea => {
            textarea.value = '';
        });

        wrapper.appendChild(newRow);
        bindRow(newRow);
        updateSummary();
    });

    document.getElementById('tax_amount').addEventListener('input', updateSummary);

    updateSummary();
});
</script>