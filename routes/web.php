<?php

$page = $_GET['page'] ?? 'home';

// Public-looking client URLs are mapped to internal MVC route names.
if ($page === 'client/dashboard') {
    $page = 'client-dashboard';
} elseif ($page === 'client/events') {
    $page = 'client-events';
} elseif ($page === 'client/notifications') {
    $page = 'client-notifications';
} elseif (preg_match('#^client/events/(\d+)/peserta/qr-code/aktifkan$#', $page, $matches)) {
    $page = 'client-event-barcode-activate';
    $_GET['event_id'] = $matches[1];
} elseif (preg_match('#^client/events/(\d+)/peserta/qr-code$#', $page, $matches)) {
    $page = 'client-event-barcode';
    $_GET['event_id'] = $matches[1];
} elseif (preg_match('#^client/events/(\d+)/peserta/barcode/aktifkan$#', $page, $matches)) {
    $page = 'client-event-barcode-activate';
    $_GET['event_id'] = $matches[1];
} elseif (preg_match('#^client/events/(\d+)/peserta/barcode$#', $page, $matches)) {
    $page = 'client-event-barcode';
    $_GET['event_id'] = $matches[1];
} elseif (preg_match('#^client/events/(\d+)/peserta$#', $page, $matches)) {
    $page = 'client-event-attendees';
    $_GET['event_id'] = $matches[1];
} elseif (preg_match('#^client/events/(\d+)/konten/simpan$#', $page, $matches)) {
    $page = 'client-event-content-store';
    $_GET['event_id'] = $matches[1];
} elseif (preg_match('#^client/events/(\d+)/konten/(\d+)/hapus$#', $page, $matches)) {
    $page = 'client-event-content-delete';
    $_GET['event_id'] = $matches[1];
    $_GET['id'] = $matches[2];
} elseif (preg_match('#^client/events/(\d+)/konten$#', $page, $matches)) {
    $page = 'client-event-content';
    $_GET['event_id'] = $matches[1];
} elseif (preg_match('#^client/events/(\d+)/approvals$#', $page, $matches)) {
    $page = 'client-event-approvals';
    $_GET['event_id'] = $matches[1];
} elseif (preg_match('#^client/events/(\d+)/documents$#', $page, $matches)) {
    $page = 'client-event-documents';
    $_GET['event_id'] = $matches[1];
} elseif (preg_match('#^client/events/(\d+)/timeline$#', $page, $matches)) {
    $page = 'client-event-timeline';
    $_GET['event_id'] = $matches[1];
} elseif (preg_match('#^client/events/(\d+)$#', $page, $matches)) {
    $page = 'client-event-show';
    $_GET['id'] = $matches[1];
} elseif (preg_match('#^client/approvals/(\d+)/approve$#', $page, $matches)) {
    $page = 'client-approval-approve';
    $_GET['id'] = $matches[1];
} elseif (preg_match('#^client/approvals/(\d+)/revision$#', $page, $matches)) {
    $page = 'client-approval-revision';
    $_GET['id'] = $matches[1];
} elseif (preg_match('#^client/approvals/(\d+)$#', $page, $matches)) {
    $page = 'client-approval-show';
    $_GET['id'] = $matches[1];
} elseif (preg_match('#^client/documents/(\d+)/download$#', $page, $matches)) {
    $page = 'client-document-download';
    $_GET['id'] = $matches[1];
}

$_GET['page'] = $page;

if (str_starts_with($page, 'api/mobile')) {
    (new MobileApiController())->dispatch($page);
    exit;
}

if (str_starts_with($page, 'client-')) {
    requireClientPortalLogin();
}

$protectedRoutePermissions = [
    'vehicle-maintenances-report' => 'vehicle_maintenance_report.view',
    'vehicle-maintenances-history' => 'vehicle_maintenance_history.view',
    'vehicle-maintenances' => 'vehicle_maintenance.view',
    'vehicle-usage-logs' => 'vehicle_usage_log.view',
    'vehicles-reminders' => 'vehicle_reminder.view',
    'website-inquiries' => 'website_inquiry.view',
    'website-settings' => 'website_setting.view',
    'website-dashboard' => 'website_dashboard.view',
    'website-sliders' => 'website_slider.view',
    'website-about' => 'website_about.view',
    'website-services' => 'website_service.view',
    'website-products' => 'website_product.view',
    'website-posts' => 'website_post.view',
    'website-portfolios' => 'website_portfolio.view',
    'website-testimonials' => 'website_testimonial.view',
    'website-faqs' => 'website_faq.view',
    'employee-contracts' => 'employee_contract.view',
    'employee-cash-advances' => 'employee_cash_advance.view',
    'recruitment-applicants' => 'recruitment_applicant.view',
    'purchase-requests' => 'purchase_request.view',
    'purchase-orders' => 'purchase_order.view',
    'goods-receipts' => 'goods_receipt.view',
    'vendor-bill-payments' => 'vendor_bill.view',
    'vendor-payable-aging' => 'vendor_payable_report.view',
    'vendor-bills' => 'vendor_bill.view',
    'delivery-orders' => 'delivery_order.view',
    'invoice-payments' => 'invoice.view',
    'bank-transactions' => 'bank_account.view',
    'bank-transfers' => 'bank_transfer.view',
    'bank-accounts' => 'bank_account.view',
    'expenses' => 'expense.view',
    'chart-of-accounts' => 'chart_of_account.view',
    'aging-receivables' => 'aging_receivable_report.view',
    'journal-entries' => 'journal_entry_report.view',
    'general-ledger' => 'general_ledger_report.view',
    'trial-balance' => 'trial_balance_report.view',
    'balance-sheet' => 'balance_sheet.view',
    'profit-loss' => 'profit_loss_report.view',
    'cash-flow' => 'cash_flow_report.view',
    'attendances' => 'attendance.view',
    'leave-requests' => 'leave_request.view',
    'overtime-requests' => 'overtime_request.view',
    'payroll-periods' => 'payroll.view',
    'payrolls' => 'payroll.view',
    'marketing-leads' => 'marketing_lead.view',
    'quotations' => 'quotation.view',
    'invoices' => 'invoice.view',
    'customers' => 'customer.view',
    'rental-orders' => 'rental.view',
    'rentals' => 'rental.view',
    'rental-items' => 'rental.view',
    'partner-units' => 'partner_unit.view',
    'units' => 'unit.view',
    'stock' => 'stock_check.view',
    'calendar' => 'calendar.view',
    'schedules' => 'schedule.view',
    'technicians' => 'technician.view',
    'technician-schedules' => 'technician_schedule.view',
    'unit-maintenance' => 'unit_maintenance.view',
    'employees' => 'employee.view',
    'departments' => 'department.view',
    'positions' => 'position.view',
    'vendors' => 'vendor.view',
    'products-service' => 'product_service.view',
    'users' => 'user.view',
    'roles' => 'role.view',
    'approval-matrices' => 'approval_matrix.view',
    'approval-requests' => 'approval_request.view',
    'master-events' => 'master_event.manage',
    'activity-logs' => 'activity_logs.view',
    'vehicles' => 'vehicle.view',
];

