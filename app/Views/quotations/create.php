
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
                Tambah Penawaran
            </h3>

            <p class="mb-0 text-body">
                Buat penawaran baru berdasarkan customer existing atau lead marketing.
            </p>
        </div>

        <div class="d-flex justify-content-between justify-content-md-end align-items-center flex-wrap gap-3">

            <a
                href="<?= url('quotations') ?>"
                class="btn btn-light erp-btn"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Kembali
            </a>

        </div>

    </div>

</div>

<form method="POST" action="<?= url('quotations-store') ?>">

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Informasi Penawaran
            </h4>
        </div>

        <div class="p-20">

            <div class="row g-4">

                <div class="col-md-2">
                    <label class="erp-detail-label">No Penawaran</label>
                    <input
                        type="text"
                        name="no_quotation"
                        class="form-control"
                        value="<?= htmlspecialchars($nomor) ?>"
                        readonly
                    >
                </div>

                <div class="col-md-3">
                    <label class="erp-detail-label">Sumber Data</label>
                    <select name="source_type" id="source_type" class="form-control" required>
                        <option value="customer">Customer Existing</option>
                        <option value="lead" <?= !empty($fromLead) ? 'selected' : '' ?>>
                            Lead Marketing
                        </option>
                    </select>
                </div>

                <div class="col-md-4 source-customer">
                    <label class="erp-detail-label">Customer</label>
                    <select name="customer_id" id="customer_id" class="form-control">
                        <option value="">Pilih Customer</option>
                    </select>
                </div>

                <div class="col-md-4 source-lead d-none">
                    <label class="erp-detail-label">Lead</label>
                    <select name="lead_id" id="lead_id" class="form-control">
                        <option value="">Pilih Lead</option>

                        <?php if (!empty($lead)): ?>
                            <option
                                value="<?= $lead['id'] ?>"
                                selected
                                data-name="<?= htmlspecialchars($lead['company_name'] ?: $lead['pic_name']) ?>"
                                data-phone="<?= htmlspecialchars($lead['phone'] ?? '') ?>"
                                data-address="<?= htmlspecialchars($lead['address'] ?? '') ?>"
                            >
                                <?= htmlspecialchars(($lead['lead_number'] ?? '-') . ' - ' . ($lead['company_name'] ?: $lead['pic_name'])) ?>
                            </option>
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
                        required
                        readonly
                    >
                </div>

                <div class="col-md-3">
                    <label class="erp-detail-label">No. HP</label>
                    <input
                        type="text"
                        name="customer_phone"
                        id="customer_phone"
                        class="form-control"
                        readonly
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Lokasi</label>
                    <textarea
                        name="lokasi"
                        id="lokasi"
                        class="form-control"
                        rows="2"
                    ></textarea>
                </div>

                <div class="col-md-3">
                    <label class="erp-detail-label">Tanggal Mulai</label>
                    <input
                        type="date"
                        name="tanggal_mulai"
                        class="form-control"
                        required
                    >
                </div>

                <div class="col-md-3">
                    <label class="erp-detail-label">Tanggal Selesai</label>
                    <input
                        type="date"
                        name="tanggal_selesai"
                        class="form-control"
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
                                        data-period="<?= htmlspecialchars($product['default_period_type'] ?? 'daily') ?>"
                                        data-daily="<?= htmlspecialchars($product['daily_price'] ?? 0) ?>"
                                        data-weekly="<?= htmlspecialchars($product['weekly_price'] ?? 0) ?>"
                                        data-monthly="<?= htmlspecialchars($product['monthly_price'] ?? 0) ?>"
                                        data-item-type="<?= htmlspecialchars($product['item_type'] ?? 'rental_unit') ?>"
                                        data-unit="<?= htmlspecialchars($product['unit_name'] ?? 'unit') ?>"
                                        data-unit-price="<?= htmlspecialchars($product['unit_price'] ?? 0) ?>"
                                        data-meter-price="<?= htmlspecialchars($product['meter_price'] ?? 0) ?>"
                                        data-package-price="<?= htmlspecialchars($product['package_price'] ?? 0) ?>"
                                    >
                                        <?= htmlspecialchars($product['name'] ?? '-') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <input type="hidden" name="item_name[]" class="item-name">
                            <input type="hidden" name="item_type[]" class="item-type">
                            <input type="hidden" name="category[]" value="">
                        </div>

                        <div class="col-md-1">
                            <label class="erp-detail-label">Qty</label>
                            <input type="number" name="qty[]" class="form-control qty" value="1" min="1">
                        </div>

                        <div class="col-md-2">
                            <label class="erp-detail-label">Billing</label>
                            <select name="billing_type[]" class="form-control billing-type">
                                <option value="daily">Harian</option>
                                <option value="weekly">Mingguan</option>
                                <option value="monthly">Bulanan</option>
                            </select>
                        </div>

                        <div class="col-md-1">
                            <label class="erp-detail-label">Durasi</label>
                            <input type="number" name="duration[]" class="form-control duration" value="1" min="1">
                        </div>

                        <div class="col-md-2">
                            <label class="erp-detail-label">Harga</label>
                            <input type="text" class="form-control unit-price-display" value="0">
                            <input type="hidden" name="unit_price[]" class="unit-price" value="0">
                        </div>

                        <div class="col-md-1">
                            <label class="erp-detail-label">Diskon</label>
                            <input type="text" class="form-control discount-display" value="0">
                            <input type="hidden" name="discount[]" class="discount" value="0">
                        </div>

                        <div class="col-md-1">
                            <label class="erp-detail-label">Total</label>
                            <input type="text" class="form-control subtotal" value="0" readonly>
                        </div>

                        <div class="col-md-1 text-end">
                            <label class="erp-detail-label d-block">&nbsp;</label>
                            <button type="button" class="btn btn-outline-danger erp-btn remove-item" title="Hapus Item">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>

                    </div>

                </div>

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
                    <textarea name="catatan" class="form-control" rows="8"><?=
