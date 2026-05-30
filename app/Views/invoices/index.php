
<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>
<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>

            <h3 class="mb-0">
                Invoice
            </h3>

            <p class="text-body fs-14 mb-0">
                Data tagihan customer dan pembayaran invoice
            </p>

        </div>

        <a
            href="<?= url('invoices-create') ?>"
            class="btn btn-primary text-white erp-btn"
        >
            + Tambah Invoice
        </a>

    </div>

    <div class="default-table-area mx-minus-1">

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>No Invoice</th>
                        <th>Customer</th>
                        <th>Tanggal</th>
                        <th>Jatuh Tempo</th>
                        <th class="text-end">Total</th>
                        <th class="text-end">Terbayar</th>
                        <th class="text-end">Sisa</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($invoices)): ?>

                        <?php foreach ($invoices as $invoice): ?>

                            <?php
                            $status = strtolower($invoice['computed_status'] ?? 'waiting payment');

                            $statusClass = match ($status) {
                                'waiting payment' => 'bg-info bg-opacity-10 text-info',
                                'partial paid' => 'bg-warning bg-opacity-10 text-warning',
                                'paid' => 'bg-success bg-opacity-10 text-success',
                                'overdue' => 'bg-danger bg-opacity-10 text-danger',
                                'cancelled' => 'bg-dark bg-opacity-10 text-dark',
                                default => 'bg-secondary bg-opacity-10 text-secondary'
                            };
                            ?>

                            <tr>

                                <td>

                                    <a
                                        href="<?= url('invoices-show') ?>?id=<?= $invoice['id'] ?>"
                                        class="fw-semibold text-primary text-decoration-none"
                                    >
                                        <?= htmlspecialchars($invoice['no_invoice'] ?? '-') ?>
                                    </a>

                                </td>

                                <td>

                                    <?= htmlspecialchars($invoice['customer_name'] ?? '-') ?>

                                </td>

                                <td>

                                    <?= !empty($invoice['invoice_date'])
                                        ? date('d M Y', strtotime($invoice['invoice_date']))
                                        : '-' ?>

                                </td>

                                <td>

                                    <?= !empty($invoice['due_date'])
                                        ? date('d M Y', strtotime($invoice['due_date']))
                                        : '-' ?>

                                </td>

                                <td class="text-end fw-semibold">

                                    Rp <?= number_format((float) ($invoice['computed_total'] ?? 0), 0, ',', '.') ?>

                                </td>

                                <td class="text-end fw-semibold text-success">

                                    Rp <?= number_format((float) ($invoice['computed_paid'] ?? 0), 0, ',', '.') ?>

                                </td>

                                <td class="text-end fw-semibold text-danger">

                                    Rp <?= number_format((float) ($invoice['computed_remaining'] ?? 0), 0, ',', '.') ?>

                                </td>

                                <td>

                                    <span class="default-badge <?= $statusClass ?>">

                                        <?= htmlspecialchars(ucwords(str_replace('_', ' ', $status))) ?>

                                    </span>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="8" class="text-center text-body py-4">
                                Belum ada data invoice.
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
                '?search=' . urlencode($search ?? '');

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
                                href="<?= url('invoices') . $queryString ?>&p=<?= $currentPage - 1 ?>"
                            >
                                <i class="material-symbols-outlined">west</i>
                            </a>

                        </li>

                        <!-- First -->
                        <?php if ($startPage > 1): ?>

                            <li class="page-item">

                                <a
                                    class="page-link"
                                    href="<?= url('invoices') . $queryString ?>&p=1"
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
                                    href="<?= url('invoices') . $queryString ?>&p=<?= $i ?>"
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
                                    href="<?= url('invoices') . $queryString ?>&p=<?= $totalPages ?>"
                                >
                                    <?= $totalPages ?>
                                </a>

                            </li>

                        <?php endif; ?>

                        <!-- Next -->
                        <li class="page-item <?= $currentPage >= ($totalPages ?? 1) ? 'disabled' : '' ?>">

                            <a
                                class="page-link icon"
                                href="<?= url('invoices') . $queryString ?>&p=<?= $currentPage + 1 ?>"
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
