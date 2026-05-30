<?php


class FrontPortfolioController extends Controller
{
    public function index()
    {
        $model = new WebsitePortfolio();

        $this->frontView('frontend/portfolio/index', [
            'title' => t('Portfolio event - Iventlo', 'Event portfolio - Iventlo'),
            'meta_description' => t(
                'Portfolio event Iventlo untuk corporate event, launching, gathering, seminar, wedding, dan creative production.',
                'Iventlo event portfolio for corporate events, launches, gatherings, seminars, weddings, and creative production.'
            ),
            'meta_keywords' => 'portfolio event organizer, event Iventlo, corporate event, launching event',
            'portfolios' => $model->active()
        ]);
    }

    public function detail()
    {
        $slug = $_GET['slug'] ?? '';

        $model = new WebsitePortfolio();

        $portfolio = $model->findBySlug($slug);

        if (!$portfolio) {
            http_response_code(404);
            echo t('Portfolio tidak ditemukan.', 'Portfolio item not found.');
            exit;
        }

        $portfolioImagePath = !empty($portfolio['thumbnail'])
            ? $portfolio['thumbnail']
            : ($portfolio['cover_image'] ?? '');

        $this->frontView('frontend/portfolio/detail', [
            'title' => t(
                $portfolio['title_id'],
                $portfolio['title_en']
            ) . ' - Iventlo Event Organizer',
            'meta_description' => t($portfolio['description_id'] ?? '', $portfolio['description_en'] ?? ''),
            'og_image' => $portfolioImagePath !== '' ? uploadAsset($portfolioImagePath) : null,

            'portfolio' => $portfolio
        ]);
    }
}