'• Harga belum termasuk pajak / PPN.
• Harga belum termasuk biaya teknisi standby (jika diperlukan).
• Unit disiapkan dalam kondisi baik dan siap operasional.
• Stok unit bersifat berjalan dan belum mengikat sebelum adanya pembayaran DP / booking.
• Pembayaran uang muka (DP) yang sudah dibayarkan tidak dapat dikembalikan apabila terjadi pembatalan dari pihak customer.
• Kendala selama penggunaan menjadi tanggung jawab penyewa apabila tidak menggunakan teknisi standby dari pihak kami.
• Kerusakan akibat kelalaian pengguna menjadi tanggung jawab penyewa.
• Penambahan durasi rental akan dihitung sesuai tarif yang berlaku.
• Pembayaran DP dianggap sebagai persetujuan atas penawaran ini.'
?></textarea>
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
                href="<?= url('quotations') ?>"
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
						${item.phone ? `
							<div class="small text-muted">${escape(item.phone)}</div>
						` : ''}
					</div>
				`;
			}
		},

		onChange: function(value) {
			const item = this.options[value];

			if (!item) {
				resetCustomerFields();
				return;
			}

			document.getElementById('customer_name').value = item.name || item.text || '';
			document.getElementById('customer_phone').value = item.phone || '';
			document.getElementById('lokasi').value = item.address || '';
		}
	});

	const leadSelectTom = new TomSelect("#lead_id", {
		valueField: 'id',
		labelField: 'text',
		searchField: 'text',
		maxOptions: 20,
		preload: false,

		load: function(query, callback) {
			if (!query.length) return callback();

			fetch(`<?= url('marketing-leads-search-ajax') ?>?q=${encodeURIComponent(query)}`)
				.then(response => response.json())
				.then(json => callback(json))
				.catch(() => callback());
		},

		render: {
			option: function(item, escape) {
				return `
					<div class="py-2">
						<div class="fw-semibold">${escape(item.text)}</div>
						${item.phone ? `
							<div class="small text-muted">${escape(item.phone)}</div>
						` : ''}
					</div>
				`;
			}
		},

		onChange: function(value) {
			const item = this.options[value];

			if (!item) {
				resetCustomerFields();
				return;
			}

			document.getElementById('customer_name').value = item.name || item.text || '';
			document.getElementById('customer_phone').value = item.phone || '';
			document.getElementById('lokasi').value = item.address || '';
		}
	});

	const sourceType = document.getElementById('source_type');
	const customerBox = document.querySelector('.source-customer');
	const leadBox = document.querySelector('.source-lead');
	<?php if (!empty($fromLead)): ?>
	    sourceType.value = 'lead';
	<?php endif; ?>

	function resetCustomerFields() {
		document.getElementById('customer_name').value = '';
		document.getElementById('customer_phone').value = '';
		document.getElementById('lokasi').value = '';
	}

	function toggleSource() {
	    resetCustomerFields();

	    if (sourceType.value === 'lead') {
	        customerBox.classList.add('d-none');
	        leadBox.classList.remove('d-none');
	        customerSelect.clear();

	        const selectedLead = leadSelectTom.getValue();
	        if (selectedLead) {
	            leadSelectTom.trigger('change', selectedLead);
	        }

	    } else {
	        customerBox.classList.remove('d-none');
	        leadBox.classList.add('d-none');
	        leadSelectTom.clear();
	    }
	}

	sourceType.addEventListener('change', toggleSource);
	toggleSource();

	const wrapper = document.getElementById('items-wrapper');
	const addBtn = document.getElementById('add-item');

	function formatRupiah(value) {
		return Number(value || 0).toLocaleString('id-ID');
	}

	function rupiahText(value) {
		return 'Rp ' + formatRupiah(value);
	}

	function parseRupiah(value) {
		return String(value || '').replace(/\./g, '').replace(/,/g, '');
	}

	function updateSummary() {
		let subtotalBeforeDiscount = 0;
		let totalDiscount = 0;
		let grandTotal = 0;

		document.querySelectorAll('.item-row').forEach(row => {
			const qty = parseFloat(row.querySelector('.qty')?.value || 0);
			const duration = parseFloat(row.querySelector('.duration')?.value || 0);
			const price = parseFloat(row.querySelector('.unit-price')?.value || 0);
			const discount = parseFloat(row.querySelector('.discount')?.value || 0);
			const billing = row.querySelector('.billing-type')?.value || 'unit';

			let rowSubtotalBeforeDiscount = 0;

			if (billing === 'daily' || billing === 'weekly' || billing === 'monthly') {
				rowSubtotalBeforeDiscount = qty * duration * price;
			} else {
				rowSubtotalBeforeDiscount = qty * price;
			}

			const rowTotal = Math.max(0, rowSubtotalBeforeDiscount - discount);

			subtotalBeforeDiscount += rowSubtotalBeforeDiscount;
			totalDiscount += discount;
			grandTotal += rowTotal;
		});

		document.getElementById('summary-subtotal').innerText = rupiahText(subtotalBeforeDiscount);
		document.getElementById('summary-discount').innerText = rupiahText(totalDiscount);
		document.getElementById('summary-grand-total').innerText = rupiahText(grandTotal);
	}

	function calculateSubtotal(row) {
		const qty = parseFloat(row.querySelector('.qty')?.value || 0);
		const duration = parseFloat(row.querySelector('.duration')?.value || 0);
		const price = parseFloat(row.querySelector('.unit-price')?.value || 0);
		const discount = parseFloat(row.querySelector('.discount')?.value || 0);
		const billing = row.querySelector('.billing-type')?.value || 'unit';

		let subtotalBeforeDiscount = 0;

		if (billing === 'daily' || billing === 'weekly' || billing === 'monthly') {
			subtotalBeforeDiscount = qty * duration * price;
		} else {
			subtotalBeforeDiscount = qty * price;
		}

		const total = Math.max(0, subtotalBeforeDiscount - discount);

		row.querySelector('.subtotal').value = formatRupiah(total);
		updateSummary();
	}

	function setPriceByPeriod(row) {
		const productSelect = row.querySelector('.product-select');
		const billingSelect = row.querySelector('.billing-type');
		const priceInput = row.querySelector('.unit-price');
		const displayInput = row.querySelector('.unit-price-display');
		const option = productSelect.options[productSelect.selectedIndex];

		if (!option || !option.value) {
			priceInput.value = 0;
			displayInput.value = '0';
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
		} else if (billing === 'package') {
			priceInput.value = option.dataset.packagePrice || 0;
		} else {
			priceInput.value = option.dataset.unitPrice || 0;
		}

		displayInput.value = formatRupiah(priceInput.value);
		calculateSubtotal(row);
	}

	function updateBillingOptions(row) {
		const productSelect = row.querySelector('.product-select');
		const billingSelect = row.querySelector('.billing-type');
		const option = productSelect.options[productSelect.selectedIndex];
		const itemType = option?.dataset.itemType || 'rental_unit';

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
	}

	function fillProduct(row) {
		const productSelect = row.querySelector('.product-select');
		const option = productSelect.options[productSelect.selectedIndex];

		row.querySelector('.item-name').value = option?.dataset.name || '';
		row.querySelector('.item-type').value = option?.dataset.itemType || 'rental_unit';

		updateBillingOptions(row);
		setPriceByPeriod(row);
	}

	function bindRow(row) {
		row.querySelector('.product-select').addEventListener('change', function () {
			fillProduct(row);
		});

		row.querySelector('.billing-type').addEventListener('change', function () {
			setPriceByPeriod(row);
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
			});

			wrapper.appendChild(newRow);
			bindRow(newRow);
			updateSummary();
		});
	}

	updateSummary();
});
</script>