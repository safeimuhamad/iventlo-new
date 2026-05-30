<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger mb-4">
        <?= $_SESSION['error']; ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<?php
$quotation = $quotation ?? null;
$quotationItems = $quotationItems ?? [];
$isFromQuotation = !empty($quotation);
?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h3 class="mb-1">Tambah Invoice Rental</h3>
            <p class="mb-0 text-body">
                <?= $isFromQuotation
                    ? 'Buat invoice berdasarkan quotation. Data customer dan item sudah terisi otomatis dan masih bisa disesuaikan.'
                    : 'Buat invoice rental baru berdasarkan customer, produk atau jasa, pajak, dan ringkasan pembayaran.'
                ?>
            </p>
        </div>

        <a href="<?= url('invoices') ?>" class="btn btn-light erp-btn">
            <i class="ri-arrow-left-line me-1"></i>
            Kembali
        </a>
    </div>
</div>

<form method="POST" action="<?= url('invoices-store') ?>">

    <input type="hidden" name="quotation_id" value="<?= htmlspecialchars($quotation['id'] ?? '') ?>">

    <div class="card bg-white rounded-10 border border-white mb-4">
        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">Informasi Invoice</h4>
        </div>

        <div class="p-20">
            <div class="row g-4">

                <div class="col-md-4">
                    <label class="erp-detail-label">Customer</label>
                    <select name="customer_id" id="customer_id" class="form-control">
                        <?php if (!empty($quotation['customer_id'])): ?>
                            <option value="<?= htmlspecialchars($quotation['customer_id']) ?>" selected>
                                <?= htmlspecialchars($quotation['customer_name'] ?? '-') ?>
                            </option>
                        <?php else: ?>
                            <option value="">Pilih Customer</option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="erp-detail-label">Nama Customer</label>
                    <input
                        type="text"
                        name="customer_name"
                        id="customer_name"
                        class="form-control"
                        value="<?= htmlspecialchars($quotation['customer_name'] ?? '') ?>"
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
                        value="<?= htmlspecialchars($quotation['customer_phone'] ?? '') ?>"
                    >
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">Lokasi</label>
                    <textarea
                        name="lokasi"
                        id="lokasi"
                        class="form-control"
                        rows="2"
                    ><?= htmlspecialchars($quotation['lokasi'] ?? '') ?></textarea>
                </div>

                <div class="col-md-2">
                    <label class="erp-detail-label">Tipe Invoice</label>
                    <select name="invoice_type" class="form-control">
                        <option value="dp">DP</option>
                        <option value="full">Full Payment</option>
                        <option value="final">Pelunasan</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="erp-detail-label">PPN</label>
                    <select name="tax_type" id="tax_type" class="form-control">
                        <option value="include_ppn" <?= (($quotation['tax_type'] ?? '') === 'include_ppn') ? 'selected' : '' ?>>
                            Include PPN
                        </option>
                        <option value="non_ppn" <?= (($quotation['tax_type'] ?? 'non_ppn') === 'non_ppn') ? 'selected' : '' ?>>
                            Non PPN
                        </option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="erp-detail-label">Tanggal Invoice</label>
                    <input type="date" name="invoice_date" class="form-control" value="<?= date('Y-m-d') ?>">
                </div>

                <div class="col-md-2">
                    <label class="erp-detail-label">Jatuh Tempo</label>
                    <input type="date" name="due_date" class="form-control" value="<?= date('Y-m-d') ?>">
                </div>

            </div>
        </div>
    </div>

    <div class="card bg-white rounded-10 border border-white mb-4">
        <div class="p-20 border-bottom">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <h4 class="erp-detail-section-title mb-0">Produk / Jasa yang Disewakan</h4>

                <button type="button" id="add-item" class="btn btn-outline-primary erp-btn">
                    <i class="ri-add-line me-1"></i>
                    Tambah Item
                </button>
            </div>
        </div>

        <div class="p-20">
            <div id="items-wrapper">

                <?php
                $rows = !empty($quotationItems) ? $quotationItems : [[]];
                ?>

                <?php foreach ($rows as $qItem): ?>
                    <?php
                    $selectedProductId = $qItem['product_id'] ?? '';
                    $billingType = $qItem['billing_type'] ?? 'daily';
                    $itemType = $qItem['item_type'] ?? 'rental_unit';
                    $qty = $qItem['qty'] ?? 1;
                    $duration = $qItem['duration'] ?? 1;
                    $unitPrice = (float) ($qItem['unit_price'] ?? 0);
                    $discount = (float) ($qItem['discount'] ?? 0);

                    if (in_array($billingType, ['daily', 'weekly', 'monthly'])) {
                        $rowBeforeDiscount = $qty * $duration * $unitPrice;
                    } elseif (in_array($billingType, ['package', 'fixed'])) {
                        $rowBeforeDiscount = $unitPrice;
                    } else {
                        $rowBeforeDiscount = $qty * $unitPrice;
                    }

                    $rowSubtotal = max(0, $rowBeforeDiscount - $discount);
                    ?>

                    <div class="item-row border rounded-10 p-3 mb-3">
                        <div class="row align-items-end g-3">

                            <div class="col-md-3">
                                <label class="erp-detail-label">Produk / Jasa</label>

                                <select name="product_id[]" class="form-control product-select">
                                    <option value="">-- Pilih Produk / Jasa --</option>

                                    <?php foreach ($products as $product): ?>
                                        <option
                                            value="<?= $product['id'] ?>"
                                            data-name="<?= htmlspecialchars($product['name'] ?? '') ?>"
                                            data-item-type="<?= htmlspecialchars($product['item_type'] ?? 'rental_unit') ?>"
                                            data-unit="<?= htmlspecialchars($product['unit_name'] ?? 'unit') ?>"
                                            data-period="<?= htmlspecialchars($product['default_period_type'] ?? 'daily') ?>"
                                            data-daily="<?= htmlspecialchars($product['daily_price'] ?? 0) ?>"
                                            data-weekly="<?= htmlspecialchars($product['weekly_price'] ?? 0) ?>"
                                            data-monthly="<?= htmlspecialchars($product['monthly_price'] ?? 0) ?>"
                                            data-unit-price="<?= htmlspecialchars($product['unit_price'] ?? 0) ?>"
                                            data-meter-price="<?= htmlspecialchars($product['meter_price'] ?? 0) ?>"
                                            data-package-price="<?= htmlspecialchars($product['package_price'] ?? 0) ?>"
                                            <?= ((string)$selectedProductId === (string)$product['id']) ? 'selected' : '' ?>
                                        >
                                            <?= htmlspecialchars($product['name'] ?? '-') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <input type="hidden" name="item_name[]" class="item-name" value="<?= htmlspecialchars($qItem['item_name'] ?? '') ?>">
                                <input type="hidden" name="item_type[]" class="item-type" value="<?= htmlspecialchars($itemType) ?>">
                                <input type="hidden" name="category[]" class="category" value="<?= htmlspecialchars($qItem['category'] ?? '') ?>">
                            </div>

                            <div class="col-md-1">
                                <label class="erp-detail-label">Qty</label>
                                <input type="number" name="qty[]" class="form-control qty" value="<?= htmlspecialchars($qty) ?>" min="1">
                            </div>

                            <div class="col-md-2">
                                <label class="erp-detail-label">Billing</label>
                                <select name="billing_type[]" class="form-control billing-type" data-current="<?= htmlspecialchars($billingType) ?>">
                                    <?php foreach (['daily' => 'Harian', 'weekly' => 'Mingguan', 'monthly' => 'Bulanan', 'unit' => 'Per Unit', 'meter' => 'Per Meter', 'package' => 'Paket', 'fixed' => 'Fixed'] as $key => $label): ?>
                                        <option value="<?= $key ?>" <?= $billingType === $key ? 'selected' : '' ?>>
                                            <?= $label ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-1">
                                <label class="erp-detail-label">Durasi</label>
                                <input type="number" name="duration[]" class="form-control duration" value="<?= htmlspecialchars($duration) ?>" min="1">
                            </div>

                            <div class="col-md-2">
                                <label class="erp-detail-label">Harga</label>
                                <input type="text" class="form-control unit-price-display" value="<?= number_format($unitPrice, 0, ',', '.') ?>">
                                <input type="hidden" name="unit_price[]" class="unit-price" value="<?= htmlspecialchars($unitPrice) ?>">
                            </div>

                            <div class="col-md-1">
                                <label class="erp-detail-label">Diskon</label>
                                <input type="text" class="form-control discount-display" value="<?= number_format($discount, 0, ',', '.') ?>">
                                <input type="hidden" name="discount[]" class="discount" value="<?= htmlspecialchars($discount) ?>">
                            </div>

                            <div class="col-md-1">
                                <label class="erp-detail-label">Total</label>
                                <input type="text" class="form-control subtotal" value="<?= number_format($rowSubtotal, 0, ',', '.') ?>" readonly>
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
                    <h4 class="erp-detail-section-title mb-0">Catatan</h4>
                </div>

                <div class="p-20">
                    <textarea name="notes" class="form-control" rows="8"><?= htmlspecialchars($quotation['catatan'] ?? '') ?: "• Harga belum termasuk biaya teknisi standby (jika diperlukan).
