<?php

class EmployeeCashAdvance
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    private function scopeWhere(&$sql, &$params)
    {
        $scope = $_SESSION['data_scope'] ?? 'own';
        $employeeId = $_SESSION['employee_id'] ?? null;

        if ($scope !== 'all') {
            $sql .= " AND ca.employee_id = ?";
            $params[] = $employeeId;
        }
    }

    public function markAsDeducted($employeeId, $startDate, $endDate, $payrollPeriodId)
    {
        $stmt = $this->db->prepare("
            UPDATE employee_cash_advances
            SET 
                payroll_period_id = ?,
                deducted_at = NOW(),
                updated_at = NOW()
            WHERE employee_id = ?
            AND status = 'paid'
            AND payroll_period_id IS NULL
            AND DATE(disbursed_at) BETWEEN ? AND ?
        ");

        return $stmt->execute([
            $payrollPeriodId,
            $employeeId,
            $startDate,
            $endDate
        ]);
    }

    public function countAll()
    {
        $sql = "
        SELECT COUNT(*) AS total
        FROM employee_cash_advances ca
        WHERE 1=1
        ";

        $params = [];
        $this->scopeWhere($sql, $params);

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $row = $stmt->fetch();

        return (int) ($row['total'] ?? 0);
    }

    public function getPaginated($limit, $offset)
    {
        $sql = "
        SELECT 
        ca.*,
        e.full_name AS employee_name,
        e.employee_code,
        ba.bank_name,
        ba.account_name,
        ba.account_number
        FROM employee_cash_advances ca
        LEFT JOIN employees e ON e.id = ca.employee_id
        LEFT JOIN bank_accounts ba ON ba.id = ca.payment_account_id
        WHERE 1=1
        ";

        $params = [];
        $this->scopeWhere($sql, $params);

        $sql .= "
        ORDER BY ca.id DESC
        LIMIT ? OFFSET ?
        ";

        $stmt = $this->db->prepare($sql);

        $index = 1;

        foreach ($params as $param) {
            $stmt->bindValue($index, $param, PDO::PARAM_INT);
            $index++;
        }

        $stmt->bindValue($index, (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue($index + 1, (int) $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function generateNumber()
    {
        $prefix = 'KB-' . date('Ym') . '-';

        $stmt = $this->db->prepare("
            SELECT cash_advance_number
            FROM employee_cash_advances
            WHERE cash_advance_number LIKE ?
            ORDER BY id DESC
            LIMIT 1
            ");

        $stmt->execute([$prefix . '%']);
        $last = $stmt->fetch();

        $number = 1;

        if ($last) {
            $lastNumber = (int) substr($last['cash_advance_number'], -4);
            $number = $lastNumber + 1;
        }

        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO employee_cash_advances
            (
                cash_advance_number,
                employee_id,
                request_date,
                purpose,
                description,
                amount,
                approved_amount,
                status,
                created_by
                )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

        return $stmt->execute([
            $data['cash_advance_number'],
            $data['employee_id'],
            $data['request_date'],
            $data['purpose'],
            $data['description'],
            $data['amount'],
            $data['approved_amount'] ?? 0,
            $data['status'] ?? 'waiting_supervisor_approval',
            $data['created_by'] ?? ($_SESSION['user_id'] ?? null)
        ]);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT 
            ca.*,
            e.full_name AS employee_name,
            e.employee_code,
            ba.bank_name,
            ba.account_name,
            ba.account_number
            FROM employee_cash_advances ca
            LEFT JOIN employees e ON e.id = ca.employee_id
            LEFT JOIN bank_accounts ba ON ba.id = ca.payment_account_id
            WHERE ca.id = ?
            LIMIT 1
            ");

        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE employee_cash_advances SET
                purpose = ?,
                description = ?,
                amount = ?,
                updated_at = NOW()
                WHERE id = ?
                AND status = 'waiting_supervisor_approval'
                ");

        return $stmt->execute([
            $data['purpose'],
            $data['description'],
            $data['amount'],
            $id
        ]);
    }

    public function supervisorApprove($id, $note = '')
    {
        $stmt = $this->db->prepare("
            UPDATE employee_cash_advances SET
                status = 'waiting_finance_approval',
                supervisor_approved_at = NOW(),
                supervisor_approved_by = ?,
                supervisor_note = ?,
                updated_at = NOW()
                WHERE id = ?
                AND status = 'waiting_supervisor_approval'
                ");

        return $stmt->execute([
            $_SESSION['user_id'] ?? null,
            $note,
            $id
        ]);
    }

    public function financeApprove($id, $approvedAmount, $note = '')
    {
        $stmt = $this->db->prepare("
            UPDATE employee_cash_advances SET
                status = 'waiting_disbursement',
                approved_amount = ?,
                finance_approved_at = NOW(),
                finance_approved_by = ?,
                finance_note = ?,
                updated_at = NOW()
                WHERE id = ?
                AND status = 'waiting_finance_approval'
                ");

        return $stmt->execute([
            $approvedAmount,
            $_SESSION['user_id'] ?? null,
            $note,
            $id
        ]);
    }


    public function disburse($id, $paymentAccountId, $note = '')
    {
        $this->db->beginTransaction();

        try {
            $item = $this->find($id);

            if (!$item || ($item['status'] ?? '') !== 'waiting_disbursement') {
                $this->db->rollBack();
                return false;
            }

            $amount = (float) ($item['approved_amount'] ?? 0);
            $bankCheck = $this->db->prepare("
                SELECT current_balance
                FROM bank_accounts
                WHERE id = ?
                LIMIT 1
            ");

            $bankCheck->execute([$paymentAccountId]);
            $bankAccount = $bankCheck->fetch();

            if (!$bankAccount) {
                $this->db->rollBack();
                return false;
            }

            $currentBalance = (float) ($bankAccount['current_balance'] ?? 0);

            if ($currentBalance < $amount) {
                $this->db->rollBack();
                return false;
            }

            if ($amount <= 0) {
                $this->db->rollBack();
                return false;
            }

            $stmt = $this->db->prepare("
                UPDATE employee_cash_advances SET
                    status = 'paid',
                    payment_account_id = ?,
                    disbursed_at = NOW(),
                    disbursed_by = ?,
                    disbursement_note = ?,
                    updated_at = NOW()
                    WHERE id = ?
                    AND status = 'waiting_disbursement'
                    ");

            $stmt->execute([
                $paymentAccountId,
                $_SESSION['user_id'] ?? null,
                $note,
                $id
            ]);

            $bank = $this->db->prepare("
                UPDATE bank_accounts
                    SET current_balance = current_balance - ?
                    WHERE id = ?
                    ");

            $bank->execute([
                $amount,
                $paymentAccountId
            ]);

            $trx = $this->db->prepare("
                INSERT INTO bank_transactions
                (
                    bank_account_id,
                    transaction_date,
                    transaction_type,
                    reference_type,
                    reference_id,
                    description,
                    amount
                    )
                VALUES (?, ?, ?, ?, ?, ?, ?)
                ");

            $trx->execute([
                $paymentAccountId,
                date('Y-m-d'),
                'out',
                'employee_cash_advance',
                $id,
                'Pencairan kasbon karyawan: ' . ($item['cash_advance_number'] ?? ''),
                $amount
            ]);

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function reject($id, $note = '')
    {
        $stmt = $this->db->prepare("
            UPDATE employee_cash_advances SET
                status = 'rejected',
                finance_note = ?,
                updated_at = NOW()
                WHERE id = ?
                AND status != 'paid'
                ");

        return $stmt->execute([
            $note,
            $id
        ]);
    }

    public function getPaidCashAdvanceByEmployee($employeeId, $startDate, $endDate)
    {
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(approved_amount), 0) AS total
            FROM employee_cash_advances
            WHERE employee_id = ?
            AND status = 'paid'
            AND payroll_period_id IS NULL
            AND DATE(disbursed_at) BETWEEN ? AND ?
        ");

        $stmt->execute([
            $employeeId,
            $startDate,
            $endDate
        ]);

        $row = $stmt->fetch();

        return (float) ($row['total'] ?? 0);
    }
}