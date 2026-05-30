<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<?php
$services = $services ?? [];
$testimonials = $testimonials ?? [];
$faqs = $faqs ?? [];
$portfolios = $portfolios ?? [];
$audienceSegments = frontAudienceSegments();

function serviceIconClass(array $service): string
{
    $slug = strtolower((string) ($service['slug'] ?? ''));
    $title = strtolower((string) ($service['title_id'] ?? $service['title_en'] ?? ''));

    if (str_contains($slug, 'corporate') || str_contains($title, 'corporate')) {
        return 'flaticon-hall';
    }

    if (str_contains($slug, 'gathering') || str_contains($title, 'gathering') || str_contains($title, 'outing')) {
        return 'flaticon-team';
    }

    if (str_contains($slug, 'launch') || str_contains($title, 'launch')) {
        return 'flaticon-app';
    }

    if (str_contains($slug, 'seminar') || str_contains($title, 'seminar') || str_contains($title, 'conference')) {
        return 'flaticon-mic';
    }

    if (str_contains($slug, 'wedding') || str_contains($title, 'wedding') || str_contains($title, 'private')) {
        return 'flaticon-star';
    }

    if (str_contains($slug, 'creative') || str_contains($title, 'creative')) {
        return 'flaticon-creativity';
    }

    if (str_contains($slug, 'exhibition') || str_contains($title, 'expo')) {
        return 'flaticon-art-show';
    }

    if (str_contains($slug, 'registration') || str_contains($slug, 'ticket') || str_contains($title, 'ticket')) {
        return 'flaticon-tickets';
    }

    return $service['icon'] ?: 'flaticon-hall';
}
?>

<section class="page-title" style="background-image: url('<?= frontAsset('images/background/9.jpg') ?>');">
    <div class="shape-thirtyfour"></div>
    <div class="shape-thirtyfive"></div>
    <div class="shape-thirtysix bounce-y"></div>
    <div class="shape-thirtyseven bounce-x"></div>

    <div class="auto-container">
        <div class="inner-container">
            <h1 class="title"><?= t('Layanan Kami', 'What We Offer') ?></h1>

            <div class="breadcrumb-marquee">
                <?php for ($i = 1; $i <= 10; $i++): ?>
                    <span class="title-two">
                        <?= t('Layanan kami . Beranda', 'What we offer . Home') ?>
                    </span>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</section>

<section class="feature-section-three">
    <div class="large-container">
        <div class="row">

            <?php if (!empty($services)): ?>
                <?php foreach ($services as $index => $service): ?>
                    <div class="feature-block-three has-active col-xl-3 col-lg-6 col-md-6 col-sm-6 wow fadeInUp" data-wow-delay="<?= ($index % 4) * 200 ?>ms">
                        <div class="inner-box <?= $index === 1 ? 'active' : '' ?>">
                            <div class="icon-box">
                                <i class="icon <?= htmlspecialchars(serviceIconClass($service)) ?>"></i>
                            </div>

                            <div class="content">
                                <h3 class="title">
                                    <a href="<?= frontUrl('service-detail', ['slug' => $service['slug']]) ?>">
                                        <?= htmlspecialchars(sentenceCaseText(t($service['title_id'], $service['title_en']))) ?>
                                    </a>
                                </h3>

                                <div class="text">
                                    <?= htmlspecialchars(t($service['description_id'], $service['description_en'])) ?>
                                </div>
                            </div>

                            <a href="<?= frontUrl('service-detail', ['slug' => $service['slug']]) ?>" class="read-more">
                                <i class="icon fas fa-angle-right"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <p><?= t('Belum ada layanan tersedia.', 'No services available yet.') ?></p>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>

