                <div class="main-content-container overflow-hidden">
                    <div class="row">
                        <div class="col-xxl-6 col-xxxl-12">
                            <div class="card bg-white p-20 pb-0 rounded-10 border border-white mb-4">
                                <h3 class="mb-20">Operasional Overview</h3>
                                <div class="row" style="--bs-gutter-x: 20px;">

                                    <!-- Rental Aktif -->
                                    <div class="col-md-6">
                                        <div class="card bg-body-bg p-20 rounded-10 border border-white mb-20">

                                            <div class="d-flex">
                                                <div class="flex-grow-1">
                                                    <h3 class="mb-10">Rental Aktif</h3>

                                                    <h2 class="fs-26 fw-medium mb-0 lh-1">
                                                        <?= (int) ($rentalActive ?? 0) ?>
                                                    </h2>
                                                </div>

                                                <div class="flex-shrink-0 ms-sm-3">
                                                    <div class="bg-primary text-center rounded-circle d-flex align-items-center justify-content-center"
                                                    style="width: 65px; height: 65px;">

                                                    <span class="material-symbols-outlined text-white fs-28">
                                                        inventory_2
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center"
                                        style="margin-top: 23px;">

                                        <p class="mb-0 fs-14">
                                            Rental sedang berjalan
                                        </p>

                                        <span class="badge bg-primary bg-opacity-10 text-primary">
                                            ACTIVE
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Kirim Hari Ini -->
                            <div class="col-md-6">
                                <div class="card bg-body-bg p-20 rounded-10 border border-white mb-20">

                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <h3 class="mb-10">Kirim Hari Ini</h3>

                                            <h2 class="fs-26 fw-medium mb-0 lh-1">
                                                <?= (int) ($todayDelivery ?? 0) ?>
                                            </h2>
                                        </div>

                                        <div class="flex-shrink-0 ms-sm-3">
                                            <div class="bg-success text-center rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 65px; height: 65px;">

                                            <span class="material-symbols-outlined text-white fs-28">
                                                local_shipping
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center"
                                style="margin-top: 23px;">

                                <p class="mb-0 fs-14">
                                    Pengiriman unit hari ini
                                </p>

                                <span class="badge bg-success bg-opacity-10 text-success">
                                    DELIVERY
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Bongkar Hari Ini -->
                    <div class="col-md-6">
                        <div class="card bg-body-bg p-20 rounded-10 border border-white mb-20">

                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <h3 class="mb-10">Bongkar Hari Ini</h3>

                                    <h2 class="fs-26 fw-medium mb-0 lh-1">
                                        <?= (int) ($todayPickup ?? 0) ?>
                                    </h2>
                                </div>

                                <div class="flex-shrink-0 ms-sm-3">
                                    <div class="bg-warning text-center rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 65px; height: 65px;">

                                    <span class="material-symbols-outlined text-white fs-28">
                                        assignment_return
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center"
                        style="margin-top: 23px;">

                        <p class="mb-0 fs-14">
                            Pengambilan unit hari ini
                        </p>

                        <span class="badge bg-warning bg-opacity-10 text-warning">
                            PICKUP
                        </span>
                    </div>
                </div>
            </div>

            <!-- Unit Maintenance -->
            <div class="col-md-6">
                <div class="card bg-body-bg p-20 rounded-10 border border-white mb-20">

                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <h3 class="mb-10">Unit Maintenance</h3>

                            <h2 class="fs-26 fw-medium mb-0 lh-1">
                                <?= count($unitMaintenanceDueList ?? []) ?>
                            </h2>
                        </div>

                        <div class="flex-shrink-0 ms-sm-3">
                            <div class="bg-danger text-center rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 65px; height: 65px;">

                            <span class="material-symbols-outlined text-white fs-28">
                                build
                            </span>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center"
                style="margin-top: 23px;">

                <p class="mb-0 fs-14">
                    Unit wajib maintenance
                </p>

                <span class="badge bg-danger bg-opacity-10 text-danger">
                    MAINTENANCE
                </span>
            </div>
        </div>
    </div>

