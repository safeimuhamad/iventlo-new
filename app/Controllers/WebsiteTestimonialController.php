<?php



class WebsiteTestimonialController extends Controller
{
    public function index()
    {
        requirePermission('website_testimonial.view');

        $model = new WebsiteTestimonial();
        $limit = 10;
        $currentPage = max(1, (int) ($_GET['p'] ?? 1));
        $totalData = $model->countAll();
        $totalPages = max(1, (int) ceil($totalData / $limit));
        $currentPage = min($currentPage, $totalPages);
        $offset = ($currentPage - 1) * $limit;

        $this->view('website/testimonials/index', [
            'title' => 'Website Testimonials',
            'testimonials' => $model->paginate($limit, $offset),
            'totalData' => $totalData,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
            'limit' => $limit,
            'paginationRoute' => 'website-testimonials',
        ]);
    }

    public function create()
    {
        requirePermission('website_testimonial.create');

        $this->view('website/testimonials/form', [
            'title' => 'Tambah Testimoni',
            'testimonial' => null,
            'services' => $this->serviceOptions(),
        ]);
    }

    public function store()
    {
        requirePermission('website_testimonial.create');

        $image = null;

        if (!empty($_FILES['image']['name'])) {
            $image = $this->uploadImage($_FILES['image']);
        }

        $model = new WebsiteTestimonial();
        $category = $this->categoryFromPost();

        $model->create([
            'name' => trim($_POST['name'] ?? ''),
            'company_name' => trim($_POST['company_name'] ?? ''),
            'position' => trim($_POST['position'] ?? ''),
            'category_id' => $category['id'],
            'category_en' => $category['en'],
            'testimonial_id' => trim($_POST['testimonial_id'] ?? ''),
            'testimonial_en' => trim($_POST['testimonial_en'] ?? ''),
            'image' => $image,
            'rating' => (int) ($_POST['rating'] ?? 5),
            'status' => $_POST['status'] ?? 'active',
        ]);

        $_SESSION['success'] = 'Testimoni berhasil ditambahkan.';
        header('Location: ' . url('website-testimonials'));
        exit;
    }

    public function edit()
    {
        requirePermission('website_testimonial.edit');

        $id = $_GET['id'] ?? null;

        if (!$id) {
            header('Location: ' . url('website-testimonials'));
            exit;
        }

        $model = new WebsiteTestimonial();
        $testimonial = $model->find($id);

        if (!$testimonial) {
            $_SESSION['error'] = 'Testimoni tidak ditemukan.';
            header('Location: ' . url('website-testimonials'));
            exit;
        }

        $this->view('website/testimonials/form', [
            'title' => 'Edit Testimoni',
            'testimonial' => $testimonial,
            'services' => $this->serviceOptions(),
        ]);
    }

    public function update()
    {
        requirePermission('website_testimonial.edit');

        $id = $_POST['id'] ?? null;

        if (!$id) {
            header('Location: ' . url('website-testimonials'));
            exit;
        }

        $model = new WebsiteTestimonial();
        $testimonial = $model->find($id);

        if (!$testimonial) {
            $_SESSION['error'] = 'Testimoni tidak ditemukan.';
            header('Location: ' . url('website-testimonials'));
            exit;
        }

        $image = $testimonial['image'] ?? null;

        if (!empty($_FILES['image']['name'])) {
            $image = $this->uploadImage($_FILES['image']);
        }

        $category = $this->categoryFromPost();

        $model->update($id, [
            'name' => trim($_POST['name'] ?? ''),
            'company_name' => trim($_POST['company_name'] ?? ''),
            'position' => trim($_POST['position'] ?? ''),
            'category_id' => $category['id'],
            'category_en' => $category['en'],
            'testimonial_id' => trim($_POST['testimonial_id'] ?? ''),
            'testimonial_en' => trim($_POST['testimonial_en'] ?? ''),
            'image' => $image,
            'rating' => (int) ($_POST['rating'] ?? 5),
            'status' => $_POST['status'] ?? 'active',
        ]);

        $_SESSION['success'] = 'Testimoni berhasil diperbarui.';
        header('Location: ' . url('website-testimonials'));
        exit;
    }

    public function delete()
    {
        requirePermission('website_testimonial.delete');

        $id = $_GET['id'] ?? null;

        if ($id) {
            $model = new WebsiteTestimonial();
            $model->delete($id);

            $_SESSION['success'] = 'Testimoni berhasil dihapus.';
        }

        header('Location: ' . url('website-testimonials'));
        exit;
    }

    private function uploadImage($file)
    {
        return uploadWebsiteImageAsWebp($file, 'testimonials', 'testimonial');
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
