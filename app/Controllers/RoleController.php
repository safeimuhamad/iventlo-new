<?php

class RoleController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('role.view');

        $roleModel = new Role();

        $limit = 20;
        $currentPage = max(1, (int) ($_GET['p'] ?? 1));
        $offset = ($currentPage - 1) * $limit;

        $totalData = $roleModel->countAll();
        $totalPages = (int) ceil($totalData / $limit);

        $roles = $roleModel->paginate($limit, $offset);

        activity_log(
            'System - Role',
            'view',
            'Melihat daftar role'
        );

        $this->view('roles/index', [
            'title' => 'Role Management',
            'roles' => $roles,
            'limit' => $limit,
            'currentPage' => $currentPage,
            'totalData' => $totalData,
            'totalPages' => $totalPages
        ]);
    }

    public function create()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('role.create');

        activity_log(
            'System - Role',
            'create_form',
            'Membuka form tambah role'
        );

        $this->view('roles/create', [
            'title' => 'Tambah Role'
        ]);
    }

    public function store()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('role.create');

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'status' => $_POST['status'] ?? 'active'
        ];

        if ($data['name'] === '') {

            activity_log(
                'System - Role',
                'create_failed',
                'Gagal menambahkan role karena nama kosong'
            );

            $_SESSION['error'] = 'Nama role wajib diisi.';
            $this->redirect('roles-create');
        }

        $roleModel = new Role();

        $roleId = $roleModel->create($data);

        activity_log(
            'System - Role',
            'create',
            'Menambahkan role: ' . $data['name'],
            $roleId,
            $data['name']
        );

        $_SESSION['success'] = 'Role berhasil ditambahkan.';
        $this->redirect('roles');
    }

    public function edit()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('role.edit');

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error'] = 'ID role tidak valid.';
            $this->redirect('roles');
        }

        $roleModel = new Role();
        $role = $roleModel->find($id);

        if (!$role) {

            activity_log(
                'System - Role',
                'edit_failed',
                'Gagal membuka form edit role karena data tidak ditemukan',
                $id
            );

            $_SESSION['error'] = 'Role tidak ditemukan.';
            $this->redirect('roles');
        }

        activity_log(
            'System - Role',
            'edit_form',
            'Membuka form edit role: ' . ($role['name'] ?? '-'),
            $id,
            $role['name'] ?? null
        );

        $this->view('roles/edit', [
            'title' => 'Edit Role',
            'role' => $role
        ]);
    }

    public function update()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('role.edit');

        $id = $_POST['id'] ?? null;

        if (!$id) {
            $_SESSION['error'] = 'ID role tidak valid.';
            $this->redirect('roles');
        }

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'status' => $_POST['status'] ?? 'active'
        ];

        if ($data['name'] === '') {

            activity_log(
                'System - Role',
                'update_failed',
                'Gagal mengubah role karena nama kosong',
                $id
            );

            $_SESSION['error'] = 'Nama role wajib diisi.';
            $this->redirect('roles-edit', ['id' => $id]);
        }

        $roleModel = new Role();

        $oldRole = $roleModel->find($id);

        $roleModel->update($id, $data);

        activity_log(
            'System - Role',
            'update',
            'Mengubah role: ' . $data['name'],
            $id,
            $data['name'] ?? ($oldRole['name'] ?? null)
        );

        $_SESSION['success'] = 'Role berhasil diperbarui.';
        $this->redirect('roles');
    }

    public function permissions()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('role.permission');

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error'] = 'ID role tidak valid.';
            $this->redirect('roles');
        }

        $roleModel = new Role();

        $role = $roleModel->find($id);

        if (!$role) {

            activity_log(
                'System - Role',
                'permission_failed',
                'Gagal membuka hak akses role karena data tidak ditemukan',
                $id
            );

            $_SESSION['error'] = 'Role tidak ditemukan.';
            $this->redirect('roles');
        }

        $permissions = $roleModel->allPermissions();
        $selectedPermissions = $roleModel->getPermissionIdsByRole($id);

        if (strtolower((string) ($role['name'] ?? '')) === 'client') {
            $permissions = array_values(array_filter($permissions, function ($permission) {
                return ($permission['module'] ?? '') === 'client_portal';
            }));
        }

        $groupedPermissions = [];

        foreach ($permissions as $permission) {
            $groupedPermissions[$permission['module']][] = $permission;
        }

        $priorityModules = ['client_portal', 'master_event'];
        uksort($groupedPermissions, function ($left, $right) use ($priorityModules) {
            $leftPriority = array_search($left, $priorityModules, true);
            $rightPriority = array_search($right, $priorityModules, true);
            $leftPriority = $leftPriority === false ? PHP_INT_MAX : $leftPriority;
            $rightPriority = $rightPriority === false ? PHP_INT_MAX : $rightPriority;

            if ($leftPriority !== $rightPriority) {
                return $leftPriority <=> $rightPriority;
            }

            return strcasecmp($left, $right);
        });

        activity_log(
            'System - Role',
            'permission_view',
            'Melihat hak akses role: ' . ($role['name'] ?? '-'),
            $id,
            $role['name'] ?? null
        );

        $this->view('roles/permissions', [
            'title' => 'Hak Akses Role',
            'role' => $role,
            'groupedPermissions' => $groupedPermissions,
            'selectedPermissions' => $selectedPermissions
        ]);
    }

    public function updatePermissions()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('role.permission');

        $roleId = $_POST['role_id'] ?? null;
        $permissionIds = $_POST['permissions'] ?? [];

        if (!$roleId) {
            $_SESSION['error'] = 'ID role tidak valid.';
            $this->redirect('roles');
        }

        $roleModel = new Role();

        $role = $roleModel->find($roleId);

        if (strtolower((string) ($role['name'] ?? '')) === 'client') {
            $portalPermissionIds = [];

            foreach ($roleModel->allPermissions() as $permission) {
                if (($permission['module'] ?? '') === 'client_portal') {
                    $portalPermissionIds[] = (string) $permission['id'];
                }
            }

            $permissionIds = array_values(array_intersect(
                array_map('strval', (array) $permissionIds),
                $portalPermissionIds
            ));
        }

        $success = $roleModel->syncPermissions($roleId, $permissionIds);

        if ($success) {

            activity_log(
                'System - Role',
                'permission_update',
                'Mengubah hak akses role: ' . ($role['name'] ?? '-'),
                $roleId,
                $role['name'] ?? null
            );

            $_SESSION['success'] = 'Hak akses berhasil diperbarui.';
        } else {

            activity_log(
                'System - Role',
                'permission_update_failed',
                'Gagal mengubah hak akses role: ' . ($role['name'] ?? '-'),
                $roleId,
                $role['name'] ?? null
            );

            $_SESSION['error'] = 'Hak akses gagal diperbarui.';
        }

        $this->redirect('roles-permissions', ['id' => $roleId]);
    }
}
