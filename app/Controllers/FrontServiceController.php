<?php


class FrontServiceController extends Controller
{
    public function index()
    {
        $serviceModel = new WebsiteService();
        $testimonialModel = new WebsiteTestimonial();
        $faqModel = new WebsiteFaq();
        $portfolioModel = new WebsitePortfolio();

        $this->frontView('frontend/services/index', [
            'title' => t('Layanan - Iventlo Event Organizer', 'Services - Iventlo Event Organizer'),
            'meta_description' => t(
                'Layanan Iventlo Event Organizer untuk corporate event, wedding, gathering, seminar, launching, community event, dan creative production.',
                'Iventlo Event Organizer services for corporate events, weddings, gatherings, seminars, launches, community events, and creative production.'
            ),
            'meta_keywords' => 'layanan event organizer, corporate event, wedding organizer, seminar, gathering, product launching',

            'services' => $serviceModel->active(),
            'testimonials' => $testimonialModel->active(),
            'faqs' => $faqModel->active(),
            'portfolios' => $portfolioModel->active()
        ]);
    }

    public function show()
    {
        $slug = trim($_GET['slug'] ?? '');
        $service = (new WebsiteService())->findActiveBySlug($slug);

        if (!$service) {
            http_response_code(404);
            echo t('Layanan tidak ditemukan.', 'Service not found.');
            exit;
        }

        $serviceTitle = t($service['title_id'] ?? '', $service['title_en'] ?? '');
        $serviceDescription = t($service['description_id'] ?? '', $service['description_en'] ?? '');
        $serviceImage = !empty($service['image'])
            ? uploadAsset($service['image'])
            : uploadAsset('website/content/service-creative-production-live.jpg');
        $defaultMetaDescription = trim($serviceDescription . ' ' . t(
            'Konsultasikan kebutuhan konsep, produksi, operasional, dokumentasi, dan laporan event bersama tim Iventlo.',
            'Discuss concept, production, operations, documentation, and event reporting needs with the Iventlo team.'
        ));
        $metaTitle = trim((string) t($service['meta_title_id'] ?? '', $service['meta_title_en'] ?? ''));
        $metaDescription = trim((string) t($service['meta_description_id'] ?? '', $service['meta_description_en'] ?? ''));
        $metaKeywords = trim((string) t($service['meta_keywords_id'] ?? '', $service['meta_keywords_en'] ?? ''));
        $ogTitle = trim((string) t($service['og_title_id'] ?? '', $service['og_title_en'] ?? ''));
        $ogDescription = trim((string) t($service['og_description_id'] ?? '', $service['og_description_en'] ?? ''));
        $metaRobots = trim((string) ($service['meta_robots'] ?? ''));
        $serviceCategory = $service['title_id'] ?? '';
        $portfolioModel = new WebsitePortfolio();
        $testimonialModel = new WebsiteTestimonial();
        $faqModel = new WebsiteFaq();

        $this->frontView('frontend/services/detail', [
            'title' => $metaTitle ?: ($serviceTitle . ' - Iventlo Event Organizer'),
            'meta_description' => $metaDescription ?: $defaultMetaDescription,
            'meta_keywords' => $metaKeywords ?: ('layanan event organizer, ' . strtolower($serviceTitle) . ', Iventlo'),
            'meta_robots' => $metaRobots ?: 'index, follow, max-image-preview:large',
            'og_title' => $ogTitle ?: ($metaTitle ?: ($serviceTitle . ' - Iventlo Event Organizer')),
            'og_description' => $ogDescription ?: ($metaDescription ?: $defaultMetaDescription),
            'og_image' => $serviceImage,
            'service' => $service,
            'portfolios' => $portfolioModel->activeByCategory($serviceCategory, 6),
            'testimonials' => $testimonialModel->activeByCategory($serviceCategory, 3),
            'faqs' => $faqModel->activeByCategory($serviceCategory, 6),
        ]);
    }
}
