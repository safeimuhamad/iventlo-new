<?php


class FrontProductController extends Controller
{
    public function index()
    {
        $model = new WebsiteProduct();

        $q = trim($_GET['q'] ?? '');
        $category = trim($_GET['category'] ?? '');

        $products = $model->activeFiltered($q, $category);

        $this->frontView('frontend/products/index', [
            'title' => t('Produk & paket - Iventlo Event Organizer', 'Products & packages - Iventlo Event Organizer'),
            'meta_description' => t(
                'Produk dan paket event Iventlo untuk corporate gathering, wedding organizer, seminar, launching, creative production, dan hybrid event.',
                'Iventlo event products and packages for corporate gatherings, wedding organizer, seminars, launches, creative production, and hybrid events.'
            ),
            'meta_keywords' => 'paket event organizer, produk event, corporate gathering, wedding organizer, product launching',
            'products' => $products
        ]);
    }
}