foreach ($protectedRoutePermissions as $prefix => $permission) {
    if ($page === $prefix || str_starts_with($page, $prefix . '-')) {
        requirePermission($permission);
        break;
    }
}

$protectedActionPermissions = [
    'units-create' => 'unit.create',
    'units-store' => 'unit.create',
    'units-edit' => 'unit.edit',
    'units-update' => 'unit.edit',
    'units-delete' => 'unit.edit',
    'partner-units-create' => 'partner_unit.create',
    'partner-units-store' => 'partner_unit.create',
    'partner-units-edit' => 'partner_unit.edit',
    'partner-units-update' => 'partner_unit.edit',
    'technicians-create' => 'technician.create',
    'technicians-store' => 'technician.create',
    'technicians-edit' => 'technician.edit',
    'technicians-update' => 'technician.edit',
    'rentals-create' => 'rental.create',
    'rentals-store' => 'rental.create',
    'rental-items-create' => 'rental.edit',
    'rental-items-store' => 'rental.edit',
    'rentals-process-out' => 'rental.edit',
    'rentals-process-return' => 'rental.edit',
    'rentals-assign-technician' => 'rental.edit',
    'rentals-store-technician' => 'rental.edit',
    'rentals-delete-technician' => 'rental.edit',
    'rental-orders-create-from-quotation' => 'rental.create',
    'delivery-orders-create' => 'delivery_order.create',
    'delivery-orders-store' => 'delivery_order.create',
    'delivery-orders-edit' => 'delivery_order.edit',
    'delivery-orders-update' => 'delivery_order.edit',
    'delivery-orders-delete' => 'delivery_order.edit',
    'delivery-orders-print' => 'delivery_order.print',
    'customers-create' => 'customer.create',
    'customers-store' => 'customer.create',
    'customers-store-ajax' => 'customer.create',
    'customers-edit' => 'customer.edit',
    'customers-update' => 'customer.edit',
    'customers-delete' => 'customer.delete',
    'quotations-create' => 'quotation.create',
    'quotations-store' => 'quotation.create',
    'quotations-create-from-lead' => 'quotation.create',
    'quotations-edit' => 'quotation.edit',
    'quotations-update' => 'quotation.edit',
    'quotations-delete' => 'quotation.delete',
    'quotations-print-rental' => 'quotation.print',
    'quotations-print-service' => 'quotation.print',
    'invoices-create' => 'invoice.create',
    'invoices-store' => 'invoice.create',
    'invoices-create-from-quotation' => 'invoice.create',
    'invoices-edit' => 'invoice.edit',
    'invoices-update' => 'invoice.edit',
    'invoices-delete' => 'invoice.delete',
    'invoices-print' => 'invoice.print',
    'invoice-payments-create' => 'invoice.payment',
    'invoice-payments-store' => 'invoice.payment',
    'bank-accounts-create' => 'bank_account.create',
    'bank-accounts-store' => 'bank_account.create',
    'bank-accounts-edit' => 'bank_account.edit',
    'bank-accounts-update' => 'bank_account.edit',
    'bank-transfers-create' => 'bank_transfer.create',
    'bank-transfers-store' => 'bank_transfer.create',
    'bank-transfers-delete' => 'bank_transfer.create',
    'expenses-create' => 'expense.create',
    'expenses-store' => 'expense.create',
    'expenses-edit' => 'expense.edit',
    'expenses-update' => 'expense.edit',
    'expenses-delete' => 'expense.edit',
    'chart-of-accounts-create' => 'chart_of_account.create',
    'chart-of-accounts-store' => 'chart_of_account.create',
    'chart-of-accounts-edit' => 'chart_of_account.edit',
    'chart-of-accounts-update' => 'chart_of_account.edit',
    'chart-of-accounts-delete' => 'chart_of_account.edit',
    'vendors-create' => 'vendor.create',
    'vendors-store' => 'vendor.create',
    'vendors-edit' => 'vendor.edit',
    'vendors-update' => 'vendor.edit',
    'vendors-delete' => 'vendor.edit',
    'vendor-bills-create' => 'vendor_bill.create',
    'vendor-bills-store' => 'vendor_bill.create',
    'vendor-bills-delete' => 'vendor_bill.create',
    'vendor-bill-payments-create' => 'vendor_bill.payment',
    'vendor-bill-payments-store' => 'vendor_bill.payment',
    'unit-maintenance-process' => 'unit_maintenance.create',
    'unit-maintenance-store' => 'unit_maintenance.create',
    'vehicles-create' => 'vehicle.create',
    'vehicles-store' => 'vehicle.create',
    'vehicles-edit' => 'vehicle.edit',
    'vehicles-update' => 'vehicle.edit',
    'vehicle-usage-logs-create' => 'vehicle_usage_log.create',
    'vehicle-usage-logs-store' => 'vehicle_usage_log.create',
    'vehicle-maintenances-process' => 'vehicle_maintenance.create',
    'vehicle-maintenances-store' => 'vehicle_maintenance.create',
    'products-service-create' => 'product_service.create',
    'products-service-store' => 'product_service.create',
    'products-service-edit' => 'product_service.edit',
    'products-service-update' => 'product_service.edit',
    'products-service-delete' => 'product_service.edit',
    'employees-create' => 'employee.create',
    'employees-store' => 'employee.create',
    'employees-edit' => 'employee.edit',
    'employees-update' => 'employee.edit',
    'employees-delete' => 'employee.edit',
    'departments-create' => 'department.create',
    'departments-store' => 'department.create',
    'departments-edit' => 'department.edit',
    'departments-update' => 'department.edit',
    'departments-delete' => 'department.edit',
    'positions-create' => 'position.create',
    'positions-store' => 'position.create',
    'positions-edit' => 'position.edit',
    'positions-update' => 'position.edit',
    'positions-delete' => 'position.edit',
    'attendances-create' => 'attendance.create',
    'attendances-store' => 'attendance.create',
    'attendances-edit' => 'attendance.edit',
    'attendances-update' => 'attendance.edit',
    'attendances-delete' => 'attendance.edit',
    'leave-requests-create' => 'leave_request.create',
    'leave-requests-store' => 'leave_request.create',
    'leave-requests-edit' => 'leave_request.edit',
    'leave-requests-update' => 'leave_request.edit',
    'leave-requests-delete' => 'leave_request.edit',
    'leave-requests-approve' => 'leave_request.approve',
    'leave-requests-reject' => 'leave_request.approve',
    'overtime-requests-create' => 'overtime_request.create',
    'overtime-requests-store' => 'overtime_request.create',
    'overtime-requests-edit' => 'overtime_request.edit',
    'overtime-requests-update' => 'overtime_request.edit',
    'overtime-requests-delete' => 'overtime_request.edit',
    'overtime-requests-approve' => 'overtime_request.approve',
    'overtime-requests-reject' => 'overtime_request.approve',
    'payroll-periods-create' => 'payroll.create',
    'payroll-periods-store' => 'payroll.create',
    'payroll-periods-edit' => 'payroll.edit',
    'payroll-periods-update' => 'payroll.edit',
    'payroll-periods-delete' => 'payroll.edit',
    'payrolls-generate' => 'payroll.create',
    'payrolls-edit' => 'payroll.edit',
    'payrolls-update' => 'payroll.edit',
    'payrolls-paid' => 'payroll.edit',
];

