<?php

class Unit
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getPaginated($limit, $offset)
    {
        $stmt = $this->db->prepare("SELECT * FROM units ORDER BY id DESC LIMIT ? OFFSET ?");
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(2, (int)$offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function countAll()
    {
        $stmt = $this->db->query("SELECT COUNT(*) AS total FROM units");
        $row = $stmt->fetch();

        return (int)$row['total'];
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM units WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE units SET
            kode_unit = ?,
            nama_unit = ?,
            tipe_unit = ?,
            kategori = ?,
            brand = ?,
            kapasitas = ?,
            status_unit = ?,
            lokasi_sekarang = ?
            WHERE id = ?
            ");

        return $stmt->execute([
            $data['kode_unit'],
            $data['nama_unit'],
            $data['tipe_unit'],
            $data['kategori'],
            $data['brand'],
            $data['kapasitas'],
            $data['status_unit'],
            $data['lokasi_sekarang'],
            $id
        ]);
    }
    
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM units WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getAvailable()
    {
        $stmt = $this->db->query("
            SELECT * 
            FROM units
            WHERE status_unit = 'available'
            ORDER BY nama_unit ASC
            ");

        return $stmt->fetchAll();
    }

    public function getAvailableByDate1($tanggalRental, $tanggalSelesai)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM units
            WHERE status_unit NOT IN ('maintenance', 'broken')
            AND id NOT IN (
                SELECT ri.unit_id
                FROM rental_items ri
                JOIN rentals r ON r.id = ri.rental_id
                WHERE ri.source_type = 'internal'
                AND ri.unit_id IS NOT NULL
                AND r.status_rental NOT IN ('completed', 'cancelled')
                AND r.tanggal_rental <= ?
                AND r.tanggal_selesai >= ?
                )
            ORDER BY nama_unit ASC
            ");

        $stmt->execute([
            $tanggalSelesai,
            $tanggalRental
        ]);

        return $stmt->fetchAll();
    }

    public function updateStatus($id, $status)
    {
        $stmt = $this->db->prepare("
            UPDATE units 
            SET status_unit = ? 
            WHERE id = ?
            ");

        return $stmt->execute([$status, $id]);
    }


    public function countByStatus($status)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM units WHERE status_unit = ?");
        $stmt->execute([$status]);
        $row = $stmt->fetch();

        return (int) $row['total'];
    }

    public function countAllActive()
    {
        $stmt = $this->db->query("
            SELECT COUNT(*) AS total 
            FROM units 
            WHERE status_unit != 'inactive'
            ");
        $row = $stmt->fetch();

        return (int) $row['total'];
    }

    public function getAvailableByDate($tanggalRental, $tanggalSelesai)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM units
            WHERE status_unit NOT IN ('maintenance', 'broken', 'inactive')
            AND id NOT IN (
                SELECT ri.unit_id
                FROM rental_items ri
                JOIN rentals r ON r.id = ri.rental_id
                WHERE ri.source_type = 'internal'
                AND ri.unit_id IS NOT NULL
                AND r.status_rental NOT IN ('completed', 'cancelled')
                AND r.tanggal_rental <= ?
                AND r.tanggal_selesai >= ?
                )
            ORDER BY kategori ASC, nama_unit ASC
            ");

        $stmt->execute([$tanggalSelesai, $tanggalRental]);
        return $stmt->fetchAll();
    }

    public function getBookedByDate($tanggalRental, $tanggalSelesai)
    {
        $stmt = $this->db->prepare("
            SELECT 
            u.*,
            r.no_rental,
            r.customer_name,
            r.tanggal_rental,
            r.tanggal_selesai,
            ri.status_item
            FROM rental_items ri
            JOIN units u ON u.id = ri.unit_id
            JOIN rentals r ON r.id = ri.rental_id
            WHERE ri.source_type = 'internal'
            AND ri.unit_id IS NOT NULL
            AND r.status_rental NOT IN ('completed', 'cancelled')
            AND r.tanggal_rental <= ?
            AND r.tanggal_selesai >= ?
            ORDER BY r.tanggal_rental ASC
            ");

        $stmt->execute([$tanggalSelesai, $tanggalRental]);
        return $stmt->fetchAll();
    }

    public function getAvailableGrouped($tanggalRental, $tanggalSelesai)
    {
        $stmt = $this->db->prepare("
            SELECT 
            nama_unit,
            COUNT(*) as total

            FROM units

            WHERE status_unit NOT IN ('maintenance', 'broken', 'inactive')

            AND id NOT IN (
                SELECT ri.unit_id
                FROM rental_items ri
                JOIN rentals r ON r.id = ri.rental_id

                WHERE ri.source_type = 'internal'
                AND ri.unit_id IS NOT NULL
                AND r.status_rental NOT IN ('completed', 'cancelled')

                AND r.tanggal_rental <= ?
                AND r.tanggal_selesai >= ?
                )

            GROUP BY nama_unit
            ORDER BY total DESC, nama_unit ASC
            ");

        $stmt->execute([$tanggalSelesai, $tanggalRental]);

        return $stmt->fetchAll();
    }

    public function getBookedGrouped($tanggalRental, $tanggalSelesai)
    {
        $stmt = $this->db->prepare("
            SELECT 
            u.nama_unit,
            COUNT(*) as total

            FROM rental_items ri

            JOIN units u ON u.id = ri.unit_id
            JOIN rentals r ON r.id = ri.rental_id

            WHERE ri.source_type = 'internal'
            AND ri.unit_id IS NOT NULL

            AND r.status_rental NOT IN ('completed', 'cancelled')

            AND r.tanggal_rental <= ?
            AND r.tanggal_selesai >= ?

            GROUP BY u.nama_unit
            ORDER BY total DESC, u.nama_unit ASC
            ");

        $stmt->execute([$tanggalSelesai, $tanggalRental]);

        return $stmt->fetchAll();
    }

    public function getAvailabilityBoard()
{
    $stmt = $this->db->prepare("
        SELECT
            tipe_unit,

            COUNT(*) AS total_unit,

            SUM(
                CASE
                    WHEN status_unit = 'available'
                    THEN 1
                    ELSE 0
                END
            ) AS available_count,

            SUM(
                CASE
                    WHEN status_unit = 'rented'
                    OR status_unit = 'on_rent'
                    THEN 1
                    ELSE 0
                END
            ) AS rented_count,

            SUM(
                CASE
                    WHEN status_unit = 'maintenance'
                    THEN 1
                    ELSE 0
                END
            ) AS maintenance_count,

            SUM(
                CASE
                    WHEN status_unit = 'broken'
                    THEN 1
                    ELSE 0
                END
            ) AS broken_count

        FROM units

        -- WHERE is_active = 1

        GROUP BY tipe_unit

        ORDER BY tipe_unit ASC
    ");

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}