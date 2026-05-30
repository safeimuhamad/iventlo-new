<?php


class WebsiteServiceController extends Controller
{
    public function index()
    {
        requirePermission('website_service.view');

        $model = new WebsiteService();
        $limit = 10;
        $currentPage = max(1, (int) ($_GET['p'] ?? 1));
        $totalData = $model->countAll();
        $totalPages = max(1, (int) ceil($totalData / $limit));
        $currentPage = min($currentPage, $totalPages);
        $offset = ($currentPage - 1) * $limit;

        $this->view('website/services/index', [
            'title' => 'Website Services',
            'services' => $model->paginate($limit, $offset),
            'totalData' => $totalData,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
            'limit' => $limit,
            'paginationRoute' => 'website-services',
        ]);
    }

    public function create()
    {
        requirePermission('website_service.create');

        $this->view('website/services/form', [
            'title' => 'Tambah Layanan',
            'service' => null
        ]);
    }

    public function store()
    {
        requirePermission('website_service.create');

        $model = new WebsiteService();

        $image = null;

        if (!empty($_FILES['image']['name'])) {
            $image = $this->uploadImage($_FILES['image']);
        }

        $model->create([
            'title_id' => trim($_POST['title_id'] ?? ''),
            'title_en' => trim($_POST['title_en'] ?? ''),
            'description_id' => trim($_POST['description_id'] ?? ''),
            'description_en' => trim($_POST['description_en'] ?? ''),
            'meta_title_id' => trim($_POST['meta_title_id'] ?? ''),
            'meta_title_en' => trim($_POST['meta_title_en'] ?? ''),
            'meta_description_id' => trim($_POST['meta_description_id'] ?? ''),
            'meta_description_en' => trim($_POST['meta_description_en'] ?? ''),
            'meta_keywords_id' => trim($_POST['meta_keywords_id'] ?? ''),
            'meta_keywords_en' => trim($_POST['meta_keywords_en'] ?? ''),
            'og_title_id' => trim($_POST['og_title_id'] ?? ''),
            'og_title_en' => trim($_POST['og_title_en'] ?? ''),
            'og_description_id' => trim($_POST['og_description_id'] ?? ''),
            'og_description_en' => trim($_POST['og_description_en'] ?? ''),
            'meta_robots' => trim($_POST['meta_robots'] ?? 'index, follow, max-image-preview:large'),
            'icon' => trim($_POST['icon'] ?? ''),
            'image' => $image,
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
            'status' => $_POST['status'] ?? 'active',
        ]);

        $_SESSION['success'] = 'Layanan berhasil ditambahkan.';
        header('Location: ' . url('website-services'));
        exit;
    }

    public function edit()
    {
        requirePermission('website_service.edit');

        $id = $_GET['id'] ?? null;

        if (!$id) {
            header('Location: ' . url('website-services'));
            exit;
        }

        $model = new WebsiteService();
        $service = $model->find($id);

        if (!$service) {
            $_SESSION['error'] = 'Layanan tidak ditemukan.';
            header('Location: ' . url('website-services'));
            exit;
        }

        $this->view('website/services/form', [
            'title' => 'Edit Layanan',
            'service' => $service
        ]);
    }

    public function update()
    {
        requirePermission('website_service.edit');

        $id = $_POST['id'] ?? null;

        if (!$id) {
            header('Location: ' . url('website-services'));
            exit;
        }

        $model = new WebsiteService();
        $service = $model->find($id);

        if (!$service) {
            $_SESSION['error'] = 'Layanan tidak ditemukan.';
            header('Location: ' . url('website-services'));
            exit;
        }

        $image = $service['image'] ?? null;

        if (!empty($_FILES['image']['name'])) {
            $image = $this->uploadImage($_FILES['image']);
        }

        $model->update($id, [
            'title_id' => trim($_POST['title_id'] ?? ''),
            'title_en' => trim($_POST['title_en'] ?? ''),
            'description_id' => trim($_POST['description_id'] ?? ''),
            'description_en' => trim($_POST['description_en'] ?? ''),
            'meta_title_id' => trim($_POST['meta_title_id'] ?? ''),
            'meta_title_en' => trim($_POST['meta_title_en'] ?? ''),
            'meta_description_id' => trim($_POST['meta_description_id'] ?? ''),
            'meta_description_en' => trim($_POST['meta_description_en'] ?? ''),
            'meta_keywords_id' => trim($_POST['meta_keywords_id'] ?? ''),
            'meta_keywords_en' => trim($_POST['meta_keywords_en'] ?? ''),
            'og_title_id' => trim($_POST['og_title_id'] ?? ''),
            'og_title_en' => trim($_POST['og_title_en'] ?? ''),
            'og_description_id' => trim($_POST['og_description_id'] ?? ''),
            'og_description_en' => trim($_POST['og_description_en'] ?? ''),
            'meta_robots' => trim($_POST['meta_robots'] ?? 'index, follow, max-image-preview:large'),
            'icon' => trim($_POST['icon'] ?? ''),
            'image' => $image,
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
            'status' => $_POST['status'] ?? 'active',
        ]);

        $_SESSION['success'] = 'Layanan berhasil diperbarui.';
        header('Location: ' . url('website-services'));
        exit;
    }

    public function delete()
    {
        requirePermission('website_service.delete');

        $id = $_GET['id'] ?? null;

        if ($id) {
            $model = new WebsiteService();
            $model->delete($id);

            $_SESSION['success'] = 'Layanan berhasil dihapus.';
        }

        header('Location: ' . url('website-services'));
        exit;
    }

    private function uploadImage($file)
    {
        return uploadWebsiteImageAsWebp($file, 'services', 'service');
    }
}
