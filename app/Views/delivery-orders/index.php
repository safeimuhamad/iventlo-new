<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>

            <h3 class="mb-0">
                Surat Jalan
            </h3>

            <p class="text-body fs-14 mb-0">
                Data pengiriman, pemasangan, dan bongkar unit rental
            </p>

        </div>

        <a
            href="<?= url('delivery-orders-create') ?>"
            class="btn btn-primary text-white erp-btn"
        >
            + Tambah Surat Jalan
        </a>

    </div>

    <div class="default-table-area mx-minus-1">

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>No SJ</th>
                        <th>Customer</th>
                        <th>Tipe</th>
                        <th style="min-width:220px;">Teknisi</th>
                        <th>Kendaraan</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($items)): ?>

                        <?php foreach ($items as $item): ?>

                            <?php
                            $type = strtolower($item['sj_type'] ?? 'pasang');

                            $typeClass = $type === 'pasang'
                                ? 'bg-primary bg-opacity-10 text-primary'
                                : 'bg-warning bg-opacity-10 text-warning';
                            ?>

                            <tr>

                                <td>

                                    <a
                                        href="<?= url('delivery-orders-show') ?>?id=<?= $item['id'] ?>"
                                        class="fw-semibold text-primary text-decoration-none"
                                    >
                                        <?= htmlspecialchars($item['no_surat_jalan'] ?? '-') ?>
                                    </a>

                                </td>

                                <td>

                                    <?= htmlspecialchars($item['customer_name'] ?? '-') ?>

                                </td>

                                <td>

                                    <span class="default-badge <?= $typeClass ?>">

                                        <?= $type === 'pasang' ? 'Pasang' : 'Bongkar' ?>

                                    </span>

                                </td>

                                <td class="text-wrap" style="min-width:220px; max-width:320px;">

                                    <?= htmlspecialchars($item['technician_names'] ?? '-') ?>

                                </td>

                                <td>

                                    <?= htmlspecialchars($item['vehicle_name'] ?? '-') ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="5" class="text-center text-body py-4">
                                Belum ada data surat jalan.
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
                                href="<?= url('delivery-orders') . $queryString ?>&p=<?= $currentPage - 1 ?>"
                            >
                                <i class="material-symbols-outlined">west</i>
                            </a>

                        </li>

                        <!-- First -->
                        <?php if ($startPage > 1): ?>

                            <li class="page-item">

                                <a
                                    class="page-link"
                                    href="<?= url('delivery-orders') . $queryString ?>&p=1"
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
                                    href="<?= url('delivery-orders') . $queryString ?>&p=<?= $i ?>"
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
                                    href="<?= url('delivery-orders') . $queryString ?>&p=<?= $totalPages ?>"
                                >
                                    <?= $totalPages ?>
                                </a>

                            </li>

                        <?php endif; ?>

                        <!-- Next -->
                        <li class="page-item <?= $currentPage >= ($totalPages ?? 1) ? 'disabled' : '' ?>">

                            <a
                                class="page-link icon"
                                href="<?= url('delivery-orders') . $queryString ?>&p=<?= $currentPage + 1 ?>"
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
