UPDATE website_settings
SET tagline = 'Partner event organizer untuk acara korporat, gathering, launching, dan momen spesial yang terencana dengan baik.',
    instagram = CASE WHEN instagram LIKE '%asdf%' THEN NULL ELSE instagram END,
    facebook = CASE WHEN facebook LIKE '%asdf%' THEN NULL ELSE facebook END,
    linkedin = CASE WHEN linkedin LIKE '%asdf%' THEN NULL ELSE linkedin END,
    youtube = CASE WHEN youtube LIKE '%asdf%' THEN NULL ELSE youtube END,
    tiktok = CASE WHEN tiktok LIKE '%asdf%' THEN NULL ELSE tiktok END,
    google_map = CASE WHEN google_map LIKE '%asdf%' THEN NULL ELSE google_map END,
    meta_title = 'Iventlo Event Organizer | Corporate Event, Gathering & Creative Production',
    meta_keywords = 'event organizer jakarta, corporate event, company gathering, product launching, creative production, iventlo',
    meta_description = 'Iventlo membantu merancang dan menjalankan corporate event, gathering, launching, serta creative production secara profesional dan terstruktur.'
WHERE id = 1;

UPDATE website_about
SET title_id = 'Partner Event Organizer untuk Acara yang Rapi dan Berkesan',
    title_en = 'Your Event Organizer Partner for Seamless and Memorable Events',
    content_id = 'PT Iventlo Mitra Acara mendampingi perusahaan, instansi, komunitas, dan brand dalam merancang acara dari konsep hingga pelaksanaan. Tim kami membantu menyusun kebutuhan, koordinasi vendor, produksi, operasional lapangan, dan dokumentasi agar setiap detail berjalan terarah.',
    content_en = 'PT Iventlo Mitra Acara supports companies, institutions, communities, and brands in planning events from concept to execution. Our team coordinates requirements, vendors, production, on-site operations, and documentation so every detail is delivered with purpose.',
    image = 'website/content/about-event-planning.jpg',
    image_2 = 'website/content/about-event-production-live.jpg',
    vision_id = 'Menjadi partner event terpercaya yang membantu setiap acara hadir dengan perencanaan matang, eksekusi profesional, dan pengalaman berkesan.',
    vision_en = 'To be a trusted event partner delivering thoughtful planning, professional execution, and memorable experiences.',
    mission_id = '<ul><li>Merancang konsep acara yang relevan dengan tujuan client.</li><li>Mengawal produksi dan operasional secara detail serta terukur.</li><li>Membangun kolaborasi responsif bersama vendor dan partner.</li><li>Menghadirkan pengalaman acara yang nyaman bagi peserta.</li></ul>',
    mission_en = '<ul><li>Design event concepts aligned with client objectives.</li><li>Manage production and operations with measured attention to detail.</li><li>Build responsive collaboration with vendors and partners.</li><li>Deliver enjoyable experiences for event audiences.</li></ul>'
WHERE id = 1;

UPDATE website_sliders
SET title_id = 'Wujudkan Event yang Berkesan',
    title_en = 'Create an Event Worth Remembering',
    subtitle_id = 'EVENT ORGANIZER PROFESIONAL',
    subtitle_en = 'PROFESSIONAL EVENT ORGANIZER',
    description_id = 'Corporate event, gathering, launching, seminar, dan creative production dengan perencanaan terstruktur.',
    description_en = 'Corporate events, gatherings, launches, seminars, and creative production with structured planning.',
    button_text_id = 'Konsultasi Event',
    button_text_en = 'Plan Your Event'
WHERE id = 1;

UPDATE website_sliders
SET title_id = 'Dari Konsep hingga Hari Acara',
    title_en = 'From Concept to Event Day',
    subtitle_id = 'FULL-SERVICE EVENT PARTNER',
    subtitle_en = 'FULL-SERVICE EVENT PARTNER',
    description_id = 'Kami mendampingi kebutuhan venue, vendor, produksi, rundown, crew, dan dokumentasi.',
    description_en = 'We support venue needs, vendors, production, rundown, crew, and documentation.',
    button_text_id = 'Lihat Layanan',
    button_text_en = 'Explore Services'
