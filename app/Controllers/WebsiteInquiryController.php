<?php


class WebsiteInquiryController extends Controller
{
    public function index()
    {
        requirePermission('website_inquiry.view');

        $model = new WebsiteInquiry();
        $limit = 10;
        $currentPage = max(1, (int) ($_GET['p'] ?? 1));
        $totalData = $model->countAll();
        $totalPages = max(1, (int) ceil($totalData / $limit));
        $currentPage = min($currentPage, $totalPages);
        $offset = ($currentPage - 1) * $limit;

        $this->view('website/inquiries/index', [
            'title' => 'Inquiry Leads',
            'inquiries' => $model->paginate($limit, $offset),
            'totalData' => $totalData,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
            'limit' => $limit,
            'paginationRoute' => 'website-inquiries',
        ]);
    }

    public function show()
    {
        requirePermission('website_inquiry.show');

        $id = $_GET['id'] ?? null;

        if (!$id) {
            header('Location: ' . url('website-inquiries'));
            exit;
        }

        $model = new WebsiteInquiry();
        $inquiry = $model->find($id);

        if (!$inquiry) {
            $_SESSION['error'] = 'Inquiry tidak ditemukan.';
            header('Location: ' . url('website-inquiries'));
            exit;
        }

        $this->view('website/inquiries/show', [
            'title' => 'Detail Inquiry',
            'inquiry' => $inquiry
        ]);
    }

    public function update()
    {
        requirePermission('website_inquiry.edit');

        $id = $_POST['id'] ?? null;

        if (!$id) {
            header('Location: ' . url('website-inquiries'));
            exit;
        }

        $model = new WebsiteInquiry();

        $model->updateStatus($id, [
            'status' => $_POST['status'] ?? 'new',
            'notes' => trim($_POST['notes'] ?? ''),
            'follow_up_date' => $_POST['follow_up_date'] ?? null
        ]);

        $_SESSION['success'] = 'Inquiry berhasil diperbarui.';
        header('Location: ' . url('website-inquiries-show', ['id' => $id]));
        exit;
    }

    public function delete()
    {
        requirePermission('website_inquiry.delete');

        $id = $_GET['id'] ?? null;

        if ($id) {
            $model = new WebsiteInquiry();
            $model->delete($id);

            $_SESSION['success'] = 'Inquiry berhasil dihapus.';
        }

        header('Location: ' . url('website-inquiries'));
        exit;
    }
}
