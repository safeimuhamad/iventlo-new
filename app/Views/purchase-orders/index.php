<?php
$filterStatus = $status ?? '';
?>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>
            <h3 class="mb-0">Purchase Order</h3>

            <p class="text-body fs-14 mb-0">
                Data purchase order vendor
            </p>
        </div>

        <?php if (can('purchase_order.create')): ?>
            <a
                href="<?= url('purchase-orders-create') ?>"
                class="btn btn-primary text-white erp-btn"
            >
                + Tambah PO
            </a>
        <?php endif; ?>

    </div>

    <div class="p-20 border-top">

        <form method="GET" class="row g-3 align-items-end">

            <input type="hidden" name="page" value="purchase-orders">

            <div class="col-md-5">

                <label class="form-label">Cari Purchase Order</label>

                <input
                    type="text"
                    name="search"
                    class="form-control erp-control erp-input"
                    placeholder="Cari nomor PO / vendor..."
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

                    <option value="draft" <?= $filterStatus === 'draft' ? 'selected' : '' ?>>
                        Draft
                    </option>

                    <option value="approved" <?= $filterStatus === 'approved' ? 'selected' : '' ?>>
                        Approved
                    </option>

                    <option value="sent" <?= $filterStatus === 'sent' ? 'selected' : '' ?>>
                        Sent
                    </option>

                    <option value="partial_received" <?= $filterStatus === 'partial_received' ? 'selected' : '' ?>>
                        Partial Received
                    </option>

                    <option value="completed" <?= $filterStatus === 'completed' ? 'selected' : '' ?>>
                        Completed
                    </option>

                    <option value="cancelled" <?= $filterStatus === 'cancelled' ? 'selected' : '' ?>>
                        Cancelled
                    </option>

                </select>

            </div>

            <div class="col-md-2">

                <div class="d-flex gap-2 filter-action-group">

                    <button
                        class="btn btn-primary text-white erp-btn w-100"
                    >
                        Filter
                    </button>

                    <a
                        href="<?= url('purchase-orders') ?>"
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
                        <th>No. PO</th>
                        <th>Tanggal</th>
                        <th>Vendor</th>
                        <th>No. PR</th>
                        <th>Status</th>
                        <th class="text-end">Grand Total</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($items)): ?>

                        <?php foreach ($items as $item): ?>

                            <?php
                            $rowStatus = strtolower($item['status'] ?? 'draft');

                            $statusClass = match ($rowStatus) {

                                'draft' => 'bg-secondary bg-opacity-10 text-secondary',

                                'approved' => 'bg-info bg-opacity-10 text-info',

                                'sent' => 'bg-primary bg-opacity-10 text-primary',

                                'partial_received' => 'bg-warning bg-opacity-10 text-warning',

                                'completed' => 'bg-success bg-opacity-10 text-success',

                                'cancelled' => 'bg-danger bg-opacity-10 text-danger',

                                default => 'bg-secondary bg-opacity-10 text-secondary'
                            };
                            ?>

                            <tr>

                                <td>
                                    <a
                                        href="<?= url('purchase-orders-show') ?>?id=<?= $item['id'] ?>"
                                        class="fw-semibold text-primary text-decoration-none"
                                    >
                                        <?= htmlspecialchars($item['po_number'] ?? '-') ?>
                                    </a>
                                </td>

                                <td>

                                    <?php if (!empty($item['po_date'])): ?>

                                        <?= date('d M Y', strtotime($item['po_date'])) ?>

                                    <?php else: ?>

                                        <span class="text-muted">-</span>

                                    <?php endif; ?>

                                </td>

                                <td>
                                    <?= htmlspecialchars($item['vendor_name'] ?? '-') ?>
                                </td>

                                <td>

                                    <?php if (!empty($item['purchase_request_id'])): ?>

                                        <a
                                            href="<?= url('purchase-requests-show') ?>?id=<?= $item['purchase_request_id'] ?>"
                                            class="text-primary text-decoration-none"
                                        >
                                            <?= htmlspecialchars($item['pr_number'] ?? '-') ?>
                                        </a>

                                    <?php else: ?>

                                        <span class="text-muted">-</span>

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <span class="default-badge <?= $statusClass ?>">
                                        <?= ucwords(str_replace('_', ' ', $rowStatus)) ?>
                                    </span>

                                </td>

                                <td class="text-end">

                                    <?php if (($item['grand_total'] ?? 0) > 0): ?>

                                        Rp <?= number_format($item['grand_total'] ?? 0, 0, ',', '.') ?>

                                    <?php else: ?>

                                        <span class="text-muted">-</span>

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="6" class="text-center text-body py-4">
                                Belum ada data purchase order.
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

                        <!-- Previous -->
                        <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">

                            <a
                                class="page-link icon"
                                href="<?= url('purchase-orders') ?>?p=<?= $currentPage - 1 . $queryString ?>"
                            >
                                <i class="material-symbols-outlined">west</i>
                            </a>

                        </li>

                        <!-- First -->
                        <?php if ($startPage > 1): ?>

                            <li class="page-item">
                                <a
                                    class="page-link"
                                    href="<?= url('purchase-orders') ?>?p=1<?= $queryString ?>"
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
                                    href="<?= url('purchase-orders') ?>?p=<?= $i . $queryString ?>"
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
                                    href="<?= url('purchase-orders') ?>?p=<?= $totalPages . $queryString ?>"
                                >
                                    <?= $totalPages ?>
                                </a>
                            </li>

                        <?php endif; ?>

                        <!-- Next -->
                        <li class="page-item <?= $currentPage >= ($totalPages ?? 1) ? 'disabled' : '' ?>">

                            <a
                                class="page-link icon"
                                href="<?= url('purchase-orders') ?>?p=<?= $currentPage + 1 . $queryString ?>"
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