<?php

class Product
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getAll($search = '')
    {
        $sql = "
            SELECT *
            FROM quotation_products
            WHERE 1=1
        ";

        $params = [];

        if ($search !== '') {
            $sql .= "
                AND (
                    name LIKE ?
                    OR category LIKE ?
                    OR item_type LIKE ?
                    OR status LIKE ?
                )
            ";

            $params = [
                "%{$search}%",
                "%{$search}%",
                "%{$search}%",
                "%{$search}%"
            ];
        }

        $sql .= " ORDER BY id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActive()
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM quotation_products
            WHERE status = 'active'
            ORDER BY name ASC
        ");

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM quotation_products
            WHERE id = ?
            LIMIT 1
        ");

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO quotation_products
            (
                name,
                category,
                item_type,
                unit_name,
                default_period_type,
                daily_price,
                weekly_price,
                monthly_price,
                unit_price,
                meter_price,
                package_price,
                description,
                status,
                created_at
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");

        return $stmt->execute([
            $data['name'],
            $data['category'],
            $data['item_type'],
            $data['unit_name'],
            $data['default_period_type'],
            $data['daily_price'],
            $data['weekly_price'],
            $data['monthly_price'],
            $data['unit_price'],
            $data['meter_price'],
            $data['package_price'],
            $data['description'],
            $data['status']
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE quotation_products SET
                name = ?,
                category = ?,
                item_type = ?,
                unit_name = ?,
                default_period_type = ?,
                daily_price = ?,
                weekly_price = ?,
                monthly_price = ?,
                unit_price = ?,
                meter_price = ?,
                package_price = ?,
                description = ?,
                status = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['name'],
            $data['category'],
            $data['item_type'],
            $data['unit_name'],
            $data['default_period_type'],
            $data['daily_price'],
            $data['weekly_price'],
            $data['monthly_price'],
            $data['unit_price'],
            $data['meter_price'],
            $data['package_price'],
            $data['description'],
            $data['status'],
            $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("
            UPDATE quotation_products
            SET status = 'inactive'
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }

    public function countAll($search = '')
{
    $sql = "
        SELECT COUNT(*)
        FROM quotation_products
        WHERE 1=1
    ";

    $params = [];

    if ($search !== '') {

        $sql .= "
            AND (
                name LIKE ?
                OR category LIKE ?
                OR item_type LIKE ?
                OR status LIKE ?
            )
        ";

        $params = [
            "%{$search}%",
            "%{$search}%",
            "%{$search}%",
            "%{$search}%"
        ];
    }

    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);

    return (int) $stmt->fetchColumn();
}

public function getPaginated($limit, $offset, $search = '')
{
    $sql = "
        SELECT *
        FROM quotation_products
        WHERE 1=1
    ";

    $params = [];

    if ($search !== '') {

        $sql .= "
            AND (
                name LIKE ?
                OR category LIKE ?
                OR item_type LIKE ?
                OR status LIKE ?
            )
        ";

        $params = [
            "%{$search}%",
            "%{$search}%",
            "%{$search}%",
            "%{$search}%"
        ];
    }

    $sql .= "
        ORDER BY id DESC
        LIMIT ? OFFSET ?
    ";

    $params[] = (int) $limit;
    $params[] = (int) $offset;

    $stmt = $this->db->prepare($sql);

    foreach ($params as $key => $value) {

        $stmt->bindValue(
            $key + 1,
            $value,
            is_int($value)
                ? PDO::PARAM_INT
                : PDO::PARAM_STR
        );
    }

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}