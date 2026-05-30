<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success mb-4">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>
            <h3 class="mb-0">Data Karyawan</h3>

            <p class="text-body fs-14 mb-0">
                Data master karyawan perusahaan
            </p>
        </div>

        <a
            href="<?= url('employees-create') ?>"
            class="btn btn-primary text-white erp-btn"
        >
            + Tambah Karyawan
        </a>

    </div>

    <div class="p-20 border-top">

        <form method="GET" class="row g-3 align-items-end">

            <input type="hidden" name="page" value="employees">

            <div class="col-md-5">

                <label class="form-label">Cari Karyawan</label>

                <input
                    type="text"
                    name="search"
                    class="form-control erp-control erp-input"
                    placeholder="Cari nama, kode, divisi, jabatan..."
                    value="<?= htmlspecialchars($search ?? '') ?>"
                >

            </div>

            <div class="col-md-2">

                <div class="d-flex gap-2 filter-action-group">

                    <button
                        class="btn btn-primary text-white erp-btn w-100"
                    >
                        Cari
                    </button>

                    <a
                        href="<?= url('employees') ?>"
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
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Nickname</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Divisi</th>
                        <th>Jabatan</th>
                        <th>Status Kerja</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($employees)): ?>

                        <?php foreach ($employees as $employee): ?>

                            <?php
                            $employmentStatus = [
                                'permanent' => 'Tetap',
                                'contract' => 'Kontrak',
                                'daily' => 'Harian',
                                'intern' => 'Magang'
                            ];

                            $isActive = ($employee['status'] ?? '') === 'active';
                            ?>

                            <tr>

                                <td>

                                    <span class="fw-semibold">
                                        <?= htmlspecialchars($employee['employee_code'] ?? '-') ?>
                                    </span>

                                </td>

                                <td>

                                    <a
                                        href="<?= url('employees-show') ?>?id=<?= $employee['id'] ?>"
                                        class="fw-semibold text-primary text-decoration-none"
                                    >
                                        <?= htmlspecialchars($employee['full_name'] ?? '-') ?>
                                    </a>

                                </td>

                                <td>

                                    <?= htmlspecialchars($employee['nickname'] ?? '-') ?>

                                </td>

                                <td>

                                    <?= htmlspecialchars($employee['phone'] ?? '-') ?>

                                </td>

                                <td>

                                    <?= htmlspecialchars($employee['email'] ?? '-') ?>

                                </td>

                                <td>

                                    <?= htmlspecialchars($employee['department_name'] ?? '-') ?>

                                </td>

                                <td>

                                    <?= htmlspecialchars($employee['position_name'] ?? '-') ?>

                                </td>

                                <td>

                                    <?= $employmentStatus[$employee['employment_status'] ?? ''] ?? '-' ?>

                                </td>

                                <td>

                                    <?php if ($isActive): ?>

                                        <span class="default-badge bg-success bg-opacity-10 text-success">
                                            Aktif
                                        </span>

                                    <?php else: ?>

                                        <span class="default-badge bg-secondary bg-opacity-10 text-secondary">
                                            Nonaktif
                                        </span>

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">
                                Belum ada data karyawan.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

    <div class="d-flex justify-content-center justify-content-sm-between align-items-center text-center flex-wrap gap-2 showing-wrap pt-15 p-20 border-top">

        <span class="fs-15">

            Showing

            <?= ($totalRows ?? 0) > 0 ? (($currentPage - 1) * $limit + 1) : 0 ?>

            to

            <?= min($currentPage * $limit, $totalRows ?? 0) ?>

            of

            <?= (int) ($totalRows ?? 0) ?> entries

        </span>

        <?php
        $queryString =
            '&search=' . urlencode($search ?? '');

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
                            href="<?= url('employees') ?>?p=<?= $currentPage - 1 . $queryString ?>"
                        >
                            <i class="material-symbols-outlined">west</i>
                        </a>

                    </li>

                    <!-- First -->
                    <?php if ($startPage > 1): ?>

                        <li class="page-item">
                            <a
                                class="page-link"
                                href="<?= url('employees') ?>?p=1<?= $queryString ?>"
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
                                href="<?= url('employees') ?>?p=<?= $i . $queryString ?>"
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
                                href="<?= url('employees') ?>?p=<?= $totalPages . $queryString ?>"
                            >
                                <?= $totalPages ?>
                            </a>
                        </li>

                    <?php endif; ?>

                    <!-- Next -->
                    <li class="page-item <?= $currentPage >= ($totalPages ?? 1) ? 'disabled' : '' ?>">

                        <a
                            class="page-link icon"
                            href="<?= url('employees') ?>?p=<?= $currentPage + 1 . $queryString ?>"
                        >
                            <i class="material-symbols-outlined">east</i>
                        </a>

                    </li>

                </ul>

            </nav>

        <?php endif; ?>

    </div>

</div>