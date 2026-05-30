<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<?php
$posts = $posts ?? [];
$totalPosts = (int) ($totalPosts ?? count($posts));
$articleCurrentPage = (int) ($articleCurrentPage ?? ($_GET['p'] ?? 1));
$totalPages = (int) ($totalPages ?? 1);
$limit = (int) ($limit ?? 10);
$q = trim((string) ($q ?? ($_GET['q'] ?? '')));
$popularPosts = $popularPosts ?? (!empty($posts) ? array_slice($posts, 0, 3) : []);
$buildBlogPageUrl = static function (int $page) use ($q): string {
    $params = [];

    if ($q !== '') {
        $params['q'] = $q;
    }

    if ($page > 1) {
        $params['p'] = $page;
    }

    return frontUrl('blog', $params);
};
?>

<section class="page-title" style="background-image: url('<?= frontAsset('images/background/8.jpg') ?>');">
    <div class="shape-thirtyfour"></div>
    <div class="shape-thirtyfive"></div>
    <div class="shape-thirtysix bounce-y"></div>
    <div class="shape-thirtyseven bounce-x"></div>

    <div class="auto-container">
        <div class="inner-container">
            <h1 class="title"><?= t('Artikel', 'Latest News') ?></h1>

            <div class="breadcrumb-marquee">
                <?php for ($i = 1; $i <= 10; $i++): ?>
                    <span class="title-two">
                        <?= t('Artikel . Beranda', 'Latest News . Home') ?>
                    </span>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</section>

