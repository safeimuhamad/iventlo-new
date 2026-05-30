<?php
$totalInternal = 0;
$totalPartner = 0;

foreach ($items as $item) {
    if (($item['source_type'] ?? '') === 'internal') {
        $totalInternal++;
    }

    if (($item['source_type'] ?? '') === 'partner') {
        $totalPartner++;
    }
}

$statusRental = $rental['status_rental'] ?? 'draft';

$statusClass = match ($statusRental) {
    'draft' => 'bg-secondary bg-opacity-10 text-secondary',
    'scheduled' => 'bg-primary bg-opacity-10 text-primary',
    'on_rent' => 'bg-warning bg-opacity-10 text-warning',
    'completed' => 'bg-success bg-opacity-10 text-success',
    'cancelled' => 'bg-danger bg-opacity-10 text-danger',
    default => 'bg-secondary bg-opacity-10 text-secondary'
};
?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Detail Rental
            </h3>

            <p class="mb-0 text-body">
                <?= htmlspecialchars($rental['no_rental'] ?? '-') ?>
            </p>
        </div>

        <div class="d-flex justify-content-between justify-content-md-end align-items-center flex-wrap gap-3">

            <a href="<?= url('rentals') ?>" class="btn btn-light erp-btn">
                <i class="ri-arrow-left-line me-1"></i>
                Kembali
            </a>

            <?php if (in_array(($rental['status_rental'] ?? ''), ['scheduled', 'on_rent'])): ?>

                <div class="dropdown">

                    <button
                        class="btn btn-primary text-white dropdown-toggle erp-btn"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >
                        <i class="ri-settings-3-line me-1"></i>
                        Actions
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end erp-dropdown-menu">

                        <?php if (($rental['status_rental'] ?? '') === 'scheduled'): ?>
                            <li>
                                <a
                                    href="<?= url('rentals-process-out') ?>?id=<?= $rental['id'] ?>"
                                    class="dropdown-item erp-dropdown-item"
                                    onclick="return confirm('Proses unit keluar untuk rental ini?')"
                                >
                                    <div class="erp-dropdown-title text-warning">
                                        <i class="ri-truck-line me-2"></i>
                                        Proses Keluar
                                    </div>

                                    <div class="erp-dropdown-desc">
                                        Update status unit menjadi keluar
                                    </div>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (($rental['status_rental'] ?? '') === 'on_rent'): ?>
                            <li>
                                <a
                                    href="<?= url('rentals-process-return') ?>?id=<?= $rental['id'] ?>"
                                    class="dropdown-item erp-dropdown-item"
                                    onclick="return confirm('Proses unit kembali untuk rental ini?')"
                                >
                                    <div class="erp-dropdown-title text-success">
                                        <i class="ri-arrow-go-back-line me-2"></i>
                                        Proses Kembali
                                    </div>

                                    <div class="erp-dropdown-desc">
                                        Update status unit menjadi kembali
                                    </div>
                                </a>
                            </li>
                        <?php endif; ?>

                    </ul>

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="row g-4">

        <div class="col-md-3">
            <div class="erp-detail-label">Status Rental</div>

            <div class="erp-detail-value">
                <span class="default-badge <?= $statusClass ?>">
                    <?= htmlspecialchars(ucwords(str_replace('_', ' ', $statusRental))) ?>
                </span>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">Total Unit</div>

            <div class="erp-detail-value">
                <?= count($items) ?> Unit
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">Unit Internal</div>

            <div class="erp-detail-value">
                <?= $totalInternal ?> Unit
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">Unit Rekanan</div>

            <div class="erp-detail-value">
                <?= $totalPartner ?> Unit
            </div>
        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title">
            Informasi Rental
        </h4>
    </div>

    <div class="p-20">

        <div class="row g-4">

            <div class="col-md-3">
                <div class="erp-detail-label">Customer</div>
                <div class="erp-detail-value">
                    <?= htmlspecialchars($rental['customer_name'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">No. HP</div>
                <div class="erp-detail-value">
                    <?= htmlspecialchars($rental['customer_phone'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">Tanggal Rental</div>
                <div class="erp-detail-value">
                    <?= !empty($rental['tanggal_rental'])
                        ? date('d M Y', strtotime($rental['tanggal_rental']))
                        : '-' ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">Tanggal Selesai</div>
                <div class="erp-detail-value">
                    <?= !empty($rental['tanggal_selesai'])
                        ? date('d M Y', strtotime($rental['tanggal_selesai']))
                        : '-' ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="erp-detail-label">Catatan</div>
                <div class="erp-detail-value">
                    <?= htmlspecialchars($rental['catatan'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="erp-detail-label">Lokasi</div>
                <div class="erp-detail-value">
                    <?= nl2br(htmlspecialchars($rental['lokasi'] ?? '-')) ?>
                </div>
            </div>

        </div>

    </div>

</div>
<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title">
            Item Order
        </h4>
    </div>

    <div class="default-table-area mx-minus-1">

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Item</th>
                        <th class="text-end">Qty</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($quotationItems)): ?>

                        <?php foreach ($quotationItems as $index => $item): ?>

                            <tr>
                                <td>
                                    <?= $index + 1 ?>
                                </td>

                                <td class="fw-semibold">
                                    <?= htmlspecialchars($item['item_name'] ?? '-') ?>
                                </td>

                                <td class="text-end fw-semibold">
                                    <?= number_format((float) ($item['qty'] ?? 0), 0, ',', '.') ?>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="3" class="text-center text-body py-4">
                                Belum ada item order.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20 border-bottom">

        <div>
            <h4 class="erp-detail-section-title">
                Unit Rental
            </h4>
        </div>

        <a
            href="<?= url('rental-items-create') ?>?rental_id=<?= $rental['id'] ?>"
            class="btn btn-primary text-white erp-btn"
        >
            + Tambah Unit
        </a>

    </div>

    <div class="default-table-area mx-minus-1">

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead>
                    <tr>
                        <th>Source</th>
                        <th>Kode Unit</th>
                        <th>Nama Unit</th>
                        <th>Brand</th>
                        <th>Kategori</th>
                        <th>Vendor</th>
                        <th class="text-end">Modal Rekanan</th>
                        <th>Status Item</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($items)): ?>

                        <?php foreach ($items as $item): ?>

                            <?php
                            $sourceType = $item['source_type'] ?? '-';

                            $kodeUnit = $sourceType === 'internal'
                                ? ($item['kode_unit'] ?? '-')
                                : '-';

                            $namaUnit = $sourceType === 'internal'
                                ? ($item['nama_unit'] ?? '-')
                                : ($item['partner_unit_name'] ?? '-');

                            $brand = $item['brand'] ?? $item['partner_unit_brand'] ?? '-';
                            $kategori = $item['kategori'] ?? $item['partner_unit_category'] ?? '-';

                            $itemStatus = $item['status_item'] ?? 'booked';

                            $itemClass = match ($itemStatus) {
                                'booked' => 'bg-primary bg-opacity-10 text-primary',
                                'out' => 'bg-warning bg-opacity-10 text-warning',
                                'returned' => 'bg-success bg-opacity-10 text-success',
                                'problem' => 'bg-danger bg-opacity-10 text-danger',
                                default => 'bg-secondary bg-opacity-10 text-secondary'
                            };
                            ?>

                            <tr>
                                <td>
                                    <?= htmlspecialchars(ucfirst($sourceType)) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($kodeUnit) ?>
                                </td>

                                <td class="fw-semibold">
                                    <?= htmlspecialchars($namaUnit) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($brand) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($kategori) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($item['partner_name'] ?? '-') ?>
                                </td>

                                <td class="text-end fw-semibold">
                                    <?php if ((float) ($item['partner_cost'] ?? 0) > 0): ?>
                                        Rp <?= number_format((float) ($item['partner_cost'] ?? 0), 0, ',', '.') ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <span class="default-badge <?= $itemClass ?>">
                                        <?= htmlspecialchars(ucwords(str_replace('_', ' ', $itemStatus))) ?>
                                    </span>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="8" class="text-center text-body py-4">
                                Belum ada unit untuk rental ini.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20 border-bottom">

        <div>
            <h4 class="erp-detail-section-title">
                Assignment Teknisi
            </h4>
        </div>

        <a
            href="<?= url('rentals-assign-technician') ?>?rental_id=<?= $rental['id'] ?>"
            class="btn btn-primary text-white erp-btn"
        >
            + Assign Teknisi
        </a>

    </div>

    <div class="default-table-area mx-minus-1">

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead>
                    <tr>
                        <th>Teknisi</th>
                        <th>No. HP</th>
                        <th>Jenis Pekerjaan</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($technicianAssignments)): ?>

                        <?php foreach ($technicianAssignments as $assign): ?>

                            <?php
                            $taskLabel = match ($assign['task_type'] ?? '') {
                                'kirim_pasang' => 'Kirim / Pasang',
                                'bongkar' => 'Bongkar',
                                default => '-'
                            };

                            $assignStatus = $assign['status'] ?? 'assigned';

                            $assignStatusClass = match ($assignStatus) {
                                'assigned' => 'bg-primary bg-opacity-10 text-primary',
                                'done' => 'bg-success bg-opacity-10 text-success',
                                'cancelled' => 'bg-danger bg-opacity-10 text-danger',
                                default => 'bg-secondary bg-opacity-10 text-secondary'
                            };
                            ?>

                            <tr>
                                <td class="fw-semibold">
                                    <?= htmlspecialchars($assign['name'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($assign['phone'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($taskLabel) ?>
                                </td>

                                <td>
                                    <?= !empty($assign['scheduled_date'])
                                        ? date('d M Y', strtotime($assign['scheduled_date']))
                                        : '-' ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($assign['scheduled_time'] ?? '-') ?>
                                </td>

                                <td>
                                    <span class="default-badge <?= $assignStatusClass ?>">
                                        <?= htmlspecialchars(ucwords(str_replace('_', ' ', $assignStatus))) ?>
                                    </span>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="6" class="text-center text-body py-4">
                                Belum ada teknisi yang diassign.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>