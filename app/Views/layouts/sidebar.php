<div class="sidebar-area" id="sidebar-area">
    <div class="logo position-relative d-flex align-items-center justify-content-between">
        <a href="<?= url(isClientPortalUser() ? 'client/dashboard' : 'dashboard') ?>" class="d-block text-decoration-none position-relative">
            <img src="<?= $logoSrc ?>" alt="logo-icon">
        </a>
        <button class="sidebar-burger-menu-close bg-transparent py-3 border-0 opacity-0 z-n1 position-absolute top-50 end-0 translate-middle-y" id="sidebar-burger-menu-close">
            <span class="border-1 d-block for-dark-burger" style="border-bottom: 1px solid #475569; height: 1px; width: 25px; transform: rotate(45deg);"></span>
            <span class="border-1 d-block for-dark-burger" style="border-bottom: 1px solid #475569; height: 1px; width: 25px; transform: rotate(-45deg);"></span>
        </button>
        <button class="sidebar-burger-menu bg-transparent p-0 border-0" id="sidebar-burger-menu">
            <span class="border-1 d-block for-dark-burger" style="border-bottom: 1px solid #475569; height: 1px; width: 25px;"></span>
            <span class="border-1 d-block for-dark-burger" style="border-bottom: 1px solid #475569; height: 1px; width: 25px; margin: 6px 0;"></span>
            <span class="border-1 d-block for-dark-burger" style="border-bottom: 1px solid #475569; height: 1px; width: 25px;"></span>
        </button>
    </div>
    <aside id="layout-menu" class="layout-menu menu-vertical menu active" data-simplebar>
        <ul class="menu-inner">
            <?php if (isClientPortalUser()): ?>
                <?php $clientUnread = (new ClientNotification())->countUnread($_SESSION['user_id'] ?? 0); ?>
                <li class="menu-item <?= isActiveMenu(['client-dashboard']) ?>">
                    <a href="<?= url('client/dashboard') ?>" class="menu-link">
                        <span class="material-symbols-outlined menu-icon">dashboard</span>
                        <span class="title">Dashboard Portal</span>
                    </a>
                </li>
                <li class="menu-item <?= isActiveMenu(['client-events', 'client-event-show', 'client-event-approvals', 'client-event-documents', 'client-event-timeline', 'client-approval-show']) ?>">
                    <a href="<?= url('client/events') ?>" class="menu-link">
                        <span class="material-symbols-outlined menu-icon">event</span>
                        <span class="title">Event Saya</span>
                    </a>
                </li>
                <li class="menu-item <?= isActiveMenu(['client-notifications']) ?>">
                    <a href="<?= url('client/notifications') ?>" class="menu-link d-flex justify-content-between align-items-center">
                        <span class="d-flex align-items-center">
                            <span class="material-symbols-outlined menu-icon">notifications</span>
                            <span class="title">Notifikasi</span>
                        </span>
                        <?php if ($clientUnread > 0): ?>
                            <span class="badge bg-danger rounded-pill"><?= $clientUnread ?></span>
                        <?php endif; ?>
                    </a>
                </li>
            <?php else: ?>
            <?php
            $pendingApprovalCount = 0;
            if (!empty($_SESSION['user_id']) && class_exists('ApprovalRequest')) {
                $approvalRequestModel = new ApprovalRequest();

                $pendingApprovalCount = $approvalRequestModel->countMyPending(
                    $_SESSION['user_id'] ?? 0,
                    $_SESSION['role_id'] ?? 0
                );
            }
            ?>
            <?php if (can('dashboard.view')): ?>
                <li class="menu-item <?= isActiveMenu(['dashboard']) ?>">
                    <a href="<?= url('dashboard') ?>" class="menu-link">
                        <span class="material-symbols-outlined menu-icon">dashboard</span>
                        <span class="title">Dashboard</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if (can('approval_request.view')): ?>
                <li class="menu-item <?= isActiveMenu(['approval-requests', 'approval-requests-show']) ?>">
                    <a href="<?= url('approval-requests') ?>" class="menu-link d-flex justify-content-between align-items-center">
                        <span class="d-flex align-items-center">
                            <span class="material-symbols-outlined menu-icon">approval</span>
                            <span class="title">Approval Request</span>
                        </span>

                        <?php if ($pendingApprovalCount > 0): ?>
                            <span class="badge bg-danger rounded-pill">
                                <?= $pendingApprovalCount ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </li>
            <?php endif; ?>
            <?php if (can('master_event.manage')): ?>
                <li class="menu-item <?= isActiveMenu(['master-events', 'master-events-create', 'master-events-edit']) ?>">
                    <a href="<?= url('master-events') ?>" class="menu-link">
                        <span class="material-symbols-outlined menu-icon">event_note</span>
                        <span class="title">Master Event Client</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if (canOpenClientPortal()): ?>
                <li class="menu-item <?= isActiveMenu(['client-dashboard', 'client-events', 'client-event-show', 'client-event-approvals', 'client-event-documents', 'client-event-timeline', 'client-approval-show', 'client-notifications']) ?>">
                    <a href="<?= url('client/dashboard') ?>" class="menu-link">
                        <span class="material-symbols-outlined menu-icon">account_circle</span>
                        <span class="title">Client Portal</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php
            $websitePages = [
                'website-dashboard',

                'website-settings',

                'website-sliders',
                'website-sliders-create',
                'website-sliders-edit',

                'website-about',

                'website-services',
                'website-services-create',
                'website-services-edit',

                'website-products',
                'website-products-create',
                'website-products-edit',

                'website-posts',
                'website-posts-create',
                'website-posts-edit',

                'website-portfolios',
                'website-portfolios-create',
                'website-portfolios-edit',

                'website-testimonials',
                'website-testimonials-create',
                'website-testimonials-edit',

                'website-faqs',
                'website-faqs-create',
                'website-faqs-edit',

                'website-inquiries',
                'website-inquiries-show',
                'website-inquiries-edit'
            ];
            $reportPages = [
                'balance-sheet',
                'journal-entries',
                'journal-entries-show',
                'cash-flow',
                'general-ledger',
                'trial-balance',
                'profit-loss',
                'aging-receivables',
                'vendor-payable-aging',
                'unit-maintenance-report',
                'vehicle-maintenances-report'
            ];

            $financePages = [
                'bank-accounts',
                'bank-accounts-create',
                'bank-accounts-edit',
                'bank-transactions',
                'bank-transfers',
                'bank-transfers-create',
                'chart-of-accounts',
                'chart-of-accounts-create',
                'chart-of-accounts-edit',
                'expenses',
                'expenses-create',
                'expenses-show',
                'expenses-edit',
                'employee-cash-advances',
                'employee-cash-advances-create',
                'employee-cash-advances-edit',
                'employee-cash-advances-show'
            ];
            $salesPages = [
                'customers',
                'customers-create',
                'customers-edit',
                'customers-show',
                'quotations',
                'quotations-create',
                'quotations-edit',
                'quotations-show',
                'invoices',
                'invoices-create',
                'invoices-edit',
                'invoices-show',
                'rentals',
                'rentals-create',
                'rentals-show',
                'rentals-edit',
                'stock',
                'rental-items-create',
                'quotations-create-from-lead'
            ];

            $operationalPages = [
                'delivery-orders',
                'delivery-orders-show',
                'delivery-orders-create',
                'delivery-orders-edit',
                'calendar',
                'schedules',
                'technicians',
                'technician-schedules'
            ];

            $maintenancePages = [
                'unit-maintenance',
                'unit-maintenance-process',
                'unit-maintenance-store',
                'unit-maintenance-history',
                'unit-maintenance-show',
                'vehicle-usage-logs',
                'vehicle-usage-logs-create',
                'vehicle-usage-logs-store',
                'vehicle-maintenances',
                'vehicle-maintenances-process',
                'vehicle-maintenances-history',
                'vehicle-maintenances-show',
                'vehicles-reminders'
            ];

            $masterUnitPages = [
                'units',
                'units-create',
                'units-edit',
                'partner-units',
                'partner-units-create',
                'partner-units-edit'
            ];

            $purchasePages = [
                'vendors',
                'vendors-create',
                'vendors-edit',
                'vendor-bills',
                'vendor-bills-create',
                'vendor-bills-show',
                'vendor-bill-payments-create',
                'purchase-requests',
                'purchase-requests-create',
                'purchase-requests-show',
                'purchase-requests-edit',
                'purchase-orders',
                'purchase-orders-create',
                'purchase-orders-show',
                'purchase-orders-edit',
                'goods-receipts',
                'goods-receipts-create',
                'goods-receipts-show',
            ];

            $masterDataPages = array_merge($masterUnitPages, [
                'users',
                'users-create',
                'products-service', 
                'products-service-create', 
                'products-service-edit', 
                'products-service-show',
                'vehicles', 
                'vehicles-create', 
                'vehicles-edit',
                'roles',
                'roles-edit',
                'roles-show',
                'roles-permissions',
                'approval-matrices',
                'approval-matrices-create',
                'approval-matrices-show',
                'approval-matrices-edit',
            ]);

            $hrisPages = [
                'employees',
                'employees-create',
                'employees-edit',
                'employees-show',
                'departments',
                'departments-create',
                'departments-edit',
                'positions',
                'positions-create',
                'positions-edit',
                'attendances',
                'attendances-create',
                'attendances-show',
                'attendances-edit',
                'leave-requests',
                'leave-requests-create',
                'leave-requests-show',
                'leave-requests-edit',
                'overtime-requests',
                'overtime-requests-create',
                'overtime-requests-show',
                'overtime-requests-edit',
                'payroll-periods',
                'payroll-periods-create',
                'payroll-periods-show',
                'payroll-periods-edit',
                'payrolls',
                'payrolls-show',
                'payrolls-print',
                'payrolls-edit',
                'payrolls-paid',
                'recruitment-applicants',
                'recruitment-applicants-create',
                'recruitment-applicants-show',
                'recruitment-applicants-edit',
                'employee_contract.view'
            ];

            $marketingPages = [
                'marketing-leads',
                'marketing-leads-create',
                'marketing-leads-show',
                'marketing-leads-edit'
            ];
            ?>
            <?php if (canAny([
                'balance_sheet.view',
                'journal_entry_report.view',
                'cash_flow_report.view',
                'general_ledger_report.view',
                'trial_balance_report.view',
                'profit_loss_report.view',
                'aging_receivable_report.view',
                'vendor_payable_report.view',
                'unit_maintenance_report.view',
                'vehicle_maintenance_report.view'
            ])): ?>

            <li class="menu-item <?= isActiveMenu($reportPages) ?> <?= isOpenMenu($reportPages) ?>">

                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <span class="material-symbols-outlined menu-icon">analytics</span>
                    <span class="title">Laporan</span>
                </a>

                <ul class="menu-sub">

                    <?php if (can('balance_sheet.view')): ?>
                        <li class="menu-item <?= isActiveMenu(['balance-sheet']) ?>">
                            <a href="<?= url('balance-sheet') ?>" class="menu-link">
                                Neraca
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (can('journal_entry_report.view')): ?>
                        <li class="menu-item <?= isActiveMenu(['journal-entries', 'journal-entries-show']) ?>">
                            <a href="<?= url('journal-entries') ?>" class="menu-link">
                                Jurnal Umum
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (can('cash_flow_report.view')): ?>
                        <li class="menu-item <?= isActiveMenu(['cash-flow']) ?>">
                            <a href="<?= url('cash-flow') ?>" class="menu-link">
                                Arus Kas
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (can('general_ledger_report.view')): ?>
                        <li class="menu-item <?= isActiveMenu(['general-ledger']) ?>">
                            <a href="<?= url('general-ledger') ?>" class="menu-link">
                                Buku Besar
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (can('trial_balance_report.view')): ?>
                        <li class="menu-item <?= isActiveMenu(['trial-balance']) ?>">
                            <a href="<?= url('trial-balance') ?>" class="menu-link">
                                Neraca Saldo
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (can('profit_loss_report.view')): ?>
                        <li class="menu-item <?= isActiveMenu(['profit-loss']) ?>">
                            <a href="<?= url('profit-loss') ?>" class="menu-link">
                                Laba Rugi
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (can('aging_receivable_report.view')): ?>
                        <li class="menu-item <?= isActiveMenu(['aging-receivables']) ?>">
                            <a href="<?= url('aging-receivables') ?>" class="menu-link">
                                Aging Piutang
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (can('vendor_payable_report.view')): ?>
                        <li class="menu-item <?= isActiveMenu(['vendor-payable-aging']) ?>">
                            <a href="<?= url('vendor-payable-aging') ?>" class="menu-link">
                                Aging Hutang Vendor
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (can('unit_maintenance_report.view')): ?>
                        <li class="menu-item <?= isActiveMenu(['unit-maintenance-report']) ?>">
                            <a href="<?= url('unit-maintenance-report') ?>" class="menu-link">
                                Service AC
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (can('vehicle_maintenance_report.view')): ?>
                        <li class="menu-item <?= isActiveMenu(['vehicle-maintenances-report']) ?>">
                            <a href="<?= url('vehicle-maintenances-report') ?>" class="menu-link">
                                Service Kendaraan
                            </a>
                        </li>
                    <?php endif; ?>

                </ul>

            </li>

        <?php endif; ?>

        <?php if (canAny([
            'bank_account.view',
            'bank_transfer.view',
            'expense.view',
            'chart_of_account.view',
            'employee_cash_advance.view'
        ])): ?>

        <li class="menu-item <?= isActiveMenu($financePages) ?> <?= isOpenMenu($financePages) ?>">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <span class="material-symbols-outlined menu-icon">account_balance_wallet</span>
                <span class="title">Keuangan</span>
            </a>

            <ul class="menu-sub">

                <?php if (can('bank_account.view')): ?>
                    <li class="menu-item <?= isActiveMenu([
                        'bank-accounts',
                        'bank-transactions',
                        'bank-accounts-create',
                        'bank-accounts-edit'
                        ]) ?>">
                        <a href="<?= url('bank-accounts') ?>" class="menu-link">
                            Kas & Bank
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (can('bank_transfer.view')): ?>
                    <li class="menu-item <?= isActiveMenu([
                        'bank-transfers',
                        'bank-transfers-create'
                        ]) ?>">
                        <a href="<?= url('bank-transfers') ?>" class="menu-link">
                            Transfer Bank
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (can('expense.view')): ?>
                    <li class="menu-item <?= isActiveMenu([
                        'expenses',
                        'expenses-create',
                        'expenses-show',
                        'expenses-edit'
                        ]) ?>">
                        <a href="<?= url('expenses') ?>" class="menu-link">
                            Pengeluaran
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (can('chart_of_account.view')): ?>
                    <li class="menu-item <?= isActiveMenu([
                        'chart-of-accounts',
                        'chart-of-accounts-create',
                        'chart-of-accounts-edit'
                        ]) ?>">
                        <a href="<?= url('chart-of-accounts') ?>" class="menu-link">
                            Chart of Accounts
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (can('employee_cash_advance.view')): ?>
                    <li class="menu-item <?= isActiveMenu([
                        'employee-cash-advances',
                        'employee-cash-advances-create',
                        'employee-cash-advances-show',
                        'employee-cash-advances-edit'
                        ]) ?>">
                        <a href="<?= url('employee-cash-advances') ?>" class="menu-link">
                            Kasbon
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </li>

    <?php endif; ?>
    <?php if (canAny([
        'customer.view',
        'quotation.view',
        'invoice.view',
        'rental.view',
        'stock_check.view'
    ])): ?>

    <li class="menu-item <?= isActiveMenu($salesPages) ?> <?= isOpenMenu($salesPages) ?>">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <span class="material-symbols-outlined menu-icon">point_of_sale</span>
            <span class="title">Penjualan</span>
        </a>

        <ul class="menu-sub">

            <?php if (can('customer.view')): ?>
                <li class="menu-item <?= isActiveMenu(['customers', 'customers-create', 'customers-show', 'customers-edit']) ?>">
                    <a href="<?= url('customers') ?>" class="menu-link">
                        Customer
                    </a>
                </li>
            <?php endif; ?>

            <?php if (can('quotation.view')): ?>
                <li class="menu-item <?= isActiveMenu(['quotations', 'quotations-create', 'quotations-edit', 'quotations-show']) ?>">
                    <a href="<?= url('quotations') ?>" class="menu-link">
                        Penawaran
                    </a>
                </li>
            <?php endif; ?>

            <?php if (can('invoice.view')): ?>
                <li class="menu-item <?= isActiveMenu(['invoices', 'invoices-create', 'invoices-edit', 'invoices-show']) ?>">
                    <a href="<?= url('invoices') ?>" class="menu-link">
                        Invoice
                    </a>
                </li>
            <?php endif; ?>

            <?php if (can('rental.view')): ?>
                <li class="menu-item <?= isActiveMenu(['rentals', 'rentals-create', 'rentals-show', 'rentals-edit', 'rental-items-create']) ?>">
                    <a href="<?= url('rentals') ?>" class="menu-link">
                        Order
                    </a>
                </li>
            <?php endif; ?>

            <?php if (can('stock_check.view')): ?>
                <li class="menu-item <?= isActiveMenu(['stock']) ?>">
                    <a href="<?= url('stock') ?>" class="menu-link">
                        Cek Stok
                    </a>
                </li>
            <?php endif; ?>

        </ul>
    </li>

