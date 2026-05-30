<div class="card bg-white rounded-10 border border-white p-20 mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h3 class="mb-1">Detail Log Pemakaian Kendaraan</h3>
            <p class="mb-0 text-body">Informasi perjalanan dan pemakaian kendaraan operasional.</p>
        </div>
        <a href="<?= url('vehicle-usage-logs') ?>" class="btn btn-light erp-btn">
            <i class="ri-arrow-left-line me-1"></i>Kembali
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card bg-white rounded-10 border border-white h-100">
            <div class="p-20 border-bottom">
                <h4 class="erp-detail-section-title mb-0">Informasi Perjalanan</h4>
            </div>
            <div class="p-20">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="erp-detail-label">Kendaraan</div>
                        <div class="erp-detail-value"><?= htmlspecialchars(($item['vehicle_code'] ?? '-') . ' - ' . ($item['vehicle_name'] ?? '-')) ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="erp-detail-label">No. Polisi</div>
                        <div class="erp-detail-value"><?= htmlspecialchars($item['plate_number'] ?? '-') ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="erp-detail-label">Tanggal Pemakaian</div>
                        <div class="erp-detail-value"><?= !empty($item['usage_date']) ? date('d M Y', strtotime($item['usage_date'])) : '-' ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="erp-detail-label">Aktivitas</div>
                        <div class="erp-detail-value"><?= htmlspecialchars(ucfirst($item['activity_type'] ?? '-')) ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="erp-detail-label">Tujuan / Lokasi</div>
                        <div class="erp-detail-value"><?= htmlspecialchars($item['destination'] ?? '-') ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="erp-detail-label">Driver</div>
                        <div class="erp-detail-value"><?= htmlspecialchars($item['driver_name'] ?? '-') ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card bg-white rounded-10 border border-white h-100">
            <div class="p-20 border-bottom">
                <h4 class="erp-detail-section-title mb-0">Kilometer</h4>
            </div>
            <div class="p-20">
                <div class="d-flex justify-content-between mb-3">
                    <span>KM Awal</span><strong><?= number_format((int) ($item['km_start'] ?? 0), 0, ',', '.') ?> km</strong>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>KM Akhir</span><strong><?= number_format((int) ($item['km_end'] ?? 0), 0, ',', '.') ?> km</strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between text-primary">
                    <span>Total Jarak</span><strong><?= number_format((int) ($item['distance_km'] ?? 0), 0, ',', '.') ?> km</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card bg-white rounded-10 border border-white">
    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title mb-0">Catatan</h4>
    </div>
    <div class="p-20 text-body"><?= nl2br(htmlspecialchars($item['notes'] ?? '-')) ?></div>
</div>