WHERE id = 2;

INSERT INTO website_services
    (title_id, title_en, description_id, description_en, icon, image, slug, sort_order, status)
SELECT 'Corporate Event', 'Corporate Events',
       'Konsep dan produksi acara perusahaan seperti anniversary, town hall, awarding, dan meeting besar.',
       'Concept and production for company anniversaries, town halls, awards, and large meetings.',
       'fas fa-building', 'website/content/service-corporate-live.jpg', 'corporate-event', 1, 'active'
WHERE NOT EXISTS (SELECT 1 FROM website_services WHERE slug = 'corporate-event');

INSERT INTO website_services
    (title_id, title_en, description_id, description_en, icon, image, slug, sort_order, status)
SELECT 'Gathering & Outing', 'Gathering & Outing',
       'Program kebersamaan tim dengan aktivitas, panggung, hiburan, konsumsi, dan alur acara yang tertata.',
       'Team bonding programs with activities, staging, entertainment, catering, and an organized event flow.',
       'fas fa-users', 'website/content/service-gathering-live.webp', 'gathering-outing', 2, 'active'
WHERE NOT EXISTS (SELECT 1 FROM website_services WHERE slug = 'gathering-outing');

INSERT INTO website_services
    (title_id, title_en, description_id, description_en, icon, image, slug, sort_order, status)
SELECT 'Product Launching', 'Product Launching',
       'Peluncuran produk dengan pengalaman brand, panggung, multimedia, registrasi, dan dokumentasi.',
       'Product launches featuring brand experiences, staging, multimedia, registration, and documentation.',
       'fas fa-rocket', 'website/content/service-launching-live.jpg', 'product-launching', 3, 'active'
WHERE NOT EXISTS (SELECT 1 FROM website_services WHERE slug = 'product-launching');

INSERT INTO website_services
    (title_id, title_en, description_id, description_en, icon, image, slug, sort_order, status)
SELECT 'Seminar & Conference', 'Seminar & Conference',
       'Dukungan acara edukasi dan konferensi mulai dari registrasi, panggung, audio visual, hingga rundown.',
       'Support for seminars and conferences from registration and staging to audiovisual setup and rundown.',
       'fas fa-microphone', 'website/content/service-conference-live.jpg', 'seminar-conference', 4, 'active'
WHERE NOT EXISTS (SELECT 1 FROM website_services WHERE slug = 'seminar-conference');

INSERT INTO website_services
    (title_id, title_en, description_id, description_en, icon, image, slug, sort_order, status)
SELECT 'Wedding & Private Event', 'Wedding & Private Events',
       'Pendampingan perayaan personal dengan konsep, dekorasi, vendor, koordinasi acara, dan dokumentasi.',
       'Support for private celebrations with concepts, decor, vendors, event coordination, and documentation.',
       'fas fa-heart', 'website/content/service-private-event-live.jpg', 'wedding-private-event', 5, 'active'
WHERE NOT EXISTS (SELECT 1 FROM website_services WHERE slug = 'wedding-private-event');

INSERT INTO website_services
    (title_id, title_en, description_id, description_en, icon, image, slug, sort_order, status)
SELECT 'Creative Production', 'Creative Production',
       'Produksi visual, panggung, branding venue, konten, dan dokumentasi untuk memperkuat pesan acara.',
       'Visual, stage, venue branding, content, and documentation production to reinforce event messaging.',
       'fas fa-camera', 'website/content/service-creative-production-live.jpg', 'creative-production', 6, 'active'
WHERE NOT EXISTS (SELECT 1 FROM website_services WHERE slug = 'creative-production');

UPDATE website_services
SET icon = 'fas fa-building',
    image = 'website/content/service-corporate-live.jpg'
WHERE slug = 'corporate-event';

UPDATE website_services
SET icon = 'fas fa-users',
    image = 'website/content/service-gathering-live.webp'
WHERE slug = 'gathering-outing';

UPDATE website_services
SET icon = 'fas fa-rocket',
    image = 'website/content/service-launching-live.jpg'
WHERE slug = 'product-launching';

