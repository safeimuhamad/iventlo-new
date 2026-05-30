<?php


class FrontHomeController extends Controller
{
    public function index()
    {
        $sliderModel = new WebsiteSlider();
        $serviceModel = new WebsiteService();
        $testimonialModel = new WebsiteTestimonial();
        $faqModel = new WebsiteFaq();
        $postModel = new WebsitePost();
        $aboutModel = new WebsiteAbout();
        $productModel = new WebsiteProduct();
        $portfolioModel = new WebsitePortfolio();

        $this->frontView('frontend/home/index', [
            'title' => t(
                website_setting('meta_title') ?: 'Iventlo Event Organizer | Corporate Event & Gathering',
                'Iventlo Event Organizer | Corporate Events & Gatherings'
            ),
            'meta_description' => t(
                website_setting('meta_description') ?: 'Iventlo Event Organizer profesional untuk corporate event, wedding, gathering, seminar, launching, dan creative event.',
                'Iventlo helps plan and deliver corporate events, gatherings, launches, and creative production professionally and systematically.'
            ),
            'meta_keywords' => website_setting('meta_keywords') ?: 'event organizer, event planner, corporate event',

            'sliders' => $sliderModel->active(),
            'services' => $serviceModel->active(),
            'testimonials' => $testimonialModel->active(),
            'faqs' => $faqModel->activeGeneralOrLatest(4),
            'posts' => $postModel->published(3),
            'about' => $aboutModel->first(),
            'products' => $productModel->active(),
            'portfolios' => $portfolioModel->active(),
            'publicEvents' => (new EventTicket())->upcomingPublic(3)
        ]);
    }
}
