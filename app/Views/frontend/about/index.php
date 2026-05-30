<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<?php
$about = $about ?? [];
$testimonials = $testimonials ?? [];
$faqs = $faqs ?? [];
$portfolios = $portfolios ?? [];
$audienceSegments = frontAudienceSegments();

$aboutTitle = t(
    $about['title_id'] ?? '',
    $about['title_en'] ?? ''
);

$aboutDescription = t(
    $about['content_id'] ?? 'Iventlo membantu merencanakan dan melaksanakan acara secara profesional dari konsep hingga dokumentasi.',
    $about['content_en'] ?? 'Iventlo professionally plans and delivers events from concept through documentation.'
);

$aboutImage1 = !empty($about['image'])
    ? uploadAsset($about['image'])
    : frontAsset('images/resource/about1-1.png');

$aboutImage2 = !empty($about['image_2'])
    ? uploadAsset($about['image_2'])
    : frontAsset('images/resource/about1-2.png');
?>

<!-- Page Title -->
<section class="page-title" style="background-image: url('<?= frontAsset('images/background/8.jpg') ?>');">
    <div class="shape-thirtyfour"></div>
    <div class="shape-thirtyfive"></div>
    <div class="shape-thirtysix bounce-y"></div>
    <div class="shape-thirtyseven bounce-x"></div>

    <div class="auto-container">
        <div class="inner-container">
            <h1 class="title"><?= t('Tentang Kami', 'About Us') ?></h1>

            <div class="breadcrumb-marquee">
                <?php for ($i = 1; $i <= 10; $i++): ?>
                    <span class="title-two">
                        <?= t('Tentang kami . Beranda', 'About us . Home') ?>
                    </span>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</section>
<!-- End Page Title -->

<!-- About Section -->
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
                    <div class="count">360°</div>
                    <div class="text">
                        <?= t('Dukungan', 'Event') ?> <br>
                        <?= t('Event', 'Support') ?>
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
                    <span class="sub-title"><?= t('Tentang Iventlo', 'About Iventlo') ?></span>

                    <h2 class="text-reveal-anim">
                        <?= htmlspecialchars(sentenceCaseText($aboutTitle)) ?>
                    </h2>

                    <div class="text text-anim">
                        <?= nl2br(htmlspecialchars($aboutDescription)) ?>
                    </div>
                </div>

                <div class="btn-box">
                    <a href="<?= frontUrl('services') ?>" class="theme-btn btn-style-one icon-btn bg-yellow">
                        <i class="icon flaticon-tickets"></i>
	                        <span class="btn-title"><?= t('Lihat layanan', 'View services') ?></span>
                    </a>

                    <a href="<?= frontUrl('contact') ?>" class="theme-btn btn-style-one icon-btn">
                        <i class="icon flaticon-placeholder"></i>
	                        <span class="btn-title"><?= t('Hubungi kami', 'Contact us') ?></span>
                    </a>
                </div>
            </div>
        </div>

        <div class="outer-box show-xl">
            <div class="image-box wow fadeInUp">
                <figure class="image">
                    <img src="<?= $aboutImage2 ?>" alt="<?= htmlspecialchars($aboutTitle) ?>">
                </figure>
                <div class="speaker-box">
                    <i class="icon flaticon-mic"></i>
                    <div class="count">360°</div>
                    <div class="text">
                        <?= t('Dukungan', 'Event') ?> <br>
                        <?= t('Event', 'Support') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End About Section -->

<!-- Marquee Section -->
<div class="marquee-section">
    <div class="marquee-container">
        <div class="marquee">
            <?php
            $marquees = [
	                'Corporate event',
	                'Wedding organizer',
                'Gathering',
                'Seminar',
	                'Product launching',
	                'Creative event',
	                'Community event',
                'Conference'
            ];
            ?>

            <?php foreach ($marquees as $item): ?>
                <div class="text"><?= $item ?></div>
                <div class="text">*</div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<!-- End Marquee Section -->

