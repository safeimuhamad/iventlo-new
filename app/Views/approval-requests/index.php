<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">
        <div>
            <h3 class="mb-0">
                Approval Request
            </h3>

            <p class="text-body fs-14 mb-0">
                Monitoring proses approval dokumen dan transaksi
            </p>
        </div>
    </div>

    <div class="p-20 border-top">

        <form method="GET" class="row g-3 align-items-end">

            <input type="hidden" name="page" value="approval-requests">

            <div class="col-md-5">
                <label class="form-label">Cari Approval</label>

                <input
                    type="text"
                    name="search"
                    class="form-control erp-control erp-input"
                    placeholder="Cari module atau nomor dokumen..."
                    value="<?= htmlspecialchars($search ?? '') ?>"
                >
            </div>

            <div class="col-md-3">
                <label class="form-label">Status</label>

                <select
                    name="status"
                    class="form-select erp-control erp-select"
                >
                    <option value="">Semua Status</option>

                    <option value="waiting_approval" <?= (($status ?? '') === 'waiting_approval') ? 'selected' : '' ?>>
                        Menunggu Approval
                    </option>

                    <option value="approved" <?= (($status ?? '') === 'approved') ? 'selected' : '' ?>>
                        Disetujui
                    </option>

                    <option value="rejected" <?= (($status ?? '') === 'rejected') ? 'selected' : '' ?>>
                        Ditolak
                    </option>

                    <option value="cancelled" <?= (($status ?? '') === 'cancelled') ? 'selected' : '' ?>>
                        Dibatalkan
                    </option>
                </select>
            </div>

            <div class="col-md-2">
                <div class="d-flex gap-2 filter-action-group">

                    <button
                        type="submit"
                        class="btn btn-primary text-white erp-btn w-100"
                    >
                        Filter
                    </button>

                    <a
                        href="<?= url('approval-requests') ?>"
                        class="btn btn-light erp-btn"
                    >
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
                        <th>Dokumen</th>
                        <th>Module</th>
                        <th class="text-end">Nominal</th>
                        <th>Level Saat Ini</th>
                        <th>Status</th>
                        <th>Diajukan Oleh</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($approvalRequests)): ?>

                        <?php foreach ($approvalRequests as $row): ?>

                            <?php
                            $statusLabels = [
                                'waiting_approval' => 'Menunggu Approval',
                                'approved' => 'Disetujui',
                                'rejected' => 'Ditolak',
                                'cancelled' => 'Dibatalkan'
                            ];

                            $statusClass = [
                                'waiting_approval' => 'bg-warning bg-opacity-10 text-warning',
                                'approved' => 'bg-success bg-opacity-10 text-success',
                                'rejected' => 'bg-danger bg-opacity-10 text-danger',
                                'cancelled' => 'bg-dark bg-opacity-10 text-dark'
                            ];

                            $rowStatus = $row['status'] ?? '';
                            ?>

                            <tr>

                                <td>
                                    <a
                                        href="<?= url('approval-requests-show') ?>?id=<?= $row['id'] ?>"
                                        class="fw-semibold text-primary text-decoration-none"
                                    >
                                        <?= htmlspecialchars($row['reference_no'] ?? '-') ?>
                                    </a>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['module_name'] ?? '-') ?>
                                </td>

                                <td class="text-end">
                                    <?php if (($row['amount'] ?? 0) > 0): ?>
                                        Rp <?= number_format((float) ($row['amount'] ?? 0), 0, ',', '.') ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <span class="default-badge bg-info bg-opacity-10 text-info">
                                        Level <?= (int) ($row['current_level'] ?? 1) ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="default-badge <?= $statusClass[$rowStatus] ?? 'bg-secondary bg-opacity-10 text-secondary' ?>">
                                        <?= $statusLabels[$rowStatus] ?? '-' ?>
                                    </span>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['requested_by_name'] ?? '-') ?>
                                </td>

                                <td>
                                    <?php if (!empty($row['requested_at'])): ?>
                                        <?= date('d M Y H:i', strtotime($row['requested_at'])) ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Belum ada approval request.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

    <div class="d-flex justify-content-center justify-content-sm-between align-items-center text-center flex-wrap gap-2 showing-wrap pt-15 p-20 border-top">

        <span class="fs-15">
            Showing
            <?= ($totalRows ?? 0) > 0 ? (($p - 1) * $limit + 1) : 0 ?>
            to
            <?= min($p * $limit, $totalRows ?? 0) ?>
            of
            <?= (int) ($totalRows ?? 0) ?> entries
        </span>

        <?php
        $currentPage = (int) ($p ?? 1);

        $queryString =
            '&search=' . urlencode($search ?? '') .
            '&status=' . urlencode($status ?? '');

        $startPage = max(1, $currentPage - 2);
        $endPage = min($totalPages ?? 1, $currentPage + 2);
        ?>

        <?php if (($totalPages ?? 1) > 1): ?>

            <nav class="custom-pagination">

                <ul class="pagination mb-0 justify-content-center">

                    <!-- Previous -->
                    <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">

                        <a
                            class="page-link icon"
                            href="<?= url('approval-requests') ?>?p=<?= $currentPage - 1 . $queryString ?>"
                        >
                            <i class="material-symbols-outlined">west</i>
                        </a>

                    </li>

                    <!-- First -->
                    <?php if ($startPage > 1): ?>

                        <li class="page-item">
                            <a
                                class="page-link"
                                href="<?= url('approval-requests') ?>?p=1<?= $queryString ?>"
                            >
                                1
                            </a>
                        </li>

                        <?php if ($startPage > 2): ?>
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        <?php endif; ?>

                    <?php endif; ?>

                    <!-- Pages -->
                    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>

                        <li class="page-item">

                            <a
                                class="page-link <?= $currentPage == $i ? 'active' : '' ?>"
                                href="<?= url('approval-requests') ?>?p=<?= $i . $queryString ?>"
                            >
                                <?= $i ?>
                            </a>

                        </li>

                    <?php endfor; ?>

                    <!-- Last -->
                    <?php if ($endPage < ($totalPages ?? 1)): ?>

                        <?php if ($endPage < ($totalPages ?? 1) - 1): ?>
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        <?php endif; ?>

                        <li class="page-item">
                            <a
                                class="page-link"
                                href="<?= url('approval-requests') ?>?p=<?= $totalPages . $queryString ?>"
                            >
                                <?= $totalPages ?>
                            </a>
                        </li>

                    <?php endif; ?>

                    <!-- Next -->
                    <li class="page-item <?= $currentPage >= ($totalPages ?? 1) ? 'disabled' : '' ?>">

                        <a
                            class="page-link icon"
                            href="<?= url('approval-requests') ?>?p=<?= $currentPage + 1 . $queryString ?>"
                        >
                            <i class="material-symbols-outlined">east</i>
                        </a>

                    </li>

                </ul>

            </nav>

        <?php endif; ?>

    </div>

</div>