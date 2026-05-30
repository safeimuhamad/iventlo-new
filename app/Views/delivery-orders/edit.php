<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger mb-4">
        <?= $_SESSION['error']; ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>
<?php
$isBongkar = ($deliveryOrder['sj_type'] ?? 'pasang') === 'bongkar';

$typeLabel = $isBongkar ? 'Surat Jalan Bongkar' : 'Surat Jalan Pasang / Kirim';
$tanggalLabel = $isBongkar ? 'Tanggal Bongkar' : 'Tanggal Kirim';
$jamLabel = $isBongkar ? 'Jam Bongkar' : 'Jam Kirim';
?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Edit Surat Jalan
            </h3>

            <p class="mb-0 text-body">
                Perbarui data surat jalan, jadwal pengiriman atau bongkar, kendaraan, driver, dan status.
            </p>
        </div>

        <a
            href="<?= url('delivery-orders-show') ?>?id=<?= htmlspecialchars($deliveryOrder['id']) ?>"
            class="btn btn-light erp-btn"
        >
            <i class="ri-arrow-left-line me-1"></i>
            Kembali
        </a>

    </div>

</div>

<form method="POST" action="<?= url('delivery-orders-update') ?>">

    <input type="hidden" name="id" value="<?= htmlspecialchars($deliveryOrder['id']) ?>">

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Informasi Surat Jalan
            </h4>
        </div>

        <div class="p-20">

            <div class="row g-4">

                <div class="col-md-3">
                    <label class="erp-detail-label">
                        Nomor Surat Jalan
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="<?= htmlspecialchars($deliveryOrder['no_surat_jalan'] ?? '-') ?>"
                        readonly
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">
                        Rental
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="<?= htmlspecialchars(($deliveryOrder['no_rental'] ?? '-') . ' - ' . ($deliveryOrder['customer_name'] ?? '-')) ?>"
                        readonly
                    >
                </div>

                <div class="col-md-3">
                    <label class="erp-detail-label">
                        Tipe Surat Jalan
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="<?= htmlspecialchars($typeLabel) ?>"
                        readonly
                    >
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">
                        <?= htmlspecialchars($tanggalLabel) ?>
                    </label>

                    <input
                        type="date"
                        name="tanggal_kirim"
                        class="form-control"
                        value="<?= htmlspecialchars($deliveryOrder['tanggal_kirim'] ?? '') ?>"
                    >
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">
                        <?= htmlspecialchars($jamLabel) ?>
                    </label>

                    <input
                        type="time"
                        name="jam_kirim"
                        class="form-control"
                        value="<?= htmlspecialchars($deliveryOrder['jam_kirim'] ?? '') ?>"
                    >
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">
                        Status Surat Jalan
                    </label>

                    <select name="status_sj" class="form-control">
                        <?php
                        $statuses = ['draft', 'sent', 'completed'];
                        foreach ($statuses as $status):
                        ?>
                            <option
                                value="<?= $status ?>"
                                <?= ($deliveryOrder['status_sj'] ?? '') === $status ? 'selected' : '' ?>
                            >
                                <?= ucfirst($status) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>

        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Kendaraan & Driver
            </h4>
        </div>

        <div class="p-20">

            <div class="row g-4">

                <div class="col-md-4">
                    <label class="erp-detail-label">
                        Kendaraan Operasional
                    </label>

                    <select name="vehicle_id" class="form-select">
                        <option value="">
                            Pilih Kendaraan
                        </option>

                        <?php foreach ($vehicles as $vehicle): ?>
                            <option
                                value="<?= $vehicle['id'] ?>"
                                <?= (($deliveryOrder['vehicle_id'] ?? '') == $vehicle['id']) ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($vehicle['vehicle_code']) ?>
                                -
                                <?= htmlspecialchars($vehicle['vehicle_name']) ?>
                                -
                                <?= htmlspecialchars($vehicle['plate_number']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">
                        Nama Driver
                    </label>

                    <input
                        type="text"
                        name="driver_name"
                        class="form-control"
                        value="<?= htmlspecialchars($deliveryOrder['driver_name'] ?? '') ?>"
                    >
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">
                        KM Awal
                    </label>

                    <input
                        type="number"
                        name="km_start"
                        class="form-control"
                        min="0"
                        value="<?= (int) ($deliveryOrder['km_start'] ?? 0) ?>"
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
                        name="catatan"
                        class="form-control"
                        rows="8"
                    ><?= htmlspecialchars($deliveryOrder['catatan'] ?? '') ?></textarea>

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
                        <span>No. SJ</span>
                        <strong>
                            <?= htmlspecialchars($deliveryOrder['no_surat_jalan'] ?? '-') ?>
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Status</span>
                        <strong>
                            <?= htmlspecialchars(ucfirst($deliveryOrder['status_sj'] ?? '-')) ?>
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Tipe</span>
                        <strong>
                            <?= htmlspecialchars($typeLabel) ?>
                        </strong>
                    </div>

                    <hr>

                    <div class="text-body">
                        Perubahan data akan memperbarui jadwal dan informasi operasional surat jalan.
                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white p-20">

        <div class="d-flex justify-content-end flex-wrap gap-3">

            <a
                href="<?= url('delivery-orders-show') ?>?id=<?= htmlspecialchars($deliveryOrder['id']) ?>"
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