<?php endif; ?>
<?php if (canAny([
    'vendor.view',
    'vendor_bill.view',
    'purchase_order.view',
    'goods_receipt.view',
    'purchase_request.view'
])): ?>

<li class="menu-item <?= isActiveMenu($purchasePages) ?> <?= isOpenMenu($purchasePages) ?>">

    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <span class="material-symbols-outlined menu-icon">shopping_cart</span>
        <span class="title">Pembelian</span>
    </a>
    <ul class="menu-sub">
        <?php if (can('purchase_request.view')): ?>
            <li class="menu-item <?= isActiveMenu(['purchase-requests']) ?>">
                <a href="<?= url('purchase-requests') ?>" class="menu-link">
                    Purchase Request
                </a>
            </li>
        <?php endif; ?>
        <?php if (can('purchase_order.view')): ?>
            <li class="menu-item <?= isActiveMenu(['purchase-orders']) ?>">
                <a href="<?= url('purchase-orders') ?>" class="menu-link">
                    <span class="menu-bullet">
                        <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="title">Purchase Order</span>
                </a>
            </li>
        <?php endif; ?>
        <?php if (can('goods_receipt.view')): ?>
            <li class="menu-item <?= isActiveMenu(['goods-receipts']) ?>">
                <a href="<?= url('goods-receipts') ?>" class="menu-link">
                    Penerimaan Barang
                </a>
            </li>
        <?php endif; ?>
        <?php if (can('vendor.view')): ?>
            <li class="menu-item <?= isActiveMenu([
                'vendors',
                'vendors-create',
                'vendors-edit'
                ]) ?>">
                <a href="<?= url('vendors') ?>" class="menu-link">
                    Vendor
                </a>
            </li>
        <?php endif; ?>

        <?php if (can('vendor_bill.view')): ?>
            <li class="menu-item <?= isActiveMenu([
                'vendor-bills',
                'vendor-bills-create',
                'vendor-bills-show',
                'vendor-bill-payments-create'
                ]) ?>">
                <a href="<?= url('vendor-bills') ?>" class="menu-link">
                    Hutang Vendor
                </a>
            </li>
        <?php endif; ?>

    </ul>

