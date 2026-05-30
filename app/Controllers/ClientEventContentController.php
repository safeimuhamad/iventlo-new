<?php

class ClientEventContentController extends ClientPortalController
{
    public function index()
    {
        $this->requirePortal();
        $event = $this->accessibleEvent($_GET['event_id'] ?? 0);
        $canManage = ($event['access_level'] ?? '') === 'admin';
        $selectedType = $_GET['jenis'] ?? 'agenda';
        if (!in_array($selectedType, EventPortalContent::types(), true)) {
            $selectedType = 'agenda';
        }
        $contents = $canManage
            ? (new EventPortalContent())->forEvent($event['id'])
            : (new EventPortalContent())->visibleForEvent($event['id']);
        $contents = array_values(array_filter($contents, function ($content) use ($selectedType) {
            return ($content['content_type'] ?? '') === $selectedType;
        }));

        $this->portalView('client/events/content', [
            'title' => 'Konten Peserta',
            'event' => $event,
            'contents' => $contents,
            'canManage' => $canManage,
            'selectedType' => $selectedType
        ]);
    }

    public function store()
    {
        $this->requirePortal();
        $event = $this->manageableEvent($_GET['event_id'] ?? 0);
        $type = $_POST['content_type'] ?? '';
        $title = trim($_POST['title'] ?? '');
        if ($title === '' || !in_array($type, EventPortalContent::types(), true)) {
            $_SESSION['error'] = 'Jenis konten dan judul wajib diisi.';
            $this->redirect('client/events/' . $event['id'] . '/konten', ['jenis' => $type ?: 'agenda']);
        }
        $upload = $this->upload($_FILES['content_file'] ?? null, $event['id']);
        (new EventPortalContent())->add($event['id'], [
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
        ]);
        activity_log('Client Portal Content', 'create', 'Menambahkan konten peserta: ' . $type, $event['id']);
        $_SESSION['success'] = 'Konten peserta berhasil ditambahkan.';
        $this->redirect('client/events/' . $event['id'] . '/konten', ['jenis' => $type]);
    }

    public function delete()
    {
        $this->requirePortal();
        $event = $this->manageableEvent($_GET['event_id'] ?? 0);
        (new EventPortalContent())->deleteForEvent((int) ($_GET['id'] ?? 0), $event['id']);
        $_SESSION['success'] = 'Konten peserta berhasil dihapus.';
        $this->redirect('client/events/' . $event['id'] . '/konten');
    }

    private function upload($file, $eventId)
    {
        if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        $validated = validatedDocumentUpload($file);
        if (!$validated || ($file['size'] ?? 0) > 10 * 1024 * 1024) {
            $_SESSION['error'] = 'Lampiran harus berupa PDF/JPG/PNG/WEBP maksimal 10 MB.';
            $this->redirect('client/events/' . $eventId . '/konten');
        }
        $directory = __DIR__ . '/../../public/uploads/event-content';
        if (!is_dir($directory)) {
            mkdir($directory, 0775, true);
        }
        $name = 'event-' . (int) $eventId . '-' . bin2hex(random_bytes(8)) . '.' . $validated['extension'];
        if (!move_uploaded_file($file['tmp_name'], $directory . '/' . $name)) {
            $_SESSION['error'] = 'Upload lampiran gagal.';
            $this->redirect('client/events/' . $eventId . '/konten');
        }
        return ['path' => 'event-content/' . $name, 'name' => basename($file['name']), 'type' => $validated['mime']];
    }
}
