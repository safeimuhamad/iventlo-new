      <div class="main-content-container overflow-hidden">
                    <div class="row">
                        <div class="col-xxl-6 col-xxxxxl-12">
                            <div class="card rounded-10 border-0 mb-4 bg-img zinnia-card"
                            style="background: linear-gradient(101deg, #5040F4 55.73%, #796DF6 99.52%); padding: 30.5px 40px;">

                            <div class="row align-items-center">
                                <div class="col-sm-7">

                                    <h2 class="fs-26 fw-normal text-white mb-3">
                                        Welcome <span class="fw-900">Owner!</span>
                                    </h2>

                                    <p class="fs-16 lh-1-8" style="color: #CBC7FF; margin-bottom: 40px;">
                                        Ringkasan performa bisnis rental bulan ini.
                                    </p>

                                    <div class="d-flex flex-wrap gap-3">

                                        <div class="d-flex rounded-1" style="padding: 9px 12px; background: rgba(255,255,255,0.16);">
                                            <div class="flex-shrink-0">
                                                <i class="ri-money-dollar-circle-line fs-18 text-center rounded-circle d-inline-block"
                                                style="background-color: #C5CBFF; color: #382C83; width: 36px; height: 36px; line-height: 36px;"></i>
                                            </div>

                                                <div class="flex-grow-1 ms-10">

                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <span class="fs-15" style="color: #CBC7FF;">Income</span>

                                                        <span class="badge bg-success text-white"
                                                            style="font-size: 10px; padding: 4px 6px;">

                                                            <i class="material-symbols-outlined align-middle"
                                                                style="font-size: 11px;">trending_up</i>

                                                            5.2%
                                                        </span>
                                                    </div>

                                                    <h2 class="fs-16 mb-0 lh-1 text-white">
                                                        Rp <?= number_format((float) ($incomeThisMonth ?? 0), 0, ',', '.') ?>
                                                    </h2>

                                                </div>
                                        </div>

                                <div class="d-flex rounded-1" style="padding: 9px 12px; background: rgba(255,255,255,0.16);">
                                    <div class="flex-shrink-0">
                                        <i class="ri-line-chart-line fs-18 text-center rounded-circle d-inline-block"
                                        style="background-color: #C5CBFF; color: #382C83; width: 36px; height: 36px; line-height: 36px;"></i>
                                    </div>

                                    <div class="flex-grow-1 ms-10">

                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <span class="fs-15" style="color: #CBC7FF;">Profit</span>

                                            <span class="badge bg-success text-white"
                                                style="font-size: 10px; padding: 4px 6px;">

                                                <i class="material-symbols-outlined align-middle"
                                                    style="font-size: 11px;">trending_up</i>

                                                3.8%
                                            </span>
                                        </div>

                                        <h2 class="fs-16 mb-0 lh-1 text-white">
                                            Rp <?= number_format((float) ($profitThisMonth ?? 0), 0, ',', '.') ?>
                                        </h2>

                                    </div>
                        </div>

                    </div>
                </div>

                <div class="col-sm-5">
                    <div class="text-center text-sm-end mt-4 mt-sm-0">
                        <img src="assets/images/welcome2.png" alt="welcome2">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="card bg-white p-20 py-30 rounded-10 border border-white mb-4 position-relative z-1">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <i class="ri-calendar-check-fill rounded-1 d-flex justify-content-center align-items-center fs-28 text-primary mb-3"
                            style="width: 50px; height: 50px; box-shadow: 0px 4px 20px 0px rgba(232, 231, 244, 0.70);">
                        </i>

                        <h3 class="mb-12 lh-1 fs-14 text-body">
                            Rental Aktif
                        </h3>

                        <div class="d-flex align-items-center">
                            <h2 class="fs-26 fw-bold mb-0 lh-1">
                                <?= (int) ($rentalActive ?? 0) ?>
                            </h2>

                            <i class="material-symbols-outlined fs-14 text-success ms-1">
                                trending_up
                            </i>

                            <span class="lh-1 fs-14 text-success">
                                Active
                            </span>
                        </div>
                    </div>

                    <div class="flex-shrink-0 ms-3 position-relative" style="width: 200px;">
                        <div class="w-100 position-absolute top-50 translate-middle-y" style="right: -8px;">
                            <div id="owner_rental_active_chart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card bg-white p-20 py-30 rounded-10 border border-white mb-4 position-relative z-1">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <i class="ri-money-dollar-circle-fill rounded-1 d-flex justify-content-center align-items-center fs-28 text-primary mb-3"
                        style="width: 50px; height: 50px; box-shadow: 0px 4px 20px 0px rgba(232, 231, 244, 0.70);">
                    </i>

                    <h3 class="mb-12 lh-1 fs-14 text-body">
                        Profit Bulan Ini
                    </h3>

                    <div class="d-flex align-items-center">
                        <h2 class="fs-22 fw-bold mb-0 lh-1 <?= ($profitThisMonth ?? 0) >= 0 ? 'text-success' : 'text-danger' ?>">
                            Rp <?= number_format((float) ($profitThisMonth ?? 0), 0, ',', '.') ?>
                        </h2>
                    </div>
                </div>

                <div class="flex-shrink-0 ms-3 position-relative" style="width: 200px;">
                    <div class="w-100 position-absolute top-50 translate-middle-y" style="right: -8px;">
                        <div id="owner_profit_chart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div class="col-xxl-6 col-xxxxxl-12">
    <div class="card bg-white p-40 rounded-10 border-0 mb-4 position-relative z-1 quick-view-bg" style="padding-top: 29px;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-20">
            <h3 class="text-white fs-26">Quick View</h3>

            <span class="badge bg-white text-primary">
                Realtime
            </span>
        </div>

        <div class="card bg-white rounded-10 border-0" style="box-shadow: 0px 4px 54px 0px rgba(215, 231, 223, 0.40);">
            <div class="row g-0">

                <div class="col-sm-6 border-bottom border-border-color-90">
                    <div class="card bg-white p-40 rounded-10 border border-white mb-0 position-relative z-1">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div class="flex-grow-1">
                                <h3 class="mb-10 lh-1 fs-14 text-body fw-medium">Saldo Bank</h3>

                                <h2 class="fs-22 fw-bold mb-2 lh-1">
                                    Rp <?= number_format((float) ($totalBankBalance ?? 0), 0, ',', '.') ?>
                                </h2>

                                <span class="d-inline-flex align-items-center gap-1 bg-success text-white rounded-1 mb-2" style="padding: 5px 8px;">
                                    <i class="material-symbols-outlined fs-14 text-white">trending_up</i>
                                    <span class="lh-1 fs-14 fw-bold">4.11%</span>
                                </span>

                                <p class="mb-0 fs-14">Total rekening aktif</p>
                            </div>

                            <i class="ri-bank-fill d-flex justify-content-center align-items-center bg-primary bg-opacity-10 fs-36 text-primary rounded-1"
                            style="width: 70px; height: 70px;"></i>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 border-bottom border-start border-border-color-90">
                    <div class="card bg-white p-40 rounded-10 border border-white mb-0 position-relative z-1">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div class="flex-grow-1">
                                <h3 class="mb-10 lh-1 fs-14 text-body fw-medium">Piutang</h3>

                                <h2 class="fs-22 fw-bold mb-2 lh-1 text-warning">
                                    Rp <?= number_format((float) ($outstandingReceivable ?? 0), 0, ',', '.') ?>
                                </h2>

                                <span class="d-inline-flex align-items-center gap-1 bg-danger text-white rounded-1 mb-2" style="padding: 5px 8px;">
                                    <i class="material-symbols-outlined fs-14 text-white">trending_down</i>
                                    <span class="lh-1 fs-14 fw-bold">1.25%</span>
                                </span>

                                <p class="mb-0 fs-14">Outstanding invoice</p>
                            </div>

                            <i class="ri-wallet-3-fill d-flex justify-content-center align-items-center bg-warning bg-opacity-10 fs-36 text-warning rounded-1"
                            style="width: 70px; height: 70px;"></i>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="card bg-white p-40 rounded-10 border border-white mb-0 position-relative z-1">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div class="flex-grow-1">
                                <h3 class="mb-10 lh-1 fs-14 text-body fw-medium">Rental Aktif</h3>

                                <h2 class="fs-26 fw-bold mb-2 lh-1">
                                    <?= (int) ($rentalActive ?? 0) ?>
                                </h2>

                                <span class="d-inline-flex align-items-center gap-1 bg-success text-white rounded-1 mb-2" style="padding: 5px 8px;">
                                    <i class="material-symbols-outlined fs-14 text-white">trending_up</i>
                                    <span class="lh-1 fs-14 fw-bold">Active</span>
                                </span>

                                <p class="mb-0 fs-14">Project berjalan</p>
                            </div>

                            <i class="ri-truck-fill d-flex justify-content-center align-items-center bg-info bg-opacity-10 fs-36 text-info rounded-1"
                            style="width: 70px; height: 70px;"></i>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 border-start border-border-color-90">
                    <div class="card bg-white p-40 rounded-10 border border-white mb-0 position-relative z-1">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div class="flex-grow-1">
                                <h3 class="mb-10 lh-1 fs-14 text-body fw-medium">Unit Available</h3>

                                <h2 class="fs-26 fw-bold mb-2 lh-1 text-success">
                                    <?= (int) ($unitAvailable ?? 0) ?>
                                </h2>

                                <span class="d-inline-flex align-items-center gap-1 bg-success text-white rounded-1 mb-2" style="padding: 5px 8px;">
                                    <i class="material-symbols-outlined fs-14 text-white">trending_up</i>
                                    <span class="lh-1 fs-14 fw-bold">Ready</span>
                                </span>

                                <p class="mb-0 fs-14">Ready untuk rental</p>
                            </div>

                            <i class="ri-checkbox-circle-fill d-flex justify-content-center align-items-center bg-success bg-opacity-10 fs-36 text-success rounded-1"
                            style="width: 70px; height: 70px;"></i>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</div>
