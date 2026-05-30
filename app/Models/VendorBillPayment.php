<?php

class VendorBillPayment
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO vendor_bill_payments
            (
                vendor_bill_id,
                bank_account_id,
                payment_date,
                payment_amount,
                payment_method,
                reference_no,
                notes
            )
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $data['vendor_bill_id'],
            $data['bank_account_id'],
            $data['payment_date'],
            $data['payment_amount'],
            $data['payment_method'],
            $data['reference_no'],
            $data['notes']
        ]);

        return $this->db->lastInsertId();
    }

    public function getTotalPaid($billId)
    {
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(payment_amount), 0) AS total_paid
            FROM vendor_bill_payments
            WHERE vendor_bill_id = ?
        ");

        $stmt->execute([$billId]);

        return (float)($stmt->fetch()['total_paid'] ?? 0);
    }


    public function getByBill($billId)
{
    $stmt = $this->db->prepare("
        SELECT 
            vbp.*,
            ba.account_code,
            ba.account_name,
            ba.bank_name,
            ba.account_number
        FROM vendor_bill_payments vbp
        LEFT JOIN bank_accounts ba ON ba.id = vbp.bank_account_id
        WHERE vbp.vendor_bill_id = ?
        ORDER BY vbp.payment_date ASC, vbp.id ASC
    ");

    $stmt->execute([$billId]);

    return $stmt->fetchAll();
}
public function hasPayment($billId)
{
    $stmt = $this->db->prepare("
        SELECT COUNT(*) AS total
        FROM vendor_bill_payments
        WHERE vendor_bill_id = ?
    ");

    $stmt->execute([$billId]);

    return ((int)$stmt->fetch()['total']) > 0;
}

}
