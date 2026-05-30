<?php

class FrontContactController extends Controller
{
    public function index()
    {
        $this->frontView('frontend/contact/index', [
            'title' => t('Kontak - Iventlo Event Organizer', 'Contact - Iventlo Event Organizer'),
            'meta_description' => t(
                'Hubungi Iventlo Event Organizer untuk kebutuhan corporate event, wedding, gathering, seminar, launching, dan creative production.',
                'Contact Iventlo Event Organizer for corporate events, weddings, gatherings, seminars, launches, and creative production.'
            ),
            'meta_keywords' => 'kontak event organizer, iventlo contact, corporate event, wedding organizer'
        ]);
    }

    public function send()
    {
        if (!consumeRateLimit('contact_inquiry', 4, 600)) {
            $_SESSION['error'] = t(
                'Terlalu banyak pesan dikirim. Silakan coba kembali beberapa menit lagi.',
                'Too many messages have been submitted. Please try again in a few minutes.'
            );
            header('Location: ' . frontUrl('contact'));
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if ($name === '' || $email === '' || $message === '') {
            $_SESSION['error'] = t(
                'Nama, email, dan pesan wajib diisi.',
                'Name, email, and message are required.'
            );
            header('Location: ' . frontUrl('contact'));
            exit;
        }

        $model = new WebsiteInquiry();

        $model->create([
            'name' => $name,
            'email' => $email,
            'phone' => trim($_POST['phone'] ?? ''),
            'company_name' => trim($_POST['company_name'] ?? ''),
            'service_interest' => trim($_POST['service_interest'] ?? ''),
            'message' => $message
        ]);

        $_SESSION['success'] = t(
            'Inquiry berhasil dikirim. Tim Iventlo akan segera menghubungi Anda.',
            'Your inquiry has been submitted. The Iventlo team will contact you shortly.'
        );
        header('Location: ' . frontUrl('contact'));
        exit;
    }
}