<section class="funfact-section">
    <div class="bg bg-pattern-7"></div>
    <div class="shape-thirtytwo bounce-y"></div>
    <div class="shape-thirtythree bounce-y"></div>

    <div class="auto-container">
        <div class="sec-title-outer">
            <div class="sec-title light mb-0">
	                <span class="sub-title"><?= t('Mengapa memilih Iventlo', 'Why choose Iventlo') ?></span>
                <h2 class="text-reveal-anim">
                    <?= t(
	                        'Kami mengubah ide acara menjadi pengalaman nyata',
	                        'We turn your event ideas into real experiences'
                    ) ?>
                </h2>
            </div>

            <div class="text-box">
                <div class="text">
                    <?= t(
                        'Setiap layanan Iventlo dirancang untuk membantu client menyelenggarakan acara yang rapi, kreatif, tepat sasaran, dan sesuai tujuan bisnis maupun personal.',
                        'Every Iventlo service is designed to help clients organize events that are well-planned, creative, targeted, and aligned with business or personal goals.'
                    ) ?>
                </div>

                <div class="btn-box">
                    <a href="<?= frontUrl('contact') ?>" class="theme-btn btn-style-one bg-orange">
	                        <span class="btn-title"><?= t('Konsultasi layanan', 'Service consultation') ?></span>
                    </a>
                </div>
            </div>
        </div>

        <div class="fact-counter">
            <div class="row">
                <div class="counter-block col-lg-3 col-md-6 col-sm-6 wow fadeInUp">
                    <div class="inner-box">
                        <div class="icon">
                            <img src="<?= frontAsset('images/resource/counter1-1.svg') ?>" alt="Event">
                        </div>
                        <div class="content">
                            <div class="count-box">
                                <span class="count-text" data-speed="3000" data-stop="6">0</span>
                            </div>
	                            <h4 class="counter-title"><?= t('Kategori layanan', 'Service categories') ?></h4>
                        </div>
                    </div>
                </div>

                <div class="counter-block col-lg-3 col-md-6 col-sm-6 wow fadeInUp" data-wow-delay="200ms">
                    <div class="inner-box">
                        <div class="icon">
                            <img src="<?= frontAsset('images/resource/counter1-2.svg') ?>" alt="Client">
                        </div>
                        <div class="content">
                            <div class="count-box">
                                <span class="count-text" data-speed="3000" data-stop="4">0</span>
                            </div>
	                            <h4 class="counter-title"><?= t('Tahap pendampingan', 'Planning stages') ?></h4>
                        </div>
                    </div>
                </div>

                <div class="counter-block col-lg-3 col-md-6 col-sm-6 wow fadeInUp" data-wow-delay="400ms">
                    <div class="inner-box">
                        <div class="icon">
                            <img src="<?= frontAsset('images/resource/counter1-3.svg') ?>" alt="Vendor">
                        </div>
                        <div class="content">
                            <div class="count-box">
                                <span class="count-text" data-speed="3000" data-stop="2">0</span>
                            </div>
	                            <h4 class="counter-title"><?= t('Bahasa layanan', 'Service languages') ?></h4>
                        </div>
                    </div>
                </div>

                <div class="counter-block col-lg-3 col-md-6 col-sm-6 wow fadeInUp" data-wow-delay="600ms">
                    <div class="inner-box">
                        <div class="icon">
                            <img src="<?= frontAsset('images/resource/counter1-4.svg') ?>" alt="Partner">
                        </div>
                        <div class="content">
                            <div class="count-box">
                                <span class="count-text" data-speed="3000" data-stop="1">0</span>
                            </div>
	                            <h4 class="counter-title"><?= t('Partner terintegrasi', 'Integrated partner') ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<section class="client-section">
    <div class="auto-container">
        <div class="row">
            <div class="content-column col-xl-4 col-lg-12 col-md-12 col-sm-12 wow fadeInLeft">
                <div class="inner-column">
                    <div class="sec-title">
	                        <span class="sub-title"><?= t('Untuk berbagai acara', 'For diverse events') ?></span>
                        <h2 class="text-reveal-anim">
	                            <?= t('Solusi event untuk brand, instansi, dan komunitas', 'Event solutions for brands, institutions, and communities') ?>
                        </h2>
                        <div class="text">
                            <?= t(
                                'Kami membantu berbagai kebutuhan event untuk perusahaan, komunitas, organisasi, brand, dan personal client.',
                                'We support various event needs for companies, communities, organizations, brands, and personal clients.'
                            ) ?>
                        </div>
                    </div>

                    <div class="btn-box">
                        <a href="<?= frontUrl('contact') ?>" class="theme-btn btn-style-one bg-yellow">
	                            <span class="btn-title"><?= t('Jadi partner kami', 'Become our partner') ?></span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="sponsors-column col-xl-8 col-lg-12 col-md-12 col-sm-12 wow fadeInRight" data-wow-delay="200ms">
                <div class="inner-column">
                    <?php
                    $clientRows = array_chunk($audienceSegments, 4);
                    ?>

                    <?php foreach ($clientRows as $row): ?>
                        <div class="blocks-outer-box">
                            <?php foreach ($row as $segment): ?>
                                <div class="client-block">
                                    <div class="inner-box">
                                        <div class="audience-card">
                                            <i class="icon <?= htmlspecialchars($segment['icon']) ?>" aria-hidden="true"></i>
                                            <span class="label"><?= htmlspecialchars(t($segment['label_id'], $segment['label_en'])) ?></span>
                                            <span class="detail"><?= htmlspecialchars(t($segment['detail_id'], $segment['detail_en'])) ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($testimonials)): ?>