</li>

<?php endif; ?>


<?php if (canAny([
    'delivery_order.view',
    'calendar.view',
    'schedule.view',
    'technician.view',
    'technician_schedule.view'
])): ?>

<li class="menu-item <?= isActiveMenu($operationalPages) ?> <?= isOpenMenu($operationalPages) ?>">

    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <span class="material-symbols-outlined menu-icon">engineering</span>
        <span class="title">Operasional</span>
    </a>

    <ul class="menu-sub">

        <?php if (can('delivery_order.view')): ?>
            <li class="menu-item <?= isActiveMenu([
                'delivery-orders',
                'delivery-orders-show',
                'delivery-orders-create',
                'delivery-orders-edit'
                ]) ?>">
                <a href="<?= url('delivery-orders') ?>" class="menu-link">
                    Surat Jalan
                </a>
            </li>
        <?php endif; ?>

        <?php if (can('calendar.view')): ?>
            <li class="menu-item <?= isActiveMenu(['calendar']) ?>">
                <a href="<?= url('calendar') ?>" class="menu-link">
                    Kalender Rental
                </a>
            </li>
        <?php endif; ?>

        <?php if (can('schedule.view')): ?>
            <li class="menu-item <?= isActiveMenu(['schedules']) ?>">
                <a href="<?= url('schedules') ?>" class="menu-link">
                    Jadwal Harian
                </a>
            </li>
        <?php endif; ?>

        <?php if (can('technician.view')): ?>
            <li class="menu-item <?= isActiveMenu(['technicians']) ?>">
                <a href="<?= url('technicians') ?>" class="menu-link">
                    Teknisi
                </a>
            </li>
        <?php endif; ?>

        <?php if (can('technician_schedule.view')): ?>
            <li class="menu-item <?= isActiveMenu(['technician-schedules']) ?>">
                <a href="<?= url('technician-schedules') ?>" class="menu-link">
                    Jadwal Teknisi
                </a>
            </li>
        <?php endif; ?>

    </ul>
