<div class="main-content-container overflow-hidden">

    <!-- ROW 1: SUMMARY -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <h3 class="mb-0">Penawaran vs Order</h3>

                    <span class="badge bg-primary bg-opacity-10 text-primary">
                        Year <?= date('Y') ?>
                    </span>
                </div>

                <div id="total_sales_chart" style="margin-bottom: -16px; margin-top: -1.5px;"></div>
            </div>
        </div>

        <!-- KPI CARDS -->
        <div class="col-lg-6 col-xxl-3 col-xxxl-6">
            <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <h3 class="mb-10">Total Penawaran</h3>

                        <h2 class="fs-26 fw-medium mb-0 lh-1">
                            <?= (int) ($totalQuotations ?? 0) ?>
                        </h2>
                    </div>

                    <div class="flex-shrink-0 ms-3">
                        <div class="bg-primary text-white text-center rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 65px; height: 65px;">
                            <i class="material-symbols-outlined fs-32">request_quote</i>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center" style="margin-top: 21px;">
                    <p class="mb-0 fs-14">Semua penawaran</p>

                    <span class="badge bg-primary bg-opacity-10 text-primary">
                        QUOTATION
                    </span>
                </div>
            </div>

            <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <h3 class="mb-10">Total Customer</h3>

                        <h2 class="fs-26 fw-medium mb-0 lh-1">
                            <?= (int) ($totalCustomers ?? 0) ?>
                        </h2>
                    </div>

                    <div class="flex-shrink-0 ms-3">
                        <div class="bg-info text-white text-center rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 65px; height: 65px;">
                            <i class="material-symbols-outlined fs-32">diversity_2</i>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center" style="margin-top: 21px;">
                    <p class="mb-0 fs-14">Database customer</p>

                    <span class="badge bg-info bg-opacity-10 text-info">
                        CUSTOMER
                    </span>
                </div>
            </div>
        </div>

        <!-- MONTHLY + PIPELINE -->
        <div class="col-xl-12 col-xxl-3 col-xxxl-12">
            <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <h3 class="mb-10">Penawaran Bulan Ini</h3>

                        <h2 class="fs-26 fw-medium mb-0 lh-1">
                            <?= (int) ($quotationThisMonth ?? 0) ?>
                        </h2>
                    </div>

                    <div class="flex-shrink-0 ms-3">
                        <div class="bg-warning text-white text-center rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 65px; height: 65px;">
                            <i class="material-symbols-outlined fs-32">calendar_month</i>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center" style="margin-top: 23px;">
                    <p class="mb-0 fs-14">Dibuat bulan ini</p>

                    <span class="badge bg-warning bg-opacity-10 text-warning">
                        MONTHLY
                    </span>
                </div>
            </div>

            <div class="bg-primary-50 p-20 border rounded-10 border-primary-50 mb-4">
                <h3 class="text-white mb-12">Pipeline Penawaran</h3>

                <div class="d-flex flex-wrap gap-2 justify-content-between mb-14">
                    <div>
                        <span class="fs-14 text-white mb-1 d-block">Draft</span>
                        <h2 class="fs-20 fw-medium lh-1 text-white mb-0">
                            <?= (int) ($quotationThisMonth ?? 0) ?>
                        </h2>
                    </div>

                    <div>
                        <span class="fs-14 text-white mb-1 d-block">Pending</span>
                        <h2 class="fs-20 fw-medium lh-1 text-white mb-0">
                            <?= (int) ($quotationPending ?? 0) ?>
                        </h2>
                    </div>

                    <div>
                        <span class="fs-14 text-white mb-1 d-block">Deal</span>
                        <h2 class="fs-20 fw-medium lh-1 text-white mb-0">
                            <?= (int) ($quotationDeal ?? 0) ?>
                        </h2>
                    </div>
                </div>

                <?php
                $pipelineTotal =
                    (int) ($quotationThisMonth ?? 0) +
                    (int) ($quotationPending ?? 0) +
                    (int) ($quotationDeal ?? 0);

                $dealPercent = $pipelineTotal > 0
                    ? round(((int) ($quotationDeal ?? 0) / $pipelineTotal) * 100)
                    : 0;
                ?>

                <div class="progress rounded-0 mb-6" style="height: 3px; background-color: #6258cc;">
                    <div class="progress-bar rounded-0 bg-white"
                        style="width: <?= $dealPercent ?>%; height: 3px;">
                    </div>
                </div>

                <span class="fs-14 text-white d-block" style="margin-bottom: -6px;">
                    Conversion deal <?= $dealPercent ?>%
                </span>
            </div>
        </div>
    </div>

    <!-- ROW 2: VALUE + CUSTOMER + QUICK ACTION -->
    <div class="row">
        <div class="col-xxl-6 col-xxxl-12">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-20">
                            <h3 class="mb-0">Income</h3>

                            <span class="badge bg-success bg-opacity-10 text-success">
                                <?= date('Y') ?>
                            </span>
                        </div>

                        <h2 class="lh-1 fs-26 fw-medium mb-0">
                            Rp <?= number_format($totalIncomeThisMonth ?? 0, 0, ',', '.') ?>
                        </h2>

                        <small class="text-muted d-block mt-2">
                            Total pembayaran masuk bulan ini
                        </small>

                        <div id="income_chart" style="margin-bottom: -17px;"></div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-20">
                            <h3 class="mb-0">Penawaran Harian</h3>

                            <span class="badge bg-info bg-opacity-10 text-info">
                                7 Hari
                            </span>
                        </div>

                        <h2 class="lh-1 fs-26 fw-medium mb-0">
                            <?= array_sum($dailyQuotationChart['data'] ?? []) ?>
                        </h2>

                        <small class="text-muted d-block mt-2">
                            Total penawaran 7 hari terakhir
                        </small>

                        <div id="daily_quotation_chart" style="margin-bottom: -17px;"></div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card bg-white rounded-10 border border-white p-20 mb-4">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div>
                                <span class="fs-16 text-body d-block mb-2">Nilai Deal</span>

                                <h2 class="fs-32 fw-bold mb-0">
                                    Rp <?= number_format((float) ($totalDealValue ?? 0), 0, ',', '.') ?>
                                </h2>
                            </div>

                            <span class="badge bg-success bg-opacity-10 text-success fs-16">
                                <i class="material-symbols-outlined align-middle fs-18">verified</i>
                                Deal
                            </span>
                        </div>

                        <?php
                        $dealValuePercent = ($totalQuotationValue ?? 0) > 0
                            ? round(($totalDealValue / $totalQuotationValue) * 100)
                            : 0;
                        ?>

                        <div class="progress rounded-pill" style="height: 8px;">
                            <div class="progress-bar bg-success rounded-pill"
                                style="width: <?= $dealValuePercent ?>%;">
                            </div>
                        </div>

                        <small class="text-muted d-block mt-2">
                            <?= $dealValuePercent ?>% dari total pipeline quotation
                        </small>
                    </div>

                        <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-20">
                                <h3 class="mb-0">Pipeline Status Penawaran</h3>

                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                    Realtime
                                </span>
                            </div>

                                <?php
                                $totalPipeline =
                                    (int)($quotationDraft ?? 0) +
                                    (int)($quotationPending ?? 0) +
                                    (int)($quotationDeal ?? 0);

                                $pendingPercent = $totalPipeline > 0
                                    ? round((($quotationDraft ?? 0) / $totalPipeline) * 100)
                                    : 0;

                                $approvedPercent = $totalPipeline > 0
                                    ? round((($quotationPending ?? 0) / $totalPipeline) * 100)
                                    : 0;

                                $orderPercent = $totalPipeline > 0
                                    ? round((($quotationDeal ?? 0) / $totalPipeline) * 100)
                                    : 0;
                                ?>

                                    <div class="d-flex justify-content-between align-items-center mb-20 flex-wrap gap-2">

                                        <div>
                                            <span class="fs-15 text-secondary d-block">
                                                Pending
                                            </span>

                                            <strong class="fs-18">
                                                <?= (int)($quotationDraft ?? 0) ?>
                                            </strong>

                                            <span class="text-muted">
                                                (<?= $pendingPercent ?>%)
                                            </span>
                                        </div>

                                        <div>
                                            <span class="fs-15 text-secondary d-block">
                                                Approved
                                            </span>

                                            <strong class="fs-18">
                                                <?= (int)($quotationPending ?? 0) ?>
                                            </strong>

                                            <span class="text-muted">
                                                (<?= $approvedPercent ?>%)
                                            </span>
                                        </div>

                                        <div>
                                            <span class="fs-15 text-secondary d-block">
                                                Deal
                                            </span>

                                            <strong class="fs-18">
                                                <?= (int)($quotationDeal ?? 0) ?>
                                            </strong>

                                            <span class="text-muted">
                                                (<?= $orderPercent ?>%)
                                            </span>
                                        </div>

                                    </div>

                                    <div id="pipeline_status_chart"></div>

                            <div id="pipeline_status_chart"></div>
                        </div>
                    </div>

                <!-- REVENUE + CUSTOMER -->
                <div class="col-md-6">
                    <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                         <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-20">
                            <h3 class="mb-0">Customer Baru Bulan Ini</h3>

                            <span class="badge bg-success bg-opacity-10 text-success">
                                +<?= $newCustomersThisMonth ?? 0 ?>
                            </span>
                        </div>

                        <h2 class="fs-26 fw-medium mb-40">
                            <?= number_format($newCustomersThisMonth ?? 0) ?>
                        </h2>

                        <h4 class="fs-16 text-secondary mb-20">
                            Customer Terbaru
                        </h4>

                        <div class="d-flex align-items-center flex-wrap">

                            <?php foreach (($latestCustomers ?? []) as $customer): ?>

                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold me-2 mb-2"
                                    style="width: 55px; height: 55px; font-size: 18px;">

                                    <?= strtoupper(substr($customer['company_name'] ?? 'C', 0, 1)) ?>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    </div>

                    <div class="card p-20 bg-white rounded-10 border border-white mb-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-20">
                            <h3 class="mb-0">Customer Terbaru</h3>

                            <a href="<?= url('customers') ?>" class="text-decoration-none fs-15 hover-text">
                                Lihat Semua
                            </a>
                        </div>

                        <h2 class="lh-1 fs-26 fw-medium mb-3">
                            <?= (int) ($totalCustomers ?? 0) ?>
                        </h2>

                        <span class="fs-16 text-body d-block mb-10">
                            Database Customer
                        </span>

                        <ul class="p-0 mb-0 list-unstyled">
                            <?php if (!empty($latestCustomers)): ?>
                                <?php foreach ($latestCustomers as $customer): ?>
                                    <li class="border-bottom pb-2 mb-2">
                                        <strong>
                                            <?= htmlspecialchars($customer['company_name'] ?? $customer['customer_name'] ?? '-') ?>
                                        </strong>

                                        <small class="text-muted d-block">
                                            <?= htmlspecialchars($customer['pic_name'] ?? '-') ?>
                                            <?= !empty($customer['phone']) ? ' • ' . htmlspecialchars($customer['phone']) : '' ?>
                                        </small>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li class="text-muted">Belum ada customer.</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- TOP CUSTOMER + UPCOMING -->
        <div class="col-xxl-6 col-xxxl-12">
            <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-20">
                    <h3 class="mb-0">Top Customer</h3>
                </div>

                <div class="default-table-area without-header table-top-selling-products">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <tbody>
                                <?php if (!empty($topCustomers)): ?>
                                    <?php foreach ($topCustomers as $index => $customer): ?>
                                        <tr>
                                            <td class="text-body fw-medium">
                                                <?= str_pad($index + 1, 2, '0', STR_PAD_LEFT) ?>.
                                            </td>

                                            <td class="ps-0">
                                                <strong><?= htmlspecialchars($customer['customer_name'] ?? '-') ?></strong>

                                                <span class="fs-14 text-body fw-normal d-block">
                                                    <?= (int) ($customer['total_quotation'] ?? 0) ?> penawaran
                                                </span>
                                            </td>

                                            <td class="text-body">
                                                Terakhir:
                                                <?= !empty($customer['last_quotation_date'])
                                                    ? date('d M Y', strtotime($customer['last_quotation_date']))
                                                    : '-'
                                                ?>
                                            </td>

                                            <td class="text-end">
                                                <a href="<?= url('quotations', ['search' => $customer['customer_name'] ?? '']) ?>"
                                                    class="btn btn-sm btn-light border">
                                                    Lihat
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            Belum ada data top customer.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card bg-white rounded-10 border border-white mb-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">
                    <h3 class="mb-0">Jadwal Rental Mendatang</h3>

                    <a href="<?= url('rentals') ?>" class="text-decoration-none fs-15 hover-text">
                        Lihat Semua
                    </a>
                </div>

                <div class="default-table-area mx-minus-1">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>No Rental</th>
                                    <th>Customer</th>
                                    <th>Mulai</th>
                                    <th>Selesai</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if (!empty($upcomingProjects)): ?>
                                    <?php foreach ($upcomingProjects as $item): ?>
                                        <?php
                                        $status = strtolower($item['status_rental'] ?? 'scheduled');

                                        $badgeClass = match ($status) {
                                            'on_rent' => 'bg-success text-success',
                                            'scheduled' => 'bg-primary text-primary',
                                            'completed' => 'bg-secondary text-secondary',
                                            default => 'bg-warning text-warning'
                                        };
                                        ?>

                                        <tr>
                                            <td><?= htmlspecialchars($item['no_rental'] ?? '-') ?></td>

                                            <td>
                                                <strong><?= htmlspecialchars($item['customer_name'] ?? '-') ?></strong>

                                                <small class="text-muted d-block">
                                                    <?= htmlspecialchars($item['lokasi'] ?? '-') ?>
                                                </small>
                                            </td>

                                            <td>
                                                <?= !empty($item['tanggal_rental'])
                                                    ? date('d M Y', strtotime($item['tanggal_rental']))
                                                    : '-'
                                                ?>
                                            </td>

                                            <td>
                                                <?= !empty($item['tanggal_selesai'])
                                                    ? date('d M Y', strtotime($item['tanggal_selesai']))
                                                    : '-'
                                                ?>
                                            </td>

                                            <td>
                                                <span class="badge <?= $badgeClass ?>">
                                                    <?= ucfirst($status) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            Belum ada jadwal rental.
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

   <!-- ROW 3: PIPELINE, FOLLOW UP, LOCATION -->
