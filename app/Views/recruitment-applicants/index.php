<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>
            <h3 class="mb-0">Rekrutmen</h3>

            <p class="text-body fs-14 mb-0">
                Data kandidat dan proses rekrutmen
            </p>
        </div>

        <?php if (can('recruitment_applicant.create')): ?>
            <a
                href="<?= url('recruitment-applicants-create') ?>"
                class="btn btn-primary text-white erp-btn"
            >
                + Tambah Kandidat
            </a>
        <?php endif; ?>

    </div>

    <div class="p-20 border-top">

        <form method="GET" class="row g-3 align-items-end">

            <input type="hidden" name="page" value="recruitment-applicants">

            <div class="col-md-5">
                <label class="form-label">Cari Kandidat</label>

                <input
                    type="text"
                    name="search"
                    class="form-control erp-control erp-input"
                    value="<?= htmlspecialchars($search ?? '') ?>"
                    placeholder="Nama, no HP, email, posisi, status"
                >
            </div>

            <div class="col-md-2">
                <div class="d-flex gap-2 filter-action-group">

                    <button class="btn btn-primary text-white erp-btn w-100">
                        Cari
                    </button>

                    <a href="<?= url('recruitment-applicants') ?>" class="btn btn-light erp-btn">
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
                        <th>No Kandidat</th>
                        <th>Nama Kandidat</th>
                        <th>No. HP</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Position</th>
                        <th>Source</th>
                        <th>Status</th>
                        <th>Interview</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($items)): ?>
                        <?php foreach ($items as $item): ?>
                            <?php
                            $status = $item['status'] ?? 'new';

                            $statusLabel = [
                                'new' => 'New',
                                'screening' => 'Screening',
                                'interview' => 'Interview',
                                'test' => 'Test',
                                'offering' => 'Offering',
                                'hired' => 'Hired',
                                'rejected' => 'Rejected',
                            ];

                            $statusClass = [
                                'new' => 'bg-primary bg-opacity-10 text-primary',
                                'screening' => 'bg-info bg-opacity-10 text-info',
                                'interview' => 'bg-warning bg-opacity-10 text-warning',
                                'test' => 'bg-secondary bg-opacity-10 text-secondary',
                                'offering' => 'bg-purple bg-opacity-10 text-purple',
                                'hired' => 'bg-success bg-opacity-10 text-success',
                                'rejected' => 'bg-danger bg-opacity-10 text-danger',
                            ];
                            ?>

                            <tr>
                                <td>
                                    <a
                                        href="<?= url('recruitment-applicants-show') ?>?id=<?= $item['id'] ?>"
                                        class="fw-semibold text-primary text-decoration-none"
                                    >
                                        <?= htmlspecialchars($item['applicant_number'] ?? '-') ?>
                                    </a>
                                </td>

                                <td><?= htmlspecialchars($item['full_name'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($item['phone'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($item['email'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($item['department_name'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($item['position_name'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($item['source'] ?? '-') ?></td>

                                <td>
                                    <span class="default-badge <?= $statusClass[$status] ?? 'bg-secondary bg-opacity-10 text-secondary' ?>">
                                        <?= $statusLabel[$status] ?? $status ?>
                                    </span>
                                </td>

                                <td>
                                    <?= !empty($item['interview_date'])
                                        ? htmlspecialchars(date('d M Y H:i', strtotime($item['interview_date'])))
                                        : '<span class="text-muted">-</span>' ?>
                                </td>
                            </tr>

                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center text-body py-4">
                                Belum ada data kandidat.
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
            $queryString = '&search=' . urlencode($search ?? '');
            $startPage = max(1, $currentPage - 2);
            $endPage = min($totalPages ?? 1, $currentPage + 2);
            ?>

            <?php if (($totalPages ?? 1) > 1): ?>
                <nav class="custom-pagination">
                    <ul class="pagination mb-0 justify-content-center">

                        <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                            <a
                                class="page-link icon"
                                href="<?= url('recruitment-applicants') ?>?p=<?= $currentPage - 1 . $queryString ?>"
                            >
                                <i class="material-symbols-outlined">west</i>
                            </a>
                        </li>

                        <?php if ($startPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= url('recruitment-applicants') ?>?p=1<?= $queryString ?>">
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
                                    href="<?= url('recruitment-applicants') ?>?p=<?= $i . $queryString ?>"
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
                                    href="<?= url('recruitment-applicants') ?>?p=<?= $totalPages . $queryString ?>"
                                >
                                    <?= $totalPages ?>
                                </a>
                            </li>
                        <?php endif; ?>

                        <li class="page-item <?= $currentPage >= ($totalPages ?? 1) ? 'disabled' : '' ?>">
                            <a
                                class="page-link icon"
                                href="<?= url('recruitment-applicants') ?>?p=<?= $currentPage + 1 . $queryString ?>"
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