<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<?php
$sliders = $sliders ?? [];
$services = $services ?? [];
$testimonials = $testimonials ?? [];
$faqs = $faqs ?? [];
$posts = $posts ?? [];
$products = $products ?? [];
$portfolios = $portfolios ?? [];
$publicEvents = $publicEvents ?? [];
$about = $about ?? [];
$audienceSegments = frontAudienceSegments();

$aboutTitle = t(
    $about['title_id'] ?? 'Partner event organizer untuk acara yang rapi, kreatif, dan berkesan',
    $about['title_en'] ?? 'Your event organizer partner for well-planned, creative, and memorable events'
);

$aboutContent = t(
    $about['content_id'] ?? 'Iventlo membantu perusahaan, komunitas, brand, dan personal client dalam merancang event dari tahap konsep, budgeting, vendor, produksi, pelaksanaan, hingga dokumentasi.',
    $about['content_en'] ?? 'Iventlo helps companies, communities, brands, and personal clients plan events from concept, budgeting, vendor coordination, production, execution, to documentation.'
);

$aboutImage1 = !empty($about['image'])
    ? uploadAsset($about['image'])
    : frontAsset('images/resource/about1-1.png');

$aboutImage2 = !empty($about['image_2'])
    ? uploadAsset($about['image_2'])
    : frontAsset('images/resource/about1-2.png');

function sliderButtonUrl($link)
{
    $link = trim((string) $link);

    if (preg_match('/^\?page=([a-z0-9-]+)$/i', $link, $matches)) {
        return frontUrl($matches[1]);
    }

    return safeLinkUrl($link, frontUrl('contact'));
}

?>

<section class="banner-section style-two">
    <div class="banner-layer"></div>

    <div class="swiper banner-swiper">
        <div class="swiper-wrapper">

            <?php if (!empty($sliders)): ?>
                <?php foreach ($sliders as $sliderIndex => $slider): ?>
                    <div class="banner-slide <?= $sliderIndex === 0 ? 'banner-slide-primary' : 'banner-slide-secondary' ?> swiper-slide">
                        <div class="bg bg-image"
                             style="background-image: url('<?= !empty($slider['image']) ? uploadAsset($slider['image']) : frontAsset('images/banner/1.png') ?>');">
                        </div>

                        <div class="auto-container">
                            <div class="content-box">
                                <?php if ($sliderIndex === 0): ?>
                                    <h1 class="title animate-4">
                                        <?= htmlspecialchars(t($slider['title_id'] ?? '', $slider['title_en'] ?? '')) ?>
                                    </h1>
                                <?php else: ?>
                                    <h2 class="title animate-4">
                                        <?= htmlspecialchars(t($slider['title_id'] ?? '', $slider['title_en'] ?? '')) ?>
                                    </h2>
                                <?php endif; ?>

                                <div class="location-box animate-5">
                                    <div class="text">
                                        <?= nl2br(htmlspecialchars(t($slider['description_id'] ?? '', $slider['description_en'] ?? ''))) ?>
                                    </div>

                                    <div class="btn-box">
                                        <a href="<?= htmlspecialchars(sliderButtonUrl($slider['button_link'] ?? '')) ?>"
                                           class="theme-btn btn-style-one bg-yellow">
                                            <span class="btn-title">
                                                <?= htmlspecialchars(sentenceCaseText(t(
                                                    $slider['button_text_id'] ?? 'Konsultasi sekarang',
                                                    $slider['button_text_en'] ?? 'Start consultation'
                                                ))) ?>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="banner-slide swiper-slide">
                    <div class="bg bg-image"
                         style="background-image: url('<?= frontAsset('images/banner/1.png') ?>');">
                    </div>

                    <div class="auto-container">
                        <div class="content-box">
                            <h1 class="title animate-4">
                                <?= t('Wujudkan event berkesan', 'Create memorable events') ?>
                            </h1>

                            <div class="location-box animate-5">
                                <div class="text">
                                    <?= t(
                                        'Corporate event, wedding, gathering, seminar, launching & creative event',
                                        'Corporate event, wedding, gathering, seminar, launching & creative event'
                                    ) ?>
                                </div>

                                <div class="btn-box">
                                    <a href="<?= frontUrl('contact') ?>"
                                       class="theme-btn btn-style-one bg-yellow">
                                        <span class="btn-title">
                                            <?= t('Konsultasi sekarang', 'Start consultation') ?>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <div class="swiper-pagination"></div>
