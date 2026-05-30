<?php
$filterStatus = $status ?? '';
?>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>
            <h3 class="mb-0">Penerimaan Barang</h3>

            <p class="text-body fs-14 mb-0">
                Data penerimaan barang dari purchase order
            </p>
        </div>

        <?php if (can('goods_receipt.create')): ?>
            <a
                href="<?= url('goods-receipts-create') ?>"
                class="btn btn-primary text-white erp-btn"
            >
                + Tambah Penerimaan
            </a>
        <?php endif; ?>

    </div>

    <div class="p-20 border-top">

        <form method="GET" class="row g-3 align-items-end">

            <input type="hidden" name="page" value="goods-receipts">

            <div class="col-md-5">

                <label class="form-label">Cari Penerimaan Barang</label>

                <input
                    type="text"
                    name="search"
                    class="form-control erp-control erp-input"
                    placeholder="Cari nomor GR / PO / vendor..."
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

                    <option value="received" <?= $filterStatus === 'received' ? 'selected' : '' ?>>
                        Received
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
                        href="<?= url('goods-receipts') ?>"
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
                        <th>No. Receipt</th>
                        <th>No. PO</th>
                        <th>Vendor</th>
                        <th>Tanggal Terima</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($items)): ?>

                        <?php foreach ($items as $item): ?>

                            <?php
                            $rowStatus = strtolower($item['status'] ?? 'received');

                            $statusClass = match ($rowStatus) {

                                'draft' => 'bg-secondary bg-opacity-10 text-secondary',

                                'received' => 'bg-success bg-opacity-10 text-success',

                                'cancelled' => 'bg-danger bg-opacity-10 text-danger',

                                default => 'bg-secondary bg-opacity-10 text-secondary'
                            };
                            ?>

                            <tr>

                                <td>

                                    <a
                                        href="<?= url('goods-receipts-show') ?>?id=<?= $item['id'] ?>"
                                        class="fw-semibold text-primary text-decoration-none"
                                    >
                                        <?= htmlspecialchars($item['receipt_number'] ?? '-') ?>
                                    </a>

                                </td>

                                <td>

                                    <?php if (!empty($item['purchase_order_id'])): ?>

                                        <a
                                            href="<?= url('purchase-orders-show') ?>?id=<?= $item['purchase_order_id'] ?>"
                                            class="text-primary text-decoration-none"
                                        >
                                            <?= htmlspecialchars($item['po_number'] ?? '-') ?>
                                        </a>

                                    <?php else: ?>

                                        <span class="text-muted">-</span>

                                    <?php endif; ?>

                                </td>

                                <td>
                                    <?= htmlspecialchars($item['vendor_name'] ?? '-') ?>
                                </td>

                                <td>

                                    <?php if (!empty($item['receipt_date'])): ?>

                                        <?= date('d M Y', strtotime($item['receipt_date'])) ?>

                                    <?php else: ?>

                                        <span class="text-muted">-</span>

                                    <?php endif; ?>

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
                            <td colspan="5" class="text-center text-body py-4">
                                Belum ada data penerimaan barang.
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
                                href="<?= url('goods-receipts') ?>?p=<?= $currentPage - 1 . $queryString ?>"
                            >
                                <i class="material-symbols-outlined">west</i>
                            </a>

                        </li>

                        <!-- First -->
                        <?php if ($startPage > 1): ?>

                            <li class="page-item">
                                <a
                                    class="page-link"
                                    href="<?= url('goods-receipts') ?>?p=1<?= $queryString ?>"
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
                                    href="<?= url('goods-receipts') ?>?p=<?= $i . $queryString ?>"
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
                                    href="<?= url('goods-receipts') ?>?p=<?= $totalPages . $queryString ?>"
                                >
                                    <?= $totalPages ?>
                                </a>
                            </li>

                        <?php endif; ?>

                        <!-- Next -->
                        <li class="page-item <?= $currentPage >= ($totalPages ?? 1) ? 'disabled' : '' ?>">

                            <a
                                class="page-link icon"
                                href="<?= url('goods-receipts') ?>?p=<?= $currentPage + 1 . $queryString ?>"
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