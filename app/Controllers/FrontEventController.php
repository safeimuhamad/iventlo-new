<?php

class FrontEventController extends Controller
{
    public function index()
    {
        $this->frontView('frontend/events/index', [
            'title' => t('Event & tiket - Iventlo Event Organizer', 'Events & tickets - Iventlo Event Organizer'),
            'meta_description' => t(
                'Temukan event publik Iventlo, lihat jadwal acara, venue, kuota peserta, dan pesan tiket event berbayar secara online.',
                'Discover Iventlo public events, view schedules, venues, participant quotas, and reserve tickets for paid events online.'
            ),
            'meta_keywords' => 'event berbayar, tiket event, event organizer, seminar, expo, Iventlo',
            'events' => (new EventTicket())->publicEvents()
        ]);
    }

    public function show()
    {
        $event = $this->publishedEvent();

        $this->frontView('frontend/events/detail', [
            'title' => t($event['title'], $event['title_en'] ?: $event['title']) . ' | Iventlo',
            'meta_description' => t($event['description'], $event['description_en'] ?: $event['description']),
            'og_image' => !empty($event['cover_image']) ? uploadAsset($event['cover_image']) : '',
            'event' => $event
        ]);
    }

    public function purchase()
    {
        $event = $this->publishedEvent();
        $slug = isEnglish() ? ($event['public_slug_en'] ?: $event['public_slug']) : $event['public_slug'];

        if (!isPublicMember()) {
            header('Location: ' . frontUrl('member-login'));
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim((string) ($_SESSION['name'] ?? ''));
            $email = filter_var(trim((string) ($_SESSION['email'] ?? '')), FILTER_VALIDATE_EMAIL);
            $phone = trim($_POST['buyer_phone'] ?? '');

            if ($name === '' || !$email || $phone === '') {
                $_SESSION['front_error'] = t('Nomor WhatsApp wajib diisi.', 'WhatsApp number is required.');
            } else {
                $order = (new EventTicket())->createOrder($event, [
                    'buyer_name' => $name,
                    'buyer_email' => $email,
                    'buyer_phone' => $phone,
                    'quantity' => $_POST['quantity'] ?? 1
                ], (int) $_SESSION['user_id']);

                if ($order) {
                    $this->frontView('frontend/events/confirmation', [
                        'title' => t('Konfirmasi pemesanan tiket', 'Ticket order confirmation'),
                        'event' => $event,
                        'order' => $order
                    ]);
                    return;
                }

                $_SESSION['front_error'] = t('Kuota tidak tersedia atau penjualan tiket sudah ditutup.', 'Tickets are unavailable or sales have been closed.');
            }
        }

        $this->frontView('frontend/events/purchase', [
            'title' => t('Beli tiket', 'Buy tickets') . ' - ' . t($event['title'], $event['title_en'] ?: $event['title']),
            'event' => $event,
            'memberName' => $_SESSION['name'] ?? '',
            'memberEmail' => $_SESSION['email'] ?? '',
            'frontError' => $_SESSION['front_error'] ?? null
        ]);
        unset($_SESSION['front_error']);
    }

    private function publishedEvent()
    {
        $slug = trim((string) ($_GET['slug'] ?? ''));
        $event = (new EventTicket())->findPublishedBySlug($slug);

        if (!$event) {
            http_response_code(404);
            echo '404 - Event tidak ditemukan';
            exit;
        }

        return $event;
    }
}
