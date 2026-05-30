<?php

class RecruitmentApplicantController extends Controller
{
    private function authorize($permissionKey)
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission($permissionKey);
    }

    public function index()
    {
        $this->authorize('recruitment_applicant.view');

        $model = new RecruitmentApplicant();

        $search = trim($_GET['search'] ?? '');

        $limit = 10;
        $currentPage = max(1, (int) ($_GET['p'] ?? 1));
        $offset = ($currentPage - 1) * $limit;

        $totalData = $model->countAll($search);
        $totalPages = (int) ceil($totalData / $limit);

        activity_log(
            'HRIS - Recruitment',
            'view',
            'Melihat daftar kandidat recruitment'
        );

        $this->view('recruitment-applicants/index', [
            'title' => 'Recruitment Applicants',
            'items' => $model->getPaginated($limit, $offset, $search),
            'search' => $search,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalData' => $totalData,
            'limit' => $limit
        ]);
    }

    public function create()
    {
        $this->authorize('recruitment_applicant.create');

        $departmentModel = new Department();
        $positionModel = new Position();

        activity_log(
            'HRIS - Recruitment',
            'create_form',
            'Membuka form tambah kandidat'
        );

        $this->view('recruitment-applicants/create', [
            'title' => 'Tambah Kandidat',
            'departments' => $departmentModel->all(),
            'positions' => $positionModel->all()
        ]);
    }

    public function store()
    {
        $this->authorize('recruitment_applicant.create');

        $model = new RecruitmentApplicant();

        $fullName = trim($_POST['full_name'] ?? '');

        if ($fullName === '') {

            activity_log(
                'HRIS - Recruitment',
                'create_failed',
                'Gagal membuat kandidat karena nama kosong'
            );

            $_SESSION['error'] = 'Nama kandidat wajib diisi.';
            $this->redirect('recruitment-applicants-create');
        }

        $applicantId = $model->create([
            'applicant_number' => $model->generateNumber(),
            'full_name' => $fullName,
            'phone' => trim($_POST['phone'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
            'department_id' => $_POST['department_id'] ?? null,
            'position_id' => $_POST['position_id'] ?? null,
            'source' => trim($_POST['source'] ?? ''),
            'expected_salary' => (float) ($_POST['expected_salary'] ?? 0),
            'cv_file' => null,
            'portfolio_file' => null,
            'google_drive_url' => trim($_POST['google_drive_url'] ?? ''),
            'status' => $_POST['status'] ?? 'new',
            'notes' => trim($_POST['notes'] ?? ''),
            'interview_date' => !empty($_POST['interview_date'])
                ? $_POST['interview_date']
                : null,
            'created_by' => $_SESSION['user_id'] ?? null
        ]);

        activity_log(
            'HRIS - Recruitment',
            'create',
            'Menambahkan kandidat: ' . $fullName,
            $applicantId,
            $fullName
        );

        $_SESSION['success'] = 'Kandidat berhasil dibuat.';
        $this->redirect('recruitment-applicants');
    }

    public function show()
    {
        $this->authorize('recruitment_applicant.view');

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('recruitment-applicants');
        }

        $model = new RecruitmentApplicant();
        $item = $model->find($id);

        if (!$item) {

            activity_log(
                'HRIS - Recruitment',
                'view_failed',
                'Gagal membuka detail kandidat karena data tidak ditemukan',
                $id
            );

            $_SESSION['error'] = 'Kandidat tidak ditemukan.';
            $this->redirect('recruitment-applicants');
        }

        activity_log(
            'HRIS - Recruitment',
            'view',
            'Melihat detail kandidat: ' . ($item['full_name'] ?? '-'),
            $id,
            $item['applicant_number'] ?? null
        );

        $this->view('recruitment-applicants/show', [
            'title' => 'Detail Kandidat',
            'item' => $item
        ]);
    }

    public function edit()
    {
        $this->authorize('recruitment_applicant.edit');

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('recruitment-applicants');
        }

        $model = new RecruitmentApplicant();
        $item = $model->find($id);

        if (!$item) {

            activity_log(
                'HRIS - Recruitment',
                'edit_failed',
                'Gagal membuka form edit kandidat karena data tidak ditemukan',
                $id
            );

            $_SESSION['error'] = 'Kandidat tidak ditemukan.';
            $this->redirect('recruitment-applicants');
        }

        $departmentModel = new Department();
        $positionModel = new Position();

        activity_log(
            'HRIS - Recruitment',
            'edit_form',
            'Membuka form edit kandidat: ' . ($item['full_name'] ?? '-'),
            $id,
            $item['applicant_number'] ?? null
        );

        $this->view('recruitment-applicants/edit', [
            'title' => 'Edit Kandidat',
            'item' => $item,
            'departments' => $departmentModel->all(),
            'positions' => $positionModel->all()
        ]);
    }

    public function update()
    {
        $this->authorize('recruitment_applicant.edit');

        $id = $_POST['id'] ?? null;

        if (!$id) {
            $this->redirect('recruitment-applicants');
        }

        $model = new RecruitmentApplicant();

        $oldItem = $model->find($id);

        if (!$oldItem) {

            activity_log(
                'HRIS - Recruitment',
                'update_failed',
                'Gagal update kandidat karena data tidak ditemukan',
                $id
            );

            $_SESSION['error'] = 'Kandidat tidak ditemukan.';
            $this->redirect('recruitment-applicants');
        }

        $model->update($id, [
            'full_name' => trim($_POST['full_name'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
            'department_id' => $_POST['department_id'] ?? null,
            'position_id' => $_POST['position_id'] ?? null,
            'source' => trim($_POST['source'] ?? ''),
            'expected_salary' => (float) ($_POST['expected_salary'] ?? 0),
            'cv_file' => null,
            'portfolio_file' => null,
            'google_drive_url' => trim($_POST['google_drive_url'] ?? ''),
            'status' => $_POST['status'] ?? 'new',
            'notes' => trim($_POST['notes'] ?? ''),
            'interview_date' => !empty($_POST['interview_date'])
                ? $_POST['interview_date']
                : null
        ]);

        activity_log(
            'HRIS - Recruitment',
            'update',
            'Mengubah kandidat: ' . ($_POST['full_name'] ?? '-'),
            $id,
            $oldItem['applicant_number'] ?? null
        );

        $_SESSION['success'] = 'Kandidat berhasil diperbarui.';
        $this->redirect('recruitment-applicants-show', ['id' => $id]);
    }

    public function delete()
    {
        $this->authorize('recruitment_applicant.delete');

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('recruitment-applicants');
        }

        $model = new RecruitmentApplicant();
        $item = $model->find($id);

        $model->delete($id);

        activity_log(
            'HRIS - Recruitment',
            'delete',
            'Menghapus kandidat: ' . ($item['full_name'] ?? '-'),
            $id,
            $item['applicant_number'] ?? null
        );

        $_SESSION['success'] = 'Kandidat berhasil dihapus.';
        $this->redirect('recruitment-applicants');
    }

    public function convertToEmployee()
    {
        $this->authorize('recruitment_applicant.convert');

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('recruitment-applicants');
        }

        $applicantModel = new RecruitmentApplicant();
        $employeeModel = new Employee();

        $applicant = $applicantModel->find($id);

        if (!$applicant) {

            activity_log(
                'HRIS - Recruitment',
                'convert_failed',
                'Gagal convert kandidat karena data tidak ditemukan',
                $id
            );

            $_SESSION['error'] = 'Kandidat tidak ditemukan.';
            $this->redirect('recruitment-applicants');
        }

        if (!empty($applicant['converted_employee_id'])) {

            activity_log(
                'HRIS - Recruitment',
                'convert_failed',
                'Kandidat sudah pernah dikonversi: ' . ($applicant['full_name'] ?? '-'),
                $id,
                $applicant['applicant_number'] ?? null
            );

            $_SESSION['error'] = 'Kandidat sudah dikonversi menjadi karyawan.';
            $this->redirect('recruitment-applicants-show', ['id' => $id]);
        }

        if (($applicant['status'] ?? '') !== 'hired') {

            activity_log(
                'HRIS - Recruitment',
                'convert_failed',
                'Gagal convert kandidat karena status bukan hired: ' . ($applicant['full_name'] ?? '-'),
                $id,
                $applicant['applicant_number'] ?? null
            );

            $_SESSION['error'] = 'Kandidat hanya bisa dikonversi jika status Hired.';
            $this->redirect('recruitment-applicants-show', ['id' => $id]);
        }

        $employeeId = $employeeModel->createFromApplicant($applicant);

        $applicantModel->markAsConverted($id, $employeeId);

        activity_log(
            'HRIS - Recruitment',
            'convert',
            'Mengkonversi kandidat menjadi karyawan: ' . ($applicant['full_name'] ?? '-'),
            $id,
            $applicant['applicant_number'] ?? null
        );

        $_SESSION['success'] = 'Kandidat berhasil dikonversi menjadi karyawan.';

        $this->redirect('employees-edit', ['id' => $employeeId]);
    }
}