<?php

class CustomerController extends Controller
{
    private function authorize()
    {
        if (!can_access(['super_admin', 'sales'])) {
            $this->redirect('dashboard');
        }
    }

    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $this->authorize();

        $model = new Customer();

        $limit = 10;
        $currentPage = isset($_GET['p']) ? (int) $_GET['p'] : 1;
        $currentPage = max($currentPage, 1);

        $totalData = $model->countAll();
        $totalPages = ceil($totalData / $limit);
        $offset = ($currentPage - 1) * $limit;

        activity_log(
            'Customer',
            'view',
            'Melihat daftar customer'
        );

        $this->view('customers/index', [
            'title' => 'Master Customer',
            'customers' => $model->getPaginated($limit, $offset),
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalData' => $totalData,
            'limit' => $limit
        ]);
    }

    public function show()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('customers');
        }

        $model = new Customer();
        $customer = $model->find($id);

        if (!$customer) {
            $this->redirect('customers');
        }

        $transactionCheck = $model->hasTransactions($id);

        activity_log(
            'Customer',
            'view',
            'Melihat detail customer: ' . ($customer['company_name'] ?? '-'),
            $id,
            $customer['company_name'] ?? null
        );

        $this->view('customers/show', [
            'title' => 'Detail Customer',
            'customer' => $customer,
            'transactionCheck' => $transactionCheck
        ]);
    }

    public function create()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        activity_log(
            'Customer',
            'create_form',
            'Membuka form tambah customer'
        );

        $this->view('customers/create', [
            'title' => 'Tambah Customer'
        ]);
    }

    public function store()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
            exit;
        }

        $companyName = trim($_POST['company_name'] ?? '');

        if ($companyName === '') {
            $_SESSION['error'] = 'Nama perusahaan / customer wajib diisi.';
            $this->redirect('customers-create');
            exit;
        }

        $model = new Customer();

        if ($model->companyExists($companyName)) {

            activity_log(
                'Customer',
                'create_failed',
                'Gagal menambahkan customer karena nama sudah terdaftar: ' . $companyName
            );

            $_SESSION['error'] = 'Nama perusahaan / customer sudah terdaftar.';
            $this->redirect('customers-create');
            exit;
        }

        $customerId = $model->create([
            'company_name' => $companyName,
            'pic_name' => $_POST['pic_name'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'email' => $_POST['email'] ?? '',
            'address' => $_POST['address'] ?? '',
            'npwp' => $_POST['npwp'] ?? '',
            'status' => $_POST['status'] ?? 'active',
            'created_by' => $_SESSION['user_id'],
        ]);

        activity_log(
            'Customer',
            'create',
            'Menambahkan customer: ' . $companyName,
            $customerId,
            $companyName
        );

        $_SESSION['success'] = 'Customer berhasil ditambahkan.';
        $this->redirect('customers');
        exit;
    }

    public function edit()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('customers');
        }

        $model = new Customer();
        $customer = $model->find($id);

        if (!$customer) {
            $this->redirect('customers');
        }

        activity_log(
            'Customer',
            'edit_form',
            'Membuka form edit customer: ' . ($customer['company_name'] ?? '-'),
            $id,
            $customer['company_name'] ?? null
        );

        $this->view('customers/edit', [
            'title' => 'Edit Customer',
            'customer' => $customer
        ]);
    }

    public function update()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_POST['id'] ?? null;

        if (!$id) {
            $this->redirect('customers');
        }

        $model = new Customer();

        $exists = $model->companyExists(
            $_POST['company_name'],
            $id
        );

        if ($exists) {

            activity_log(
                'Customer',
                'update_failed',
                'Gagal update customer karena nama sudah digunakan: ' . ($_POST['company_name'] ?? '-'),
                $id,
                $_POST['company_name'] ?? null
            );

            $_SESSION['error'] = 'Nama perusahaan / customer sudah digunakan customer lain.';

            $this->redirect('customers-edit', ['id' => $id]);
            exit;
        }

        $model->update($id, [
            'company_name' => $_POST['company_name'] ?? '',
            'pic_name' => $_POST['pic_name'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'email' => $_POST['email'] ?? '',
            'address' => $_POST['address'] ?? '',
            'npwp' => $_POST['npwp'] ?? '',
            'status' => $_POST['status'] ?? 'active',
        ]);

        activity_log(
            'Customer',
            'update',
            'Mengubah customer: ' . ($_POST['company_name'] ?? '-'),
            $id,
            $_POST['company_name'] ?? null
        );

        $this->redirect('customers');
    }

    public function delete()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('customers');
        }

        $model = new Customer();
        $customer = $model->find($id);

        if (!$customer) {
            $this->redirect('customers');
        }

        $check = $model->hasTransactions($id);

        if ($check['has_transaction']) {

            $_SESSION['error'] = 'Customer tidak bisa dihapus karena sudah memiliki transaksi penawaran atau invoice.';

            activity_log(
                'Customer',
                'delete_failed',
                'Gagal menghapus customer karena sudah memiliki transaksi: ' . ($customer['company_name'] ?? '-'),
                $id,
                $customer['company_name'] ?? null
            );

            $this->redirect('customers-show', ['id' => $id]);
        }

        $model->delete($id);

        activity_log(
            'Customer',
            'delete',
            'Menghapus customer: ' . ($customer['company_name'] ?? '-'),
            $id,
            $customer['company_name'] ?? null
        );

        $_SESSION['success'] = 'Customer berhasil dihapus.';
        $this->redirect('customers');
    }

    public function storeAjax()
    {
        error_reporting(0);

        if (empty($_SESSION['user_id'])) {

            echo json_encode([
                'success' => false,
                'message' => 'Unauthorized'
            ]);

            exit;
        }

        header('Content-Type: application/json');

        try {

            $model = new Customer();

            $companyName = trim($_POST['company_name'] ?? '');

            if ($companyName === '') {
                throw new Exception('Nama customer wajib diisi');
            }

            $customerId = $model->create([
                'company_name' => $companyName,
                'pic_name' => '',
                'phone' => $_POST['phone'] ?? '',
                'email' => '',
                'address' => $_POST['address'] ?? '',
                'npwp' => '',
                'created_by' => $_SESSION['user_id'],
                'status' => 'active'
            ]);

            activity_log(
                'Customer',
                'create_ajax',
                'Menambahkan customer via AJAX: ' . $companyName,
                $customerId,
                $companyName
            );

            echo json_encode([
                'success' => true,
                'customer' => [
                    'id' => $customerId,
                    'company_name' => $companyName,
                    'phone' => $_POST['phone'] ?? '',
                    'address' => $_POST['address'] ?? ''
                ]
            ]);

        } catch (Throwable $e) {

            activity_log(
                'Customer',
                'create_ajax_failed',
                'Gagal menambahkan customer via AJAX: ' . $e->getMessage()
            );

            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }

        exit;
    }

    public function searchAjax()
    {
        header('Content-Type: application/json');

        $keyword = trim($_GET['q'] ?? '');

        $model = new Customer();
        $customers = $model->searchAjax($keyword);

        activity_log(
            'Customer',
            'search',
            'Melakukan pencarian customer AJAX: ' . $keyword
        );

        $results = [];

        foreach ($customers as $customer) {

            $results[] = [
                'id' => $customer['id'],
                'text' => $customer['company_name'],
                'name' => $customer['company_name'],
                'phone' => $customer['phone'],
                'address' => $customer['address']
            ];
        }

        echo json_encode($results);
        exit;
    }
}