</li>

<?php endif; ?>


<?php if (canAny([
    'unit_maintenance.view',
    'unit_maintenance_history.view',
    'vehicle_usage_log.view',
    'vehicle_maintenance.view',
    'vehicle_maintenance_history.view',
    'vehicle_reminder.view'
])): ?>

<li class="menu-item <?= isActiveMenu($maintenancePages) ?> <?= isOpenMenu($maintenancePages) ?>">

    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <span class="material-symbols-outlined menu-icon">build</span>
        <span class="title">Maintenance</span>
    </a>

    <ul class="menu-sub">

        <?php if (can('unit_maintenance.view')): ?>
            <li class="menu-item <?= isActiveMenu([
                'unit-maintenance',
                'unit-maintenance-process',
                'unit-maintenance-store'
                ]) ?>">
                <a href="<?= url('unit-maintenance') ?>" class="menu-link">
                    Jadwal Service Unit
                </a>
            </li>
        <?php endif; ?>

        <?php if (can('unit_maintenance_history.view')): ?>
            <li class="menu-item <?= isActiveMenu([
                'unit-maintenance-history',
                'unit-maintenance-show'
                ]) ?>">
                <a href="<?= url('unit-maintenance-history') ?>" class="menu-link">
                    History Service Unit
                </a>
            </li>
        <?php endif; ?>

        <?php if (can('vehicle_usage_log.view')): ?>
            <li class="menu-item <?= isActiveMenu([
                'vehicle-usage-logs',
                'vehicle-usage-logs-create',
                'vehicle-usage-logs-store'
                ]) ?>">
                <a href="<?= url('vehicle-usage-logs') ?>" class="menu-link">
                    Pemakaian Kendaraan
                </a>
            </li>
        <?php endif; ?>

        <?php if (can('vehicle_maintenance.view')): ?>
            <li class="menu-item <?= isActiveMenu([
                'vehicle-maintenances',
                'vehicle-maintenances-process'
                ]) ?>">
                <a href="<?= url('vehicle-maintenances') ?>" class="menu-link">
                    Service Kendaraan
                </a>
            </li>
        <?php endif; ?>

        <?php if (can('vehicle_maintenance_history.view')): ?>
            <li class="menu-item <?= isActiveMenu([
                'vehicle-maintenances-history',
                'vehicle-maintenances-show'
                ]) ?>">
                <a href="<?= url('vehicle-maintenances-history') ?>" class="menu-link">
                    History Service
                </a>
            </li>
        <?php endif; ?>

        <?php if (can('vehicle_reminder.view')): ?>
            <li class="menu-item <?= isActiveMenu(['vehicles-reminders']) ?>">
                <a href="<?= url('vehicles-reminders') ?>" class="menu-link">
                    STNK, KIR & Pajak
                </a>
            </li>
        <?php endif; ?>

    </ul>

