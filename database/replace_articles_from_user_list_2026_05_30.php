<?php

date_default_timezone_set('Asia/Jakarta');

require_once __DIR__ . '/../app/Core/Database.php';

$db = Database::connect();
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function replaceArticleSlug(string $text): string
{
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/i', '-', $text);
    $text = trim($text, '-');

    return $text !== '' ? $text : 'artikel-iventlo';
}

function replaceArticleContent(string $title, string $category): string
{
    $safeTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
    $safeCategory = htmlspecialchars($category, ENT_QUOTES, 'UTF-8');
    $categoryLower = htmlspecialchars(strtolower($category), ENT_QUOTES, 'UTF-8');

    $categoryFocus = [
        'Corporate event' => [
            'audience' => 'manajemen, HR, komunikasi internal, sales, dan brand team',
            'objective' => 'menyampaikan pesan perusahaan, memperkuat budaya kerja, dan membangun pengalaman profesional bagi peserta',
            'success' => 'kejelasan pesan, ketepatan rundown, kualitas produksi, dan feedback peserta setelah acara',
        ],
        'Gathering & outing' => [
            'audience' => 'karyawan, keluarga karyawan, komunitas internal, dan pimpinan perusahaan',
            'objective' => 'membangun kebersamaan, meningkatkan engagement, dan menghadirkan suasana yang lebih rileks namun tetap terarah',
            'success' => 'partisipasi peserta, kenyamanan aktivitas, keamanan lokasi, dan dampak positif terhadap dinamika tim',
        ],
        'Seminar & conference' => [
            'audience' => 'peserta profesional, pembicara, moderator, sponsor, media, dan komunitas industri',
            'objective' => 'membagikan pengetahuan, mempertemukan pemangku kepentingan, dan menciptakan forum diskusi yang kredibel',
            'success' => 'jumlah peserta hadir, kualitas materi, interaksi sesi, serta dokumentasi insight yang bisa digunakan kembali',
        ],
        'Product launching' => [
            'audience' => 'calon pelanggan, media, partner bisnis, komunitas, KOL, dan internal brand team',
            'objective' => 'memperkenalkan produk, membangun perhatian pasar, dan menciptakan momen brand yang mudah diingat',
            'success' => 'jangkauan publikasi, respons audiens, kualitas product reveal, dan tindak lanjut setelah acara',
        ],
        'Exhibition & expo' => [
            'audience' => 'pengunjung pameran, buyer, distributor, partner, media, dan tim sales',
            'objective' => 'menarik traffic booth, menghasilkan leads berkualitas, dan memperkuat kehadiran brand di ruang industri',
            'success' => 'jumlah leads, kualitas percakapan, traffic booth, dan potensi transaksi setelah expo',
        ],
        'Event management' => [
            'audience' => 'project owner, event team, vendor, PIC internal, dan seluruh pihak operasional',
            'objective' => 'membuat proses perencanaan, produksi, eksekusi, dan evaluasi berjalan dalam satu sistem kerja yang jelas',
            'success' => 'kontrol timeline, kesiapan vendor, akurasi budget, mitigasi risiko, dan kualitas reporting',
        ],
        'Event registration & ticketing' => [
            'audience' => 'peserta, panitia registrasi, tim security, sponsor, dan penyelenggara acara',
            'objective' => 'mempercepat proses registrasi, mengurangi antrean, dan menyediakan data kehadiran yang akurat',
            'success' => 'kecepatan check-in, akurasi data peserta, minimnya antrean, dan laporan attendance yang siap digunakan',
        ],
        'Wedding & private event' => [
            'audience' => 'pasangan, keluarga, tamu undangan, vendor, dan tim wedding organizer',
            'objective' => 'menghadirkan momen personal yang tertata, hangat, dan sesuai karakter pemilik acara',
            'success' => 'kenyamanan tamu, ketepatan rundown, kualitas dekorasi, dan kelancaran koordinasi keluarga serta vendor',
        ],
        'Creative production' => [
            'audience' => 'brand team, creative director, production team, vendor multimedia, dan peserta acara',
            'objective' => 'membangun identitas visual, suasana panggung, dan pengalaman acara yang kuat secara emosional',
            'success' => 'konsistensi visual, kekuatan storytelling, kualitas multimedia, dan daya ingat peserta terhadap acara',
        ],
        'SEO & edukasi event organizer' => [
            'audience' => 'perusahaan, brand owner, komunitas, pasangan, dan siapa pun yang sedang memilih partner event',
            'objective' => 'membantu pembaca memahami cara memilih, merencanakan, dan mengevaluasi layanan event organizer secara tepat',
            'success' => 'kejelasan keputusan, efisiensi budget, ketepatan scope kerja, dan kesiapan briefing kepada vendor',
        ],
    ];

    $focus = $categoryFocus[$category] ?? [
        'audience' => 'klien, peserta, panitia, vendor, dan stakeholder acara',
        'objective' => 'membuat acara lebih terstruktur, relevan, dan mudah dievaluasi',
        'success' => 'kelancaran acara, kepuasan peserta, dan laporan yang dapat ditindaklanjuti',
    ];

    $audience = htmlspecialchars($focus['audience'], ENT_QUOTES, 'UTF-8');
    $objective = htmlspecialchars($focus['objective'], ENT_QUOTES, 'UTF-8');
    $success = htmlspecialchars($focus['success'], ENT_QUOTES, 'UTF-8');

    return '<p><strong>' . $safeTitle . '</strong> bukan hanya soal memilih vendor atau menyusun rundown. Dalam praktik event yang profesional, topik ini perlu dipahami sebagai bagian dari strategi komunikasi, pengalaman peserta, dan pengelolaan operasional yang terukur. Banyak acara terlihat sederhana dari luar, tetapi di baliknya terdapat keputusan penting tentang tujuan, alur peserta, kualitas produksi, koordinasi tim, sampai cara mengevaluasi hasil setelah acara selesai.</p>'
        . '<p>Artikel ini disusun oleh Iventlo sebagai panduan komprehensif untuk membantu perusahaan, brand, komunitas, maupun penyelenggara acara merancang event dengan lebih matang. Fokusnya bukan sekadar membuat acara berjalan, melainkan memastikan setiap elemen memiliki alasan, terhubung dengan kebutuhan bisnis atau organisasi, dan memberi pengalaman yang rapi bagi peserta.</p>'
        . '<blockquote>Acara yang baik bukan hanya ramai. Acara yang baik memiliki tujuan yang jelas, alur yang nyaman, eksekusi yang disiplin, dan hasil yang bisa dievaluasi.</blockquote>'
        . '<h2>Memahami konteks dan tujuan acara</h2>'
        . '<p>Dalam kategori ' . $safeCategory . ', langkah pertama yang perlu dilakukan adalah memahami konteks acara. Siapa audiens utamanya? Pesan apa yang ingin dibawa? Apakah acara bertujuan untuk edukasi, engagement, promosi, apresiasi, penjualan, atau penguatan brand? Jawaban dari pertanyaan ini akan menentukan format acara, gaya komunikasi, pilihan venue, kebutuhan produksi, sampai tone visual yang digunakan.</p>'
        . '<p>Target audiens untuk topik ini biasanya melibatkan ' . $audience . '. Karena itu, keputusan kreatif dan operasional harus mempertimbangkan kepentingan banyak pihak. Acara untuk internal perusahaan tentu berbeda dengan acara publik. Event untuk media membutuhkan flow yang berbeda dengan event untuk karyawan. Begitu juga acara dengan target leads, komunitas, atau tamu VIP membutuhkan pendekatan hospitality yang lebih detail.</p>'
        . '<h2>Menentukan indikator keberhasilan sejak awal</h2>'
        . '<p>Sebelum membahas detail teknis, tentukan terlebih dahulu indikator keberhasilan. Untuk topik ini, tujuan utamanya adalah ' . $objective . '. Indikator ini perlu diterjemahkan ke dalam ukuran yang lebih praktis, misalnya jumlah peserta hadir, tingkat partisipasi, jumlah leads, kualitas dokumentasi, respons media, engagement peserta, atau kepuasan stakeholder internal.</p>'
        . '<p>Dengan indikator yang jelas, tim tidak hanya sibuk menjalankan checklist. Tim juga memahami prioritas. Jika targetnya engagement, maka desain aktivitas dan interaksi peserta harus kuat. Jika targetnya publikasi, maka dokumentasi, press moment, dan materi komunikasi perlu disiapkan sejak awal. Jika targetnya efisiensi operasional, maka registrasi, check-in, dan koordinasi vendor harus menjadi perhatian utama.</p>'
        . '<h2>Strategi perencanaan yang lebih profesional</h2>'
        . '<p>Perencanaan event profesional dimulai dari brief yang rapi. Brief sebaiknya memuat latar belakang acara, tujuan, target peserta, estimasi jumlah undangan, lokasi, tanggal, budget range, kebutuhan branding, stakeholder yang terlibat, dan ekspektasi output. Dokumen ini akan menjadi acuan bagi tim internal, event organizer, vendor produksi, MC, talent, fotografer, videografer, sampai tim venue.</p>'
        . '<p>Setelah brief disepakati, susun timeline kerja yang realistis. Timeline ideal memisahkan fase konsep, vendor sourcing, produksi desain, approval materi, registrasi, technical meeting, loading, rehearsal, event day, dan reporting. Setiap fase harus memiliki PIC dan deadline yang jelas. Tanpa pembagian tanggung jawab, pekerjaan kecil mudah tertunda dan baru terlihat sebagai masalah menjelang hari pelaksanaan.</p>'
        . '<h2>Checklist utama yang perlu disiapkan</h2>'
        . '<ul><li><strong>Konsep acara:</strong> tema, objective, key message, format, tone visual, dan pengalaman peserta.</li><li><strong>Rundown:</strong> alur waktu, transisi antar sesi, durasi sambutan, sesi utama, entertainment, break, dan closing.</li><li><strong>Produksi:</strong> panggung, sound system, lighting, LED screen, backdrop, multimedia, dekorasi, signage, dan kebutuhan listrik.</li><li><strong>Hospitality:</strong> registrasi, welcome area, konsumsi, seating plan, VIP handling, usher, dan flow peserta.</li><li><strong>Dokumentasi:</strong> photo list, video highlight, aftermovie, kebutuhan drone jika relevan, serta materi publikasi pasca event.</li><li><strong>Reporting:</strong> laporan attendance, evaluasi kendala, dokumentasi final, insight peserta, dan rekomendasi perbaikan.</li></ul>'
        . '<p>Checklist tersebut perlu disesuaikan dengan skala acara. Event kecil mungkin tidak membutuhkan semua elemen produksi, tetapi tetap membutuhkan koordinasi yang rapi. Sebaliknya, event besar membutuhkan dokumen yang lebih detail, termasuk floor plan, cue sheet, traffic flow, risk register, dan contact list seluruh PIC.</p>'
        . '<h2>Pengalaman peserta sebagai pusat desain acara</h2>'
        . '<p>Salah satu kesalahan umum dalam merancang event adalah terlalu fokus pada panggung, tetapi lupa pada perjalanan peserta. Padahal pengalaman peserta dimulai sejak menerima undangan, mengisi form registrasi, mendapatkan reminder, tiba di lokasi, melakukan check-in, mengikuti sesi, menikmati konsumsi, sampai menerima dokumentasi setelah acara selesai.</p>'
        . '<p>Untuk membuat pengalaman lebih nyaman, pastikan informasi acara mudah dipahami. Gunakan undangan yang jelas, landing page atau form registrasi yang ringkas, QR code untuk check-in jika diperlukan, signage yang mudah ditemukan, dan petugas lapangan yang memahami flow. Semakin sedikit kebingungan peserta, semakin profesional acara terlihat.</p>'
        . '<h2>Peran teknologi dalam event modern</h2>'
        . '<p>Teknologi tidak harus selalu rumit. Dalam banyak acara, teknologi paling berguna adalah teknologi yang membuat pekerjaan lebih cepat dan data lebih akurat. Sistem registrasi online, QR attendance, dashboard kehadiran, dan dokumentasi digital dapat membantu penyelenggara memantau progres secara realtime. Data peserta juga dapat digunakan untuk follow-up, evaluasi, dan kebutuhan komunikasi berikutnya.</p>'
        . '<p>Namun, penggunaan teknologi harus tetap disesuaikan dengan profil peserta. Jika audiens tidak terbiasa dengan sistem digital, siapkan flow bantuan di area registrasi. Jika acara memiliki banyak tamu VIP, pastikan ada jalur khusus yang lebih personal. Teknologi seharusnya memperlancar acara, bukan menambah hambatan baru.</p>'
        . '<h2>Koordinasi vendor dan tim lapangan</h2>'
        . '<p>Vendor management adalah bagian penting dalam keberhasilan event. Setiap vendor harus memahami scope kerja, waktu loading, kebutuhan teknis, contact person, batas area kerja, dan standar output. Koordinasi ini perlu dilakukan sebelum event day melalui technical meeting dan final confirmation. Pada acara yang kompleks, cue sheet sangat membantu agar MC, multimedia, lighting, sound, stage manager, dan show caller bergerak dalam ritme yang sama.</p>'
        . '<p>Di hari pelaksanaan, komunikasi harus singkat dan jelas. Gunakan satu jalur koordinasi utama agar instruksi tidak bercabang. PIC utama perlu memiliki kewenangan untuk mengambil keputusan cepat, terutama jika terjadi perubahan mendadak seperti keterlambatan pembicara, perubahan cuaca, kendala teknis, atau penyesuaian rundown.</p>'
        . '<h2>Mitigasi risiko sebelum hari pelaksanaan</h2>'
        . '<p>Event profesional selalu memiliki rencana cadangan. Risiko yang umum muncul antara lain perubahan jumlah peserta, keterlambatan vendor, gangguan listrik, masalah audio, antrean registrasi, keterlambatan konsumsi, cuaca buruk, atau perubahan agenda dari stakeholder. Semua risiko ini sebaiknya dibahas sebelum acara, bukan saat masalah sudah terjadi.</p>'
        . '<p>Buat daftar risiko sederhana berisi potensi masalah, dampak, penanggung jawab, dan solusi. Misalnya, jika registrasi padat, siapkan meja tambahan atau jalur QR check-in. Jika venue outdoor, siapkan opsi area teduh atau rencana pindah ruangan. Jika acara bergantung pada multimedia, lakukan rehearsal dan siapkan file backup di beberapa perangkat.</p>'
        . '<h2>Evaluasi dan reporting setelah acara</h2>'
        . '<p>Keberhasilan event tidak berhenti ketika lampu panggung dimatikan. Setelah acara selesai, tim perlu mengumpulkan data dan insight. Untuk topik ini, ukuran keberhasilan dapat dilihat dari ' . $success . '. Data tersebut akan lebih bernilai jika disusun menjadi laporan yang mudah dibaca oleh manajemen atau stakeholder.</p>'
        . '<p>Laporan event idealnya berisi ringkasan acara, jumlah registrasi, jumlah hadir, dokumentasi foto dan video, highlight kegiatan, kendala yang muncul, solusi yang dilakukan, feedback peserta, serta rekomendasi untuk acara berikutnya. Dengan reporting yang baik, event berikutnya dapat dirancang lebih efisien dan lebih berdampak.</p>'
        . '<h2>Bagaimana Iventlo membantu?</h2>'
        . '<p>Iventlo mendampingi klien dalam proses end-to-end event management, mulai dari pengembangan konsep, perencanaan timeline, vendor management, digital registration, produksi multimedia, operasional lapangan, dokumentasi, hingga laporan pasca acara. Pendekatan ini membantu klien memantau progres secara lebih transparan dan memastikan setiap kebutuhan acara memiliki penanggung jawab yang jelas.</p>'
        . '<p>Untuk perusahaan atau brand yang ingin menjalankan ' . $categoryLower . ' secara lebih profesional, bekerja dengan partner event yang memahami aspek kreatif, teknis, dan operasional dapat menghemat banyak waktu. Tim internal tetap dapat fokus pada tujuan strategis, sementara detail lapangan dikelola oleh tim yang terbiasa menangani dinamika event.</p>'
        . '<h2>Kesimpulan</h2>'
        . '<p>' . $safeTitle . ' perlu dipahami sebagai proses menyeluruh, bukan hanya daftar pekerjaan menjelang hari acara. Dengan tujuan yang jelas, strategi yang matang, teknologi yang sesuai, koordinasi vendor yang rapi, dan evaluasi yang terukur, sebuah event dapat menjadi pengalaman yang profesional, berkesan, dan memberi nilai nyata bagi penyelenggara maupun peserta.</p>';
}

