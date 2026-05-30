<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">
        <div>
            <h3 class="mb-0">Activity Logs</h3>

            <p class="text-body fs-14 mb-0">
                Riwayat aktivitas pengguna sistem
            </p>
        </div>
    </div>

    <div class="p-20 border-top">
        <form method="GET" class="row g-3 align-items-end">
            <input type="hidden" name="page" value="activity-logs">

            <div class="col-md-5">
                <label class="form-label">Cari Aktivitas</label>

                <input
                    type="text"
                    name="search"
                    class="form-control erp-control erp-input"
                    placeholder="Cari user, module, action, deskripsi..."
                    value="<?= htmlspecialchars($search ?? '') ?>"
                >
            </div>

            <div class="col-md-2">
                <div class="d-flex gap-2 filter-action-group">
                    <button type="submit" class="btn btn-primary text-white erp-btn w-100">
                        Cari
                    </button>

                    <a href="<?= url('activity-logs') ?>" class="btn btn-light erp-btn">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="default-table-area mx-minus-1">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>User</th>
                        <th>Module</th>
                        <th>Action</th>
                        <th>Description</th>
                        <th>Reference</th>
                        <th>IP Address</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($logs)): ?>
                        <?php foreach ($logs as $row): ?>
                            <?php
                            $badgeClass = [
                                'create' => 'bg-success bg-opacity-10 text-success',
                                'update' => 'bg-warning bg-opacity-10 text-warning',
                                'delete' => 'bg-danger bg-opacity-10 text-danger',
                                'approve' => 'bg-primary bg-opacity-10 text-primary',
                                'reject' => 'bg-danger bg-opacity-10 text-danger',
                                'print' => 'bg-info bg-opacity-10 text-info',
                                'generate' => 'bg-secondary bg-opacity-10 text-secondary',
                                'payment' => 'bg-dark bg-opacity-10 text-dark',
                                'view' => 'bg-info bg-opacity-10 text-info',
                            ];

                            $action = strtolower($row['action'] ?? '');
                            ?>

                            <tr>
                                <td>
                                    <?= !empty($row['created_at'])
                                        ? date('d M Y', strtotime($row['created_at']))
                                        : '<span class="text-muted">-</span>' ?>
                                </td>

                                <td>
                                    <?= !empty($row['created_at'])
                                        ? date('H:i:s', strtotime($row['created_at']))
                                        : '<span class="text-muted">-</span>' ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['user_name'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['module'] ?? '-') ?>
                                </td>

                                <td>
                                    <span class="default-badge <?= $badgeClass[$action] ?? 'bg-secondary bg-opacity-10 text-secondary' ?>">
                                        <?= strtoupper($action ?: '-') ?>
                                    </span>
                                </td>

                                <td style="min-width: 300px;">
                                    <?= htmlspecialchars($row['description'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= !empty($row['reference_number'])
                                        ? htmlspecialchars($row['reference_number'])
                                        : '<span class="text-muted">-</span>' ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['ip_address'] ?? '-') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                Belum ada activity logs.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center justify-content-sm-between align-items-center text-center flex-wrap gap-2 showing-wrap pt-15 p-20 border-top">
            <span class="fs-15">
                Showing
                <?= ($total ?? 0) > 0 ? (($page - 1) * $limit + 1) : 0 ?>
                to
                <?= min($page * $limit, $total ?? 0) ?>
                of
                <?= (int) ($total ?? 0) ?> entries
            </span>

            <?php
            $currentPage = (int) ($page ?? 1);
            $queryString = '&search=' . urlencode($search ?? '');
            $startPage = max(1, $currentPage - 2);
            $endPage = min($totalPages ?? 1, $currentPage + 2);
            ?>

            <?php if (($totalPages ?? 1) > 1): ?>
                <nav class="custom-pagination">
                    <ul class="pagination mb-0 justify-content-center">

                        <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                            <a
                                class="page-link icon"
                                href="<?= url('activity-logs') ?>?p=<?= $currentPage - 1 . $queryString ?>"
                            >
                                <i class="material-symbols-outlined">west</i>
                            </a>
                        </li>

                        <?php if ($startPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= url('activity-logs') ?>?p=1<?= $queryString ?>">
                                    1
                                </a>
                            </li>

                            <?php if ($startPage > 2): ?>
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                            <li class="page-item">
                                <a
                                    class="page-link <?= $currentPage == $i ? 'active' : '' ?>"
                                    href="<?= url('activity-logs') ?>?p=<?= $i . $queryString ?>"
                                >
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($endPage < ($totalPages ?? 1)): ?>
                            <?php if ($endPage < ($totalPages ?? 1) - 1): ?>
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            <?php endif; ?>

                            <li class="page-item">
                                <a
                                    class="page-link"
                                    href="<?= url('activity-logs') ?>?p=<?= $totalPages . $queryString ?>"
                                >
                                    <?= $totalPages ?>
                                </a>
                            </li>
                        <?php endif; ?>

                        <li class="page-item <?= $currentPage >= ($totalPages ?? 1) ? 'disabled' : '' ?>">
                            <a
                                class="page-link icon"
                                href="<?= url('activity-logs') ?>?p=<?= $currentPage + 1 . $queryString ?>"
                            >
                                <i class="material-symbols-outlined">east</i>
                            </a>
                        </li>

                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>

</div>