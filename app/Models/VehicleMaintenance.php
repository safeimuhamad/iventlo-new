<?php

class VehicleMaintenance
{
	protected $db;

	public function __construct()
	{
		$this->db = Database::connect();
	}

	public function dueVehicles()
	{
		$stmt = $this->db->query("
			SELECT *,
			(total_km - last_maintenance_km) AS km_after_service
			FROM vehicles
			WHERE maintenance_status IN ('due', 'process')
			ORDER BY total_km DESC
			");

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function findVehicle($id)
	{
		$stmt = $this->db->prepare("
			SELECT *
			FROM vehicles
			WHERE id = ?
			");
		$stmt->execute([$id]);

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function setProcess($vehicleId)
	{
		$stmt = $this->db->prepare("
			UPDATE vehicles
			SET 
			maintenance_status = 'process',
			vehicle_status = 'maintenance'
			WHERE id = ?
			");

		return $stmt->execute([$vehicleId]);
	}

	public function defaultChecklists()
	{
		return [
			'Ganti Oli Mesin',
			'Cek Filter Oli',
			'Cek Air Radiator',
			'Cek Minyak Rem',
			'Cek Ban',
			'Cek Aki',
			'Cek Lampu',
			'Cek Rem',
			'Cek Suspensi',
			'Test Drive'
		];
	}

	public function store($data, $files = [])
	{
		try {

			$this->db->beginTransaction();

			$stmt = $this->db->prepare("
				INSERT INTO vehicle_maintenances
				(
					vehicle_id,
					maintenance_date,
					maintenance_type,
					km_at_maintenance,
					mechanic_name,
					workshop_name,
					notes,
					cost
					)
				VALUES (?, ?, ?, ?, ?, ?, ?, ?)
				");

			$stmt->execute([
				$data['vehicle_id'],
				$data['maintenance_date'],
				$data['maintenance_type'],
				$data['km_at_maintenance'],
				$data['mechanic_name'] ?? null,
				$data['workshop_name'] ?? null,
				$data['notes'] ?? null,
				$data['cost'] ?? 0
			]);

			$maintenanceId = $this->db->lastInsertId();

        // save checklist
			if (!empty($data['checklist_name'])) {

				foreach ($data['checklist_name'] as $index => $checklistName) {

					$stmt = $this->db->prepare("
						INSERT INTO vehicle_maintenance_checklists
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
						$checklistName,
						$data['checklist_status'][$index] ?? 'ok',
						$data['checklist_notes'][$index] ?? null
					]);
				}
			}
		$this->uploadDocuments($maintenanceId, $files);
        // update vehicle
			$stmt = $this->db->prepare("
				UPDATE vehicles
				SET
				maintenance_status = 'normal',
				vehicle_status = 'available',
				last_maintenance_km = total_km,
				last_maintenance_date = ?
				WHERE id = ?
				");

			$stmt->execute([
				$data['maintenance_date'],
				$data['vehicle_id']
			]);

			return $this->db->commit();

		} catch (Exception $e) {

			$this->db->rollBack();

			throw $e;
		}
	}

	public function history()
	{
		$stmt = $this->db->query("
			SELECT 
			m.*,
			v.vehicle_code,
			v.vehicle_name,
			v.plate_number
			FROM vehicle_maintenances m
			JOIN vehicles v ON v.id = m.vehicle_id
			ORDER BY m.maintenance_date DESC, m.id DESC
			");

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function findMaintenance($id)
	{
		$stmt = $this->db->prepare("
			SELECT 
			m.*,
			v.vehicle_code,
			v.vehicle_name,
			v.plate_number,
			v.brand
			FROM vehicle_maintenances m
			JOIN vehicles v ON v.id = m.vehicle_id
			WHERE m.id = ?
			");
		$stmt->execute([$id]);

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function getChecklists($maintenanceId)
	{
		$stmt = $this->db->prepare("
			SELECT *
			FROM vehicle_maintenance_checklists
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
			FROM vehicle_maintenance_documents
			WHERE maintenance_id = ?
			ORDER BY id ASC
			");
		$stmt->execute([$maintenanceId]);

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function report($startDate = null, $endDate = null)
	{
		$sql = "
		SELECT 
		m.*,
		v.vehicle_code,
		v.vehicle_name,
		v.plate_number
		FROM vehicle_maintenances m
		JOIN vehicles v ON v.id = m.vehicle_id
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
		COUNT(*) AS total_service,
		SUM(cost) AS total_cost,
		AVG(cost) AS average_cost
		FROM vehicle_maintenances
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


	private function uploadDocuments($maintenanceId, $files)
{
    if (empty($files['documents']['name'][0])) {
        return;
    }

    $uploadDir = __DIR__ . '/../../public/uploads/vehicle-maintenance/';

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
            throw new Exception('Dokumen service harus berupa PDF atau gambar yang valid.');
        }

        $fileType = $validated['mime'];
        $extension = $validated['extension'];
        $fileName = 'vehicle_service_' . $maintenanceId . '_' . time() . '_' . $index . '.' . $extension;
        $filePath = 'uploads/vehicle-maintenance/' . $fileName;

        move_uploaded_file($tmpName, $uploadDir . $fileName);

        $stmt = $this->db->prepare("
            INSERT INTO vehicle_maintenance_documents
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

}
