<?php

class PayrollController extends Controller
{
    public function generate()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $periodId = $_GET['period_id'] ?? null;

        if (!$periodId) {
            $this->redirect('payroll-periods');
        }

        $periodModel = new PayrollPeriod();
        $period = $periodModel->find($periodId);

        if (!$period) {

            activity_log(
                'HRIS - Payroll',
                'generate_failed',
                'Gagal generate payroll karena periode tidak ditemukan',
                $periodId
            );

            $this->redirect('payroll-periods');
        }

        $employeeModel = new Employee();
        $payrollModel = new Payroll();
        $cashAdvanceModel = new EmployeeCashAdvance();

        $employees = $employeeModel->getActive();

        $generatedCount = 0;

        foreach ($employees as $employee) {

            $exists = $payrollModel->exists($periodId, $employee['id']);

            if ($exists) {
                continue;
            }

            $basicSalary = (float) ($employee['basic_salary'] ?? 0);

            $attendanceDays = $this->countAttendance(
                $employee['id'],
                $period['start_date'],
                $period['end_date']
            );

            $lateMinutes = $this->sumLateMinutes(
                $employee['id'],
                $period['start_date'],
                $period['end_date']
            );

            $overtimeMinutes = $this->sumOvertimeMinutes(
                $employee['id'],
                $period['start_date'],
                $period['end_date']
            );

            $overtimeAmount = $overtimeMinutes * 500;

            $cashAdvanceDeduction = $cashAdvanceModel->getPaidCashAdvanceByEmployee(
                $employee['id'],
                $period['start_date'],
                $period['end_date']
            );

            $deductionAmount = $cashAdvanceDeduction;

            $grossSalary = $basicSalary + $overtimeAmount;

            $netSalary = $grossSalary - $deductionAmount;

            $payrollModel->create([
                'payroll_period_id' => $periodId,
                'employee_id' => $employee['id'],

                'basic_salary' => $basicSalary,

                'allowance_amount' => 0,
                'overtime_amount' => $overtimeAmount,
                'bonus_amount' => 0,

                'deduction_amount' => $deductionAmount,
                'cash_advance_deduction' => $cashAdvanceDeduction,
                'bpjs_amount' => 0,
                'tax_amount' => 0,

                'gross_salary' => $grossSalary,
                'net_salary' => $netSalary,

                'attendance_days' => $attendanceDays,
                'absent_days' => 0,
                'late_minutes' => $lateMinutes,
                'overtime_minutes' => $overtimeMinutes,

                'status' => 'draft',
                'notes' => ''
            ]);

            $cashAdvanceModel->markAsDeducted(
                $employee['id'],
                $period['start_date'],
                $period['end_date'],
                $periodId
            );

            $generatedCount++;
        }

        activity_log(
            'HRIS - Payroll',
            'generate',
            'Generate payroll periode: ' .
            ($period['period_name'] ?? '-') .
            ' (' . $generatedCount . ' payroll dibuat)',
            $periodId,
            $period['period_name'] ?? null
        );