<!-- Funfact Section -->
<section class="funfact-section">
    <div class="bg bg-pattern-7"></div>
    <div class="shape-thirtytwo bounce-y"></div>
    <div class="shape-thirtythree bounce-y"></div>

    <div class="auto-container">
        <div class="sec-title-outer">
            <div class="sec-title light mb-0">
	                <span class="sub-title"><?= t('Mengapa memilih kami', 'Why choose us') ?></span>
                <h2 class="text-reveal-anim">
                    <?= t(
	                            'Kami mengubah ide acara menjadi pengalaman nyata',
	                            'We turn event ideas into real experiences'
                    ) ?>
                </h2>
            </div>

            <div class="text-box">
                <div class="text">
                    <?= t(
                        'Dengan pendekatan yang terstruktur, kreatif, dan detail-oriented, Iventlo membantu client menyelenggarakan acara yang sesuai tujuan, tepat sasaran, dan meninggalkan kesan positif.',
                        'With a structured, creative, and detail-oriented approach, Iventlo helps clients organize events that meet objectives, reach the right audience, and leave a positive impression.'
                    ) ?>
                </div>

                <div class="btn-box">
                    <a href="<?= frontUrl('contact') ?>" class="theme-btn btn-style-one bg-orange">
	                        <span class="btn-title"><?= t('Diskusi event', 'Discuss your event') ?></span>
                    </a>
                </div>
            </div>
        </div>

        <div class="fact-counter">
            <div class="row">
                <div class="counter-block col-lg-3 col-md-6 col-sm-6 wow fadeInUp">
                    <div class="inner-box">
                        <div class="icon">
                            <img src="<?= frontAsset('images/resource/counter1-1.svg') ?>" alt="Event Completed">
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
                            <img src="<?= frontAsset('images/resource/counter1-2.svg') ?>" alt="Happy Clients">
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
                            <img src="<?= frontAsset('images/resource/counter1-3.svg') ?>" alt="Vendors">
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
                            <img src="<?= frontAsset('images/resource/counter1-4.svg') ?>" alt="Partners">
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
<!-- End Funfact Section -->

<!-- Feature Section -->
<section class="feature-section-two">
    <div class="auto-container">
        <div class="row">
            <div class="blocks-box col-xl-6 col-lg-12 col-md-12 col-sm-12">
                <div class="sec-title orange">
	                    <span class="sub-title"><?= t('Keunggulan kami', 'Our strengths') ?></span>
                    <h2 class="scrub-each-word text-split">
                        <?= t(
	                            'Pendekatan terarah untuk setiap acara',
	                            'A focused approach for every event'
                        ) ?>
                    </h2>
                </div>

                <div class="feature-block-two has-active wow fadeInUp">
                    <div class="inner-box">
                        <div class="icon-box">
                            <i class="icon flaticon-checked"></i>
                            <div class="icon-seven"></div>
                            <div class="icon-eight"></div>
                        </div>
                        <div class="content">
	                            <h5 class="title"><?= t('Perencanaan terstruktur', 'Structured planning') ?></h5>
                            <div class="text">
                                <?= t(
                                    'Kami menyusun kebutuhan event dari konsep, timeline, rundown, budget, hingga kebutuhan teknis.',
                                    'We organize event needs from concept, timeline, rundown, budget, to technical requirements.'
                                ) ?>
                            </div>
                            <a href="<?= frontUrl('services') ?>" class="read-more">
	                                <span><?= t('Selengkapnya', 'Read more') ?></span> <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="feature-block-two has-active wow fadeInUp">
                    <div class="inner-box">
                        <div class="icon-box">
                            <i class="icon flaticon-creativity"></i>
                            <div class="icon-seven"></div>
                            <div class="icon-eight"></div>
                        </div>
                        <div class="content">
	                            <h5 class="title"><?= t('Konsep kreatif', 'Creative concepts') ?></h5>
                            <div class="text">
                                <?= t(
                                    'Kami membantu merancang konsep visual dan pengalaman acara yang sesuai karakter brand.',
                                    'We help design visual concepts and event experiences that match your brand character.'
                                ) ?>
                            </div>
                            <a href="<?= frontUrl('services') ?>" class="read-more">
	                                <span><?= t('Selengkapnya', 'Read more') ?></span> <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="blocks-box col-xl-6 col-lg-12 col-md-12 col-sm-12">
                <div class="feature-block-two has-active wow fadeInUp">
                    <div class="inner-box">
                        <div class="icon-box">
                            <i class="icon flaticon-networking"></i>
                            <div class="icon-seven"></div>
                            <div class="icon-eight"></div>
                        </div>
                        <div class="content">
	                            <h5 class="title"><?= t('Koordinasi vendor', 'Vendor coordination') ?></h5>
                            <div class="text">
                                <?= t(
                                    'Kami mengatur komunikasi dan koordinasi vendor agar kebutuhan produksi berjalan lancar.',
                                    'We manage vendor communication and coordination to keep production needs running smoothly.'
                                ) ?>
                            </div>
                            <a href="<?= frontUrl('services') ?>" class="read-more">
	                                <span><?= t('Selengkapnya', 'Read more') ?></span> <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="feature-block-two has-active wow fadeInUp" data-wow-delay="200ms">
                    <div class="inner-box active">
                        <div class="icon-box">
                            <i class="icon flaticon-mic"></i>
                            <div class="icon-seven"></div>
                            <div class="icon-eight"></div>
                        </div>
                        <div class="content">
	                            <h5 class="title"><?= t('Eksekusi lapangan', 'On-site execution') ?></h5>
                            <div class="text">
                                <?= t(
                                    'Tim kami memastikan acara berjalan sesuai rundown, timeline, dan standar kualitas yang disepakati.',
                                    'Our team ensures the event follows the rundown, timeline, and agreed quality standards.'
                                ) ?>
                            </div>
                            <a href="<?= frontUrl('services') ?>" class="read-more">
	                                <span><?= t('Selengkapnya', 'Read more') ?></span> <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="feature-block-two has-active wow fadeInUp" data-wow-delay="200ms">
                    <div class="inner-box">
                        <div class="icon-box">
                            <i class="icon flaticon-art-show"></i>
                            <div class="icon-seven"></div>
                            <div class="icon-eight"></div>
                        </div>
                        <div class="content">
	                            <h5 class="title"><?= t('Dokumentasi acara', 'Event documentation') ?></h5>
                            <div class="text">
                                <?= t(
                                    'Kami mendukung dokumentasi foto dan video untuk kebutuhan publikasi maupun arsip perusahaan.',
                                    'We support photo and video documentation for publication needs and company archives.'
                                ) ?>
                            </div>
                            <a href="<?= frontUrl('services') ?>" class="read-more">
	                                <span><?= t('Selengkapnya', 'Read more') ?></span> <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Feature Section -->

