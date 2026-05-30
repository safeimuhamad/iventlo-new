<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>
            <h3 class="mb-0">
                Role Management
            </h3>

            <p class="text-body fs-14 mb-0">
                Data role dan pengaturan akses pengguna sistem
            </p>
        </div>

        <?php if (can('role.create')): ?>
            <a
                href="<?= url('roles-create') ?>"
                class="btn btn-primary text-white erp-btn"
            >
                + Tambah Role
            </a>
        <?php endif; ?>

    </div>

    <div class="default-table-area mx-minus-1">

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>Role</th>
                        <th style="min-width:220px;">Deskripsi</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($roles)): ?>

                        <?php foreach ($roles as $role): ?>

                            <?php
                            $status = strtolower($role['status'] ?? 'active');

                            $statusClass = $status === 'active'
                                ? 'bg-success bg-opacity-10 text-success'
                                : 'bg-secondary bg-opacity-10 text-secondary';

                            $statusLabel = $status === 'active'
                                ? 'Aktif'
                                : 'Nonaktif';
                            ?>

                            <tr>

                                <td>
                                    <a
                                        href="<?= url('roles-edit') ?>?id=<?= $role['id'] ?>"
                                        class="fw-semibold text-primary text-decoration-none"
                                    >
                                        <?= htmlspecialchars($role['name'] ?? '-') ?>
                                    </a>
                                </td>

                                <td class="text-wrap" style="min-width:220px; max-width:420px;">
                                    <?= htmlspecialchars($role['description'] ?? '-') ?>
                                </td>

                                <td>
                                    <span class="default-badge <?= $statusClass ?>">
                                        <?= $statusLabel ?>
                                    </span>
                                </td>

                                <td class="text-end">
                                    <?php if (can('role.permission')): ?>
                                        <a
                                            href="<?= url('roles-permissions', ['id' => $role['id']]) ?>"
                                            class="btn btn-light btn-sm"
                                        >
                                            Hak Akses
                                        </a>
                                    <?php endif; ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                            <tr>
                                <td colspan="4" class="text-center text-body py-4">
                                Belum ada data role.
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
                                href="<?= url('roles') ?>?p=<?= $currentPage - 1 ?>"
                            >
                                <i class="material-symbols-outlined">west</i>
                            </a>
                        </li>

                        <?php if ($startPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= url('roles') ?>?p=1">
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
                                    href="<?= url('roles') ?>?p=<?= $i ?>"
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
                                <a class="page-link" href="<?= url('roles') ?>?p=<?= $totalPages ?>">
                                    <?= $totalPages ?>
                                </a>
                            </li>
                        <?php endif; ?>

                        <li class="page-item <?= $currentPage >= ($totalPages ?? 1) ? 'disabled' : '' ?>">
                            <a
                                class="page-link icon"
                                href="<?= url('roles') ?>?p=<?= $currentPage + 1 ?>"
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
