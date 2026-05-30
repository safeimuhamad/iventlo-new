<?php
$status = $item['status'] ?? 'new';
$priority = $item['priority'] ?? 'medium';

$statusLabel = [
    'new' => 'New',
    'contacted' => 'Contacted',
    'follow_up' => 'Follow Up',
    'survey' => 'Survey',
    'quotation' => 'Quotation',
    'deal' => 'Deal',
    'lost' => 'Lost',
];

$statusClass = [
    'new' => 'bg-primary bg-opacity-10 text-primary',
    'contacted' => 'bg-info bg-opacity-10 text-info',
    'follow_up' => 'bg-warning bg-opacity-10 text-warning',
    'survey' => 'bg-secondary bg-opacity-10 text-secondary',
    'quotation' => 'bg-purple bg-opacity-10 text-purple',
    'deal' => 'bg-success bg-opacity-10 text-success',
    'lost' => 'bg-danger bg-opacity-10 text-danger',
];

$priorityClass = [
    'low' => 'bg-secondary bg-opacity-10 text-secondary',
    'medium' => 'bg-warning bg-opacity-10 text-warning',
    'high' => 'bg-danger bg-opacity-10 text-danger',
];
?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Detail Marketing Lead
            </h3>

            <p class="mb-0 text-body">
                <?= htmlspecialchars($item['lead_number'] ?? '-') ?>
            </p>
        </div>

        <div class="d-flex justify-content-between justify-content-md-end align-items-center flex-wrap gap-3">

            <a
                href="<?= url('marketing-leads') ?>"
                class="btn btn-light erp-btn"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Kembali
            </a>

            <?php if (can('marketing_lead.edit')): ?>
                <a
                    href="<?= url('marketing-leads-edit') ?>?id=<?= $item['id'] ?>"
                    class="btn btn-outline-primary erp-btn"
                >
                    <i class="ri-edit-line me-1"></i>
                    Edit
                </a>
            <?php endif; ?>

            <div class="dropdown">

                <button
                    class="btn btn-primary text-white dropdown-toggle erp-btn"
                    type="button"
                    data-bs-toggle="dropdown"
                >
                    <i class="ri-settings-3-line me-1"></i>
                    Actions
                </button>

                <ul class="dropdown-menu dropdown-menu-end erp-dropdown-menu">

                    <?php if (can('quotation.create')): ?>
                        <li>
                            <a
                                href="<?= url('quotations-create-from-lead') ?>?id=<?= $item['id'] ?>"
                                class="dropdown-item erp-dropdown-item"
                            >
                                <div class="erp-dropdown-title text-primary">
                                    <i class="ri-file-list-3-line me-2"></i>
                                    Buat Penawaran
                                </div>

                                <div class="erp-dropdown-desc">
                                    Generate quotation dari lead ini
                                </div>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (can('marketing_lead.delete') && !in_array($status, ['deal', 'quotation'])): ?>
                        <li>
                            <a
                                href="<?= url('marketing-leads-delete') ?>?id=<?= $item['id'] ?>"
                                class="dropdown-item erp-dropdown-item"
                                onclick="return confirm('Hapus lead ini?')"
                            >
                                <div class="erp-dropdown-title text-danger">
                                    <i class="ri-delete-bin-line me-2"></i>
                                    Hapus Lead
                                </div>

                                <div class="erp-dropdown-desc">
                                    Hapus data marketing lead
                                </div>
                            </a>
                        </li>
                    <?php endif; ?>

                </ul>

            </div>

        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="row g-4">

        <div class="col-md-3">
            <div class="erp-detail-label">
                Status
            </div>

            <div class="erp-detail-value">
                <span class="default-badge <?= $statusClass[$status] ?>">
                    <?= $statusLabel[$status] ?>
                </span>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">
                Priority
            </div>

            <div class="erp-detail-value">
                <span class="default-badge <?= $priorityClass[$priority] ?>">
                    <?= ucfirst($priority) ?>
                </span>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">
                Company
            </div>

            <div class="erp-detail-value">
                <?= htmlspecialchars($item['company_name'] ?? '-') ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">
                Estimasi Nilai
            </div>

            <div class="erp-detail-value text-primary">
                Rp <?= number_format((float) ($item['estimated_value'] ?? 0), 0, ',', '.') ?>
            </div>
        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title">
            Informasi Lead
        </h4>
    </div>

    <div class="p-20">

        <div class="row g-4">

            <div class="col-md-4">
                <div class="erp-detail-label">Nomor Lead</div>
                <div class="erp-detail-value"><?= htmlspecialchars($item['lead_number'] ?? '-') ?></div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">Company</div>
                <div class="erp-detail-value"><?= htmlspecialchars($item['company_name'] ?? '-') ?></div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">PIC</div>
                <div class="erp-detail-value"><?= htmlspecialchars($item['pic_name'] ?? '-') ?></div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">No. HP</div>
                <div class="erp-detail-value"><?= htmlspecialchars($item['phone'] ?? '-') ?></div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">Email</div>
                <div class="erp-detail-value"><?= htmlspecialchars($item['email'] ?? '-') ?></div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">Source</div>
                <div class="erp-detail-value"><?= htmlspecialchars($item['source'] ?? '-') ?></div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">Minat Layanan</div>
                <div class="erp-detail-value"><?= htmlspecialchars($item['service_interest'] ?? '-') ?></div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">Assigned To</div>
                <div class="erp-detail-value"><?= htmlspecialchars($item['assigned_name'] ?? '-') ?></div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">Estimasi Nilai</div>
                <div class="erp-detail-value text-primary">
                    Rp <?= number_format((float) ($item['estimated_value'] ?? 0), 0, ',', '.') ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="erp-detail-label">Dibuat Oleh</div>
                <div class="erp-detail-value"><?= htmlspecialchars($item['created_by_name'] ?? '-') ?></div>
            </div>

            <div class="col-md-6">
                <div class="erp-detail-label">Tanggal Dibuat</div>
                <div class="erp-detail-value">
                    <?= !empty($item['created_at']) ? date('d M Y H:i', strtotime($item['created_at'])) : '-' ?>
                </div>
            </div>

            <div class="col-md-12">
                <div class="erp-detail-label">Alamat</div>
                <div class="erp-detail-value">
                    <?= nl2br(htmlspecialchars($item['address'] ?? '-')) ?>
                </div>
            </div>

            <div class="col-md-12">
                <div class="erp-detail-label">Catatan</div>
                <div class="erp-detail-value">
                    <?= nl2br(htmlspecialchars($item['notes'] ?? '-')) ?>
                </div>
            </div>

        </div>

    </div>