</li>

<?php endif; ?>


<?php if (canAny([
    'attendance.view',
    'leave_request.view',
    'overtime_request.view',
    'payroll.view',
    'employee.view',
    'department.view',
    'position.view',
    'recruitment_applicant.view',
    'employee_contract.view'
])): ?>

<li class="menu-item <?= isActiveMenu($hrisPages) ?> <?= isOpenMenu($hrisPages) ?>">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <span class="material-symbols-outlined menu-icon">groups</span>
        <span class="title">Kepegawaian</span>
    </a>

    <ul class="menu-sub">

        <?php if (can('attendance.view')): ?>
            <li class="menu-item <?= isActiveMenu(['attendances', 'attendances-create', 'attendances-show', 'attendances-edit']) ?>">
                <a href="<?= url('attendances') ?>" class="menu-link">Absensi</a>
            </li>
        <?php endif; ?>

        <?php if (can('leave_request.view')): ?>
            <li class="menu-item <?= isActiveMenu(['leave-requests', 'leave-requests-create', 'leave-requests-show', 'leave-requests-edit']) ?>">
                <a href="<?= url('leave-requests') ?>" class="menu-link">Cuti / Izin</a>
            </li>
        <?php endif; ?>

        <?php if (can('overtime_request.view')): ?>
            <li class="menu-item <?= isActiveMenu(['overtime-requests', 'overtime-requests-create', 'overtime-requests-show', 'overtime-requests-edit']) ?>">
                <a href="<?= url('overtime-requests') ?>" class="menu-link">Lembur</a>
            </li>
        <?php endif; ?>

        <?php if (can('payroll.view')): ?>
            <li class="menu-item <?= isActiveMenu([
                'payroll-periods',
                'payrolls-show',
                'payrolls-print',
                'payrolls-edit',
                'payrolls-paid',
                'payroll-periods-create',
                'payroll-periods-show',
                'payroll-periods-edit',
                'payrolls'
                ]) ?>">
                <a href="<?= url('payroll-periods') ?>" class="menu-link">Payroll</a>
            </li>
        <?php endif; ?>

        <?php if (can('employee.view')): ?>
            <li class="menu-item <?= isActiveMenu(['employees', 'employees-create', 'employees-edit', 'employees-show']) ?>">
                <a href="<?= url('employees') ?>" class="menu-link">Data Karyawan</a>
            </li>
        <?php endif; ?>

        <?php if (can('recruitment_applicant.view')): ?>
            <li class="menu-item <?= isActiveMenu([
                'recruitment-applicants',
                'recruitment-applicants-create',
                'recruitment-applicants-show',
                'recruitment-applicants-edit'
                ]) ?>">
                <a href="<?= url('recruitment-applicants') ?>" class="menu-link">
                    Rekrutmen
                </a>
            </li>
        <?php endif; ?>

        <?php if (can('employee_contract.view')): ?>
            <li class="menu-item <?= isActiveMenu([
                'employee-contracts',
                'employee-contracts-create',
                'employee-contracts-show',
                'employee-contracts-edit'
                ]) ?>">
                <a href="<?= url('employee-contracts') ?>" class="menu-link">

                    <span>Kontrak Karyawan</span>
                </a>
            </li>
        <?php endif; ?>

        <?php if (can('department.view')): ?>
            <li class="menu-item <?= isActiveMenu(['departments', 'departments-create', 'departments-edit']) ?>">
                <a href="<?= url('departments') ?>" class="menu-link">Divisi</a>
            </li>
        <?php endif; ?>

        <?php if (can('position.view')): ?>
            <li class="menu-item <?= isActiveMenu(['positions', 'positions-create', 'positions-edit']) ?>">
                <a href="<?= url('positions') ?>" class="menu-link">Jabatan</a>
            </li>
        <?php endif; ?>

    </ul>
