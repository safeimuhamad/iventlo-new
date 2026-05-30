
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<section class="page-title" style="background-image: url('<?= frontAsset('images/background/8.jpg') ?>');">
    <div class="shape-thirtyfour"></div>
    <div class="shape-thirtyfive"></div>
    <div class="shape-thirtysix bounce-y"></div>
    <div class="shape-thirtyseven bounce-x"></div>

    <div class="auto-container">
        <div class="inner-container">
            <h1 class="title"><?= t('Portfolio Event', 'Event Portfolio') ?></h1>

            <div class="breadcrumb-marquee">
                <?php for ($i = 1; $i <= 10; $i++): ?>
                    <span class="title-two">
	                        <?= t('Portfolio . Beranda', 'Portfolio . Home') ?>
                    </span>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</section>

<section class="gallery-section">
    <div class="auto-container">

        <div class="sec-title text-center">
	            <span class="sub-title"><?= t('Inspirasi konsep', 'Concept inspiration') ?></span>
            <h2 class="text-reveal-anim">
                <?= t(
	                    'Konsep event yang dapat kami kembangkan',
	                    'Event concepts we can develop'
                ) ?>
            </h2>
        </div>

        <div class="row">

            <?php if (!empty($portfolios)): ?>
                <?php foreach ($portfolios as $portfolio): ?>
                    <?php
                    $portfolioImagePath = !empty($portfolio['thumbnail'])
                        ? $portfolio['thumbnail']
                        : ($portfolio['cover_image'] ?? '');
                    $portfolioImage = $portfolioImagePath !== ''
                        ? uploadAsset($portfolioImagePath)
                        : frontAsset('images/resource/gallery1-1.jpg');
                    $portfolioTitle = t($portfolio['title_id'], $portfolio['title_en']);
                    $portfolioUrl = frontUrl('portfolio-detail', [
                        'slug' => current_lang() === 'en' ? $portfolio['slug_en'] : $portfolio['slug_id']
                    ]);
                    ?>
                    <div class="gallery-block col-lg-4 col-md-6 col-sm-12">
                        <div class="inner-box">
                            <figure class="image">
                                <a href="<?= $portfolioUrl ?>">
                                    <img
                                        src="<?= $portfolioImage ?>"
                                        alt="<?= htmlspecialchars($portfolioTitle) ?>"
                                    >
                                    <img
                                        src="<?= $portfolioImage ?>"
                                        alt="<?= htmlspecialchars($portfolioTitle) ?>"
                                    >
                                </a>
                            </figure>

                            <div class="content-box">
                                <span class="cat">
                                    <?= htmlspecialchars(t($portfolio['category_id'] ?? '', $portfolio['category_en'] ?? '')) ?>
                                </span>

                                <h4 class="title">
                                    <a href="<?= $portfolioUrl ?>">
                                        <?= htmlspecialchars(sentenceCaseText($portfolioTitle)) ?>
                                    </a>
                                </h4>

                                <?php if (!empty($portfolio['client_name'])): ?>
                                    <div class="text">
                                        <?= htmlspecialchars($portfolio['client_name']) ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($portfolio['event_date'])): ?>
                                    <div class="text">
                                        <?= date('d M Y', strtotime($portfolio['event_date'])) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p><?= t('Inspirasi konsep sedang disiapkan. Silakan konsultasikan kebutuhan event Anda.', 'Concept inspirations are being prepared. Please consult us about your event needs.') ?></p>
                </div>
            <?php endif; ?>

        </div>

    </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