if (isset($protectedActionPermissions[$page])) {
    requirePermission($protectedActionPermissions[$page]);
}

$postOnlyRoutes = [
    'process-login',
    'contact-send',
    'units-delete',
    'rentals-process-out',
    'rentals-process-return',
    'rentals-store-technician',
    'rentals-delete-technician',
    'delivery-orders-delete',
    'users-delete',
    'customers-delete',
    'customers-store-ajax',
    'quotations-delete',
    'invoices-delete',
    'expenses-delete',
    'chart-of-accounts-delete',
    'bank-transfers-delete',
    'vendors-delete',
    'vendor-bills-delete',
    'products-service-delete',
    'employees-delete',
    'departments-delete',
    'positions-delete',
    'attendances-delete',
    'leave-requests-approve',
    'leave-requests-delete',
    'overtime-requests-approve',
    'overtime-requests-delete',
    'payroll-periods-delete',
    'payrolls-generate',
    'payrolls-paid',
    'marketing-leads-delete',
    'recruitment-applicants-delete',
    'recruitment-applicants-convert',
    'employee-contracts-delete',
    'purchase-orders-approve',
    'purchase-orders-sent',
    'purchase-orders-create-bill',
    'purchase-orders-delete',
    'goods-receipts-delete',
    'purchase-requests-submit-approval',
    'purchase-requests-approve',
    'purchase-requests-delete',
    'approval-matrices-delete',
    'website-sliders-delete',
    'website-inquiries-delete',
    'website-services-delete',
    'website-posts-delete',
    'website-testimonials-delete',
    'website-faqs-delete',
    'website-products-delete',
    'website-portfolios-delete',
    'rental-orders-create-from-quotation',
    'unit-maintenance-process',
    'vehicle-maintenances-process',
    'employee-cash-advances-disburse',
    'client-approval-revision',
    'client-event-barcode-activate',
    'client-event-content-store',
    'client-event-content-delete',
    'master-events-store-access',
    'master-events-store-milestone',
    'master-events-store-document',
    'master-events-store-approval',
    'master-events-ticket-payment',
    'master-events-ticket-checkin',
    'master-events-attendance-activate',
    'master-events-store-content',
    'master-events-delete-content',
    'member-payment',
    'member-checkin',
    'member-logout',
];

if (in_array($page, $postOnlyRoutes, true)
    || preg_match('/-(?:store|update|delete|approve|reject|save|send)$/', $page)
) {
    requirePost();
}

