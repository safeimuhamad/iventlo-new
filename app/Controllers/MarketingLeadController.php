<?php

class MarketingLeadController extends Controller
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
        $this->authorize('marketing_lead.view');

        $model = new MarketingLead();

        $limit = 10;
        $currentPage = max(1, (int) ($_GET['p'] ?? 1));
        $offset = ($currentPage - 1) * $limit;

        $status = $_GET['status'] ?? '';
        $source = $_GET['source'] ?? '';
        $keyword = trim($_GET['keyword'] ?? '');

        $totalData = $model->countFiltered($status, $source, $keyword);
        $totalPages = (int) ceil($totalData / $limit);

        activity_log(
            'Marketing - Lead',
            'view',
            'Melihat daftar marketing lead'
        );

        $this->view('marketing-leads/index', [
            'title' => 'Marketing Leads',
            'items' => $model->getFilteredPaginated(
                $limit,
                $offset,
                $status,
                $source,
                $keyword
            ),
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalData' => $totalData,
            'limit' => $limit,
            'status' => $status,
            'source' => $source,
            'keyword' => $keyword
        ]);
    }

    public function create()
    {
        $this->authorize('marketing_lead.create');

        $userModel = new User();

        activity_log(
            'Marketing - Lead',
            'create_form',
            'Membuka form tambah lead'
        );

        $this->view('marketing-leads/create', [
            'title' => 'Tambah Lead',
            'users' => $userModel->getPaginated(100, 0)
        ]);
    }

    public function store()
    {
        $this->authorize('marketing_lead.create');

        $model = new MarketingLead();

        $companyName = trim($_POST['company_name'] ?? '');
        $picName = trim($_POST['pic_name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');

        if ($companyName === '') {

            activity_log(
                'Marketing - Lead',
                'create_failed',
                'Gagal membuat lead karena nama company kosong'
            );

            $_SESSION['error'] = 'Nama company wajib diisi.';
            $this->redirect('marketing-leads-create');
        }

        if ($phone === '') {

            activity_log(
                'Marketing - Lead',
                'create_failed',
                'Gagal membuat lead karena nomor HP kosong'
            );

            $_SESSION['error'] = 'No. HP wajib diisi.';
            $this->redirect('marketing-leads-create');
        }

        if ($model->existsByCompanyName($companyName)) {

            activity_log(
                'Marketing - Lead',
                'create_failed',
                'Gagal membuat lead karena company sudah terdaftar: ' . $companyName
            );

            $_SESSION['error'] = 'Company lead sudah terdaftar. Data lead tidak dapat dibuat dua kali.';
            $this->redirect('marketing-leads-create');
        }

        $leadNumber = $model->generateNumber();

        $leadId = $model->create([
            'lead_number' => $leadNumber,
            'company_name' => $companyName,
            'pic_name' => $picName,
            'phone' => $phone,
            'email' => trim($_POST['email'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
            'source' => trim($_POST['source'] ?? ''),
            'service_interest' => trim($_POST['service_interest'] ?? ''),
            'estimated_value' => (float) ($_POST['estimated_value'] ?? 0),
            'status' => $_POST['status'] ?? 'new',
            'priority' => $_POST['priority'] ?? 'medium',
            'assigned_to' => $_POST['assigned_to'] ?? null,
            'notes' => trim($_POST['notes'] ?? ''),
            'created_by' => $_SESSION['user_id'] ?? null
        ]);

        activity_log(
            'Marketing - Lead',
            'create',
            'Menambahkan lead baru: ' . $companyName,
            $leadId,
            $leadNumber
        );

        $_SESSION['success'] = 'Lead berhasil dibuat.';
        $this->redirect('marketing-leads');
    }

    public function show()
    {
        $this->authorize('marketing_lead.view');

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('marketing-leads');
        }

        $model = new MarketingLead();
        $item = $model->find($id);

        if (!$item) {

            activity_log(
                'Marketing - Lead',
                'view_failed',
                'Gagal membuka detail lead karena data tidak ditemukan',
                $id
            );

            $_SESSION['error'] = 'Lead tidak ditemukan.';
            $this->redirect('marketing-leads');
        }

        activity_log(
            'Marketing - Lead',
            'view',
            'Melihat detail lead: ' . ($item['company_name'] ?? '-'),
            $id,
            $item['lead_number'] ?? null
        );

        $this->view('marketing-leads/show', [
            'title' => 'Detail Lead',
            'item' => $item,
            'followups' => $model->getFollowUps($id)
        ]);
    }

    public function edit()
    {
        $this->authorize('marketing_lead.edit');

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('marketing-leads');
        }

        $model = new MarketingLead();
        $item = $model->find($id);

        if (!$item) {

            activity_log(
                'Marketing - Lead',
                'edit_failed',
                'Gagal membuka form edit lead karena data tidak ditemukan',
                $id
            );

            $_SESSION['error'] = 'Lead tidak ditemukan.';
            $this->redirect('marketing-leads');
        }

        $userModel = new User();

        activity_log(
            'Marketing - Lead',
            'edit_form',
            'Membuka form edit lead: ' . ($item['company_name'] ?? '-'),
            $id,
            $item['lead_number'] ?? null
        );

        $this->view('marketing-leads/edit', [
            'title' => 'Edit Lead',
            'item' => $item,
            'users' => $userModel->getPaginated(100, 0)
        ]);
    }

    public function update()
    {
        $this->authorize('marketing_lead.edit');

        $id = $_POST['id'] ?? null;

        if (!$id) {
            $this->redirect('marketing-leads');
        }

        $model = new MarketingLead();

        $oldItem = $model->find($id);

        if (!$oldItem) {

            activity_log(
                'Marketing - Lead',
                'update_failed',
                'Gagal update lead karena data tidak ditemukan',
                $id
            );

            $_SESSION['error'] = 'Lead tidak ditemukan.';
            $this->redirect('marketing-leads');
        }

        $model->update($id, [
            'company_name' => trim($_POST['company_name'] ?? ''),
            'pic_name' => trim($_POST['pic_name'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
            'source' => trim($_POST['source'] ?? ''),
            'service_interest' => trim($_POST['service_interest'] ?? ''),
            'estimated_value' => (float) ($_POST['estimated_value'] ?? 0),
            'status' => $_POST['status'] ?? 'new',
            'priority' => $_POST['priority'] ?? 'medium',
            'assigned_to' => $_POST['assigned_to'] ?? null,
            'notes' => trim($_POST['notes'] ?? '')
        ]);

        activity_log(
            'Marketing - Lead',
            'update',
            'Mengubah data lead: ' . ($_POST['company_name'] ?? '-'),
            $id,
            $oldItem['lead_number'] ?? null
        );

        $_SESSION['success'] = 'Lead berhasil diperbarui.';
        $this->redirect('marketing-leads-show', ['id' => $id]);
    }

    public function delete()
    {
        $this->authorize('marketing_lead.delete');

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('marketing-leads');
        }

        $model = new MarketingLead();

        $item = $model->find($id);

        $model->delete($id);

        activity_log(
            'Marketing - Lead',
            'delete',
            'Menghapus lead: ' . ($item['company_name'] ?? '-'),
            $id,
            $item['lead_number'] ?? null
        );

        $_SESSION['success'] = 'Lead berhasil dihapus.';
        $this->redirect('marketing-leads');
    }

    public function storeFollowUp()
    {
        $this->authorize('marketing_lead.follow_up');

        $id = $_POST['lead_id'] ?? null;

        if (!$id) {
            $this->redirect('marketing-leads');
        }

        $model = new MarketingLead();

        $item = $model->find($id);

        if (!$item) {

            activity_log(
                'Marketing - Lead',
                'followup_failed',
                'Gagal menambahkan follow up karena lead tidak ditemukan',
                $id
            );

            $_SESSION['error'] = 'Lead tidak ditemukan.';
            $this->redirect('marketing-leads');
        }

        $followupId = $model->createFollowUp([
            'lead_id' => $id,
            'followup_date' => $_POST['followup_date'] ?? date('Y-m-d'),
            'followup_type' => $_POST['followup_type'] ?? 'whatsapp',
            'result' => $_POST['result'] ?? 'pending',
            'notes' => trim($_POST['notes'] ?? ''),
            'next_followup_date' => $_POST['next_followup_date'] ?? null,
            'created_by' => $_SESSION['user_id'] ?? null
        ]);

        $result = $_POST['result'] ?? 'pending';

        $statusMap = [
            'pending' => 'follow_up',
            'interested' => 'follow_up',
            'not_interested' => 'lost',
            'need_follow_up' => 'follow_up',
            'survey_scheduled' => 'survey',
            'quotation_requested' => 'quotation',
            'deal' => 'deal',
            'lost' => 'lost',
        ];

        if (isset($statusMap[$result])) {
            $model->updateStatus($id, $statusMap[$result]);
        }

        activity_log(
            'Marketing - Lead',
            'follow_up',
            'Menambahkan follow up lead: ' . ($item['company_name'] ?? '-'),
            $followupId,
            $item['lead_number'] ?? null
        );

        $_SESSION['success'] = 'Follow up berhasil ditambahkan.';

        $this->redirect('marketing-leads-show', ['id' => $id]);
    }

    public function convertToCustomer()
    {
        $this->authorize('marketing_lead.convert');

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('marketing-leads');
        }

        $leadModel = new MarketingLead();
        $customerModel = new Customer();

        $lead = $leadModel->find($id);

        if (!$lead) {

            activity_log(
                'Marketing - Lead',
                'convert_failed',
                'Gagal convert lead karena data tidak ditemukan',
                $id
            );

            $_SESSION['error'] = 'Lead tidak ditemukan.';
            $this->redirect('marketing-leads');
        }

        if (!empty($lead['converted_customer_id'])) {

            activity_log(
                'Marketing - Lead',
                'convert_failed',
                'Lead sudah pernah dikonversi: ' . ($lead['company_name'] ?? '-'),
                $id,
                $lead['lead_number'] ?? null
            );

            $_SESSION['error'] = 'Lead sudah pernah dikonversi.';
            $this->redirect('marketing-leads-show', ['id' => $id]);
        }

        $customerData = [
            'company_name' => $lead['company_name'] ?? '',
            'pic_name' => $lead['pic_name'] ?? '',
            'phone' => $lead['phone'] ?? '',
            'email' => $lead['email'] ?? '',
            'address' => $lead['address'] ?? '',
            'npwp' => '',
            'status' => 'active'
        ];

        $customerModel->create($customerData);

        $customerId = $customerModel->getLastInsertId();

        $leadModel->markAsConverted($id, $customerId);

        activity_log(
            'Marketing - Lead',
            'convert',
            'Mengkonversi lead menjadi customer: ' . ($lead['company_name'] ?? '-'),
            $id,
            $lead['lead_number'] ?? null
        );

        $_SESSION['success'] = 'Lead berhasil dikonversi menjadi customer.';

        $this->redirect('customers-show', ['id' => $customerId]);
    }

    public function searchAjax()
    {
        if (empty($_SESSION['user_id'])) {

            activity_log(
                'Marketing - Lead',
                'ajax_denied',
                'Akses AJAX search lead tanpa login'
            );

            http_response_code(401);
            echo json_encode([]);
            exit;
        }

        $q = trim($_GET['q'] ?? '');

        $model = new MarketingLead();

        activity_log(
            'Marketing - Lead',
            'search_ajax',
            'Melakukan pencarian AJAX lead dengan keyword: ' . $q
        );

        header('Content-Type: application/json');

        echo json_encode($model->searchAjax($q));
        exit;
    }
}