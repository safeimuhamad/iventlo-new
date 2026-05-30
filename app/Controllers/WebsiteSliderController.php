<?php

class WebsiteSliderController extends Controller
{
    public function index()
    {
        requirePermission('website_slider.view');

        $model = new WebsiteSlider();
        $limit = 10;
        $currentPage = max(1, (int) ($_GET['p'] ?? 1));
        $totalData = $model->countAll();
        $totalPages = max(1, (int) ceil($totalData / $limit));
        $currentPage = min($currentPage, $totalPages);
        $offset = ($currentPage - 1) * $limit;

        $this->view('website/sliders/index', [
            'title' => 'Website Slider',
            'sliders' => $model->paginate($limit, $offset),
            'totalData' => $totalData,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
            'limit' => $limit,
            'paginationRoute' => 'website-sliders',
        ]);
    }

    public function create()
    {
        requirePermission('website_slider.create');

        $this->view('website/sliders/form', [
            'title' => 'Tambah Slider',
            'slider' => null
        ]);
    }

    public function store()
    {
        requirePermission('website_slider.create');

        $model = new WebsiteSlider();

        $image = null;

        if (!empty($_FILES['image']['name'])) {
            $image = $this->uploadImage($_FILES['image']);
        }

        $model->create([
            'title_id' => trim($_POST['title_id'] ?? ''),
            'title_en' => trim($_POST['title_en'] ?? ''),
            'subtitle_id' => trim($_POST['subtitle_id'] ?? ''),
            'subtitle_en' => trim($_POST['subtitle_en'] ?? ''),
            'description_id' => trim($_POST['description_id'] ?? ''),
            'description_en' => trim($_POST['description_en'] ?? ''),
            'image' => $image,
            'button_text_id' => trim($_POST['button_text_id'] ?? ''),
            'button_text_en' => trim($_POST['button_text_en'] ?? ''),
            'button_link' => trim($_POST['button_link'] ?? ''),
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
            'status' => $_POST['status'] ?? 'active'
        ]);

        $_SESSION['success'] = 'Slider berhasil ditambahkan.';
        header('Location: ' . url('website-sliders'));
        exit;
    }

    public function edit()
    {
        requirePermission('website_slider.edit');

        $id = $_GET['id'] ?? null;

        if (!$id) {
            header('Location: ' . url('website-sliders'));
            exit;
        }

        $model = new WebsiteSlider();
        $slider = $model->find($id);

        if (!$slider) {
            $_SESSION['error'] = 'Slider tidak ditemukan.';
            header('Location: ' . url('website-sliders'));
            exit;
        }

        $this->view('website/sliders/form', [
            'title' => 'Edit Slider',
            'slider' => $slider
        ]);
    }

    public function update()
    {
        requirePermission('website_slider.edit');

        $id = $_POST['id'] ?? null;

        if (!$id) {
            header('Location: ' . url('website-sliders'));
            exit;
        }

        $model = new WebsiteSlider();
        $slider = $model->find($id);

        if (!$slider) {
            $_SESSION['error'] = 'Slider tidak ditemukan.';
            header('Location: ' . url('website-sliders'));
            exit;
        }

        $image = $slider['image'];

        if (!empty($_FILES['image']['name'])) {
            $image = $this->uploadImage($_FILES['image']);
        }

        $model->update($id, [
            'title_id' => trim($_POST['title_id'] ?? ''),
            'title_en' => trim($_POST['title_en'] ?? ''),
            'subtitle_id' => trim($_POST['subtitle_id'] ?? ''),
            'subtitle_en' => trim($_POST['subtitle_en'] ?? ''),
            'description_id' => trim($_POST['description_id'] ?? ''),
            'description_en' => trim($_POST['description_en'] ?? ''),
            'image' => $image,
            'button_text_id' => trim($_POST['button_text_id'] ?? ''),
            'button_text_en' => trim($_POST['button_text_en'] ?? ''),
            'button_link' => trim($_POST['button_link'] ?? ''),
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
            'status' => $_POST['status'] ?? 'active'
        ]);

        $_SESSION['success'] = 'Slider berhasil diperbarui.';
        header('Location: ' . url('website-sliders'));
        exit;
    }

    public function delete()
    {
        requirePermission('website_slider.delete');

        $id = $_GET['id'] ?? null;

        if ($id) {
            $model = new WebsiteSlider();
            $model->delete($id);

            $_SESSION['success'] = 'Slider berhasil dihapus.';
        }

        header('Location: ' . url('website-sliders'));
        exit;
    }

    private function uploadImage($file)
    {
        return uploadWebsiteImageAsWebp($file, 'sliders', 'slider');
    }
}
