<?php
$filterStatus = $status ?? '';
?>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>
            <h3 class="mb-0">Hutang Vendor</h3>

            <p class="mb-0 text-body">
                Daftar tagihan vendor / supplier
            </p>
        </div>

        <?php if (can('vendor_bill.create')): ?>
            <a
                href="<?= url('vendor-bills-create') ?>"
                class="btn btn-primary text-white erp-btn"
            >
                + Tambah Bill
            </a>
        <?php endif; ?>

    </div>

    <div class="p-20 border-top">

        <form method="GET" class="row g-3 align-items-end">

            <input type="hidden" name="page" value="vendor-bills">

            <div class="col-md-5">

                <label class="form-label">Cari Vendor Bill</label>

                <input
                    type="text"
                    name="search"
                    class="form-control erp-control erp-input"
                    placeholder="Cari bill / vendor / PO..."
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

                    <option value="unpaid" <?= $filterStatus === 'unpaid' ? 'selected' : '' ?>>
                        Unpaid
                    </option>

                    <option value="partial paid" <?= $filterStatus === 'partial paid' ? 'selected' : '' ?>>
                        Partial Paid
                    </option>

                    <option value="paid" <?= $filterStatus === 'paid' ? 'selected' : '' ?>>
                        Paid
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
                        href="<?= url('vendor-bills') ?>"
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
                        <th>No Bill</th>
                        <th>Vendor</th>
                        <th>Tanggal</th>
                        <th>Jatuh Tempo</th>
                        <th>No. PO</th>
                        <th>Status</th>
                        <th class="text-end">Total</th>
                        <th class="text-end">Sisa</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($bills)): ?>

                        <?php foreach ($bills as $bill): ?>

                            <?php
                            $rowStatus = strtolower($bill['status_payment'] ?? 'unpaid');

                            $badgeClass = match ($rowStatus) {

                                'paid' => 'bg-success bg-opacity-10 text-success',

                                'partial paid' => 'bg-warning bg-opacity-10 text-warning',

                                default => 'bg-danger bg-opacity-10 text-danger'
                            };
                            ?>

                            <tr>

                                <td>

                                    <a
                                        href="<?= url('vendor-bills-show') ?>?id=<?= $bill['id'] ?>"
                                        class="fw-semibold text-primary text-decoration-none"
                                    >
                                        <?= htmlspecialchars($bill['bill_no'] ?? '-') ?>
                                    </a>

                                </td>

                                <td>
                                    <?= htmlspecialchars($bill['vendor_name'] ?? '-') ?>
                                </td>

                                <td>

                                    <?php if (!empty($bill['bill_date'])): ?>

                                        <?= date('d M Y', strtotime($bill['bill_date'])) ?>

                                    <?php else: ?>

                                        <span class="text-muted">-</span>

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <?php if (!empty($bill['due_date'])): ?>

                                        <?= date('d M Y', strtotime($bill['due_date'])) ?>

                                    <?php else: ?>

                                        <span class="text-muted">-</span>

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <?php if (!empty($bill['purchase_order_id'])): ?>

                                        <a
                                            href="<?= url('purchase-orders-show') ?>?id=<?= $bill['purchase_order_id'] ?>"
                                            class="text-primary text-decoration-none"
                                        >
                                            <?= htmlspecialchars($bill['po_number'] ?? '-') ?>
                                        </a>

                                    <?php else: ?>

                                        <span class="text-muted">-</span>

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <span class="default-badge <?= $badgeClass ?>">
                                        <?= ucwords($rowStatus) ?>
                                    </span>

                                </td>

                                <td class="text-end fw-semibold">

                                    <?php if (($bill['grand_total'] ?? 0) > 0): ?>

                                        Rp <?= number_format((float) ($bill['grand_total'] ?? 0), 0, ',', '.') ?>

                                    <?php else: ?>

                                        <span class="text-muted">-</span>

                                    <?php endif; ?>

                                </td>

                                <td class="text-end fw-semibold text-danger">

                                    <?php if (($bill['remaining_amount'] ?? 0) > 0): ?>

                                        Rp <?= number_format((float) ($bill['remaining_amount'] ?? 0), 0, ',', '.') ?>

                                    <?php else: ?>

                                        <span class="text-muted">-</span>

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="8" class="text-center text-body py-4">
                                Belum ada hutang vendor.
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
                                href="<?= url('vendor-bills') ?>?p=<?= $currentPage - 1 . $queryString ?>"
                            >
                                <i class="material-symbols-outlined">west</i>
                            </a>

                        </li>

                        <!-- First -->
                        <?php if ($startPage > 1): ?>

                            <li class="page-item">
                                <a
                                    class="page-link"
                                    href="<?= url('vendor-bills') ?>?p=1<?= $queryString ?>"
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
                                    href="<?= url('vendor-bills') ?>?p=<?= $i . $queryString ?>"
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
                                    href="<?= url('vendor-bills') ?>?p=<?= $totalPages . $queryString ?>"
                                >
                                    <?= $totalPages ?>
                                </a>
                            </li>

                        <?php endif; ?>

                        <!-- Next -->
                        <li class="page-item <?= $currentPage >= ($totalPages ?? 1) ? 'disabled' : '' ?>">

                            <a
                                class="page-link icon"
                                href="<?= url('vendor-bills') ?>?p=<?= $currentPage + 1 . $queryString ?>"
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