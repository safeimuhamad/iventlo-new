<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php
$serviceTitle = t($service['title_id'] ?? '', $service['title_en'] ?? '');
$serviceDescription = t($service['description_id'] ?? '', $service['description_en'] ?? '');
$serviceImage = !empty($service['image'])
    ? uploadAsset($service['image'])
    : uploadAsset('website/content/service-creative-production-live.jpg');
$slug = $service['slug'] ?? '';
$portfolios = $portfolios ?? [];
$testimonials = $testimonials ?? [];
$faqs = $faqs ?? [];

$serviceDetails = [
    'corporate-event' => [
        'intro_id' => 'Iventlo membantu perusahaan merancang corporate event yang rapi, representatif, dan selaras dengan tujuan komunikasi bisnis. Setiap acara disiapkan dari tahap konsep, rundown, kebutuhan teknis, koordinasi vendor, produksi, hingga evaluasi pasca acara.',
        'intro_en' => 'Iventlo helps companies deliver corporate events that are organized, representative, and aligned with business communication goals. Each event is prepared from concept, rundown, technical needs, vendor coordination, production, to post-event evaluation.',
        'scope_id' => ['Konsep acara, tema, dan experience flow.', 'Rundown, stage direction, dan koordinasi pengisi acara.', 'Kebutuhan venue, panggung, audio visual, lighting, dan multimedia.', 'Registrasi tamu, hospitality, dokumentasi, dan laporan pelaksanaan.'],
        'scope_en' => ['Event concept, theme, and experience flow.', 'Rundown, stage direction, and talent coordination.', 'Venue, stage, audiovisual, lighting, and multimedia requirements.', 'Guest registration, hospitality, documentation, and event report.'],
        'process_id' => ['Brief kebutuhan dan tujuan event.', 'Penyusunan konsep, budget, dan timeline kerja.', 'Produksi, koordinasi lapangan, dan quality control.', 'Pelaksanaan acara dan laporan evaluasi.'],
        'process_en' => ['Requirement and event objective briefing.', 'Concept, budget, and working timeline preparation.', 'Production, field coordination, and quality control.', 'Event execution and evaluation report.'],
    ],
    'gathering-outing' => [
        'intro_id' => 'Gathering dan outing dirancang untuk membangun kebersamaan tim melalui alur acara yang menyenangkan, aman, dan tetap terukur. Iventlo menyiapkan aktivitas, kebutuhan panggung, hiburan, konsumsi, transportasi, dokumentasi, dan koordinasi peserta.',
        'intro_en' => 'Gatherings and outings are designed to strengthen team connection through enjoyable, safe, and well-managed activities. Iventlo prepares activities, staging needs, entertainment, catering, transportation, documentation, and participant coordination.',
        'scope_id' => ['Konsep aktivitas indoor, outdoor, team building, atau gala dinner.', 'Koordinasi venue, konsumsi, transportasi, dan perlengkapan peserta.', 'MC, entertainment, games, awarding, dan dokumentasi.', 'Manajemen rundown agar flow acara tetap nyaman.'],
        'scope_en' => ['Indoor, outdoor, team building, or gala dinner activity concept.', 'Venue, catering, transportation, and participant equipment coordination.', 'MC, entertainment, games, awards, and documentation.', 'Rundown management to keep the event flow comfortable.'],
        'process_id' => ['Penentuan objektif gathering dan profil peserta.', 'Rekomendasi konsep, lokasi, serta aktivitas.', 'Persiapan operasional dan koordinasi vendor.', 'Eksekusi acara dengan tim lapangan.'],
        'process_en' => ['Gathering objective and participant profile mapping.', 'Concept, location, and activity recommendations.', 'Operational preparation and vendor coordination.', 'Event execution with the field team.'],
    ],
    'product-launching' => [
        'intro_id' => 'Product launching membutuhkan pengalaman brand yang kuat, detail panggung yang presisi, dan momen utama yang mudah diingat. Iventlo membantu menyusun konsep peluncuran, flow presentasi, multimedia, registrasi tamu, dokumentasi, dan kebutuhan publikasi acara.',
        'intro_en' => 'Product launches need a strong brand experience, precise staging, and memorable key moments. Iventlo supports launch concepts, presentation flow, multimedia, guest registration, documentation, and event publication needs.',
        'scope_id' => ['Konsep brand experience dan key visual acara.', 'Stage, lighting, multimedia, reveal moment, dan show flow.', 'Registrasi tamu, VIP handling, media area, dan hospitality.', 'Dokumentasi foto/video serta materi publikasi pasca acara.'],
        'scope_en' => ['Brand experience concept and event key visual.', 'Stage, lighting, multimedia, reveal moment, and show flow.', 'Guest registration, VIP handling, media area, and hospitality.', 'Photo/video documentation and post-event publication assets.'],
        'process_id' => ['Pendalaman produk, audiens, dan pesan utama.', 'Perancangan konsep peluncuran dan show direction.', 'Produksi teknis, rehearsal, dan final checking.', 'Eksekusi peluncuran dan distribusi dokumentasi.'],
        'process_en' => ['Product, audience, and key message discovery.', 'Launch concept and show direction planning.', 'Technical production, rehearsal, and final checking.', 'Launch execution and documentation delivery.'],
    ],
    'seminar-conference' => [
        'intro_id' => 'Seminar dan conference perlu berjalan informatif, tertib, dan nyaman untuk peserta maupun pembicara. Iventlo menyiapkan kebutuhan registrasi, panggung, audio visual, materi, signage, hospitality, hingga dokumentasi dan laporan kehadiran.',
        'intro_en' => 'Seminars and conferences need to run informatively, smoothly, and comfortably for participants and speakers. Iventlo prepares registration, staging, audiovisual, materials, signage, hospitality, documentation, and attendance reporting.',
        'scope_id' => ['Registrasi peserta, meja informasi, badge, dan check-in.', 'Stage setup, audio visual, display presentasi, dan operator teknis.', 'Koordinasi pembicara, moderator, MC, dan materi acara.', 'Dokumentasi, sertifikat, laporan peserta, dan evaluasi.'],
        'scope_en' => ['Participant registration, information desk, badges, and check-in.', 'Stage setup, audiovisual, presentation display, and technical operators.', 'Speaker, moderator, MC, and event material coordination.', 'Documentation, certificates, participant reports, and evaluation.'],
        'process_id' => ['Pemetaan format acara dan kebutuhan peserta.', 'Penyusunan rundown, registrasi, dan kebutuhan teknis.', 'Rehearsal, speaker handling, dan persiapan venue.', 'Pelaksanaan acara dan laporan pasca event.'],
        'process_en' => ['Event format and participant needs mapping.', 'Rundown, registration, and technical needs preparation.', 'Rehearsal, speaker handling, and venue preparation.', 'Event execution and post-event reporting.'],
    ],
    'wedding-private-event' => [
        'intro_id' => 'Untuk wedding dan private event, Iventlo membantu menghadirkan perayaan personal yang hangat, tertata, dan sesuai karakter client. Kami mendampingi konsep, dekorasi, vendor, rundown, hospitality, dokumentasi, dan koordinasi acara di hari pelaksanaan.',
        'intro_en' => 'For weddings and private events, Iventlo helps create personal celebrations that feel warm, organized, and aligned with the client’s character. We support concept, decor, vendors, rundown, hospitality, documentation, and day-of coordination.',
        'scope_id' => ['Konsep acara, moodboard, dekorasi, dan detail ambience.', 'Koordinasi venue, vendor, konsumsi, hiburan, dan dokumentasi.', 'Rundown keluarga, prosesi, tamu VIP, dan hospitality.', 'Tim lapangan untuk memastikan setiap detail berjalan sesuai rencana.'],
        'scope_en' => ['Event concept, moodboard, decor, and ambience details.', 'Venue, vendor, catering, entertainment, and documentation coordination.', 'Family rundown, procession, VIP guests, and hospitality.', 'Field team to ensure every detail runs as planned.'],
        'process_id' => ['Diskusi konsep dan preferensi personal client.', 'Kurasi vendor, budget, dan timeline persiapan.', 'Finalisasi rundown dan technical meeting.', 'Koordinasi pelaksanaan dan dokumentasi acara.'],
        'process_en' => ['Concept discussion and client preference discovery.', 'Vendor curation, budget, and preparation timeline.', 'Rundown finalization and technical meeting.', 'Event-day coordination and documentation.'],
    ],
    'creative-production' => [
        'intro_id' => 'Creative production membantu acara tampil lebih kuat melalui visual, panggung, konten, dan dokumentasi yang terarah. Iventlo menyiapkan kebutuhan produksi mulai dari desain visual, branding venue, multimedia, show element, hingga aset konten pasca acara.',
        'intro_en' => 'Creative production strengthens events through purposeful visuals, staging, content, and documentation. Iventlo prepares production needs from visual design, venue branding, multimedia, show elements, to post-event content assets.',
        'scope_id' => ['Desain visual, key visual, motion graphic, dan materi layar.', 'Stage branding, backdrop, signage, dan kebutuhan dekoratif.', 'Dokumentasi foto/video, highlight, dan konten media sosial.', 'Koordinasi produksi dengan vendor teknis dan creative crew.'],
        'scope_en' => ['Visual design, key visuals, motion graphics, and screen materials.', 'Stage branding, backdrop, signage, and decorative requirements.', 'Photo/video documentation, highlights, and social media content.', 'Production coordination with technical vendors and creative crew.'],
        'process_id' => ['Pendalaman pesan acara dan kebutuhan visual.', 'Pembuatan konsep kreatif dan daftar produksi.', 'Produksi aset, instalasi, dan rehearsal teknis.', 'Dokumentasi serta pengiriman final output.'],
        'process_en' => ['Event message and visual needs discovery.', 'Creative concept and production list preparation.', 'Asset production, installation, and technical rehearsal.', 'Documentation and final output delivery.'],
    ],
    'exhibition-expo' => [
        'intro_id' => 'Exhibition dan expo membutuhkan pengelolaan area, exhibitor, pengunjung, sponsor, dan operasional yang detail. Iventlo membantu menyiapkan flow pameran, booth, registrasi pengunjung, signage, kebutuhan tenant, dan koordinasi lapangan agar expo berjalan tertib.',
        'intro_en' => 'Exhibitions and expos require detailed management of areas, exhibitors, visitors, sponsors, and operations. Iventlo supports exhibition flow, booths, visitor registration, signage, tenant needs, and field coordination for an orderly expo.',
        'scope_id' => ['Layout area, booth, tenant, sponsor, dan titik registrasi.', 'Registrasi pengunjung, badge, flow masuk, dan informasi acara.', 'Signage, wayfinding, kebutuhan listrik, audio, dan operasional area.', 'Koordinasi exhibitor, crew, keamanan, dan laporan pelaksanaan.'],
        'scope_en' => ['Area layout, booths, tenants, sponsors, and registration points.', 'Visitor registration, badges, entry flow, and event information.', 'Signage, wayfinding, electrical, audio, and area operations.', 'Exhibitor, crew, security coordination, and event report.'],
        'process_id' => ['Pemetaan kebutuhan expo dan target pengunjung.', 'Perancangan layout, alur, dan kebutuhan exhibitor.', 'Persiapan operasional serta technical meeting.', 'Pengelolaan hari acara dan laporan pasca expo.'],
        'process_en' => ['Expo needs and visitor target mapping.', 'Layout, flow, and exhibitor requirements planning.', 'Operational preparation and technical meeting.', 'Event-day management and post-expo reporting.'],
    ],
    'event-registration-ticketing' => [
        'intro_id' => 'Event registration dan ticketing membantu penyelenggara mengelola peserta secara lebih rapi sejak pendaftaran hingga check-in di lokasi. Iventlo menyediakan dukungan registrasi online, ticketing, QR check-in, database peserta, verifikasi pembayaran, dan laporan kehadiran.',
        'intro_en' => 'Event registration and ticketing helps organizers manage participants neatly from registration to on-site check-in. Iventlo supports online registration, ticketing, QR check-in, participant databases, payment verification, and attendance reporting.',
        'scope_id' => ['Form registrasi online, kategori tiket, kuota, dan data peserta.', 'Konfirmasi pembayaran, e-ticket, QR Code, dan informasi peserta.', 'Check-in cepat menggunakan QR scan oleh petugas atau peserta.', 'Dashboard kuota, tiket terjual, peserta hadir, dan laporan event.'],
        'scope_en' => ['Online registration forms, ticket categories, quotas, and participant data.', 'Payment confirmation, e-ticket, QR Code, and participant information.', 'Fast check-in using QR scan by staff or participants.', 'Quota, sold tickets, attendee, and event reporting dashboard.'],
        'process_id' => ['Menentukan skema tiket dan data peserta yang dibutuhkan.', 'Menyiapkan halaman registrasi, pembayaran, dan e-ticket.', 'Testing QR check-in dan briefing petugas.', 'Monitoring penjualan tiket dan laporan kehadiran.'],
        'process_en' => ['Define ticket scheme and required participant data.', 'Prepare registration, payment, and e-ticket pages.', 'Test QR check-in and brief event staff.', 'Monitor ticket sales and attendance reports.'],
    ],
];