switch ($page) {
    case 'login':
    $controller = new AuthController();
    $controller->login();
    break;

    case 'process-login':
    $controller = new AuthController();
    $controller->processLogin();
    break;

    case 'logout':
    $controller = new AuthController();
    $controller->logout();
    break;

    case 'dashboard':
    $controller = new DashboardController();
    $controller->index();
    break;

    case 'dashboard-sales':
    $controller = new DashboardController();
    $controller->sales();
    break;

    case 'dashboard-finance':
    $controller = new DashboardController();
    $controller->finance();
    break;

    case 'dashboard-operasional':
    $controller = new DashboardController();
    $controller->operasional();
    break;

    case 'dashboard-owner':
    $controller = new DashboardController();
    $controller->owner();
    break;

    case 'client-dashboard':
        (new ClientDashboardController())->index();
        break;

    case 'client-events':
        (new ClientEventController())->index();
        break;

    case 'client-event-show':
        (new ClientEventController())->show();
        break;

    case 'client-event-attendees':
        (new ClientEventController())->attendees();
        break;

    case 'client-event-barcode-activate':
        (new ClientEventController())->activateBarcode();
        break;

    case 'client-event-barcode':
        (new ClientEventController())->barcode();
        break;

    case 'client-event-content':
        (new ClientEventContentController())->index();
        break;

    case 'client-event-content-store':
        (new ClientEventContentController())->store();
        break;

    case 'client-event-content-delete':
        (new ClientEventContentController())->delete();
        break;

    case 'client-event-approvals':
        (new ClientApprovalController())->eventIndex();
        break;

    case 'client-approval-show':
        (new ClientApprovalController())->show();
        break;

    case 'client-approval-approve':
        (new ClientApprovalController())->approve();
        break;

    case 'client-approval-revision':
        (new ClientApprovalController())->revision();
        break;

    case 'client-event-documents':
        (new ClientDocumentController())->index();
        break;

    case 'client-document-download':
        (new ClientDocumentController())->download();
        break;

    case 'client-event-timeline':
        (new ClientTimelineController())->index();
        break;

    case 'client-notifications':
        (new ClientNotificationController())->index();
        break;

    case 'master-events':
        (new MasterEventController())->index();
        break;

    case 'master-events-create':
        (new MasterEventController())->create();
        break;

    case 'master-events-store':
        (new MasterEventController())->store();
        break;

    case 'master-events-edit':
        (new MasterEventController())->edit();
        break;

    case 'master-events-update':
        (new MasterEventController())->update();
        break;

    case 'master-events-store-access':
        (new MasterEventController())->storeAccess();
        break;

    case 'master-events-store-milestone':
        (new MasterEventController())->storeMilestone();
        break;

    case 'master-events-store-document':
        (new MasterEventController())->storeDocument();
        break;

    case 'master-events-store-approval':
        (new MasterEventController())->storeApproval();
        break;

    case 'master-events-ticket-payment':
        (new MasterEventController())->updateTicketPayment();
        break;

    case 'master-events-ticket-checkin':
        (new MasterEventController())->checkInTicket();
        break;

    case 'master-events-ticket-proof':
        (new MasterEventController())->viewTicketProof();
        break;

    case 'master-events-attendance-activate':
        (new MasterEventController())->activateAttendanceBarcode();
        break;

    case 'master-events-attendance-print':
        (new MasterEventController())->printAttendanceBarcode();
        break;

    case 'master-events-store-content':
        (new MasterEventController())->storePortalContent();
        break;

    case 'master-events-delete-content':
        (new MasterEventController())->deletePortalContent();
        break;

    case 'staff-ticket-checkin':
        (new StaffTicketCheckInController())->show();
        break;

    case 'units':
    $controller = new UnitController();
    $controller->index();
    break;

    case 'units-create':
    $controller = new UnitController();
    $controller->create();
    break;

    case 'units-store':
    $controller = new UnitController();
    $controller->store();
    break;

    case 'units-edit':
    $controller = new UnitController();
    $controller->edit();
    break;

    case 'units-update':
    $controller = new UnitController();
    $controller->update();
    break;

    case 'units-delete':
    $controller = new UnitController();
    $controller->delete();
    break;

    case 'rentals':
    $controller = new RentalController();
    $controller->index();
    break;

    case 'rentals-create':
    $controller = new RentalController();
    $controller->create();
    break;

    case 'rentals-store':
    $controller = new RentalController();
    $controller->store();
    break;

    case 'rentals-show':
    $controller = new RentalController();
    $controller->show();
    break;

    case 'rental-items-create':
    $controller = new RentalController();
    $controller->createItem();
    break;

    case 'rental-items-store':
    $controller = new RentalController();
    $controller->storeItem();
    break;

    case 'partner-units':
    $controller = new PartnerUnitController();
    $controller->index();
    break;

    case 'partner-units-create':
    $controller = new PartnerUnitController();
    $controller->create();
    break;

    case 'partner-units-store':
    $controller = new PartnerUnitController();
    $controller->store();
    break;

    case 'partner-units-edit':
    $controller = new PartnerUnitController();
    $controller->edit();
    break;

    case 'partner-units-update':
    $controller = new PartnerUnitController();
    $controller->update();
    break;
    case 'schedules':
    $controller = new ScheduleController();
    $controller->index();
    break;
    case 'rentals-process-out':
    $controller = new RentalController();
    $controller->processOut();
    break;

    case 'rentals-process-return':
    $controller = new RentalController();
    $controller->processReturn();
    break;

    case 'calendar':
    $controller = new CalendarController();
    $controller->index();
    break;

    case 'stock':
    $controller = new StockController();
    $controller->index();
    break;

    case 'delivery-orders':
    (new DeliveryOrderController())->index();
    break;

    case 'delivery-orders-create':
    (new DeliveryOrderController())->create();
    break;

    case 'delivery-orders-store':
    (new DeliveryOrderController())->store();
    break;

    case 'delivery-orders-print':
    (new DeliveryOrderController())->print();
    break;


    case 'delivery-orders-show':
    (new DeliveryOrderController())->show();
    break;

    case 'technicians':
    (new TechnicianController())->index();
    break;

    case 'technicians-create':
    (new TechnicianController())->create();
    break;

    case 'technicians-store':
    (new TechnicianController())->store();
    break;

    case 'technicians-edit':
    (new TechnicianController())->edit();
    break;

    case 'technicians-update':
    (new TechnicianController())->update();
    break;
    case 'rentals-assign-technician':
    (new RentalController())->assignTechnician();
    break;

    case 'rentals-store-technician':
    (new RentalController())->storeTechnician();
    break;

    case 'rentals-delete-technician':
    (new RentalController())->deleteTechnician();
    break;
    case 'technician-schedules':
    (new TechnicianController())->schedules();
    break;
    case 'delivery-orders-delete':
    (new DeliveryOrderController())->delete();
    break;

    case 'delivery-orders-edit':
    (new DeliveryOrderController())->edit();
    break;

    case 'delivery-orders-update':
    (new DeliveryOrderController())->update();
    break;

    case 'users':
    (new UserController())->index();
    break;

    case 'users-create':
    (new UserController())->create();
    break;

    case 'users-store':
    (new UserController())->store();
    break;
    case 'users-edit':
    (new UserController())->edit();
    break;

    case 'users-update':
    (new UserController())->update();
    break;

    case 'users-delete':
    (new UserController())->delete();
    break;

    case 'customers':
    (new CustomerController())->index();
    break;

    case 'customers-create':
    (new CustomerController())->create();
    break;

    case 'customers-store':
    (new CustomerController())->store();
    break;
    case 'customers-edit':
    (new CustomerController())->edit();
    break;

    case 'customers-update':
    (new CustomerController())->update();
    break;

    case 'customers-delete':
    (new CustomerController())->delete();
    break;

    case 'customers-show':
    (new CustomerController())->show();
    break;

    case 'quotations':
    (new QuotationController())->index();
    break;

    case 'quotations-create':
    (new QuotationController())->create();
    break;

    case 'quotations-store':
    (new QuotationController())->store();
    break;

    case 'quotations-edit':
    (new QuotationController())->edit();
    break;

    case 'quotations-update':
    (new QuotationController())->update();
    break;

    case 'quotations-show':
    (new QuotationController())->show();
    break;

    case 'quotations-print-rental':
    (new QuotationController())->printRental();
    break;

    case 'quotations-print-service':
    (new QuotationController())->printService();
    break;
    
    case 'quotations-delete':
    (new QuotationController())->delete();
    break;

    case 'rental-orders-create-from-quotation':
    (new RentalController())->createFromQuotation();
    break;

    case 'invoices':
    (new InvoiceController())->index();
    break;

    case 'invoices-create':
    (new InvoiceController())->create();
    break;

    case 'invoices-store':
    (new InvoiceController())->store();
    break;

    case 'invoices-edit':
    (new InvoiceController())->edit();
    break;

    case 'invoices-update':
    (new InvoiceController())->update();
    break;

    case 'invoices-show':
    (new InvoiceController())->show();
    break;
    
    case 'invoices-print':
    (new InvoiceController())->print();
    break;

    case 'invoices-delete':
    (new InvoiceController())->delete();
    break;

    case 'invoices-create-from-quotation':
    (new InvoiceController())->createFromQuotation();
    break;
    
    case 'invoice-payments-create':
    (new InvoicePaymentController())->create();
    break;

    case 'invoice-payments-store':
    (new InvoicePaymentController())->store();
    break;

    case 'bank-transactions':
    (new BankTransactionController())->index();
    break;

    case 'bank-accounts':
    (new BankAccountController())->index();
    break;

    case 'expenses':
    (new ExpenseController())->index();
    break;

    case 'expenses-create':
    (new ExpenseController())->create();
    break;

    case 'expenses-store':
    (new ExpenseController())->store();
    break;

    case 'expenses-show':
    (new ExpenseController())->show();
    break;

    case 'expenses-delete':
    (new ExpenseController())->delete();
    break;

    case 'expenses-edit':
    (new ExpenseController())->edit();
    break;

    case 'expenses-update':
    (new ExpenseController())->update();
    break;
    
    case 'journal-entries':
    (new JournalEntryController())->index();
    break;

    case 'journal-entries-show':
    (new JournalEntryController())->show();
    break;

    case 'general-ledger':
    (new GeneralLedgerController())->index();
    break;

    case 'trial-balance':
    (new TrialBalanceController())->index();
    break;

    case 'balance-sheet':
    (new BalanceSheetController())->index();
    break;
    
    case 'profit-loss':
    (new ProfitLossController())->index();
    break;

    case 'cash-flow':
    (new CashFlowController())->index();
    break;
    
    case 'aging-receivables':
    (new AgingReceivableController())->index();
    break;


    case 'chart-of-accounts':
    (new ChartOfAccountController())->index();
    break;

    case 'chart-of-accounts-create':
    (new ChartOfAccountController())->create();
    break;

    case 'chart-of-accounts-store':
    (new ChartOfAccountController())->store();
    break;

    case 'chart-of-accounts-edit':
    (new ChartOfAccountController())->edit();
    break;

    case 'chart-of-accounts-update':
    (new ChartOfAccountController())->update();
    break;

    case 'chart-of-accounts-delete':
    (new ChartOfAccountController())->delete();
    break;
    
    case 'bank-accounts-create':
    (new BankAccountController())->create();
    break;

    case 'bank-accounts-store':
    (new BankAccountController())->store();
    break;

    case 'bank-accounts-edit':
    (new BankAccountController())->edit();
    break;

    case 'bank-accounts-update':
    (new BankAccountController())->update();
    break;

    case 'bank-transfers':
    (new BankTransferController())->index();
    break;

    case 'bank-transfers-create':
    (new BankTransferController())->create();
    break;

    case 'bank-transfers-store':
    (new BankTransferController())->store();
    break;

    case 'bank-transfers-show':
    (new BankTransferController())->show();
    break;
    
    case 'bank-transfers-delete':
    (new BankTransferController())->delete();
    break;

    case 'vendors':
    (new VendorController())->index();
    break;

    case 'vendors-create':
    (new VendorController())->create();
    break;

    case 'vendors-store':
    (new VendorController())->store();
    break;

    case 'vendors-edit':
    (new VendorController())->edit();
    break;

    case 'vendors-update':
    (new VendorController())->update();
    break;

    case 'vendors-delete':
    (new VendorController())->delete();
    break;

    case 'vendor-bills':
    (new VendorBillController())->index();
    break;

    case 'vendor-bills-create':
    (new VendorBillController())->create();
    break;

    case 'vendor-bills-store':
    (new VendorBillController())->store();
    break;

    case 'vendor-bills-show':
    (new VendorBillController())->show();
    break;
    
    case 'vendor-bill-payments-create':
    (new VendorBillPaymentController())->create();
    break;

    case 'vendor-bill-payments-store':
    (new VendorBillPaymentController())->store();
    break;

    case 'vendor-payable-aging':
    (new VendorPayableAgingController())->index();
    break;

    case 'vendor-bills-delete':
    (new VendorBillController())->delete();
    break;

    case 'customers-store-ajax':
    (new CustomerController())->storeAjax();
    break;
    
    case 'customers-search-ajax':
    (new CustomerController())->searchAjax();
    break;
    
    case 'unit-maintenance':
    (new UnitMaintenanceController())->index();
    break;

    case 'unit-maintenance-due':
    (new UnitMaintenanceController())->index();
    break;

    case 'unit-maintenance-process':
    (new UnitMaintenanceController())->process();
    break;

    case 'unit-maintenance-store':
    (new UnitMaintenanceController())->store();
    break;

    case 'unit-maintenance-history':
    (new UnitMaintenanceController())->history();
    break;

    case 'unit-maintenance-show':
    (new UnitMaintenanceController())->show();
    break;

    case 'unit-maintenance-report':
    (new UnitMaintenanceController())->report();
    break;

    case 'vehicles':
    (new VehicleController())->index();
    break;

    case 'vehicles-create':
    (new VehicleController())->create();
    break;

    case 'vehicles-edit':
    (new VehicleController())->edit();
    break;

    case 'vehicles-store':
    (new VehicleController())->store();
    break;

    case 'vehicles-update':
    (new VehicleController())->update();
    break;

    case 'vehicles-reminders':
    (new VehicleController())->reminders();
    break;

    case 'vehicle-usage-logs-create':
    (new VehicleUsageLogController())->create();
    break;

    case 'vehicle-usage-logs':
    (new VehicleUsageLogController())->index();
    break;

    case 'vehicle-usage-logs-store':
    (new VehicleUsageLogController())->store();
    break;

    case 'vehicle-usage-logs-show':
    (new VehicleUsageLogController())->show();
    break;


    case 'vehicle-maintenances':
    (new VehicleMaintenanceController())->index();
    break;

    case 'vehicle-maintenances-due':
    (new VehicleMaintenanceController())->index();
    break;

    case 'vehicle-maintenances-process':
    (new VehicleMaintenanceController())->process();
    break;

    case 'vehicle-maintenances-store':
    (new VehicleMaintenanceController())->store();
    break;

    case 'vehicle-maintenances-history':
    (new VehicleMaintenanceController())->history();
    break;

    case 'vehicle-maintenances-show':
    (new VehicleMaintenanceController())->show();
    break;

    case 'vehicle-maintenances-report':
    (new VehicleMaintenanceController())->report();
    break;

    case 'products-service':
    (new ProductController())->index();
    break;

    case 'products-service-create':
    (new ProductController())->create();
    break;

    case 'products-service-store':
    (new ProductController())->store();
    break;

    case 'products-service-edit':
    (new ProductController())->edit();
    break;

    case 'products-service-update':
    (new ProductController())->update();
    break;

    case 'products-service-delete':
    (new ProductController())->delete();
    break;

    case 'employees':
    (new EmployeeController())->index();
    break;

    case 'employees-create':
    (new EmployeeController())->create();
    break;

    case 'employees-store':
    (new EmployeeController())->store();
    break;

    case 'employees-edit':
    (new EmployeeController())->edit();
    break;

    case 'employees-update':
    (new EmployeeController())->update();
    break;

    case 'employees-show':
    (new EmployeeController())->show();
    break;

    case 'employees-delete':
    (new EmployeeController())->delete();
    break;


    case 'departments':
    (new DepartmentController())->index();
    break;

    case 'departments-create':
    (new DepartmentController())->create();
    break;

    case 'departments-store':
    (new DepartmentController())->store();
    break;

    case 'departments-edit':
    (new DepartmentController())->edit();
    break;

    case 'departments-update':
    (new DepartmentController())->update();
    break;

    case 'departments-delete':
    (new DepartmentController())->delete();
    break;

    case 'positions':
    (new PositionController())->index();
    break;

    case 'positions-create':
    (new PositionController())->create();
    break;

    case 'positions-store':
    (new PositionController())->store();
    break;

    case 'positions-edit':
    (new PositionController())->edit();
    break;

    case 'positions-update':
    (new PositionController())->update();
    break;

    case 'positions-delete':
    (new PositionController())->delete();
    break;

    case 'attendances':
    (new AttendanceController())->index();
    break;

    case 'attendances-create':
    (new AttendanceController())->create();
    break;

    case 'attendances-store':
    (new AttendanceController())->store();
    break;

    case 'attendances-show':
    (new AttendanceController())->show();
    break;

    case 'attendances-edit':
    (new AttendanceController())->edit();
    break;

    case 'attendances-update':
    (new AttendanceController())->update();
    break;

    case 'attendances-delete':
    (new AttendanceController())->delete();
    break;

    case 'leave-requests':
    (new LeaveRequestController())->index();
    break;

    case 'leave-requests-create':
    (new LeaveRequestController())->create();
    break;

    case 'leave-requests-store':
    (new LeaveRequestController())->store();
    break;

    case 'leave-requests-show':
    (new LeaveRequestController())->show();
    break;

    case 'leave-requests-edit':
    (new LeaveRequestController())->edit();
    break;

    case 'leave-requests-update':
    (new LeaveRequestController())->update();
    break;

    case 'leave-requests-delete':
    (new LeaveRequestController())->delete();
    break;

    case 'leave-requests-approve':
    (new LeaveRequestController())->approve();
    break;

    case 'leave-requests-reject':
    (new LeaveRequestController())->reject();
    break;

    case 'overtime-requests':
    (new OvertimeRequestController())->index();
    break;

    case 'overtime-requests-create':
    (new OvertimeRequestController())->create();
    break;

    case 'overtime-requests-store':
    (new OvertimeRequestController())->store();
    break;

    case 'overtime-requests-show':
    (new OvertimeRequestController())->show();
    break;

    case 'overtime-requests-edit':
    (new OvertimeRequestController())->edit();
    break;

    case 'overtime-requests-update':
    (new OvertimeRequestController())->update();
    break;

    case 'overtime-requests-delete':
    (new OvertimeRequestController())->delete();
    break;

    case 'overtime-requests-approve':
    (new OvertimeRequestController())->approve();
    break;

    case 'overtime-requests-reject':
    (new OvertimeRequestController())->reject();
    break;

    case 'payroll-periods':
    (new PayrollPeriodController())->index();
    break;

    case 'payroll-periods-create':
    (new PayrollPeriodController())->create();
    break;

    case 'payroll-periods-store':
    (new PayrollPeriodController())->store();
    break;

    case 'payroll-periods-show':
    (new PayrollPeriodController())->show();
    break;

    case 'payroll-periods-edit':
    (new PayrollPeriodController())->edit();
    break;

    case 'payroll-periods-update':
    (new PayrollPeriodController())->update();
    break;

    case 'payroll-periods-delete':
    (new PayrollPeriodController())->delete();
    break;

    case 'payrolls-generate':
    (new PayrollController())->generate();
    break;

    case 'payrolls':
    (new PayrollController())->index();
    break;

    case 'payrolls-show':
    (new PayrollController())->show();
    break;
    

    case 'payrolls-print':
    (new PayrollController())->print();
    break;

    case 'payrolls-edit':
    (new PayrollController())->edit();
    break;

    case 'payrolls-update':
    (new PayrollController())->update();
    break;

    case 'payrolls-paid':
    (new PayrollController())->paid();
    break;

    case 'roles':
    $controller = new RoleController();
    $controller->index();
    break;

    case 'roles-create':
    $controller = new RoleController();
    $controller->create();
    break;

    case 'roles-store':
    $controller = new RoleController();
    $controller->store();
    break;

    case 'roles-edit':
    $controller = new RoleController();
    $controller->edit();
    break;

    case 'roles-update':
    $controller = new RoleController();
    $controller->update();
    break;

    case 'roles-permissions':
    $controller = new RoleController();
    $controller->permissions();
    break;

    case 'roles-permissions-update':
    $controller = new RoleController();
    $controller->updatePermissions();
    break;
    
    case 'employee-cash-advances':
    $controller = new EmployeeCashAdvanceController();
    $controller->index();
    break;

    case 'employee-cash-advances-create':
    $controller = new EmployeeCashAdvanceController();
    $controller->create();
    break;

    case 'employee-cash-advances-store':
    $controller = new EmployeeCashAdvanceController();
    $controller->store();
    break;

    case 'employee-cash-advances-show':
    $controller = new EmployeeCashAdvanceController();
    $controller->show();
    break;

    case 'employee-cash-advances-edit':
    $controller = new EmployeeCashAdvanceController();
    $controller->edit();
    break;

    case 'employee-cash-advances-update':
    $controller = new EmployeeCashAdvanceController();
    $controller->update();
    break;

    case 'employee-cash-advances-supervisor-approve':
    $controller = new EmployeeCashAdvanceController();
    $controller->supervisorApprove();
    break;

    case 'employee-cash-advances-finance-approve':
    $controller = new EmployeeCashAdvanceController();
    $controller->financeApprove();
    break;

    case 'employee-cash-advances-disburse':
    $controller = new EmployeeCashAdvanceController();
    $controller->disburse();
    break;
    
    case 'employee-cash-advances-reject':
    $controller = new EmployeeCashAdvanceController();
    $controller->reject();
    break;

    case 'activate-account':
    $controller = new AuthController();
    $controller->activateAccount();
    break;

    case 'activate-account-save':
    $controller = new AuthController();
    $controller->saveActivationPassword();
    break;

    case 'forgot-password':
    $controller = new AuthController();
    $controller->forgotPassword();
    break;

    case 'forgot-password-send':
    $controller = new AuthController();
    $controller->sendResetPassword();
    break;

    case 'reset-password':
    $controller = new AuthController();
    $controller->resetPassword();
    break;

    case 'reset-password-save':
    $controller = new AuthController();
    $controller->saveResetPassword();
    break;
    
    case 'marketing-leads':
    $controller = new MarketingLeadController();
    $controller->index();
    break;

    case 'marketing-leads-create':
    $controller = new MarketingLeadController();
    $controller->create();
    break;

    case 'marketing-leads-store':
    $controller = new MarketingLeadController();
    $controller->store();
    break;

    case 'marketing-leads-show':
    $controller = new MarketingLeadController();
    $controller->show();
    break;

    case 'marketing-leads-edit':
    $controller = new MarketingLeadController();
    $controller->edit();
    break;

    case 'marketing-leads-update':
    $controller = new MarketingLeadController();
    $controller->update();
    break;

    case 'marketing-leads-delete':
    $controller = new MarketingLeadController();
    $controller->delete();
    break;

    case 'marketing-leads-followup-store':
    $controller = new MarketingLeadController();
    $controller->storeFollowUp();
    break;

    case 'marketing-leads-search-ajax':
    (new MarketingLeadController())->searchAjax();
    break;

    case 'quotations-create-from-lead':
    $controller = new QuotationController();
    $controller->createFromLead();
    break;

    case 'recruitment-applicants':
    $controller = new RecruitmentApplicantController();
    $controller->index();
    break;

    case 'recruitment-applicants-create':
    $controller = new RecruitmentApplicantController();
    $controller->create();
    break;

    case 'recruitment-applicants-store':
    $controller = new RecruitmentApplicantController();
    $controller->store();
    break;

    case 'recruitment-applicants-show':
    $controller = new RecruitmentApplicantController();
    $controller->show();
    break;

    case 'recruitment-applicants-edit':
    $controller = new RecruitmentApplicantController();
    $controller->edit();
    break;

    case 'recruitment-applicants-update':
    $controller = new RecruitmentApplicantController();
    $controller->update();
    break;

    case 'recruitment-applicants-delete':
    $controller = new RecruitmentApplicantController();
    $controller->delete();
    break;

    case 'recruitment-applicants-convert':
    $controller = new RecruitmentApplicantController();
    $controller->convertToEmployee();
    break;

    case 'employees-create-user':
    $controller = new EmployeeController();
    $controller->createUserAccount();
    break;
    
    case 'employee-contracts':
    $controller = new EmployeeContractController();
    $controller->index();
    break;

    case 'employee-contracts-create':
    $controller = new EmployeeContractController();
    $controller->create();
    break;

    case 'employee-contracts-store':
    $controller = new EmployeeContractController();
    $controller->store();
    break;

    case 'employee-contracts-show':
    $controller = new EmployeeContractController();
    $controller->show();
    break;

    case 'employee-contracts-edit':
    $controller = new EmployeeContractController();
    $controller->edit();
    break;

    case 'employee-contracts-update':
    $controller = new EmployeeContractController();
    $controller->update();
    break;

    case 'employee-contracts-delete':
    $controller = new EmployeeContractController();
    $controller->delete();
    break;

    case 'employee-contracts-print':
    $controller = new EmployeeContractController();
    $controller->print();
    break;

    case 'activity-logs':
    (new ActivityLogController())->index();
    break;

    /*
    |--------------------------------------------------------------------------
    | PURCHASE ORDER
    |--------------------------------------------------------------------------
    */

    case 'purchase-orders':
    (new PurchaseOrderController())->index();
    break;

    case 'purchase-orders-create':
    (new PurchaseOrderController())->create();
    break;

    case 'purchase-orders-store':
    (new PurchaseOrderController())->store();
    break;

    case 'purchase-orders-show':
    (new PurchaseOrderController())->show();
    break;

    case 'purchase-orders-edit':
    (new PurchaseOrderController())->edit();
    break;

    case 'purchase-orders-update':
    (new PurchaseOrderController())->update();
    break;

    case 'purchase-orders-approve':
    (new PurchaseOrderController())->approve();
    break;

    case 'purchase-orders-sent':
    (new PurchaseOrderController())->markAsSent();
    break;

    case 'purchase-orders-print':
    (new PurchaseOrderController())->print();
    break;

    case 'purchase-orders-delete':
    (new PurchaseOrderController())->delete();
    break;

    case 'goods-receipts':
    (new GoodsReceiptController())->index();
    break;

    case 'goods-receipts-create':
    (new GoodsReceiptController())->create();
    break;

    case 'goods-receipts-store':
    (new GoodsReceiptController())->store();
    break;

    case 'goods-receipts-show':
    (new GoodsReceiptController())->show();
    break;

    case 'goods-receipts-delete':
    (new GoodsReceiptController())->delete();
    break;

    case 'purchase-orders-create-bill':
    (new PurchaseOrderController())->createBill();
    break;

    case 'purchase-requests':
    (new PurchaseRequestController())->index();
    break;

    case 'purchase-requests-create':
    (new PurchaseRequestController())->create();
    break;

    case 'purchase-requests-store':
    (new PurchaseRequestController())->store();
    break;

    case 'purchase-requests-show':
    (new PurchaseRequestController())->show();
    break;

    case 'purchase-requests-edit':
    (new PurchaseRequestController())->edit();
    break;

    case 'purchase-requests-update':
    (new PurchaseRequestController())->update();
    break;

    case 'purchase-requests-approve':
    (new PurchaseRequestController())->approve();
    break;

    case 'purchase-requests-reject':
    (new PurchaseRequestController())->reject();
    break;

    case 'purchase-requests-delete':
    (new PurchaseRequestController())->delete();
    break;

    case 'purchase-orders-create-from-pr':
    (new PurchaseOrderController())->createFromPr();
    break;

    case 'approval-matrices':
    (new ApprovalMatrixController())->index();
    break;

    case 'approval-matrices-create':
    (new ApprovalMatrixController())->create();
    break;

    case 'approval-matrices-store':
    (new ApprovalMatrixController())->store();
    break;

    case 'approval-matrices-show':
    (new ApprovalMatrixController())->show();
    break;

    case 'approval-matrices-edit':
    (new ApprovalMatrixController())->edit();
    break;

    case 'approval-matrices-update':
    (new ApprovalMatrixController())->update();
    break;

    case 'approval-matrices-delete':
    (new ApprovalMatrixController())->delete();
    break;

    case 'approval-requests':
        (new ApprovalRequestController())->index();
        break;

    case 'approval-requests-show':
        (new ApprovalRequestController())->show();
        break;

    case 'approval-requests-approve':
        (new ApprovalRequestController())->approve();
        break;

    case 'approval-requests-reject':
        (new ApprovalRequestController())->reject();
        break;

    case 'purchase-requests-submit-approval':
        (new PurchaseRequestController())->submitApproval();
        break;
        
    case 'home':
        (new FrontHomeController())->index();
        break;

    case 'services':
        (new FrontServiceController())->index();
        break;

    case 'service-detail':
        (new FrontServiceController())->show();
        break;

    case 'contact':
        (new FrontContactController())->index();
        break;

    case 'contact-send':
        (new FrontContactController())->send();
        break;

    case 'about':
        (new FrontAboutController())->index();
        break;

    case 'products':
        (new FrontProductController())->index();
        break;

    case 'events':
        (new FrontEventController())->index();
        break;

    case 'event-detail':
        (new FrontEventController())->show();
        break;

    case 'event-purchase':
        (new FrontEventController())->purchase();
        break;

    case 'member-register':
        (new FrontMemberController())->register();
        break;

    case 'member-login':
        (new FrontMemberController())->login();
        break;

    case 'member-verify':
        (new FrontMemberController())->verify();
        break;

    case 'member-forgot':
        (new FrontMemberController())->forgotPassword();
        break;

    case 'member-reset':
        (new FrontMemberController())->resetPassword();
        break;

    case 'member-dashboard':
        (new FrontMemberController())->dashboard();
        break;

    case 'member-order':
        (new FrontMemberController())->order();
        break;

    case 'member-event-content':
        (new FrontMemberController())->eventContent();
        break;

    case 'member-payment':
        (new FrontMemberController())->uploadProof();
        break;

    case 'member-proof':
        (new FrontMemberController())->proof();
        break;

    case 'member-checkin':
        (new FrontMemberController())->checkIn();
        break;

    case 'member-attendance-scan':
        (new FrontMemberController())->attendanceScan();
        break;

    case 'member-logout':
        (new FrontMemberController())->logout();
        break;

    case 'vendor-register':
        (new FrontVendorController())->index();
        break;

    case 'vendor-register-store':
        (new FrontVendorController())->store();
        break;

    case 'portfolio':
        (new FrontPortfolioController())->index();
        break;


    case 'blog':
        (new FrontBlogController())->index();
        break;

    case 'website-sliders':
        (new WebsiteSliderController())->index();
        break;

    case 'website-sliders-create':
        (new WebsiteSliderController())->create();
        break;

    case 'website-sliders-store':
        (new WebsiteSliderController())->store();
        break;

    case 'website-sliders-edit':
        (new WebsiteSliderController())->edit();
        break;

    case 'website-sliders-update':
        (new WebsiteSliderController())->update();
        break;

    case 'website-sliders-delete':
        (new WebsiteSliderController())->delete();
        break;
        
    case 'website-settings':
        (new WebsiteSettingController())->index();
        break;

    case 'website-settings-update':
        (new WebsiteSettingController())->update();
        break;

    case 'website-dashboard':
        (new WebsiteDashboardController())->index();
        break;
    
    case 'website-inquiries':
        (new WebsiteInquiryController())->index();
        break;

    case 'website-inquiries-show':
        (new WebsiteInquiryController())->show();
        break;

    case 'website-inquiries-update':
        (new WebsiteInquiryController())->update();
        break;

    case 'website-inquiries-delete':
        (new WebsiteInquiryController())->delete();
        break;

    case 'website-about':
        (new WebsiteAboutController())->index();
        break;

    case 'website-about-update':
        (new WebsiteAboutController())->update();
        break;

    case 'website-services':
        (new WebsiteServiceController())->index();
        break;

    case 'website-services-create':
        (new WebsiteServiceController())->create();
        break;

    case 'website-services-store':
        (new WebsiteServiceController())->store();
        break;

    case 'website-services-edit':
        (new WebsiteServiceController())->edit();
        break;

    case 'website-services-update':
        (new WebsiteServiceController())->update();
        break;

    case 'website-services-delete':
        (new WebsiteServiceController())->delete();
        break;

    case 'website-posts':
        (new WebsitePostController())->index();
        break;

    case 'website-posts-create':
        (new WebsitePostController())->create();
        break;

    case 'website-posts-store':
        (new WebsitePostController())->store();
        break;

    case 'website-posts-edit':
        (new WebsitePostController())->edit();
        break;

    case 'website-posts-update':
        (new WebsitePostController())->update();
        break;

    case 'website-posts-delete':
        (new WebsitePostController())->delete();
        break;


    case 'website-testimonials':
        (new WebsiteTestimonialController())->index();
        break;

    case 'website-testimonials-create':
        (new WebsiteTestimonialController())->create();
        break;

    case 'website-testimonials-store':
        (new WebsiteTestimonialController())->store();
        break;

    case 'website-testimonials-edit':
        (new WebsiteTestimonialController())->edit();
        break;

    case 'website-testimonials-update':
        (new WebsiteTestimonialController())->update();
        break;

    case 'website-testimonials-delete':
        (new WebsiteTestimonialController())->delete();
        break;

    case 'website-faqs':
        (new WebsiteFaqController())->index();
        break;

    case 'website-faqs-create':
        (new WebsiteFaqController())->create();
        break;

    case 'website-faqs-store':
        (new WebsiteFaqController())->store();
        break;

    case 'website-faqs-edit':
        (new WebsiteFaqController())->edit();
        break;

    case 'website-faqs-update':
        (new WebsiteFaqController())->update();
        break;

    case 'website-faqs-delete':
        (new WebsiteFaqController())->delete();
        break;

    case 'website-products':
        (new WebsiteProductController())->index();
        break;

    case 'website-products-create':
        (new WebsiteProductController())->create();
        break;

    case 'website-products-store':
        (new WebsiteProductController())->store();
        break;

    case 'website-products-edit':
        (new WebsiteProductController())->edit();
        break;

    case 'website-products-update':
        (new WebsiteProductController())->update();
        break;

    case 'website-products-delete':
        (new WebsiteProductController())->delete();
        break;

    case 'website-portfolios':
        (new WebsitePortfolioController())->index();
        break;

    case 'website-portfolios-create':
        (new WebsitePortfolioController())->create();
        break;

    case 'website-portfolios-store':
        (new WebsitePortfolioController())->store();
        break;

    case 'website-portfolios-edit':
        (new WebsitePortfolioController())->edit();
        break;

    case 'website-portfolios-update':
        (new WebsitePortfolioController())->update();
        break;

    case 'website-portfolios-delete':
        (new WebsitePortfolioController())->delete();
        break;
    
    case 'portfolio-detail':
        (new FrontPortfolioController())->detail();
        break;

    case 'blog-detail':
        (new FrontBlogController())->detail();
        break;

    case 'robots.txt':
        header('Content-Type: text/plain; charset=utf-8');
        echo "User-agent: *\n";
        echo "Allow: /\n";
        echo "Disallow: /login\n";
        echo "Disallow: /logout\n";
        echo "Disallow: /dashboard\n";
        echo "Disallow: /users\n";
        echo "Disallow: /roles\n";
        echo "Disallow: /master-\n";
        echo "Disallow: /website-\n";
        echo "Disallow: /client/\n";
        echo "Disallow: /member/masuk\n";
        echo "Disallow: /member/daftar\n";
        echo "Disallow: /member/verifikasi\n";
        echo "Disallow: /member/lupa-password\n";
        echo "Disallow: /member/reset-password\n";
        echo "Disallow: /member/dashboard\n";
        echo "Disallow: /member/pesanan\n";
        echo "Disallow: /en/member/login\n";
        echo "Disallow: /en/member/register\n";
        echo "Disallow: /en/member/verify\n";
        echo "Disallow: /en/member/forgot-password\n";
        echo "Disallow: /en/member/reset-password\n";
        echo "Disallow: /en/member/dashboard\n";
        echo "Disallow: /en/member/orders\n";
        echo "Disallow: /api/\n";
        echo "Disallow: /*?*\n";
        echo "Sitemap: " . baseUrl() . "/sitemap.xml\n";
        break;

    case 'sitemap.xml':
        header('Content-Type: application/xml; charset=utf-8');
        $staticPages = ['home', 'about', 'services', 'products', 'events', 'portfolio', 'blog', 'contact', 'vendor-register'];
        $urls = [];
        foreach ($staticPages as $pageName) {
            $urls[] = ['loc' => frontUrl($pageName, [], 'id'), 'priority' => $pageName === 'home' ? '1.0' : '0.8'];
            $urls[] = ['loc' => frontUrl($pageName, [], 'en'), 'priority' => $pageName === 'home' ? '0.9' : '0.7'];
        }

        foreach ((new WebsiteService())->active() as $service) {
            $urls[] = ['loc' => frontUrl('service-detail', ['slug' => $service['slug']], 'id'), 'priority' => '0.8'];
            $urls[] = ['loc' => frontUrl('service-detail', ['slug' => $service['slug']], 'en'), 'priority' => '0.7'];
        }

        foreach ((new WebsitePortfolio())->active() as $portfolio) {
            $urls[] = ['loc' => frontUrl('portfolio-detail', ['slug' => $portfolio['slug_id']], 'id'), 'priority' => '0.7'];
            $urls[] = ['loc' => frontUrl('portfolio-detail', ['slug' => $portfolio['slug_en']], 'en'), 'priority' => '0.6'];
        }

        foreach ((new WebsitePost())->published(50) as $post) {
            $urls[] = ['loc' => frontUrl('blog-detail', ['slug' => $post['slug_id']], 'id'), 'priority' => '0.7'];
            $urls[] = ['loc' => frontUrl('blog-detail', ['slug' => $post['slug_en']], 'en'), 'priority' => '0.6'];
        }

        foreach ((new EventTicket())->publicEvents() as $event) {
            if (!empty($event['public_slug'])) {
                $urls[] = ['loc' => frontUrl('event-detail', ['slug' => $event['public_slug']], 'id'), 'priority' => '0.8'];
            }

            if (!empty($event['public_slug_en'])) {
                $urls[] = ['loc' => frontUrl('event-detail', ['slug' => $event['public_slug_en']], 'en'), 'priority' => '0.7'];
            }
        }

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $urlItem) {
            echo "  <url>\n";
            echo '    <loc>' . htmlspecialchars($urlItem['loc'], ENT_XML1) . "</loc>\n";
            echo "    <changefreq>weekly</changefreq>\n";
            echo '    <priority>' . htmlspecialchars($urlItem['priority'], ENT_XML1) . "</priority>\n";
            echo "  </url>\n";
        }
        echo "</urlset>\n";
        break;

    default:
    http_response_code(404);
    echo "404 - Page not found";
    break;
}
