<?php

class DashboardController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        if (isClientPortalUser()) {
            $this->redirect('client/dashboard');
        }

        requirePermission('dashboard.view');

        activity_log(
            'Dashboard',
            'view',
            'Mengakses dashboard utama'
        );

        if (can('report.owner')) {
            return $this->owner();
        }

        if (can('report.finance')) {
            return $this->finance();
        }

        if (can('report.operational')) {
            return $this->operasional();
        }

        if (can('report.sales')) {
            return $this->sales();
        }

        return $this->employee();
    }

    public function employee()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('dashboard.view');

        $rentalModel = new Rental();
        $unitModel = new Unit();
        $invoiceModel = new Invoice();
        $invoicePaymentModel = new InvoicePayment();
        $expenseModel = new Expense();
        $quotationModel = new Quotation();

        $incomeThisMonth = $invoicePaymentModel->totalIncomeThisMonth();
        $expenseThisMonth = $expenseModel->totalExpenseThisMonth();

        $bankModel = new BankAccount();

        $totalBankBalance = 0;

        foreach ($bankModel->getActive() as $bank) {
            $totalBankBalance += (float) ($bank['current_balance'] ?? 0);
        }

        activity_log(
            'Dashboard Employee',
            'view',
            'Melihat dashboard karyawan'
        );

        $this->view('dashboard/employee', [
            'title' => 'Dashboard Karyawan',
        ]);
    }

    public function owner()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('dashboard.view');

        $rentalModel = new Rental();
        $unitModel = new Unit();
        $invoiceModel = new Invoice();
        $invoicePaymentModel = new InvoicePayment();
        $expenseModel = new Expense();
        $quotationModel = new Quotation();

        $incomeThisMonth = $invoicePaymentModel->totalIncomeThisMonth();
        $expenseThisMonth = $expenseModel->totalExpenseThisMonth();

        $bankModel = new BankAccount();

        $totalBankBalance = 0;

        foreach ($bankModel->getActive() as $bank) {
            $totalBankBalance += (float) ($bank['current_balance'] ?? 0);
        }

        activity_log(
            'Dashboard Owner',
            'view',
            'Melihat dashboard owner'
        );

        $this->view('dashboard/owner', [
            'title' => 'Dashboard Owner',
            'incomeThisMonth' => $incomeThisMonth,
            'expenseThisMonth' => $expenseThisMonth,
            'profitThisMonth' => $incomeThisMonth - $expenseThisMonth,
            'outstandingReceivable' => $invoiceModel->getOutstandingReceivable(),
            'rentalActive' => $rentalModel->countByStatus('on_rent'),
            'rentalScheduled' => $rentalModel->countByStatus('scheduled'),
            'unitAvailable' => $unitModel->countByStatus('available'),
            'unitMaintenance' => $unitModel->countByStatus('maintenance'),
            'unitBroken' => $unitModel->countByStatus('broken'),
            'totalQuotations' => $quotationModel->countAll(),
            'quotationDeal' => $quotationModel->countByStatus('approved'),
            'incomeMonthlyChart' => $invoicePaymentModel->monthlyIncome(),
            'expenseMonthlyChart' => $expenseModel->monthlyExpense(),
            'overdueInvoices' => $invoiceModel->overdueInvoices(5),
            'totalBankBalance' => $totalBankBalance,
            'bankAccounts' => $bankModel->getActive(),
            'topQuotationsThisMonth' => $quotationModel->topQuotationsThisMonth(4),
            'upcomingReturns' => $rentalModel->getEndingTomorrow(),
            'rentalTrendChart' => $rentalModel->monthlyRentalTrend(),
        ]);
    }

    public function operasional()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('dashboard.view');

        $rentalModel = new Rental();
        $unitModel = new Unit();
        $unitMaintenanceModel = new UnitMaintenance();
        $vehicleMaintenanceModel = new VehicleMaintenance();
        $vehicleModel = new Vehicle();

        $today = date('Y-m-d');
        $tomorrow = date('Y-m-d', strtotime('+1 day'));

        $availableToday = $unitModel->getAvailableGrouped($today, $today);
        $availableTomorrow = $unitModel->getAvailableGrouped($tomorrow, $tomorrow);

        activity_log(
            'Dashboard Operasional',
            'view',
            'Melihat dashboard operasional'
        );

        $this->view('dashboard/operasional', [
            'title' => 'Dashboard Operasional',

            'todayDelivery' => $rentalModel->countTodayDelivery(),
            'todayPickup' => $rentalModel->countTodayPickup(),
            'rentalActive' => $rentalModel->countByStatus('on_rent'),
            'rentalScheduled' => $rentalModel->countByStatus('scheduled'),

            'unitAvailable' => $unitModel->countByStatus('available'),
            'unitMaintenance' => $unitModel->countByStatus('maintenance'),
            'unitBroken' => $unitModel->countByStatus('broken'),
            'unitTotal' => $unitModel->countAllActive(),

            'todayDeliveriesList' => $rentalModel->getTodayDeliveries(),
            'todayPickupsList' => $rentalModel->getTodayPickups(),
            'endingTomorrowList' => $rentalModel->getEndingTomorrow(),
            'upcomingReturns' => $rentalModel->getEndingTomorrow(),

            'availableToday' => $availableToday,
            'availableTomorrow' => $availableTomorrow,

            'unitMaintenanceDueList' => $unitMaintenanceModel->getDueUnits(),
            'vehicleServiceDueList' => $vehicleMaintenanceModel->dueVehicles(),
            'vehicleReminderList' => $vehicleModel->reminders(),
            'vehicleFleetList' => $vehicleModel->all(),

            'activeTechnicians' => $rentalModel->getTodayTechnicians(),
            'unitAvailabilityBoard' => $unitModel->getAvailabilityBoard(),
        ]);
    }

    public function sales()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('dashboard.view');

        $customerModel = new Customer();
        $quotationModel = new Quotation();
        $orderModel = new Rental();
        $invoiceModel = new Invoice();
        $invoicePaymentModel = new InvoicePayment();

        activity_log(
            'Dashboard Sales',
            'view',
            'Melihat dashboard sales'
        );

        $this->view('dashboard/sales', [
            'title' => 'Dashboard Sales',
            'totalCustomers' => $customerModel->countAll(),
            'totalQuotations' => $quotationModel->countAll(),
            'quotationThisMonth' => $quotationModel->countThisMonth(),
            'quotationDeal' => $quotationModel->countByStatus('order'),
            'quotationPending' => $quotationModel->countByStatus('approved'),
            'quotationDraft' => $quotationModel->countByStatus('waiting approval'),
            'rentalFromSales' => $orderModel->countByStatus('scheduled'),
            'recentQuotations' => $quotationModel->latest(7),
            'followUpList' => $quotationModel->followUpToday(),
            'latestCustomers' => $customerModel->latest(6),
            'topCustomers' => $customerModel->topCustomers(),
            'recentSalesActivities' => $quotationModel->recentActivities(),
            'upcomingProjects' => $orderModel->upcomingProjects(),
            'totalQuotationValue' => $quotationModel->totalQuotationThisMonth(),
            'totalDealValue' => $quotationModel->totalDealThisMonth(),
            'topLocations' => $quotationModel->topLocations(),
            'paidRevenueThisMonth' => $invoiceModel->paidRevenueThisMonth(),
            'monthlyQuotationChart' => $quotationModel->monthlyQuotationChart(),
            'dailyQuotationChart' => $quotationModel->dailyQuotationChart(7),
            'monthlyIncomeChart' => $invoicePaymentModel->monthlyIncomeChart(),
            'totalIncomeThisMonth' => $invoicePaymentModel->totalIncomeThisMonth(),
            'newCustomersThisMonth' => $customerModel->totalNewCustomersThisMonth(),
            'latestCustomers' => $customerModel->latestCustomers(),
            'topQuotationsThisMonth' => $quotationModel->topQuotationsThisMonth(),
            'latestPayments' => $invoicePaymentModel->latestPayments(7),
        ]);
    }

    public function finance()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('dashboard.view');

        $coaModel = new ChartOfAccount();
        $invoiceModel = new Invoice();
        $invoicePaymentModel = new InvoicePayment();
        $bankModel = new BankAccount();
        $expenseModel = new Expense();
        $vendorBillModel = new VendorBill();

        $totalBankBalance = 0;

        foreach ($bankModel->getActive() as $bank) {
            $totalBankBalance += (float) ($bank['current_balance'] ?? 0);
        }

        $incomeThisMonth = $invoicePaymentModel->totalIncomeThisMonth();
        $expenseThisMonth = $expenseModel->totalExpenseThisMonth();

        activity_log(
            'Dashboard Finance',
            'view',
            'Melihat dashboard finance'
        );

        $this->view('dashboard/finance', [
            'title' => 'Dashboard Finance',
            'totalBankBalance' => $totalBankBalance,
            'incomeThisMonth' => $incomeThisMonth,
            'expenseThisMonth' => $expenseThisMonth,
            'cashFlowThisMonth' => $incomeThisMonth - $expenseThisMonth,
            'outstandingReceivable' => $invoiceModel->getOutstandingReceivable(),
            'currentMonthProfit' => $coaModel->getCurrentMonthProfit(),
            'latestPayments' => $invoicePaymentModel->latestPayments(5),
            'incomeMonthlyChart' => $invoicePaymentModel->monthlyIncome(),
            'expenseMonthlyChart' => $expenseModel->monthlyExpense(),
            'expenseByAccount' => $coaModel->expenseByAccountThisMonth(),
            'latestJournalTransactions' => $coaModel->latestJournalTransactions(5),
            'overdueInvoices' => $invoiceModel->overdueInvoices(),
            'outstandingPayable' => $vendorBillModel->getOutstandingPayable(),
            'receivableMonthlyChart' => $invoiceModel->monthlyReceivableChart(),
            'payableMonthlyChart' => $vendorBillModel->monthlyPayableChart(),
        ]);
    }
}