$detail = $serviceDetails[$slug] ?? [
    'intro_id' => 'Iventlo menyiapkan layanan event secara menyeluruh mulai dari konsep, perencanaan teknis, koordinasi vendor, produksi lapangan, hingga dokumentasi dan laporan pasca acara.',
    'intro_en' => 'Iventlo prepares end-to-end event services from concept, technical planning, vendor coordination, field production, to documentation and post-event reporting.',
    'scope_id' => ['Konsep dan kebutuhan acara.', 'Rundown, produksi, dan koordinasi lapangan.', 'Vendor, crew, dokumentasi, dan laporan event.'],
    'scope_en' => ['Event concept and requirements.', 'Rundown, production, and field coordination.', 'Vendors, crew, documentation, and event reporting.'],
    'process_id' => ['Brief kebutuhan.', 'Perencanaan dan produksi.', 'Pelaksanaan dan evaluasi.'],
    'process_en' => ['Requirement briefing.', 'Planning and production.', 'Execution and evaluation.'],
];

$intro = t($detail['intro_id'], $detail['intro_en']);
$scopeItems = isEnglish() ? $detail['scope_en'] : $detail['scope_id'];
$processItems = isEnglish() ? $detail['process_en'] : $detail['process_id'];
$planningDetails = [
    t(
        'Sebelum produksi berjalan, tim Iventlo melakukan pendalaman kebutuhan untuk memahami tujuan acara, karakter peserta, pesan utama, batasan teknis, serta ekspektasi pengalaman yang ingin dibangun. Tahap ini membantu memastikan setiap keputusan produksi memiliki alasan yang jelas dan tidak sekadar mengikuti template.',
        'Before production begins, the Iventlo team explores the event objectives, participant profile, key messages, technical constraints, and desired experience. This stage ensures every production decision has a clear purpose instead of simply following a template.'
    ),
    t(
        'Setelah konsep disetujui, kami menyusun timeline kerja, daftar kebutuhan, pembagian PIC, dan alur koordinasi. Seluruh kebutuhan utama seperti venue, vendor, materi acara, teknis panggung, registrasi, dokumentasi, hingga laporan dipetakan agar progres mudah dipantau.',
        'After the concept is approved, we prepare the working timeline, requirement list, PIC assignments, and coordination flow. Key needs such as venue, vendors, event materials, stage technicals, registration, documentation, and reports are mapped so progress is easy to monitor.'
    ),
    t(
        'Pada hari pelaksanaan, tim lapangan memastikan rundown berjalan sesuai rencana, perubahan situasi cepat ditangani, dan komunikasi antar pihak tetap terkendali. Client dapat fokus pada tamu, peserta, atau momen utama acara karena detail operasional ditangani oleh tim yang siap di lokasi.',
        'On event day, the field team ensures the rundown runs as planned, situation changes are handled quickly, and communication between parties stays controlled. Clients can focus on guests, participants, or the main event moments while operational details are handled by the on-site team.'
    ),
];
$deliverables = [
    t('Konsep acara dan rekomendasi kebutuhan berdasarkan objective client.', 'Event concept and requirement recommendations based on client objectives.'),
    t('Rundown kerja, timeline persiapan, dan koordinasi vendor atau crew terkait.', 'Working rundown, preparation timeline, and vendor or crew coordination.'),
    t('Dukungan pelaksanaan di lokasi dengan PIC yang jelas dan alur komunikasi terarah.', 'On-site execution support with clear PICs and structured communication flow.'),
    t('Dokumentasi dan laporan pasca acara sesuai ruang lingkup layanan yang disepakati.', 'Documentation and post-event reporting according to the agreed service scope.'),
];
$idealFor = [
    t('Perusahaan yang membutuhkan event rapi dan representatif.', 'Companies that need organized and representative events.'),
    t('Brand, komunitas, instansi, atau personal client dengan kebutuhan acara khusus.', 'Brands, communities, institutions, or personal clients with specific event needs.'),
    t('Client yang ingin proses persiapan lebih terarah tanpa kehilangan fleksibilitas.', 'Clients who want a more structured preparation process without losing flexibility.'),
];
?>

