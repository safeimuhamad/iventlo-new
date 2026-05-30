<?php

class BankTransfer
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO bank_transfers
            (
                transfer_date,
                from_bank_account_id,
                to_bank_account_id,
                amount,
                reference_no,
                notes
                )
            VALUES (?, ?, ?, ?, ?, ?)
            ");

        $stmt->execute([
            $data['transfer_date'],
            $data['from_bank_account_id'],
            $data['to_bank_account_id'],
            $data['amount'],
            $data['reference_no'],
            $data['notes']
        ]);

        return $this->db->lastInsertId();
    }

    public function getAll()
    {
        $stmt = $this->db->query("
            SELECT 
            bt.*,
            from_bank.account_name AS from_account_name,
            from_bank.account_code AS from_account_code,
            to_bank.account_name AS to_account_name,
            to_bank.account_code AS to_account_code
            FROM bank_transfers bt
            LEFT JOIN bank_accounts from_bank 
            ON from_bank.id = bt.from_bank_account_id
            LEFT JOIN bank_accounts to_bank 
            ON to_bank.id = bt.to_bank_account_id
            ORDER BY bt.transfer_date DESC, bt.id DESC
            ");

        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT 
            bt.*,

            from_bank.account_code AS from_account_code,
            from_bank.account_name AS from_account_name,
            from_bank.bank_name AS from_bank_name,
            from_bank.account_number AS from_account_number,

            to_bank.account_code AS to_account_code,
            to_bank.account_name AS to_account_name,
            to_bank.bank_name AS to_bank_name,
            to_bank.account_number AS to_account_number

            FROM bank_transfers bt

            LEFT JOIN bank_accounts from_bank 
            ON from_bank.id = bt.from_bank_account_id

            LEFT JOIN bank_accounts to_bank 
            ON to_bank.id = bt.to_bank_account_id

            WHERE bt.id = ?
            LIMIT 1
            ");

        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("
            DELETE FROM bank_transfers
            WHERE id = ?
            ");

        return $stmt->execute([$id]);
    }

}