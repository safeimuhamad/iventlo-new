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
                Tambah Surat Jalan
            </h3>

            <p class="mb-0 text-body">
                Buat surat jalan untuk proses pengiriman, pemasangan, atau pembongkaran unit rental.
            </p>
        </div>

        <a
            href="<?= url('delivery-orders') ?>"
            class="btn btn-light erp-btn"
        >
            <i class="ri-arrow-left-line me-1"></i>
            Kembali
        </a>

    </div>

</div>

<form
    method="POST"
    action="<?= url('delivery-orders-store') ?>"
>

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
                        name="no_surat_jalan"
                        class="form-control"
                        value="<?= htmlspecialchars($nomor ?? '') ?>"
                        readonly
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">
                        Rental <span class="text-danger">*</span>
                    </label>

                    <select
                        name="rental_id"
                        class="form-control"
                        required
                    >
                        <option value="">
                            -- Pilih Rental --
                        </option>

                        <?php foreach ($rentals as $rental): ?>
                            <option
                                value="<?= $rental['id'] ?>"
                                data-status="<?= htmlspecialchars($rental['status_rental']) ?>"
                                data-tanggal-rental="<?= htmlspecialchars($rental['tanggal_rental']) ?>"
                                data-tanggal-selesai="<?= htmlspecialchars($rental['tanggal_selesai']) ?>"
                                data-jam-kirim="<?= htmlspecialchars($rental['jam_kirim'] ?? '') ?>"
                                data-jam-bongkar="<?= htmlspecialchars($rental['jam_bongkar'] ?? '') ?>"
                            >
                                <?= htmlspecialchars($rental['no_rental']) ?>
                                -
                                <?= htmlspecialchars($rental['customer_name']) ?>
                                (
                                <?= $rental['status_rental'] === 'on_rent' ? 'Bongkar' : 'Pasang' ?>
                                )
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="col-md-3">
                    <label class="erp-detail-label">
                        Tipe Surat Jalan
                    </label>

                    <input
                        type="text"
                        id="sj_type_label"
                        class="form-control"
                        readonly
                    >
                </div>

                <div class="col-md-6">
                    <label
                        id="tanggal_label"
                        class="erp-detail-label"
                    >
                        Tanggal Kirim
                    </label>

                    <input
                        type="date"
                        name="tanggal_kirim"
                        id="tanggal_kirim"
                        class="form-control"
                    >
                </div>

                <div class="col-md-6">
                    <label
                        id="jam_label"
                        class="erp-detail-label"
                    >
                        Jam Kirim
                    </label>

                    <input
                        type="time"
                        name="jam_kirim"
                        id="jam_kirim"
                        class="form-control"
                    >
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

                    <select
                        name="vehicle_id"
                        class="form-select"
                    >
                        <option value="">
                            Pilih Kendaraan
                        </option>

                        <?php foreach ($vehicles as $vehicle): ?>
                            <option value="<?= $vehicle['id'] ?>">
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
                        placeholder="Nama driver"
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
                        value="0"
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
                        <span>No. SJ</span>
                        <strong>
                            <?= htmlspecialchars($nomor ?? '-') ?>
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Status Awal</span>
                        <strong>
                            Draft
                        </strong>
                    </div>

                    <hr>

                    <div class="text-body">
                        Surat jalan akan mengikuti tipe rental yang dipilih,
                        yaitu pasang atau bongkar.
                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white p-20">

        <div class="d-flex justify-content-end flex-wrap gap-3">

            <a
                href="<?= url('delivery-orders') ?>"
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
    const rentalSelect = document.querySelector('select[name="rental_id"]');
    const sjTypeLabel = document.getElementById('sj_type_label');
    const tanggalLabel = document.getElementById('tanggal_label');
    const jamLabel = document.getElementById('jam_label');
    const tanggalInput = document.getElementById('tanggal_kirim');
    const jamInput = document.getElementById('jam_kirim');

    function updateSjInfo() {
        const option = rentalSelect.options[rentalSelect.selectedIndex];

        if (!option || !option.value) {
            sjTypeLabel.value = '';
            tanggalLabel.innerText = 'Tanggal Kirim';
            jamLabel.innerText = 'Jam Kirim';
            tanggalInput.value = '';
            jamInput.value = '';
            return;
        }

        const status = option.dataset.status;

        if (status === 'on_rent') {
            sjTypeLabel.value = 'Surat Jalan Bongkar';
            tanggalLabel.innerText = 'Tanggal Bongkar';
            jamLabel.innerText = 'Jam Bongkar';
            tanggalInput.value = option.dataset.tanggalSelesai || '';
            jamInput.value = option.dataset.jamBongkar || '';
        } else {
            sjTypeLabel.value = 'Surat Jalan Pasang / Kirim';
            tanggalLabel.innerText = 'Tanggal Kirim';
            jamLabel.innerText = 'Jam Kirim';
            tanggalInput.value = option.dataset.tanggalRental || '';
            jamInput.value = option.dataset.jamKirim || '';
        }
    }

    rentalSelect.addEventListener('change', updateSjInfo);
    updateSjInfo();
});
</script>