</li>
<?php endif; ?>

<?php if (canAny([
    'marketing_lead.view'
])): ?>

<li class="menu-item <?= isActiveMenu($marketingPages) ?> <?= isOpenMenu($marketingPages) ?>">

    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <span class="material-symbols-outlined menu-icon">campaign</span>
        <span class="title">Marketing</span>
    </a>

    <ul class="menu-sub">

        <?php if (can('marketing_lead.view')): ?>
            <li class="menu-item <?= isActiveMenu([
                'marketing-leads',
                'marketing-leads-create',
                'marketing-leads-show',
                'marketing-leads-edit'
                ]) ?>">
                <a href="<?= url('marketing-leads') ?>" class="menu-link">
                    Leads
                </a>
            </li>
        <?php endif; ?>

    </ul>

</li>

<?php endif; ?>
<?php if (canAny([
    'website_dashboard.view',
    'website_setting.view',
    'website_slider.view',
    'website_about.view',
    'website_service.view',
    'website_product.view',
    'website_post.view',
    'website_portfolio.view',
    'website_testimonial.view',
    'website_faq.view',
    'website_inquiry.view'
])): ?>

<li class="menu-item <?= isActiveMenu($websitePages) ?> <?= isOpenMenu($websitePages) ?>">

    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <span class="material-symbols-outlined menu-icon">language</span>
        <span class="title">Website CMS</span>
    </a>

    <ul class="menu-sub">

        <?php if (can('website_dashboard.view')): ?>
        <li class="menu-item <?= isActiveMenu(['website-dashboard']) ?>">
            <a href="<?= url('website-dashboard') ?>" class="menu-link">
                Dashboard Website
            </a>
        </li>
        <?php endif; ?>

        <?php if (can('website_setting.view')): ?>
        <li class="menu-item <?= isActiveMenu(['website-settings']) ?>">
            <a href="<?= url('website-settings') ?>" class="menu-link">
                Website Setting
            </a>
        </li>
        <?php endif; ?>

        <?php if (can('website_slider.view')): ?>
        <li class="menu-item <?= isActiveMenu([
            'website-sliders',
            'website-sliders-create',
            'website-sliders-edit'
        ]) ?>">
            <a href="<?= url('website-sliders') ?>" class="menu-link">
                Slider Homepage
            </a>
        </li>
        <?php endif; ?>

        <?php if (can('website_about.view')): ?>
        <li class="menu-item <?= isActiveMenu(['website-about']) ?>">
            <a href="<?= url('website-about') ?>" class="menu-link">
                Tentang Kami
            </a>
        </li>
        <?php endif; ?>

        <?php if (can('website_service.view')): ?>
        <li class="menu-item <?= isActiveMenu([
            'website-services',
            'website-services-create',
            'website-services-edit'
        ]) ?>">
            <a href="<?= url('website-services') ?>" class="menu-link">
                Layanan
            </a>
        </li>
        <?php endif; ?>

        <?php if (can('website_product.view')): ?>
        <li class="menu-item <?= isActiveMenu([
            'website-products',
            'website-products-create',
            'website-products-edit'
        ]) ?>">
            <a href="<?= url('website-products') ?>" class="menu-link">
                Produk
            </a>
        </li>
        <?php endif; ?>

        <?php if (can('website_post.view')): ?>
        <li class="menu-item <?= isActiveMenu([
            'website-posts',
            'website-posts-create',
            'website-posts-edit'
        ]) ?>">
            <a href="<?= url('website-posts') ?>" class="menu-link">
                Artikel
            </a>
        </li>
        <?php endif; ?>

        <?php if (can('website_portfolio.view')): ?>
        <li class="menu-item <?= isActiveMenu([
            'website-portfolios',
            'website-portfolios-create',
            'website-portfolios-edit'
        ]) ?>">
            <a href="<?= url('website-portfolios') ?>" class="menu-link">
                Portfolio Event
            </a>
        </li>
        <?php endif; ?>

        <?php if (can('website_testimonial.view')): ?>
        <li class="menu-item <?= isActiveMenu([
            'website-testimonials',
            'website-testimonials-create',
            'website-testimonials-edit'
        ]) ?>">
            <a href="<?= url('website-testimonials') ?>" class="menu-link">
                Testimoni
            </a>
        </li>
        <?php endif; ?>

        <?php if (can('website_faq.view')): ?>
        <li class="menu-item <?= isActiveMenu([
            'website-faqs',
            'website-faqs-create',
            'website-faqs-edit'
        ]) ?>">
            <a href="<?= url('website-faqs') ?>" class="menu-link">
                FAQ
            </a>
        </li>
        <?php endif; ?>

        <?php if (can('website_inquiry.view')): ?>
        <li class="menu-item <?= isActiveMenu([
            'website-inquiries',
            'website-inquiries-show',
            'website-inquiries-edit'
        ]) ?>">
            <a href="<?= url('website-inquiries') ?>" class="menu-link">
                Inquiry Leads
            </a>
        </li>
        <?php endif; ?>

    </ul>

