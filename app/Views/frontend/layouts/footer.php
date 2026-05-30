<?php
$companyName = website_setting('company_name') ?: 'Iventlo Event Organizer';
$tagline = t(
    website_setting('tagline') ?: 'Iventlo Event Organizer membantu merancang dan mengeksekusi event yang rapi, berkesan, dan sesuai tujuan acara Anda.',
    'Iventlo Event Organizer plans and delivers well-organized, memorable events aligned with your objectives.'
);
$footerDescription = t(
    'Partner event organizer untuk acara korporat, gathering, launching, seminar, expo, wedding, dan momen spesial yang membutuhkan perencanaan matang. Iventlo membantu dari penyusunan konsep, kebutuhan teknis, koordinasi vendor, produksi lapangan, registrasi peserta, dokumentasi, hingga laporan pasca acara agar setiap detail berjalan rapi dan berkesan.',
    'An event organizer partner for corporate events, gatherings, launches, seminars, expos, weddings, and special moments that need thoughtful planning. Iventlo supports concept development, technical preparation, vendor coordination, field production, participant registration, documentation, and post-event reporting so every detail runs smoothly and memorably.'
);

$siteLogo = website_setting('logo');
$siteLogoWhite = website_setting('logo_white');

$sitePhone = website_setting('phone') ?: '+62 812-3456-7890';
$siteWhatsapp = website_setting('whatsapp') ?: $sitePhone;
$siteEmail = website_setting('email') ?: 'hello@iventlo.com';
$siteAddress = website_setting('address') ?: 'Indonesia';

$siteInstagram = website_setting('instagram');
$siteFacebook = website_setting('facebook');
$siteLinkedin = website_setting('linkedin');
$siteYoutube = website_setting('youtube');
$siteTiktok = website_setting('tiktok');

$logoSrc = !empty($siteLogoWhite) ? uploadAsset($siteLogoWhite) : (!empty($siteLogo) ? uploadAsset($siteLogo) : frontAsset('images/logo.svg'));
$phoneClean = preg_replace('/[^0-9+]/', '', $sitePhone);
$whatsappClean = preg_replace('/[^0-9]/', '', $siteWhatsapp);

if (str_starts_with($whatsappClean, '0')) {
    $whatsappClean = '62' . substr($whatsappClean, 1);
}

$whatsappMessage = rawurlencode(t(
    'Halo Iventlo, saya ingin konsultasi kebutuhan event.',
    'Hello Iventlo, I would like to discuss an event requirement.'
));
$whatsappUrl = $whatsappClean !== ''
    ? 'https://wa.me/' . $whatsappClean . '?text=' . $whatsappMessage
    : '';

$footerPortfolios = array_slice(array_values(array_filter(
    $footerPortfolios ?? [],
    static function ($portfolio) {
        return !empty($portfolio['thumbnail']) || !empty($portfolio['cover_image']);
    }
)), 0, 6);
?>

