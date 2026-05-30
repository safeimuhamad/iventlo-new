<?php

class WebsitePost
{
    protected $db;
    private static $longContentEnsured = false;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function all()
    {
        $stmt = $this->db->query("
            SELECT *
            FROM website_posts
            ORDER BY id DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function paginate($limit, $offset)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM website_posts
            ORDER BY id DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->bindValue(1, (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(2, (int) $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll()
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM website_posts")->fetchColumn();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM website_posts WHERE id = ?");
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function published($limit = 10, $q = '')
    {
        $this->ensureLongPublicArticleContent();

        $sql = "
            SELECT *
            FROM website_posts
            WHERE status IN ('published', 'publish')
        ";

        $params = [];

        if ($q !== '') {
            $sql .= "
                AND (
                    title_id LIKE :q
                    OR title_en LIKE :q
                    OR excerpt_id LIKE :q
                    OR excerpt_en LIKE :q
                    OR content_id LIKE :q
                    OR content_en LIKE :q
                )
            ";

            $params[':q'] = '%' . $q . '%';
        }

        $sql .= "
            ORDER BY published_at DESC, id DESC
            LIMIT :limit
        ";

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function publishedPaginated($limit = 10, $offset = 0, $q = '')
    {
        $this->ensureLongPublicArticleContent();

        $sql = "
            SELECT *
            FROM website_posts
            WHERE status IN ('published', 'publish')
        ";

        $params = [];

        if ($q !== '') {
            $sql .= "
                AND (
                    title_id LIKE :q
                    OR title_en LIKE :q
                    OR excerpt_id LIKE :q
                    OR excerpt_en LIKE :q
                    OR content_id LIKE :q
                    OR content_en LIKE :q
                )
            ";

            $params[':q'] = '%' . $q . '%';
        }

        $sql .= "
            ORDER BY published_at DESC, id DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countPublished($q = '')
    {
        $sql = "
            SELECT COUNT(*)
            FROM website_posts
            WHERE status IN ('published', 'publish')
        ";

        $params = [];

        if ($q !== '') {
            $sql .= "
                AND (
                    title_id LIKE :q
                    OR title_en LIKE :q
                    OR excerpt_id LIKE :q
                    OR excerpt_en LIKE :q
                    OR content_id LIKE :q
                    OR content_en LIKE :q
                )
            ";

            $params[':q'] = '%' . $q . '%';
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn();
    }

    public function findBySlug($slug)
    {
        $this->ensureLongPublicArticleContent();

        $stmt = $this->db->prepare("
            SELECT *
            FROM website_posts
            WHERE status IN ('published', 'publish')
            AND (
                slug_id = ?
                OR slug_en = ?
            )
            LIMIT 1
        ");

        $stmt->execute([$slug, $slug]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO website_posts
            (
                title_id, title_en,
                slug_id, slug_en,
                excerpt_id, excerpt_en,
                content_id, content_en,
                featured_image,
                meta_title, meta_keywords, meta_description,
                published_at, sort_order, status
            )
            VALUES
            (
                :title_id, :title_en,
                :slug_id, :slug_en,
                :excerpt_id, :excerpt_en,
                :content_id, :content_en,
                :featured_image,
                :meta_title, :meta_keywords, :meta_description,
                :published_at, :sort_order, :status
            )
        ");

        return $stmt->execute($data);
    }

    public function update($id, $data)
    {
        $data['id'] = $id;

        $stmt = $this->db->prepare("
            UPDATE website_posts SET
                title_id = :title_id,
                title_en = :title_en,
                slug_id = :slug_id,
                slug_en = :slug_en,
                excerpt_id = :excerpt_id,
                excerpt_en = :excerpt_en,
                content_id = :content_id,
                content_en = :content_en,
                featured_image = :featured_image,
                meta_title = :meta_title,
                meta_keywords = :meta_keywords,
                meta_description = :meta_description,
                published_at = :published_at,
                sort_order = :sort_order,
                status = :status
            WHERE id = :id
        ");

        return $stmt->execute($data);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM website_posts WHERE id = ?");
        return $stmt->execute([$id]);
    }

    private function ensureLongPublicArticleContent()
    {
        if (self::$longContentEnsured) {
            return;
        }

        self::$longContentEnsured = true;

        $articles = $this->longPublicArticleContent();

        $stmt = $this->db->prepare("
            UPDATE website_posts
            SET content_id = :content_id,
                content_en = :content_en
            WHERE slug_id = :slug_id
            AND (
                CHAR_LENGTH(COALESCE(content_id, '')) < 1000
                OR CHAR_LENGTH(COALESCE(content_en, '')) < 1000
            )
        ");

        foreach ($articles as $slug => $content) {
            $stmt->execute([
                ':slug_id' => $slug,
                ':content_id' => $content['id'],
                ':content_en' => $content['en'],
            ]);
        }
    }

    private function longPublicArticleContent()
    {
        return [
            'checklist-merencanakan-corporate-event' => [
                'id' => '<p>Corporate event yang berhasil tidak hanya ditentukan oleh panggung yang megah atau jumlah peserta yang hadir. Acara perusahaan perlu dirancang sebagai media komunikasi yang jelas, terukur, dan nyaman untuk semua pihak yang terlibat. Karena itu, checklist perencanaan menjadi alat penting agar tim internal, vendor, pembicara, tamu VIP, dan peserta berada pada alur kerja yang sama sejak awal.</p><h2>Mulai dari tujuan acara</h2><p>Tentukan pesan utama, profil peserta, hasil yang ingin dicapai, serta indikator keberhasilan acara. Tujuan yang jelas akan membantu menentukan format acara, pemilihan venue, gaya komunikasi, kebutuhan panggung, materi presentasi, hingga cara mengevaluasi hasil. Untuk town hall, fokusnya bisa berupa penyampaian arah perusahaan. Untuk awarding, fokusnya adalah apresiasi dan pengalaman emosional peserta. Untuk meeting besar, fokusnya bisa pada efektivitas informasi dan kelancaran diskusi.</p><h2>Susun kebutuhan operasional</h2><p>Setelah tujuan ditetapkan, susun daftar kebutuhan secara detail: tanggal acara, lokasi, jumlah peserta, rundown, layout ruangan, registrasi, konsumsi, audio visual, lighting, multimedia, dokumentasi, keamanan, hospitality, transportasi, dan kebutuhan khusus tamu undangan. Checklist ini sebaiknya dilengkapi PIC, deadline, status progres, dan catatan risiko agar setiap kebutuhan dapat dipantau dengan mudah.</p><h2>Pastikan alur peserta nyaman</h2><p>Pengalaman peserta dimulai sebelum mereka tiba di lokasi. Undangan, reminder, titik registrasi, signage, flow masuk ruangan, informasi agenda, hingga follow-up setelah acara perlu disiapkan secara konsisten. Alur yang tertata membuat peserta merasa diarahkan, mengurangi antrean, dan membantu acara terlihat profesional.</p><h2>Siapkan evaluasi setelah acara</h2><p>Dokumentasikan hasil acara melalui foto, video, daftar hadir, feedback peserta, catatan kendala, dan rekomendasi perbaikan. Evaluasi ini penting agar event berikutnya lebih matang, baik dari sisi konsep, teknis, komunikasi, maupun anggaran. Dengan perencanaan yang rapi, corporate event dapat menjadi pengalaman yang bukan hanya berjalan lancar, tetapi juga meninggalkan dampak nyata untuk perusahaan.</p>',
                'en' => '<p>A successful corporate event is not only defined by an impressive stage or the number of people in attendance. Company events need to work as clear, measurable, and comfortable communication platforms for every stakeholder involved. That is why a planning checklist is essential. It keeps internal teams, vendors, speakers, VIP guests, and participants aligned from the earliest stage of preparation.</p><h2>Start with the event objective</h2><p>Define the key message, audience profile, desired outcome, and success indicators. A clear objective helps determine the event format, venue selection, communication style, stage requirements, presentation materials, and evaluation method. A town hall may focus on communicating company direction. An awarding event may focus on appreciation and emotional experience. A large meeting may prioritize information flow and productive discussion.</p><h2>Map the operational needs</h2><p>Once the objective is defined, prepare a detailed requirement list: event date, location, audience size, rundown, room layout, registration, catering, audiovisual setup, lighting, multimedia, documentation, security, hospitality, transportation, and special guest requirements. The checklist should include PIC assignments, deadlines, progress status, and risk notes so every item can be monitored easily.</p><h2>Design a comfortable participant journey</h2><p>The participant experience begins before guests arrive at the venue. Invitations, reminders, registration points, signage, entrance flow, agenda information, and post-event follow-up should feel consistent and easy to understand. A structured journey reduces queues, helps participants feel guided, and makes the event look professional.</p><h2>Prepare post-event evaluation</h2><p>Document the event through photos, videos, attendance data, participant feedback, issue notes, and improvement recommendations. This evaluation is valuable for making the next event stronger in concept, technical execution, communication, and budgeting. With proper planning, a corporate event can run smoothly and create meaningful impact for the company.</p>',
            ],
            'cara-menyiapkan-product-launching' => [
                'id' => '<p>Product launching adalah momen penting untuk memperkenalkan nilai produk, membangun perhatian pasar, dan menciptakan pengalaman pertama yang kuat bagi audiens. Launching yang baik tidak berhenti pada seremonial pembukaan. Acara perlu menggabungkan cerita brand, product reveal, pengalaman tamu, dokumentasi, dan strategi follow-up agar pesan produk terus hidup setelah acara selesai.</p><h2>Bangun cerita produk yang jelas</h2><p>Sebelum membahas panggung dan dekorasi, tentukan cerita utama yang ingin disampaikan. Apa masalah yang diselesaikan produk? Siapa audiens utamanya? Keunggulan apa yang perlu diingat peserta? Jawaban dari pertanyaan ini akan menjadi dasar untuk menentukan tema, visual, alur presentasi, materi layar, hingga narasi MC dan pembicara.</p><h2>Rancang momen reveal yang berkesan</h2><p>Product reveal harus disiapkan sebagai puncak pengalaman. Lighting, audio, multimedia, countdown, choreography, dan posisi tamu perlu dipikirkan secara detail. Momen ini sebaiknya mudah difoto, mudah direkam, dan mudah dibagikan di media sosial. Jika produk membutuhkan demonstrasi, pastikan kebutuhan teknis diuji dalam rehearsal agar tidak mengganggu momentum acara.</p><h2>Perhatikan perjalanan tamu</h2><p>Registrasi, hospitality, area display, photo spot, media corner, dan ruang interaksi produk memegang peran besar dalam membangun kesan profesional. Tamu perlu memahami agenda, tahu ke mana harus bergerak, dan mendapatkan pengalaman yang konsisten dengan karakter brand. Untuk undangan media atau VIP, siapkan alur khusus agar komunikasi tetap rapi.</p><h2>Siapkan dokumentasi dan tindak lanjut</h2><p>Dokumentasi bukan hanya arsip, tetapi aset publikasi. Foto, video highlight, soundbite pembicara, testimoni tamu, dan materi press release perlu direncanakan sejak awal. Setelah acara, tim dapat mengirim materi follow-up, laporan kehadiran, dan rangkuman insight. Dengan persiapan yang matang, product launching dapat menjadi momentum brand yang kuat, bukan sekadar acara satu hari.</p>',
                'en' => '<p>A product launch is an important moment to introduce product value, build market attention, and create a strong first impression for the audience. A good launch is more than an opening ceremony. It should combine brand storytelling, product reveal, guest experience, documentation, and follow-up strategy so the product message continues after the event ends.</p><h2>Build a clear product story</h2><p>Before discussing stage design and decoration, define the main story you want to communicate. What problem does the product solve? Who is the primary audience? What advantage should participants remember? These answers become the foundation for the theme, visuals, presentation flow, screen materials, MC script, and speaker narrative.</p><h2>Design a memorable reveal moment</h2><p>The product reveal should be prepared as the highlight of the experience. Lighting, audio, multimedia, countdown, choreography, and guest positioning need careful planning. This moment should be easy to photograph, record, and share on social media. If the product requires a demonstration, all technical requirements should be tested during rehearsal so the momentum is not interrupted.</p><h2>Pay attention to the guest journey</h2><p>Registration, hospitality, display areas, photo spots, media corners, and product interaction zones play a major role in creating a professional impression. Guests need to understand the agenda, know where to go, and experience a flow that matches the brand character. For media or VIP guests, prepare a dedicated flow to keep communication clean and controlled.</p><h2>Prepare documentation and follow-up</h2><p>Documentation is not just an archive; it is a publication asset. Photos, highlight videos, speaker soundbites, guest testimonials, and press release materials should be planned early. After the event, the team can send follow-up materials, attendance reports, and insight summaries. With strong preparation, a product launch becomes a meaningful brand momentum, not just a one-day event.</p>',
            ],
            'ide-gathering-perusahaan-engagement-tim' => [
                'id' => '<p>Gathering perusahaan memiliki peran penting dalam membangun kedekatan tim, menyegarkan energi kerja, dan memperkuat budaya organisasi. Acara gathering yang baik tidak hanya berisi permainan atau hiburan, tetapi juga perlu memiliki tujuan yang jelas, alur aktivitas yang nyaman, dan pengalaman yang relevan dengan karakter peserta. Dengan perencanaan yang tepat, gathering dapat menjadi ruang untuk apresiasi, komunikasi, dan kolaborasi yang lebih kuat.</p><h2>Tentukan tema yang menyatukan</h2><p>Tema membantu seluruh elemen acara terasa konsisten, mulai dari aktivitas, dekorasi, dress code, komunikasi undangan, hingga dokumentasi. Tema seperti stronger together, growth mindset, atau celebration of collaboration dapat diterjemahkan ke dalam games, sesi sharing, awarding, dan visual acara. Tema yang tepat membuat peserta merasa acara bukan sekadar agenda tahunan, tetapi bagian dari perjalanan bersama perusahaan.</p><h2>Kombinasikan format kegiatan</h2><p>Susun agenda dengan kombinasi aktivitas kelompok, sesi apresiasi, hiburan, waktu bebas, dan momen komunikasi perusahaan. Untuk peserta yang aktif, team building outdoor bisa menjadi pilihan. Untuk tim dengan kebutuhan relaksasi, konsep gala dinner, music performance, atau creative workshop dapat terasa lebih sesuai. Keseimbangan ini penting agar semua peserta dapat menikmati acara tanpa merasa dipaksa mengikuti aktivitas yang terlalu berat.</p><h2>Perhatikan kenyamanan peserta</h2><p>Transportasi, konsumsi, lokasi, cuaca, keamanan, fasilitas kesehatan, dan flow registrasi perlu diperhitungkan sejak awal. Jika acara dilakukan di luar kota atau outdoor, siapkan contingency plan untuk perubahan cuaca dan kondisi lapangan. Komunikasi sebelum acara juga harus jelas agar peserta memahami jadwal, perlengkapan yang perlu dibawa, dan aturan kegiatan.</p><h2>Jadikan gathering sebagai momentum</h2><p>Setelah acara selesai, manfaatkan dokumentasi, rangkuman kegiatan, dan feedback peserta untuk memperpanjang dampaknya. Foto, video highlight, dan ucapan apresiasi dapat dibagikan melalui kanal internal perusahaan. Dengan konsep yang matang, gathering bukan hanya waktu bersenang-senang, tetapi momentum untuk membangun engagement tim yang lebih kuat dan berkelanjutan.</p>',
                'en' => '<p>A company gathering plays an important role in building team connection, refreshing work energy, and strengthening organizational culture. A good gathering is not only about games or entertainment. It should have a clear objective, comfortable activity flow, and an experience that matches the participant profile. With the right planning, a gathering can become a space for appreciation, communication, and stronger collaboration.</p><h2>Choose a unifying theme</h2><p>A theme helps every event element feel consistent, from activities, decoration, dress code, invitation communication, to documentation. Themes such as stronger together, growth mindset, or celebration of collaboration can be translated into games, sharing sessions, awards, and event visuals. The right theme helps participants feel that the event is not just an annual agenda, but part of the company journey.</p><h2>Combine activity formats</h2><p>Create an agenda that mixes group activities, appreciation sessions, entertainment, free time, and company communication moments. For active participants, outdoor team building can be a strong option. For teams that need relaxation, a gala dinner, music performance, or creative workshop may feel more suitable. This balance is important so all participants can enjoy the event without feeling forced into activities that are too demanding.</p><h2>Prioritize participant comfort</h2><p>Transportation, meals, location, weather, safety, health facilities, and registration flow should be considered from the beginning. If the event takes place out of town or outdoors, prepare contingency plans for weather and field conditions. Pre-event communication must also be clear so participants understand the schedule, required items, and activity guidelines.</p><h2>Turn the gathering into momentum</h2><p>After the event, use documentation, activity summaries, and participant feedback to extend its impact. Photos, highlight videos, and appreciation messages can be shared through internal company channels. With a mature concept, a gathering becomes more than a fun day; it becomes momentum for stronger and more sustainable team engagement.</p>',
            ],
        ];
    }
}