</li>

<?php endif; ?>
<?php if (canAny([
    'unit.view',
    'partner_unit.view',
    'user.view',
    'role.view',
    'vehicle.view',
    'product_service.view',
    'activity_logs.view',
    'approval_matrix.view'
])): ?>

<li class="menu-item <?= isActiveMenu($masterDataPages) ?> <?= isOpenMenu($masterDataPages) ?>">

    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <span class="material-symbols-outlined menu-icon">database</span>
        <span class="title">Master Data</span>
    </a>

    <ul class="menu-sub">

        <?php if (canAny(['unit.view', 'partner_unit.view'])): ?>
            <li class="menu-item <?= isActiveMenu($masterUnitPages) ?> <?= isOpenMenu($masterUnitPages) ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    Unit Rental
                </a>

                <ul class="menu-sub">

                    <?php if (can('unit.view')): ?>
                        <li class="menu-item <?= isActiveMenu(['units', 'units-create', 'units-edit']) ?>">
                            <a href="<?= url('units') ?>" class="menu-link">
                                Internal
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (can('partner_unit.view')): ?>
                        <li class="menu-item <?= isActiveMenu(['partner-units', 'partner-units-create', 'partner-units-edit']) ?>">
                            <a href="<?= url('partner-units') ?>" class="menu-link">
                                Vendor
                            </a>
                        </li>
                    <?php endif; ?>

                </ul>
            </li>
        <?php endif; ?>


        <?php if (can('user.view')): ?>
            <li class="menu-item <?= isActiveMenu(['users', 'users-create']) ?>">
                <a href="<?= url('users') ?>" class="menu-link">
                    Users
                </a>
            </li>
        <?php endif; ?>


        <?php if (can('role.view')): ?>
            <li class="menu-item <?= isActiveMenu(['roles', 'roles-create', 'roles-edit', 'roles-permissions']) ?>">
                <a href="<?= url('roles') ?>" class="menu-link">
                    Role & Hak Akses
                </a>
            </li>
        <?php endif; ?>
        <?php if (can('approval_matrix.view')): ?>
            <li class="menu-item <?= isActiveMenu([
                'approval-matrices',
                'approval-matrices-create',
                'approval-matrices-show',
                'approval-matrices-edit'
                ]) ?>">
                <a href="<?= url('approval-matrices') ?>" class="menu-link">
                    DOA Matrix
                </a>
            </li>
        <?php endif; ?>
        <?php if (can('vehicle.view')): ?>
            <li class="menu-item <?= isActiveMenu(['vehicles', 'vehicles-create', 'vehicles-edit']) ?>">
                <a href="<?= url('vehicles') ?>" class="menu-link">
                    Kendaraan
                </a>
            </li>
        <?php endif; ?>
        <?php if (can('activity_logs.view')): ?>
            <li class="menu-item <?= isActiveMenu(['activity-logs']) ?>">
                <a href="<?= url('activity-logs') ?>" class="menu-link">
                    <span class="title">Activity Logs</span>
                </a>
            </li>
        <?php endif; ?>
        <?php if (can('product_service.view')): ?>
            <li class="menu-item <?= isActiveMenu([
                'products-service',
                'products-service-create',
                'products-service-edit',
                'products-service-show'
                ]) ?>">
                <a href="<?= url('products-service') ?>" class="menu-link">
                    Produk & Jasa
                </a>
            </li>
        <?php endif; ?>

    </ul>
</li>

<?php endif; ?>
<?php endif; ?>
<li class="menu-item">
    <a href="<?= url('logout') ?>" class="menu-link">
        <span class="material-symbols-outlined menu-icon">logout</span>
        <span class="title">Logout</span>
    </a>
</li>

</ul>
</aside>
</div>
