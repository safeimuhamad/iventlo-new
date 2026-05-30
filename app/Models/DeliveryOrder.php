<?php

class DeliveryOrder
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function countAll($search = '')
    {
        $sql = "
        SELECT COUNT(*) AS total
        FROM delivery_orders do
        JOIN rentals r ON r.id = do.rental_id
        LEFT JOIN vehicles v ON v.id = do.vehicle_id
        WHERE 1
        ";

        $params = [];

        if ($search !== '') {
            $sql .= "
            AND (
            do.no_surat_jalan LIKE ?
            OR do.sj_type LIKE ?
            OR do.status_sj LIKE ?

            OR r.no_rental LIKE ?
            OR r.customer_name LIKE ?
            OR r.lokasi LIKE ?

            OR do.driver_name LIKE ?

            OR v.vehicle_code LIKE ?
            OR v.vehicle_name LIKE ?
            OR v.plate_number LIKE ?
            )
            ";

            $keyword = "%{$search}%";
            $params = array_fill(0, 10, $keyword);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO delivery_orders (
                rental_id,
                no_surat_jalan,
                sj_type,
                tanggal_kirim,
                jam_kirim,
                vehicle_id,
                driver_name,
                km_start,
                catatan,
                status_sj
                ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
                )
                ");

        return $stmt->execute([
            $data['rental_id'],
            $data['no_surat_jalan'],
            $data['sj_type'],
            $data['tanggal_kirim'],
            $data['jam_kirim'],
            !empty($data['vehicle_id']) ? $data['vehicle_id'] : null,
            $data['driver_name'] ?? null,
            !empty($data['km_start']) ? (int) $data['km_start'] : 0,
            $data['catatan'] ?? null,
            $data['status_sj'] ?? 'draft'
        ]);
    }

    public function generateNumber()
    {
        return 'SJ-' . date('mdHis') . rand(10,99);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT 
            do.*,

            r.no_rental,
            r.customer_name,
            r.customer_phone,
            r.lokasi,
            r.tanggal_rental,
            r.tanggal_selesai,

            v.vehicle_code,
            v.vehicle_name,
            v.plate_number,

            (
                SELECT GROUP_CONCAT( CONCAT(
                    UPPER(LEFT(t.name,1)),
                    LOWER(SUBSTRING(t.name,2))
                    )ORDER BY t.name ASC SEPARATOR ', ')
                FROM rental_technicians rt
                JOIN technicians t ON t.id = rt.technician_id

                WHERE rt.rental_id = do.rental_id

                AND rt.task_type = CASE
                WHEN do.sj_type = 'pasang' THEN 'kirim_pasang'
                WHEN do.sj_type = 'bongkar' THEN 'bongkar'
                END

                AND rt.status != 'cancelled'
                ) AS technician_names

            FROM delivery_orders do

            JOIN rentals r ON r.id = do.rental_id

            LEFT JOIN vehicles v ON v.id = do.vehicle_id

            WHERE do.id = ?

            LIMIT 1
            ");

        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function getItems($rentalId)
    {
        $stmt = $this->db->prepare("
            SELECT 
            CASE 
            WHEN ri.source_type = 'internal' THEN u.nama_unit
            ELSE ri.partner_unit_name
            END AS unit_name,

            CASE 
            WHEN ri.source_type = 'internal' THEN u.brand
            ELSE ri.partner_unit_brand
            END AS brand,

            CASE 
            WHEN ri.source_type = 'internal' THEN u.kategori
            ELSE ri.partner_unit_category
            END AS kategori,

            COUNT(*) AS jumlah

            FROM rental_items ri
            LEFT JOIN units u ON u.id = ri.unit_id
            WHERE ri.rental_id = ?

            GROUP BY unit_name, brand, kategori
            ORDER BY unit_name ASC
            ");

        $stmt->execute([$rentalId]);
        return $stmt->fetchAll();
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM delivery_orders WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE delivery_orders SET
            tanggal_kirim = ?,
            jam_kirim = ?,
            vehicle_id = ?,
            catatan = ?,
            status_sj = ?
            WHERE id = ?
            ");

        return $stmt->execute([
            $data['tanggal_kirim'],
            $data['jam_kirim'],
            $data['vehicle_id'],
            $data['catatan'],
            $data['status_sj'],
            $id
        ]);
    }


    public function paginate($search = '', $limit = 10, $offset = 0)
    {
        $sql = "
        SELECT 
        do.*,
        r.no_rental,
        r.customer_name,
        r.lokasi,

        v.vehicle_code,
        v.vehicle_name,
        v.plate_number,

        (
            SELECT GROUP_CONCAT( CONCAT(
                UPPER(LEFT(t.name,1)),
                LOWER(SUBSTRING(t.name,2))
                ) ORDER BY t.name ASC SEPARATOR ', ')
            FROM rental_technicians rt
            JOIN technicians t ON t.id = rt.technician_id
            WHERE rt.rental_id = do.rental_id
            AND rt.task_type = CASE
            WHEN do.sj_type = 'pasang' THEN 'kirim_pasang'
            WHEN do.sj_type = 'bongkar' THEN 'bongkar'
            END
            AND rt.status != 'cancelled'
            ) AS technician_names

        FROM delivery_orders do
        JOIN rentals r ON r.id = do.rental_id
        LEFT JOIN vehicles v ON v.id = do.vehicle_id
        WHERE 1
        ";

        $params = [];

        if ($search !== '') {
            $sql .= "
            AND (
            do.no_surat_jalan LIKE ?
            OR do.sj_type LIKE ?
            OR do.status_sj LIKE ?

            OR r.no_rental LIKE ?
            OR r.customer_name LIKE ?
            OR r.lokasi LIKE ?

            OR do.driver_name LIKE ?

            OR v.vehicle_code LIKE ?
            OR v.vehicle_name LIKE ?
            OR v.plate_number LIKE ?
            )
            ";

            $keyword = "%{$search}%";
            $params = array_fill(0, 10, $keyword);
        }

        $limit = (int) $limit;
        $offset = (int) $offset;

        $sql .= " ORDER BY do.id DESC LIMIT {$limit} OFFSET {$offset}";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }


}