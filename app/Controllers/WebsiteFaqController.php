<?php

require_once __DIR__ . '/../Models/WebsiteFaq.php';

class WebsiteFaqController extends Controller
{
    public function index()
    {
        requirePermission('website_faq.view');

        $model = new WebsiteFaq();
        $limit = 10;
        $currentPage = max(1, (int) ($_GET['p'] ?? 1));
        $totalData = $model->countAll();
        $totalPages = max(1, (int) ceil($totalData / $limit));
        $currentPage = min($currentPage, $totalPages);
        $offset = ($currentPage - 1) * $limit;

        $this->view('website/faqs/index', [
            'title' => 'Website FAQ',
            'faqs' => $model->paginate($limit, $offset),
            'totalData' => $totalData,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
            'limit' => $limit,
            'paginationRoute' => 'website-faqs',
        ]);
    }

    public function create()
    {
        requirePermission('website_faq.create');

        $this->view('website/faqs/form', [
            'title' => 'Tambah FAQ',
            'faq' => null,
            'services' => $this->serviceOptions(),
        ]);
    }

    public function store()
    {
        requirePermission('website_faq.create');

        $model = new WebsiteFaq();
        $category = $this->categoryFromPost();

        $model->create([
            'category_id' => $category['id'],
            'category_en' => $category['en'],
            'question_id' => trim($_POST['question_id'] ?? ''),
            'question_en' => trim($_POST['question_en'] ?? ''),
            'answer_id' => trim($_POST['answer_id'] ?? ''),
            'answer_en' => trim($_POST['answer_en'] ?? ''),
            'status' => $_POST['status'] ?? 'active',
        ]);

        $_SESSION['success'] = 'FAQ berhasil ditambahkan.';
        header('Location: ' . url('website-faqs'));
        exit;
    }

    public function edit()
    {
        requirePermission('website_faq.edit');

        $id = $_GET['id'] ?? null;

        if (!$id) {
            header('Location: ' . url('website-faqs'));
            exit;
        }

        $model = new WebsiteFaq();
        $faq = $model->find($id);

        if (!$faq) {
            $_SESSION['error'] = 'FAQ tidak ditemukan.';
            header('Location: ' . url('website-faqs'));
            exit;
        }

        $this->view('website/faqs/form', [
            'title' => 'Edit FAQ',
            'faq' => $faq,
            'services' => $this->serviceOptions(),
        ]);
    }

    public function update()
    {
        requirePermission('website_faq.edit');

        $id = $_POST['id'] ?? null;

        if (!$id) {
            header('Location: ' . url('website-faqs'));
            exit;
        }

        $model = new WebsiteFaq();
        $faq = $model->find($id);

        if (!$faq) {
            $_SESSION['error'] = 'FAQ tidak ditemukan.';
            header('Location: ' . url('website-faqs'));
            exit;
        }

        $category = $this->categoryFromPost();

        $model->update($id, [
            'category_id' => $category['id'],
            'category_en' => $category['en'],
            'question_id' => trim($_POST['question_id'] ?? ''),
            'question_en' => trim($_POST['question_en'] ?? ''),
            'answer_id' => trim($_POST['answer_id'] ?? ''),
            'answer_en' => trim($_POST['answer_en'] ?? ''),
            'status' => $_POST['status'] ?? 'active',
        ]);

        $_SESSION['success'] = 'FAQ berhasil diperbarui.';
        header('Location: ' . url('website-faqs'));
        exit;
    }

    public function delete()
    {
        requirePermission('website_faq.delete');

        $id = $_GET['id'] ?? null;

        if ($id) {
            $model = new WebsiteFaq();
            $model->delete($id);

            $_SESSION['success'] = 'FAQ berhasil dihapus.';
        }

        header('Location: ' . url('website-faqs'));
        exit;
    }

    private function serviceOptions()
    {
        return (new WebsiteService())->active();
    }

    private function categoryFromPost()
    {
        $categoryId = trim($_POST['category_id'] ?? '');
        $categoryEn = trim($_POST['category_en'] ?? '');

        if ($categoryId !== '' && $categoryEn === '') {
            foreach ($this->serviceOptions() as $service) {
                if (($service['title_id'] ?? '') === $categoryId) {
                    $categoryEn = $service['title_en'] ?? $categoryId;
                    break;
                }
            }
        }

        return [
            'id' => $categoryId,
            'en' => $categoryEn,
        ];
    }
}
