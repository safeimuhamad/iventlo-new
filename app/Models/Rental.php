<?php

class Rental
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function countAll($search = '')
    {
        $userId = $_SESSION['user_id'] ?? null;
        $role = $_SESSION['user_role'] ?? '';

        $sql = "
        SELECT COUNT(*) AS total 
        FROM rentals
        WHERE 1
        ";

        $params = [];

    /*
    |--------------------------------------------------------------------------
    | Restrict only sales
    |--------------------------------------------------------------------------
    */

    if ($role === 'sales') {
        $sql .= " AND created_by = ?";
        $params[] = $userId;
    }

    if ($search !== '') {

        $sql .= "
        AND (
        no_rental LIKE ?
        OR customer_name LIKE ?
        OR customer_phone LIKE ?
        OR lokasi LIKE ?
        OR status_rental LIKE ?
        )
        ";

        $keyword = "%{$search}%";

        $params[] = $keyword;
        $params[] = $keyword;
        $params[] = $keyword;
        $params[] = $keyword;
        $params[] = $keyword;
    }

    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);

    $row = $stmt->fetch();

    return (int) $row['total'];
}


    public function getPaginated($limit, $offset, $search = '')
    {
        $userId = $_SESSION['user_id'] ?? null;
        $role = $_SESSION['user_role'] ?? '';

        $sql = "
        SELECT *
        FROM rentals
        WHERE 1
        ";

        $params = [];

        /*
        |--------------------------------------------------------------------------
        | Restrict only sales
        |--------------------------------------------------------------------------
        */

        if ($role === 'sales') {
            $sql .= " AND created_by = ?";
            $params[] = $userId;
        }

        if ($search !== '') {

            $sql .= "
            AND (
            no_rental LIKE ?
            OR customer_name LIKE ?
            OR customer_phone LIKE ?
            OR lokasi LIKE ?
            OR status_rental LIKE ?
            )
            ";

            $keyword = "%{$search}%";

            $params[] = $keyword;
            $params[] = $keyword;
            $params[] = $keyword;
            $params[] = $keyword;
            $params[] = $keyword;
        }

        $sql .= " ORDER BY id DESC LIMIT ? OFFSET ?";

        $stmt = $this->db->prepare($sql);

        foreach ($params as $index => $param) {
            $stmt->bindValue($index + 1, $param);
        }

        $stmt->bindValue(count($params) + 1, (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(count($params) + 2, (int) $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll();
    }


    public function find($id)
    {
        $userId = $_SESSION['user_id'] ?? null;
        $role = $_SESSION['user_role'] ?? '';

        $sql = "
        SELECT *
        FROM rentals
        WHERE id = ?
        ";

        $params = [$id];

        /*
        |--------------------------------------------------------------------------
        | Restrict only sales
        |--------------------------------------------------------------------------
        */

        if ($role === 'sales') {
            $sql .= " AND created_by = ?";
            $params[] = $userId;
        }

        $sql .= " LIMIT 1";

        $stmt = $this->db->prepare($sql);

        $stmt->execute($params);

        return $stmt->fetch();
    }

    public function getItems($rentalId)
    {
        $stmt = $this->db->prepare("
            SELECT 
            ri.*,
            u.kode_unit,
            u.nama_unit,
            u.kategori,
            u.brand,
            p.partner_name
            FROM rental_items ri
            LEFT JOIN units u ON ri.unit_id = u.id
            LEFT JOIN partners p ON ri.partner_id = p.id
            WHERE ri.rental_id = ?
            ORDER BY ri.id DESC
            ");
        $stmt->execute([$rentalId]);
        return $stmt->fetchAll();
    }

    public function addItem($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO rental_items 
            (
                rental_id,
                source_type,
                unit_id,
                partner_id,
                partner_unit_name,
                partner_unit_brand,
                partner_unit_category,
                partner_cost,
                status_item
                )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'booked')
            ");

        return $stmt->execute([
            $data['rental_id'],
            $data['source_type'],
            $data['unit_id'],
            $data['partner_id'],
            $data['partner_unit_name'],
            $data['partner_unit_brand'],
            $data['partner_unit_category'],
            $data['partner_cost']
        ]);
    }

    public function updateStatus($id, $status)
    {
        $stmt = $this->db->prepare("
            UPDATE rentals 
            SET status_rental = ? 
            WHERE id = ?
            ");

        return $stmt->execute([$status, $id]);
    }

    public function getDeliverySchedule($date)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM rentals
            WHERE tanggal_rental = ?
            AND status_rental IN ('draft','scheduled')
            ORDER BY jam_kirim ASC, id DESC
            ");
        $stmt->execute([$date]);
        return $stmt->fetchAll();
    }

    public function getPickupSchedule($date)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM rentals
            WHERE tanggal_selesai = ?
            AND status_rental IN ('scheduled','on_rent')
            ORDER BY jam_bongkar ASC, id DESC
            ");
        $stmt->execute([$date]);
        return $stmt->fetchAll();
    }

    public function markAsOut($rentalId)
    {
        $this->db->beginTransaction();

        $stmt = $this->db->prepare("
            UPDATE rental_items
            SET status_item = 'out',
            tanggal_keluar = NOW()
            WHERE rental_id = ?
            AND status_item = 'booked'
            ");
        $stmt->execute([$rentalId]);

        $stmt = $this->db->prepare("
            UPDATE rentals
            SET status_rental = 'on_rent'
            WHERE id = ?
            ");
        $stmt->execute([$rentalId]);

        return $this->db->commit();
    }

    public function markAsReturned($rentalId)
    {
        try {
            $this->db->beginTransaction();

            // 1. Ambil unit yang benar-benar sedang keluar
            $stmt = $this->db->prepare("
                SELECT 
                ri.unit_id,
                u.total_rental_count,
                u.last_maintenance_count,
                u.maintenance_interval
                FROM rental_items ri
                JOIN units u ON u.id = ri.unit_id
                WHERE ri.rental_id = ?
                AND ri.status_item = 'out'
                ");
            $stmt->execute([$rentalId]);
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // 2. Update counter pemakaian unit
            foreach ($items as $item) {
                $totalRentalCount = (int) $item['total_rental_count'] + 1;
                $lastMaintenanceCount = (int) $item['last_maintenance_count'];
                $maintenanceInterval = (int) $item['maintenance_interval'];
                $usageAfterMaintenance = $totalRentalCount - $lastMaintenanceCount;
                $maintenanceStatus = 'normal';
                if ($maintenanceInterval > 0 && $usageAfterMaintenance >= $maintenanceInterval) {
                    $maintenanceStatus = 'due';
                }
                $stmtUpdateUnit = $this->db->prepare("
                    UPDATE units
                    SET 
                    total_rental_count = ?,
                    maintenance_status = ?,
                    status_unit = CASE 
                    WHEN ? = 'due' THEN 'maintenance'
                    ELSE 'available'
                    END
                    WHERE id = ?
                    ");

                $stmtUpdateUnit->execute([
                    $totalRentalCount,
                    $maintenanceStatus,
                    $maintenanceStatus,
                    $item['unit_id']
                ]);
            }

            // 3. Tandai item rental sudah kembali
            $stmt = $this->db->prepare("
                UPDATE rental_items
                SET 
                status_item = 'returned',
                tanggal_kembali = NOW()
                WHERE rental_id = ?
                AND status_item = 'out'
                ");
            $stmt->execute([$rentalId]);

            // 4. Tandai rental selesai
            $stmt = $this->db->prepare("
                UPDATE rentals
                SET status_rental = 'completed'
                WHERE id = ?
                ");
            $stmt->execute([$rentalId]);

            return $this->db->commit();

        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function countByStatus($status)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM rentals WHERE status_rental = ?");
        $stmt->execute([$status]);
        $row = $stmt->fetch();

        return (int) $row['total'];
    }

    public function countTodayDelivery()
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) AS total 
            FROM rentals 
            WHERE tanggal_rental = CURDATE()
            AND status_rental IN ('draft','scheduled')
            ");
        $stmt->execute();
        $row = $stmt->fetch();

        return (int) $row['total'];
    }

    public function countTodayPickup()
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) AS total 
            FROM rentals 
            WHERE tanggal_selesai = CURDATE()
            AND status_rental = 'on_rent'
            ");
        $stmt->execute();
        $row = $stmt->fetch();

        return (int) $row['total'];
    }

    public function getCalendarEvents()
    {
        $stmt = $this->db->query("
            SELECT 
            id,
            no_rental,
            customer_name,
            tanggal_rental,
            tanggal_selesai,
            status_rental
            FROM rentals
            WHERE status_rental != 'cancelled'
            ORDER BY tanggal_rental ASC
            ");

        return $stmt->fetchAll();
    }

    public function getTodayDeliveries()
    {
        $stmt = $this->db->prepare("
            SELECT
            do.*,

            r.customer_name,
            r.lokasi,
            r.no_rental,

            v.vehicle_code,
            v.vehicle_name,
            v.plate_number,

            (
                SELECT GROUP_CONCAT(
                    t.name
                    ORDER BY t.name ASC
                    SEPARATOR ', '
                    )
                FROM rental_technicians rt

                JOIN technicians t
                ON t.id = rt.technician_id

                WHERE rt.rental_id = do.rental_id

                AND rt.task_type = CASE
                WHEN do.sj_type = 'pasang'
                THEN 'kirim_pasang'

                WHEN do.sj_type = 'bongkar'
                THEN 'bongkar'
                END

                AND rt.status != 'cancelled'

                ) AS technician_names

            FROM delivery_orders do

            JOIN rentals r
            ON r.id = do.rental_id

            LEFT JOIN vehicles v
            ON v.id = do.vehicle_id

            WHERE do.tanggal_kirim = CURDATE()

            ORDER BY do.jam_kirim ASC

            LIMIT 10
            ");

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTodayPickups()
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM rentals
            WHERE tanggal_selesai = CURDATE()
            AND status_rental = 'on_rent'
            ORDER BY jam_bongkar ASC
            LIMIT 10
            ");

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getEndingTomorrow()
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM rentals
            WHERE tanggal_selesai = DATE_ADD(CURDATE(), INTERVAL 1 DAY)
            AND status_rental = 'on_rent'
            LIMIT 10
            ");

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO rentals
            (
                no_rental,
                customer_name,
                customer_phone,
                lokasi,
                tanggal_rental,
                tanggal_selesai,
                status_rental,
                catatan,
                quotation_id,
                created_by
                )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

        $stmt->execute([
            $data['no_rental'],
            $data['customer_name'],
            $data['customer_phone'],
            $data['lokasi'],
            $data['tanggal_rental'],
            $data['tanggal_selesai'],
            $data['status_rental'],
            $data['catatan'],
            $data['quotation_id'],
            $data['created_by'] ?? ($_SESSION['user_id'] ?? null)
        ]);

        return $this->db->lastInsertId();
    }

    public function all()
    {
        $stmt = $this->db->query("
            SELECT *
            FROM rentals
            ORDER BY id DESC
            ");

        return $stmt->fetchAll();
    }

    public function getWithoutDeliveryOrder()
    {
        $stmt = $this->db->query("
            SELECT r.*
            FROM rentals r
            WHERE r.status_rental IN ('scheduled', 'on_rent')

            AND EXISTS (
                SELECT 1
                FROM rental_technicians rt
                WHERE rt.rental_id = r.id
                AND rt.status != 'cancelled'
                AND rt.task_type = CASE
                WHEN r.status_rental = 'scheduled' THEN 'kirim_pasang'
                WHEN r.status_rental = 'on_rent' THEN 'bongkar'
                END
                )

            AND NOT EXISTS (
                SELECT 1
                FROM delivery_orders do
                WHERE do.rental_id = r.id
                AND do.sj_type = CASE 
                WHEN r.status_rental = 'scheduled' THEN 'pasang'
                WHEN r.status_rental = 'on_rent' THEN 'bongkar'
                END
                )

            ORDER BY r.id DESC
            ");

        return $stmt->fetchAll();
    }

    public function getUpcomingReturns($limit = 5)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM rentals
            WHERE status_rental = 'on_rent'
            AND tanggal_selesai <= DATE_ADD(CURDATE(), INTERVAL 3 DAY)
            ORDER BY tanggal_selesai ASC
            LIMIT {$limit}
            ");

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getTodayTechnicians()
    {
        $stmt = $this->db->prepare("
            SELECT
            rt.*,
            t.name AS technician_name,
            r.customer_name,
            r.lokasi,
            r.tanggal_rental,
            r.tanggal_selesai

            FROM rental_technicians rt

            JOIN technicians t
            ON t.id = rt.technician_id

            JOIN rentals r
            ON r.id = rt.rental_id

            WHERE rt.status != 'cancelled'

            AND (
                r.tanggal_rental = CURDATE()
                OR r.tanggal_selesai = CURDATE()
                )

            ORDER BY t.name ASC
            ");

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function upcomingProjects($limit = 8)
    {
        $limit = (int) $limit;

        $stmt = $this->db->query("
            SELECT
            id,
            no_rental,
            customer_name,
            lokasi,
            tanggal_rental,
            tanggal_selesai,
            status_rental
            FROM rentals
            WHERE tanggal_rental >= CURDATE()
            ORDER BY tanggal_rental ASC
            LIMIT {$limit}
            ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function monthlyRentalTrend($year = null)
    {
        $year = $year ?: date('Y');

        $stmt = $this->db->prepare("
            SELECT 
                MONTH(tanggal_rental) AS month_number,
                COUNT(*) AS total_rental
            FROM rentals
            WHERE YEAR(tanggal_rental) = ?
            GROUP BY MONTH(tanggal_rental)
            ORDER BY MONTH(tanggal_rental) ASC
        ");

        $stmt->execute([$year]);

        $data = array_fill(0, 12, 0);

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $index = ((int) $row['month_number']) - 1;

            if ($index >= 0 && $index < 12) {
                $data[$index] = (int) $row['total_rental'];
            }
        }

        return $data;
    }

}