<div class="row">
    <div class="col-xxl-3 col-xxxl-6">
        <div class="card bg-white rounded-10 border border-white mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">
                <h3>Alert Owner</h3>

                <span class="badge bg-danger bg-opacity-10 text-danger">
                    Priority
                </span>
            </div>

            <ul class="p-20 pt-0 mb-0 list-unstyled last-child-none" data-simplebar style="max-height: 390px; margin-top: 3.5px;">

                <li class="d-flex justify-content-between align-items-center border-border-color-50" style="border-bottom: 1px dashed; padding-bottom: 18px; margin-bottom: 18px;">
                    <div class="d-flex">
                        <div class="flex-shrink-0 position-relative top-3">
                            <i class="ri-error-warning-fill d-flex justify-content-center align-items-center fs-18 rounded-1 text-danger"
                            style="width: 32px; height: 32px; box-shadow: 0px 4px 20px 0px rgba(232, 231, 244, 0.70);">
                        </i>
                    </div>

                    <div class="flex-grow-1 ms-3">
                        <span class="fs-16 text-secondary d-block">Piutang Overdue</span>
                        <span class="fs-14">Invoice melewati jatuh tempo</span>
                    </div>
                </div>

                <div class="text-end">
                    <h3 class="fs-16"><?= count($overdueInvoices ?? []) ?></h3>
                    <span class="fs-14 fw-medium text-danger">Alert</span>
                </div>
            </li>

            <li class="d-flex justify-content-between align-items-center border-border-color-50" style="border-bottom: 1px dashed; padding-bottom: 18px; margin-bottom: 18px;">
                <div class="d-flex">
                    <div class="flex-shrink-0 position-relative top-3">
                        <i class="ri-tools-fill d-flex justify-content-center align-items-center fs-18 rounded-1 text-warning"
                        style="width: 32px; height: 32px; box-shadow: 0px 4px 20px 0px rgba(232, 231, 244, 0.70);">
                    </i>
                </div>

                <div class="flex-grow-1 ms-3">
                    <span class="fs-16 text-secondary d-block">Unit Maintenance</span>
                    <span class="fs-14">Unit belum siap rental</span>
                </div>
            </div>

            <div class="text-end">
                <h3 class="fs-16"><?= (int) ($unitMaintenance ?? 0) ?></h3>
                <span class="fs-14 fw-medium text-warning">Check</span>
            </div>
        </li>

        <li class="d-flex justify-content-between align-items-center border-border-color-50" style="border-bottom: 1px dashed; padding-bottom: 18px; margin-bottom: 18px;">
            <div class="d-flex">
                <div class="flex-shrink-0 position-relative top-3">
                    <i class="ri-close-circle-fill d-flex justify-content-center align-items-center fs-18 rounded-1 text-danger"
                    style="width: 32px; height: 32px; box-shadow: 0px 4px 20px 0px rgba(232, 231, 244, 0.70);">
                </i>
            </div>

            <div class="flex-grow-1 ms-3">
                <span class="fs-16 text-secondary d-block">Unit Broken</span>
                <span class="fs-14">Unit rusak / tidak bisa dipakai</span>
            </div>
        </div>

        <div class="text-end">
            <h3 class="fs-16"><?= (int) ($unitBroken ?? 0) ?></h3>
            <span class="fs-14 fw-medium text-danger">Urgent</span>
        </div>
    </li>

