<?php

class MasterEventController extends Controller
{
    public function index()
    {
        $this->authorize();
        activity_log('Master Event', 'view', 'Melihat master event client');

        $this->view('master-events/index', [
            'title' => 'Master Event Client',
            'events' => (new MasterEvent())->all()
        ]);
    }

    public function create()
    {
        $this->authorize();
        $this->view('master-events/form', [
            'title' => 'Tambah Master Event',
            'event' => ['event_code' => 'EVT-' . date('Ym') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 4))]
        ]);
    }

    public function store()
    {
        $this->authorize();
        $data = $this->validatedEventData();
        $id = (new MasterEvent())->create($data);

        activity_log('Master Event', 'create', 'Membuat master event: ' . $data['event_code'], $id);
        $_SESSION['success'] = 'Master event berhasil dibuat. Tambahkan akses client, milestone, dan dokumen.';
        $this->redirect('master-events-edit', ['id' => $id]);
    }

    public function edit()
    {
        $this->authorize();
        $id = (int) ($_GET['id'] ?? 0);
        $model = new MasterEvent();
        $event = $model->find($id);

        if (!$event) {
            $_SESSION['error'] = 'Master event tidak ditemukan.';
            $this->redirect('master-events');
        }

        $this->view('master-events/form', [
            'title' => 'Edit Master Event',
            'event' => $event,
            'clientUsers' => $model->clientUsers(),
            'accessList' => $model->accessList($id),
            'milestones' => $model->milestones($id),
            'documents' => $model->documents($id),
            'availableApprovals' => $model->availableApprovals($id),
            'linkedApprovals' => $model->linkedApprovals($id),
            'ticketStats' => (new EventTicket())->stats($id),
            'ticketOrders' => (new EventTicket())->ordersForEvent($id),
            'ticketAttendees' => (new EventTicket())->attendeesForEvent($id),
            'portalContents' => (new EventPortalContent())->forEvent($id)
        ]);
    }

    public function update()
    {
        $this->authorize();
        $id = (int) ($_POST['id'] ?? 0);
        $data = $this->validatedEventData();
        (new MasterEvent())->update($id, $data);

        activity_log('Master Event', 'update', 'Mengubah master event: ' . $data['event_code'], $id);
        $_SESSION['success'] = 'Master event berhasil diperbarui.';
        $this->redirect('master-events-edit', ['id' => $id]);
    }

    public function storeAccess()
    {
        $this->authorize();
        $eventId = (int) ($_POST['event_id'] ?? 0);
        $userId = (int) ($_POST['user_id'] ?? 0);
        $level = in_array($_POST['access_level'] ?? '', ['admin', 'viewer'], true) ? $_POST['access_level'] : 'viewer';
        $status = in_array($_POST['status'] ?? '', ['active', 'inactive'], true) ? $_POST['status'] : 'active';

        $model = new MasterEvent();
        if (!$eventId || !$userId || !$model->isClientUser($userId)) {
            $_SESSION['error'] = 'Pilih user aktif dengan role Client.';
            $this->redirect('master-events-edit', ['id' => $eventId]);
        }

        $model->saveAccess($eventId, $userId, $level, $status);
        activity_log('Master Event Access', 'save', 'Memperbarui akses client event', $eventId);
        $_SESSION['success'] = 'Akses client berhasil disimpan.';
        $this->redirect('master-events-edit', ['id' => $eventId]);
    }

    public function storeMilestone()
    {
        $this->authorize();
        $eventId = (int) ($_POST['event_id'] ?? 0);
        $title = trim($_POST['title'] ?? '');

        if (!$eventId || $title === '') {
            $_SESSION['error'] = 'Judul milestone wajib diisi.';
            $this->redirect('master-events-edit', ['id' => $eventId]);
        }

        $status = in_array($_POST['status'] ?? '', ['pending', 'progress', 'completed'], true) ? $_POST['status'] : 'pending';
        (new MasterEvent())->addMilestone($eventId, [
            'title' => $title,
            'description' => trim($_POST['description'] ?? ''),
            'due_date' => $_POST['due_date'] ?? '',
            'status' => $status,
            'sort_order' => $_POST['sort_order'] ?? 0,
            'visible_to_client' => isset($_POST['visible_to_client']) ? 1 : 0
        ]);

        if ($status === 'completed' && isset($_POST['visible_to_client'])) {
            (new ClientNotification())->createForEvent($eventId, 'Milestone selesai', $title . ' telah selesai.', 'milestone_completed');
        }

        activity_log('Master Event Milestone', 'create', 'Menambahkan milestone client', $eventId);
        $_SESSION['success'] = 'Milestone berhasil ditambahkan.';
        $this->redirect('master-events-edit', ['id' => $eventId]);
    }

    public function storeDocument()
    {
        $this->authorize();
        $eventId = (int) ($_POST['event_id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $file = $_FILES['document_file'] ?? null;

        if (!$eventId || $title === '' || !$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'Judul dan file dokumen wajib diisi.';
            $this->redirect('master-events-edit', ['id' => $eventId]);
        }

        $allowed = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'png', 'jpg', 'jpeg'];
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $allowed, true) || ($file['size'] ?? 0) > 10 * 1024 * 1024) {
            $_SESSION['error'] = 'Format file tidak didukung atau ukuran melebihi 10 MB.';
            $this->redirect('master-events-edit', ['id' => $eventId]);
        }

        $directory = __DIR__ . '/../../public/uploads/event-documents';
        if (!is_dir($directory)) {
            mkdir($directory, 0775, true);
        }
        $storedName = 'event-' . $eventId . '-' . bin2hex(random_bytes(8)) . '.' . $extension;
        if (!move_uploaded_file($file['tmp_name'], $directory . '/' . $storedName)) {
            $_SESSION['error'] = 'Upload dokumen gagal.';
            $this->redirect('master-events-edit', ['id' => $eventId]);
        }

        $category = in_array($_POST['category'] ?? '', ['proposal', 'quotation', 'contract', 'invoice', 'rundown', 'layout', 'report', 'other'], true) ? $_POST['category'] : 'other';
        $visible = isset($_POST['visible_to_client']) ? 1 : 0;
        (new MasterEvent())->addDocument($eventId, [
            'title' => $title,
            'category' => $category,
            'file_path' => 'uploads/event-documents/' . $storedName,
            'file_name' => basename($file['name']),
            'file_type' => $file['type'] ?? null,
            'visible_to_client' => $visible
        ]);

        if ($visible) {
            (new ClientNotification())->createForEvent($eventId, 'Dokumen baru', $title . ' tersedia di Document Center.', 'document_new');
        }

        activity_log('Master Event Document', 'upload', 'Mengunggah dokumen client', $eventId);
        $_SESSION['success'] = 'Dokumen berhasil diunggah.';
        $this->redirect('master-events-edit', ['id' => $eventId]);
    }

    public function storeApproval()
    {
        $this->authorize();
        $eventId = (int) ($_POST['event_id'] ?? 0);
        $approvalId = (int) ($_POST['approval_id'] ?? 0);

        if (!$eventId || !$approvalId) {
            $_SESSION['error'] = 'Approval harus dipilih.';
            $this->redirect('master-events-edit', ['id' => $eventId]);
        }

        if (!(new MasterEvent())->linkApproval($eventId, $approvalId)) {
            $_SESSION['error'] = 'Approval tidak tersedia atau sudah terkait dengan event lain.';
            $this->redirect('master-events-edit', ['id' => $eventId]);
        }
        (new ClientNotification())->createForEvent(
            $eventId,
            'Approval baru',
            'Terdapat approval baru yang memerlukan perhatian Anda.',
            'approval_new'
        );
        activity_log('Master Event Approval', 'link', 'Mengaitkan approval ke event client', $approvalId);
        $_SESSION['success'] = 'Approval berhasil dikaitkan ke event client.';
        $this->redirect('master-events-edit', ['id' => $eventId]);
    }

    public function updateTicketPayment()
    {
        $this->authorize();
        $eventId = (int) ($_POST['event_id'] ?? 0);
        $orderId = (int) ($_POST['order_id'] ?? 0);
        $status = $_POST['payment_status'] ?? '';

        if (!$eventId || !$orderId || !in_array($status, ['paid', 'cancelled'], true)) {
            $_SESSION['error'] = 'Transaksi tiket tidak valid.';
            $this->redirect('master-events-edit', ['id' => $eventId]);
        }

        (new EventTicket())->updatePaymentStatus(
            $eventId,
            $orderId,
            $status,
            trim($_POST['payment_note'] ?? ''),
            (int) ($_SESSION['user_id'] ?? 0)
        );

        activity_log('Event Ticket', 'payment', 'Memperbarui status pembayaran tiket: ' . $status, $orderId);
        $_SESSION['success'] = $status === 'paid' ? 'Pembayaran tiket dikonfirmasi.' : 'Pemesanan tiket dibatalkan.';
        $this->redirect('master-events-edit', ['id' => $eventId]);
    }

    public function checkInTicket()
    {
        $this->authorize();
        $eventId = (int) ($_POST['event_id'] ?? 0);
        $_SESSION['error'] = 'Konfirmasi kehadiran peserta dilakukan melalui scan QR Code di lokasi acara.';
        $this->redirect('master-events-edit', ['id' => $eventId]);
    }

    public function viewTicketProof()
    {
        $this->authorize();
        $order = (new EventTicket())->findOrder((int) ($_GET['id'] ?? 0));
        $file = __DIR__ . '/../../storage/' . ltrim((string) ($order['payment_proof'] ?? ''), '/');

        if (!$order || empty($order['payment_proof']) || !is_file($file)) {
            http_response_code(404);
            exit('Bukti pembayaran tidak ditemukan.');
        }

        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file) ?: 'application/octet-stream';
        header('Content-Type: ' . $mime);
        header('Content-Disposition: inline; filename="' . basename($file) . '"');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }

    public function activateAttendanceBarcode()
    {
        $this->authorize();
        $eventId = (int) ($_POST['event_id'] ?? 0);

        if (!$eventId || !(new MasterEvent())->activateAttendanceBarcode($eventId)) {
            $_SESSION['error'] = 'Event tidak ditemukan.';
            $this->redirect('master-events-edit', ['id' => $eventId]);
        }

        activity_log('Event Attendance', 'activate_barcode', 'Mengaktifkan QR Code kehadiran event', $eventId);
        $_SESSION['success'] = 'QR Code check-in berhasil diaktifkan dan siap dicetak.';
        $this->redirect('master-events-edit', ['id' => $eventId]);
    }

    public function printAttendanceBarcode()
    {
        $this->authorize();
        $event = (new MasterEvent())->find((int) ($_GET['id'] ?? 0));

        if (!$event || empty($event['attendance_token']) || empty($event['attendance_checkin_enabled'])) {
            $_SESSION['error'] = 'Aktifkan QR Code check-in terlebih dahulu.';
            $this->redirect('master-events-edit', ['id' => (int) ($_GET['id'] ?? 0)]);
        }

        $this->frontView('master-events/barcode', [
            'title' => 'Cetak QR Code Check-in',
            'event' => $event,
            'scanUrl' => frontUrl('member-attendance-scan', ['slug' => $event['attendance_token']])
        ]);
    }

    public function storePortalContent()
    {
        $this->authorize();
        $eventId = (int) ($_POST['event_id'] ?? 0);
        $data = $this->portalContentData($eventId);

        if (!$eventId || !$data) {
            $this->redirect('master-events-edit', ['id' => $eventId]);
        }

        (new EventPortalContent())->add($eventId, $data);
        activity_log('Event Portal Content', 'create', 'Menambahkan konten peserta: ' . $data['content_type'], $eventId);
        $_SESSION['success'] = 'Konten portal peserta berhasil ditambahkan.';
        $this->redirect('master-events-edit', ['id' => $eventId]);
    }

    public function deletePortalContent()
    {
        $this->authorize();
        $eventId = (int) ($_POST['event_id'] ?? 0);
        (new EventPortalContent())->deleteForEvent((int) ($_POST['id'] ?? 0), $eventId);
        $_SESSION['success'] = 'Konten portal peserta berhasil dihapus.';
        $this->redirect('master-events-edit', ['id' => $eventId]);
    }

    private function portalContentData($eventId)
    {
        $title = trim($_POST['title'] ?? '');
        $type = $_POST['content_type'] ?? '';
        if ($title === '' || !in_array($type, EventPortalContent::types(), true)) {
            $_SESSION['error'] = 'Jenis konten dan judul wajib diisi.';
            return null;
        }

        $upload = $this->uploadPortalContent($_FILES['content_file'] ?? null, $eventId);
        if (!empty($_FILES['content_file']['name']) && !$upload) {
            return null;
        }
        return [
            'content_type' => $type,
            'title' => $title,
            'subtitle' => trim($_POST['subtitle'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'scheduled_at' => trim($_POST['scheduled_at'] ?? ''),
            'location' => trim($_POST['location'] ?? ''),
            'file_path' => $upload['path'] ?? null,
            'file_name' => $upload['name'] ?? null,
            'file_type' => $upload['type'] ?? null,
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
            'visible_to_member' => isset($_POST['visible_to_member']) ? 1 : 0
        ];
    }

    private function uploadPortalContent($file, $eventId)
    {
        if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK || ($file['size'] ?? 0) > 10 * 1024 * 1024) {
            $_SESSION['error'] = 'File konten gagal diunggah atau lebih dari 10 MB.';
            return null;
        }
        $allowed = ['pdf', 'jpg', 'jpeg', 'png', 'webp'];
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $allowed, true)) {
            $_SESSION['error'] = 'File konten harus berupa PDF atau gambar.';
            return null;
        }
        $directory = __DIR__ . '/../../public/uploads/event-content';
        if (!is_dir($directory)) {
            mkdir($directory, 0775, true);
        }
        $name = 'event-' . (int) $eventId . '-' . bin2hex(random_bytes(8)) . '.' . $extension;
        if (!move_uploaded_file($file['tmp_name'], $directory . '/' . $name)) {
            $_SESSION['error'] = 'Upload file konten gagal.';
            return null;
        }
        return ['path' => 'event-content/' . $name, 'name' => basename($file['name']), 'type' => $file['type'] ?? null];
    }

    private function validatedEventData()
    {
        $code = trim($_POST['event_code'] ?? '');
        $title = trim($_POST['title'] ?? '');
        $clientName = trim($_POST['client_name'] ?? '');

        if ($code === '' || $title === '' || $clientName === '') {
            $_SESSION['error'] = 'Kode event, nama event, dan client wajib diisi.';
            $this->back();
        }

        $status = in_array($_POST['status'] ?? '', ['planning', 'preparation', 'on_going', 'completed', 'cancelled'], true) ? $_POST['status'] : 'planning';

        $existingCover = trim($_POST['existing_cover_image'] ?? '');
        $coverImage = $existingCover;
        if (!empty($_FILES['cover_image']['name'])) {
            $uploaded = $this->uploadCoverImage($_FILES['cover_image']);
            if (!$uploaded) {
                $_SESSION['error'] = 'Cover event harus berupa gambar JPG, PNG, atau WEBP maksimal 5 MB.';
                $this->back();
            }
            $coverImage = $uploaded;
        }

        $slug = $this->slug($_POST['public_slug'] ?? $title);
        $slugEn = $this->slug($_POST['public_slug_en'] ?? ($_POST['title_en'] ?? $title));
        $isPublic = isset($_POST['is_public']) ? 1 : 0;
        $isPaid = isset($_POST['is_paid']) ? 1 : 0;
        $salesStatus = in_array($_POST['ticket_sales_status'] ?? '', ['closed', 'open', 'sold_out'], true)
            ? $_POST['ticket_sales_status']
            : 'closed';

        return [
            'event_code' => $code,
            'title' => $title,
            'title_en' => trim($_POST['title_en'] ?? ''),
            'client_name' => $clientName,
            'status' => $status,
            'event_date' => $_POST['event_date'] ?? '',
            'end_date' => $_POST['end_date'] ?? '',
            'venue' => trim($_POST['venue'] ?? ''),
            'venue_en' => trim($_POST['venue_en'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'description_en' => trim($_POST['description_en'] ?? ''),
            'progress' => max(0, min(100, (int) ($_POST['progress'] ?? 0))),
            'public_slug' => $isPublic ? ($slug ?: null) : null,
            'public_slug_en' => $isPublic ? ($slugEn ?: null) : null,
            'cover_image' => $coverImage ?: null,
            'is_public' => $isPublic,
            'is_paid' => $isPaid,
            'ticket_price' => $isPaid ? max(0, (float) ($_POST['ticket_price'] ?? 0)) : 0,
            'participant_quota' => max(0, (int) ($_POST['participant_quota'] ?? 0)),
            'ticket_sales_status' => ($isPublic && $isPaid) ? $salesStatus : 'closed'
        ];
    }

    private function uploadCoverImage($file)
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK || ($file['size'] ?? 0) > 5 * 1024 * 1024) {
            return null;
        }

        $extension = validatedImageExtension($file);
        if ($extension === null) {
            return null;
        }

        $directory = __DIR__ . '/../../public/uploads/events';
        if (!is_dir($directory)) {
            mkdir($directory, 0775, true);
        }

        $name = 'event-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . $extension;
        if (!move_uploaded_file($file['tmp_name'], $directory . '/' . $name)) {
            return null;
        }

        return 'events/' . $name;
    }

    private function slug($value)
    {
        $value = strtolower(trim((string) $value));
        $value = preg_replace('/[^a-z0-9\s-]/', '', $value);
        $value = preg_replace('/[\s-]+/', '-', $value);

        return trim($value, '-');
    }

    private function authorize()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('master_event.manage');
    }
}
