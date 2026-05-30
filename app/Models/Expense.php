<?php

class Expense
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function create($data)
    {

        $stmt = $this->db->prepare("
            INSERT INTO expenses
            (
                expense_no,
                bank_account_id,
                expense_date,
                payment_method,
                beneficiary,
                billing_address,
                expense_category,
                description,
                amount,
                reference_no
                )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

        $stmt->execute([
            $data['expense_no'],
            $data['bank_account_id'],
            $data['expense_date'],
            $data['payment_method'],
            $data['beneficiary'],
            $data['billing_address'],
            $data['expense_category'],
            $data['description'],
            $data['amount'],
            $data['reference_no']
        ]);

        return $this->db->lastInsertId();
    }

    public function getAll()
    {
        $stmt = $this->db->query("
            SELECT 
            e.*,
            ba.account_code,
            ba.account_name,
            ba.bank_name,
            ba.account_number
            FROM expenses e
            LEFT JOIN bank_accounts ba ON ba.id = e.bank_account_id
            ORDER BY e.expense_date DESC, e.id DESC
            ");

        return $stmt->fetchAll();
    }


    public function generateNumber()
    {
        return 'EXP-' . date('Ymd-His');
    }

    public function addItem($expenseId, $item)
    {
        $stmt = $this->db->prepare("
            INSERT INTO expense_items
            (
                expense_id,
                account_id,
                description,
                amount
                )
            VALUES (?, ?, ?, ?)
            ");

        return $stmt->execute([
            $expenseId,
            $item['account_id'],
            $item['description'],
            $item['amount']
        ]);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT 
            e.*,
            ba.account_code,
            ba.account_name,
            ba.bank_name,
            ba.account_number,
            ba.account_holder
            FROM expenses e
            LEFT JOIN bank_accounts ba ON ba.id = e.bank_account_id
            WHERE e.id = ?
            LIMIT 1
            ");

        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function getItems($expenseId)
    {
        $stmt = $this->db->prepare("
            SELECT 
            ei.*,
            coa.account_code,
            coa.account_name
            FROM expense_items ei
            LEFT JOIN chart_of_accounts coa ON coa.id = ei.account_id
            WHERE ei.expense_id = ?
            ORDER BY ei.id ASC
            ");

        $stmt->execute([$expenseId]);

        return $stmt->fetchAll();
    }

    public function deleteItems($expenseId)
    {
        $stmt = $this->db->prepare("
            DELETE FROM expense_items
            WHERE expense_id = ?
            ");

        return $stmt->execute([$expenseId]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("
            DELETE FROM expenses
            WHERE id = ?
            ");

        return $stmt->execute([$id]);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE expenses SET
            bank_account_id = ?,
            expense_date = ?,
            payment_method = ?,
            beneficiary = ?,
            billing_address = ?,
            expense_category = ?,
            description = ?,
            amount = ?,
            reference_no = ?
            WHERE id = ?
            ");

        return $stmt->execute([
            $data['bank_account_id'],
            $data['expense_date'],
            $data['payment_method'],
            $data['beneficiary'],
            $data['billing_address'],
            $data['expense_category'],
            $data['description'],
            $data['amount'],
            $data['reference_no'],
            $id
        ]);
    }

    public function totalExpenseThisMonth()
    {
        $stmt = $this->db->query("
            SELECT COALESCE(SUM(amount), 0)
            FROM expenses
            WHERE MONTH(expense_date) = MONTH(CURDATE())
            AND YEAR(expense_date) = YEAR(CURDATE())
            ");

        return (float) $stmt->fetchColumn();
    }

    public function monthlyExpense($year = null)
    {
        $year = $year ?: date('Y');

        $stmt = $this->db->prepare("
            SELECT
                MONTH(expense_date) AS month_number,
                COALESCE(SUM(amount), 0) AS total
            FROM expenses
            WHERE YEAR(expense_date) = ?
            GROUP BY MONTH(expense_date)
        ");

        $stmt->execute([$year]);

        $data = array_fill(1, 12, 0);

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $data[(int) $row['month_number']] = (float) $row['total'];
        }

        return array_values($data);
    }

    public function expenseByCategoryThisMonth()
    {
        $stmt = $this->db->query("
            SELECT
                category,
                COALESCE(SUM(amount), 0) AS total
            FROM expenses
            WHERE MONTH(expense_date) = MONTH(CURDATE())
            AND YEAR(expense_date) = YEAR(CURDATE())
            GROUP BY category
            ORDER BY total DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}