</div>
</div>
</div>
<div class="col-xxl-6 col-xxxl-12">
    <div class="card bg-white p-20 rounded-10 border border-white mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-0">
            <h3>Aktivitas Operasional Hari Ini</h3>
            <div class="dropdown select-dropdown without-border">
                <button class="dropdown-toggle bg-transparent text-secondary fs-15" data-bs-toggle="dropdown" aria-expanded="false">
                    Hari Ini
                </button>
            </div>
        </div>
        <div class="mt-4">

            <?php if (!empty($todayDeliveriesList)): ?>

            <?php foreach (array_slice($todayDeliveriesList, 0, 5) as $item): ?>

            <div class="d-flex border-bottom pb-3 mb-3">

                <div class="flex-shrink-0">
                    <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                    style="width: 50px; height: 50px;">

                    <span class="material-symbols-outlined text-success">
                        local_shipping
                    </span>
                </div>
            </div>

            <div class="flex-grow-1 ms-3">

                <div class="d-flex justify-content-between align-items-start">

                    <div>
                        <h4 class="fs-15 mb-1">
                            <?= htmlspecialchars($item['customer_name'] ?? '-') ?>
                        </h4>

                        <span class="text-muted fs-14">
                            <?= htmlspecialchars($item['lokasi'] ?? '-') ?>
                        </span>
                    </div>

                    <span class="badge bg-success bg-opacity-10 text-success">
                        Kirim
                    </span>
                </div>

                <div class="mt-2 fs-14 text-muted">
                    SJ:
                    <?= htmlspecialchars($item['no_rental'] ?? '-') ?>

                    •

                    <?= htmlspecialchars($item['jam_kirim'] ?? '-') ?>
                </div>

            </div>
        </div>

        <?php endforeach; ?>

        <?php else: ?>

        <div class="text-center py-5">

            <span class="material-symbols-outlined text-muted mb-2"
            style="font-size: 50px;">

            event_busy
        </span>

        <p class="text-muted mb-0">
            Tidak ada aktivitas operasional hari ini.
        </p>

    </div>

    <?php endif; ?>

</div>
</div>
</div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">
        <h4 class="fs-18 fw-semibold mb-0">Live Unit Availability</h4>

        <a href="<?= url('units') ?>"
            class="text-decoration-none fs-15 hover-text">

            Lihat Semua
        </a>
    </div>

    <div class="p-20 pt-0">

        <div class="row g-3">

            <?php if (!empty($unitAvailabilityBoard)): ?>

                <?php foreach ($unitAvailabilityBoard as $item): ?>

                    <div class="col-xl-3 col-md-6">

                        <div class="border rounded-10 p-3 h-100">

                            <div class="d-flex justify-content-between align-items-center mb-3">

                                <h4 class="fs-18 mb-0">
                                    <?= htmlspecialchars($item['tipe_unit'] ?? '-') ?>
                                </h4>

                                <span class="badge bg-primary bg-opacity-10 text-primary">

                                    <?= (int) ($item['total_unit'] ?? 0) ?>

                                </span>

                            </div>

                            <div class="d-flex justify-content-between mb-2">

                                <span class="text-muted">
                                    Available
                                </span>

                                <strong class="text-success">
                                    <?= (int) ($item['available_count'] ?? 0) ?>
                                </strong>

                            </div>

                            <div class="progress mb-3"
                                style="height: 6px;">

                                <div class="progress-bar bg-success"
                                    style="width:
                                    <?= ($item['total_unit'] ?? 0) > 0
                                        ? (($item['available_count'] ?? 0) / $item['total_unit']) * 100
                                        : 0 ?>%">
                                </div>

                            </div>

                            <div class="row text-center">

                                <div class="col-4">

                                    <small class="text-muted d-block">
                                        Rent
                                    </small>

                                    <strong class="text-primary">
                                        <?= (int) ($item['rented_count'] ?? 0) ?>
                                    </strong>

                                </div>

                                <div class="col-4">

                                    <small class="text-muted d-block">
                                        Maint
                                    </small>

                                    <strong class="text-warning">
                                        <?= (int) ($item['maintenance_count'] ?? 0) ?>
                                    </strong>

                                </div>

                                <div class="col-4">

                                    <small class="text-muted d-block">
                                        Broken
                                    </small>

                                    <strong class="text-danger">
                                        <?= (int) ($item['broken_count'] ?? 0) ?>
                                    </strong>

                                </div>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <div class="col-12">

                    <div class="text-center py-5 text-muted">

                        Belum ada data unit.

                    </div>

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>
    </div>
