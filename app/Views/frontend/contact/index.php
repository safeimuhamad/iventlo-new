
<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php
$whatsappClean = preg_replace('/\D+/', '', $siteWhatsapp ?? $sitePhone);
$whatsappClean = str_starts_with($whatsappClean, '0') ? '62' . substr($whatsappClean, 1) : $whatsappClean;
$whatsappUrl = 'https://wa.me/' . $whatsappClean . '?text=' . rawurlencode(t(
    'Halo Iventlo, saya ingin berkonsultasi mengenai kebutuhan event.',
    'Hello Iventlo, I would like to discuss my event requirements.'
));
$iventloOfficeAddress = '18 Office Park, Jl. TB Simatupang No.18, RT.2/RW.1, Kebagusan, Kec. Ps. Minggu, Kota Jakarta Selatan, DKI Jakarta 12520';
$displayAddress = trim((string) $siteAddress);
$mapAddress = $displayAddress !== '' && strtolower($displayAddress) !== 'indonesia'
    ? $displayAddress
    : $iventloOfficeAddress;
if ($displayAddress === '' || strtolower($displayAddress) === 'indonesia') {
    $siteAddress = $iventloOfficeAddress;
}
$mapSetting = trim((string) website_setting('google_map'));

if (preg_match('/<iframe[^>]+src=["\']([^"\']+)["\']/i', $mapSetting, $match)) {
    $mapSetting = html_entity_decode($match[1], ENT_QUOTES, 'UTF-8');
}

$mapSource = str_starts_with($mapSetting, 'https://www.google.com/maps/embed')
    || str_starts_with($mapSetting, 'https://maps.google.com/maps?')
    ? $mapSetting
    : 'https://maps.google.com/maps?q=' . rawurlencode($mapAddress) . '&z=17&output=embed';
?>

<section class="page-title" style="background-image: url('<?= frontAsset('images/background/8.jpg') ?>');">
    <div class="shape-thirtyfour"></div>
    <div class="shape-thirtyfive"></div>
    <div class="shape-thirtysix bounce-y"></div>
    <div class="shape-thirtyseven bounce-x"></div>

    <div class="auto-container">
        <div class="inner-container">
	            <h1 class="title"><?= t('Hubungi Kami', 'Contact Us') ?></h1>

            <div class="breadcrumb-marquee">
                <?php for ($i = 1; $i <= 10; $i++): ?>
                    <span class="title-two">
	                        <?= t('Hubungi kami . Beranda', 'Contact us . Home') ?>
                    </span>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</section>

<section class="contact-section">
    <div class="auto-container">
        <div class="row">

            <div class="info-column col-xl-5 col-lg-12 col-md-12 col-sm-12">
                <div class="inner-column">
                    <div class="sec-title">
	                        <span class="sub-title"><?= t('Kontak Iventlo', 'Contact Iventlo') ?></span>
                        <h2 class="text-reveal-anim">
                            <?= t(
	                                'Diskusikan kebutuhan event Anda bersama kami',
	                                'Discuss your event needs with us'
                            ) ?>
                        </h2>
                        <div class="text">
                            <?= t(
                                'Sampaikan kebutuhan acara Anda, mulai dari corporate event, wedding, gathering, seminar, launching, hingga creative production.',
                                'Tell us about your event needs, from corporate events, weddings, gatherings, seminars, launches, to creative production.'
                            ) ?>
                        </div>
                    </div>

                    <ul class="contact-list-one">
                        <li>
                            <i class="icon lnr-icon-phone-handset"></i>
	                            <span class="title"><?= t('Telepon / WhatsApp', 'Phone / WhatsApp') ?></span>
                            <div class="text">
                                <a href="tel:<?= htmlspecialchars($phoneClean) ?>"><?= htmlspecialchars($sitePhone) ?></a>
                            </div>
                        </li>

                        <li>
                            <i class="icon lnr-icon-envelope1"></i>
                            <span class="title"><?= t('Email', 'Email') ?></span>
                            <div class="text">
                                <a href="mailto:<?= htmlspecialchars($siteEmail) ?>"><?= htmlspecialchars($siteEmail) ?></a>
                            </div>
                        </li>

                        <li>
                            <i class="icon lnr-icon-map-marker"></i>
                            <span class="title"><?= t('Lokasi', 'Location') ?></span>
                            <div class="text"><?= nl2br(htmlspecialchars($siteAddress)) ?></div>
                        </li>
                    </ul>

                    <div class="btn-box mt-4">
                        <a href="<?= htmlspecialchars($whatsappUrl) ?>" target="_blank" rel="noopener noreferrer" class="theme-btn btn-style-one bg-yellow">
	                            <span class="btn-title"><?= t('Chat WhatsApp', 'Chat on WhatsApp') ?></span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="form-column col-xl-7 col-lg-12 col-md-12 col-sm-12">
                <div class="inner-column">
                    <div class="contact-form">
                        <?php if (!empty($_SESSION['success'])): ?>
                        <div class="alert alert-success">
                            <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($_SESSION['error'])): ?>
                            <div class="alert alert-danger">
                                <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                            </div>
                        <?php endif; ?>
                        <form method="post" action="<?= frontUrl('contact-send') ?>">
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12">
	                                    <input type="text" name="name" placeholder="<?= t('Nama lengkap', 'Full name') ?>" required>
                                </div>

                                <div class="form-group col-lg-6 col-md-6 col-sm-12">
                                    <input type="email" name="email" placeholder="<?= t('Email', 'Email') ?>" required>
                                </div>

                                <div class="form-group col-lg-6 col-md-6 col-sm-12">
	                                    <input type="text" name="phone" placeholder="<?= t('No. WhatsApp', 'WhatsApp number') ?>" required>
                                </div>

                                <div class="form-group col-lg-6 col-md-6 col-sm-12">
	                                    <input type="text" name="company_name" placeholder="<?= t('Nama perusahaan / instansi', 'Company / organization') ?>">
                                </div>

                                <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                    <select name="service_interest" required>
	                                        <option value=""><?= t('Pilih kebutuhan event', 'Choose event need') ?></option>
	                                        <option value="Corporate Event">Corporate event</option>
	                                        <option value="Wedding Organizer">Wedding organizer</option>
	                                        <option value="Gathering & Outing">Gathering & outing</option>
	                                        <option value="Seminar & Conference">Seminar & conference</option>
	                                        <option value="Product Launching">Product launching</option>
	                                        <option value="Creative Production">Creative production</option>
                                    </select>
                                </div>

                                <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                    <textarea name="message" placeholder="<?= t('Ceritakan kebutuhan event Anda', 'Tell us about your event needs') ?>" required></textarea>
                                </div>

                                <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                    <button type="submit" class="theme-btn btn-style-one bg-yellow">
	                                        <span class="btn-title"><?= t('Kirim inquiry', 'Send inquiry') ?></span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="map-section">
    <div class="auto-container">
        <div class="map-outer">
            <iframe
                src="<?= htmlspecialchars($mapSource) ?>"
                title="<?= htmlspecialchars(t('Peta lokasi Iventlo', 'Iventlo location map')) ?>"
                width="100%"
                height="450"
                style="border:0;"
                allowfullscreen=""
                referrerpolicy="no-referrer-when-downgrade"
                loading="lazy">
            </iframe>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