<section class="page-title" style="background-image: url('<?= frontAsset('images/background/9.jpg') ?>');">
    <div class="shape-thirtyfour"></div>
    <div class="shape-thirtyfive"></div>
    <div class="shape-thirtysix bounce-y"></div>
    <div class="shape-thirtyseven bounce-x"></div>

    <div class="auto-container">
        <div class="inner-container">
            <h1 class="title"><?= htmlspecialchars(sentenceCaseText($serviceTitle)) ?></h1>
            <div class="breadcrumb-marquee">
                <?php for ($i = 1; $i <= 8; $i++): ?>
	                    <span class="title-two"><?= htmlspecialchars(sentenceCaseText($serviceTitle)) ?> . Iventlo</span>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</section>

<section class="event-details service-detail-page">
    <div class="auto-container">
        <div class="image-box">
            <img src="<?= $serviceImage ?>" alt="<?= htmlspecialchars($serviceTitle) ?>">
        </div>

        <div class="content-box">
	            <span class="category"><?= t('Layanan Iventlo', 'Iventlo service') ?></span>
	            <h2><?= htmlspecialchars(sentenceCaseText($serviceTitle)) ?></h2>
            <div class="text">
                <p class="lead-text"><?= nl2br(htmlspecialchars($intro)) ?></p>
                <p>
                    <?= t(
                        'Lingkup layanan dapat disesuaikan dengan tujuan acara, skala peserta, lokasi, anggaran, dan pengalaman yang ingin Anda hadirkan.',
                        'The service scope can be tailored to your event goals, audience size, location, budget, and desired experience.'
                    ) ?>
                </p>
                <?php foreach ($planningDetails as $paragraph): ?>
                    <p><?= htmlspecialchars($paragraph) ?></p>
                <?php endforeach; ?>
            </div>

            <div class="service-detail-grid">
                <div class="service-detail-card">
                    <h3><?= t('Cakupan layanan', 'Service scope') ?></h3>
                    <ul>
                        <?php foreach ($scopeItems as $item): ?>
                            <li><?= htmlspecialchars($item) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="service-detail-card">
                    <h3><?= t('Alur kerja', 'Workflow') ?></h3>
                    <ul>
                        <?php foreach ($processItems as $item): ?>
                            <li><?= htmlspecialchars($item) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <div class="service-detail-note">
                <h3><?= t('Output yang Anda dapatkan', 'What you receive') ?></h3>
                <p>
                    <?= t(
                        'Setiap project disiapkan dengan timeline kerja, PIC yang jelas, koordinasi teknis, dan laporan pelaksanaan agar client dapat memantau progres dengan mudah dari awal hingga acara selesai.',
                        'Every project is prepared with a working timeline, clear PIC, technical coordination, and event reporting so clients can monitor progress easily from start to finish.'
                    ) ?>
                </p>
                <ul class="service-detail-checklist">
                    <?php foreach ($deliverables as $deliverable): ?>
                        <li><?= htmlspecialchars($deliverable) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="service-detail-note service-detail-note-light">
                <h3><?= t('Cocok untuk', 'Ideal for') ?></h3>
                <ul class="service-detail-checklist">
                    <?php foreach ($idealFor as $item): ?>
                        <li><?= htmlspecialchars($item) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="btn-box mt-4">
                <a href="<?= frontUrl('contact') ?>" class="theme-btn btn-style-one bg-yellow">
	                    <span class="btn-title"><?= t('Minta penawaran', 'Request a quote') ?></span>
                </a>
                <a href="<?= frontUrl('services') ?>" class="theme-btn btn-style-one">
	                    <span class="btn-title"><?= t('Semua layanan', 'All services') ?></span>
                </a>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($portfolios)): ?>