<footer class="main-footer footer-style-one">
    <div class="bg bg-image" style="background-image: url('<?= frontAsset('images/background/3.jpg') ?>');"></div>
    <div class="shape-eleven bounce-y"></div>
    <div class="shape-twelve bounce-y"></div>

    <div class="widgets-section">
        <div class="auto-container">
            <div class="row">

                <div class="footer-column col-xl-3 col-lg-6 col-md-6 col-sm-6">
                    <div class="footer-widget about-widget">
                        <div class="widget-content">
                            <div class="logo">
                                <a href="<?= frontUrl('home') ?>">
                                    <img src="<?= $logoSrc ?>" alt="<?= htmlspecialchars($companyName) ?>">
                                </a>
                            </div>

                            <div class="text">
                                <?= htmlspecialchars($footerDescription) ?>
                            </div>

                            <ul class="social-icon-one">
                                <?php if (!empty($siteInstagram)): ?>
                                    <li><a href="<?= htmlspecialchars(safeLinkUrl($siteInstagram)) ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a></li>
                                <?php endif; ?>

                                <?php if (!empty($siteFacebook)): ?>
                                    <li><a href="<?= htmlspecialchars(safeLinkUrl($siteFacebook)) ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook-f"></i></a></li>
                                <?php endif; ?>

                                <?php if (!empty($siteTiktok)): ?>
                                    <li><a href="<?= htmlspecialchars(safeLinkUrl($siteTiktok)) ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-tiktok"></i></a></li>
                                <?php endif; ?>

                                <?php if (!empty($siteYoutube)): ?>
                                    <li><a href="<?= htmlspecialchars(safeLinkUrl($siteYoutube)) ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-youtube"></i></a></li>
                                <?php endif; ?>

                                <?php if (!empty($siteLinkedin)): ?>
                                    <li><a href="<?= htmlspecialchars(safeLinkUrl($siteLinkedin)) ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-linkedin-in"></i></a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="footer-column col-xl-3 col-lg-6 col-md-6 col-sm-6">
                    <div class="footer-widget links-widget">
	                        <h3 class="widget-title"><?= t('Tautan cepat', 'Quick links') ?></h3>

                        <div class="widget-content">
                            <ul class="user-links">
                                <li><i class="fa fa-angle-double-right"></i><a href="<?= frontUrl('home') ?>"><?= t('Beranda', 'Home') ?></a></li>
	                                <li><i class="fa fa-angle-double-right"></i><a href="<?= frontUrl('about') ?>"><?= t('Tentang kami', 'About us') ?></a></li>
                                <li><i class="fa fa-angle-double-right"></i><a href="<?= frontUrl('services') ?>"><?= t('Layanan', 'Services') ?></a></li>
                                <li><i class="fa fa-angle-double-right"></i><a href="<?= frontUrl('products') ?>"><?= t('Produk', 'Products') ?></a></li>
	                                <li><i class="fa fa-angle-double-right"></i><a href="<?= frontUrl('events') ?>"><?= t('Event & tiket', 'Events & tickets') ?></a></li>
	                                <li><i class="fa fa-angle-double-right"></i><a href="<?= isPublicMember() ? frontUrl('member-dashboard') : frontUrl('member-login') ?>"><?= isPublicMember() ? t('Tiket saya', 'My tickets') : t('Masuk peserta', 'Participant sign in') ?></a></li>
	                                <li><i class="fa fa-angle-double-right"></i><a href="<?= frontUrl('vendor-register') ?>"><?= t('Vendor', 'Vendor') ?></a></li>
                                <li><i class="fa fa-angle-double-right"></i><a href="<?= frontUrl('portfolio') ?>"><?= t('Portfolio', 'Portfolio') ?></a></li>
                                <li><i class="fa fa-angle-double-right"></i><a href="<?= frontUrl('blog') ?>"><?= t('Artikel', 'Blog') ?></a></li>
                                <li><i class="fa fa-angle-double-right"></i><a href="<?= frontUrl('contact') ?>"><?= t('Kontak', 'Contact') ?></a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="footer-column col-xl-6 col-lg-12 col-md-12 col-sm-12">
                    <div class="footer-widget gallery-widget">
                        <h3 class="widget-title"><?= t('Galeri', 'Gallery') ?></h3>

                        <div class="widget-content">
                            <div class="swiper gallery-swiper">
                                <div class="insta-gallery swiper-wrapper">
                                    <?php foreach ($footerPortfolios as $galleryItem): ?>
                                        <?php
                                        $galleryImage = !empty($galleryItem['thumbnail']) ? $galleryItem['thumbnail'] : ($galleryItem['cover_image'] ?? '');
                                        $galleryTitle = t($galleryItem['title_id'] ?? 'Portfolio Event Iventlo', $galleryItem['title_en'] ?? 'Iventlo Event Portfolio');
                                        ?>
                                        <figure class="image swiper-slide">
                                            <a href="<?= uploadAsset($galleryImage) ?>" data-rel="lightcase" title="<?= htmlspecialchars($galleryTitle) ?>">
                                                <img src="<?= uploadAsset($galleryImage) ?>" alt="<?= htmlspecialchars($galleryTitle) ?>">
                                            </a>
                                        </figure>
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

                        <div class="contact-widget">
                            <h3 class="widget-title"><?= t('Informasi', 'Information') ?></h3>

                            <div class="widget-content">
                                <div class="contact-list-box">
                                    <ul class="contact-list-two light">
                                        <li>
                                            <i class="fa fa-map-marker-alt"></i>
                                            <?= nl2br(htmlspecialchars($siteAddress)) ?>
                                        </li>
                                    </ul>

                                    <ul class="contact-list-two two light">
                                        <li>
                                            <i class="fa fa-envelope"></i>
                                            <a href="mailto:<?= htmlspecialchars($siteEmail) ?>">
                                                <?= htmlspecialchars($siteEmail) ?>
                                            </a>
                                        </li>

                                        <li>
                                            <i class="fa fa-phone"></i>
                                            <a href="tel:<?= htmlspecialchars($phoneClean) ?>">
                                                <?= htmlspecialchars($sitePhone) ?>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="auto-container">
            <div class="inner-container">
                <div class="copyright-text">
	                    © <?= date('Y') ?> <?= htmlspecialchars($companyName) ?>. <?= t('Seluruh hak cipta dilindungi.', 'All rights reserved.') ?>
                </div>
            </div>
        </div>
    </div>
