<?php


class WebsitePortfolioController extends Controller
{
    public function index()
    {
        requirePermission('website_portfolio.view');

        $model = new WebsitePortfolio();
        $limit = 10;
        $currentPage = max(1, (int) ($_GET['p'] ?? 1));
        $totalData = $model->countAll();
        $totalPages = max(1, (int) ceil($totalData / $limit));
        $currentPage = min($currentPage, $totalPages);
        $offset = ($currentPage - 1) * $limit;

        $this->view('website/portfolios/index', [
            'title' => 'Website Portfolio',
            'portfolios' => $model->paginate($limit, $offset),
            'totalData' => $totalData,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
            'limit' => $limit,
            'paginationRoute' => 'website-portfolios',
        ]);
    }

    public function create()
    {
        requirePermission('website_portfolio.create');

        $this->view('website/portfolios/form', [
            'title' => 'Tambah Portfolio',
            'portfolio' => null,
            'services' => $this->serviceOptions(),
        ]);
    }

    public function store()
    {
        requirePermission('website_portfolio.create');

        $thumbnail = null;

        if (!empty($_FILES['thumbnail']['name'])) {
            $thumbnail = $this->uploadImage($_FILES['thumbnail']);
        }

        $model = new WebsitePortfolio();
        $category = $this->categoryFromPost();

        $model->create([
            'title_id' => trim($_POST['title_id'] ?? ''),
            'title_en' => trim($_POST['title_en'] ?? ''),
            'slug_id' => $this->slug($_POST['slug_id'] ?: $_POST['title_id']),
            'slug_en' => $this->slug($_POST['slug_en'] ?: $_POST['title_en']),
            'category_id' => $category['id'],
            'category_en' => $category['en'],
            'client_name' => trim($_POST['client_name'] ?? ''),
            'event_date' => !empty($_POST['event_date']) ? $_POST['event_date'] : null,
            'location_id' => trim($_POST['location_id'] ?? ''),
            'location_en' => trim($_POST['location_en'] ?? ''),
            'description_id' => trim($_POST['description_id'] ?? ''),
            'description_en' => trim($_POST['description_en'] ?? ''),
            'thumbnail' => $thumbnail,
            'status' => $_POST['status'] ?? 'active',
        ]);

        $_SESSION['success'] = 'Portfolio berhasil ditambahkan.';
        header('Location: ' . url('website-portfolios'));
        exit;
    }

    public function edit()
    {
        requirePermission('website_portfolio.edit');

        $id = $_GET['id'] ?? null;

        if (!$id) {
            header('Location: ' . url('website-portfolios'));
            exit;
        }

        $model = new WebsitePortfolio();
        $portfolio = $model->find($id);

        if (!$portfolio) {
            $_SESSION['error'] = 'Portfolio tidak ditemukan.';
            header('Location: ' . url('website-portfolios'));
            exit;
        }

        $this->view('website/portfolios/form', [
            'title' => 'Edit Portfolio',
            'portfolio' => $portfolio,
            'services' => $this->serviceOptions(),
        ]);
    }

    public function update()
    {
        requirePermission('website_portfolio.edit');

        $id = $_POST['id'] ?? null;

        if (!$id) {
            header('Location: ' . url('website-portfolios'));
            exit;
        }

        $model = new WebsitePortfolio();
        $portfolio = $model->find($id);

        if (!$portfolio) {
            $_SESSION['error'] = 'Portfolio tidak ditemukan.';
            header('Location: ' . url('website-portfolios'));
            exit;
        }

        $thumbnail = $portfolio['thumbnail'] ?? null;

        if (!empty($_FILES['thumbnail']['name'])) {
            $thumbnail = $this->uploadImage($_FILES['thumbnail']);
        }

        $category = $this->categoryFromPost();

        $model->update($id, [
            'title_id' => trim($_POST['title_id'] ?? ''),
            'title_en' => trim($_POST['title_en'] ?? ''),
            'slug_id' => $this->slug($_POST['slug_id'] ?: $_POST['title_id']),
            'slug_en' => $this->slug($_POST['slug_en'] ?: $_POST['title_en']),
            'category_id' => $category['id'],
            'category_en' => $category['en'],
            'client_name' => trim($_POST['client_name'] ?? ''),
            'event_date' => !empty($_POST['event_date']) ? $_POST['event_date'] : null,
            'location_id' => trim($_POST['location_id'] ?? ''),
            'location_en' => trim($_POST['location_en'] ?? ''),
            'description_id' => trim($_POST['description_id'] ?? ''),
            'description_en' => trim($_POST['description_en'] ?? ''),
            'thumbnail' => $thumbnail,
            'status' => $_POST['status'] ?? 'active',
        ]);

        $_SESSION['success'] = 'Portfolio berhasil diperbarui.';
        header('Location: ' . url('website-portfolios'));
        exit;
    }

    public function delete()
    {
        requirePermission('website_portfolio.delete');

        $id = $_GET['id'] ?? null;

        if ($id) {
            $model = new WebsitePortfolio();
            $model->delete($id);

            $_SESSION['success'] = 'Portfolio berhasil dihapus.';
        }

        header('Location: ' . url('website-portfolios'));
        exit;
    }

    private function uploadImage($file)
    {
        return uploadWebsiteImageAsWebp($file, 'portfolios', 'portfolio');
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

    private function slug($text)
    {
        $text = strtolower(trim((string) $text));
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
        $text = preg_replace('/[\s-]+/', '-', $text);
        return trim($text, '-');
    }
}
