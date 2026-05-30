<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<?php
$postTitle = t($post['title_id'] ?? '', $post['title_en'] ?? '');
$postExcerpt = t($post['excerpt_id'] ?? '', $post['excerpt_en'] ?? '');

$postContent = current_lang() === 'en'
    ? ($post['content_en'] ?? '')
    : ($post['content_id'] ?? '');

$postImage = !empty($post['featured_image'])
    ? uploadAsset($post['featured_image'])
    : frontAsset('images/resource/blog-single-1.jpg');

$publishedDate = $post['published_at'] ?? $post['created_at'] ?? date('Y-m-d');
$heroTitle = sentenceCaseText($postTitle);
$wordCount = str_word_count(strip_tags($postContent));
$readingMinutes = max(3, (int) ceil($wordCount / 180));
?>

<section class="page-title" style="background-image: url('<?= frontAsset('images/background/8.jpg') ?>');">
    <div class="shape-thirtyfour"></div>
    <div class="shape-thirtyfive"></div>
    <div class="shape-thirtysix bounce-y"></div>
    <div class="shape-thirtyseven bounce-x"></div>

    <div class="auto-container">
        <div class="inner-container">
            <h1 class="title"><?= htmlspecialchars($heroTitle) ?></h1>

            <div class="breadcrumb-marquee">
                <?php for ($i = 1; $i <= 10; $i++): ?>
                    <span class="title-two">
	                        <?= htmlspecialchars($heroTitle) ?> . Iventlo
                    </span>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</section>

<section class="blog-details article-detail-page">
    <div class="auto-container">
        <div class="row">

            <div class="content-column col-xl-8 col-lg-12 col-md-12 col-sm-12">
                <div class="inner-column">

                    <div class="news-single-block">
                        <div class="image-box">
                            <figure class="image overlay-anim">
                                <img src="<?= $postImage ?>" alt="<?= htmlspecialchars($postTitle) ?>">
                            </figure>
                        </div>

                        <div class="content-box">
                            <div class="post-meta-box">
                                <div class="cat"><?= t('Artikel', 'Article') ?></div>

                                <ul class="post-meta">
                                    <li>
                                        <i class="icon fa fa-calendar"></i>
                                        <?= date('d M Y', strtotime($publishedDate)) ?>
                                    </li>
                                    <li>
                                        <i class="icon fa fa-user"></i>
                                        Admin
                                    </li>
                                    <li>
                                        <i class="icon fa fa-clock"></i>
                                        <?= $readingMinutes ?> <?= t('menit baca', 'min read') ?>
                                    </li>
                                </ul>
                            </div>

                            <h2 class="title">
                                <?= htmlspecialchars(sentenceCaseText($postTitle)) ?>
                            </h2>

                            <?php if (!empty($postExcerpt)): ?>
                                <div class="article-excerpt">
                                    <?= htmlspecialchars($postExcerpt) ?>
                                </div>
                            <?php endif; ?>

                            <div class="text article-rich-content">
                                <?= sanitizeRichHtml($postContent) ?>
                            </div>

                            <div class="article-cta-box">
                                <div>
                                    <h3><?= t('Butuh partner event yang rapi?', 'Need a structured event partner?') ?></h3>
                                    <p><?= t('Diskusikan kebutuhan acara Anda bersama tim Iventlo agar konsep, produksi, registrasi, dokumentasi, dan reporting berjalan lebih terukur.', 'Discuss your event needs with Iventlo so concept, production, registration, documentation, and reporting are handled more clearly.') ?></p>
                                </div>
                                <a href="<?= frontUrl('contact') ?>" class="theme-btn btn-style-one bg-orange">
                                    <span class="btn-title"><?= t('Konsultasi event', 'Event consultation') ?></span>
                                </a>
                            </div>
                        </div>
                    </div>

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
                                >
                                <button type="submit">
                                    <i class="icon flaticon-search"></i>
                                </button>
                            </div>
                        </form>
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
