<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php
$filterStatus = $status ?? '';
?>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>
            <h3 class="mb-0">
                Purchase Request
            </h3>

            <p class="text-body fs-14 mb-0">
                Data permintaan pembelian barang dan kebutuhan operasional
            </p>
        </div>

        <?php if (can('purchase_request.create')): ?>
            <a
                href="<?= url('purchase-requests-create') ?>"
                class="btn btn-primary text-white erp-btn"
            >
                + Tambah PR
            </a>
        <?php endif; ?>

    </div>

    <div class="p-20 border-top">

        <form method="GET" class="row g-3 align-items-end">

            <input type="hidden" name="page" value="purchase-requests">

            <div class="col-md-5">
                <label class="form-label">Cari Purchase Request</label>

                <input
                    type="text"
                    name="search"
                    class="form-control erp-control erp-input"
                    placeholder="Cari nomor PR / kebutuhan / status..."
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
                    <option value="draft" <?= $filterStatus === 'draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="waiting_approval" <?= $filterStatus === 'waiting_approval' ? 'selected' : '' ?>>Waiting Approval</option>
                    <option value="approved" <?= $filterStatus === 'approved' ? 'selected' : '' ?>>Approved</option>
                    <option value="rejected" <?= $filterStatus === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                    <option value="closed" <?= $filterStatus === 'closed' ? 'selected' : '' ?>>Closed</option>
                </select>
            </div>

            <div class="col-md-2">
                <div class="d-flex gap-2 filter-action-group">
                    <button class="btn btn-primary text-white erp-btn w-100">
                        Filter
                    </button>

                    <a
                        href="<?= url('purchase-requests') ?>"
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
                        <th>No. PR</th>
                        <th>Tanggal</th>
                        <th>Request By</th>
                        <th>Divisi</th>
                        <th style="min-width:220px;">Kebutuhan</th>
                        <th>Dibutuhkan</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($items)): ?>

                        <?php foreach ($items as $item): ?>

                            <?php
                            $rowStatus = strtolower($item['status'] ?? 'draft');

                            $statusClass = match ($rowStatus) {
                                'draft' => 'bg-secondary bg-opacity-10 text-secondary',
                                'waiting_approval' => 'bg-warning bg-opacity-10 text-warning',
                                'approved' => 'bg-success bg-opacity-10 text-success',
                                'rejected' => 'bg-danger bg-opacity-10 text-danger',
                                'closed' => 'bg-dark bg-opacity-10 text-dark',
                                default => 'bg-secondary bg-opacity-10 text-secondary'
                            };
                            ?>

                            <tr>
                                <td>
                                    <a
                                        href="<?= url('purchase-requests-show') ?>?id=<?= $item['id'] ?>"
                                        class="fw-semibold text-primary text-decoration-none"
                                    >
                                        <?= htmlspecialchars($item['pr_number'] ?? '-') ?>
                                    </a>
                                </td>

                                <td>
                                    <?= !empty($item['request_date'])
                                        ? date('d M Y', strtotime($item['request_date']))
                                        : '<span class="text-muted">-</span>' ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($item['requested_by_name'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($item['department_name'] ?? '-') ?>
                                </td>

                                <td class="text-wrap" style="min-width:220px; max-width:320px;">
                                    <?= htmlspecialchars($item['purpose'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= !empty($item['needed_date'])
                                        ? date('d M Y', strtotime($item['needed_date']))
                                        : '<span class="text-muted">-</span>' ?>
                                </td>

                                <td>
                                    <span class="default-badge <?= $statusClass ?>">
                                        <?= ucwords(str_replace('_', ' ', $rowStatus)) ?>
                                    </span>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="7" class="text-center text-body py-4">
                                Belum ada data purchase request.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

        <div class="d-flex justify-content-center justify-content-sm-between align-items-center text-center flex-wrap gap-2 showing-wrap pt-15 p-20 border-top">

            <span class="fs-15">
                Showing
                <?= ($totalData ?? 0) > 0 ? (($currentPage - 1) * $limit + 1) : 0 ?>
                to
                <?= min($currentPage * $limit, $totalData ?? 0) ?>
                of
                <?= (int) ($totalData ?? 0) ?> entries
            </span>

            <?php
            $queryString =
                '&search=' . urlencode($search ?? '') .
                '&status=' . urlencode($filterStatus ?? '');

            $startPage = max(1, $currentPage - 2);
            $endPage = min($totalPages ?? 1, $currentPage + 2);
            ?>

            <?php if (($totalPages ?? 1) > 1): ?>

                <nav class="custom-pagination">
                    <ul class="pagination mb-0 justify-content-center">

                        <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                            <a
                                class="page-link icon"
                                href="<?= url('purchase-requests') ?>?p=<?= $currentPage - 1 . $queryString ?>"
                            >
                                <i class="material-symbols-outlined">west</i>
                            </a>
                        </li>

                        <?php if ($startPage > 1): ?>
                            <li class="page-item">
                                <a
                                    class="page-link"
                                    href="<?= url('purchase-requests') ?>?p=1<?= $queryString ?>"
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

                        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                            <li class="page-item">
                                <a
                                    class="page-link <?= $currentPage == $i ? 'active' : '' ?>"
                                    href="<?= url('purchase-requests') ?>?p=<?= $i . $queryString ?>"
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
                                    href="<?= url('purchase-requests') ?>?p=<?= $totalPages . $queryString ?>"
                                >
                                    <?= $totalPages ?>
                                </a>
                            </li>
                        <?php endif; ?>

                        <li class="page-item <?= $currentPage >= ($totalPages ?? 1) ? 'disabled' : '' ?>">
                            <a
                                class="page-link icon"
                                href="<?= url('purchase-requests') ?>?p=<?= $currentPage + 1 . $queryString ?>"
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