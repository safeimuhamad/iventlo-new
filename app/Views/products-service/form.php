<?php
$item = $item ?? [];
?>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title mb-0">
            Informasi Produk / Jasa
        </h4>
    </div>

    <div class="p-20">
        <div class="row g-4">

            <div class="col-md-6">
                <label class="erp-detail-label">
                    Nama Produk / Jasa <span class="text-danger">*</span>
                </label>
                <input
                    type="text"
                    name="name"
                    class="form-control"
                    value="<?= htmlspecialchars($item['name'] ?? '') ?>"
                    required
                >
            </div>

            <div class="col-md-3">
                <label class="erp-detail-label">Jenis Produk</label>
                <select name="category" class="form-control">
                    <optgroup label="Air Conditioning">
                        <?php foreach (['AC Split','AC Cassette','AC Standing','AC Portable','AC Ceiling','AC Ceiling Suspended','AC Window','AC Ducting','AC Central','VRV VRF','Chiller','AHU','FCU'] as $category): ?>
                            <option value="<?= $category ?>" <?= ($item['category'] ?? '') === $category ? 'selected' : '' ?>>
                                <?= $category === 'VRV VRF' ? 'VRV / VRF' : $category ?>
                            </option>
                        <?php endforeach; ?>
                    </optgroup>

                    <optgroup label="Cooling Equipment">
                        <?php foreach (['Air Cooler','Misty Fan','Blower Fan','Cooling Fan'] as $category): ?>
                            <option value="<?= $category ?>" <?= ($item['category'] ?? '') === $category ? 'selected' : '' ?>>
                                <?= $category ?>
                            </option>
                        <?php endforeach; ?>
                    </optgroup>

                    <optgroup label="Ventilation">
                        <?php foreach (['Exhaust Fan','Ventilation Fan','Fresh Air Fan'] as $category): ?>
                            <option value="<?= $category ?>" <?= ($item['category'] ?? '') === $category ? 'selected' : '' ?>>
                                <?= $category ?>
                            </option>
                        <?php endforeach; ?>
                    </optgroup>

                    <optgroup label="HVAC Support">
                        <?php foreach (['Ducting','Electrical','Panel','Piping','Insulation','Sparepart','Material','Tools','Transport'] as $category): ?>
                            <option value="<?= $category ?>" <?= ($item['category'] ?? '') === $category ? 'selected' : '' ?>>
                                <?= $category ?>
                            </option>
                        <?php endforeach; ?>
                    </optgroup>

                    <option value="Lainnya" <?= ($item['category'] ?? '') === 'Lainnya' ? 'selected' : '' ?>>
                        Lainnya
                    </option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="erp-detail-label">Status</label>
                <select name="status" class="form-control">
                    <option value="active" <?= ($item['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= ($item['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>

        </div>
    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title mb-0">
            Pengaturan Transaksi
        </h4>
    </div>

    <div class="p-20">
        <div class="row g-4">

            <div class="col-md-4">
                <label class="erp-detail-label">Tipe Transaksi</label>
                <select name="item_type" id="item_type" class="form-control">
                    <option value="rental_unit" <?= ($item['item_type'] ?? '') === 'rental_unit' ? 'selected' : '' ?>>Rental Unit</option>
                    <option value="service" <?= ($item['item_type'] ?? '') === 'service' ? 'selected' : '' ?>>Service</option>
                    <option value="installation" <?= ($item['item_type'] ?? '') === 'installation' ? 'selected' : '' ?>>Instalasi</option>
                    <option value="material" <?= ($item['item_type'] ?? '') === 'material' ? 'selected' : '' ?>>Material</option>
                    <option value="sparepart" <?= ($item['item_type'] ?? '') === 'sparepart' ? 'selected' : '' ?>>Sparepart</option>
                    <option value="transport" <?= ($item['item_type'] ?? '') === 'transport' ? 'selected' : '' ?>>Transport</option>
                    <option value="other" <?= ($item['item_type'] ?? '') === 'other' ? 'selected' : '' ?>>Lainnya</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="erp-detail-label">Satuan</label>
                <input
                    type="text"
                    name="unit_name"
                    class="form-control"
                    value="<?= htmlspecialchars($item['unit_name'] ?? 'unit') ?>"
                >
            </div>

            <div class="col-md-4">
                <label class="erp-detail-label">Default Billing</label>
                <select name="default_period_type" id="default_period_type" class="form-control">
                    <option value="daily" <?= ($item['default_period_type'] ?? '') === 'daily' ? 'selected' : '' ?>>Harian</option>
                    <option value="weekly" <?= ($item['default_period_type'] ?? '') === 'weekly' ? 'selected' : '' ?>>Mingguan</option>
                    <option value="monthly" <?= ($item['default_period_type'] ?? '') === 'monthly' ? 'selected' : '' ?>>Bulanan</option>
                    <option value="unit" <?= ($item['default_period_type'] ?? '') === 'unit' ? 'selected' : '' ?>>Per Unit</option>
                    <option value="meter" <?= ($item['default_period_type'] ?? '') === 'meter' ? 'selected' : '' ?>>Per Meter</option>
                    <option value="package" <?= ($item['default_period_type'] ?? '') === 'package' ? 'selected' : '' ?>>Paket</option>
                    <option value="fixed" <?= ($item['default_period_type'] ?? '') === 'fixed' ? 'selected' : '' ?>>Fixed</option>
                </select>
            </div>

        </div>
    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title mb-0">
            Harga Produk / Jasa
        </h4>
    </div>

    <div class="p-20">
        <div class="row g-4">

            <?php
            $priceFields = [
                ['class' => 'price-rental', 'label' => 'Harga Harian', 'name' => 'daily_price'],
                ['class' => 'price-rental', 'label' => 'Harga Mingguan', 'name' => 'weekly_price'],
                ['class' => 'price-rental', 'label' => 'Harga Bulanan', 'name' => 'monthly_price'],
                ['class' => 'price-service', 'label' => 'Harga Per Unit', 'name' => 'unit_price'],
                ['class' => 'price-material', 'label' => 'Harga Per Meter', 'name' => 'meter_price'],
                ['class' => 'price-service price-fixed', 'label' => 'Harga Paket / Fixed', 'name' => 'package_price'],
            ];
            ?>

            <?php foreach ($priceFields as $field): ?>
                <div class="col-md-4 price-field <?= $field['class'] ?>">
                    <label class="erp-detail-label"><?= $field['label'] ?></label>

                    <input
                        type="text"
                        class="form-control rupiah-input"
                        data-target="<?= $field['name'] ?>"
                        value="<?= number_format((float) ($item[$field['name']] ?? 0), 0, ',', '.') ?>"
                    >

                    <input
                        type="hidden"
                        name="<?= $field['name'] ?>"
                        id="<?= $field['name'] ?>"
                        value="<?= htmlspecialchars($item[$field['name']] ?? 0) ?>"
                    >
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
                    Deskripsi
                </h4>
            </div>

            <div class="p-20">
                <textarea
                    name="description"
                    class="form-control"
                    rows="8"
                    placeholder="Deskripsi produk atau jasa"
                ><?= htmlspecialchars($item['description'] ?? '') ?></textarea>
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
                    <strong><?= htmlspecialchars($item['status'] ?? 'active') ?></strong>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Tipe</span>
                    <strong><?= htmlspecialchars($item['item_type'] ?? 'rental_unit') ?></strong>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Billing</span>
                    <strong><?= htmlspecialchars($item['default_period_type'] ?? 'daily') ?></strong>
                </div>

                <hr>

                <div class="text-body">
                    Produk dan jasa digunakan pada quotation, rental order, invoice, purchase request, dan purchase order.
                </div>

            </div>

        </div>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const itemType = document.getElementById('item_type');
    const billing = document.getElementById('default_period_type');

    function showFields(selector) {
        document.querySelectorAll(selector).forEach(el => {
            el.style.display = 'block';
        });
    }

    function hideAllPriceFields() {
        document.querySelectorAll('.price-field').forEach(el => {
            el.style.display = 'none';
        });
    }

    function syncBillingOptions() {
        if (!itemType || !billing) return;

        const type = itemType.value;
        const current = billing.value;

        hideAllPriceFields();

        let options = '';

        if (type === 'rental_unit') {
            options = `
                <option value="daily">Harian</option>
                <option value="weekly">Mingguan</option>
                <option value="monthly">Bulanan</option>
            `;

            showFields('.price-rental');

        } else if (type === 'material') {
            options = `
                <option value="meter">Per Meter</option>
                <option value="unit">Per Unit</option>
            `;

            showFields('.price-material');
            showFields('.price-service');

        } else if (type === 'transport') {
            options = `
                <option value="package">Paket</option>
                <option value="fixed">Fixed</option>
            `;

            showFields('.price-fixed');

        } else {
            options = `
                <option value="unit">Per Unit</option>
                <option value="package">Paket</option>
                <option value="fixed">Fixed</option>
            `;

            showFields('.price-service');
        }

        billing.innerHTML = options;

        if ([...billing.options].some(option => option.value === current)) {
            billing.value = current;
        }
    }

    if (itemType) {
        itemType.addEventListener('change', syncBillingOptions);
    }

    syncBillingOptions();

    document.querySelectorAll('.rupiah-input').forEach(input => {

        function formatRupiah() {
            let value = input.value.replace(/\D/g, '');
            const target = document.getElementById(input.dataset.target);

            if (target) {
                target.value = value || 0;
            }

            input.value = value
                ? new Intl.NumberFormat('id-ID').format(value)
                : '';
        }

        formatRupiah();

        input.addEventListener('input', formatRupiah);

    });

});
</script>