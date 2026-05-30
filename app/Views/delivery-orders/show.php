<?php
$isBongkar = ($deliveryOrder['sj_type'] ?? 'pasang') === 'bongkar';

$typeLabel    = $isBongkar ? 'Bongkar' : 'Pasang / Kirim';
$tanggalLabel = $isBongkar ? 'Tanggal Bongkar' : 'Tanggal Kirim';
$jamLabel     = $isBongkar ? 'Jam Bongkar' : 'Jam Kirim';

$typeClass = $isBongkar
    ? 'bg-warning bg-opacity-10 text-warning'
    : 'bg-primary bg-opacity-10 text-primary';
?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Detail Surat Jalan
            </h3>

            <p class="mb-0 text-body">
                <?= htmlspecialchars($deliveryOrder['no_surat_jalan'] ?? '-') ?>
            </p>
        </div>

        <div class="d-flex justify-content-between justify-content-md-end align-items-center flex-wrap gap-3">

            <a
                href="<?= url('delivery-orders') ?>"
                class="btn btn-light erp-btn"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Kembali
            </a>

            <a
                href="<?= url('delivery-orders-edit') ?>?id=<?= $deliveryOrder['id'] ?>"
                class="btn btn-outline-primary erp-btn"
            >
                <i class="ri-edit-line me-1"></i>
                Edit
            </a>

            <button
                type="button"
                class="btn btn-outline-primary erp-btn"
                onclick="openPrintPopup('<?= url('delivery-orders-print') ?>?id=<?= $deliveryOrder['id'] ?>')"
            >
                <i class="ri-printer-line me-1"></i>
                Print
            </button>

        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="row g-4">

        <div class="col-md-3">
            <div class="erp-detail-label">
                Jenis Surat Jalan
            </div>

            <div class="erp-detail-value">
                <span class="default-badge <?= $typeClass ?>">
                    <?= htmlspecialchars($typeLabel) ?>
                </span>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">
                Customer
            </div>

            <div class="erp-detail-value">
                <?= htmlspecialchars($deliveryOrder['customer_name'] ?? '-') ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">
                <?= htmlspecialchars($tanggalLabel) ?>
            </div>

            <div class="erp-detail-value">
                <?= !empty($deliveryOrder['tanggal_kirim'])
                    ? date('d M Y', strtotime($deliveryOrder['tanggal_kirim']))
                    : '-' ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">
                Teknisi
            </div>

            <div class="erp-detail-value">
                <?= htmlspecialchars($deliveryOrder['technician_names'] ?? '-') ?>
            </div>
        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title">
            Informasi Surat Jalan
        </h4>
    </div>

    <div class="p-20">

        <div class="row g-4">

            <div class="col-md-3">
                <div class="erp-detail-label">
                    No Surat Jalan
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($deliveryOrder['no_surat_jalan'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">
                    Jenis Surat Jalan
                </div>

                <div class="erp-detail-value">
                    <span class="default-badge <?= $typeClass ?>">
                        <?= htmlspecialchars($typeLabel) ?>
                    </span>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">
                    <?= htmlspecialchars($tanggalLabel) ?>
                </div>

                <div class="erp-detail-value">
                    <?= !empty($deliveryOrder['tanggal_kirim'])
                        ? date('d M Y', strtotime($deliveryOrder['tanggal_kirim']))
                        : '-' ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">
                    <?= htmlspecialchars($jamLabel) ?>
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($deliveryOrder['jam_kirim'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">
                    No Rental
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($deliveryOrder['no_rental'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">
                    Customer
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($deliveryOrder['customer_name'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">
                    No HP
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($deliveryOrder['customer_phone'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">
                    Teknisi
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($deliveryOrder['technician_names'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">
                    Kode Kendaraan
                </div>

                <div class="erp-detail-value">
                    <?= !empty($deliveryOrder['vehicle_id'])
                        ? htmlspecialchars($deliveryOrder['vehicle_code'] ?? '-')
                        : '-' ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">
                    Nama Kendaraan
                </div>

                <div class="erp-detail-value">
                    <?= !empty($deliveryOrder['vehicle_id'])
                        ? htmlspecialchars($deliveryOrder['vehicle_name'] ?? '-')
                        : '-' ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">
                    No Polisi
                </div>

                <div class="erp-detail-value">
                    <?= !empty($deliveryOrder['vehicle_id'])
                        ? htmlspecialchars($deliveryOrder['plate_number'] ?? '-')
                        : '-' ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">
                    Driver
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($deliveryOrder['driver_name'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">
                    KM Awal
                </div>

                <div class="erp-detail-value">
                    <?= number_format((int) ($deliveryOrder['km_start'] ?? 0), 0, ',', '.') ?> km
                </div>
            </div>

            <div class="col-md-6">
                <div class="erp-detail-label">
                    Lokasi
                </div>

                <div class="erp-detail-value">
                    <?= nl2br(htmlspecialchars($deliveryOrder['lokasi'] ?? '-')) ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="erp-detail-label">
                    Catatan
                </div>

                <div class="erp-detail-value">
                    <?= nl2br(htmlspecialchars($deliveryOrder['catatan'] ?? '-')) ?>
                </div>
            </div>

        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title">
            Daftar Unit
        </h4>
    </div>

    <div class="default-table-area mx-minus-1">

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Unit</th>
                        <th>Brand</th>
                        <th>Kategori</th>
                        <th class="text-end">Jumlah</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($items)): ?>

                        <?php foreach ($items as $index => $item): ?>

                            <tr>
                                <td>
                                    <?= $index + 1 ?>
                                </td>

                                <td class="fw-semibold">
                                    <?= htmlspecialchars($item['unit_name'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($item['brand'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($item['kategori'] ?? '-') ?>
                                </td>

                                <td class="text-end fw-semibold">
                                    <?= number_format((float) ($item['jumlah'] ?? 0), 0, ',', '.') ?> Unit
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="5" class="text-center text-body py-4">
                                Belum ada unit.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<script>
    function openPrintPopup(url) {
        const width = 900;
        const height = 700;

        const left = (screen.width / 2) - (width / 2);
        const top = (screen.height / 2) - (height / 2);

        window.open(
            url,
            'printWindow',
            `width=${width},height=${height},top=${top},left=${left},resizable=yes,scrollbars=yes`
        );
    }
</script>