<section class="portfolio-section-two service-related-section">
    <div class="auto-container">
        <div class="sec-title text-center">
            <span class="sub-title"><?= t('Portfolio terkait', 'Related portfolio') ?></span>
            <h2 class="text-reveal-anim">
                <?= htmlspecialchars(t(
                    'Contoh project ' . sentenceCaseText($serviceTitle),
                    sentenceCaseText($serviceTitle) . ' project examples'
                )) ?>
            </h2>
        </div>

        <div class="row">
            <?php foreach ($portfolios as $portfolio): ?>
                <?php
                $portfolioImagePath = !empty($portfolio['thumbnail'])
                    ? $portfolio['thumbnail']
                    : ($portfolio['cover_image'] ?? '');
                $portfolioImage = $portfolioImagePath !== ''
                    ? uploadAsset($portfolioImagePath)
                    : frontAsset('images/resource/gallery2-1.jpg');
                $portfolioTitle = t($portfolio['title_id'] ?? '', $portfolio['title_en'] ?? '');
                $portfolioSlug = current_lang() === 'en'
                    ? ($portfolio['slug_en'] ?? $portfolio['slug_id'])
                    : ($portfolio['slug_id'] ?? $portfolio['slug_en']);
                ?>

                <div class="news-block col-lg-4 col-md-6 col-sm-12">
                    <div class="inner-box">
                        <div class="image-box">
                            <figure class="image">
                                <a href="<?= frontUrl('portfolio-detail', ['slug' => $portfolioSlug]) ?>">
                                    <img src="<?= $portfolioImage ?>" alt="<?= htmlspecialchars($portfolioTitle) ?>">
                                </a>
                            </figure>
                        </div>
                        <div class="content-box">
                            <span class="post-info">
                                <?= htmlspecialchars(t($portfolio['category_id'] ?? '', $portfolio['category_en'] ?? '')) ?>
                            </span>
                            <h4 class="title">
                                <a href="<?= frontUrl('portfolio-detail', ['slug' => $portfolioSlug]) ?>">
                                    <?= htmlspecialchars(sentenceCaseText($portfolioTitle)) ?>
                                </a>
                            </h4>
                            <?php if (!empty($portfolio['client_name'])): ?>
                                <div class="text"><?= htmlspecialchars($portfolio['client_name']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($testimonials)): ?>