<div class="row">
    <div class="col-lg-6 col-xxl-4 col-xxxl-6 mb-4">
<div class="card bg-white p-20 rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-20">
        <h3 class="mb-0">Pembayaran 7 Hari Terakhir</h3>

        <span class="badge bg-success bg-opacity-10 text-success">
            Realtime
        </span>
    </div>

    <div class="transactions-history">

        <?php if (!empty($latestPayments)): ?>

            <?php foreach ($latestPayments as $payment): ?>

                <div class="d-flex justify-content-between align-items-center py-3 border-bottom">

                    <div class="d-flex align-items-center">

                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 55px; height: 55px;">

                                <span class="material-symbols-outlined">
                                    payments
                                </span>

                            </div>
                        </div>

                        <div class="flex-grow-1 ms-3">

                            <h4 class="fs-16 mb-1">
                                <?= htmlspecialchars($payment['customer_name'] ?? '-') ?>
                            </h4>

                            <span class="fs-14 text-muted d-block">
                                <?= htmlspecialchars($payment['no_invoice'] ?? '-') ?>
                            </span>

                            <small class="text-muted">
                                <?= date('d M Y', strtotime($payment['payment_date'])) ?>
                            </small>

                        </div>

                    </div>

                    <div class="text-end">

                        <strong class="text-success fs-16">
                            + Rp <?= number_format((float) ($payment['payment_amount'] ?? 0), 0, ',', '.') ?>
                        </strong>

                        <small class="text-muted d-block">
                            <?= htmlspecialchars($payment['payment_method'] ?? '-') ?>
                        </small>

                    </div>

                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <div class="text-center text-muted py-5">
                Belum ada pembayaran.
            </div>

        <?php endif; ?>

    </div>