</ul>
</div>
</div>
<div class="col-xxl-6 col-xxxl-6">
    <div class="card bg-white rounded-10 border border-white p-20 mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-20">
            <h3>Income vs Expense</h3>

            <span class="badge bg-primary bg-opacity-10 text-primary">
                Tahun <?= date('Y') ?>
            </span>
        </div>

        <div style="margin: -30px -20px -15px -20px;">
            <div id="owner_income_expense_chart"></div>
        </div>
    </div>
</div>
<div class="col-xxl-3 col-xxxl-12">
    <div class="card bg-white rounded-10 border border-white p-20 mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-20">
            <h3>Status Unit</h3>

            <span class="badge bg-success bg-opacity-10 text-success">
                Realtime
            </span>
        </div>

        <div style="margin: -30px -20px -15px -20px;">
            <div id="owner_unit_status_chart"></div>
        </div>
    </div>
</div>
</div>
<div class="row">
    <div class="col-xxl-6 col-xxxl-6">
        <div class="card bg-white rounded-10 border border-white p-20 mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-20">
                <h3>Income Trend</h3>

                <span class="badge bg-success bg-opacity-10 text-success">
                    Tahun <?= date('Y') ?>
                </span>
            </div>

            <div style="margin: -25px -12px -15px -14px;">
                <div id="owner_income_trend_chart"></div>
            </div>
        </div>
    </div>
    <div class="col-xxl-6 col-xxxl-6">
        <div class="card bg-white rounded-10 border border-white mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">
                <h3>Piutang Jatuh Tempo</h3>

                <span class="badge bg-danger bg-opacity-10 text-danger">
                    Overdue
                </span>
            </div>

            <div class="default-table-area mx-minus-1 style-two table-browser-used for-border-color1">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th class="fw-normal text-body-color-40 fs-14">Invoice</th>
                                <th class="fw-normal text-body-color-40 fs-14">Customer</th>
                                <th class="fw-normal text-body-color-40 fs-14">Sisa Tagihan</th>
                                <th class="fw-normal text-body-color-40 fs-14">Overdue</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (!empty($overdueInvoices)): ?>
                                <?php foreach ($overdueInvoices as $invoice): ?>
                                    <tr>
                                        <td class="text-primary fs-16">
                                            <?= htmlspecialchars($invoice['no_invoice'] ?? '-') ?>
                                        </td>

                                        <td>
                                            <strong class="text-secondary fs-16">
                                                <?= htmlspecialchars($invoice['customer_name'] ?? '-') ?>
                                            </strong>

                                            <small class="text-muted d-block">
                                                Jatuh tempo:
                                                <?= !empty($invoice['due_date'])
                                                ? date('d M Y', strtotime($invoice['due_date']))
                                                : '-'
                                                ?>
                                            </small>
                                        </td>

                                        <td class="text-danger fw-medium">
                                            Rp <?= number_format((float) ($invoice['remaining_amount'] ?? 0), 0, ',', '.') ?>
                                        </td>

                                        <td>
                                            <span class="text-danger bg-danger bg-opacity-10 fs-14 fw-normal d-inline-block default-badge style-two border border-danger">
                                                <?= (int) ($invoice['overdue_days'] ?? 0) ?> Hari
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        Tidak ada piutang jatuh tempo.
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
    <div class="col-xxl-4">
        <div class="card bg-white rounded-10 border border-white p-20 mb-0 rounded-bottom-0">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-20">
                <h3>Top Quotation Bulan Ini</h3>

                <span class="badge bg-primary bg-opacity-10 text-primary">
                    Top 4
                </span>
            </div>

            <div style="margin: -29px -15px -26px -21px;">
                <div id="owner_top_quotation_chart"></div>
            </div>
        </div>

        <div class="card bg-white rounded-10 border border-white p-0 mb-4 rounded-top">
            <div class="default-table-area mx-minus-1 style-two table-browser-used for-width for-border-color1">
                <div class="table-responsive">
                    <table class="table align-middle w-100">
                        <thead>
                            <tr>
                                <th class="fw-normal text-body-color-40 fs-14">Quotation</th>
                                <th class="fw-normal text-body-color-40 fs-14">Customer</th>
                                <th class="fw-normal text-body-color-40 fs-14">Nilai</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (!empty($topQuotationsThisMonth)): ?>
                                <?php foreach ($topQuotationsThisMonth as $item): ?>
                                    <tr>
                                        <td class="text-primary fs-16">
                                            <?= htmlspecialchars($item['no_quotation'] ?? '-') ?>
                                        </td>

                                        <td class="text-secondary fs-16">
                                            <?= htmlspecialchars($item['customer_name'] ?? '-') ?>
                                        </td>

                                        <td class="text-secondary fs-16">
                                            Rp <?= number_format((float) ($item['total_value'] ?? 0), 0, ',', '.') ?>
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
    <div class="col-xxl-8">
        <div class="card bg-white rounded-10 border border-white p-20 mb-4">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-20">
                <h3>Cash Position</h3>

                <span class="badge bg-success bg-opacity-10 text-success">
                    Bank Active
                </span>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div id="owner_cash_position_chart" style="height: 351px;"></div>
                </div>

                <div class="col-lg-6">
                    <div class="d-flex flex-column gap-3">

                        <?php if (!empty($bankAccounts)): ?>
                            <?php foreach ($bankAccounts as $bank): ?>
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-3">
                                    <div>
                                        <h4 class="fs-15 mb-1">
                                            <?= htmlspecialchars($bank['bank_name'] ?? '-') ?>
                                        </h4>

                                        <small class="text-muted d-block">
                                            <?= htmlspecialchars($bank['account_name'] ?? '-') ?>
                                        </small>

                                        <small class="text-muted">
                                            <?= htmlspecialchars($bank['account_number'] ?? '-') ?>
                                        </small>
                                    </div>

                                    <strong class="text-primary">
                                        Rp <?= number_format((float) ($bank['current_balance'] ?? 0), 0, ',', '.') ?>
                                    </strong>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center text-muted py-4">
                                Belum ada rekening aktif.
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<div class="row">
    <div class="col-xxl-9 col-xxxl-6">
        <div class="card bg-white rounded-10 border border-white mb-4 p-20">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-1 mb-20">
                <h3>Trend Rental</h3>

                <span class="badge bg-primary bg-opacity-10 text-primary">
                    Overview
                </span>
            </div>

            <div style="margin: -24px -9px -26px -16px;">
                <div id="owner_rental_trend_chart"></div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-xxxl-12">
        <div class="card bg-white rounded-10 border border-white mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-1 p-20">
                <h3>Reminder Owner</h3>

                <span class="badge bg-warning bg-opacity-10 text-warning">
                    Attention
                </span>
            </div>

            <div class="default-table-area mx-minus-1 style-two for-border-color1">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <tbody>
                            <tr>
                                <td class="text-secondary fs-16">Piutang Overdue</td>
                                <td class="text-danger fs-16 fw-medium">
                                    <?= count($overdueInvoices ?? []) ?> Invoice
                                </td>
                            </tr>

                            <tr>
                                <td class="text-secondary fs-16">Unit Maintenance</td>
                                <td class="text-warning fs-16 fw-medium">
                                    <?= (int) ($unitMaintenance ?? 0) ?> Unit
                                </td>
                            </tr>

                            <tr>
                                <td class="text-secondary fs-16">Unit Broken</td>
                                <td class="text-danger fs-16 fw-medium">
                                    <?= (int) ($unitBroken ?? 0) ?> Unit
                                </td>
                            </tr>

                            <tr>
                                <td class="text-secondary fs-16">Rental Scheduled</td>
                                <td class="text-primary fs-16 fw-medium">
                                    <?= (int) ($rentalScheduled ?? 0) ?> Rental
                                </td>
                            </tr>

                            <tr class="last-child-border-none">
                                <td class="text-secondary fs-16">Selesai Besok</td>
                                <td class="text-success fs-16 fw-medium">
                                    <?= count($upcomingReturns ?? []) ?> Rental
                                </td>
                            </tr>
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

        const rupiah = function (value) {
            return "Rp " + new Intl.NumberFormat("id-ID").format(value || 0);
        };

        const renderChart = function (selector, options) {
            const el = document.querySelector(selector);

            if (!el || typeof ApexCharts === 'undefined') {
                return;
            }

            new ApexCharts(el, options).render();
        };

        renderChart("#owner_rental_active_chart", {
            series: [{
                data: [2, 3, 4, 3, 5, 6, <?= (int) ($rentalActive ?? 0) ?>]
            }],
            chart: {
                type: "area",
                height: 80,
                sparkline: { enabled: true },
                toolbar: { show: false }
            },
            stroke: {
                curve: "smooth",
                width: 3
            },
            fill: {
                opacity: 0.15
            },
            colors: ["#796df6"]
        });

        renderChart("#owner_profit_chart", {
            series: [{
                data: [5, 8, 6, 10, 9, 12, <?= (float) ($profitThisMonth ?? 0) ?>]
            }],
            chart: {
                type: "line",
                height: 80,
                sparkline: { enabled: true },
                toolbar: { show: false }
            },
            stroke: {
                curve: "smooth",
                width: 3
            },
            colors: ["#20c997"],
            tooltip: {
                y: {
                    formatter: rupiah
                }
            }
        });

        renderChart("#owner_income_expense_chart", {
            series: [
            {
                name: "Income",
                data: <?= json_encode($incomeMonthlyChart ?? []) ?>
            },
            {
                name: "Expense",
                data: <?= json_encode($expenseMonthlyChart ?? []) ?>
            }
            ],
            chart: {
                type: "line",
                height: 360,
                toolbar: { show: false }
            },
            stroke: {
                curve: "smooth",
                width: 4
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: [
                    "Jan", "Feb", "Mar", "Apr", "Mei", "Jun",
                    "Jul", "Agu", "Sep", "Okt", "Nov", "Des"
                    ]
            },
            yaxis: {
                labels: {
                    formatter: rupiah
                }
            },
            tooltip: {
                y: {
                    formatter: rupiah
                }
            },
            colors: ["#198754", "#dc3545"],
            grid: {
                borderColor: "#e5e7eb",
                strokeDashArray: 5
            },
            legend: {
                position: "top",
                horizontalAlign: "center"
            }
        });

        renderChart("#owner_unit_status_chart", {
            series: [
                <?= (int) ($unitAvailable ?? 0) ?>,
                <?= (int) ($unitMaintenance ?? 0) ?>,
                <?= (int) ($unitBroken ?? 0) ?>
                ],
            chart: {
                type: "donut",
                height: 330
            },
            labels: [
                "Available",
                "Maintenance",
                "Broken"
                ],
            dataLabels: {
                enabled: false
            },
            legend: {
                position: "bottom"
            },
            colors: ["#20c997", "#ffc107", "#dc3545"]
        });

        renderChart("#owner_income_trend_chart", {
            series: [
            {
                name: "Income",
                data: <?= json_encode($incomeMonthlyChart ?? []) ?>
            }
            ],
            chart: {
                type: "area",
                height: 340,
                toolbar: { show: false }
            },
            stroke: {
                curve: "smooth",
                width: 4
            },
            fill: {
                opacity: 0.18
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: [
                    "Jan", "Feb", "Mar", "Apr", "Mei", "Jun",
                    "Jul", "Agu", "Sep", "Okt", "Nov", "Des"
                    ]
            },
            yaxis: {
                labels: {
                    formatter: rupiah
                }
            },
            tooltip: {
                y: {
                    formatter: rupiah
                }
            },
            colors: ["#20c997"],
            grid: {
                borderColor: "#e5e7eb",
                strokeDashArray: 5
            }
        });
        renderChart("#owner_top_quotation_chart", {
            series: [
            {
                name: "Nilai",
                data: <?= json_encode(array_map('floatval', array_column($topQuotationsThisMonth ?? [], 'total_value'))) ?>
            }
            ],
            chart: {
                type: "bar",
                height: 260,
                toolbar: { show: false }
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: false,
                    columnWidth: "45%"
                }
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: <?= json_encode(array_column($topQuotationsThisMonth ?? [], 'no_quotation')) ?>
            },
            yaxis: {
                labels: {
                    formatter: rupiah
                }
            },
            tooltip: {
                y: {
                    formatter: rupiah
                }
            },
            colors: ["#796df6"],
            grid: {
                borderColor: "#e5e7eb",
                strokeDashArray: 5
            }
        });

        renderChart("#owner_cash_position_chart", {
            series: <?= json_encode(array_map('floatval', array_column($bankAccounts ?? [], 'current_balance'))) ?>,
            chart: {
                type: "donut",
                height: 340
            },
            labels: <?= json_encode(array_column($bankAccounts ?? [], 'bank_name')) ?>,
            dataLabels: {
                enabled: false
            },
            legend: {
                position: "bottom"
            },
            tooltip: {
                y: {
                    formatter: rupiah
                }
            },
            colors: ["#796df6", "#06b6d4", "#20c997", "#ffc107", "#dc3545"]
        });

        renderChart("#owner_rental_trend_chart", {
            series: [{
                name: "Rental",
                data: <?= json_encode($rentalTrendChart ?? []) ?>
            }],
            chart: {
                type: "area",
                height: 330,
                toolbar: { show: false }
            },
            stroke: {
                curve: "smooth",
                width: 4
            },
            fill: {
                opacity: 0.18
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: [
                    "Jan","Feb","Mar","Apr","Mei","Jun",
                    "Jul","Agu","Sep","Okt","Nov","Des"
                    ]
            },
            colors: ["#796df6"],
            grid: {
                borderColor: "#e5e7eb",
                strokeDashArray: 5
            }
        });

    });
</script>