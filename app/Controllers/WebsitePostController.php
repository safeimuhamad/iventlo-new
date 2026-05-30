<?php


class WebsitePostController extends Controller
{
    public function index()
    {
        requirePermission('website_post.view');

        $model = new WebsitePost();
        $limit = 10;
        $currentPage = max(1, (int) ($_GET['p'] ?? 1));
        $totalData = $model->countAll();
        $totalPages = max(1, (int) ceil($totalData / $limit));
        $currentPage = min($currentPage, $totalPages);
        $offset = ($currentPage - 1) * $limit;

        $this->view('website/posts/index', [
            'title' => 'Website Articles',
            'posts' => $model->paginate($limit, $offset),
            'totalData' => $totalData,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
            'limit' => $limit,
            'paginationRoute' => 'website-posts',
        ]);
    }

    public function create()
    {
        requirePermission('website_post.create');

        $this->view('website/posts/form', [
            'title' => 'Tambah Artikel',
            'post' => null
        ]);
    }

    public function store()
    {
        requirePermission('website_post.create');

        $model = new WebsitePost();

        $image = null;

        if (!empty($_FILES['featured_image']['name'])) {
            $image = $this->uploadImage($_FILES['featured_image']);
        }

        $model->create([
            ':title_id' => trim($_POST['title_id'] ?? ''),
            ':title_en' => trim($_POST['title_en'] ?? ''),
            ':slug_id' => $this->slug($_POST['slug_id'] ?: $_POST['title_id']),
            ':slug_en' => $this->slug($_POST['slug_en'] ?: $_POST['title_en']),
            ':excerpt_id' => trim($_POST['excerpt_id'] ?? ''),
            ':excerpt_en' => trim($_POST['excerpt_en'] ?? ''),
            ':content_id' => trim($_POST['content_id'] ?? ''),
            ':content_en' => trim($_POST['content_en'] ?? ''),
            ':featured_image' => $image,
            ':meta_title' => trim($_POST['meta_title'] ?? ''),
            ':meta_keywords' => trim($_POST['meta_keywords'] ?? ''),
            ':meta_description' => trim($_POST['meta_description'] ?? ''),
            ':published_at' => !empty($_POST['published_at']) ? $_POST['published_at'] : null,
            ':sort_order' => (int) ($_POST['sort_order'] ?? 0),
            ':status' => $_POST['status'] ?? 'draft',
        ]);

        $_SESSION['success'] = 'Artikel berhasil ditambahkan.';
        header('Location: ' . url('website-posts'));
        exit;
    }

    public function edit()
    {
        requirePermission('website_post.edit');

        $id = $_GET['id'] ?? null;

        if (!$id) {
            header('Location: ' . url('website-posts'));
            exit;
        }

        $model = new WebsitePost();
        $post = $model->find($id);

        if (!$post) {
            $_SESSION['error'] = 'Artikel tidak ditemukan.';
            header('Location: ' . url('website-posts'));
            exit;
        }

        $this->view('website/posts/form', [
            'title' => 'Edit Artikel',
            'post' => $post
        ]);
    }

    public function update()
    {
        requirePermission('website_post.edit');

        $id = $_POST['id'] ?? null;

        if (!$id) {
            header('Location: ' . url('website-posts'));
            exit;
        }

        $model = new WebsitePost();
        $post = $model->find($id);

        if (!$post) {
            $_SESSION['error'] = 'Artikel tidak ditemukan.';
            header('Location: ' . url('website-posts'));
            exit;
        }

        $image = $post['featured_image'] ?? null;

        if (!empty($_FILES['featured_image']['name'])) {
            $image = $this->uploadImage($_FILES['featured_image']);
        }

        $model->update($id, [
            ':title_id' => trim($_POST['title_id'] ?? ''),
            ':title_en' => trim($_POST['title_en'] ?? ''),
            ':slug_id' => $this->slug($_POST['slug_id'] ?: $_POST['title_id']),
            ':slug_en' => $this->slug($_POST['slug_en'] ?: $_POST['title_en']),
            ':excerpt_id' => trim($_POST['excerpt_id'] ?? ''),
            ':excerpt_en' => trim($_POST['excerpt_en'] ?? ''),
            ':content_id' => trim($_POST['content_id'] ?? ''),
            ':content_en' => trim($_POST['content_en'] ?? ''),
            ':featured_image' => $image,
            ':meta_title' => trim($_POST['meta_title'] ?? ''),
            ':meta_keywords' => trim($_POST['meta_keywords'] ?? ''),
            ':meta_description' => trim($_POST['meta_description'] ?? ''),
            ':published_at' => !empty($_POST['published_at']) ? $_POST['published_at'] : null,
            ':sort_order' => (int) ($_POST['sort_order'] ?? 0),
            ':status' => $_POST['status'] ?? 'draft',
        ]);

        $_SESSION['success'] = 'Artikel berhasil diperbarui.';
        header('Location: ' . url('website-posts'));
        exit;
    }

    public function delete()
    {
        requirePermission('website_post.delete');

        $id = $_GET['id'] ?? null;

        if ($id) {
            $model = new WebsitePost();
            $model->delete($id);
            $_SESSION['success'] = 'Artikel berhasil dihapus.';
        }

        header('Location: ' . url('website-posts'));
        exit;
    }

    private function uploadImage($file)
    {
        return uploadWebsiteImageAsWebp($file, 'posts', 'post');
    }

    private function slug($text)
    {
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
        $text = preg_replace('/[\s-]+/', '-', $text);
        return trim($text, '-');
    }
}
