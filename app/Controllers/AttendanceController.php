<?php

class AttendanceController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $search = $_GET['search'] ?? '';
        $p = max(1, (int) ($_GET['p'] ?? 1));
        $limit = 10;
        $offset = ($p - 1) * $limit;

        $model = new Attendance();

        $totalRows = $model->countAll($search);
        $totalPages = ceil($totalRows / $limit);

        activity_log(
            'HRIS - Absensi',
            'view',
            'Melihat daftar absensi'
        );

        $this->view('attendances/index', [
            'title' => 'Absensi',
            'attendances' => $model->paginate($search, $limit, $offset),
            'search' => $search,
            'p' => $p,
            'currentPage' => $p,
            'limit' => $limit,
            'totalPages' => $totalPages,
            'totalRows' => $totalRows
        ]);
    }

    public function create()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $employeeModel = new Employee();

        activity_log(
            'HRIS - Absensi',
            'create_form',
            'Membuka form tambah absensi'
        );

        $this->view('attendances/create', [
            'title' => 'Tambah Absensi',
            'employees' => $employeeModel->getActive()
        ]);
    }

    public function store()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        if (empty($_POST['employee_id']) || empty($_POST['attendance_date'])) {
            echo "Karyawan dan tanggal absensi wajib diisi.";
            exit;
        }

        $model = new Attendance();

        $model->create([
            'employee_id' => $_POST['employee_id'],
            'attendance_date' => $_POST['attendance_date'],
            'check_in' => $_POST['check_in'] ?? null,
            'check_out' => $_POST['check_out'] ?? null,
            'status' => $_POST['status'] ?? 'present',
            'late_minutes' => (int) ($_POST['late_minutes'] ?? 0),
            'overtime_minutes' => (int) ($_POST['overtime_minutes'] ?? 0),
            'notes' => $_POST['notes'] ?? ''
        ]);

        activity_log(
            'HRIS - Absensi',
            'create',
            'Menambahkan data absensi tanggal: ' . $_POST['attendance_date'],
            null,
            $_POST['attendance_date'] ?? null
        );

        $this->redirect('attendances');
    }

    public function show()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('attendances');
        }

        $model = new Attendance();
        $attendance = $model->find($id);

        if (!$attendance) {
            $this->redirect('attendances');
        }

        activity_log(
            'HRIS - Absensi',
            'view',
            'Melihat detail absensi: ' . (($attendance['full_name'] ?? '-') . ' / ' . ($attendance['attendance_date'] ?? '-')),
            $id,
            $attendance['attendance_date'] ?? null
        );

        $this->view('attendances/show', [
            'title' => 'Detail Absensi',
            'attendance' => $attendance
        ]);
    }

    public function edit()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('attendances');
        }

        $model = new Attendance();
        $attendance = $model->find($id);

        if (!$attendance) {
            $this->redirect('attendances');
        }

        $employeeModel = new Employee();

        activity_log(
            'HRIS - Absensi',
            'edit_form',
            'Membuka form edit absensi: ' . (($attendance['full_name'] ?? '-') . ' / ' . ($attendance['attendance_date'] ?? '-')),
            $id,
            $attendance['attendance_date'] ?? null
        );

        $this->view('attendances/edit', [
            'title' => 'Edit Absensi',
            'attendance' => $attendance,
            'employees' => $employeeModel->getActive()
        ]);
    }

    public function update()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_POST['id'] ?? null;

        if (!$id) {
            $this->redirect('attendances');
        }

        if (empty($_POST['employee_id']) || empty($_POST['attendance_date'])) {
            echo "Karyawan dan tanggal absensi wajib diisi.";
            exit;
        }

        $model = new Attendance();

        $model->update($id, [
            'employee_id' => $_POST['employee_id'],
            'attendance_date' => $_POST['attendance_date'],
            'check_in' => $_POST['check_in'] ?? null,
            'check_out' => $_POST['check_out'] ?? null,
            'status' => $_POST['status'] ?? 'present',
            'late_minutes' => (int) ($_POST['late_minutes'] ?? 0),
            'overtime_minutes' => (int) ($_POST['overtime_minutes'] ?? 0),
            'notes' => $_POST['notes'] ?? ''
        ]);

        activity_log(
            'HRIS - Absensi',
            'update',
            'Mengubah data absensi tanggal: ' . $_POST['attendance_date'],
            $id,
            $_POST['attendance_date'] ?? null
        );

        $this->redirect('attendances');
    }

    public function delete()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('attendances');
        }

        $model = new Attendance();
        $attendance = $model->find($id);

        $model->delete($id);

        activity_log(
            'HRIS - Absensi',
            'delete',
            'Menghapus data absensi: ' . (($attendance['full_name'] ?? '-') . ' / ' . ($attendance['attendance_date'] ?? '-')),
            $id,
            $attendance['attendance_date'] ?? null
        );

        $this->redirect('attendances');
    }
}