<!-- Client Section -->
<section class="client-section-two">
    <div class="bg bg-pattern-5"></div>
    <div class="auto-container">
        <div class="sec-title text-center">
	                        <span class="sub-title"><?= t('Untuk berbagai acara', 'For diverse events') ?></span>
            <h2 class="text-reveal-anim">
	                            <?= t('Solusi visual untuk beragam pengalaman event', 'Visual solutions for diverse event experiences') ?>
            </h2>
        </div>

        <div class="row g-0">
            <?php foreach ($audienceSegments as $segment): ?>
                <div class="client-block-two col-lg-3 col-md-4 col-sm-6 wow fadeInUp">
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
    </div>
</section>
<!-- End Client Section -->

<!-- FAQ Section -->
<section class="faq-section pt-0">
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
                                    <?= t('Pertanyaan tentang layanan kami', 'Questions about our services') ?>
                                </textPath>
                            </text>
                        </svg>
                        <div class="logo">
                            <img src="<?= frontAsset('images/favicon.png') ?>" alt="FAQ">
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
	                                'Frequently asked questions about Iventlo'
                            ) ?>
                        </h2>
                    </div>

                    <ul class="accordion-box">
                        <?php
                        $faqs = [
                            [
                                'q_id' => 'Jenis event apa saja yang bisa ditangani Iventlo?',
                                'q_en' => 'What types of events can Iventlo handle?',
                                'a_id' => 'Kami menangani corporate event, wedding, gathering, seminar, conference, product launching, community event, hingga creative production.',
                                'a_en' => 'We handle corporate events, weddings, gatherings, seminars, conferences, product launches, community events, and creative productions.'
                            ],
                            [
                                'q_id' => 'Apakah Iventlo bisa membantu dari tahap konsep?',
                                'q_en' => 'Can Iventlo help from the concept stage?',
                                'a_id' => 'Ya. Kami dapat membantu mulai dari konsep, budgeting, vendor, rundown, produksi, eksekusi lapangan, hingga dokumentasi.',
                                'a_en' => 'Yes. We can help from concept, budgeting, vendor coordination, rundown, production, on-site execution, to documentation.'
                            ],
                            [
                                'q_id' => 'Apakah bisa request paket custom?',
                                'q_en' => 'Can I request a custom package?',
                                'a_id' => 'Bisa. Setiap event dapat disesuaikan berdasarkan kebutuhan, skala acara, lokasi, jumlah peserta, dan target pengalaman yang ingin dibuat.',
                                'a_en' => 'Yes. Every event can be customized based on needs, event scale, location, number of participants, and the desired experience.'
                            ],
                            [
                                'q_id' => 'Bagaimana cara mulai konsultasi?',
                                'q_en' => 'How do I start a consultation?',
                                'a_id' => 'Anda bisa menghubungi kami melalui halaman kontak dan menyampaikan kebutuhan acara, tanggal rencana, lokasi, serta gambaran konsep.',
                                'a_en' => 'You can contact us through the contact page and share your event needs, planned date, location, and concept overview.'
                            ],
                        ];
                        ?>

                        <?php foreach ($faqs as $index => $faq): ?>
                            <li class="accordion block <?= $index === 0 ? 'active-tab' : '' ?>">
                                <div class="acc-btn <?= $index === 0 ? 'active' : '' ?>">
                                    <?= t($faq['q_id'], $faq['q_en']) ?>
                                    <i class="icon fa fa-plus"></i>
                                </div>
                                <div class="acc-content <?= $index === 0 ? 'current' : '' ?>">
                                    <div class="content">
                                        <div class="text">
                                            <?= t($faq['a_id'], $faq['a_en']) ?>
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
<!-- End FAQ Section -->

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
