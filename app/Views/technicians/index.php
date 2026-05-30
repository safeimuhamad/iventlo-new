<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>

            <h3 class="mb-0">
                Master Teknisi
            </h3>

            <p class="text-body fs-14 mb-0">
                Data teknisi dan tim operasional lapangan
            </p>

        </div>

        <a
            href="<?= url('technicians-create') ?>"
            class="btn btn-primary text-white erp-btn"
        >
            + Tambah Teknisi
        </a>

    </div>

    <div class="default-table-area mx-minus-1">

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>Nama Teknisi</th>
                        <th>No. HP</th>
                        <th>Role</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($technicians)): ?>

                        <?php foreach ($technicians as $technician): ?>

                            <?php
                            $status = strtolower($technician['status'] ?? 'active');

                            $statusClass = $status === 'active'
                                ? 'bg-success bg-opacity-10 text-success'
                                : 'bg-secondary bg-opacity-10 text-secondary';
                            ?>

                            <tr>

                                <td>

                                    <a
                                        href="<?= url('technicians-edit') ?>?id=<?= $technician['id'] ?>"
                                        class="fw-semibold text-primary text-decoration-none"
                                    >
                                        <?= htmlspecialchars($technician['name'] ?? '-') ?>
                                    </a>

                                </td>

                                <td>

                                    <?= htmlspecialchars($technician['phone'] ?? '-') ?>

                                </td>

                                <td>

                                    <?= htmlspecialchars($technician['role_type'] ?? '-') ?>

                                </td>

                                <td>

                                    <span class="default-badge <?= $statusClass ?>">

                                        <?= htmlspecialchars(ucfirst($status)) ?>

                                    </span>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="4" class="text-center text-body py-4">
                                Belum ada data teknisi.
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
                                href="<?= url('technicians') ?>?p=<?= $currentPage - 1 ?>"
                            >
                                <i class="material-symbols-outlined">west</i>
                            </a>

                        </li>

                        <!-- First -->
                        <?php if ($startPage > 1): ?>

                            <li class="page-item">

                                <a
                                    class="page-link"
                                    href="<?= url('technicians') ?>?p=1"
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
                                    href="<?= url('technicians') ?>?p=<?= $i ?>"
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
                                    href="<?= url('technicians') ?>?p=<?= $totalPages ?>"
                                >
                                    <?= $totalPages ?>
                                </a>

                            </li>

                        <?php endif; ?>

                        <!-- Next -->
                        <li class="page-item <?= $currentPage >= ($totalPages ?? 1) ? 'disabled' : '' ?>">

                            <a
                                class="page-link icon"
                                href="<?= url('technicians') ?>?p=<?= $currentPage + 1 ?>"
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