<?php


class FrontBlogController extends Controller
{
    public function index()
    {
        $model = new WebsitePost();
        $q = trim($_GET['q'] ?? '');
        $limit = 3;
        $totalPosts = $model->countPublished($q);
        $totalPages = max(1, (int) ceil($totalPosts / $limit));
        $currentPage = max(1, (int) ($_GET['p'] ?? 1));
        $currentPage = min($currentPage, $totalPages);
        $offset = ($currentPage - 1) * $limit;

        $this->frontView('frontend/blog/index', [
            'title' => t('Artikel - Iventlo Event Organizer', 'Blog - Iventlo Event Organizer'),
            'meta_description' => t(
                'Artikel dan insight Iventlo seputar event organizer, corporate event, wedding organizer, gathering, launching, dan creative event.',
                'Articles and insights from Iventlo about event organizing, corporate events, wedding organizer, gatherings, launches, and creative events.'
            ),
            'meta_keywords' => 'artikel event organizer, tips event, corporate event, wedding organizer, product launching',
            'posts' => $model->publishedPaginated($limit, $offset, $q),
            'popularPosts' => $model->published(3),
            'articleCurrentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalPosts' => $totalPosts,
            'limit' => $limit,
            'q' => $q,
        ]);
    }

    public function detail()
    {
        $slug = $_GET['slug'] ?? '';

        $model = new WebsitePost();
        $post = $model->findBySlug($slug);

        if (!$post) {
            http_response_code(404);
            echo t('Artikel tidak ditemukan.', 'Article not found.');
            exit;
        }

        $postTitle = t($post['title_id'] ?? '', $post['title_en'] ?? '');
        $postExcerpt = t($post['excerpt_id'] ?? '', $post['excerpt_en'] ?? '');

        $this->frontView('frontend/blog/detail', [
            'title' => $postTitle . ' - Iventlo Event Organizer',
            'meta_description' => $postExcerpt ?: t(
                'Insight event dan panduan perencanaan dari Iventlo.',
                'Event insights and planning guides from Iventlo.'
            ),
            'meta_keywords' => $post['meta_keywords'] ?: 'event organizer, artikel event, Iventlo',

            'og_title' => $postTitle,
            'og_description' => $postExcerpt ?: t(
                'Insight event dan panduan perencanaan dari Iventlo.',
                'Event insights and planning guides from Iventlo.'
            ),
            'og_image' => !empty($post['featured_image'])
                ? uploadAsset($post['featured_image'])
                : frontAsset('images/resource/blog-single-1.jpg'),

            'post' => $post
        ]);
    }
}