$articleGroups = [
    'Corporate event' => [
        'Cara Merencanakan Corporate Event yang Profesional dan Efektif',
        '10 Kesalahan yang Harus Dihindari Saat Mengadakan Corporate Event',
        'Panduan Lengkap Menyelenggarakan Company Gathering',
        'Strategi Membuat Acara Perusahaan yang Berkesan',
        'Tips Mengelola Corporate Event dengan Anggaran Terbatas',
        'Mengapa Corporate Event Penting untuk Meningkatkan Branding Perusahaan',
        'Tren Corporate Event yang Populer Tahun Ini',
        'Cara Memilih Venue yang Tepat untuk Acara Perusahaan',
        'Checklist Persiapan Corporate Event yang Wajib Dimiliki',
        'Peran Event Organizer dalam Kesuksesan Corporate Event',
    ],
    'Gathering & outing' => [
        'Ide Company Gathering yang Seru dan Tidak Membosankan',
        'Manfaat Employee Gathering untuk Meningkatkan Produktivitas Tim',
        '20 Aktivitas Team Building yang Efektif untuk Perusahaan',
        'Cara Menentukan Konsep Gathering yang Sesuai dengan Budaya Perusahaan',
        'Tips Mengadakan Family Gathering yang Berkesan',
        'Perbedaan Gathering, Outing, dan Team Building',
        'Destinasi Terbaik untuk Corporate Outing di Indonesia',
        'Rundown Company Gathering yang Efektif dan Terstruktur',
        'Cara Mengukur Keberhasilan Acara Gathering Perusahaan',
        'Kesalahan Umum Saat Menyelenggarakan Outbound Perusahaan',
    ],
    'Seminar & conference' => [
        'Langkah-Langkah Menyelenggarakan Seminar yang Sukses',
        'Cara Menentukan Tema Seminar yang Menarik Peserta',
        'Checklist Persiapan Seminar Profesional',
        'Strategi Meningkatkan Jumlah Peserta Seminar',
        'Tips Memilih Moderator dan Pembicara Seminar',
        'Hybrid Event vs Seminar Offline: Mana yang Lebih Efektif?',
        'Cara Membuat Pengalaman Peserta Seminar Lebih Interaktif',
        'Peran Teknologi dalam Kesuksesan Seminar Modern',
        'Tips Mengelola Registrasi Peserta Seminar dengan Mudah',
        'Faktor Penting dalam Menyelenggarakan Conference Skala Besar',
    ],
    'Product launching' => [
        'Cara Menyelenggarakan Product Launching yang Menarik Perhatian',
        'Strategi Meningkatkan Publikasi Saat Peluncuran Produk',
        'Checklist Event Product Launching yang Wajib Dipersiapkan',
        'Kesalahan yang Sering Terjadi Saat Product Launching',
        'Cara Memilih Venue untuk Acara Peluncuran Produk',
        'Peran Event Organizer dalam Product Launching',
        'Ide Aktivasi Brand untuk Acara Peluncuran Produk',
        'Mengukur Keberhasilan Event Product Launching',
        'Strategi Mengundang Media pada Acara Peluncuran Produk',
        'Tips Membuat Peluncuran Produk Menjadi Viral',
    ],
    'Exhibition & expo' => [
        'Panduan Lengkap Mengikuti Pameran dan Expo',
        'Cara Mendesain Booth Pameran yang Menarik Pengunjung',
        'Strategi Mendapatkan Leads Berkualitas dari Event Expo',
        'Tips Memaksimalkan Investasi pada Pameran Bisnis',
        'Kesalahan yang Harus Dihindari Saat Mengikuti Expo',
        'Cara Menarik Pengunjung ke Booth Pameran',
        'Peran Event Organizer dalam Pengelolaan Exhibition',
        'Tren Desain Booth Pameran Modern',
        'Cara Mengukur ROI dari Event Pameran',
        'Checklist Persiapan Mengikuti Expo atau Trade Show',
    ],
    'Event management' => [
        'Apa Itu Event Management dan Mengapa Penting?',
        'Tahapan Event Management dari Awal hingga Akhir',
        'Cara Menyusun Timeline Event yang Efektif',
        'Tips Mengelola Vendor untuk Acara Besar',
        'Manajemen Risiko dalam Penyelenggaraan Event',
        'Cara Membuat Rundown Acara yang Profesional',
        'Strategi Mengelola Perubahan Mendadak Saat Event Berlangsung',
        'Faktor yang Menentukan Kesuksesan Sebuah Event',
        'Panduan Menentukan Anggaran Acara Secara Efisien',
        'Mengapa Menggunakan Event Organizer Lebih Menguntungkan?',
    ],
    'Event registration & ticketing' => [
        'Manfaat Registrasi Online untuk Acara Modern',
        'Cara Mengelola Tiket Event dengan Lebih Efisien',
        'Keuntungan Menggunakan QR Code untuk Check-In Peserta',
        'Tips Mengurangi Antrean Registrasi Saat Event',
        'Sistem Ticketing Digital untuk Acara Profesional',
        'Cara Memilih Platform Registrasi Event yang Tepat',
        'Mengapa Data Peserta Penting dalam Sebuah Event',
        'Strategi Meningkatkan Jumlah Pendaftar Acara',
        'Registrasi Online vs Registrasi Manual: Mana yang Lebih Baik?',
        'Cara Membuat Pengalaman Check-In Peserta Lebih Cepat',
    ],
    'Wedding & private event' => [
        'Tips Merencanakan Pernikahan yang Berkesan',
        'Checklist Persiapan Wedding yang Wajib Diketahui',
        'Cara Memilih Wedding Organizer yang Tepat',
        'Ide Konsep Pernikahan Modern yang Populer',
        'Tips Mengatur Anggaran Pernikahan Secara Efisien',
        'Kesalahan yang Sering Terjadi Saat Mempersiapkan Pernikahan',
        'Cara Menentukan Venue Pernikahan Impian',
        'Inspirasi Dekorasi Pernikahan Elegan',
        'Panduan Menyusun Rundown Acara Pernikahan',
        'Peran Wedding Organizer dalam Hari Pernikahan',
    ],
    'Creative production' => [
        'Pentingnya Creative Concept dalam Sebuah Event',
        'Cara Menciptakan Pengalaman Event yang Berkesan',
        'Tren Desain Panggung dan Produksi Event Modern',
        'Strategi Membangun Identitas Acara Melalui Creative Production',
        'Teknologi yang Mengubah Industri Event Saat Ini',
        'Cara Menggabungkan Unsur Digital dalam Event Offline',
        'Pentingnya Visual Branding dalam Sebuah Event',
        'Tips Membuat Event yang Instagramable',
        'Peran Multimedia dalam Kesuksesan Event',
        'Inovasi Creative Production yang Sedang Tren',
    ],
    'SEO & edukasi event organizer' => [
        'Kapan Sebaiknya Menggunakan Jasa Event Organizer?',
        'Berapa Biaya Menggunakan Jasa Event Organizer?',
        'Cara Memilih Event Organizer yang Profesional',
        'Tanda-Tanda Event Organizer yang Berpengalaman',
        'Event Organizer vs Event Planner: Apa Perbedaannya?',
        'Mengapa Perusahaan Membutuhkan Event Organizer?',
        'Tips Memilih Vendor Event yang Berkualitas',
        'Cara Menentukan Konsep Event yang Tepat untuk Bisnis Anda',
        'Panduan Lengkap Menyelenggarakan Event dari Nol',
        'Masa Depan Industri Event di Era Digital',
    ],
];