</section>

<section class="about-section">
    <div class="icon-one bounce-y"></div>
    <div class="icon-two bounce-y"></div>

    <div class="auto-container">
        <div class="outer-box">
            <div class="image-box wow fadeInUp">
                <div class="image-outer">
                    <figure class="image overlay-anim">
                        <img src="<?= $aboutImage2 ?>" alt="<?= htmlspecialchars($aboutTitle) ?>">
                    </figure>

                    <div class="icon-nineteen"></div>
                    <div class="icon-twenty"></div>
                </div>

                <div class="speaker-box">
                    <i class="icon flaticon-mic"></i>

                    <div class="count">
                        <?= $about['counter_number'] ?? 50 ?>+
                    </div>

                    <div class="text">
                        <?= htmlspecialchars(
                            t(
                                $about['counter_label_id'] ?? 'Event dikelola',
                                $about['counter_label_en'] ?? 'Events managed'
                            )
                        ) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="outer-box two">
            <div class="image-box">
                <div class="image-outer">
                    <figure class="image overlay-anim reveal">
                        <img src="<?= $aboutImage1 ?>" alt="<?= htmlspecialchars($aboutTitle) ?>">
                    </figure>

                    <div class="icon-twentyfour"></div>
                </div>
            </div>

            <div class="content-box wow fadeInRight" data-wow-delay="200ms">
                <div class="sec-title">
                    <span class="sub-title">
                        <?= t('Tentang Iventlo', 'About Iventlo') ?>
                    </span>

                    <h2 class="text-reveal-anim">
                        <?= htmlspecialchars(sentenceCaseText($aboutTitle)) ?>
                    </h2>

                    <div class="text text-anim">
                        <?= nl2br(htmlspecialchars($aboutContent)) ?>
                    </div>
                </div>

                <div class="btn-box">
                    <a href="<?= frontUrl('services') ?>"
                       class="theme-btn btn-style-one icon-btn bg-yellow">
                        <i class="icon flaticon-tickets"></i>

                        <span class="btn-title">
                            <?= t('Layanan kami', 'Our services') ?>
                        </span>
                    </a>

                    <a href="<?= frontUrl('contact') ?>"
                       class="theme-btn btn-style-one icon-btn">
                        <i class="icon flaticon-placeholder"></i>

                        <span class="btn-title">
                            <?= t('Hubungi kami', 'Contact us') ?>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="marquee-section">
    <div class="marquee-container">
        <div class="marquee">
            <div class="text">Corporate event</div>
            <div class="text">*</div>

            <div class="text">Wedding</div>
            <div class="text">*</div>

            <div class="text">Gathering</div>
            <div class="text">*</div>

            <div class="text">Seminar</div>
            <div class="text">*</div>

            <div class="text">Launching</div>
            <div class="text">*</div>

            <div class="text">Creative event</div>
            <div class="text">*</div>
        </div>
    </div>
</div>