UPDATE website_services
SET icon = 'fas fa-microphone',
    image = 'website/content/service-conference-live.jpg'
WHERE slug = 'seminar-conference';

UPDATE website_services
SET icon = 'fas fa-heart',
    image = 'website/content/service-private-event-live.jpg'
WHERE slug = 'wedding-private-event';

UPDATE website_services
SET icon = 'fas fa-camera',
    image = 'website/content/service-creative-production-live.jpg'
WHERE slug = 'creative-production';

INSERT INTO website_products
    (title_id, title_en, description_id, description_en, category, image, price_label_id, price_label_en, status)
SELECT 'Paket Corporate Gathering', 'Corporate Gathering Package',
       'Perencanaan konsep, venue coordination, rundown, produksi panggung, sound system, crew, dan dokumentasi dasar.',
       'Concept planning, venue coordination, rundown, staging, sound system, crew, and essential documentation.',
       'Corporate Event', 'website/content/package-corporate-live.webp', 'Kustom', 'Custom', 'active'
WHERE NOT EXISTS (SELECT 1 FROM website_products WHERE title_id = 'Paket Corporate Gathering');

INSERT INTO website_products
    (title_id, title_en, description_id, description_en, category, image, price_label_id, price_label_en, status)
SELECT 'Paket Product Launching', 'Product Launch Package',
       'Konsep peluncuran, brand activation area, multimedia, registrasi tamu, dokumentasi, dan event management.',
       'Launch concept, brand activation area, multimedia, guest registration, documentation, and event management.',
       'Launching', 'website/content/package-launching-live.jpg', 'Kustom', 'Custom', 'active'
WHERE NOT EXISTS (SELECT 1 FROM website_products WHERE title_id = 'Paket Product Launching');

INSERT INTO website_products
    (title_id, title_en, description_id, description_en, category, image, price_label_id, price_label_en, status)
SELECT 'Paket Seminar Profesional', 'Professional Seminar Package',
       'Registrasi peserta, kebutuhan panggung, display presentasi, audio visual, moderator flow, dan dokumentasi.',
       'Participant registration, staging, presentation displays, audiovisual setup, moderator flow, and documentation.',
       'Conference', 'website/content/package-seminar-live.jpg', 'Kustom', 'Custom', 'active'
WHERE NOT EXISTS (SELECT 1 FROM website_products WHERE title_id = 'Paket Seminar Profesional');

INSERT INTO website_products
    (title_id, title_en, description_id, description_en, category, image, price_label_id, price_label_en, status)
SELECT 'Paket Intimate Celebration', 'Intimate Celebration Package',
       'Perencanaan acara privat, dekorasi, koordinasi vendor, rundown, hospitality, dan dokumentasi pilihan.',
       'Private event planning, decor, vendor coordination, rundown, hospitality, and selected documentation.',
       'Private Event', 'website/content/package-private-event-live.jpg', 'Kustom', 'Custom', 'active'
WHERE NOT EXISTS (SELECT 1 FROM website_products WHERE title_id = 'Paket Intimate Celebration');

UPDATE website_products
SET image = 'website/content/package-corporate-live.webp',
    price_label_id = 'Kustom',
    price_label_en = 'Custom'
WHERE title_id = 'Paket Corporate Gathering';

UPDATE website_products
SET image = 'website/content/package-launching-live.jpg',
    price_label_id = 'Kustom',
    price_label_en = 'Custom'
WHERE title_id = 'Paket Product Launching';

UPDATE website_products
SET image = 'website/content/package-seminar-live.jpg',
    price_label_id = 'Kustom',
    price_label_en = 'Custom'
WHERE title_id = 'Paket Seminar Profesional';

UPDATE website_products
SET image = 'website/content/package-private-event-live.jpg',
    price_label_id = 'Kustom',
    price_label_en = 'Custom'
WHERE title_id = 'Paket Intimate Celebration';

INSERT INTO website_portfolios
    (title_id, title_en, slug_id, slug_en, client_name, category, category_id, category_en, location_id, location_en,
     description_id, description_en, cover_image, thumbnail, status)