$keepSlugs = [
    'checklist-merencanakan-corporate-event',
    'cara-menyiapkan-product-launching',
    'ide-gathering-perusahaan-engagement-tim',
];

$columns = $db->query('SHOW COLUMNS FROM website_posts')->fetchAll(PDO::FETCH_COLUMN);
$hasCreatedAt = in_array('created_at', $columns, true);
$hasUpdatedAt = in_array('updated_at', $columns, true);

$baseColumns = [
    'title_id',
    'title_en',
    'slug_id',
    'slug_en',
    'excerpt_id',
    'excerpt_en',
    'content_id',
    'content_en',
    'featured_image',
    'meta_title',
    'meta_keywords',
    'meta_description',
    'published_at',
    'sort_order',
    'status',
];

if ($hasCreatedAt) {
    $baseColumns[] = 'created_at';
}

if ($hasUpdatedAt) {
    $baseColumns[] = 'updated_at';
}

$placeholders = array_map(static fn ($column) => ':' . $column, $baseColumns);
$insert = $db->prepare(
    'INSERT INTO website_posts (' . implode(', ', $baseColumns) . ') VALUES (' . implode(', ', $placeholders) . ')'
);

$today = new DateTimeImmutable('today', new DateTimeZone('Asia/Jakarta'));
$dateCursor = $today->sub(new DateInterval('P1D'));
$defaultImage = 'website/content/article-corporate.jpg';
$deleted = 0;
$inserted = 0;

