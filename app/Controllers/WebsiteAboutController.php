<?php

class WebsiteAboutController extends Controller
{
    public function index()
    {
        requirePermission('website_about.view');

        $model = new WebsiteAbout();

        $this->view('website/about/form', [
            'title' => 'Tentang Kami',
            'about' => $model->first()
        ]);
    }

    public function update()
    {
        requirePermission('website_about.edit');

        $model = new WebsiteAbout();
        $about = $model->first();

        if (!$about) {
            $_SESSION['error'] = 'Data About belum tersedia.';
            header('Location: ' . url('website-about'));
            exit;
        }

        $image = $about['image'] ?? null;
        $image2 = $about['image_2'] ?? null;

        if (!empty($_FILES['image']['name'])) {
            $image = $this->uploadImage($_FILES['image']);
        }

        if (!empty($_FILES['image_2']['name'])) {
            $image2 = $this->uploadImage($_FILES['image_2']);
        }

        $model->update($about['id'], [
            'title_id' => trim($_POST['title_id'] ?? ''),
            'title_en' => trim($_POST['title_en'] ?? ''),
            'content_id' => trim($_POST['content_id'] ?? ''),
            'content_en' => trim($_POST['content_en'] ?? ''),
            'image' => $image,
            'image_2' => $image2,
            'vision_id' => trim($_POST['vision_id'] ?? ''),
            'vision_en' => trim($_POST['vision_en'] ?? ''),
            'mission_id' => trim($_POST['mission_id'] ?? ''),
            'mission_en' => trim($_POST['mission_en'] ?? ''),
        ]);

        $_SESSION['success'] = 'Data Tentang Kami berhasil diperbarui.';
        header('Location: ' . url('website-about'));
        exit;
    }

    private function uploadImage($file)
    {
        return uploadWebsiteImageAsWebp($file, 'about', 'about');
    }
}
