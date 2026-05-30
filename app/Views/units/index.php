<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>

            <h3 class="mb-0">
                Master Unit
            </h3>

            <p class="text-body fs-14 mb-0">
                Data master unit rental dan inventaris perusahaan
            </p>

        </div>

        <a
            href="<?= url('units-create') ?>"
            class="btn btn-primary text-white erp-btn"
        >
            + Tambah Unit
        </a>

    </div>

    <div class="default-table-area mx-minus-1">

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>Kode Unit</th>
                        <th>Nama Unit</th>
                        <th>Tipe</th>
                        <th>Kategori</th>
                        <th>Brand</th>
                        <th>Status</th>
                        <th>Lokasi</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($units)): ?>

                        <?php foreach ($units as $unit): ?>

                            <?php
                            $status = strtolower($unit['status_unit'] ?? 'available');

                            $badgeClass = match ($status) {
                                'available'   => 'bg-success bg-opacity-10 text-success',
                                'booked'      => 'bg-primary bg-opacity-10 text-primary',
                                'on_rent'     => 'bg-info bg-opacity-10 text-info',
                                'maintenance' => 'bg-warning bg-opacity-10 text-warning',
                                'broken'      => 'bg-danger bg-opacity-10 text-danger',
                                default       => 'bg-secondary bg-opacity-10 text-secondary'
                            };
                            ?>

                            <tr>

                                <td>

                                    <a
                                        href="<?= url('units-edit') ?>?id=<?= $unit['id'] ?>"
                                        class="fw-semibold text-primary text-decoration-none"
                                    >
                                        <?= htmlspecialchars($unit['kode_unit'] ?? '-') ?>
                                    </a>

                                </td>

                                <td>
                                    <?= htmlspecialchars($unit['nama_unit'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($unit['tipe_unit'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($unit['kategori'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($unit['brand'] ?? '-') ?>
                                </td>

                                <td>

                                    <span class="default-badge <?= $badgeClass ?>">

                                        <?= htmlspecialchars(
                                            ucwords(str_replace('_', ' ', $status))
                                        ) ?>

                                    </span>

                                </td>

                                <td>
                                    <?= htmlspecialchars($unit['lokasi_sekarang'] ?? '-') ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="7" class="text-center text-body py-4">
                                Belum ada data unit.
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
            $startPage = max(1, $currentPage - 2);
            $endPage = min($totalPages ?? 1, $currentPage + 2);
            ?>

            <?php if (($totalPages ?? 1) > 1): ?>

                <nav class="custom-pagination">

                    <ul class="pagination mb-0 justify-content-center">

                        <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">

                            <a
                                class="page-link icon"
                                href="<?= url('units') ?>?p=<?= $currentPage - 1 ?>"
                            >
                                <i class="material-symbols-outlined">west</i>
                            </a>

                        </li>

                        <?php if ($startPage > 1): ?>

                            <li class="page-item">
                                <a
                                    class="page-link"
                                    href="<?= url('units') ?>?p=1"
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
                                    href="<?= url('units') ?>?p=<?= $i ?>"
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
                                    href="<?= url('units') ?>?p=<?= $totalPages ?>"
                                >
                                    <?= $totalPages ?>
                                </a>

                            </li>

                        <?php endif; ?>

                        <li class="page-item <?= $currentPage >= ($totalPages ?? 1) ? 'disabled' : '' ?>">

                            <a
                                class="page-link icon"
                                href="<?= url('units') ?>?p=<?= $currentPage + 1 ?>"
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