</div>
<div class="row">
    <div class="col-xxl-9 col-lg-8">
        <div class="card bg-white rounded-10 border border-white mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">
                <h3>Jadwal Operasional Hari Ini</h3>

                <div class="dropdown select-dropdown without-border">
                    <button class="dropdown-toggle bg-transparent text-secondary fs-15" data-bs-toggle="dropdown" aria-expanded="false">
                        Hari Ini
                    </button>
                </div>
            </div>

            <div class="default-table-area mx-minus-1 table-all-projects">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>No SJ</th>
                                <th>Customer</th>
                                <th>Jadwal</th>
                                <th>Teknisi</th>
                                <th>Kendaraan</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php if (!empty($todayDeliveriesList)): ?>

                            <?php foreach ($todayDeliveriesList as $item): ?>

                            <?php
                                $statusClass = match ($item['status_sj'] ?? '') {
                                    'draft' => 'bg-secondary',
                                    'process' => 'bg-warning text-dark',
                                    'completed' => 'bg-success',
                                    default => 'bg-primary'
                                };

                                $statusLabel = match ($item['status_sj'] ?? '') {
                                    'draft' => 'Draft',
                                    'process' => 'Proses',
                                    'completed' => 'Selesai',
                                    default => 'Aktif'
                                };
                            ?>

                            <tr>

                                <td>
                                    <strong>
                                        <?= htmlspecialchars($item['no_surat_jalan'] ?? '-') ?>
                                    </strong>
                                </td>

                                <td>
                                    <strong>
                                        <?= htmlspecialchars($item['customer_name'] ?? '-') ?>
                                    </strong>

                                    <small class="text-muted d-block">
                                        <?= htmlspecialchars($item['lokasi'] ?? '-') ?>
                                    </small>
                                </td>

                                <td>
                                    <?= htmlspecialchars($item['tanggal_kirim'] ?? '-') ?>
                                    <br>

                                    <small class="text-muted">
                                        <?= htmlspecialchars($item['jam_kirim'] ?? '-') ?>
                                    </small>
                                </td>

                                <td>
                                    <small>
                                        <?= htmlspecialchars($item['technician_names'] ?? '-') ?>
                                    </small>
                                </td>

                                <td>

                                    <?php if (!empty($item['vehicle_code'])): ?>

                                    <strong>
                                        <?= htmlspecialchars($item['vehicle_code']) ?>
                                    </strong>

                                    <br>

                                    <small class="text-muted">
                                        <?= htmlspecialchars($item['plate_number'] ?? '-') ?>
                                    </small>

                                    <?php else: ?>

                                    -

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <div class="d-flex justify-content-end" style="gap: 12px;">

                                        <a href="<?= url('delivery-orders-show', ['id' => $item['id']]) ?>"
                                            class="bg-transparent p-0 border-0">

                                            <i class="material-symbols-outlined fs-16 text-primary">
                                                visibility
                                            </i>
                                        </a>

                                    </div>

                                </td>

                            </tr>

                            <?php endforeach; ?>

                            <?php else: ?>

                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">

                                    Tidak ada jadwal operasional hari ini.

                                </td>
                            </tr>

                            <?php endif; ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-lg-4">
        <div class="card bg-white rounded-10 border border-white p-20 mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3" style="margin-bottom: 36.5px;">
                <h3>Status Unit</h3>

                <div class="dropdown select-dropdown without-border">
                    <button class="dropdown-toggle bg-transparent text-secondary fs-15" data-bs-toggle="dropdown" aria-expanded="false">
                        Realtime
                    </button>
                </div>
            </div>

            <div class="text-center">
                <div class="text-center">

                    <div class="row g-3">

                        <div class="col-6">

                            <div class="bg-success bg-opacity-10 rounded-10 p-3">

                                <span class="material-symbols-outlined text-success mb-2"
                                style="font-size: 35px;">

                                check_circle
                            </span>

                            <h3 class="mb-1">
                                <?= (int) ($unitAvailable ?? 0) ?>
                            </h3>

                            <span class="text-muted fs-14">
                                Available
                            </span>

                        </div>

                    </div>

                    <div class="col-6">

                        <div class="bg-primary bg-opacity-10 rounded-10 p-3">

                            <span class="material-symbols-outlined text-primary mb-2"
                            style="font-size: 35px;">

                            inventory_2
                        </span>

                        <h3 class="mb-1">
                            <?= (int) ($rentalActive ?? 0) ?>
                        </h3>

                        <span class="text-muted fs-14">
                            On Rent
                        </span>

                    </div>

                </div>

                <div class="col-6">

                    <div class="bg-warning bg-opacity-10 rounded-10 p-3">

                        <span class="material-symbols-outlined text-warning mb-2"
                        style="font-size: 35px;">

                        build
                    </span>

                    <h3 class="mb-1">
                        <?= (int) ($unitMaintenance ?? 0) ?>
                    </h3>

                    <span class="text-muted fs-14">
                        Maintenance
                    </span>

                </div>

            </div>

            <div class="col-6">

                <div class="bg-danger bg-opacity-10 rounded-10 p-3">

                    <span class="material-symbols-outlined text-danger mb-2"
                    style="font-size: 35px;">

                    warning
                </span>

                <h3 class="mb-1">
                    <?= (int) ($unitBroken ?? 0) ?>
                </h3>

                <span class="text-muted fs-14">
                    Broken
                </span>

            </div>

        </div>

    </div>

    <div class="mt-4">

        <h2 class="mb-1">
            <?= (int) ($unitTotal ?? 0) ?>
        </h2>

        <span class="text-muted">
            Total Unit Aktif
        </span>

    </div>

