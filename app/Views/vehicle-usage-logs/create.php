
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
                Tambah Log Pemakaian Kendaraan
            </h3>

            <p class="mb-0 text-body">
                Catat aktivitas penggunaan kendaraan operasional untuk kebutuhan monitoring dan histori perjalanan.
            </p>
        </div>

        <a
            href="<?= url('vehicle-usage-logs') ?>"
            class="btn btn-light erp-btn"
        >
            <i class="ri-arrow-left-line me-1"></i>
            Kembali
        </a>

    </div>

</div>

<form
    action="<?= url('vehicle-usage-logs-store') ?>"
    method="POST"
>

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Informasi Pemakaian Kendaraan
            </h4>
        </div>

        <div class="p-20">

            <div class="row g-4">

                <div class="col-md-4">
                    <label class="erp-detail-label">
                        Kendaraan
                    </label>

                    <select
                        name="vehicle_id"
                        class="form-select"
                        required
                    >
                        <option value="">
                            Pilih Kendaraan
                        </option>

                        <?php foreach ($vehicles as $vehicle): ?>
                            <option value="<?= $vehicle['id'] ?>">
                                <?= htmlspecialchars($vehicle['vehicle_code']) ?>
                                -
                                <?= htmlspecialchars($vehicle['vehicle_name']) ?>
                                (<?= htmlspecialchars($vehicle['plate_number']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">
                        Tanggal Pemakaian
                    </label>

                    <input
                        type="date"
                        name="usage_date"
                        class="form-control"
                        value="<?= date('Y-m-d') ?>"
                        required
                    >
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">
                        Jenis Aktivitas
                    </label>

                    <select
                        name="activity_type"
                        class="form-select"
                        required
                    >
                        <option value="delivery">Pengiriman</option>
                        <option value="pickup">Penarikan</option>
                        <option value="survey">Survey</option>
                        <option value="service">Service</option>
                        <option value="other">Lainnya</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">
                        Tujuan / Lokasi
                    </label>

                    <input
                        type="text"
                        name="destination"
                        class="form-control"
                        placeholder="Contoh: PT ABC Jakarta"
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">
                        Nama Driver
                    </label>

                    <input
                        type="text"
                        name="driver_name"
                        class="form-control"
                        placeholder="Nama driver"
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">
                        KM Awal
                    </label>

                    <input
                        type="number"
                        name="km_start"
                        class="form-control"
                        min="0"
                        required
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">
                        KM Akhir
                    </label>

                    <input
                        type="number"
                        name="km_end"
                        class="form-control"
                        min="0"
                        required
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
                        placeholder="Catatan pemakaian kendaraan"
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
                        <span>Tanggal</span>

                        <strong>
                            <?= date('d M Y') ?>
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Status</span>

                        <strong>
                            Draft
                        </strong>
                    </div>

                    <hr>

                    <div class="text-body">
                        Log kendaraan digunakan untuk monitoring operasional,
                        histori perjalanan, dan evaluasi biaya kendaraan.
                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white p-20">

        <div class="d-flex justify-content-end flex-wrap gap-3">

            <a
                href="<?= url('vehicle-usage-logs') ?>"
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