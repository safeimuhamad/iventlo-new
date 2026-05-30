<?php

class MarketingLead
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function countAll()
    {
        $stmt = $this->db->query("
            SELECT COUNT(*) AS total
            FROM marketing_leads
        ");

        $row = $stmt->fetch();

        return (int) ($row['total'] ?? 0);
    }

    public function getPaginated($limit, $offset)
    {
        $stmt = $this->db->prepare("
            SELECT 
                ml.*,
                u.name AS assigned_name,
                creator.name AS created_by_name,
                (
                    SELECT f.next_followup_date
                    FROM marketing_lead_followups f
                    WHERE f.lead_id = ml.id
                    AND f.next_followup_date IS NOT NULL
                    ORDER BY f.next_followup_date ASC
                    LIMIT 1
                ) AS next_followup_date
            FROM marketing_leads ml
            LEFT JOIN users u ON u.id = ml.assigned_to
            LEFT JOIN users creator ON creator.id = ml.created_by
            ORDER BY ml.id DESC
            LIMIT ? OFFSET ?
        ");

        $stmt->bindValue(1, (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(2, (int) $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function generateNumber()
    {
        $prefix = 'LD-' . date('Ym') . '-';

        $stmt = $this->db->prepare("
            SELECT lead_number
            FROM marketing_leads
            WHERE lead_number LIKE ?
            ORDER BY id DESC
            LIMIT 1
        ");

        $stmt->execute([$prefix . '%']);
        $last = $stmt->fetch();

        $number = 1;

        if ($last) {
            $lastNumber = (int) substr($last['lead_number'], -4);
            $number = $lastNumber + 1;
        }

        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO marketing_leads
            (
                lead_number,
                company_name,
                pic_name,
                phone,
                email,
                address,
                source,
                service_interest,
                estimated_value,
                status,
                priority,
                assigned_to,
                notes,
                created_by
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['lead_number'],
            $data['company_name'],
            $data['pic_name'],
            $data['phone'],
            $data['email'],
            $data['address'],
            $data['source'],
            $data['service_interest'],
            $data['estimated_value'],
            $data['status'] ?? 'new',
            $data['priority'] ?? 'medium',
            $data['assigned_to'] ?? null,
            $data['notes'],
            $data['created_by'] ?? ($_SESSION['user_id'] ?? null)
        ]);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT 
                ml.*,
                u.name AS assigned_name,
                creator.name AS created_by_name
            FROM marketing_leads ml
            LEFT JOIN users u ON u.id = ml.assigned_to
            LEFT JOIN users creator ON creator.id = ml.created_by
            WHERE ml.id = ?
            LIMIT 1
        ");

        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE marketing_leads SET
                company_name = ?,
                pic_name = ?,
                phone = ?,
                email = ?,
                address = ?,
                source = ?,
                service_interest = ?,
                estimated_value = ?,
                status = ?,
                priority = ?,
                assigned_to = ?,
                notes = ?,
                updated_at = NOW()
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['company_name'],
            $data['pic_name'],
            $data['phone'],
            $data['email'],
            $data['address'],
            $data['source'],
            $data['service_interest'],
            $data['estimated_value'],
            $data['status'],
            $data['priority'],
            $data['assigned_to'] ?? null,
            $data['notes'],
            $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("
            DELETE FROM marketing_leads
            WHERE id = ?
            AND status NOT IN ('deal', 'quotation')
        ");

        return $stmt->execute([$id]);
    }

    public function getFollowUps($leadId)
{
    $stmt = $this->db->prepare("
        SELECT 
            f.*,
            u.name AS created_by_name
        FROM marketing_lead_followups f
        LEFT JOIN users u ON u.id = f.created_by
        WHERE f.lead_id = ?
        ORDER BY f.followup_date DESC, f.id DESC
    ");

    $stmt->execute([$leadId]);

    return $stmt->fetchAll();
}

    public function createFollowUp($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO marketing_lead_followups
            (
                lead_id,
                followup_date,
                followup_type,
                result,
                notes,
                next_followup_date,
                created_by
            )
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['lead_id'],
            $data['followup_date'],
            $data['followup_type'],
            $data['result'],
            $data['notes'],
            $data['next_followup_date'] ?? null,
            $data['created_by'] ?? ($_SESSION['user_id'] ?? null)
        ]);
    }

    public function updateStatus($id, $status)
    {
        $stmt = $this->db->prepare("
            UPDATE marketing_leads SET
                status = ?,
                updated_at = NOW()
            WHERE id = ?
        ");

        return $stmt->execute([
            $status,
            $id
        ]);
    }

    private function buildFilterWhere($status, $source, $keyword, &$params)
{
    $where = " WHERE 1=1 ";

    if ($status !== '') {
        $where .= " AND ml.status = ? ";
        $params[] = $status;
    }

    if ($source !== '') {
        $where .= " AND ml.source = ? ";
        $params[] = $source;
    }

    if ($keyword !== '') {
        $where .= " AND (
            ml.lead_number LIKE ?
            OR ml.company_name LIKE ?
            OR ml.pic_name LIKE ?
            OR ml.phone LIKE ?
            OR ml.email LIKE ?
            OR ml.service_interest LIKE ?
        ) ";

        $search = '%' . $keyword . '%';

        $params[] = $search;
        $params[] = $search;
        $params[] = $search;
        $params[] = $search;
        $params[] = $search;
        $params[] = $search;
    }

    return $where;
}

public function countFiltered($status = '', $source = '', $keyword = '')
{
    $params = [];

    $sql = "
        SELECT COUNT(*) AS total
        FROM marketing_leads ml
    ";

    $sql .= $this->buildFilterWhere($status, $source, $keyword, $params);

    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);

    $row = $stmt->fetch();

    return (int) ($row['total'] ?? 0);
}

    public function getFilteredPaginated($limit, $offset, $status = '', $source = '', $keyword = '')
    {
        $params = [];

        $sql = "
            SELECT 
                ml.*,
                u.name AS assigned_name,
                creator.name AS created_by_name,
                (
                    SELECT f.next_followup_date
                    FROM marketing_lead_followups f
                    WHERE f.lead_id = ml.id
                    AND f.next_followup_date IS NOT NULL
                    ORDER BY f.next_followup_date ASC
                    LIMIT 1
                ) AS next_followup_date
            FROM marketing_leads ml
            LEFT JOIN users u ON u.id = ml.assigned_to
            LEFT JOIN users creator ON creator.id = ml.created_by
        ";

        $sql .= $this->buildFilterWhere($status, $source, $keyword, $params);

        $sql .= "
            ORDER BY ml.id DESC
            LIMIT ? OFFSET ?
        ";

        $stmt = $this->db->prepare($sql);

        $index = 1;

        foreach ($params as $param) {
            $stmt->bindValue($index, $param);
            $index++;
        }

        $stmt->bindValue($index, (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue($index + 1, (int) $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function markAsConverted($leadId, $customerId)
    {
        $stmt = $this->db->prepare("
            UPDATE marketing_leads SET
                converted_customer_id = ?,
                status = 'deal',
                updated_at = NOW()
            WHERE id = ?
        ");

        return $stmt->execute([
            $customerId,
            $leadId
        ]);
    }

    public function getOpenLeads()
    {
        $stmt = $this->db->query("
            SELECT *
            FROM marketing_leads
            WHERE status NOT IN ('deal', 'lost')
            ORDER BY id DESC
        ");

        return $stmt->fetchAll();
    }

    public function searchAjax($keyword)
    {
        $keyword = trim($keyword);

        if ($keyword === '') {
            return [];
        }

        $stmt = $this->db->prepare("
            SELECT 
                id,
                lead_number,
                company_name,
                pic_name,
                phone,
                address
            FROM marketing_leads
            WHERE 
                company_name LIKE ?
                OR pic_name LIKE ?
                OR phone LIKE ?
                OR lead_number LIKE ?
            ORDER BY id DESC
            LIMIT 20
        ");

        $search = '%' . $keyword . '%';

        $stmt->execute([
            $search,
            $search,
            $search,
            $search
        ]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];

        foreach ($rows as $row) {
            $name = $row['company_name'] ?: $row['pic_name'];

            $result[] = [
                'id'      => $row['id'],
                'text'    => ($row['lead_number'] ?: '-') . ' - ' . $name,
                'name'    => $name,
                'phone'   => $row['phone'] ?? '',
                'address' => $row['address'] ?? '',
            ];
        }

        return $result;
    }

    public function existsByCompanyName($companyName)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*)
            FROM marketing_leads
            WHERE LOWER(TRIM(company_name)) = LOWER(TRIM(?))
        ");

        $stmt->execute([$companyName]);

        return (int) $stmt->fetchColumn() > 0;
    }
}