</div>
</div>
</div>
</div>
</div>
<div class="row">
<div class="col-lg-4">

    <div class="card bg-white rounded-10 border border-white p-20 mb-4">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-20">

            <div>
                <h4 class="fs-18 fw-semibold mb-1">
                    Kalender Operasional
                </h4>

                <span class="text-muted fs-14">
                    Monitoring aktivitas operasional
                </span>
            </div>

            <span class="badge bg-primary bg-opacity-10 text-primary">
                <?= date('F Y') ?>
            </span>

        </div>

        <!-- CALENDAR -->
        <div class="calendar-container style-two mb-4">

            <div class="calendar-header mb-3">

                <button id="prevMonth" class="change-btn">
                    <i class="ri-arrow-left-s-line"></i>
                </button>

                <div>
                    <select id="monthSelect" class="month-year"></select>
                    <select id="yearSelect" class="month-year"></select>
                </div>

                <button id="nextMonth" class="change-btn">
                    <i class="ri-arrow-right-s-line"></i>
                </button>

            </div>

            <table class="calendar-table">
                <thead>
                    <tr>
                        <th>S</th>
                        <th>M</th>
                        <th>T</th>
                        <th>W</th>
                        <th>T</th>
                        <th>F</th>
                        <th>S</th>
                    </tr>
                </thead>

                <tbody id="calendarBody">
                    <!-- generated by js -->
                </tbody>

            </table>

        </div>

        <!-- LEGEND -->
        <div class="d-flex flex-wrap gap-3 mb-4">

            <div class="d-flex align-items-center gap-2">
                <span class="rounded-circle bg-success d-inline-block"
                    style="width:10px;height:10px;"></span>

                <small class="text-muted">
                    Delivery
                </small>
            </div>

            <div class="d-flex align-items-center gap-2">
                <span class="rounded-circle bg-warning d-inline-block"
                    style="width:10px;height:10px;"></span>

                <small class="text-muted">
                    Pickup
                </small>
            </div>

            <div class="d-flex align-items-center gap-2">
                <span class="rounded-circle bg-danger d-inline-block"
                    style="width:10px;height:10px;"></span>

                <small class="text-muted">
                    Maintenance
                </small>
            </div>

        </div>

        <!-- REMINDER -->
        <div class="border-top pt-3">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <h5 class="fs-16 fw-semibold mb-0">
                    Reminder Operasional
                </h5>

                <span class="badge bg-danger bg-opacity-10 text-danger">
                    <?= 
                    count($unitMaintenanceDueList ?? []) +
                    count($vehicleServiceDueList ?? []) +
                    count($vehicleReminderList ?? [])
                    ?>
                </span>

            </div>

            <ul class="p-0 mb-0 list-unstyled last-child-none working-schedule-list">

                <!-- UNIT -->
                <?php if (!empty($unitMaintenanceDueList)): ?>

                    <?php foreach (array_slice($unitMaintenanceDueList, 0, 2) as $unit): ?>

                        <li class="border-border-color"
                            style="border-bottom: 1px dashed; margin-bottom: 16px; padding-bottom: 16px;">

                            <div class="d-flex">

                                <div class="flex-shrink-0">

                                    <span class="d-block bg-danger rounded-circle position-relative top-9"
                                        style="width: 10px; height: 10px;">
                                    </span>

                                </div>

                                <div class="flex-grow-1 ms-10">

                                    <p class="fs-15 mb-1 lh-1-8">

                                        Unit
                                        <strong>
                                            <?= htmlspecialchars($unit['kode_unit'] ?? '-') ?>
                                        </strong>

                                        wajib maintenance.

                                    </p>

                                    <small class="text-muted">

                                        Pemakaian:
                                        <?= (int) ($unit['total_rental_count'] ?? 0) ?>x

                                    </small>

                                </div>

                            </div>

                        </li>

                    <?php endforeach; ?>

                <?php endif; ?>

                <!-- SERVICE -->
                <?php if (!empty($vehicleServiceDueList)): ?>

                    <?php foreach (array_slice($vehicleServiceDueList, 0, 1) as $vehicle): ?>

                        <li class="border-border-color"
                            style="border-bottom: 1px dashed; margin-bottom: 16px; padding-bottom: 16px;">

                            <div class="d-flex">

                                <div class="flex-shrink-0">

                                    <span class="d-block bg-warning rounded-circle position-relative top-9"
                                        style="width: 10px; height: 10px;">
                                    </span>

                                </div>

                                <div class="flex-grow-1 ms-10">

                                    <p class="fs-15 mb-1 lh-1-8">

                                        Kendaraan
                                        <strong>
                                            <?= htmlspecialchars($vehicle['vehicle_code'] ?? '-') ?>
                                        </strong>

                                        perlu service.

                                    </p>

                                    <small class="text-muted">

                                        <?= htmlspecialchars($vehicle['plate_number'] ?? '-') ?>

                                    </small>

                                </div>

                            </div>

                        </li>

                    <?php endforeach; ?>

                <?php endif; ?>

                <!-- DOKUMEN -->
                <?php if (!empty($vehicleReminderList)): ?>

                    <?php foreach (array_slice($vehicleReminderList, 0, 1) as $vehicle): ?>

                        <li>

                            <div class="d-flex">

                                <div class="flex-shrink-0">

                                    <span class="d-block bg-info rounded-circle position-relative top-9"
                                        style="width: 10px; height: 10px;">
                                    </span>

                                </div>

                                <div class="flex-grow-1 ms-10">

                                    <p class="fs-15 mb-1 lh-1-8">

                                        Dokumen kendaraan
                                        <strong>
                                            <?= htmlspecialchars($vehicle['vehicle_code'] ?? '-') ?>
                                        </strong>

                                    </p>

                                    <small class="text-muted">
                                        STNK / Pajak / KIR mendekati expired
                                    </small>

                                </div>

                            </div>

                        </li>

                    <?php endforeach; ?>

                <?php endif; ?>

            </ul>

        </div>

    </div>

