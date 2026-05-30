<?php

class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function findByUsername($username)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM users
            WHERE email = ?
            AND status = 'active'
            LIMIT 1
        ");

        $stmt->execute([$username]);

        return $stmt->fetch();
    }

    public function updateLastLogin($id)
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET last_login = NOW() 
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }

    public function countAll()
    {
        $stmt = $this->db->query("
            SELECT COUNT(*) AS total 
            FROM users
        ");

        $row = $stmt->fetch();

        return (int) ($row['total'] ?? 0);
    }

    public function getAll()
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM users
            ORDER BY name ASC
        ");
        $stmt->execute();

        return $stmt->fetchAll();
    }
    public function getPaginated($limit, $offset)
    {
        $stmt = $this->db->prepare("
            SELECT 
                u.*,
                r.name AS role_name,
                e.full_name AS employee_name,
                e.employee_code
            FROM users u
            LEFT JOIN roles r ON r.id = u.role_id
            LEFT JOIN employees e ON e.id = u.employee_id
            ORDER BY u.id DESC
            LIMIT ? OFFSET ?
        ");

        $stmt->bindValue(1, (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(2, (int) $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getPermissionsByRoleId($roleId)
    {
        $stmt = $this->db->prepare("
            SELECT p.permission_key
            FROM role_permissions rp
            INNER JOIN permissions p ON p.id = rp.permission_id
            WHERE rp.role_id = ?
        ");

        $stmt->execute([$roleId]);

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO users 
            (
                name,
                username,
                email,
                password,
                activation_token,
                activation_expires_at,
                role_id,
                employee_id,
                data_scope,
                status
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['name'],
            $data['username'],
            $data['email'],
            $data['password'], // sudah hash dari controller
            $data['activation_token'] ?? null,
            $data['activation_expires_at'] ?? null,
            $data['role_id'],
            $data['employee_id'] ?? null,
            $data['data_scope'] ?? 'own',
            $data['status'] ?? 'pending'
        ]);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT 
                u.*,
                r.name AS role_name,
                e.full_name AS employee_name,
                e.employee_code
            FROM users u
            LEFT JOIN roles r ON r.id = u.role_id
            LEFT JOIN employees e ON e.id = u.employee_id
            WHERE u.id = ?
            LIMIT 1
        ");

        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("
            SELECT
                u.*,
                r.name AS role_name
            FROM users u
            LEFT JOIN roles r ON r.id = u.role_id
            WHERE u.email = ?
            LIMIT 1
        ");

        $stmt->execute([$email]);

        return $stmt->fetch();
    }

    public function memberRoleId()
    {
        $stmt = $this->db->query("SELECT id FROM roles WHERE LOWER(name) = 'member' AND status = 'active' LIMIT 1");
        $role = $stmt->fetch();

        return (int) ($role['id'] ?? 0);
    }

    public function createMember($data)
    {
        $roleId = $this->memberRoleId();

        if (!$roleId || $this->findByEmail($data['email'])) {
            return false;
        }

        return $this->create([
            'name' => $data['name'],
            'username' => $data['email'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role_id' => $roleId,
            'data_scope' => 'own',
            'status' => 'active'
        ]);
    }

    public function requestMemberVerification($email, $token)
    {
        $roleId = $this->memberRoleId();
        $existing = $this->findByEmail($email);

        if (!$roleId) {
            return false;
        }

        if ($existing) {
            if ((int) ($existing['role_id'] ?? 0) !== $roleId || ($existing['status'] ?? '') !== 'pending') {
                return false;
            }

            $stmt = $this->db->prepare("
                UPDATE users SET activation_token = ?, activation_expires_at = ?
                WHERE id = ? AND status = 'pending'
            ");
            $stmt->execute([$token, date('Y-m-d H:i:s', strtotime('+24 hours')), (int) $existing['id']]);

            return $existing;
        }

        $created = $this->create([
            'name' => $email,
            'username' => $email,
            'email' => $email,
            'password' => password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT),
            'activation_token' => $token,
            'activation_expires_at' => date('Y-m-d H:i:s', strtotime('+24 hours')),
            'role_id' => $roleId,
            'data_scope' => 'own',
            'status' => 'pending'
        ]);

        return $created ? $this->findByEmail($email) : false;
    }

    public function findMemberByActivationToken($token)
    {
        $stmt = $this->db->prepare("
            SELECT u.*, r.name AS role_name
            FROM users u
            INNER JOIN roles r ON r.id = u.role_id
            WHERE u.activation_token = ?
            AND LOWER(r.name) = 'member'
            AND u.status = 'pending'
            AND u.activation_expires_at >= NOW()
            LIMIT 1
        ");
        $stmt->execute([$token]);

        return $stmt->fetch();
    }

    public function activateMember($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE users SET
                name = ?,
                birth_date = ?,
                gender = ?,
                password = ?,
                status = 'active',
                activation_token = NULL,
                activation_expires_at = NULL,
                activated_at = NOW()
            WHERE id = ? AND status = 'pending'
        ");

        return $stmt->execute([
            $data['name'],
            $data['birth_date'],
            $data['gender'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            (int) $id
        ]);
    }

    public function findActiveMemberByResetToken($token)
    {
        $stmt = $this->db->prepare("
            SELECT u.*, r.name AS role_name
            FROM users u
            INNER JOIN roles r ON r.id = u.role_id
            WHERE u.reset_token = ?
            AND LOWER(r.name) = 'member'
            AND u.status = 'active'
            AND u.reset_expires_at >= NOW()
            LIMIT 1
        ");
        $stmt->execute([$token]);

        return $stmt->fetch();
    }

    public function findActivePortalByResetToken($token)
    {
        $stmt = $this->db->prepare("
            SELECT u.*, r.name AS role_name
            FROM users u
            INNER JOIN roles r ON r.id = u.role_id
            WHERE u.reset_token = ?
            AND LOWER(r.name) IN ('member', 'client')
            AND u.status = 'active'
            AND u.reset_expires_at >= NOW()
            LIMIT 1
        ");
        $stmt->execute([$token]);

        return $stmt->fetch();
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE users SET
                name = ?,
                email = ?,
                role_id = ?,
                employee_id = ?,
                data_scope = ?,
                status = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['name'],
            $data['email'],
            $data['role_id'],
            $data['employee_id'] ?? null,
            $data['data_scope'] ?? 'own',
            $data['status'],
            $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("
            DELETE FROM users 
            WHERE id = ?
            AND role_id != 1
        ");

        return $stmt->execute([$id]);
    }

    public function findByActivationToken($token)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM users
            WHERE activation_token = ?
            AND status = 'pending'
            AND activation_expires_at >= NOW()
            LIMIT 1
        ");

        $stmt->execute([$token]);

        return $stmt->fetch();
    }

    public function activateAccount($id, $password)
    {
        $stmt = $this->db->prepare("
            UPDATE users SET
                password = ?,
                status = 'active',
                activation_token = NULL,
                activation_expires_at = NULL,
                activated_at = NOW()
            WHERE id = ?
            AND status = 'pending'
        ");

        return $stmt->execute([
            password_hash($password, PASSWORD_DEFAULT),
            $id
        ]);
    }

    public function saveResetToken($id, $token)
    {
        $stmt = $this->db->prepare("
            UPDATE users SET
                reset_token = ?,
                reset_expires_at = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $token,
            date('Y-m-d H:i:s', strtotime('+1 hour')),
            $id
        ]);
    }

    public function findByResetToken($token)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM users
            WHERE reset_token = ?
            AND reset_expires_at >= NOW()
            AND status = 'active'
            LIMIT 1
        ");

        $stmt->execute([$token]);

        return $stmt->fetch();
    }

    public function updatePasswordByResetToken($id, $password)
    {
        $stmt = $this->db->prepare("
            UPDATE users SET
                password = ?,
                reset_token = NULL,
                reset_expires_at = NULL
            WHERE id = ?
        ");

        return $stmt->execute([
            password_hash($password, PASSWORD_DEFAULT),
            $id
        ]);
    }

    public function findByEmployeeId($employeeId)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM users
            WHERE employee_id = ?
            LIMIT 1
        ");

        $stmt->execute([$employeeId]);

        return $stmt->fetch();
    }
}
