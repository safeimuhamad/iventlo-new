<?php

class UnitMaintenance
{

    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getDueUnits()
    {
        $stmt = $this->db->query("
            SELECT *
            FROM units
            WHERE maintenance_status IN ('due', 'process')
            ORDER BY total_rental_count DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUnit($unitId)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM units
            WHERE id = ?
        ");
        $stmt->execute([$unitId]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function defaultChecklists()
    {
        return [
            'Cek kondisi body unit',
            'Cek filter udara',
            'Cek evaporator',
            'Cek kondensor',
            'Cek fan indoor',
            'Cek fan outdoor',
            'Cek tekanan freon',
            'Cek arus listrik',
            'Cek kabel power',
            'Cek socket / plug',
            'Cek remote / kontrol',
            'Cek drain / pembuangan air',
            'Cek suara mesin',
            'Cek performa dingin',
            'Cleaning unit',
            'Test running'
        ];
    }

    public function storeMaintenance($unitId, $data, $files)
    {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("
                SELECT total_rental_count
                FROM units
                WHERE id = ?
            ");
            $stmt->execute([$unitId]);
            $unit = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$unit) {
                throw new Exception('Unit tidak ditemukan');
            }

            $stmt = $this->db->prepare("
                INSERT INTO unit_maintenances
                (
                    unit_id,
                    maintenance_date,
                    maintenance_type,
                    rental_count_at_maintenance,
                    technician_name,
                    notes,
                    cost
                )
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $unitId,
                $data['maintenance_date'] ?? date('Y-m-d'),
                $data['maintenance_type'] ?? 'ringan',
                $unit['total_rental_count'],
                $data['technician_name'] ?? null,
                $data['notes'] ?? null,
                $data['cost'] ?? 0
            ]);

            $maintenanceId = $this->db->lastInsertId();

            if (!empty($data['checklist_name'])) {
                foreach ($data['checklist_name'] as $index => $name) {
                    $status = $data['checklist_status'][$index] ?? 'ok';
                    $note = $data['checklist_notes'][$index] ?? null;

                    $stmt = $this->db->prepare("
                        INSERT INTO unit_maintenance_checklists
                        (
                            maintenance_id,
                            checklist_name,
                            checklist_status,
                            notes
                        )
                        VALUES (?, ?, ?, ?)
                    ");

                    $stmt->execute([
                        $maintenanceId,
                        $name,
                        $status,
                        $note
                    ]);
                }
            }

            $this->uploadDocuments($maintenanceId, $files);

            $stmt = $this->db->prepare("
                UPDATE units
                SET 
                    last_maintenance_count = total_rental_count,
                    maintenance_status = 'normal',
                    status_unit = 'available',
                    last_maintenance_date = ?
                WHERE id = ?
            ");

            $stmt->execute([
                $data['maintenance_date'] ?? date('Y-m-d'),
                $unitId
            ]);

            return $this->db->commit();

        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    private function uploadDocuments($maintenanceId, $files)
    {
        if (empty($files['documents']['name'][0])) {
            return;
        }

        $uploadDir = __DIR__ . '/../../public/uploads/maintenance/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        foreach ($files['documents']['name'] as $index => $originalName) {
            if (empty($originalName)) {
                continue;
            }

            $tmpName = $files['documents']['tmp_name'][$index];
            $validated = validatedDocumentUpload([
                'name' => $originalName,
                'tmp_name' => $tmpName,
                'error' => $files['documents']['error'][$index] ?? UPLOAD_ERR_OK
            ]);

            if ($validated === null) {
                throw new Exception('Dokumen maintenance harus berupa PDF atau gambar yang valid.');
            }

            $fileType = $validated['mime'];
            $extension = $validated['extension'];
            $fileName = 'maintenance_' . $maintenanceId . '_' . time() . '_' . $index . '.' . $extension;
            $filePath = 'uploads/maintenance/' . $fileName;

            move_uploaded_file($tmpName, $uploadDir . $fileName);

            $stmt = $this->db->prepare("
                INSERT INTO unit_maintenance_documents
                (
                    maintenance_id,
                    file_name,
                    file_path,
                    file_type
                )
                VALUES (?, ?, ?, ?)
            ");

            $stmt->execute([
                $maintenanceId,
                $originalName,
                $filePath,
                $fileType
            ]);
        }
    }

    public function history()
    {
        $stmt = $this->db->query("
            SELECT 
                m.*,
                u.kode_unit,
                u.nama_unit
            FROM unit_maintenances m
            JOIN units u ON u.id = m.unit_id
            ORDER BY m.maintenance_date DESC, m.id DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findMaintenance($id)
    {
        $stmt = $this->db->prepare("
            SELECT 
                m.*,
                u.kode_unit,
                u.nama_unit
            FROM unit_maintenances m
            JOIN units u ON u.id = m.unit_id
            WHERE m.id = ?
        ");
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getChecklists($maintenanceId)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM unit_maintenance_checklists
            WHERE maintenance_id = ?
            ORDER BY id ASC
        ");
        $stmt->execute([$maintenanceId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDocuments($maintenanceId)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM unit_maintenance_documents
            WHERE maintenance_id = ?
            ORDER BY id ASC
        ");
        $stmt->execute([$maintenanceId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

public function setProcess($unitId)
{
    $stmt = $this->db->prepare("
        UPDATE units
        SET 
            maintenance_status = 'process',
            status_unit = 'maintenance'
        WHERE id = ?
    ");

    return $stmt->execute([$unitId]);
}

public function report($startDate = null, $endDate = null)
{
    $sql = "
        SELECT 
            m.*,
            u.kode_unit,
            u.nama_unit
        FROM unit_maintenances m
        JOIN units u ON u.id = m.unit_id
        WHERE 1=1
    ";

    $params = [];

    if ($startDate) {
        $sql .= " AND m.maintenance_date >= ?";
        $params[] = $startDate;
    }

    if ($endDate) {
        $sql .= " AND m.maintenance_date <= ?";
        $params[] = $endDate;
    }

    $sql .= " ORDER BY m.maintenance_date DESC";

    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function reportSummary($startDate = null, $endDate = null)
{
    $sql = "
        SELECT 
            COUNT(*) AS total_maintenance,
            SUM(cost) AS total_cost,
            AVG(cost) AS average_cost
        FROM unit_maintenances
        WHERE 1=1
    ";

    $params = [];

    if ($startDate) {
        $sql .= " AND maintenance_date >= ?";
        $params[] = $startDate;
    }

    if ($endDate) {
        $sql .= " AND maintenance_date <= ?";
        $params[] = $endDate;
    }

    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

}
