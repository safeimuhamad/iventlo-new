<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>
            <h3 class="mb-0">Kontrak Karyawan</h3>

            <p class="text-body fs-14 mb-0">
                Data kontrak kerja karyawan
            </p>
        </div>

        <?php if (can('employee_contract.create')): ?>
            <a
                href="<?= url('employee-contracts-create') ?>"
                class="btn btn-primary text-white erp-btn"
            >
                + Tambah Kontrak
            </a>
        <?php endif; ?>

    </div>

    <div class="p-20 border-top">

        <form method="GET" class="row g-3 align-items-end">

            <input type="hidden" name="page" value="employee-contracts">

            <div class="col-md-5">
                <label class="form-label">Cari Kontrak</label>

                <input
                    type="text"
                    name="search"
                    class="form-control erp-control erp-input"
                    value="<?= htmlspecialchars($search ?? '') ?>"
                    placeholder="No kontrak, nama karyawan, NIK, tipe, status"
                >
            </div>

            <div class="col-md-2">
                <div class="d-flex gap-2 filter-action-group">

                    <button class="btn btn-primary text-white erp-btn w-100">
                        Cari
                    </button>

                    <a href="<?= url('employee-contracts') ?>" class="btn btn-light erp-btn">
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
                        <th>No Kontrak</th>
                        <th>Karyawan</th>
                        <th>NIK</th>
                        <th>Tipe</th>
                        <th>Mulai</th>
                        <th>Selesai</th>
                        <th class="text-end">Salary</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($items)): ?>
                        <?php foreach ($items as $item): ?>
                            <?php
                            $status = $item['status'] ?? 'active';

                            $statusClass = [
                                'active' => 'bg-success bg-opacity-10 text-success',
                                'expired' => 'bg-danger bg-opacity-10 text-danger',
                                'terminated' => 'bg-dark bg-opacity-10 text-dark',
                                'renewed' => 'bg-info bg-opacity-10 text-info',
                            ];

                            $typeLabel = [
                                'probation' => 'Probation',
                                'contract' => 'Kontrak',
                                'permanent' => 'Permanent',
                                'freelance' => 'Freelance',
                                'internship' => 'Internship',
                            ];
                            ?>

                            <tr>
                                <td>
                                    <a
                                        href="<?= url('employee-contracts-show') ?>?id=<?= $item['id'] ?>"
                                        class="fw-semibold text-primary text-decoration-none"
                                    >
                                        <?= htmlspecialchars($item['contract_number'] ?? '-') ?>
                                    </a>
                                </td>

                                <td><?= htmlspecialchars($item['employee_name'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($item['employee_code'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($typeLabel[$item['contract_type'] ?? ''] ?? ($item['contract_type'] ?? '-')) ?></td>

                                <td>
                                    <?= !empty($item['start_date'])
                                        ? date('d M Y', strtotime($item['start_date']))
                                        : '-' ?>
                                </td>

                                <td>
                                    <?= !empty($item['end_date'])
                                        ? date('d M Y', strtotime($item['end_date']))
                                        : '-' ?>
                                </td>

                                <td class="text-end">
                                    <?php if (($item['salary'] ?? 0) > 0): ?>
                                        Rp <?= number_format((float) ($item['salary'] ?? 0), 0, ',', '.') ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <span class="default-badge <?= $statusClass[$status] ?? 'bg-secondary bg-opacity-10 text-secondary' ?>">
                                        <?= ucfirst($status) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-body py-4">
                                Belum ada data kontrak karyawan.
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
                                href="<?= url('employee-contracts') ?>?p=<?= $currentPage - 1 . $queryString ?>"
                            >
                                <i class="material-symbols-outlined">west</i>
                            </a>
                        </li>

                        <?php if ($startPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= url('employee-contracts') ?>?p=1<?= $queryString ?>">
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
                                    href="<?= url('employee-contracts') ?>?p=<?= $i . $queryString ?>"
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
                                    href="<?= url('employee-contracts') ?>?p=<?= $totalPages . $queryString ?>"
                                >
                                    <?= $totalPages ?>
                                </a>
                            </li>
                        <?php endif; ?>

                        <li class="page-item <?= $currentPage >= ($totalPages ?? 1) ? 'disabled' : '' ?>">
                            <a
                                class="page-link icon"
                                href="<?= url('employee-contracts') ?>?p=<?= $currentPage + 1 . $queryString ?>"
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