</footer>

<div class="page-social-icon-box">
    <ul class="social-icon-three">
        <?php if (!empty($siteInstagram)): ?>
            <li><a href="<?= htmlspecialchars(safeLinkUrl($siteInstagram)) ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a></li>
        <?php endif; ?>

        <?php if (!empty($siteFacebook)): ?>
            <li><a href="<?= htmlspecialchars(safeLinkUrl($siteFacebook)) ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook-f"></i></a></li>
        <?php endif; ?>

        <?php if (!empty($siteTiktok)): ?>
            <li><a href="<?= htmlspecialchars(safeLinkUrl($siteTiktok)) ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-tiktok"></i></a></li>
        <?php endif; ?>

        <?php if (!empty($siteYoutube)): ?>
            <li><a href="<?= htmlspecialchars(safeLinkUrl($siteYoutube)) ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-youtube"></i></a></li>
        <?php endif; ?>

        <?php if (!empty($siteLinkedin)): ?>
            <li><a href="<?= htmlspecialchars(safeLinkUrl($siteLinkedin)) ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-linkedin-in"></i></a></li>
        <?php endif; ?>
    </ul>
</div>

<?php if (!empty($whatsappUrl)): ?>
    <a
        class="iventlo-whatsapp-float"
        href="<?= htmlspecialchars($whatsappUrl) ?>"
        target="_blank"
        rel="noopener noreferrer"
        aria-label="<?= t('Hubungi Iventlo melalui WhatsApp', 'Contact Iventlo on WhatsApp') ?>"
    >
        <i class="fab fa-whatsapp"></i>
        <span><?= t('Hubungi kami', 'Chat with us') ?></span>
    </a>
<?php endif; ?>

</div>

<div class="progress-wrap">
    <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
        <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
    </svg>
</div>

<script src="<?= frontAsset('js/jquery.js') ?>"></script>
<script src="<?= frontAsset('js/popper.min.js') ?>"></script>
<script src="<?= frontAsset('js/bootstrap.min.js') ?>"></script>
<script src="<?= frontAsset('js/jquery.fancybox.js') ?>"></script>
<script src="<?= frontAsset('js/jquery.countdown.js') ?>"></script>
<script src="<?= frontAsset('js/wow.js') ?>"></script>
<script src="<?= frontAsset('js/appear.js') ?>"></script>
<script src="<?= frontAsset('js/lightcase.js') ?>"></script>
<script src="<?= frontAsset('js/swipper.min.js') ?>"></script>
<script src="<?= frontAsset('js/backtotop.js') ?>"></script>
<script src="<?= frontAsset('js/gsap.min.js') ?>"></script>
<script src="<?= frontAsset('js/ScrollTrigger.min.js') ?>"></script>
<script src="<?= frontAsset('js/splitType.js') ?>"></script>
<script src="<?= frontAsset('js/SplitText.min.js') ?>"></script>
<script src="<?= frontAsset('js/script.js') ?>"></script>
<script src="<?= frontAsset('js/color-settings.js') ?>"></script>

</body>
</html>
