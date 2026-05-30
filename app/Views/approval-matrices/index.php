<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>

            <h3 class="mb-0">
                DOA Matrix
            </h3>

            <p class="text-body fs-14 mb-0">
                Delegation of Authority untuk proses approval dokumen dan transaksi
            </p>

        </div>

        <a
            href="<?= url('approval-matrices-create') ?>"
            class="btn btn-primary text-white erp-btn"
        >
            + Tambah Matrix
        </a>

    </div>

    <div class="p-20 border-top">

        <form method="GET" class="row g-3 align-items-end">

            <input type="hidden" name="page" value="approval-matrices">

            <div class="col-md-10">

                <label class="form-label">
                    Cari Matrix
                </label>

                <input
                    type="text"
                    name="search"
                    class="form-control erp-control erp-input"
                    placeholder="Cari module, department, approver atau document type..."
                    value="<?= htmlspecialchars($search ?? '') ?>"
                >

            </div>

            <div class="col-md-2">

                <button
                    class="btn btn-primary text-white erp-btn w-100"
                >
                    Filter
                </button>

            </div>

        </form>

    </div>

    <div class="default-table-area mx-minus-1">

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>Module</th>
                        <th>Department</th>
                        <th>Min Amount</th>
                        <th>Max Amount</th>
                        <th>Level</th>
                        <th>Approver User</th>
                        <th>Approver Role</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($approvalMatrices)): ?>

                        <?php foreach ($approvalMatrices as $row): ?>

                            <?php
                            $statusClass = ((int) ($row['is_active'] ?? 1) === 1)
                                ? 'bg-success bg-opacity-10 text-success'
                                : 'bg-secondary bg-opacity-10 text-secondary';
                            ?>

                            <tr>

                                <td>

                                    <a
                                        href="<?= url('approval-matrices-show') ?>?id=<?= $row['id'] ?>"
                                        class="fw-semibold text-primary text-decoration-none"
                                    >
                                        <?= htmlspecialchars($row['module_name'] ?? '-') ?>
                                    </a>

                                </td>

                                <td>

                                    <?= htmlspecialchars($row['department_name'] ?? 'Semua Department') ?>

                                </td>

                                <td class="text-end">

                                    Rp <?= number_format((float) ($row['min_amount'] ?? 0), 0, ',', '.') ?>

                                </td>

                                <td class="text-end">

                                    <?php if (!empty($row['max_amount'])): ?>

                                        Rp <?= number_format((float) $row['max_amount'], 0, ',', '.') ?>

                                    <?php else: ?>

                                        Unlimited

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <span class="default-badge bg-info bg-opacity-10 text-info">
                                        Level <?= (int) ($row['approval_level'] ?? 1) ?>
                                    </span>

                                </td>

                                <td>

                                    <?= htmlspecialchars($row['approver_user_name'] ?? '-') ?>

                                </td>

                                <td>

                                    <?= htmlspecialchars($row['approver_role_name'] ?? '-') ?>

                                </td>

                                <td>

                                    <span class="default-badge <?= $statusClass ?>">

                                        <?= ((int) ($row['is_active'] ?? 1) === 1)
                                            ? 'Aktif'
                                            : 'Nonaktif' ?>

                                    </span>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                Belum ada data DOA Matrix.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

        <div class="d-flex justify-content-center justify-content-sm-between align-items-center text-center flex-wrap gap-2 showing-wrap pt-15 p-20 border-top">

            <?php
            $currentPage = (int) ($currentPage ?? $p ?? 1);
            $limit = (int) ($limit ?? 10);
            $totalRows = (int) ($totalRows ?? 0);
            $totalPages = (int) ($totalPages ?? 1);

            $searchQuery = '?search=' . urlencode($search ?? '');

            $startPage = max(1, $currentPage - 2);
            $endPage = min($totalPages, $currentPage + 2);
            ?>

            <span class="fs-15">
                Showing
                <?= $totalRows > 0 ? (($currentPage - 1) * $limit + 1) : 0 ?>
                to
                <?= min($currentPage * $limit, $totalRows) ?>
                of
                <?= $totalRows ?> entries
            </span>

            <?php if ($totalPages > 1): ?>

                <nav class="custom-pagination">

                    <ul class="pagination mb-0 justify-content-center">

                        <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                            <a
                                class="page-link icon"
                                href="<?= url('approval-matrices') . $searchQuery ?>&p=<?= $currentPage - 1 ?>"
                            >
                                <i class="material-symbols-outlined">west</i>
                            </a>
                        </li>

                        <?php if ($startPage > 1): ?>

                            <li class="page-item">
                                <a class="page-link" href="<?= url('approval-matrices') . $searchQuery ?>&p=1">1</a>
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
                                    href="<?= url('approval-matrices') . $searchQuery ?>&p=<?= $i ?>"
                                >
                                    <?= $i ?>
                                </a>
                            </li>

                        <?php endfor; ?>

                        <?php if ($endPage < $totalPages): ?>

                            <?php if ($endPage < ($totalPages - 1)): ?>
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            <?php endif; ?>

                            <li class="page-item">
                                <a class="page-link" href="<?= url('approval-matrices') . $searchQuery ?>&p=<?= $totalPages ?>">
                                    <?= $totalPages ?>
                                </a>
                            </li>

                        <?php endif; ?>

                        <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                            <a
                                class="page-link icon"
                                href="<?= url('approval-matrices') . $searchQuery ?>&p=<?= $currentPage + 1 ?>"
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