<section class="blog-section">
    <div class="auto-container">
        <div class="row">

            <div class="content-column col-xl-8 col-lg-12 col-md-12 col-sm-12">
                <div class="inner-column">

                    <?php if (!empty($posts)): ?>
                        <?php foreach ($posts as $post): ?>

                            <?php
                            $postTitle = t($post['title_id'] ?? '', $post['title_en'] ?? '');
                            $postExcerpt = t($post['excerpt_id'] ?? '', $post['excerpt_en'] ?? '');

                            $postSlug = current_lang() === 'en'
                                ? ($post['slug_en'] ?? $post['slug_id'] ?? '')
                                : ($post['slug_id'] ?? $post['slug_en'] ?? '');

                            $postUrl = frontUrl('blog-detail', ['slug' => $postSlug]);

                            $postImage = !empty($post['featured_image'])
                                ? uploadAsset($post['featured_image'])
                                : frontAsset('images/resource/blog-single-1.jpg');

                            $dateSource = $post['published_at'] ?? $post['created_at'] ?? date('Y-m-d');
                            $day = date('d', strtotime($dateSource));
                            $month = date('M', strtotime($dateSource));
                            ?>

                            <div class="news-single-block">
                                <div class="image-box">
                                    <figure class="image overlay-anim">
                                        <a href="<?= $postUrl ?>">
                                            <img
                                                src="<?= $postImage ?>"
                                                alt="<?= htmlspecialchars($postTitle) ?>"
                                            >
                                        </a>
                                    </figure>

                                    <div class="date-box">
                                        <h4 class="date"><?= $day ?></h4>
                                        <div class="month"><?= $month ?></div>
                                    </div>

                                    <a href="<?= $postUrl ?>" class="theme-btn btn-style-one bg-orange">
	                    <span class="btn-title"><?= t('Baca selengkapnya', 'Read more') ?></span>
                                    </a>
                                </div>

                                <div class="content-box">
                                    <div class="post-meta-box">
                                        <div class="cat"><?= t('Artikel', 'Article') ?></div>

                                        <ul class="post-meta">
                                            <li><i class="icon fa fa-user"></i> Admin</li>
	                                            <li><i class="icon fa fa-comment"></i> <?= t('Insight event', 'Event insight') ?></li>
                                        </ul>
                                    </div>

                                    <h3 class="title">
                                        <a href="<?= $postUrl ?>">
                                            <?= htmlspecialchars(sentenceCaseText($postTitle)) ?>
                                        </a>
                                    </h3>

                                    <?php if (!empty($postExcerpt)): ?>
                                        <div class="text">
                                            <?= htmlspecialchars($postExcerpt) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                        <?php endforeach; ?>
                    <?php else: ?>

                        <div class="news-single-block">
                            <div class="content-box">
                                <h3 class="title">
                                    <?= t('Belum ada artikel tersedia.', 'No articles available yet.') ?>
                                </h3>
                            </div>
                        </div>

                    <?php endif; ?>

                    <?php if ($totalPages > 1): ?>
                        <?php
                        $startPage = max(1, $articleCurrentPage - 2);
                        $endPage = min($totalPages, $articleCurrentPage + 2);

                        if ($articleCurrentPage <= 2) {
                            $endPage = min($totalPages, 5);
                        }

                        if ($articleCurrentPage >= $totalPages - 1) {
                            $startPage = max(1, $totalPages - 4);
                        }
                        ?>

                        <nav class="blog-pagination" aria-label="<?= t('Navigasi artikel', 'Article navigation') ?>">
                            <ul>
                                <li class="<?= $articleCurrentPage <= 1 ? 'disabled' : '' ?>">
                                    <a href="<?= $articleCurrentPage <= 1 ? '#' : $buildBlogPageUrl($articleCurrentPage - 1) ?>" aria-label="<?= t('Halaman sebelumnya', 'Previous page') ?>">
                                        <i class="fa fa-angle-left"></i>
                                    </a>
                                </li>

                                <?php if ($startPage > 1): ?>
                                    <li>
                                        <a href="<?= $buildBlogPageUrl(1) ?>">1</a>
                                    </li>

                                    <?php if ($startPage > 2): ?>
                                        <li class="dots"><span>...</span></li>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php for ($page = $startPage; $page <= $endPage; $page++): ?>
                                    <li class="<?= $page === $articleCurrentPage ? 'active' : '' ?>">
                                        <a href="<?= $buildBlogPageUrl($page) ?>" aria-current="<?= $page === $articleCurrentPage ? 'page' : 'false' ?>">
                                            <?= $page ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($endPage < $totalPages): ?>
                                    <?php if ($endPage < $totalPages - 1): ?>
                                        <li class="dots"><span>...</span></li>
                                    <?php endif; ?>

                                    <li>
                                        <a href="<?= $buildBlogPageUrl($totalPages) ?>"><?= $totalPages ?></a>
                                    </li>
                                <?php endif; ?>

                                <li class="<?= $articleCurrentPage >= $totalPages ? 'disabled' : '' ?>">
                                    <a href="<?= $articleCurrentPage >= $totalPages ? '#' : $buildBlogPageUrl($articleCurrentPage + 1) ?>" aria-label="<?= t('Halaman berikutnya', 'Next page') ?>">
                                        <i class="fa fa-angle-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    <?php endif; ?>

                </div>
            </div>

            <div class="sidebar-column col-xl-4 col-lg-12 col-md-12 col-sm-12">
                <aside class="sidebar">

                    <div class="sidebar-widget search-box">
	                        <h3 class="sidebar-title"><?= t('Cari artikel', 'Search') ?></h3>

                        <form method="get" action="<?= frontUrl('blog') ?>">
                            <div class="form-group">
                                <input
                                    type="search"
                                    name="q"
                                    placeholder="<?= t('Cari artikel...', 'Search articles...') ?>"
                                    value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
                                >
                                <button type="submit">
                                    <i class="icon flaticon-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <?php if (!empty($popularPosts)): ?>
                        <div class="sidebar-widget latest-news">
	                            <h3 class="sidebar-title"><?= t('Artikel populer', 'Popular posts') ?></h3>

                            <div class="widget-content">
                                <?php foreach ($popularPosts as $popular): ?>

                                    <?php
                                    $popularTitle = t($popular['title_id'] ?? '', $popular['title_en'] ?? '');

                                    $popularSlug = current_lang() === 'en'
                                        ? ($popular['slug_en'] ?? $popular['slug_id'] ?? '')
                                        : ($popular['slug_id'] ?? $popular['slug_en'] ?? '');

                                    $popularUrl = frontUrl('blog-detail', ['slug' => $popularSlug]);

                                    $popularImage = !empty($popular['featured_image'])
                                        ? uploadAsset($popular['featured_image'])
                                        : frontAsset('images/resource/blog-post-1.jpg');

                                    $popularDate = $popular['published_at'] ?? $popular['created_at'] ?? date('Y-m-d');
                                    ?>

                                    <article class="post">
                                        <div class="post-thumb">
                                            <a href="<?= $popularUrl ?>">
                                                <img
                                                    src="<?= $popularImage ?>"
                                                    alt="<?= htmlspecialchars($popularTitle) ?>"
                                                >
                                            </a>
                                        </div>

                                        <div class="content">
                                            <h5 class="title">
                                                <a href="<?= $popularUrl ?>">
                                                    <?= htmlspecialchars(sentenceCaseText($popularTitle)) ?>
                                                </a>
                                            </h5>

                                            <div class="date">
                                                <?= date('F j, Y', strtotime($popularDate)) ?>
                                            </div>
                                        </div>
                                    </article>

                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="sidebar-widget category-list">
                        <h3 class="sidebar-title"><?= t('Kategori', 'Categories') ?></h3>

                        <ul class="cat-list">
	                            <li class="active"><a href="<?= frontUrl('blog') ?>">Event planning</a></li>
	                            <li><a href="<?= frontUrl('blog') ?>">Corporate event</a></li>
	                            <li><a href="<?= frontUrl('blog') ?>">Wedding organizer</a></li>
	                            <li><a href="<?= frontUrl('blog') ?>">Product launching</a></li>
	                            <li><a href="<?= frontUrl('blog') ?>">Creative production</a></li>
	                            <li><a href="<?= frontUrl('blog') ?>">Event highlights</a></li>
                        </ul>
                    </div>

                    <div class="sidebar-widget tags">
                        <h3 class="sidebar-title">Tags</h3>

                        <ul class="popular-tags clearfix">
                            <li><a href="<?= frontUrl('blog') ?>">Event</a></li>
                            <li><a href="<?= frontUrl('blog') ?>">EO</a></li>
                            <li><a href="<?= frontUrl('blog') ?>">Corporate</a></li>
                            <li><a href="<?= frontUrl('blog') ?>">Wedding</a></li>
                            <li><a href="<?= frontUrl('blog') ?>">Gathering</a></li>
                            <li><a href="<?= frontUrl('blog') ?>">Launching</a></li>
                            <li><a href="<?= frontUrl('blog') ?>">Creative</a></li>
                        </ul>
                    </div>

                </aside>
            </div>

        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
