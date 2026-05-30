<?php
$item = $item ?? [];
?>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title mb-0">
            Informasi Kendaraan
        </h4>
    </div>

    <div class="p-20">

        <div class="row g-4">

            <div class="col-md-4">
                <label class="erp-detail-label">
                    Kode Kendaraan <span class="text-danger">*</span>
                </label>

                <input
                    type="text"
                    name="vehicle_code"
                    class="form-control"
                    value="<?= htmlspecialchars($item['vehicle_code'] ?? '') ?>"
                    placeholder="VH-001"
                    required
                >
            </div>

            <div class="col-md-4">
                <label class="erp-detail-label">
                    Nama Kendaraan <span class="text-danger">*</span>
                </label>

                <input
                    type="text"
                    name="vehicle_name"
                    class="form-control"
                    value="<?= htmlspecialchars($item['vehicle_name'] ?? '') ?>"
                    placeholder="Grand Max Pick Up"
                    required
                >
            </div>

            <div class="col-md-4">
                <label class="erp-detail-label">
                    Nomor Polisi <span class="text-danger">*</span>
                </label>

                <input
                    type="text"
                    name="plate_number"
                    class="form-control"
                    value="<?= htmlspecialchars($item['plate_number'] ?? '') ?>"
                    placeholder="B 1234 ABC"
                    required
                >
            </div>

            <div class="col-md-4">
                <label class="erp-detail-label">Tipe Kendaraan</label>

                <input
                    type="text"
                    name="vehicle_type"
                    class="form-control"
                    value="<?= htmlspecialchars($item['vehicle_type'] ?? '') ?>"
                    placeholder="Pick Up / Box / Motor"
                >
            </div>

            <div class="col-md-4">
                <label class="erp-detail-label">Brand</label>

                <input
                    type="text"
                    name="brand"
                    class="form-control"
                    value="<?= htmlspecialchars($item['brand'] ?? '') ?>"
                    placeholder="Daihatsu / Suzuki / Toyota"
                >
            </div>

            <div class="col-md-4">
                <label class="erp-detail-label">Tahun</label>

                <input
                    type="number"
                    name="year"
                    class="form-control"
                    value="<?= htmlspecialchars($item['year'] ?? '') ?>"
                    placeholder="2022"
                >
            </div>

        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title mb-0">
            Maintenance & Kilometer
        </h4>
    </div>

    <div class="p-20">

        <div class="row g-4">

            <div class="col-md-4">
                <label class="erp-detail-label">Total KM Saat Ini</label>

                <input
                    type="number"
                    name="total_km"
                    class="form-control"
                    value="<?= htmlspecialchars($item['total_km'] ?? 0) ?>"
                    min="0"
                >
            </div>

            <div class="col-md-4">
                <label class="erp-detail-label">Interval Service KM</label>

                <input
                    type="number"
                    name="maintenance_interval_km"
                    class="form-control"
                    value="<?= htmlspecialchars($item['maintenance_interval_km'] ?? 5000) ?>"
                    min="1"
                >
            </div>

            <div class="col-md-4">
                <label class="erp-detail-label">Interval Service Bulan</label>

                <input
                    type="number"
                    name="maintenance_interval_month"
                    class="form-control"
                    value="<?= htmlspecialchars($item['maintenance_interval_month'] ?? 3) ?>"
                    min="1"
                >
            </div>

        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title mb-0">
            Dokumen Kendaraan
        </h4>
    </div>

    <div class="p-20">

        <div class="row g-4">

            <div class="col-md-4">
                <label class="erp-detail-label">Tanggal Berakhir STNK</label>

                <input
                    type="date"
                    name="stnk_expired_date"
                    class="form-control"
                    value="<?= htmlspecialchars($item['stnk_expired_date'] ?? '') ?>"
                >
            </div>

            <div class="col-md-4">
                <label class="erp-detail-label">Tanggal Pajak Kendaraan</label>

                <input
                    type="date"
                    name="tax_expired_date"
                    class="form-control"
                    value="<?= htmlspecialchars($item['tax_expired_date'] ?? '') ?>"
                >
            </div>

            <div class="col-md-4">
                <label class="erp-detail-label">Tanggal KIR Berakhir</label>

                <input
                    type="date"
                    name="kir_expired_date"
                    class="form-control"
                    value="<?= htmlspecialchars($item['kir_expired_date'] ?? '') ?>"
                >
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
                    placeholder="Catatan kendaraan"
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
                    <span>Kode</span>
                    <strong>
                        <?= htmlspecialchars($item['vehicle_code'] ?? '-') ?>
                    </strong>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Nomor Polisi</span>
                    <strong>
                        <?= htmlspecialchars($item['plate_number'] ?? '-') ?>
                    </strong>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Total KM</span>
                    <strong>
                        <?= number_format((float) ($item['total_km'] ?? 0), 0, ',', '.') ?>
                    </strong>
                </div>

                <hr>

                <div class="text-body">
                    Data kendaraan digunakan untuk operasional pengiriman, pemakaian kendaraan, service berkala, dan monitoring dokumen.
                </div>

            </div>

        </div>

    </div>

</div>