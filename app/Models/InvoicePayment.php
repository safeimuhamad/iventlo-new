<?php

class InvoicePayment
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO invoice_payments
            (
                invoice_id,
                bank_account_id,
                payment_date,
                payment_amount,
                payment_method,
                bank_account,
                reference_no,
                notes
                )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $data['invoice_id'],
            $data['bank_account_id'],
            $data['payment_date'],
            $data['payment_amount'],
            $data['payment_method'],
            $data['bank_account'],
            $data['reference_no'],
            $data['notes']
        ]);

        return $this->db->lastInsertId();
    }


    public function getTotalPaid($invoiceId)
    {
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(payment_amount), 0) AS total_paid
            FROM invoice_payments
            WHERE invoice_id = ?
            ");

        $stmt->execute([$invoiceId]);

        return (float) $stmt->fetch()['total_paid'];
    }

    public function getByInvoice($invoiceId)
    {
        $stmt = $this->db->prepare("
            SELECT 
            ip.*,
            ba.account_code,
            ba.account_name,
            ba.bank_name,
            ba.account_number
            FROM invoice_payments ip
            LEFT JOIN bank_accounts ba 
            ON ba.id = ip.bank_account_id
            WHERE ip.invoice_id = ?
            ORDER BY ip.payment_date ASC, ip.id ASC
            ");

        $stmt->execute([$invoiceId]);

        return $stmt->fetchAll();
    }

    public function getRemainingAmount($invoiceId, $invoiceTotal)
    {
        $totalPaid = $this->getTotalPaid($invoiceId);

        return max(0, (float)$invoiceTotal - (float)$totalPaid);
    }

    public function getComputedStatus($invoiceId, $invoiceTotal)
    {
        $totalPaid = $this->getTotalPaid($invoiceId);
        $remaining = max(0, (float)$invoiceTotal - (float)$totalPaid);

        if ($totalPaid <= 0) {
            return 'waiting payment';
        }

        if ($remaining > 0) {
            return 'partial paid';
        }

        return 'paid';
    }

    public function monthlyIncomeChart($year = null)
    {
        $year = $year ?: date('Y');

        $stmt = $this->db->prepare("
            SELECT
                MONTH(payment_date) AS month_number,
                COALESCE(SUM(payment_amount), 0) AS total_income
            FROM invoice_payments
            WHERE YEAR(payment_date) = ?
            GROUP BY MONTH(payment_date)
            ORDER BY MONTH(payment_date) ASC
        ");

        $stmt->execute([$year]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $labels = [];
        $data = [];

        $monthNames = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug',
            9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
        ];

        foreach ($rows as $row) {
            $monthNumber = (int) $row['month_number'];

            $labels[] = $monthNames[$monthNumber] ?? '-';
            $data[] = (float) $row['total_income'];
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    public function totalIncomeThisMonth()
    {
        $stmt = $this->db->query("
            SELECT COALESCE(SUM(payment_amount), 0) AS total
            FROM invoice_payments
            WHERE MONTH(payment_date) = MONTH(CURDATE())
            AND YEAR(payment_date) = YEAR(CURDATE())
        ");

        return (float) $stmt->fetchColumn();
    }

    public function latestPayments($limit = 7)
    {
        $stmt = $this->db->prepare("
            SELECT
                ip.*,
                i.no_invoice,
                i.customer_name
            FROM invoice_payments ip
            LEFT JOIN invoices i
                ON i.id = ip.invoice_id
            WHERE ip.payment_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
            ORDER BY ip.payment_date DESC, ip.id DESC
            LIMIT ?
        ");

        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function monthlyIncome($year = null)
    {
        $year = $year ?: date('Y');

        $stmt = $this->db->prepare("
            SELECT
                MONTH(payment_date) AS month_number,
                COALESCE(SUM(payment_amount), 0) AS total
            FROM invoice_payments
            WHERE YEAR(payment_date) = ?
            GROUP BY MONTH(payment_date)
        ");

        $stmt->execute([$year]);

        $data = array_fill(1, 12, 0);

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $data[(int) $row['month_number']] = (float) $row['total'];
        }

        return array_values($data);
    }

}
