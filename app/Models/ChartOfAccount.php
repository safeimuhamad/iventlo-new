<?php

class ChartOfAccount
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function findByCode($accountCode)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM chart_of_accounts
            WHERE account_code = ?
            AND is_active = 1
            LIMIT 1
            ");

        $stmt->execute([$accountCode]);

        return $stmt->fetch();
    }

    public function getIdByCode($accountCode)
    {
        $account = $this->findByCode($accountCode);

        return $account['id'] ?? null;
    }

    public function getActive()
    {
        $stmt = $this->db->query("
            SELECT *
            FROM chart_of_accounts
            WHERE is_active = 1
            ORDER BY account_code ASC
            ");

        return $stmt->fetchAll();
    }


    public function getTrialBalance()
    {
        $stmt = $this->db->query("
            SELECT
            coa.id,
            coa.account_code,
            coa.account_name,
            coa.account_type,
            coa.normal_balance,

            COALESCE(SUM(jel.debit), 0) AS total_debit,
            COALESCE(SUM(jel.credit), 0) AS total_credit

            FROM chart_of_accounts coa

            LEFT JOIN journal_entry_lines jel
            ON jel.account_id = coa.id

            GROUP BY coa.id

            ORDER BY coa.account_code ASC
            ");

        return $stmt->fetchAll();
    }

    public function getProfitLossByType($accountType, $startDate, $endDate)
    {
        $stmt = $this->db->prepare("
            SELECT
            coa.id,
            coa.account_code,
            coa.account_name,
            coa.account_type,
            coa.normal_balance,
            COALESCE(SUM(jel.debit), 0) AS total_debit,
            COALESCE(SUM(jel.credit), 0) AS total_credit
            FROM chart_of_accounts coa
            LEFT JOIN journal_entry_lines jel ON jel.account_id = coa.id
            LEFT JOIN journal_entries je ON je.id = jel.journal_entry_id
            WHERE coa.account_type = ?
            AND (
                je.journal_date BETWEEN ? AND ?
                OR je.journal_date IS NULL
                )
            GROUP BY coa.id
            ORDER BY coa.account_code ASC
            ");

        $stmt->execute([$accountType, $startDate, $endDate]);

        return $stmt->fetchAll();
    }
    public function getBalanceSheetAccounts($accountType, $endDate)
    {
        $stmt = $this->db->prepare("
            SELECT
            coa.id,
            coa.account_code,
            coa.account_name,
            coa.account_type,
            coa.normal_balance,

            COALESCE(SUM(jel.debit), 0) AS total_debit,
            COALESCE(SUM(jel.credit), 0) AS total_credit

            FROM chart_of_accounts coa

            LEFT JOIN journal_entry_lines jel
            ON jel.account_id = coa.id

            LEFT JOIN journal_entries je
            ON je.id = jel.journal_entry_id

            WHERE coa.account_type = ?
            AND (
                je.journal_date <= ?
                OR je.journal_date IS NULL
                )

            GROUP BY coa.id

            ORDER BY coa.account_code ASC
            ");

        $stmt->execute([$accountType, $endDate]);

        return $stmt->fetchAll();
    }

    public function getNetProfitUntil($endDate)
    {
        $stmt = $this->db->prepare("
            SELECT
            coa.account_type,

            COALESCE(SUM(jel.debit), 0) AS total_debit,
            COALESCE(SUM(jel.credit), 0) AS total_credit

            FROM chart_of_accounts coa

            LEFT JOIN journal_entry_lines jel
            ON jel.account_id = coa.id

            LEFT JOIN journal_entries je
            ON je.id = jel.journal_entry_id

            WHERE coa.account_type IN ('income', 'expense')
            AND (
                je.journal_date <= ?
                OR je.journal_date IS NULL
                )

            GROUP BY coa.account_type
            ");

        $stmt->execute([$endDate]);

        $rows = $stmt->fetchAll();

        $income = 0;
        $expense = 0;

        foreach ($rows as $row) {

            if ($row['account_type'] === 'income') {
                $income = (float)$row['total_credit']
                - (float)$row['total_debit'];
            }

            if ($row['account_type'] === 'expense') {
                $expense = (float)$row['total_debit']
                - (float)$row['total_credit'];
            }
        }

        return $income - $expense;
    }

    public function getAccountBalanceByCode($accountCode)
    {
        $stmt = $this->db->prepare("
            SELECT
            coa.normal_balance,

            COALESCE(SUM(jel.debit), 0) AS total_debit,
            COALESCE(SUM(jel.credit), 0) AS total_credit

            FROM chart_of_accounts coa

            LEFT JOIN journal_entry_lines jel
            ON jel.account_id = coa.id

            WHERE coa.account_code = ?

            GROUP BY coa.id

            LIMIT 1
            ");

        $stmt->execute([$accountCode]);

        $row = $stmt->fetch();

        if (!$row) {
            return 0;
        }

        return ($row['normal_balance'] ?? 'debit') === 'debit'
        ? ((float)$row['total_debit'] - (float)$row['total_credit'])
        : ((float)$row['total_credit'] - (float)$row['total_debit']);
    }

    public function getCurrentMonthProfit()
    {
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-d');

        $stmt = $this->db->prepare("
            SELECT
            coa.account_type,

            COALESCE(SUM(jel.debit), 0) AS total_debit,
            COALESCE(SUM(jel.credit), 0) AS total_credit

            FROM chart_of_accounts coa

            LEFT JOIN journal_entry_lines jel
            ON jel.account_id = coa.id

            LEFT JOIN journal_entries je
            ON je.id = jel.journal_entry_id

            WHERE coa.account_type IN ('income', 'expense')
            AND je.journal_date BETWEEN ? AND ?

            GROUP BY coa.account_type
            ");

        $stmt->execute([$startDate, $endDate]);

        $rows = $stmt->fetchAll();

        $income = 0;
        $expense = 0;

        foreach ($rows as $row) {

            if ($row['account_type'] === 'income') {
                $income = (float)$row['total_credit']
                - (float)$row['total_debit'];
            }

            if ($row['account_type'] === 'expense') {
                $expense = (float)$row['total_debit']
                - (float)$row['total_credit'];
            }
        }

        return $income - $expense;
    }


    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM chart_of_accounts
            WHERE id = ?
            LIMIT 1
            ");

        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO chart_of_accounts
            (
                account_code,
                account_name,
                account_type,
                normal_balance,
                is_active
                )
            VALUES (?, ?, ?, ?, 1)
            ");

        return $stmt->execute([
            $data['account_code'],
            $data['account_name'],
            $data['account_type'],
            $data['normal_balance']
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE chart_of_accounts SET
            account_code = ?,
            account_name = ?,
            account_type = ?,
            normal_balance = ?
            WHERE id = ?
            ");

        return $stmt->execute([
            $data['account_code'],
            $data['account_name'],
            $data['account_type'],
            $data['normal_balance'],
            $id
        ]);
    }

    public function softDelete($id)
    {
        $stmt = $this->db->prepare("
            UPDATE chart_of_accounts
            SET is_active = 0
            WHERE id = ?
            ");

        return $stmt->execute([$id]);
    }

    public function expenseByAccountThisMonth()
    {
        $stmt = $this->db->query("
            SELECT
            coa.account_name,
            COALESCE(SUM(jel.debit), 0) AS total
            FROM journal_entry_lines jel
            JOIN chart_of_accounts coa
            ON coa.id = jel.account_id
            WHERE coa.account_type = 'expense'
            AND MONTH(jel.created_at) = MONTH(CURDATE())
            AND YEAR(jel.created_at) = YEAR(CURDATE())
            GROUP BY coa.id, coa.account_name
            HAVING total > 0
            ORDER BY total DESC
            ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function latestJournalTransactions($limit = 5)
{
    $limit = (int) $limit;

    $stmt = $this->db->prepare("
        SELECT
            je.id,
            je.journal_date,
            je.reference_type,
            je.reference_id,
            je.description,
            je.total_debit,
            je.total_credit,
            je.created_at
        FROM journal_entries je
        ORDER BY je.journal_date DESC, je.id DESC
        LIMIT ?
    ");

    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}