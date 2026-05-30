
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
                Edit Purchase Request
            </h3>

            <p class="mb-0 text-body">
                Perbarui permintaan pembelian, item kebutuhan, estimasi biaya, dan status approval.
            </p>
        </div>

        <div class="d-flex justify-content-between justify-content-md-end align-items-center flex-wrap gap-3">

            <a
                href="<?= url('purchase-requests-show') ?>?id=<?= htmlspecialchars($item['id']) ?>"
                class="btn btn-light erp-btn"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Kembali
            </a>

        </div>

    </div>

</div>

<form method="POST" action="<?= url('purchase-requests-update') ?>">

    <input type="hidden" name="id" value="<?= htmlspecialchars($item['id']) ?>">

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Informasi Purchase Request
            </h4>
        </div>

        <div class="p-20">

            <div class="row g-4">

                <div class="col-md-3">
                    <label class="erp-detail-label">No. PR</label>
                    <input
                        type="text"
                        class="form-control"
                        value="<?= htmlspecialchars($item['pr_number'] ?? '-') ?>"
                        readonly
                    >
                </div>

                <div class="col-md-3">
                    <label class="erp-detail-label">Tanggal Request</label>
                    <input
                        type="date"
                        name="request_date"
                        class="form-control"
                        value="<?= htmlspecialchars($item['request_date'] ?? date('Y-m-d')) ?>"
                        required
                    >
                </div>

                <div class="col-md-3">
                    <label class="erp-detail-label">Tanggal Dibutuhkan</label>
                    <input
                        type="date"
                        name="needed_date"
                        class="form-control"
                        value="<?= htmlspecialchars($item['needed_date'] ?? '') ?>"
                    >
                </div>

                <div class="col-md-3">
                    <label class="erp-detail-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="draft" <?= ($item['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="waiting_approval" <?= ($item['status'] ?? '') === 'waiting_approval' ? 'selected' : '' ?>>Waiting Approval</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">Divisi</label>
                    <select name="department_id" class="form-control">
                        <option value="">-- Pilih Divisi --</option>

                        <?php foreach ($departments as $department): ?>
                            <option
                                value="<?= $department['id'] ?>"
                                <?= (int) ($item['department_id'] ?? 0) === (int) $department['id'] ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($department['name'] ?? '-') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-8">
                    <label class="erp-detail-label">Kebutuhan / Tujuan Pembelian</label>
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

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <h4 class="erp-detail-section-title mb-0">
                    Item Request
                </h4>

                <button type="button" id="add-item" class="btn btn-outline-primary erp-btn">
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
                                <label class="erp-detail-label">Estimasi Harga</label>
                                <input
                                    type="text"
                                    class="form-control estimated-price-display"
                                    value="<?= number_format((float) ($row['estimated_price'] ?? 0), 0, ',', '.') ?>"
                                >

                                <input
                                    type="hidden"
                                    name="estimated_price[]"
                                    class="estimated-price"
                                    value="<?= htmlspecialchars($row['estimated_price'] ?? 0) ?>"
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
                                <button type="button" class="btn btn-outline-danger erp-btn remove-item">
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
                    <div class="d-flex justify-content-between">
                        <span>Total Estimasi</span>
                        <strong id="summary-grand-total">Rp 0</strong>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white p-20">

        <div class="d-flex justify-content-end flex-wrap gap-3">

            <a
                href="<?= url('purchase-requests-show') ?>?id=<?= htmlspecialchars($item['id']) ?>"
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
        return String(value || '').replace(/\./g, '').replace(/,/g, '');
    }

    function rupiahText(value) {
        return 'Rp ' + formatRupiah(value);
    }

    function updateSummary() {
        let grandTotal = 0;

        document.querySelectorAll('.item-row').forEach(row => {
            const qty = parseFloat(row.querySelector('.qty')?.value || 0);
            const price = parseFloat(row.querySelector('.estimated-price')?.value || 0);
            const total = qty * price;

            grandTotal += total;
            row.querySelector('.subtotal').value = formatRupiah(total);
        });

        document.getElementById('summary-grand-total').innerText = rupiahText(grandTotal);
    }

    function bindRow(row) {
        row.querySelector('.qty').addEventListener('input', updateSummary);

        row.querySelector('.estimated-price-display').addEventListener('input', function () {
            const cleanValue = parseRupiah(this.value);

            row.querySelector('.estimated-price').value = cleanValue;
            this.value = formatRupiah(cleanValue);

            updateSummary();
        });

        row.querySelector('.remove-item').addEventListener('click', function () {
            if (document.querySelectorAll('.item-row').length <= 1) {
                return;
            }

            row.remove();
            updateSummary();
        });
    }

    document.querySelectorAll('.item-row').forEach(bindRow);

    addBtn.addEventListener('click', function () {
        const firstRow = document.querySelector('.item-row');
        const newRow = firstRow.cloneNode(true);

        newRow.querySelectorAll('input').forEach(input => {
            if (input.classList.contains('qty')) {
                input.value = 1;
            } else if (
                input.classList.contains('estimated-price-display') ||
                input.classList.contains('estimated-price') ||
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

    updateSummary();
});
</script>