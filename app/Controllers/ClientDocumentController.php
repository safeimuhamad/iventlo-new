<?php

class ClientDocumentController extends ClientPortalController
{
    public function index()
    {
        $this->requirePortal();
        $event = $this->accessibleEvent($_GET['event_id'] ?? 0);

        $this->portalView('client/documents/index', [
            'title' => 'Dokumen Event',
            'event' => $event,
            'documents' => (new ClientDocument())->visibleForEvent($event['id'], $this->currentUserId())
        ]);
    }

    public function download()
    {
        $this->requirePortal();
        $document = (new ClientDocument())->findDownloadable($_GET['id'] ?? 0, $this->currentUserId());

        if (!$document) {
            $_SESSION['error'] = 'Dokumen tidak ditemukan atau tidak dapat diakses.';
            $this->redirect('client/events');
        }

        $documentDirectory = realpath(__DIR__ . '/../../public/uploads/event-documents');
        $file = $documentDirectory ? realpath($documentDirectory . '/' . basename($document['file_path'])) : false;

        if (!$file || !str_starts_with($file, $documentDirectory . DIRECTORY_SEPARATOR) || !is_file($file)) {
            $_SESSION['error'] = 'File dokumen belum tersedia.';
            $this->redirect('client/events/' . $document['event_id'] . '/documents');
        }

        activity_log('Client Document', 'download', 'Mengunduh dokumen event: ' . $document['title'], $document['id']);

        $contentType = function_exists('mime_content_type') ? mime_content_type($file) : null;
        header('Content-Type: ' . ($contentType ?: 'application/octet-stream'));
        header('Content-Disposition: attachment; filename="' . basename($document['file_name']) . '"');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }
}
