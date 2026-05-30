<?php



class WebsiteProductController extends Controller
{
    public function index()
    {
        requirePermission('website_product.view');

        $model = new WebsiteProduct();
        $limit = 10;
        $currentPage = max(1, (int) ($_GET['p'] ?? 1));
        $totalData = $model->countAll();
        $totalPages = max(1, (int) ceil($totalData / $limit));
        $currentPage = min($currentPage, $totalPages);
        $offset = ($currentPage - 1) * $limit;

        $this->view('website/products/index', [
            'title' => 'Website Products',
            'products' => $model->paginate($limit, $offset),
            'totalData' => $totalData,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
            'limit' => $limit,
            'paginationRoute' => 'website-products',
        ]);
    }

    public function create()
    {
        requirePermission('website_product.create');

        $this->view('website/products/form', [
            'title' => 'Tambah Produk',
            'product' => null
        ]);
    }

    public function store()
    {
        requirePermission('website_product.create');

        $image = null;

        if (!empty($_FILES['image']['name'])) {
            $image = $this->uploadImage($_FILES['image']);
        }

        $model = new WebsiteProduct();

        $model->create([
            'title_id' => trim($_POST['title_id'] ?? ''),
            'title_en' => trim($_POST['title_en'] ?? ''),
            'description_id' => trim($_POST['description_id'] ?? ''),
            'description_en' => trim($_POST['description_en'] ?? ''),
            'category' => trim($_POST['category'] ?? ''),
            'image' => $image,
            'price_label_id' => trim($_POST['price_label_id'] ?? ''),
            'price_label_en' => trim($_POST['price_label_en'] ?? ''),
            'status' => $_POST['status'] ?? 'active',
        ]);

        $_SESSION['success'] = 'Produk berhasil ditambahkan.';
        header('Location: ' . url('website-products'));
        exit;
    }

    public function edit()
    {
        requirePermission('website_product.edit');

        $id = $_GET['id'] ?? null;

        if (!$id) {
            header('Location: ' . url('website-products'));
            exit;
        }

        $model = new WebsiteProduct();
        $product = $model->find($id);

        if (!$product) {
            $_SESSION['error'] = 'Produk tidak ditemukan.';
            header('Location: ' . url('website-products'));
            exit;
        }

        $this->view('website/products/form', [
            'title' => 'Edit Produk',
            'product' => $product
        ]);
    }

    public function update()
    {
        requirePermission('website_product.edit');

        $id = $_POST['id'] ?? null;

        if (!$id) {
            header('Location: ' . url('website-products'));
            exit;
        }

        $model = new WebsiteProduct();
        $product = $model->find($id);

        if (!$product) {
            $_SESSION['error'] = 'Produk tidak ditemukan.';
            header('Location: ' . url('website-products'));
            exit;
        }

        $image = $product['image'] ?? null;

        if (!empty($_FILES['image']['name'])) {
            $image = $this->uploadImage($_FILES['image']);
        }

        $model->update($id, [
            'title_id' => trim($_POST['title_id'] ?? ''),
            'title_en' => trim($_POST['title_en'] ?? ''),
            'description_id' => trim($_POST['description_id'] ?? ''),
            'description_en' => trim($_POST['description_en'] ?? ''),
            'category' => trim($_POST['category'] ?? ''),
            'image' => $image,
            'price_label_id' => trim($_POST['price_label_id'] ?? ''),
            'price_label_en' => trim($_POST['price_label_en'] ?? ''),
            'status' => $_POST['status'] ?? 'active',
        ]);

        $_SESSION['success'] = 'Produk berhasil diperbarui.';
        header('Location: ' . url('website-products'));
        exit;
    }

    public function delete()
    {
        requirePermission('website_product.delete');

        $id = $_GET['id'] ?? null;

        if ($id) {
            $model = new WebsiteProduct();
            $model->delete($id);

            $_SESSION['success'] = 'Produk berhasil dihapus.';
        }

        header('Location: ' . url('website-products'));
        exit;
    }

    private function uploadImage($file)
    {
        return uploadWebsiteImageAsWebp($file, 'products', 'product');
    }
}