</div>
    </div>

    <div class="col-lg-6 col-xxl-4 col-xxxl-6 mb-4">
        <div class="card bg-white rounded-10 border border-white p-20 h-100">

            <div class="d-flex justify-content-between align-items-center mb-20">
                <h3 class="mb-0">Follow Up Hari Ini</h3>

                <span class="badge bg-warning bg-opacity-10 text-warning">
                    <?= count($followUpList ?? []) ?>
                </span>
            </div>

            <?php if (!empty($followUpList)): ?>
                <?php foreach (array_slice($followUpList, 0, 5) as $item): ?>
                    <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-3">
                        <div>
                            <h4 class="fs-15 mb-1">
                                <?= htmlspecialchars($item['customer_name'] ?? '-') ?>
                            </h4>

                            <span class="fs-13 text-muted d-block mb-1">
                                <?= htmlspecialchars($item['no_quotation'] ?? '-') ?>
                            </span>

                            <small class="text-muted">
                                <?= htmlspecialchars($item['lokasi'] ?? '-') ?>
                            </small>
                        </div>

                        <div class="text-end">
                            <span class="badge bg-warning bg-opacity-10 text-warning mb-2">
                                <?= ucfirst($item['status'] ?? '-') ?>
                            </span>

                            <div>
                                <a href="<?= url('quotations-show', ['id' => $item['id']]) ?>"
                                    class="btn btn-sm btn-light border">
                                    Detail
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-4 text-muted">
                    Tidak ada follow up hari ini.
                </div>
            <?php endif; ?>

        </div>
    </div>

    <div class="col-lg-12 col-xxl-4 col-xxxl-12 mb-4">