<section class="features-section">
    <div class="shape-one bounce-y"></div>
    <div class="shape-two"></div>

    <div class="auto-container">
        <div class="sec-title text-center">
            <span class="sub-title">
                <?= t('Layanan kami', 'Our services') ?>
            </span>

            <h2 class="text-reveal-anim">
                <?= t(
                    'Solusi event organizer untuk berbagai kebutuhan acara',
                    'Event organizer solutions for every occasion'
                ) ?>
            </h2>
        </div>

        <div class="row">
            <?php if (!empty($services)): ?>
                <?php foreach ($services as $index => $service): ?>
                    <div class="feature-block has-active col-lg-3 col-md-6 col-sm-6 wow fadeInUp"
                         data-wow-delay="<?= ($index % 4) * 200 ?>ms">

                        <div class="inner-box <?= $index === 2 ? 'active' : '' ?>">
                            <i class="icon <?= htmlspecialchars($service['icon'] ?: 'flaticon-speech') ?>"></i>

                            <div class="content">
                                <h5 class="title">
	                                    <?= htmlspecialchars(
	                                        sentenceCaseText(t(
	                                            $service['title_id'],
	                                            $service['title_en']
	                                        ))
	                                    ) ?>
                                </h5>

                                <div class="text">
                                    <?= htmlspecialchars(
                                        t(
                                            $service['description_id'],
                                            $service['description_en']
                                        )
                                    ) ?>
                                </div>

                                <a href="<?= frontUrl('services') ?>" class="read-more">
                                    <span><?= t('Selengkapnya', 'Read more') ?></span>
                                    <i class="fa fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p>
                        <?= t(
                            'Belum ada layanan tersedia.',
                            'No services available yet.'
                        ) ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php if (!empty($portfolios)): ?>
<section class="gallery-section p-0">
    <div class="outer-box">
        <div class="gallery one">

            <?php foreach (array_slice($portfolios, 0, 4) as $portfolio): ?>
                <?php
                $portfolioImagePath = !empty($portfolio['thumbnail'])
                    ? $portfolio['thumbnail']
                    : ($portfolio['cover_image'] ?? '');
                $portfolioImage = $portfolioImagePath !== ''
                    ? uploadAsset($portfolioImagePath)
                    : frontAsset('images/resource/gallery1-1.jpg');
                ?>

                <div class="gallery-block">
                    <div class="inner-box">
                        <figure class="image">
                            <a href="<?= $portfolioImage ?>" data-rel="lightcase">
                                <img src="<?= $portfolioImage ?>"
                                     alt="<?= htmlspecialchars(t($portfolio['title_id'], $portfolio['title_en'])) ?>">

                                <img src="<?= $portfolioImage ?>"
                                     alt="<?= htmlspecialchars(t($portfolio['title_id'], $portfolio['title_en'])) ?>">
                            </a>
                        </figure>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>

    <?php if (count($portfolios) > 4): ?>
        <div class="outer-box">
            <div class="gallery two">

                <?php foreach (array_slice($portfolios, 4, 4) as $portfolio): ?>
                    <?php
                    $portfolioImagePath = !empty($portfolio['thumbnail'])
                        ? $portfolio['thumbnail']
                        : ($portfolio['cover_image'] ?? '');
                    $portfolioImage = $portfolioImagePath !== ''
                        ? uploadAsset($portfolioImagePath)
                        : frontAsset('images/resource/gallery1-5.jpg');
                    ?>

                    <div class="gallery-block">
                        <div class="inner-box">
                            <figure class="image">
                                <a href="<?= $portfolioImage ?>" data-rel="lightcase">
                                    <img src="<?= $portfolioImage ?>"
                                         alt="<?= htmlspecialchars(t($portfolio['title_id'], $portfolio['title_en'])) ?>">

                                    <img src="<?= $portfolioImage ?>"
                                         alt="<?= htmlspecialchars(t($portfolio['title_id'], $portfolio['title_en'])) ?>">
                                </a>
                            </figure>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    <?php endif; ?>
</section>
<?php endif; ?>