<section class="testimonial-section-two parallax-section">
    <div class="bg-box">
        <div class="parallax-bg bg bg-image" data-speed="0.5" style="background-image: url('<?= frontAsset('images/background/testimonial-iventlo-purple.png') ?>');"></div>
    </div>

    <div class="auto-container">
        <div class="sec-title-outer">
            <div class="sec-title light mb-0">
                <span class="sub-title"><?= t('Testimoni layanan', 'Service testimonials') ?></span>
                <h2 class="text-reveal-anim">
                    <?= htmlspecialchars(t(
                        'Apa kata client tentang ' . sentenceCaseText($serviceTitle),
                        'What clients say about ' . sentenceCaseText($serviceTitle)
                    )) ?>
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
                                    “<?= htmlspecialchars(t($testimonial['testimonial_id'] ?? '', $testimonial['testimonial_en'] ?? '')) ?>”
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
                                <path id="service-detail-faq-circle" d="M 50, 50m -37, 0a 37,37 0 1,1 74,0a 37,37 0 1,1 -74,0"/>
                            </defs>
                            <text>
                                <textPath xlink:href="#service-detail-faq-circle">
                                    <?= htmlspecialchars(t('Pertanyaan layanan Iventlo', 'Iventlo service questions')) ?>
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
                            <?= htmlspecialchars(t(
                                'FAQ ' . sentenceCaseText($serviceTitle),
                                sentenceCaseText($serviceTitle) . ' FAQ'
                            )) ?>
                        </h2>
                    </div>

                    <ul class="accordion-box">
                        <?php foreach ($faqs as $index => $faq): ?>
                            <li class="accordion block <?= $index === 0 ? 'active-tab' : '' ?>">
                                <div class="acc-btn <?= $index === 0 ? 'active' : '' ?>">
                                    <?= htmlspecialchars(t($faq['question_id'] ?? '', $faq['question_en'] ?? '')) ?>
                                    <i class="icon fa fa-plus"></i>
                                </div>

                                <div class="acc-content <?= $index === 0 ? 'current' : '' ?>">
                                    <div class="content">
                                        <div class="text">
                                            <?= nl2br(htmlspecialchars(t($faq['answer_id'] ?? '', $faq['answer_en'] ?? ''))) ?>
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

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