<div class="card bg-white p-20 rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-20">
        <h3 class="mb-0">Top Quotation Bulan Ini</h3>

        <span class="badge bg-primary bg-opacity-10 text-primary">
            Top 6
        </span>
    </div>

    <div class="default-table-area without-header table-top-sellers">
        <div class="table-responsive">
            <table class="table align-middle">
                <tbody>

                    <?php if (!empty($topQuotationsThisMonth)): ?>

                        <?php foreach ($topQuotationsThisMonth as $index => $item): ?>

                            <tr>
                                <td class="text-body fw-medium" style="width: 50px;">
                                    <?= str_pad($index + 1, 2, '0', STR_PAD_LEFT) ?>.
                                </td>

                                <td class="ps-0">
                                    <div class="d-flex align-items-center text-decoration-none">
                                        <div class="flex-shrink-0">
                                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold"
                                                style="width: 50px; height: 50px;">

                                                <?= strtoupper(substr($item['customer_name'] ?? 'Q', 0, 1)) ?>

                                            </div>
                                        </div>

                                        <div class="flex-grow-1 ms-12">
                                            <h3 class="fw-normal mb-1">
                                                <?= htmlspecialchars($item['no_quotation'] ?? '-') ?>
                                            </h3>

                                            <span class="fs-14 text-body fw-normal">
                                                <?= htmlspecialchars($item['customer_name'] ?? '-') ?>
                                            </span>

                                            <small class="text-muted d-block">
                                                <?= htmlspecialchars($item['lokasi'] ?? '-') ?>
                                            </small>
                                        </div>
                                    </div>
                                </td>

                                <td class="text-end">
                                    <strong>
                                        Rp <?= number_format((float) ($item['total_value'] ?? 0), 0, ',', '.') ?>
                                    </strong>

                                    <small class="text-muted d-block">
                                        <?= !empty($item['created_at'])
                                            ? date('d M Y', strtotime($item['created_at']))
                                            : '-'
                                        ?>
                                    </small>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                Belum ada quotation bulan ini.
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

    <!-- ROW 4: RECENT QUOTATIONS + ACTIVITIES -->
    <div class="row">
        <div class="col-xxl-8 col-xxxxl-12">
            <div class="card bg-white rounded-10 border border-white mb-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">
                    <h3 class="mb-0">Penawaran Terbaru</h3>
                </div>

                <div class="default-table-area mx-minus-1 table-recent-orders">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>No Quotation</th>
                                    <th>Customer</th>
                                    <th>Mulai</th>
                                    <th>Selesai</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if (!empty($recentQuotations)): ?>
                                    <?php foreach ($recentQuotations as $item): ?>
                                        <?php
                                        $status = strtolower($item['status'] ?? 'waiting approval');

                                        $badgeClass = match ($status) {
                                            'approved', 'deal', 'accepted' => 'bg-success text-success',
                                            'waiting approval', 'pending', 'sent' => 'bg-warning text-warning',
                                            'rejected', 'cancelled' => 'bg-danger text-danger',
                                            default => 'bg-secondary text-secondary'
                                        };
                                        ?>

                                        <tr>
                                            <td class="text-body">
                                                <?= htmlspecialchars($item['no_quotation'] ?? '-') ?>
                                            </td>

                                            <td>
                                                <strong><?= htmlspecialchars($item['customer_name'] ?? '-') ?></strong>

                                                <small class="text-muted d-block">
                                                    <?= htmlspecialchars($item['lokasi'] ?? '-') ?>
                                                </small>
                                            </td>

                                            <td class="text-body">
                                                <?= !empty($item['tanggal_mulai'])
                                                    ? date('d M Y', strtotime($item['tanggal_mulai']))
                                                    : '-'
                                                ?>
                                            </td>

                                            <td class="text-body">
                                                <?= !empty($item['tanggal_selesai'])
                                                    ? date('d M Y', strtotime($item['tanggal_selesai']))
                                                    : '-'
                                                ?>
                                            </td>

                                            <td>
                                                <span class="badge <?= $badgeClass ?>">
                                                    <?= ucfirst($status) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            Belum ada data penawaran.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-4 col-xxxxl-12">
            <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-20">
                    <h3 class="mb-0">Aktivitas Sales Terbaru</h3>
                </div>

                <div class="default-table-area without-header table-transactions-history">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <tbody>
                                <?php if (!empty($recentSalesActivities)): ?>
                                    <?php foreach ($recentSalesActivities as $item): ?>
                                        <?php
                                        $status = strtolower($item['status'] ?? 'draft');

                                        $iconClass = match ($status) {
                                            'approved', 'accepted', 'deal' => 'text-success',
                                            'waiting approval', 'pending', 'sent' => 'text-warning',
                                            'rejected', 'cancelled' => 'text-danger',
                                            default => 'text-primary'
                                        };

                                        $bgClass = match ($status) {
                                            'approved', 'accepted', 'deal' => '#e0f8ea',
                                            'waiting approval', 'pending', 'sent' => '#fff4e8',
                                            'rejected', 'cancelled' => '#fce4e2',
                                            default => '#dbeafd'
                                        };
                                        ?>

                                        <tr>
                                            <td class="ps-0">
                                                <div class="d-flex align-items-center text-decoration-none">
                                                    <div class="flex-shrink-0">
                                                        <div class="<?= $iconClass ?> text-center rounded-circle"
                                                            style="width: 50px; height: 50px; line-height: 62px; background-color: <?= $bgClass ?>;">
                                                            <i class="material-symbols-outlined fs-24">
                                                                request_quote
                                                            </i>
                                                        </div>
                                                    </div>

                                                    <div class="flex-grow-1 ms-15">
                                                        <h3 class="fw-normal mb-1">
                                                            <?= htmlspecialchars($item['no_quotation'] ?? '-') ?>
                                                        </h3>

                                                        <span class="fs-14 text-body fw-normal">
                                                            <?= htmlspecialchars($item['customer_name'] ?? '-') ?>
                                                        </span>

                                                        <small class="text-muted d-block">
                                                            <?= !empty($item['created_at'])
                                                                ? date('d M Y H:i', strtotime($item['created_at']))
                                                                : '-'
                                                            ?>
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="text-end">
                                                <a href="<?= url('quotations-show', ['id' => $item['id']]) ?>"
                                                    class="btn btn-sm btn-light border">
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td class="text-center text-muted py-4">
                                            Belum ada aktivitas sales.
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    const chartEl = document.querySelector("#total_sales_chart");

    if (!chartEl) return;

    const options = {
                series: [
                    {
                        name: "Penawaran",
                        data: <?= json_encode($monthlyQuotationChart['quotations'] ?? []) ?>
                    },
                    {
                        name: "Order",
                        data: <?= json_encode($monthlyQuotationChart['deals'] ?? []) ?>
                    }
                ],
            chart: {
            height: 330,
            type: "line",
            toolbar: {
                show: false
            }
        },
        stroke: {
            curve: "smooth",
            width: 4
        },
        dataLabels: {
            enabled: false
        },
        xaxis: {
            categories: <?= json_encode($monthlyQuotationChart['months'] ?? []) ?>
        },
        yaxis: {
            labels: {
                formatter: function (value) {
                    return new Intl.NumberFormat('id-ID').format(value);
                }
            }
        },
        tooltip: {
            y: {
                formatter: function (value) {
                    return new Intl.NumberFormat('id-ID').format(value);
                }
            }
        },
        legend: {
            position: "top",
            horizontalAlign: "center"
        },
        colors: ["#796df6", "#06b6d4"],
        grid: {
            borderColor: "#e5e7eb",
            strokeDashArray: 5
        }
    };

    const chart = new ApexCharts(chartEl, options);
    chart.render();
});

