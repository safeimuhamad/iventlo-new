<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>

            <h3 class="mb-0">
                Penawaran
            </h3>

            <p class="text-body fs-14 mb-0">
                Data quotation dan penawaran customer
            </p>

        </div>

        <a
            href="<?= url('quotations-create') ?>"
            class="btn btn-primary text-white erp-btn"
        >
            + Tambah Penawaran
        </a>

    </div>

    <div class="default-table-area mx-minus-1">

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>No Penawaran</th>
                        <th>Customer</th>
                        <th>No. HP</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Sumber</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($quotations)): ?>

                        <?php foreach ($quotations as $quotation): ?>

                            <?php
                            $status = strtolower($quotation['status'] ?? 'waiting approval');

                            $statusClass = match ($status) {
                                'waiting approval' => 'bg-warning bg-opacity-10 text-warning',
                                'order' => 'bg-primary bg-opacity-10 text-primary',
                                'approved' => 'bg-success bg-opacity-10 text-success',
                                'cancelled' => 'bg-dark bg-opacity-10 text-dark',
                                default => 'bg-secondary bg-opacity-10 text-secondary'
                            };
                            ?>

                            <tr>

                                <td>

                                    <a
                                        href="<?= url('quotations-show') ?>?id=<?= $quotation['id'] ?>"
                                        class="fw-semibold text-primary text-decoration-none"
                                    >
                                        <?= htmlspecialchars($quotation['no_quotation'] ?? '-') ?>
                                    </a>

                                </td>

                                <td>

                                    <?= htmlspecialchars($quotation['customer_name'] ?? $quotation['company_name'] ?? '-') ?>

                                </td>

                                <td>

                                    <?= htmlspecialchars($quotation['customer_phone'] ?? '-') ?>

                                </td>

                                <td>

                                    <?= !empty($quotation['tanggal_mulai'])
                                        ? date('d M Y', strtotime($quotation['tanggal_mulai']))
                                        : '-' ?>

                                </td>

                                <td>

                                    <?= !empty($quotation['tanggal_selesai'])
                                        ? date('d M Y', strtotime($quotation['tanggal_selesai']))
                                        : '-' ?>

                                </td>

                                <td>

                                    <?php if (!empty($quotation['lead_id'])): ?>

                                        <span class="default-badge bg-info bg-opacity-10 text-info">

                                            Lead:
                                            <?= htmlspecialchars($quotation['lead_number'] ?? '-') ?>

                                        </span>

                                    <?php else: ?>

                                        <span class="default-badge bg-success bg-opacity-10 text-success">
                                            Customer
                                        </span>

                                    <?php endif; ?>

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
                            <td colspan="7" class="text-center text-body py-4">
                                Belum ada data penawaran.
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
                                href="<?= url('quotations') . $queryString ?>&p=<?= $currentPage - 1 ?>"
                            >
                                <i class="material-symbols-outlined">west</i>
                            </a>

                        </li>

                        <!-- First -->
                        <?php if ($startPage > 1): ?>

                            <li class="page-item">

                                <a
                                    class="page-link"
                                    href="<?= url('quotations') . $queryString ?>&p=1"
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
                                    href="<?= url('quotations') . $queryString ?>&p=<?= $i ?>"
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
                                    href="<?= url('quotations') . $queryString ?>&p=<?= $totalPages ?>"
                                >
                                    <?= $totalPages ?>
                                </a>

                            </li>

                        <?php endif; ?>

                        <!-- Next -->
                        <li class="page-item <?= $currentPage >= ($totalPages ?? 1) ? 'disabled' : '' ?>">

                            <a
                                class="page-link icon"
                                href="<?= url('quotations') . $queryString ?>&p=<?= $currentPage + 1 ?>"
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
