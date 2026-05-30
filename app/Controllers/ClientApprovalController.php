<?php

class ClientApprovalController extends ClientPortalController
{
    public function eventIndex()
    {
        $this->requirePortal();
        $event = $this->accessibleEvent($_GET['event_id'] ?? 0);

        $this->portalView('client/approvals/index', [
            'title' => 'Approval Event',
            'event' => $event,
            'approvals' => (new ClientApproval())->forEvent($event['id'], $this->currentUserId())
        ]);
    }

    public function show()
    {
        $this->requirePortal();
        $approval = (new ClientApproval())->findAccessible($_GET['id'] ?? 0, $this->currentUserId());

        if (!$approval) {
            $_SESSION['error'] = 'Approval tidak ditemukan atau tidak dapat diakses.';
            $this->redirect('client/events');
        }

        $this->portalView('client/approvals/show', [
            'title' => 'Detail Approval',
            'approval' => $approval,
            'steps' => (new ApprovalRequest())->getSteps($approval['id']),
            'documents' => (new ClientDocument())->visibleForEvent($approval['event_id'], $this->currentUserId())
        ]);
    }

    public function approve()
    {
        $this->requirePortal();
        $approval = $this->manageableApproval();
        $notes = trim($_POST['notes'] ?? '');

        if ((new ApprovalRequest())->approve($approval['id'], $notes)) {
            activity_log('Client Approval', 'approve', 'Client menyetujui approval event', $approval['id']);
            $_SESSION['success'] = 'Approval berhasil disetujui.';
        } else {
            $_SESSION['error'] = 'Approval tidak dapat diproses pada status saat ini.';
        }

        $this->redirect('client/approvals/' . $approval['id']);
    }

    public function revision()
    {
        $this->requirePortal();
        $approval = $this->manageableApproval();
        $comment = trim($_POST['comment'] ?? '');

        if ($comment === '') {
            $_SESSION['error'] = 'Komentar revisi wajib diisi.';
            $this->redirect('client/approvals/' . $approval['id']);
        }

        if ((new ApprovalRequest())->reject($approval['id'], 'Permintaan revisi client: ' . $comment)) {
            activity_log('Client Approval', 'request_revision', 'Client meminta revisi approval event', $approval['id']);
            (new ClientNotification())->createForEvent(
                $approval['event_id'],
                'Permintaan revisi approval',
                'Revisi diminta untuk approval ' . ($approval['reference_no'] ?: ('APP-' . $approval['id'])) . '.',
                'approval_revision'
            );
            $_SESSION['success'] = 'Permintaan revisi berhasil dikirim.';
        } else {
            $_SESSION['error'] = 'Permintaan revisi tidak dapat diproses pada status saat ini.';
        }

        $this->redirect('client/approvals/' . $approval['id']);
    }

    private function manageableApproval()
    {
        $approval = (new ClientApproval())->findAccessible($_GET['id'] ?? 0, $this->currentUserId());

        if (!$approval) {
            $_SESSION['error'] = 'Approval tidak ditemukan atau tidak dapat diakses.';
            $this->redirect('client/events');
        }

        $this->manageableEvent($approval['event_id']);

        return $approval;
    }
}
