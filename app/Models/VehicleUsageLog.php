<?php

class VehicleUsageLog
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function all()
    {
        $stmt = $this->db->query("
            SELECT 
                l.*,
                v.vehicle_code,
                v.vehicle_name,
                v.plate_number
            FROM vehicle_usage_logs l
            JOIN vehicles v ON v.id = l.vehicle_id
            ORDER BY l.usage_date DESC, l.id DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function vehicles()
    {
        $stmt = $this->db->query("
            SELECT *
            FROM vehicles
            ORDER BY vehicle_name ASC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT
                l.*,
                v.vehicle_code,
                v.vehicle_name,
                v.plate_number
            FROM vehicle_usage_logs l
            JOIN vehicles v ON v.id = l.vehicle_id
            WHERE l.id = ?
            LIMIT 1
        ");

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        try {
            $this->db->beginTransaction();

            $vehicleId = $data['vehicle_id'];
            $kmStart = (int) $data['km_start'];
            $kmEnd = (int) $data['km_end'];
            $distanceKm = max(0, $kmEnd - $kmStart);

            $stmt = $this->db->prepare("
                INSERT INTO vehicle_usage_logs
                (
                    vehicle_id,
                    usage_date,
                    activity_type,
                    destination,
                    km_start,
                    km_end,
                    distance_km,
                    driver_name,
                    notes
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $vehicleId,
                $data['usage_date'] ?? date('Y-m-d'),
                $data['activity_type'] ?? 'delivery',
                $data['destination'] ?? null,
                $kmStart,
                $kmEnd,
                $distanceKm,
                $data['driver_name'] ?? null,
                $data['notes'] ?? null
            ]);

            $stmt = $this->db->prepare("
                UPDATE vehicles
                SET 
                    total_km = total_km + ?,
                    vehicle_status = 'available'
                WHERE id = ?
            ");
            $stmt->execute([$distanceKm, $vehicleId]);

            $this->checkMaintenanceDue($vehicleId);

            return $this->db->commit();

        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    private function checkMaintenanceDue($vehicleId)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM vehicles
            WHERE id = ?
        ");
        $stmt->execute([$vehicleId]);
        $vehicle = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$vehicle) {
            return false;
        }

        $kmAfterMaintenance = 
            (int) $vehicle['total_km'] - (int) $vehicle['last_maintenance_km'];

        $isKmDue = $kmAfterMaintenance >= (int) $vehicle['maintenance_interval_km'];

        $isDateDue = false;

        if (!empty($vehicle['last_maintenance_date'])) {
            $intervalMonth = (int) ($vehicle['maintenance_interval_month'] ?? 3);
            $nextMaintenanceDate = date(
                'Y-m-d', 
                strtotime($vehicle['last_maintenance_date'] . " +{$intervalMonth} months")
            );

            $isDateDue = $nextMaintenanceDate <= date('Y-m-d');
        }

        if ($isKmDue || $isDateDue) {
            $stmt = $this->db->prepare("
                UPDATE vehicles
                SET 
                    maintenance_status = 'due',
                    vehicle_status = 'maintenance'
                WHERE id = ?
            ");
            $stmt->execute([$vehicleId]);
        }

        return true;
    }
}
