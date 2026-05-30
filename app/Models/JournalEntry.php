<?php

class JournalEntry
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function create($data, $lines)
    {
        $totalDebit = 0;
        $totalCredit = 0;

        foreach ($lines as $line) {
            $totalDebit += (float) ($line['debit'] ?? 0);
            $totalCredit += (float) ($line['credit'] ?? 0);
        }

        if (round($totalDebit, 2) !== round($totalCredit, 2)) {
            throw new Exception('Jurnal tidak balance.');
        }

        $ownsTransaction = !$this->db->inTransaction();

        if ($ownsTransaction) {
            $this->db->beginTransaction();
        }

        try {
            $stmt = $this->db->prepare("
                INSERT INTO journal_entries
                (
                    journal_date,
                    reference_type,
                    reference_id,
                    description,
                    total_debit,
                    total_credit
                    )
                VALUES (?, ?, ?, ?, ?, ?)
                ");

            $stmt->execute([
                $data['journal_date'],
                $data['reference_type'],
                $data['reference_id'],
                $data['description'],
                $totalDebit,
                $totalCredit
            ]);

            $journalId = $this->db->lastInsertId();

            foreach ($lines as $line) {
                $stmtLine = $this->db->prepare("
                    INSERT INTO journal_entry_lines
                    (
                        journal_entry_id,
                        account_id,
                        debit,
                        credit,
                        description
                        )
                    VALUES (?, ?, ?, ?, ?)
                    ");

                $stmtLine->execute([
                    $journalId,
                    $line['account_id'],
                    $line['debit'] ?? 0,
                    $line['credit'] ?? 0,
                    $line['description'] ?? ''
                ]);
            }

            if ($ownsTransaction) {
                $this->db->commit();
            }

            return $journalId;

        } catch (Exception $e) {
            if ($ownsTransaction && $this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }

    public function deleteByReference($referenceType, $referenceId)
    {
        $stmt = $this->db->prepare("
            SELECT id
            FROM journal_entries
            WHERE reference_type = ?
            AND reference_id = ?
            ");

        $stmt->execute([$referenceType, $referenceId]);
        $journals = $stmt->fetchAll();

        foreach ($journals as $journal) {
            $this->db->prepare("
                DELETE FROM journal_entry_lines
                WHERE journal_entry_id = ?
                ")->execute([$journal['id']]);

            $this->db->prepare("
                DELETE FROM journal_entries
                WHERE id = ?
                ")->execute([$journal['id']]);
        }

        return true;
    }


    public function getAll()
    {
        $stmt = $this->db->query("
            SELECT *
            FROM journal_entries
            ORDER BY journal_date DESC, id DESC
            ");

        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM journal_entries
            WHERE id = ?
            LIMIT 1
            ");

        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function getLines($journalEntryId)
    {
        $stmt = $this->db->prepare("
            SELECT 
            jel.*,
            coa.account_code,
            coa.account_name
            FROM journal_entry_lines jel
            LEFT JOIN chart_of_accounts coa ON coa.id = jel.account_id
            WHERE jel.journal_entry_id = ?
            ORDER BY jel.id ASC
            ");

        $stmt->execute([$journalEntryId]);

        return $stmt->fetchAll();
    }


    public function getLedgerByAccount($accountId)
    {
        $stmt = $this->db->prepare("
            SELECT
            je.journal_date,
            je.reference_type,
            je.reference_id,
            je.description AS journal_description,

            jel.debit,
            jel.credit,
            jel.description AS line_description

            FROM journal_entry_lines jel

            LEFT JOIN journal_entries je
            ON je.id = jel.journal_entry_id

            WHERE jel.account_id = ?

            ORDER BY je.journal_date ASC, jel.id ASC
            ");

        $stmt->execute([$accountId]);

        return $stmt->fetchAll();
    }

}
