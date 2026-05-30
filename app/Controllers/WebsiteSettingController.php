<?php


class WebsiteSettingController extends Controller
{
    public function index()
    {
        requirePermission('website_setting.view');

        $model = new WebsiteSetting();
        $setting = $model->first();

        $this->view('website/settings/form', [
            'title' => 'Website Setting',
            'setting' => $setting
        ]);
    }

    public function update()
    {
        requirePermission('website_setting.edit');

        $model = new WebsiteSetting();
        $setting = $model->first();

        if (!$setting) {
            $_SESSION['error'] = 'Website setting belum tersedia.';
            header('Location: ' . url('website-settings'));
            exit;
        }

        $logo = $setting['logo'] ?? null;
        $logoWhite = $setting['logo_white'] ?? null;
        $favicon = $setting['favicon'] ?? null;

        if (!empty($_FILES['logo']['name'])) {
            $logo = $this->uploadImage($_FILES['logo']);
        }

        if (!empty($_FILES['logo_white']['name'])) {
            $logoWhite = $this->uploadImage($_FILES['logo_white']);
        }

        if (!empty($_FILES['favicon']['name'])) {
            $favicon = $this->uploadImage($_FILES['favicon']);
        }

        $model->update($setting['id'], [
            'company_name' => trim($_POST['company_name'] ?? ''),
            'tagline' => trim($_POST['tagline'] ?? ''),
            'logo' => $logo,
            'logo_white' => $logoWhite,
            'favicon' => $favicon,
            'phone' => trim($_POST['phone'] ?? ''),
            'whatsapp' => trim($_POST['whatsapp'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
            'instagram' => trim($_POST['instagram'] ?? ''),
            'facebook' => trim($_POST['facebook'] ?? ''),
            'linkedin' => trim($_POST['linkedin'] ?? ''),
            'youtube' => trim($_POST['youtube'] ?? ''),
            'tiktok' => trim($_POST['tiktok'] ?? ''),
            'google_map' => trim($_POST['google_map'] ?? ''),
            'meta_title' => trim($_POST['meta_title'] ?? ''),
            'meta_keywords' => trim($_POST['meta_keywords'] ?? ''),
            'meta_description' => trim($_POST['meta_description'] ?? ''),
        ]);

        $_SESSION['success'] = 'Website setting berhasil diperbarui.';
        header('Location: ' . url('website-settings'));
        exit;
    }

    private function uploadImage($file)
    {
        return uploadWebsiteImageAsWebp($file, 'settings', 'setting');
    }
}
