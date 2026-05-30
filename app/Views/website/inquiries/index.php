<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>
            <h3 class="mb-0">Inquiry Leads</h3>
            <p class="text-body fs-14 mb-0">
                Data inquiry yang masuk dari form kontak website.
            </p>
        </div>

    </div>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger m-20">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="default-table-area mx-minus-1">

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Kontak</th>
                        <th>Kebutuhan</th>
                        <th>Status</th>
                        <th>Follow Up</th>
                        <th>Tanggal Masuk</th>
                        <th width="150">Action</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($inquiries)): ?>

                        <?php foreach ($inquiries as $inquiry): ?>

                            <?php
                            $status = strtolower($inquiry['status'] ?? 'new');

                            $statusClass = match ($status) {
                                'contacted' => 'bg-info bg-opacity-10 text-info',
                                'follow_up' => 'bg-warning bg-opacity-10 text-warning',
                                'closed' => 'bg-success bg-opacity-10 text-success',
                                default => 'bg-primary bg-opacity-10 text-primary',
                            };

                            $statusLabel = match ($status) {
                                'contacted' => 'Contacted',
                                'follow_up' => 'Follow Up',
                                'closed' => 'Closed',
                                default => 'New',
                            };
                            ?>

                            <tr>

                                <td>
                                    <a
                                        href="<?= url('website-inquiries-show') ?>?id=<?= $inquiry['id'] ?>"
                                        class="fw-semibold text-primary text-decoration-none"
                                    >
                                        <?= htmlspecialchars($inquiry['name'] ?? '-') ?>
                                    </a>

                                    <?php if (!empty($inquiry['company_name'])): ?>
                                        <div class="text-body fs-14">
                                            <?= htmlspecialchars($inquiry['company_name']) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($inquiry['phone'] ?? '-') ?>

                                    <div class="text-body fs-14">
                                        <?= htmlspecialchars($inquiry['email'] ?? '-') ?>
                                    </div>
                                </td>

                                <td>
                                    <?= htmlspecialchars($inquiry['service_interest'] ?? '-') ?>
                                </td>

                                <td>
                                    <span class="default-badge <?= $statusClass ?>">
                                        <?= $statusLabel ?>
                                    </span>
                                </td>

                                <td>
                                    <?= !empty($inquiry['follow_up_date'])
                                        ? date('d M Y', strtotime($inquiry['follow_up_date']))
                                        : '-' ?>
                                </td>

                                <td>
                                    <?= !empty($inquiry['created_at'])
                                        ? date('d M Y H:i', strtotime($inquiry['created_at']))
                                        : '-' ?>
                                </td>

                                <td>
                                    <div class="d-flex gap-2">
                                        <?php if (can('website_inquiry.show')): ?>
                                            <a
                                                href="<?= url('website-inquiries-show') ?>?id=<?= $inquiry['id'] ?>"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                Detail
                                            </a>
                                        <?php endif; ?>

                                        <?php if (can('website_inquiry.delete')): ?>
                                            <a
                                                href="<?= url('website-inquiries-delete') ?>?id=<?= $inquiry['id'] ?>"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Yakin ingin menghapus inquiry ini?')"
                                            >
                                                Hapus
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="7" class="text-center text-body py-4">
                                Belum ada inquiry.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

        <?php require __DIR__ . '/../../components/pagination.php'; ?>

    </div>

</div>
