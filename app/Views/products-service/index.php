<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>

            <h3 class="mb-0">
                Master Produk & Jasa
            </h3>

            <p class="text-body fs-14 mb-0">
                Data produk, jasa, material, sparepart dan item penjualan
            </p>

        </div>

        <a
            href="<?= url('products-service-create') ?>"
            class="btn btn-primary text-white erp-btn"
        >
            + Tambah Produk / Jasa
        </a>

    </div>

    <div class="default-table-area mx-minus-1">

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>Produk / Jasa</th>
                        <th>Kategori</th>
                        <th>Jenis</th>
                        <th>Default Billing</th>
                        <th class="text-end">Harga</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($items)): ?>

                        <?php foreach ($items as $item): ?>

                            <?php
                            $billing = $item['default_period_type'] ?? 'unit';

                            $price = match ($billing) {
                                'daily'   => $item['daily_price'] ?? 0,
                                'weekly'  => $item['weekly_price'] ?? 0,
                                'monthly' => $item['monthly_price'] ?? 0,
                                'meter'   => $item['meter_price'] ?? 0,
                                'package',
                                'fixed'   => $item['package_price'] ?? 0,
                                default   => $item['unit_price'] ?? 0,
                            };

                            $billingLabel = match ($billing) {
                                'daily'   => 'Harian',
                                'weekly'  => 'Mingguan',
                                'monthly' => 'Bulanan',
                                'unit'    => 'Per Unit',
                                'meter'   => 'Per Meter',
                                'package' => 'Paket',
                                'fixed'   => 'Fixed',
                                default   => ucfirst($billing)
                            };

                            $typeLabel = match ($item['item_type'] ?? '') {
                                'rental_unit'  => 'Rental',
                                'service'      => 'Service',
                                'installation' => 'Instalasi',
                                'material'     => 'Material',
                                'sparepart'    => 'Sparepart',
                                'transport'    => 'Transport',
                                default        => 'Lainnya'
                            };

                            $status = strtolower($item['status'] ?? 'inactive');

                            $statusClass = $status === 'active'
                                ? 'bg-success bg-opacity-10 text-success'
                                : 'bg-danger bg-opacity-10 text-danger';
                            ?>

                            <tr>

                                <td>

                                    <a
                                        href="<?= url('products-service-edit') ?>?id=<?= $item['id'] ?>"
                                        class="fw-semibold text-primary text-decoration-none"
                                    >
                                        <?= htmlspecialchars($item['name'] ?? '-') ?>
                                    </a>

                                </td>

                                <td>
                                    <?= htmlspecialchars($item['category'] ?? '-') ?>
                                </td>

                                <td>

                                    <span class="default-badge bg-primary bg-opacity-10 text-primary">
                                        <?= $typeLabel ?>
                                    </span>

                                </td>

                                <td>
                                    <?= $billingLabel ?>
                                </td>

                                <td class="text-end fw-semibold">
                                    Rp <?= number_format((float) $price, 0, ',', '.') ?>
                                </td>

                                <td>

                                    <span class="default-badge <?= $statusClass ?>">
                                        <?= ucfirst($status) ?>
                                    </span>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="6" class="text-center text-body py-4">
                                Belum ada data produk / jasa.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

        <div class="d-flex justify-content-center justify-content-sm-between align-items-center text-center flex-wrap gap-2 showing-wrap pt-15 p-20 border-top">

            <span class="fs-15">

                Showing

                <?= ($totalData ?? 0) > 0
                    ? (($currentPage - 1) * $limit + 1)
                    : 0 ?>

                to

                <?= min($currentPage * $limit, $totalData ?? 0) ?>

                of

                <?= (int) ($totalData ?? 0) ?> entries

            </span>

            <?php
            $queryString = '?search=' . urlencode($search ?? '');

            $startPage = max(1, $currentPage - 2);
            $endPage = min($totalPages ?? 1, $currentPage + 2);
            ?>

            <?php if (($totalPages ?? 1) > 1): ?>

                <nav class="custom-pagination">

                    <ul class="pagination mb-0 justify-content-center">

                        <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">

                            <a
                                class="page-link icon"
                                href="<?= url('products-service') . $queryString ?>&p=<?= $currentPage - 1 ?>"
                            >
                                <i class="material-symbols-outlined">west</i>
                            </a>

                        </li>

                        <?php if ($startPage > 1): ?>

                            <li class="page-item">
                                <a
                                    class="page-link"
                                    href="<?= url('products-service') . $queryString ?>&p=1"
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
                                    href="<?= url('products-service') . $queryString ?>&p=<?= $i ?>"
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
                                    href="<?= url('products-service') . $queryString ?>&p=<?= $totalPages ?>"
                                >
                                    <?= $totalPages ?>
                                </a>

                            </li>

                        <?php endif; ?>

                        <li class="page-item <?= $currentPage >= ($totalPages ?? 1) ? 'disabled' : '' ?>">

                            <a
                                class="page-link icon"
                                href="<?= url('products-service') . $queryString ?>&p=<?= $currentPage + 1 ?>"
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