document.addEventListener('DOMContentLoaded', function () {
    const chartEl = document.querySelector("#daily_quotation_chart");

    if (!chartEl) return;

    const options = {
        series: [
            {
                name: "Penawaran",
                data: <?= json_encode($dailyQuotationChart['data'] ?? []) ?>
            }
        ],
        chart: {
            type: "bar",
            height: 260,
            toolbar: {
                show: false
            }
        },
        plotOptions: {
            bar: {
                columnWidth: "45%",
                borderRadius: 4
            }
        },
        dataLabels: {
            enabled: false
        },
        xaxis: {
            categories: <?= json_encode($dailyQuotationChart['labels'] ?? []) ?>
        },
        yaxis: {
            labels: {
                formatter: function (value) {
                    return parseInt(value);
                }
            }
        },
        tooltip: {
            y: {
                formatter: function (value) {
                    return value + " penawaran";
                }
            }
        },
        colors: ["#06b6d4"],
        grid: {
            borderColor: "#e5e7eb",
            strokeDashArray: 5
        }
    };

    const chart = new ApexCharts(chartEl, options);
    chart.render();
});

document.addEventListener('DOMContentLoaded', function () {

    const chartEl = document.querySelector("#income_chart");

    if (!chartEl) return;

    const options = {
        series: [{
            name: "Income",
            data: <?= json_encode($monthlyIncomeChart['data'] ?? []) ?>
        }],

        chart: {
            type: "line",
            height: 260,
            toolbar: {
                show: false
            }
        },

        stroke: {
            curve: "smooth",
            width: 5
        },

        dataLabels: {
            enabled: false
        },

        xaxis: {
            categories: <?= json_encode($monthlyIncomeChart['labels'] ?? []) ?>
        },

        yaxis: {
            labels: {
                formatter: function(value) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                }
            }
        },

        tooltip: {
            y: {
                formatter: function(value) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                }
            }
        },

        colors: ['#796df6'],

        grid: {
            borderColor: '#e5e7eb',
            strokeDashArray: 5
        }
    };

    const chart = new ApexCharts(chartEl, options);
    chart.render();
});

document.addEventListener('DOMContentLoaded', function () {
    const chartEl = document.querySelector("#pipeline_status_chart");

    if (!chartEl) return;

    const options = {
        series: [
            <?= (int) ($quotationDraft ?? 0) ?>,
            <?= (int) ($quotationPending ?? 0) ?>,
            <?= (int) ($quotationDeal ?? 0) ?>
        ],
        chart: {
            type: "donut",
            height: 320
        },
        labels: ["Pending", "Approved", "Deal"],
        dataLabels: {
            enabled: false
        },
        legend: {
            position: "bottom"
        },
        colors: ["#796df6", "#1a7efb", "#06b6d4"],
        plotOptions: {
            pie: {
                donut: {
                    size: "65%"
                }
            }
        }
    };

    const chart = new ApexCharts(chartEl, options);
    chart.render();
});
</script>
