<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<?php
$totalProducts = count($products ?? []);

$categories = [];

if (!empty($products)) {
    foreach ($products as $item) {
        if (!empty($item['category'])) {
            $categories[$item['category']] = $item['category'];
        }
    }
}

$popularProducts = !empty($products) ? array_slice($products, 0, 3) : [];
?>

<section class="page-title" style="background-image: url('<?= frontAsset('images/background/8.jpg') ?>');">
    <div class="shape-thirtyfour"></div>
    <div class="shape-thirtyfive"></div>
    <div class="shape-thirtysix bounce-y"></div>
    <div class="shape-thirtyseven bounce-x"></div>

    <div class="auto-container">
        <div class="inner-container">
            <h1 class="title"><?= t('Produk & Paket', 'Products & Packages') ?></h1>

            <div class="breadcrumb-marquee">
                <?php for ($i = 1; $i <= 10; $i++): ?>
                    <span class="title-two">
	                        <?= t('Produk . Beranda', 'Products . Home') ?>
                    </span>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</section>

<div class="shop-section">
    <div class="auto-container">
        <div class="row clearfix">

            <div class="content-column col-xl-8 col-lg-12 col-md-12 col-sm-12">
                <h2 class="title text-reveal-anim">
	                    <?= t('Paket event Iventlo', 'Iventlo event packages') ?>
                </h2>

                <div class="items-sorting">
                    <div class="row clearfix">
                        <div class="results-column col-md-12 col-sm-12 col-xs-12">
                            <h6>
                                <?= t(
                                    'Menampilkan ' . $totalProducts . ' paket event',
                                    'Showing ' . $totalProducts . ' event packages'
                                ) ?>
                            </h6>
                        </div>
                    </div>
                </div>

                <div class="our-shops">
                    <div class="row clearfix">

                        <?php if (!empty($products)): ?>
                            <?php foreach ($products as $product): ?>

                                <?php
                                $productTitle = t($product['title_id'] ?? '', $product['title_en'] ?? '');
                                $productPrice = t($product['price_label_id'] ?? '', $product['price_label_en'] ?? '');
                                $productImage = !empty($product['image'])
                                    ? uploadAsset($product['image'])
                                    : uploadAsset('website/content/package-corporate-live.webp');
                                ?>

                                <div class="shop-item col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                    <div class="inner-box">

                                        <div class="image">
                                            <img
                                                src="<?= $productImage ?>"
                                                alt="<?= htmlspecialchars($productTitle) ?>"
                                            >

                                            <div class="overlay-box">
                                                <ul class="cart-option">
                                                    <li>
                                                        <a href="<?= frontUrl('contact') ?>">
                                                            <span class="fa fa-link"></span>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="<?= $productImage ?>" data-rel="lightcase">
                                                            <span class="icon fa fa-search"></span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="lower-content">
                                            <h6 class="title">
                                                <a href="<?= frontUrl('contact') ?>">
                                                    <?= htmlspecialchars(sentenceCaseText($productTitle)) ?>
                                                </a>
                                            </h6>

                                            <?php if (!empty($productPrice)): ?>
                                                <ul class="price">
                                                    <li><?= htmlspecialchars($productPrice) ?></li>
                                                </ul>
                                            <?php endif; ?>

                                            <?php if (!empty($product['category'])): ?>
                                                <div class="text">
                                                    <?= htmlspecialchars($product['category']) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                    </div>
                                </div>

                            <?php endforeach; ?>
                        <?php else: ?>

                            <div class="col-12">
                                <p><?= t('Belum ada produk tersedia.', 'No products available yet.') ?></p>
                            </div>

                        <?php endif; ?>

                    </div>
                </div>
            </div>

            <div class="sidebar-column col-xl-4 col-lg-12 col-md-12 col-sm-12">
                <aside class="sidebar">

                    <div class="sidebar-widget search-box">
	                        <h3 class="sidebar-title"><?= t('Cari paket', 'Search package') ?></h3>

                        <form method="get" action="<?= frontUrl('products') ?>">
                            <div class="form-group">
                                <input
                                    type="search"
                                    name="q"
                                    placeholder="<?= t('Cari paket event...', 'Search event package...') ?>"
                                    value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
                                >
                                <button type="submit">
                                    <i class="icon flaticon-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <?php if (!empty($categories)): ?>
                        <div class="sidebar-widget category-list">
	                            <h3 class="sidebar-title"><?= t('Kategori produk', 'Product categories') ?></h3>

                            <ul class="cat-list">
                                <?php foreach ($categories as $category): ?>
                                    <li>
                                        <a href="<?= frontUrl('products', ['category' => $category]) ?>">
                                            <?= htmlspecialchars($category) ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($popularProducts)): ?>
                        <div class="sidebar-widget latest-news">
	                            <h3 class="sidebar-title"><?= t('Paket populer', 'Popular packages') ?></h3>

                            <div class="widget-content">
                                <?php foreach ($popularProducts as $popular): ?>

                                    <?php
                                    $popularTitle = t($popular['title_id'] ?? '', $popular['title_en'] ?? '');
                                    $popularImage = !empty($popular['image'])
                                        ? uploadAsset($popular['image'])
                                        : frontAsset('images/resource/blog-post-1.jpg');
                                    ?>

                                    <article class="post">
                                        <div class="post-thumb">
                                            <a href="<?= frontUrl('contact') ?>">
                                                <img src="<?= $popularImage ?>" alt="<?= htmlspecialchars($popularTitle) ?>">
                                            </a>
                                        </div>

                                        <div class="content">
                                            <h5 class="title">
                                                <a href="<?= frontUrl('contact') ?>">
                                                    <?= htmlspecialchars(sentenceCaseText($popularTitle)) ?>
                                                </a>
                                            </h5>

                                            <div class="date">
                                                <?= t('Minta penawaran custom', 'Request custom quote') ?>
                                            </div>
                                        </div>
                                    </article>

                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="sidebar-widget tags">
	                        <h3 class="sidebar-title">Tags</h3>

                        <ul class="popular-tags clearfix">
                            <li><a href="<?= frontUrl('products') ?>">Event</a></li>
	                            <li><a href="<?= frontUrl('products') ?>">Corporate</a></li>
                            <li><a href="<?= frontUrl('products') ?>">Wedding</a></li>
                            <li><a href="<?= frontUrl('products') ?>">Launching</a></li>
                            <li><a href="<?= frontUrl('products') ?>">Gathering</a></li>
                            <li><a href="<?= frontUrl('products') ?>">Creative</a></li>
                        </ul>
                    </div>

                </aside>
            </div>

        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
