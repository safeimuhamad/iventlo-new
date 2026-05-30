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
                Edit Invoice
            </h3>

            <p class="mb-0 text-body">
                Perbarui data invoice, customer, produk atau jasa, pajak, DP, dan ringkasan pembayaran.
            </p>
        </div>

        <div class="d-flex justify-content-between justify-content-md-end align-items-center flex-wrap gap-3">
            <a
                href="<?= url('invoices-show') ?>?id=<?= htmlspecialchars($invoice['id']) ?>"
                class="btn btn-light erp-btn"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Kembali
            </a>
        </div>
    </div>

</div>

<form method="POST" action="<?= url('invoices-update') ?>">

    <input type="hidden" name="id" value="<?= htmlspecialchars($invoice['id']) ?>">

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Informasi Invoice
            </h4>
        </div>

        <div class="p-20">

            <div class="row g-4">

                <div class="col-md-2">
                    <label class="erp-detail-label">No Invoice</label>
                    <input
                        type="text"
                        name="no_invoice"
                        class="form-control"
                        value="<?= htmlspecialchars($invoice['no_invoice'] ?? '-') ?>"
                        readonly
                    >
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">Customer</label>
                    <select name="customer_id" class="form-select">
                        <option value="">Pilih Customer</option>

                        <?php foreach (($customers ?? []) as $customer): ?>
                            <option
                                value="<?= $customer['id'] ?>"
                                <?= (($invoice['customer_id'] ?? '') == $customer['id']) ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($customer['company_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="erp-detail-label">Nama Customer</label>
                    <input
                        type="text"
                        name="customer_name"
                        id="customer_name"
                        class="form-control"
                        value="<?= htmlspecialchars($invoice['customer_name'] ?? '') ?>"
                        required
                    >
                </div>

                <div class="col-md-3">
                    <label class="erp-detail-label">No. HP</label>
                    <input
                        type="text"
                        name="customer_phone"
                        id="customer_phone"
                        class="form-control"
                        value="<?= htmlspecialchars($invoice['customer_phone'] ?? '') ?>"
                    >
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">Lokasi</label>
                    <textarea
                        name="lokasi"
                        id="lokasi"
                        class="form-control"
                        rows="2"
                    ><?= htmlspecialchars($invoice['lokasi'] ?? '') ?></textarea>
                </div>

                <div class="col-md-2">
                    <label class="erp-detail-label">Tipe Invoice</label>
                    <select name="invoice_type" class="form-control">
                        <option value="dp" <?= ($invoice['invoice_type'] ?? '') === 'dp' ? 'selected' : '' ?>>DP</option>
                        <option value="full" <?= ($invoice['invoice_type'] ?? '') === 'full' ? 'selected' : '' ?>>Full Payment</option>
                        <option value="final" <?= ($invoice['invoice_type'] ?? '') === 'final' ? 'selected' : '' ?>>Pelunasan</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="erp-detail-label">PPN</label>
                    <select name="tax_type" id="tax_type" class="form-control">
                        <option value="include_ppn" <?= ($invoice['tax_type'] ?? '') === 'include_ppn' ? 'selected' : '' ?>>Include PPN</option>
                        <option value="non_ppn" <?= ($invoice['tax_type'] ?? 'non_ppn') === 'non_ppn' ? 'selected' : '' ?>>Non PPN</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="erp-detail-label">Tanggal Invoice</label>
                    <input
                        type="date"
                        name="invoice_date"
                        class="form-control"
                        value="<?= htmlspecialchars($invoice['invoice_date'] ?? date('Y-m-d')) ?>"
                    >
                </div>

                <div class="col-md-2">
                    <label class="erp-detail-label">Jatuh Tempo</label>
                    <input
                        type="date"
                        name="due_date"
                        class="form-control"
                        value="<?= htmlspecialchars($invoice['due_date'] ?? '') ?>"
                    >
                </div>

            </div>

        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <h4 class="erp-detail-section-title mb-0">
                    Produk / Jasa
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

                <?php foreach ($editItems as $item): ?>
                    <div class="item-row border rounded-10 p-3 mb-3">

                        <div class="row align-items-end g-3">

                            <div class="col-md-3">
                                <label class="erp-detail-label">Produk / Jasa</label>

                                <select name="product_id[]" class="form-control product-select" required>
                                    <option value="">-- Pilih Produk / Jasa --</option>

                                    <?php foreach ($products as $product): ?>
                                        <option
                                            value="<?= $product['id'] ?>"
                                            data-name="<?= htmlspecialchars($product['name'] ?? '') ?>"
                                            data-product-id="<?= $product['id'] ?>"
                                            data-category="<?= htmlspecialchars($product['category'] ?? '') ?>"
                                            data-item-type="<?= htmlspecialchars($product['item_type'] ?? 'rental_unit') ?>"
                                            data-period="<?= htmlspecialchars($product['default_period_type'] ?? 'daily') ?>"
                                            data-daily="<?= htmlspecialchars($product['daily_price'] ?? 0) ?>"
                                            data-weekly="<?= htmlspecialchars($product['weekly_price'] ?? 0) ?>"
                                            data-monthly="<?= htmlspecialchars($product['monthly_price'] ?? 0) ?>"
                                            data-unit-price="<?= htmlspecialchars($product['unit_price'] ?? 0) ?>"
                                            data-meter-price="<?= htmlspecialchars($product['meter_price'] ?? 0) ?>"
                                            data-package-price="<?= htmlspecialchars($product['package_price'] ?? 0) ?>"
                                            <?= ($item['item_name'] ?? '') === ($product['name'] ?? '') ? 'selected' : '' ?>
                                        >
                                            <?= htmlspecialchars($product['name'] ?? '-') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                
                                <input type="hidden" name="item_name[]" class="item-name" value="<?= htmlspecialchars($item['item_name'] ?? '') ?>">
                                <input type="hidden" name="item_type[]" class="item-type" value="<?= htmlspecialchars($item['item_type'] ?? 'rental_unit') ?>">
                                <input type="hidden" name="category[]" class="category" value="<?= htmlspecialchars($item['category'] ?? '') ?>">
                            </div>

                            <div class="col-md-1">
                                <label class="erp-detail-label">Qty</label>
                                <input type="number" name="qty[]" class="form-control qty" value="<?= htmlspecialchars($item['qty'] ?? 1) ?>" min="1">
                            </div>

                            <div class="col-md-2">
                                <label class="erp-detail-label">Billing</label>
                                <select
                                    name="billing_type[]"
                                    class="form-control billing-type"
                                    data-current="<?= htmlspecialchars($item['billing_type'] ?? $item['rental_period_type'] ?? 'daily') ?>"
                                ></select>
                            </div>

                            <div class="col-md-1">
                                <label class="erp-detail-label">Durasi</label>
                                <input type="number" name="duration[]" class="form-control duration" value="<?= htmlspecialchars($item['duration'] ?? 1) ?>" min="1">
                            </div>

                            <div class="col-md-2">
                                <label class="erp-detail-label">Harga</label>
                                <input type="text" class="form-control unit-price-display" value="<?= number_format((float)($item['unit_price'] ?? 0), 0, ',', '.') ?>">
                                <input type="hidden" name="unit_price[]" class="unit-price" value="<?= htmlspecialchars($item['unit_price'] ?? 0) ?>">
                            </div>

                            <div class="col-md-1">
                                <label class="erp-detail-label">Diskon</label>
                                <input type="text" class="form-control discount-display" value="<?= number_format((float)($item['discount'] ?? 0), 0, ',', '.') ?>">
                                <input type="hidden" name="discount[]" class="discount" value="<?= htmlspecialchars($item['discount'] ?? 0) ?>">
                            </div>

                            <div class="col-md-1">
                                <label class="erp-detail-label">Total</label>
                                <input type="text" class="form-control subtotal" value="<?= number_format((float)($item['subtotal'] ?? 0), 0, ',', '.') ?>" readonly>
                            </div>

                            <div class="col-md-1 text-end">
                                <label class="erp-detail-label d-block">&nbsp;</label>
                                <button type="button" class="btn btn-outline-danger erp-btn remove-item" title="Hapus Item">
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

        <div class="col-md-8">
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
                    ><?= htmlspecialchars($invoice['notes'] ?? '') ?></textarea>
                </div>

            </div>
        </div>

        <div class="col-md-4">
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

                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Diskon</span>
                        <strong id="summary-discount">Rp 0</strong>
                    </div>

                    <div
                        class="d-flex justify-content-between mb-2"
                        id="summary-tax-row"
                        style="display:none;"
                    >
                        <span>PPN 11%</span>
                        <strong id="summary-tax">Rp 0</strong>
                    </div>

                    <hr>

                    <div id="dp-summary-wrapper" style="display:none;">

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>DP</span>

                            <div class="d-flex align-items-center gap-1" style="width:120px;">
                                <span>%</span>
                                <input
                                    type="number"
                                    id="dp_percentage"
                                    name="dp_percentage"
                                    class="form-control text-end"
                                    value="<?= htmlspecialchars($invoice['dp_percentage'] ?? 50) ?>"
                                    min="0"
                                    max="100"
                                    step="0.01"
                                >
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Nominal DP</span>

                            <input
                                type="text"
                                id="dp_nominal_display"
                                class="form-control text-end"
                                style="width:160px;"
                                value="<?= number_format((float)($invoice['dp_nominal'] ?? 0), 0, ',', '.') ?>"
                            >

                            <input
                                type="hidden"
                                name="dp_nominal"
                                id="dp_nominal"
                                value="<?= htmlspecialchars($invoice['dp_nominal'] ?? 0) ?>"
                            >
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span>Sisa Tagihan</span>
                            <strong id="summary-remaining-bill">Rp 0</strong>
                        </div>

                    </div>

                    <div class="d-flex justify-content-between mt-3 pt-3 border-top">
                        <span class="fw-semibold">Total Pembayaran</span>
                        <strong id="summary-grand-total">Rp 0</strong>
                    </div>

                </div>

            </div>
        </div>

    </div>
    <input type="hidden" name="dp_type" id="dp_type" value="<?= htmlspecialchars($invoice['dp_type'] ?? 'percentage') ?>">
    <input type="hidden" name="paid_amount" value="<?= htmlspecialchars($invoice['paid_amount'] ?? 0) ?>">
    <input type="hidden" name="status_payment" value="<?= htmlspecialchars($invoice['status_payment'] ?? 'waiting payment') ?>">
    <input type="hidden" name="subtotal" id="subtotal_input" value="0">
    <input type="hidden" name="total_discount" id="discount_input" value="0">
    <input type="hidden" name="tax_amount" id="tax_amount_input" value="0">
    <input type="hidden" name="grand_total" id="grand_total_input" value="0">
    <input type="hidden" name="remaining_bill" id="remaining_bill_input" value="<?= htmlspecialchars($invoice['remaining_bill'] ?? 0) ?>">

    <div class="card bg-white rounded-10 border border-white p-20">

        <div class="d-flex justify-content-end flex-wrap gap-3">

            <a
                href="<?= url('invoices-show') ?>?id=<?= htmlspecialchars($invoice['id']) ?>"
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
        const customerSelect = document.getElementById('customer_id');
        const customerName = document.getElementById('customer_name');
        const customerPhone = document.getElementById('customer_phone');
        const lokasi = document.getElementById('lokasi');

        if (customerSelect) {
            customerSelect.addEventListener('change', function () {
                const option = this.options[this.selectedIndex];

                customerName.value = option?.dataset.name || '';
                customerPhone.value = option?.dataset.phone || '';
                lokasi.value = option?.dataset.address || '';
            });
        }

        const wrapper = document.getElementById('items-wrapper');
        const addBtn = document.getElementById('add-item');
        const taxTypeSelect = document.getElementById('tax_type');
        if (taxTypeSelect) {
            taxTypeSelect.addEventListener('change', function () {
                updateSummary();
            });
        }
        const invoiceTypeSelect = document.querySelector('[name="invoice_type"]');

        if (invoiceTypeSelect) {
            invoiceTypeSelect.addEventListener('change', updateSummary);
        }

        function formatRupiah(value) {
            return Number(value || 0).toLocaleString('id-ID');
        }

        function rupiahText(value) {
            return 'Rp ' + formatRupiah(value);
        }

        function parseRupiah(value) {
            return String(value || '').replace(/\./g, '').replace(/,/g, '');
        }


        function updateBillingOptions(row) {
            const productSelect = row.querySelector('.product-select');
            const billingSelect = row.querySelector('.billing-type');
            const option = productSelect.options[productSelect.selectedIndex];

            const itemType =
            option?.dataset.itemType ||
            row.querySelector('.item-type')?.value ||
            'rental_unit';

            const current = billingSelect.dataset.current || 'daily';

            let html = '';

            if (itemType === 'rental_unit') {
                html += `<option value="daily">Harian</option>`;
                html += `<option value="weekly">Mingguan</option>`;
                html += `<option value="monthly">Bulanan</option>`;
            } else if (itemType === 'material') {
                html += `<option value="meter">Per Meter</option>`;
                html += `<option value="unit">Per Unit</option>`;
            } else {
                html += `<option value="unit">Per Unit</option>`;
                html += `<option value="package">Paket</option>`;
                html += `<option value="fixed">Fixed</option>`;
            }

            billingSelect.innerHTML = html;

            if ([...billingSelect.options].some(option => option.value === current)) {
                billingSelect.value = current;
            } else {
                billingSelect.selectedIndex = 0;
            }
        }

        function getRowSubtotalBeforeDiscount(row) {
            const qty = parseFloat(row.querySelector('.qty')?.value || 0);
            const duration = parseFloat(row.querySelector('.duration')?.value || 1);
            const price = parseFloat(row.querySelector('.unit-price')?.value || 0);
            const billing = row.querySelector('.billing-type')?.value || 'unit';

            if (['daily', 'weekly', 'monthly'].includes(billing)) {
                return qty * duration * price;
            }

            if (['package', 'fixed'].includes(billing)) {
                return price;
            }

            return qty * price;
        }

        function updateSummary() {
            let subtotalBeforeDiscount = 0;
            let totalDiscount = 0;
            let grandTotalBeforeTax = 0;

            document.querySelectorAll('.item-row').forEach(row => {
                const discount = parseFloat(row.querySelector('.discount')?.value || 0);
                const rowSubtotalBeforeDiscount = getRowSubtotalBeforeDiscount(row);
                const rowTotal = Math.max(0, rowSubtotalBeforeDiscount - discount);

                subtotalBeforeDiscount += rowSubtotalBeforeDiscount;
                totalDiscount += discount;
                grandTotalBeforeTax += rowTotal;

                row.querySelector('.subtotal').value = formatRupiah(rowTotal);
            });

            const taxSelect = document.getElementById('tax_type');
            const taxType = taxSelect ? taxSelect.value : 'non_ppn';

            const taxAmount = taxType === 'include_ppn'
            ? grandTotalBeforeTax * 0.11
            : 0;

            const taxRow = document.getElementById('summary-tax-row');
            const taxText = document.getElementById('summary-tax');

            if (taxRow && taxText) {
                if (taxType === 'include_ppn') {
                    taxRow.style.setProperty('display', 'flex', 'important');
                    taxText.innerText = rupiahText(taxAmount);
                } else {
                    taxRow.style.setProperty('display', 'none', 'important');
                    taxText.innerText = rupiahText(0);
                }
            }

            const finalGrandTotal = grandTotalBeforeTax + taxAmount;

            document.getElementById('summary-subtotal').innerText = rupiahText(subtotalBeforeDiscount);
            document.getElementById('summary-discount').innerText = rupiahText(totalDiscount);

            const invoiceTypeSelect = document.querySelector('[name="invoice_type"]');
            const invoiceType = invoiceTypeSelect ? invoiceTypeSelect.value : 'full';

            let displayGrandTotal = finalGrandTotal;
            let remainingBill = 0;

            const dpWrapper = document.getElementById('dp-summary-wrapper');

            if (invoiceType === 'dp') {
                const dpPercentageInput = document.getElementById('dp_percentage');
                const dpNominalInput = document.getElementById('dp_nominal');
                const dpNominalDisplay = document.getElementById('dp_nominal_display');

                let dpPercentage = parseFloat(dpPercentageInput?.value || 50);

                if (dpPercentage <= 0) {
                    dpPercentage = 50;
                    dpPercentageInput.value = 50;
                }

                let invoiceDpAmount = parseFloat(dpNominalInput?.value || 0);

                if (dpEditMode === 'nominal') {
                    invoiceDpAmount = Math.min(finalGrandTotal, invoiceDpAmount);

                    if (invoiceDpAmount <= 0) {
                        invoiceDpAmount = finalGrandTotal * 0.5;
                        dpNominalInput.value = invoiceDpAmount;
                        dpNominalDisplay.value = formatRupiah(invoiceDpAmount);
                    }

                    dpPercentage = finalGrandTotal > 0
                    ? (invoiceDpAmount / finalGrandTotal) * 100
                    : 0;

                    dpPercentageInput.value = dpPercentage.toFixed(2);
                } else {
                    invoiceDpAmount = finalGrandTotal * (dpPercentage / 100);
                    dpNominalInput.value = invoiceDpAmount;
                    dpNominalDisplay.value = formatRupiah(invoiceDpAmount);
                }

                remainingBill = Math.max(0, finalGrandTotal - invoiceDpAmount);
                displayGrandTotal = invoiceDpAmount;

                dpWrapper.style.display = 'block';
                document.getElementById('summary-remaining-bill').innerText = rupiahText(remainingBill);
                } else {
                    dpWrapper.style.display = 'none';

                    document.getElementById('dp_percentage').value = 0;
                    document.getElementById('dp_nominal').value = 0;
                    document.getElementById('dp_nominal_display').value = '0';
                    document.getElementById('remaining_bill_input').value = 0;

                    displayGrandTotal = finalGrandTotal;
                    remainingBill = 0;
                }

            document.getElementById('summary-grand-total').innerText = rupiahText(displayGrandTotal);

            document.getElementById('subtotal_input').value = subtotalBeforeDiscount;
            document.getElementById('discount_input').value = totalDiscount;
            document.getElementById('tax_amount_input').value = taxAmount;
            document.getElementById('grand_total_input').value = displayGrandTotal;
            document.getElementById('remaining_bill_input').value = remainingBill;
        }

        function calculateSubtotal(row) {
            updateSummary();
        }

        function setPriceByPeriod(row, forceUpdatePrice = true) {
            const productSelect = row.querySelector('.product-select');
            const billingSelect = row.querySelector('.billing-type');
            const priceInput = row.querySelector('.unit-price');
            const displayInput = row.querySelector('.unit-price-display');

            const option = productSelect.options[productSelect.selectedIndex];

            if (!option || !option.value) {
                if (forceUpdatePrice) {
                    priceInput.value = 0;
                    displayInput.value = '0';
                }

                calculateSubtotal(row);
                return;
            }

            row.querySelector('.item-name').value = option.dataset.name || '';
            row.querySelector('.item-type').value = option.dataset.itemType || 'rental_unit';
            row.querySelector('.category').value = option.dataset.category || '';

            if (!forceUpdatePrice) {
                calculateSubtotal(row);
                return;
            }

            const billing = billingSelect.value;

            if (billing === 'daily') {
                priceInput.value = option.dataset.daily || 0;
            } else if (billing === 'weekly') {
                priceInput.value = option.dataset.weekly || 0;
            } else if (billing === 'monthly') {
                priceInput.value = option.dataset.monthly || 0;
            } else if (billing === 'meter') {
                priceInput.value = option.dataset.meterPrice || 0;
            } else if (billing === 'package' || billing === 'fixed') {
                priceInput.value = option.dataset.packagePrice || option.dataset.unitPrice || 0;
            } else {
                priceInput.value = option.dataset.unitPrice || 0;
            }

            displayInput.value = formatRupiah(priceInput.value);
            calculateSubtotal(row);
        }

        function fillProduct(row) {
            const productSelect = row.querySelector('.product-select');
            const option = productSelect.options[productSelect.selectedIndex];

            row.querySelector('.item-name').value = option?.dataset.name || '';
            row.querySelector('.item-type').value = option?.dataset.itemType || 'rental_unit';
            row.querySelector('.category').value = option?.dataset.category || '';

            const billingSelect = row.querySelector('.billing-type');
            billingSelect.dataset.current = option?.dataset.period || 'daily';

            updateBillingOptions(row);
            setPriceByPeriod(row, true);
        }

        function bindRow(row) {
            row.querySelector('.product-select').addEventListener('change', function () {
                fillProduct(row);
            });

            updateBillingOptions(row);
            setPriceByPeriod(row, false);

            row.querySelector('.billing-type').addEventListener('change', function () {
                this.dataset.current = this.value;
                setPriceByPeriod(row, true);
            });

            row.querySelectorAll('.qty, .duration').forEach(input => {
                input.addEventListener('input', function () {
                    calculateSubtotal(row);
                });
            });

            row.querySelector('.unit-price-display').addEventListener('input', function () {
                const cleanValue = parseRupiah(this.value);

                row.querySelector('.unit-price').value = cleanValue;
                this.value = formatRupiah(cleanValue);

                calculateSubtotal(row);
            });

            row.querySelector('.discount-display').addEventListener('input', function () {
                const cleanValue = parseRupiah(this.value);

                row.querySelector('.discount').value = cleanValue;
                this.value = formatRupiah(cleanValue);

                calculateSubtotal(row);
            });

            row.querySelector('.remove-item').addEventListener('click', function () {
                if (document.querySelectorAll('.item-row').length > 1) {
                    row.remove();
                    updateSummary();
                }
            });

            calculateSubtotal(row);
        }

        let dpEditMode = document.getElementById('dp_type')?.value || 'percentage';

        document.getElementById('dp_percentage')?.addEventListener('input', function () {
            dpEditMode = 'percentage';
            document.getElementById('dp_type').value = 'percentage';
            updateSummary();
        });

        document.getElementById('dp_nominal_display')?.addEventListener('input', function () {
            dpEditMode = 'nominal';
            document.getElementById('dp_type').value = 'nominal';

            const cleanValue = parseRupiah(this.value);
            document.getElementById('dp_nominal').value = cleanValue;
            this.value = formatRupiah(cleanValue);

            updateSummary();
        });
        if (wrapper && addBtn) {
            document.querySelectorAll('.item-row').forEach(bindRow);

            addBtn.addEventListener('click', function () {
                const firstRow = document.querySelector('.item-row');
                const newRow = firstRow.cloneNode(true);

                newRow.querySelectorAll('input').forEach(input => {
                    if (input.classList.contains('qty') || input.classList.contains('duration')) {
                        input.value = 1;
                    } else if (
                        input.classList.contains('unit-price') ||
                        input.classList.contains('unit-price-display') ||
                        input.classList.contains('discount') ||
                        input.classList.contains('discount-display') ||
                        input.classList.contains('subtotal')
                        ) {
                        input.value = '0';
                    } else {
                        input.value = '';
                    }
                });

                const billingSelect = newRow.querySelector('.billing-type');

                billingSelect.dataset.current = 'daily';

                billingSelect.innerHTML = `
                    <option value="daily">Harian</option>
                    <option value="weekly">Mingguan</option>
                    <option value="monthly">Bulanan</option>
                `;

                newRow.querySelectorAll('select').forEach(select => {
                    select.selectedIndex = 0;
                });

                wrapper.appendChild(newRow);
                bindRow(newRow);
                updateSummary();
            });
        }

        updateSummary();
    });
</script>