• Unit disiapkan dalam kondisi baik dan siap operasional.
• Stok unit bersifat berjalan & belum mengikat sebelum pembayaran DP.
• DP yang tidak dapat dikembalikan jika pembatalan dari pihak customer.
• Kerusakan akibat kelalaian pengguna menjadi tanggung jawab penyewa.
• Penambahan durasi rental akan dihitung sesuai tarif yang berlaku.
• Pembayaran DP dianggap sebagai persetujuan atas penawaran ini." ?></textarea>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-white rounded-10 border border-white h-100">
                <div class="p-20 border-bottom">
                    <h4 class="erp-detail-section-title mb-0">Ringkasan</h4>
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

                    <div class="d-flex justify-content-between mb-2" id="summary-tax-row" style="display:none !important;">
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
                                    value="50"
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
                                value="0"
                            >

                            <input
                                type="hidden"
                                name="dp_nominal"
                                id="dp_nominal"
                                value="0"
                            >
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span>Sisa Tagihan</span>
                            <strong id="summary-remaining-bill">Rp 0</strong>
                        </div>

                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Grand Total</span>
                        <strong id="summary-grand-total">Rp 0</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="remaining_bill" id="remaining_bill_input" value="0">
    <input type="hidden" name="dp_type" id="dp_type" value="percentage">
    <input type="hidden" name="no_invoice" value="<?= htmlspecialchars($nomor) ?>">
    <input type="hidden" name="paid_amount" value="0">
    <input type="hidden" name="status_payment" value="waiting payment">
    <input type="hidden" name="subtotal" id="subtotal_input" value="0">
    <input type="hidden" name="total_discount" id="discount_input" value="0">
    <input type="hidden" name="tax_amount" id="tax_amount_input" value="0">
    <input type="hidden" name="grand_total" id="grand_total_input" value="0">

    <div class="card bg-white rounded-10 border border-white p-20">
        <div class="d-flex justify-content-end flex-wrap gap-3">
            <a href="<?= url('invoices') ?>" class="btn btn-light erp-btn">
                <i class="ri-close-line me-1"></i>
                Batal
            </a>

            <button type="submit" class="btn btn-primary text-white erp-btn">
                <i class="ri-save-line me-1"></i>
                Simpan
            </button>
        </div>
    </div>

