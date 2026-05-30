<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['success'];
        unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>

            <h3 class="mb-0">
                Master User
            </h3>

            <p class="text-body fs-14 mb-0">
                Data pengguna, role, dan hak akses sistem
            </p>

        </div>

        <a
            href="<?= url('users-create') ?>"
            class="btn btn-primary text-white erp-btn"
        >
            + Tambah User
        </a>

    </div>

    <?php if (!empty($_SESSION['error'])): ?>

        <div class="alert alert-danger m-20">
            <?= $_SESSION['error'];
            unset($_SESSION['error']); ?>
        </div>

    <?php endif; ?>

    <div class="default-table-area mx-minus-1">

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Data Akses</th>
                        <th>Status</th>
                        <th>Last Login</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($users)): ?>

                        <?php foreach ($users as $user): ?>

                            <?php
                            $status = strtolower($user['status'] ?? 'active');

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
                                        href="<?= url('users-edit') ?>?id=<?= $user['id'] ?>"
                                        class="fw-semibold text-primary text-decoration-none"
                                    >
                                        <?= htmlspecialchars($user['name'] ?? '-') ?>
                                    </a>

                                </td>

                                <td>
                                    <?= htmlspecialchars($user['username'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($user['email'] ?? '-') ?>
                                </td>

                                <td>

                                    <span class="default-badge bg-primary bg-opacity-10 text-primary">

                                        <?= htmlspecialchars($user['role_name'] ?? '-') ?>

                                    </span>

                                </td>

                                <td>
                                    <?= htmlspecialchars($user['data_scope'] ?? '-') ?>
                                </td>

                                <td>

                                    <span class="default-badge <?= $statusClass ?>">

                                        <?= $statusLabel ?>

                                    </span>

                                </td>

                                <td>

                                    <?= !empty($user['last_login'])
                                        ? date('d M Y H:i', strtotime($user['last_login']))
                                        : '-' ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="7" class="text-center text-body py-4">
                                Belum ada data user.
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
                                href="<?= url('users') ?>?p=<?= $currentPage - 1 ?>"
                            >
                                <i class="material-symbols-outlined">west</i>
                            </a>

                        </li>

                        <?php if ($startPage > 1): ?>

                            <li class="page-item">
                                <a
                                    class="page-link"
                                    href="<?= url('users') ?>?p=1"
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
                                    href="<?= url('users') ?>?p=<?= $i ?>"
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
                                    href="<?= url('users') ?>?p=<?= $totalPages ?>"
                                >
                                    <?= $totalPages ?>
                                </a>

                            </li>

                        <?php endif; ?>

                        <li class="page-item <?= $currentPage >= ($totalPages ?? 1) ? 'disabled' : '' ?>">

                            <a
                                class="page-link icon"
                                href="<?= url('users') ?>?p=<?= $currentPage + 1 ?>"
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