<section class="testimonial-section-two parallax-section">
    <div class="bg-box">
        <div class="parallax-bg bg bg-image" data-speed="0.5" style="background-image: url('<?= frontAsset('images/background/testimonial-iventlo-purple.png') ?>');"></div>
    </div>

    <div class="auto-container">
        <div class="sec-title-outer">
            <div class="sec-title light mb-0">
                <span class="sub-title">Testimonials</span>
                <h2 class="text-reveal-anim">
	                    <?= t('Apa kata client kami', 'What our clients say') ?>
                </h2>
            </div>

            <div class="image-box wow fadeInRight">
                <?php foreach (array_slice($testimonials, 0, 3) as $testimonial): ?>
                    <img
                        src="<?= !empty($testimonial['image']) ? uploadAsset($testimonial['image']) : frontAsset('images/resource/author-1.png') ?>"
                        alt="<?= htmlspecialchars($testimonial['name'] ?? 'Client') ?>"
                    >
                <?php endforeach; ?>
            </div>
        </div>

        <div class="outer-box">
            <div class="row">
                <?php foreach (array_slice($testimonials, 0, 3) as $index => $testimonial): ?>
                    <div class="testimonial-block-two col-xl-4 col-lg-6 col-md-6 col-sm-12 wow fadeInUp" data-wow-delay="<?= $index * 200 ?>ms">
                        <div class="inner-box">
                            <div class="content">
                                <div class="text">
                                    “<?= htmlspecialchars(t($testimonial['testimonial_id'], $testimonial['testimonial_en'])) ?>”
                                </div>

                                <div class="icon-box">
                                    <i class="quote-icon flaticon-right-quotation-mark"></i>
                                </div>

                                <div class="rating">
                                    <?php for ($i = 1; $i <= (int) ($testimonial['rating'] ?? 5); $i++): ?>
                                        <i class="flaticon-star"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>

                            <div class="author-box">
                                <div class="author-info">
                                    <div class="designation">
                                        <?= htmlspecialchars($testimonial['position'] ?: ($testimonial['company_name'] ?? 'Client')) ?>,
                                    </div>
                                    <h4 class="name">
                                        <?= htmlspecialchars($testimonial['name'] ?? '-') ?>
                                    </h4>
                                </div>

                                <div class="image-box">
                                    <figure class="image">
                                        <img
                                            src="<?= !empty($testimonial['image']) ? uploadAsset($testimonial['image']) : frontAsset('images/resource/testi2-1.jpg') ?>"
                                            alt="<?= htmlspecialchars($testimonial['name'] ?? 'Client') ?>"
                                        >
                                    </figure>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($faqs)): ?>