SELECT 'Konsep Corporate Annual Gathering', 'Corporate Annual Gathering Concept',
       'konsep-corporate-annual-gathering', 'corporate-annual-gathering-concept', 'Konsep Iventlo',
       'Corporate Event', 'Corporate Event', 'Corporate Event', 'Jakarta & sekitarnya', 'Jakarta and surrounding areas',
       '<p>Inspirasi acara perusahaan yang menggabungkan sesi apresiasi, hiburan, area interaksi, dan visual panggung yang kuat.</p><p>Konsep dapat disesuaikan dengan jumlah peserta, venue, tujuan komunikasi, dan kebutuhan brand.</p>',
       '<p>An event concept combining appreciation sessions, entertainment, interactive spaces, and compelling stage visuals.</p><p>The concept can be tailored to audience size, venue, communication objectives, and brand requirements.</p>',
    'website/content/portfolio-corporate-live.jpg', 'website/content/portfolio-corporate-live.jpg', 'active'
WHERE NOT EXISTS (SELECT 1 FROM website_portfolios WHERE slug_id = 'konsep-corporate-annual-gathering');

INSERT INTO website_portfolios
    (title_id, title_en, slug_id, slug_en, client_name, category, category_id, category_en, location_id, location_en,
     description_id, description_en, cover_image, thumbnail, status)
SELECT 'Konsep Brand Product Launch', 'Brand Product Launch Concept',
       'konsep-brand-product-launch', 'brand-product-launch-concept', 'Konsep Iventlo',
       'Launching', 'Launching', 'Launching', 'Jakarta & sekitarnya', 'Jakarta and surrounding areas',
       '<p>Inspirasi launching dengan fokus pada storytelling brand, product reveal, media moment, dan pengalaman tamu yang terarah.</p>',
       '<p>A launch concept focused on brand storytelling, product reveal, media moments, and a guided guest experience.</p>',
    'website/content/portfolio-launch-live.jpg', 'website/content/portfolio-launch-live.jpg', 'active'
WHERE NOT EXISTS (SELECT 1 FROM website_portfolios WHERE slug_id = 'konsep-brand-product-launch');

INSERT INTO website_portfolios
    (title_id, title_en, slug_id, slug_en, client_name, category, category_id, category_en, location_id, location_en,
     description_id, description_en, cover_image, thumbnail, status)
SELECT 'Konsep Team Gathering Outdoor', 'Outdoor Team Gathering Concept',
       'konsep-team-gathering-outdoor', 'outdoor-team-gathering-concept', 'Konsep Iventlo',
       'Gathering', 'Gathering', 'Gathering', 'Jabodetabek', 'Greater Jakarta',
       '<p>Inspirasi gathering yang memadukan team activity, entertainment, award moment, dan dokumentasi untuk memperkuat kebersamaan.</p>',
       '<p>A gathering concept combining team activities, entertainment, awards, and documentation to strengthen connection.</p>',
    'website/content/portfolio-gathering-live.jpg', 'website/content/portfolio-gathering-live.jpg', 'active'
WHERE NOT EXISTS (SELECT 1 FROM website_portfolios WHERE slug_id = 'konsep-team-gathering-outdoor');

INSERT INTO website_faqs (question_id, question_en, answer_id, answer_en, status)
SELECT 'Layanan apa saja yang dapat ditangani Iventlo?', 'What services can Iventlo handle?',
       'Kami mendukung corporate event, gathering, launching, seminar, private event, dan creative production. Lingkup pekerjaan dapat disesuaikan mulai dari konsep sampai pelaksanaan.',
       'We support corporate events, gatherings, launches, seminars, private events, and creative production. Scope can be tailored from concept through execution.', 'active'
WHERE NOT EXISTS (SELECT 1 FROM website_faqs WHERE question_id = 'Layanan apa saja yang dapat ditangani Iventlo?');

INSERT INTO website_faqs (question_id, question_en, answer_id, answer_en, status)
SELECT 'Kapan sebaiknya mulai berkonsultasi untuk sebuah event?', 'When should we start consulting for an event?',
       'Semakin awal semakin baik agar pilihan venue, vendor, konsep, dan anggaran dapat direncanakan optimal. Untuk kebutuhan mendesak, tim kami tetap dapat membantu menilai opsi yang memungkinkan.',
       'The earlier the better so venue, vendors, concept, and budget can be planned optimally. For urgent needs, our team can still assess feasible options.', 'active'