<?php if (!empty($testimonials)): ?>
<section class="testimonial-section">
    <div class="shape-nine bounce-y"></div>

    <div class="auto-container">
        <div class="sec-title text-center">
	            <span class="sub-title">Testimonials</span>

            <h2 class="text-reveal-anim">
                <?= t(
                    'Apa kata client tentang Iventlo',
                    'What clients say about Iventlo'
                ) ?>
            </h2>
        </div>

        <div class="outer-box">
            <div class="swiper testi-swiper">
                <div class="swiper-wrapper">

                    <?php foreach ($testimonials as $testimonial): ?>
                        <div class="testimonial-block swiper-slide">
                            <div class="inner-box">
                                <div class="content">
                                    <div class="author-box">
                                        <div class="info-box">
                                            <h3 class="name">
                                                <?= htmlspecialchars($testimonial['name'] ?? '-') ?>
                                            </h3>

                                            <div class="designation">
                                                <?= htmlspecialchars(
                                                    $testimonial['position']
                                                    ?: ($testimonial['company_name'] ?? 'Client')
                                                ) ?>
                                            </div>
                                        </div>

                                        <i class="icon flaticon-right-quotation-mark"></i>
                                    </div>

                                    <div class="text">
                                        “<?= htmlspecialchars(
                                            t(
                                                $testimonial['testimonial_id'],
                                                $testimonial['testimonial_en']
                                            )
                                        ) ?>”
                                    </div>

                                    <div class="rating">
                                        <?php for ($i = 1; $i <= (int)($testimonial['rating'] ?? 5); $i++): ?>
                                            <i class="flaticon-star"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>

                                <div class="image-box">
                                    <figure class="image">
                                        <img
                                            src="<?= !empty($testimonial['image'])
                                                ? uploadAsset($testimonial['image'])
                                                : frontAsset('images/resource/testi1-1.png') ?>"
                                            alt="<?= htmlspecialchars($testimonial['name'] ?? 'Client') ?>"
                                        >
                                    </figure>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>

            <div class="nav-box">
                <div class="swiper-button-prev">
                    <span class="icon fa fa-arrow-left"></span>
                </div>

                <div class="swiper-button-next">
                    <span class="icon fa fa-arrow-right"></span>
                </div>
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
                                <path id="home-faq-circle" d="M 50, 50m -37, 0a 37,37 0 1,1 74,0a 37,37 0 1,1 -74,0"/>
                            </defs>
                            <text>
                                <textPath xlink:href="#home-faq-circle">
                                    <?= t('Pertanyaan umum Iventlo', 'Iventlo common questions') ?>
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
                            <?= t(
                                'Pertanyaan umum seputar Iventlo',
                                'Common questions about Iventlo'
                            ) ?>
                        </h2>
                    </div>

                    <ul class="accordion-box">

                        <?php foreach ($faqs as $index => $faq): ?>
                            <li class="accordion block <?= $index === 0 ? 'active-tab' : '' ?>">

                                <div class="acc-btn <?= $index === 0 ? 'active' : '' ?>">
                                    <?= htmlspecialchars(
                                        t(
                                            $faq['question_id'],
                                            $faq['question_en']
                                        )
                                    ) ?>

                                    <div class="icon fa fa-plus"></div>
                                </div>

                                <div class="acc-content <?= $index === 0 ? 'current' : '' ?>">
                                    <div class="content">
                                        <div class="text">
                                            <?= nl2br(
                                                htmlspecialchars(
                                                    t(
                                                        $faq['answer_id'],
                                                        $faq['answer_en']
                                                    )
                                                )
                                            ) ?>
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

