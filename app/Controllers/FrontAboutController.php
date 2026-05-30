<?php


class FrontAboutController extends Controller
{
    public function index()
    {
        $aboutModel = new WebsiteAbout();
        $testimonialModel = new WebsiteTestimonial();
        $faqModel = new WebsiteFaq();
        $portfolioModel = new WebsitePortfolio();

        $about = $aboutModel->first();

        $this->frontView('frontend/about/index', [
            'title' => t('Tentang kami - Iventlo Event Organizer', 'About us - Iventlo Event Organizer'),
            'meta_description' => t(
                'Tentang Iventlo Event Organizer, partner profesional untuk corporate event, wedding, gathering, seminar, launching, dan creative production.',
                'About Iventlo Event Organizer, a professional partner for corporate events, weddings, gatherings, seminars, launches, and creative production.'
            ),
            'meta_keywords' => 'tentang iventlo, event organizer, corporate event, wedding organizer',

            'about' => $about,
            'testimonials' => $testimonialModel->active(),
            'faqs' => $faqModel->active(),
            'portfolios' => $portfolioModel->active()
        ]);
    }
}