$db->beginTransaction();

try {
    $keepPlaceholders = implode(',', array_fill(0, count($keepSlugs), '?'));
    $delete = $db->prepare("DELETE FROM website_posts WHERE slug_id NOT IN ($keepPlaceholders)");
    $delete->execute($keepSlugs);
    $deleted = $delete->rowCount();

    $dateSelects = ['SELECT DATE(published_at) AS article_date FROM website_posts WHERE published_at IS NOT NULL'];

    if ($hasCreatedAt) {
        $dateSelects[] = 'SELECT DATE(created_at) AS article_date FROM website_posts WHERE created_at IS NOT NULL';
    }

    $existingDates = [];
    $dateRows = $db->query(implode(' UNION ', $dateSelects))->fetchAll(PDO::FETCH_COLUMN);

    foreach ($dateRows as $date) {
        if ($date) {
            $existingDates[$date] = true;
        }
    }

    $maxSortOrder = (int) $db->query('SELECT COALESCE(MAX(sort_order), 0) FROM website_posts')->fetchColumn();

    foreach ($articleGroups as $category => $titles) {
        foreach ($titles as $title) {
            while (isset($existingDates[$dateCursor->format('Y-m-d')])) {
                $dateCursor = $dateCursor->sub(new DateInterval('P1D'));
            }

            $date = $dateCursor->format('Y-m-d');
            $hour = str_pad((string) (8 + ($inserted % 10)), 2, '0', STR_PAD_LEFT);
            $minute = str_pad((string) (($inserted * 11) % 60), 2, '0', STR_PAD_LEFT);
            $timestamp = $date . ' ' . $hour . ':' . $minute . ':00';
            $slug = replaceArticleSlug($title);
            $content = replaceArticleContent($title, $category);

            $params = [
                ':title_id' => $title,
                ':title_en' => $title,
                ':slug_id' => $slug,
                ':slug_en' => $slug,
                ':excerpt_id' => 'Panduan Iventlo seputar ' . strtolower($category) . ' untuk membantu acara lebih profesional, terstruktur, dan berkesan.',
                ':excerpt_en' => 'Iventlo guide about ' . strtolower($category) . ' to help events become more professional, structured, and memorable.',
                ':content_id' => $content,
                ':content_en' => $content,
                ':featured_image' => $defaultImage,
                ':meta_title' => $title . ' | Iventlo',
                ':meta_keywords' => 'iventlo, event organizer, ' . strtolower($category) . ', ' . strtolower($title),
                ':meta_description' => 'Artikel Iventlo: ' . $title . '. Panduan praktis untuk menyelenggarakan event yang profesional.',
                ':published_at' => $timestamp,
                ':sort_order' => $maxSortOrder + $inserted + 1,
                ':status' => 'published',
            ];

            if ($hasCreatedAt) {
                $params[':created_at'] = $timestamp;
            }

            if ($hasUpdatedAt) {
                $params[':updated_at'] = $timestamp;
            }

            $insert->execute($params);

            $existingDates[$date] = true;
            $dateCursor = $dateCursor->sub(new DateInterval('P1D'));
            $inserted++;
        }
    }

    $db->commit();
} catch (Throwable $exception) {
    $db->rollBack();
    throw $exception;
}

echo 'Deleted articles: ' . $deleted . PHP_EOL;
echo 'Inserted articles: ' . $inserted . PHP_EOL;
echo 'Default image: ' . $defaultImage . PHP_EOL;
echo 'Date limit: before ' . $today->format('Y-m-d') . PHP_EOL;