</div>
<div class="col-lg-8">
    <div class="row">
        <div class="col-lg-6">
            <div class="card bg-white rounded-10 border border-white mb-4">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">
                    <h3 class="mb-0">Unit Wajib Maintenance</h3>

                    <a href="<?= url('unit-maintenance') ?>" class="text-decoration-none fs-15 hover-text">
                        Lihat Semua
                    </a>
                </div>

                <div class="default-table-area mx-minus-1">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Unit</th>
                                    <th>Pemakaian</th>
                                    <th>Interval</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if (!empty($unitMaintenanceDueList)): ?>
                                <?php foreach (array_slice($unitMaintenanceDueList, 0, 5) as $unit): ?>
                                <?php
                                    $status = $unit['maintenance_status'] ?? 'due';

                                    $badgeClass = match ($status) {
                                        'process' => 'bg-warning text-dark',
                                        'due' => 'bg-danger',
                                        default => 'bg-secondary'
                                    };

                                    $statusLabel = match ($status) {
                                        'process' => 'Proses',
                                        'due' => 'Wajib',
                                        default => ucfirst($status)
                                    };
                                ?>

                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($unit['kode_unit'] ?? '-') ?></strong><br>
                                        <small class="text-muted">
                                            <?= htmlspecialchars($unit['nama_unit'] ?? '-') ?>
                                        </small>
                                    </td>

                                    <td>
                                        <?= (int) ($unit['total_rental_count'] ?? 0) ?>x
                                    </td>

                                    <td>
                                        Setiap <?= (int) ($unit['maintenance_interval'] ?? 0) ?>x
                                    </td>

                                    <td>
                                        <span class="badge <?= $badgeClass ?>">
                                            <?= $statusLabel ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        Tidak ada unit wajib maintenance.
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card bg-white rounded-10 border border-white mb-4">

                <div class="p-20 pb-0">
                    <h3 class="mb-0">Quick Action</h3>
                </div>

                <div class="p-20">

                    <div class="row g-3">

                        <div class="col-6">
                            <a href="<?= url('rentals-create') ?>" class="text-decoration-none d-block">
                                <div class="bg-primary bg-opacity-10 rounded-10 p-3 text-center h-100">
                                    <span class="material-symbols-outlined text-primary mb-3 d-block" style="font-size: 40px;">
                                        add_box
                                    </span>

                                    <h4 class="fs-18 fw-semibold mb-2 text-dark">
                                        Rental Baru
                                    </h4>

                                    <p class="text-muted fs-14 mb-0">
                                        Buat rental baru
                                    </p>
                                </div>
                            </a>
                        </div>

                        <div class="col-6">
                            <a href="<?= url('delivery-orders-create') ?>" class="text-decoration-none d-block">
                                <div class="bg-success bg-opacity-10 rounded-10 p-3 text-center h-100">
                                    <span class="material-symbols-outlined text-success mb-3 d-block" style="font-size: 40px;">
                                        local_shipping
                                    </span>

                                    <h4 class="fs-18 fw-semibold mb-2 text-dark">
                                        Surat Jalan
                                    </h4>

                                    <p class="text-muted fs-14 mb-0">
                                        Buat surat jalan
                                    </p>
                                </div>
                            </a>
                        </div>

                        <div class="col-6">
                            <a href="<?= url('unit-maintenance') ?>" class="text-decoration-none d-block">
                                <div class="bg-warning bg-opacity-10 rounded-10 p-3 text-center h-100">
                                    <span class="material-symbols-outlined text-warning mb-3 d-block" style="font-size: 40px;">
                                        build
                                    </span>

                                    <h4 class="fs-18 fw-semibold mb-2 text-dark">
                                        Maintenance Unit
                                    </h4>

                                    <p class="text-muted fs-14 mb-0">
                                        Kelola maintenance
                                    </p>
                                </div>
                            </a>
                        </div>

                        <div class="col-6">
                            <a href="<?= url('vehicle-maintenances-due') ?>" class="text-decoration-none d-block">
                                <div class="bg-info bg-opacity-10 rounded-10 p-4 text-center h-100">
                                    <span class="material-symbols-outlined text-info mb-3 d-block" style="font-size: 48px;">
                                        directions_car
                                    </span>

                                    <h4 class="fs-18 fw-semibold mb-2 text-dark">
                                        Service Kendaraan
                                    </h4>

                                    <p class="text-muted fs-14 mb-0">
                                        Kelola service
                                    </p>
                                </div>
                            </a>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="card bg-white rounded-10 border border-white mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">
                <h3>Teknisi Aktif Hari Ini</h3>

                <form class="table-src-form position-relative ms-0">
                    <input type="text" class="form-control" placeholder="Search here...">
                    <div class="src-btn position-absolute top-50 start-0 translate-middle-y bg-transparent p-0 border-0">
                        <span class="material-symbols-outlined">search</span>
                    </div>
                </form>
            </div>

            <div class="default-table-area mx-minus-1 table-to-do-list pm-to-do-list">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Teknisi</th>
                                <th>Customer</th>
                                <th>Lokasi</th>
                                <th>Tugas</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php if (!empty($activeTechnicians)): ?>

                            <?php foreach ($activeTechnicians as $item): ?>

                            <?php

                                $taskLabel = match ($item['task_type'] ?? '') {
                                    'kirim_pasang' => 'Kirim & Pasang',
                                    'bongkar' => 'Bongkar',
                                    default => ucfirst($item['task_type'] ?? '-')
                                };

                                $statusClass = match ($item['status'] ?? '') {
                                    'completed' => 'bg-success',
                                    'process' => 'bg-warning text-dark',
                                    default => 'bg-primary'
                                };

                                $statusLabel = match ($item['status'] ?? '') {
                                    'completed' => 'Selesai',
                                    'process' => 'Proses',
                                    default => 'Assigned'
                                };
                            ?>

                            <tr>

                                <td>

                                    <strong>
                                        <?= htmlspecialchars($item['technician_name'] ?? '-') ?>
                                    </strong>

                                </td>

                                <td>
                                    <?= htmlspecialchars($item['customer_name'] ?? '-') ?>
                                </td>

                                <td>

                                    <small class="text-muted">
                                        <?= htmlspecialchars($item['lokasi'] ?? '-') ?>
                                    </small>

                                </td>

                                <td>

                                    <span class="badge bg-info bg-opacity-10 text-info">
                                        <?= $taskLabel ?>
                                    </span>

                                </td>

                                <td>

                                    <span class="badge <?= $statusClass ?>">
                                        <?= $statusLabel ?>
                                    </span>

                                </td>

                            </tr>

                            <?php endforeach; ?>

                            <?php else: ?>

                            <tr>

                                <td colspan="5" class="text-center py-5 text-muted">

                                    Tidak ada teknisi aktif hari ini.

                                </td>

                            </tr>

                            <?php endif; ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<div class="row">
    <div class="col-lg-8">
        <div class="card bg-white rounded-10 border border-white p-20 mb-4">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-20">
                <h3>Upcoming Return Rental</h3>

                <a href="<?= url('rentals') ?>"
                    class="text-decoration-none fs-15 hover-text">

                    Lihat Semua
                </a>
            </div>

            <?php if (!empty($upcomingReturns)): ?>

            <?php foreach ($upcomingReturns as $rental): ?>

            <?php
                $daysLeft = floor(
                (strtotime($rental['tanggal_selesai']) - time()) / 86400
                );

                $badgeClass = match (true) {
                    $daysLeft <= 0 => 'bg-danger',
                        $daysLeft <= 1 => 'bg-warning text-dark',
                            default => 'bg-primary'
                        };
                    ?>

                    <div class="border rounded-10 p-3 mb-3">

                        <div class="d-flex justify-content-between align-items-start mb-2">

                            <div>

                                <h4 class="fs-16 mb-1">
                                    <?= htmlspecialchars($rental['customer_name'] ?? '-') ?>
                                </h4>

                                <span class="text-muted fs-14">
                                    <?= htmlspecialchars($rental['lokasi'] ?? '-') ?>
                                </span>

                            </div>

                            <span class="badge <?= $badgeClass ?>">
                                <?= $daysLeft <= 0 ? 'Hari Ini' : $daysLeft . ' Hari Lagi' ?>
                                </span>

                            </div>

                            <div class="row mt-3">

                                <div class="col-6">

                                    <small class="text-muted d-block">
                                        Rental
                                    </small>

                                    <strong>
                                        <?= htmlspecialchars($rental['no_rental'] ?? '-') ?>
                                    </strong>

                                </div>

                                <div class="col-6">

                                    <small class="text-muted d-block">
                                        Tanggal Selesai
                                    </small>

                                    <strong>
                                        <?= htmlspecialchars($rental['tanggal_selesai'] ?? '-') ?>
                                    </strong>

                                </div>

                            </div>

                        </div>

                        <?php endforeach; ?>

                        <?php else: ?>

                        <div class="text-center py-5 text-muted">

                            Tidak ada rental yang akan selesai.

                        </div>

                        <?php endif; ?>

                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card bg-white rounded-10 border border-white mb-4">

                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">
                            <h4 class="fs-18 fw-semibold mb-0">Fleet Kendaraan</h4>

                            <a href="<?= url('vehicles') ?>" class="text-decoration-none fs-15 hover-text">
                                Lihat Semua
                            </a>
                        </div>

                        <div class="default-table-area mx-minus-1 table-team-members">
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="fw-medium">Kendaraan</th>
                                            <th scope="col" class="fw-medium">KM</th>
                                            <th scope="col" class="fw-medium text-start">Status</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php if (!empty($vehicleFleetList)): ?>
                                        <?php foreach (array_slice($vehicleFleetList, 0, 5) as $vehicle): ?>
                                        <?php
                                            $status = $vehicle['vehicle_status'] ?? 'available';

                                            $statusClass = match ($status) {
                                                'available' => 'bg-success',
                                                'used' => 'bg-primary',
                                                'maintenance' => 'bg-warning text-dark',
                                                'broken' => 'bg-danger',
                                                default => 'bg-secondary'
                                            };

                                            $statusLabel = match ($status) {
                                                'available' => 'Available',
                                                'used' => 'Dipakai',
                                                'maintenance' => 'Maintenance',
                                                'broken' => 'Rusak',
                                                default => ucfirst($status)
                                            };

                                            $maintenanceStatus = $vehicle['maintenance_status'] ?? 'normal';

                                            $maintenanceBadgeClass = match ($maintenanceStatus) {
                                                'due' => 'bg-danger',
                                                'process' => 'bg-warning text-dark',
                                                default => 'bg-success'
                                            };

                                            $maintenanceLabel = match ($maintenanceStatus) {
                                                'due' => 'Due Service',
                                                'process' => 'Proses Service',
                                                default => 'Normal'
                                            };
                                        ?>

                                        <tr>
                                            <td class="border-0">
                                                <div class="d-flex align-items-center text-decoration-none">
                                                    <div class="flex-shrink-0">
                                                        <div class="rounded-circle bg-info bg-opacity-10 d-flex align-items-center justify-content-center"
                                                        style="width: 45px; height: 45px;">
                                                        <span class="material-symbols-outlined text-info">
                                                            directions_car
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="flex-grow-1 ms-12">
                                                    <h3 class="fw-medium fs-16 mb-1">
                                                        <?= htmlspecialchars($vehicle['vehicle_code'] ?? '-') ?>
                                                    </h3>

                                                    <span class="fs-14 text-body fw-normal">
                                                        <?= htmlspecialchars($vehicle['plate_number'] ?? '-') ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="text-body border-0">
                                            <?= number_format((int) ($vehicle['total_km'] ?? 0), 0, ',', '.') ?>
                                            <small class="text-muted">km</small>
                                        </td>

                                        <td class="border-0">
                                            <span class="badge <?= $statusClass ?> mb-1">
                                                <?= $statusLabel ?>
                                            </span>

                                            <br>

                                            <span class="badge <?= $maintenanceBadgeClass ?>">
                                                <?= $maintenanceLabel ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">
                                            Belum ada data kendaraan.
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="card bg-white rounded-10 border border-white mb-4">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">
                        <h3 class="mb-0">Kendaraan Wajib Service</h3>

                        <a href="<?= url('vehicle-maintenances-due') ?>" class="text-decoration-none fs-15 hover-text">
                            Lihat Semua
                        </a>
                    </div>

                    <div class="default-table-area mx-minus-1">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Kendaraan</th>
                                        <th>Plat</th>
                                        <th>KM</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php if (!empty($vehicleServiceDueList)): ?>
                                    <?php foreach (array_slice($vehicleServiceDueList, 0, 5) as $vehicle): ?>
                                    <?php
                                        $status = $vehicle['maintenance_status'] ?? 'due';

                                        $badgeClass = match ($status) {
                                            'process' => 'bg-warning text-dark',
                                            'due' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };

                                        $statusLabel = match ($status) {
                                            'process' => 'Proses',
                                            'due' => 'Wajib Service',
                                            default => ucfirst($status)
                                        };
                                    ?>

                                    <tr>
                                        <td>
                                            <strong><?= htmlspecialchars($vehicle['vehicle_code'] ?? '-') ?></strong><br>
                                            <small class="text-muted">
                                                <?= htmlspecialchars($vehicle['vehicle_name'] ?? '-') ?>
                                            </small>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($vehicle['plate_number'] ?? '-') ?>
                                        </td>

                                        <td>
                                            <?= number_format((int) ($vehicle['total_km'] ?? 0), 0, ',', '.') ?> km
                                        </td>

                                        <td>
                                            <span class="badge <?= $badgeClass ?>">
                                                <?= $statusLabel ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            Tidak ada kendaraan wajib service.
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">
        <h3 class="mb-0">Reminder Dokumen Kendaraan</h3>

        <a href="<?= url('vehicles-reminders') ?>" class="text-decoration-none fs-15 hover-text">
            Lihat Semua
        </a>
    </div>

    <div class="default-table-area mx-minus-1">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Kendaraan</th>
                        <th>Plat</th>
                        <th>STNK</th>
                        <th>Pajak</th>
                        <th>KIR</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($vehicleReminderList)): ?>
                        <?php foreach (array_slice($vehicleReminderList, 0, 5) as $vehicle): ?>
                            <?php
                                $stnkDays = isset($vehicle['stnk_remaining_days']) ? (int) $vehicle['stnk_remaining_days'] : null;
                                $taxDays  = isset($vehicle['tax_remaining_days']) ? (int) $vehicle['tax_remaining_days'] : null;
                                $kirDays  = isset($vehicle['kir_remaining_days']) ? (int) $vehicle['kir_remaining_days'] : null;

                                $docBadge = function ($days) {
                                    if ($days === null) {
                                        return ['bg-secondary', '-'];
                                    }

                                    if ($days < 0) {
                                        return ['bg-danger', 'Expired'];
                                    }

                                    if ($days <= 30) {
                                        return ['bg-warning text-dark', $days . ' hari'];
                                    }

                                    return ['bg-success', 'Aman'];
                                };

                                [$stnkClass, $stnkLabel] = $docBadge($stnkDays);
                                [$taxClass, $taxLabel]   = $docBadge($taxDays);
                                [$kirClass, $kirLabel]   = $docBadge($kirDays);
                            ?>

                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($vehicle['vehicle_code'] ?? '-') ?></strong><br>
                                    <small class="text-muted">
                                        <?= htmlspecialchars($vehicle['vehicle_name'] ?? '-') ?>
                                    </small>
                                </td>

                                <td>
                                    <?= htmlspecialchars($vehicle['plate_number'] ?? '-') ?>
                                </td>

                                <td>
                                    <span class="badge <?= $stnkClass ?>">
                                        <?= $stnkLabel ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="badge <?= $taxClass ?>">
                                        <?= $taxLabel ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="badge <?= $kirClass ?>">
                                        <?= $kirLabel ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Tidak ada reminder dokumen kendaraan.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>

            </table>
        </div>
    </div>
</div>
            </div>
        </div>
