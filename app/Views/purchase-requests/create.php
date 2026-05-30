<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger mb-4">
        <?= $_SESSION['error']; ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>
<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h3 class="mb-1">Tambah Purchase Request</h3>
            <p class="mb-0 text-body">
                Buat permintaan pembelian barang atau kebutuhan operasional sebelum proses approval dan pembelian.
            </p>
        </div>

        <a href="<?= url('purchase-requests') ?>" class="btn btn-light erp-btn">
            <i class="ri-arrow-left-line me-1"></i>
            Kembali
        </a>
    </div>

</div>

<form method="POST" action="<?= url('purchase-requests-store') ?>">

    <?php if (!empty($purchaseRequest)): ?>
        <input type="hidden" name="purchase_request_id" value="<?= htmlspecialchars($purchaseRequest['id']) ?>">
    <?php endif; ?>

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
                        name="pr_number"
                        class="form-control"
                        value="<?= htmlspecialchars($prNumber ?? '') ?>"
                        readonly
                    >
                </div>

                <div class="col-md-3">
                    <label class="erp-detail-label">Tanggal Request</label>
                    <input
                        type="date"
                        name="request_date"
                        class="form-control"
                        value="<?= date('Y-m-d') ?>"
                        required
                    >
                </div>

                <div class="col-md-3">
                    <label class="erp-detail-label">Tanggal Dibutuhkan</label>
                    <input
                        type="date"
                        name="needed_date"
                        class="form-control"
                    >
                </div>

                <div class="col-md-3">
                    <label class="erp-detail-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="draft">Draft</option>
                        <option value="waiting_approval">Waiting Approval</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">Divisi</label>
                    <select name="department_id" class="form-control">
                        <option value="">-- Pilih Divisi --</option>

                        <?php foreach ($departments as $department): ?>
                            <option value="<?= $department['id'] ?>">
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
                        placeholder="Contoh: Pembelian sparepart AC untuk maintenance"
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

                <?php
                $defaultItems = !empty($purchaseRequestItems) ? $purchaseRequestItems : [[]];
                ?>

                <?php foreach ($defaultItems as $row): ?>

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
                    ><?= !empty($purchaseRequest) ? htmlspecialchars('Dibuat dari PR: ' . ($purchaseRequest['pr_number'] ?? '-')) : '' ?></textarea>
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

                        <strong id="summary-grand-total">
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
                href="<?= url('purchase-requests') ?>"
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
    const wrapper = document.getElementById('items-wrapper');
    const addBtn = document.getElementById('add-item');

    function formatRupiah(value) {
        return Number(value || 0).toLocaleString('id-ID');
    }

    function parseRupiah(value) {
        return String(value || '')
            .replace(/\./g, '')
            .replace(/,/g, '')
            .replace(/[^\d]/g, '');
    }

    function updateSummary() {
        let grandTotal = 0;

        document.querySelectorAll('.item-row').forEach(row => {
            const qty = parseFloat(row.querySelector('.qty')?.value || 0);
            const price = parseFloat(row.querySelector('.estimated-price')?.value || 0);
            const subtotal = qty * price;

            grandTotal += subtotal;

            row.querySelector('.subtotal').value = formatRupiah(subtotal);
        });

        document.getElementById('summary-grand-total').innerText =
            'Rp ' + formatRupiah(grandTotal);
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
            if (document.querySelectorAll('.item-row').length <= 1) return;

            row.remove();
            updateSummary();
        });

        updateSummary();
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