WHERE NOT EXISTS (SELECT 1 FROM website_faqs WHERE question_id = 'Kapan sebaiknya mulai berkonsultasi untuk sebuah event?');

INSERT INTO website_faqs (question_id, question_en, answer_id, answer_en, status)
SELECT 'Apakah paket acara dapat disesuaikan dengan anggaran?', 'Can event packages be tailored to a budget?',
       'Ya. Kami menyusun rekomendasi kebutuhan prioritas, konsep, dan ruang lingkup produksi berdasarkan tujuan acara serta anggaran yang disepakati.',
       'Yes. We develop recommended priorities, concept, and production scope according to event objectives and the agreed budget.', 'active'
WHERE NOT EXISTS (SELECT 1 FROM website_faqs WHERE question_id = 'Apakah paket acara dapat disesuaikan dengan anggaran?');

INSERT INTO website_faqs (question_id, question_en, answer_id, answer_en, status)
SELECT 'Bagaimana proses memulai kerja sama?', 'How do we start working together?',
       'Isi formulir inquiry atau hubungi WhatsApp kami dengan informasi jenis acara, estimasi tanggal, lokasi, jumlah peserta, dan kebutuhan utama. Tim kami akan menghubungi Anda untuk diskusi awal.',
       'Submit an inquiry or contact us via WhatsApp with your event type, estimated date, location, audience size, and primary needs. Our team will reach out for an initial discussion.', 'active'
WHERE NOT EXISTS (SELECT 1 FROM website_faqs WHERE question_id = 'Bagaimana proses memulai kerja sama?');

INSERT INTO website_posts
    (title_id, title_en, slug_id, slug_en, excerpt_id, excerpt_en, content_id, content_en, featured_image,
     meta_title, meta_keywords, meta_description, published_at, sort_order, status)
SELECT 'Checklist Merencanakan Corporate Event yang Terstruktur',
       'A Structured Corporate Event Planning Checklist',
       'checklist-merencanakan-corporate-event', 'structured-corporate-event-planning-checklist',
       'Susun tujuan, peserta, anggaran, venue, produksi, dan evaluasi agar corporate event berjalan terarah.',
       'Define objectives, audience, budget, venue, production, and evaluation for a well-directed corporate event.',
       '<h2>Mulai dari tujuan acara</h2><p>Tentukan pesan utama, peserta yang dituju, dan hasil yang ingin dicapai. Tujuan yang jelas membantu memilih format acara, venue, dan pengalaman peserta.</p><h2>Susun kebutuhan operasional</h2><ul><li>Tanggal, lokasi, dan estimasi peserta.</li><li>Rundown, panggung, audio visual, registrasi, konsumsi, dan dokumentasi.</li><li>Rencana komunikasi sebelum dan setelah acara.</li></ul><h2>Siapkan evaluasi</h2><p>Dokumentasikan hasil acara dan masukan peserta untuk menyempurnakan kegiatan berikutnya.</p>',
       '<h2>Start with event objectives</h2><p>Define the core message, target audience, and desired outcomes. Clear objectives guide the format, venue, and guest experience.</p><h2>Map operational needs</h2><ul><li>Date, venue, and estimated audience.</li><li>Rundown, staging, audiovisual setup, registration, catering, and documentation.</li><li>Communication plans before and after the event.</li></ul><h2>Prepare evaluation</h2><p>Document outcomes and participant feedback to improve future activities.</p>',
       'website/content/article-corporate.jpg',
       'Checklist Corporate Event Terstruktur | Iventlo',
       'corporate event, checklist event, event organizer jakarta',
       'Checklist praktis merencanakan corporate event dari tujuan hingga evaluasi bersama Iventlo.',
       NOW(), 1, 'published'
WHERE NOT EXISTS (SELECT 1 FROM website_posts WHERE slug_id = 'checklist-merencanakan-corporate-event');