<?php if (!empty($posts)): ?>
<section class="news-section">
    <div class="shape-six"></div>
    <div class="shape-seven"></div>

    <div class="auto-container">
        <div class="sec-title text-center">
            <span class="sub-title">
                <?= t('Update terbaru', 'Latest updates') ?>
            </span>

            <h2 class="text-reveal-anim">
                <?= t(
                    'Tips & insight seputar event organizer',
                    'Tips & insights about event organizing'
                ) ?>
            </h2>
        </div>

        <div class="row">

            <?php foreach ($posts as $post): ?>
                <?php
                $postTitle = t($post['title_id'], $post['title_en']);

                $postSlug = current_lang() === 'en'
                    ? ($post['slug_en'] ?? $post['slug_id'])
                    : ($post['slug_id'] ?? $post['slug_en']);

                $postUrl = frontUrl('blog-detail', ['slug' => $postSlug]);

                $postImage = !empty($post['featured_image'])
                    ? uploadAsset($post['featured_image'])
                    : frontAsset('images/resource/news1-1.jpg');

                $postDate = $post['published_at']
                    ?? $post['created_at']
                    ?? date('Y-m-d');
                ?>

                <div class="news-block has-active col-lg-4 col-md-6 col-sm-12 wow fadeInUp">
                    <div class="inner-box">
                        <div class="content-box">

                            <div class="date-box">
                                <h3 class="date">
                                    <?= date('d', strtotime($postDate)) ?>
                                </h3>

                                <div class="month">
                                    <?= date('M', strtotime($postDate)) ?>
                                </div>
                            </div>

                            <div class="cat">
                                <?= t('Artikel', 'Article') ?>
                            </div>

                            <ul class="post-meta">
                                <li>
                                    <i class="icon fa fa-user"></i> Admin
                                </li>

                                <li>
                                    <i class="icon fa fa-comment"></i>
                                    <?= t('Insight', 'Insight') ?>
                                </li>
                            </ul>

                            <h3 class="title">
                                <a href="<?= $postUrl ?>">
                                    <?= htmlspecialchars($postTitle) ?>
                                </a>
                            </h3>

                            <div class="text">
                                <?= htmlspecialchars(
                                    t(
                                        $post['excerpt_id'] ?? '',
                                        $post['excerpt_en'] ?? ''
                                    )
                                ) ?>
                            </div>

                            <div class="btn-box">
                                <a href="<?= $postUrl ?>" class="read-more">
                                    <i class="fa fa-arrow-right"></i>
                                </a>
                            </div>

                        </div>

                        <div class="image-box">
                            <figure class="image">
                                <a href="<?= $postUrl ?>">
                                    <img src="<?= $postImage ?>"
                                         alt="<?= htmlspecialchars($postTitle) ?>">

                                    <img src="<?= $postImage ?>"
                                         alt="<?= htmlspecialchars($postTitle) ?>">
                                </a>
                            </figure>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>

        </div>
    </div>
</section>
<?php endif; ?>

<section class="subscribe-section">
    <div class="auto-container">
        <div class="outer-box">
            <div class="content-box">
                <div class="shape-ten"></div>

                <div class="row">
                    <div class="content-column col-xl-5 col-lg-12 col-md-12 col-sm-12 wow fadeInLeft">
                        <div class="inner-column">
                            <div class="sub-title">
                                <?= t('Butuh partner event?', 'Need an event partner?') ?>
                            </div>

                            <h3 class="title">
                                <?= t(
                                    'Diskusikan kebutuhan event Anda bersama Iventlo',
                                    'Let’s discuss your event needs with Iventlo'
                                ) ?>
                            </h3>
                        </div>
                    </div>

                    <div class="form-column col-xl-7 col-lg-12 col-md-12 col-sm-12 wow fadeInRight"
                         data-wow-delay="200ms">

                        <div class="inner-column">
                            <div class="subscribe-form">

                                <form method="post" action="<?= frontUrl('contact-send') ?>">
                                    <div class="form-group">
                                        <input
                                            type="email"
                                            name="email"
                                            placeholder="<?= t(
                                                'Masukkan email Anda',
                                                'Enter your email address'
                                            ) ?>"
                                            required
                                        >

                                        <button type="submit"
                                                class="theme-btn btn-style-one bg-yellow">
                                            <span class="btn-title">
                                                <?= t('Kirim inquiry', 'Send inquiry') ?>
                                            </span>
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