        $this->redirect('payrolls', ['period_id' => $periodId]);
    }

    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $periodId = $_GET['period_id'] ?? null;

        if (!$periodId) {
            $this->redirect('payroll-periods');
        }

        $periodModel = new PayrollPeriod();
        $period = $periodModel->find($periodId);

        if (!$period) {

            activity_log(
                'HRIS - Payroll',
                'view_failed',
                'Gagal membuka payroll karena periode tidak ditemukan',
                $periodId
            );

            $this->redirect('payroll-periods');
        }

        $p = max(1, (int) ($_GET['p'] ?? 1));
        $limit = 20;
        $offset = ($p - 1) * $limit;

        $model = new Payroll();

        $totalRows = $model->countByPeriod($periodId);
        $totalPages = ceil($totalRows / $limit);

        activity_log(
            'HRIS - Payroll',
            'view',
            'Melihat daftar payroll periode: ' . ($period['period_name'] ?? '-'),
            $periodId,
            $period['period_name'] ?? null
        );

        $this->view('payrolls/index', [
            'title' => 'Payroll',
            'payrolls' => $model->paginateByPeriod($periodId, $limit, $offset),
            'period' => $period,
            'currentPage' => $p,
            'limit' => $limit,
            'periodId' => $periodId,
            'p' => $p,
            'totalPages' => $totalPages,
            'totalRows' => $totalRows
        ]);
    }

    private function countAttendance($employeeId, $startDate, $endDate)
    {
        $db = Database::connect();

        $stmt = $db->prepare("
            SELECT COUNT(*) AS total
            FROM attendances
            WHERE employee_id = ?
            AND attendance_date BETWEEN ? AND ?
            AND status IN ('present','late')
        ");

        $stmt->execute([
            $employeeId,
            $startDate,
            $endDate
        ]);

        $row = $stmt->fetch();

        return (int) ($row['total'] ?? 0);
    }

    private function sumLateMinutes($employeeId, $startDate, $endDate)
    {
        $db = Database::connect();

        $stmt = $db->prepare("
            SELECT SUM(late_minutes) AS total
            FROM attendances
            WHERE employee_id = ?
            AND attendance_date BETWEEN ? AND ?
        ");

        $stmt->execute([
            $employeeId,
            $startDate,
            $endDate
        ]);

        $row = $stmt->fetch();

        return (int) ($row['total'] ?? 0);
    }

    private function sumOvertimeMinutes($employeeId, $startDate, $endDate)
    {
        $db = Database::connect();

        $stmt = $db->prepare("
            SELECT SUM(total_minutes) AS total
            FROM overtime_requests
            WHERE employee_id = ?
            AND overtime_date BETWEEN ? AND ?
            AND status = 'approved'
        ");

        $stmt->execute([
            $employeeId,
            $startDate,
            $endDate
        ]);

        $row = $stmt->fetch();

        return (int) ($row['total'] ?? 0);
    }

    public function print()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('payroll-periods');
        }

        $model = new Payroll();
        $payroll = $model->find($id);

        if (!$payroll) {

            activity_log(
                'HRIS - Payroll',
                'print_failed',
                'Gagal print slip gaji karena data payroll tidak ditemukan',
                $id
            );

            $this->redirect('payroll-periods');
        }

        activity_log(
            'HRIS - Payroll',
            'print',
            'Print slip gaji: ' . ($payroll['full_name'] ?? '-'),
            $id,
            $payroll['full_name'] ?? null
        );

        require_once __DIR__ . '/../Views/payrolls/print.php';
    }

    public function show()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('payroll-periods');
        }

        $model = new Payroll();
        $payroll = $model->find($id);

        if (!$payroll) {

            activity_log(
                'HRIS - Payroll',
                'view_failed',
                'Gagal membuka detail payroll karena data tidak ditemukan',
                $id
            );

            $this->redirect('payroll-periods');
        }

        activity_log(
            'HRIS - Payroll',
            'view',
            'Melihat detail payroll: ' . ($payroll['full_name'] ?? '-'),
            $id,
            $payroll['full_name'] ?? null
        );

        $this->view('payrolls/show', [
            'title' => 'Detail Payroll',
            'payroll' => $payroll
        ]);
    }

    public function edit()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('payroll-periods');
        }

        $model = new Payroll();
        $payroll = $model->find($id);

        if (!$payroll) {

            activity_log(
                'HRIS - Payroll',
                'edit_failed',
                'Gagal membuka form edit payroll karena data tidak ditemukan',
                $id
            );

            $this->redirect('payroll-periods');
        }

        activity_log(
            'HRIS - Payroll',
            'edit_form',
            'Membuka form edit payroll: ' . ($payroll['full_name'] ?? '-'),
            $id,
            $payroll['full_name'] ?? null
        );

        $this->view('payrolls/edit', [
            'title' => 'Edit Payroll',
            'payroll' => $payroll
        ]);
    }

    public function update()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_POST['id'] ?? null;

        if (!$id) {
            $this->redirect('payroll-periods');
        }

        $basicSalary = (float) ($_POST['basic_salary'] ?? 0);
        $allowanceAmount = (float) ($_POST['allowance_amount'] ?? 0);
        $overtimeAmount = (float) ($_POST['overtime_amount'] ?? 0);
        $bonusAmount = (float) ($_POST['bonus_amount'] ?? 0);

        $deductionAmount = (float) ($_POST['deduction_amount'] ?? 0);
        $bpjsAmount = (float) ($_POST['bpjs_amount'] ?? 0);
        $taxAmount = (float) ($_POST['tax_amount'] ?? 0);

        $grossSalary =
            $basicSalary +
            $allowanceAmount +
            $overtimeAmount +
            $bonusAmount;

        $netSalary =
            $grossSalary -
            $deductionAmount -
            $bpjsAmount -
            $taxAmount;

        $model = new Payroll();

        $oldPayroll = $model->find($id);

        if (!$oldPayroll) {

            activity_log(
                'HRIS - Payroll',
                'update_failed',
                'Gagal update payroll karena data tidak ditemukan',
                $id
            );

            $this->redirect('payroll-periods');
        }

        $model->update($id, [

            'basic_salary' => $basicSalary,

            'allowance_amount' => $allowanceAmount,
            'overtime_amount' => $overtimeAmount,
            'bonus_amount' => $bonusAmount,

            'deduction_amount' => $deductionAmount,
            'bpjs_amount' => $bpjsAmount,
            'tax_amount' => $taxAmount,

            'gross_salary' => $grossSalary,
            'net_salary' => $netSalary,

            'status' => $_POST['status'] ?? 'draft',
            'notes' => $_POST['notes'] ?? ''
        ]);

        activity_log(
            'HRIS - Payroll',
            'update',
            'Mengubah payroll: ' . ($oldPayroll['full_name'] ?? '-'),
            $id,
            $oldPayroll['full_name'] ?? null
        );

        $this->redirect('payrolls-show', ['id' => $id]);
    }

    public function paid()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('payroll-periods');
        }

        $model = new Payroll();
        $payroll = $model->find($id);

        if (!$payroll) {

            activity_log(
                'HRIS - Payroll',
                'paid_failed',
                'Gagal update status payroll karena data tidak ditemukan',
                $id
            );

            $this->redirect('payroll-periods');
        }

        $model->update($id, [

            'basic_salary' => $payroll['basic_salary'],

            'allowance_amount' => $payroll['allowance_amount'],
            'overtime_amount' => $payroll['overtime_amount'],
            'bonus_amount' => $payroll['bonus_amount'],

            'deduction_amount' => $payroll['deduction_amount'],
            'bpjs_amount' => $payroll['bpjs_amount'],
            'tax_amount' => $payroll['tax_amount'],

            'gross_salary' => $payroll['gross_salary'],
            'net_salary' => $payroll['net_salary'],

            'status' => 'paid',
            'notes' => $payroll['notes']
        ]);

        activity_log(
            'HRIS - Payroll',
            'paid',
            'Payroll dibayar: ' . ($payroll['full_name'] ?? '-'),
            $id,
            $payroll['full_name'] ?? null
        );

        $this->redirect('payrolls-show', ['id' => $id]);
    }
}