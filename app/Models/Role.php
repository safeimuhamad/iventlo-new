<?php

class Role
{
    private $db;

    public function __construct()
    {
       $this->db = Database::connect();
    }

    public function all()
    {
        $stmt = $this->db->query("
            SELECT *
            FROM roles
            ORDER BY id ASC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll()
    {
        $stmt = $this->db->query("
            SELECT COUNT(*) AS total
            FROM roles
        ");

        $row = $stmt->fetch();

        return (int) ($row['total'] ?? 0);
    }

    public function getAll()
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM roles
            ORDER BY name ASC
        ");
        $stmt->execute();

        return $stmt->fetchAll();
    }
    public function getGroupedPermissions()
    {
        $stmt = $this->db->query("
            SELECT *
            FROM permissions
            ORDER BY module ASC, action_name ASC
        ");

        $permissions = $stmt->fetchAll();

        $grouped = [];

        foreach ($permissions as $permission) {

            $module = $permission['module'];

            $grouped[$module][] = $permission;
        }

        return $grouped;
    }

    public function paginate($limit = 10, $offset = 0)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM roles
            ORDER BY id ASC
            LIMIT ? OFFSET ?
        ");

        $stmt->bindValue(1, (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(2, (int) $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM roles
            WHERE id = ?
            LIMIT 1
        ");

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO roles (name, description, status)
            VALUES (?, ?, ?)
        ");

        return $stmt->execute([
            $data['name'],
            $data['description'],
            $data['status']
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE roles
            SET name = ?, description = ?, status = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['name'],
            $data['description'],
            $data['status'],
            $id
        ]);
    }

    public function allPermissions()
    {
        $stmt = $this->db->query("
            SELECT *
            FROM permissions
            ORDER BY module ASC, action_name ASC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPermissionIdsByRole($roleId)
    {
        $stmt = $this->db->prepare("
            SELECT permission_id
            FROM role_permissions
            WHERE role_id = ?
        ");

        $stmt->execute([$roleId]);

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function syncPermissions($roleId, $permissionIds)
    {
        $this->db->beginTransaction();

        try {
            $delete = $this->db->prepare("
                DELETE FROM role_permissions
                WHERE role_id = ?
            ");
            $delete->execute([$roleId]);

            if (!empty($permissionIds)) {
                $insert = $this->db->prepare("
                    INSERT INTO role_permissions (role_id, permission_id)
                    VALUES (?, ?)
                ");

                foreach ($permissionIds as $permissionId) {
                    $insert->execute([$roleId, $permissionId]);
                }
            }

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}