</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const customerSelect = new TomSelect("#customer_id", {
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        maxOptions: 20,
        preload: false,

        load: function(query, callback) {
            if (!query.length) return callback();

            fetch(`<?= url('customers-search-ajax') ?>?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(json => callback(json))
                .catch(() => callback());
        },

        render: {
            option: function(item, escape) {
                return `
                    <div class="py-2">
                        <div class="fw-semibold">${escape(item.text)}</div>
                        ${item.phone ? `<div class="small text-muted">${escape(item.phone)}</div>` : ''}
                    </div>
                `;
            }
        },

        onChange: function(value) {
            const item = this.options[value];

            if (!item) {
                document.getElementById('customer_name').value = '';
                document.getElementById('customer_phone').value = '';
                document.getElementById('lokasi').value = '';
                return;
            }

            document.getElementById('customer_name').value = item.name || item.text || '';
            document.getElementById('customer_phone').value = item.phone || '';
            document.getElementById('lokasi').value = item.address || '';
        }
    });

    const wrapper = document.getElementById('items-wrapper');
    const addBtn = document.getElementById('add-item');
    const taxTypeSelect = document.getElementById('tax_type');

    if (taxTypeSelect) {
        taxTypeSelect.addEventListener('change', updateSummary);
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
    let dpEditMode = 'percentage';

    document.getElementById('dp_percentage')?.addEventListener('input', function () {
        dpEditMode = 'percentage';
        updateSummary();
    });

    document.getElementById('dp_nominal_display')?.addEventListener('input', function () {
        dpEditMode = 'nominal';

        const cleanValue = parseRupiah(this.value);

        document.getElementById('dp_nominal').value = cleanValue;
        this.value = formatRupiah(cleanValue);

        updateSummary();
    });
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
        });

        const taxType = taxTypeSelect ? taxTypeSelect.value : 'non_ppn';
        const taxAmount = taxType === 'include_ppn' ? grandTotalBeforeTax * 0.11 : 0;
        const finalGrandTotal = grandTotalBeforeTax + taxAmount;

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
            let invoiceDpAmount = parseFloat(dpNominalInput?.value || 0);

            if (dpEditMode === 'nominal') {
                invoiceDpAmount = Math.min(finalGrandTotal, invoiceDpAmount);

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
        }

        document.getElementById('summary-subtotal').innerText = rupiahText(subtotalBeforeDiscount);
        document.getElementById('summary-discount').innerText = rupiahText(totalDiscount);
        document.getElementById('summary-grand-total').innerText = rupiahText(displayGrandTotal);

        document.getElementById('subtotal_input').value = subtotalBeforeDiscount;
        document.getElementById('discount_input').value = totalDiscount;
        document.getElementById('tax_amount_input').value = taxAmount;
        document.getElementById('grand_total_input').value = displayGrandTotal;
        document.getElementById('remaining_bill_input').value = remainingBill;
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
    }

    const invoiceTypeSelect = document.querySelector('[name="invoice_type"]');

    if (invoiceTypeSelect) {
        invoiceTypeSelect.addEventListener('change', updateSummary);
    }
    function calculateSubtotal(row) {
        const discount = parseFloat(row.querySelector('.discount')?.value || 0);
        const subtotalBeforeDiscount = getRowSubtotalBeforeDiscount(row);
        const total = Math.max(0, subtotalBeforeDiscount - discount);

        row.querySelector('.subtotal').value = formatRupiah(total);
        updateSummary();
    }

    function updateBillingOptions(row) {
        const productSelect = row.querySelector('.product-select');
        const billingSelect = row.querySelector('.billing-type');
        const option = productSelect.options[productSelect.selectedIndex];
        const itemType = option?.dataset.itemType || row.querySelector('.item-type')?.value || 'rental_unit';
        const current = billingSelect.dataset.current || billingSelect.value;

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

        if ([...billingSelect.options].some(opt => opt.value === current)) {
            billingSelect.value = current;
        }

        billingSelect.dataset.current = billingSelect.value;
    }

    function setPriceByPeriod(row, keepManualPrice = false) {
        const productSelect = row.querySelector('.product-select');
        const billingSelect = row.querySelector('.billing-type');
        const priceInput = row.querySelector('.unit-price');
        const displayInput = row.querySelector('.unit-price-display');
        const option = productSelect.options[productSelect.selectedIndex];

        if (keepManualPrice) {
            displayInput.value = formatRupiah(priceInput.value);
            calculateSubtotal(row);
            return;
        }

        if (!option || !option.value) {
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

        if (!option || !option.value) {
            return;
        }

        row.querySelector('.item-name').value = option.dataset.name || '';
        row.querySelector('.item-type').value = option.dataset.itemType || 'rental_unit';
        row.querySelector('.category').value = option.dataset.unit || '';

        updateBillingOptions(row);

        const billingSelect = row.querySelector('.billing-type');
        const defaultBilling = option.dataset.period || 'daily';

        if ([...billingSelect.options].some(opt => opt.value === defaultBilling)) {
            billingSelect.value = defaultBilling;
            billingSelect.dataset.current = defaultBilling;
        }

        setPriceByPeriod(row);
    }

    function bindRow(row) {
        row.querySelector('.product-select').addEventListener('change', function () {
            fillProduct(row);
        });

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

        updateBillingOptions(row);
        setPriceByPeriod(row, true);
        calculateSubtotal(row);
    }

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

            newRow.querySelectorAll('select').forEach(select => {
                select.selectedIndex = 0;
                select.dataset.current = select.value;
            });

            wrapper.appendChild(newRow);
            bindRow(newRow);
            updateSummary();
        });
    }

    updateSummary();
});
</script>