</div>

<?php if (can('marketing_lead.follow_up')): ?>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title">
            Tambah Follow Up
        </h4>
    </div>

    <div class="p-20">

        <form method="POST" action="<?= url('marketing-leads-followup-store') ?>">

            <input type="hidden" name="lead_id" value="<?= $item['id'] ?>">

            <div class="row">

                <div class="col-md-4 mb-3">
                    <label class="form-label">Tanggal Follow Up</label>
                    <input type="date" name="followup_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Metode</label>

                    <select name="followup_type" class="form-select">
                        <option value="whatsapp">WhatsApp</option>
                        <option value="call">Call</option>
                        <option value="email">Email</option>
                        <option value="meeting">Meeting</option>
                        <option value="survey">Survey</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Hasil</label>

                    <select name="result" class="form-select">
                        <option value="pending">Pending</option>
                        <option value="interested">Interested</option>
                        <option value="not_interested">Not Interested</option>
                        <option value="need_follow_up">Need Follow Up</option>
                        <option value="survey_scheduled">Survey Scheduled</option>
                        <option value="quotation_requested">Quotation Requested</option>
                        <option value="deal">Deal</option>
                        <option value="lost">Lost</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Follow Up Berikutnya</label>
                    <input type="date" name="next_followup_date" class="form-control">
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" rows="3" class="form-control"></textarea>
                </div>

            </div>

            <button class="btn btn-primary text-white">
                Simpan Follow Up
            </button>

        </form>

    </div>

</div>

<?php endif; ?>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title">
            Riwayat Follow Up
        </h4>
    </div>

    <div class="default-table-area mx-minus-1">

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Metode</th>
                        <th>Hasil</th>
                        <th>Next Follow Up</th>
                        <th>Catatan</th>
                        <th>Oleh</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($followups)): ?>

                        <?php foreach ($followups as $followup): ?>

                            <tr>
                                <td><?= htmlspecialchars($followup['followup_date'] ?? '-') ?></td>
                                <td><?= ucfirst(htmlspecialchars($followup['followup_type'] ?? '-')) ?></td>
                                <td><?= ucwords(str_replace('_', ' ', htmlspecialchars($followup['result'] ?? '-'))) ?></td>
                                <td><?= htmlspecialchars($followup['next_followup_date'] ?? '-') ?></td>
                                <td><?= nl2br(htmlspecialchars($followup['notes'] ?? '-')) ?></td>
                                <td><?= htmlspecialchars($followup['created_by_name'] ?? '-') ?></td>
                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="6" class="text-center text-body py-4">
                                Belum ada riwayat follow up.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>