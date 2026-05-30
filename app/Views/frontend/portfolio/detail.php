<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php
$portfolioTitle = t($portfolio['title_id'] ?? '', $portfolio['title_en'] ?? '');
$portfolioCategory = t($portfolio['category_id'] ?? '', $portfolio['category_en'] ?? '');
$portfolioLocation = t($portfolio['location_id'] ?? '', $portfolio['location_en'] ?? '');
$portfolioImagePath = !empty($portfolio['thumbnail'])
    ? $portfolio['thumbnail']
    : ($portfolio['cover_image'] ?? '');
$portfolioImage = $portfolioImagePath !== ''
    ? uploadAsset($portfolioImagePath)
    : frontAsset('images/resource/gallery1-1.jpg');
?>

<section class="page-title" style="background-image: url('<?= frontAsset('images/background/8.jpg') ?>');">
    <div class="shape-thirtyfour"></div>
    <div class="shape-thirtyfive"></div>
    <div class="shape-thirtysix bounce-y"></div>
    <div class="shape-thirtyseven bounce-x"></div>

    <div class="auto-container">
        <div class="inner-container">
	            <h1 class="title"><?= htmlspecialchars(sentenceCaseText($portfolioTitle)) ?></h1>
            <div class="breadcrumb-marquee">
                <?php for ($i = 1; $i <= 8; $i++): ?>
	                    <span class="title-two"><?= t('Inspirasi konsep . Portfolio', 'Concept inspiration . Portfolio') ?></span>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</section>

<section class="event-details">
    <div class="auto-container">
        <div class="image-box">
            <img src="<?= $portfolioImage ?>" alt="<?= htmlspecialchars($portfolioTitle) ?>">
        </div>

        <div class="content-box">
            <?php if ($portfolioCategory !== ''): ?>
                <span class="category"><?= htmlspecialchars($portfolioCategory) ?></span>
            <?php endif; ?>

            <h2><?= htmlspecialchars(sentenceCaseText($portfolioTitle)) ?></h2>

            <ul class="event-info">
                <?php if (!empty($portfolio['client_name'])): ?>
                    <li>
                        <strong><?= t('Referensi', 'Reference') ?>:</strong>
                        <?= htmlspecialchars($portfolio['client_name']) ?>
                    </li>
                <?php endif; ?>

                <?php if (!empty($portfolioLocation)): ?>
                    <li>
	                        <strong><?= t('Area layanan', 'Service area') ?>:</strong>
                        <?= htmlspecialchars($portfolioLocation) ?>
                    </li>
                <?php endif; ?>
            </ul>

            <div class="text">
                <?= sanitizeRichHtml(current_lang() === 'en'
                    ? ($portfolio['description_en'] ?? '')
                    : ($portfolio['description_id'] ?? '')
                ) ?>
            </div>

            <div class="btn-box mt-4">
                <a href="<?= frontUrl('contact') ?>" class="theme-btn btn-style-one bg-yellow">
	                    <span class="btn-title"><?= t('Diskusikan konsep ini', 'Discuss this concept') ?></span>
                </a>
                <a href="<?= frontUrl('portfolio') ?>" class="theme-btn btn-style-one">
	                    <span class="btn-title"><?= t('Kembali ke portfolio', 'Back to portfolio') ?></span>
                </a>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