INSERT INTO website_posts
    (title_id, title_en, slug_id, slug_en, excerpt_id, excerpt_en, content_id, content_en, featured_image,
     meta_title, meta_keywords, meta_description, published_at, sort_order, status)
SELECT 'Cara Menyiapkan Product Launching yang Menarik',
       'How to Prepare an Engaging Product Launch',
       'cara-menyiapkan-product-launching', 'how-to-prepare-an-engaging-product-launch',
       'Peluncuran produk yang efektif menyatukan cerita brand, product moment, dan pengalaman tamu.',
       'An effective product launch connects brand storytelling, the product moment, and the guest experience.',
       '<h2>Bangun cerita produk</h2><p>Peluncuran sebaiknya mengkomunikasikan manfaat produk dengan alur yang mudah diikuti audiens.</p><h2>Ciptakan momen utama</h2><p>Product reveal, tata cahaya, multimedia, dan dokumentasi perlu disusun sebagai satu pengalaman yang konsisten.</p><h2>Perhatikan perjalanan tamu</h2><p>Registrasi, hospitality, area interaksi, dan follow-up membantu acara terasa profesional sekaligus relevan.</p>',
       '<h2>Build the product story</h2><p>A launch should communicate product benefits through a journey the audience can easily follow.</p><h2>Create a key moment</h2><p>Product reveal, lighting, multimedia, and documentation should form one consistent experience.</p><h2>Consider the guest journey</h2><p>Registration, hospitality, interactive areas, and follow-up help the event feel professional and relevant.</p>',
       'website/content/article-launching.jpg',
       'Menyiapkan Product Launching Menarik | Iventlo',
       'product launching, event organizer, brand activation',
       'Tips merancang product launching yang menyampaikan cerita brand dan pengalaman produk secara efektif.',
       DATE_SUB(NOW(), INTERVAL 7 DAY), 2, 'published'
WHERE NOT EXISTS (SELECT 1 FROM website_posts WHERE slug_id = 'cara-menyiapkan-product-launching');

INSERT INTO website_posts
    (title_id, title_en, slug_id, slug_en, excerpt_id, excerpt_en, content_id, content_en, featured_image,
     meta_title, meta_keywords, meta_description, published_at, sort_order, status)
SELECT 'Ide Gathering Perusahaan untuk Membangun Engagement Tim',
       'Company Gathering Ideas to Strengthen Team Engagement',
       'ide-gathering-perusahaan-engagement-tim', 'company-gathering-ideas-for-team-engagement',
       'Gathering yang baik menyeimbangkan aktivitas kebersamaan, apresiasi, hiburan, dan komunikasi perusahaan.',
       'A strong gathering balances team activities, appreciation, entertainment, and company communication.',
       '<h2>Tentukan tema yang menyatukan</h2><p>Tema membantu seluruh aktivitas, dekorasi, dan komunikasi terasa konsisten.</p><h2>Kombinasikan format kegiatan</h2><p>Gabungkan aktivitas kelompok, sesi apresiasi, entertainment, dan waktu informal agar peserta dapat terlibat nyaman.</p><h2>Pastikan eksekusi nyaman</h2><p>Perhatikan transportasi, konsumsi, cuaca, keselamatan, dan dokumentasi sejak perencanaan awal.</p>',
       '<h2>Choose a unifying theme</h2><p>A theme helps activities, decor, and communication feel consistent.</p><h2>Combine activity formats</h2><p>Mix team activities, appreciation sessions, entertainment, and informal time for comfortable participation.</p><h2>Deliver a comfortable experience</h2><p>Plan transport, catering, weather contingencies, safety, and documentation from the outset.</p>',
       'website/content/article-gathering.jpg',
       'Ide Gathering Perusahaan & Engagement Tim | Iventlo',
       'gathering perusahaan, outing, team engagement, event organizer',
       'Inspirasi merancang gathering perusahaan yang nyaman, terstruktur, dan mendukung engagement tim.',
       DATE_SUB(NOW(), INTERVAL 14 DAY), 3, 'published'
WHERE NOT EXISTS (SELECT 1 FROM website_posts WHERE slug_id = 'ide-gathering-perusahaan-engagement-tim');