<section class="faq-section">
    <div class="shape-twentythree"></div>

    <div class="auto-container">
        <div class="row">
            <div class="image-column col-xl-7 col-lg-12 col-md-12 col-sm-12">
                <div class="inner-column">
                    <div class="exp-box">
                        <svg viewBox="0 0 100 100" width="100" height="100" class="circular-text">
                            <defs>
                                <path id="circle" d="M 50, 50m -37, 0a 37,37 0 1,1 74,0a 37,37 0 1,1 -74,0"/>
                            </defs>
                            <text>
                                <textPath xlink:href="#circle">
                                    <?= t('Pertanyaan tentang layanan event', 'Questions about event services') ?>
                                </textPath>
                            </text>
                        </svg>
                        <div class="logo">
                            <img src="<?= frontAsset('images/favicon.png') ?>" alt="FAQ Iventlo">
                        </div>
                    </div>

                    <figure class="image overlay-anim reveal">
                        <img src="<?= frontAsset('images/resource/faq1.webp') ?>" alt="FAQ Iventlo">
                    </figure>
                    <figure class="image two overlay-anim reveal">
                        <img src="<?= frontAsset('images/resource/faq2.webp') ?>" alt="FAQ Iventlo">
                    </figure>
                </div>
            </div>

            <div class="content-column col-xl-5 col-lg-12 col-md-12 col-sm-12 wow fadeInRight">
                <div class="inner-column">
                    <div class="sec-title">
	                        <span class="sub-title orange"><?= t('Tanya jawab', 'Questions & answers') ?></span>
                        <h2 class="text-reveal-anim">
	                            <?= t('Pertanyaan umum seputar layanan', 'Common questions about our services') ?>
                        </h2>
                    </div>

                    <ul class="accordion-box">
                        <?php foreach ($faqs as $index => $faq): ?>
                            <li class="accordion block <?= $index === 0 ? 'active-tab' : '' ?>">
                                <div class="acc-btn <?= $index === 0 ? 'active' : '' ?>">
                                    <?= htmlspecialchars(t($faq['question_id'], $faq['question_en'])) ?>
                                    <i class="icon fa fa-plus"></i>
                                </div>

                                <div class="acc-content <?= $index === 0 ? 'current' : '' ?>">
                                    <div class="content">
                                        <div class="text">
                                            <?= nl2br(htmlspecialchars(t($faq['answer_id'], $faq['answer_en']))) ?>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($portfolios)): ?>
<section class="gallery-section-two pb-0 pull-down parallax-section">
    <div class="large-container">
        <div class="outer-box">
            <div class="parallax-bg bg bg-image" data-speed="0.5" style="background-image: url('<?= frontAsset('images/background/6.jpg') ?>');"></div>

            <div class="title-box wow fadeInUp">
                <div class="sec-title light text-center">
	                    <span class="sub-title"><?= t('Mulai rencanakan event', 'Start planning your event') ?></span>
                    <h2 class="text-reveal-anim">
                        <?= t(
	                            'Sudah siap membuat acara yang berkesan?',
	                            'Ready to create a memorable event?'
                        ) ?>
                    </h2>
                </div>

                <div class="btn-box text-center">
                    <a href="<?= frontUrl('contact') ?>" class="theme-btn btn-style-one bg-orange">
	                        <span class="btn-title"><?= t('Konsultasi sekarang', 'Start consultation') ?></span>
                    </a>
                </div>
            </div>

            <div class="gallery-box gallery-swiper-two">
                <div class="swiper-wrapper">
                    <?php foreach (array_slice($portfolios, 0, 6) as $portfolio): ?>
                        <?php
                        $portfolioImage = !empty($portfolio['thumbnail'])
                            ? uploadAsset($portfolio['thumbnail'])
                            : frontAsset('images/resource/gallery2-1.jpg');
                        $portfolioSlug = current_lang() === 'en'
                            ? ($portfolio['slug_en'] ?? $portfolio['slug_id'])
                            : ($portfolio['slug_id'] ?? $portfolio['slug_en']);
                        ?>

                        <div class="gallery-block-two swiper-slide">
                            <div class="inner-box">
                                <figure class="image">
                                    <a href="<?= frontUrl('portfolio-detail', ['slug' => $portfolioSlug]) ?>">
                                        <img src="<?= $portfolioImage ?>" alt="<?= htmlspecialchars(t($portfolio['title_id'], $portfolio['title_en'])) ?>">
                                        <img src="<?= $portfolioImage ?>" alt="<?= htmlspecialchars(t($portfolio['title_id'], $portfolio['title_en'])) ?>">
                                    </a>
                                </figure>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </div>
</section>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
