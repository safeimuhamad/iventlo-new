<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>
            <h3 class="mb-0">Periode Payroll</h3>

            <p class="text-body fs-14 mb-0">
                Data periode payroll karyawan
            </p>
        </div>

        <a
            href="<?= url('payroll-periods-create') ?>"
            class="btn btn-primary text-white erp-btn"
        >
            + Tambah Periode
        </a>

    </div>

    <div class="p-20 border-top">

        <form method="GET" class="row g-3 align-items-end">

            <input type="hidden" name="page" value="payroll-periods">

            <div class="col-md-5">

                <label class="form-label">Cari Periode Payroll</label>

                <input
                    type="text"
                    name="search"
                    class="form-control erp-control erp-input"
                    placeholder="Cari periode atau status..."
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
                        href="<?= url('payroll-periods') ?>"
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
                        <th>Periode</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Tanggal Payroll</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($payrollPeriods)): ?>

                        <?php foreach ($payrollPeriods as $period): ?>

                            <?php
                            $statusLabels = [
                                'draft' => 'Draft',
                                'processed' => 'Diproses',
                                'paid' => 'Dibayar',
                                'closed' => 'Ditutup'
                            ];

                            $statusClass = [
                                'draft' => 'bg-secondary bg-opacity-10 text-secondary',
                                'processed' => 'bg-warning bg-opacity-10 text-warning',
                                'paid' => 'bg-success bg-opacity-10 text-success',
                                'closed' => 'bg-dark bg-opacity-10 text-dark'
                            ];

                            $status = $period['status'] ?? '';
                            ?>

                            <tr>

                                <td>

                                    <a
                                        href="<?= url('payrolls') ?>?period_id=<?= $period['id'] ?>"
                                        class="fw-semibold text-primary text-decoration-none"
                                    >
                                        <?= htmlspecialchars($period['period_name'] ?? '-') ?>
                                    </a>

                                </td>

                                <td>

                                    <?php if (!empty($period['start_date'])): ?>

                                        <?= date('d M Y', strtotime($period['start_date'])) ?>

                                    <?php else: ?>

                                        <span class="text-muted">-</span>

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <?php if (!empty($period['end_date'])): ?>

                                        <?= date('d M Y', strtotime($period['end_date'])) ?>

                                    <?php else: ?>

                                        <span class="text-muted">-</span>

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <?php if (!empty($period['payroll_date'])): ?>

                                        <?= date('d M Y', strtotime($period['payroll_date'])) ?>

                                    <?php else: ?>

                                        <span class="text-muted">-</span>

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <span class="default-badge <?= $statusClass[$status] ?? 'bg-secondary bg-opacity-10 text-secondary' ?>">

                                        <?= $statusLabels[$status] ?? '-' ?>

                                    </span>

                                </td>

                                <td class="text-end">

                                    <div class="d-flex justify-content-end gap-2">

                                        <a
                                            href="<?= url('payroll-periods-show') ?>?id=<?= $period['id'] ?>"
                                            class="btn btn-outline-primary btn-sm"
                                        >
                                            Detail
                                        </a>

                                    </div>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Belum ada periode payroll.
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
                            href="<?= url('payroll-periods') ?>?p=<?= $currentPage - 1 . $queryString ?>"
                        >
                            <i class="material-symbols-outlined">west</i>
                        </a>

                    </li>

                    <!-- First -->
                    <?php if ($startPage > 1): ?>

                        <li class="page-item">
                            <a
                                class="page-link"
                                href="<?= url('payroll-periods') ?>?p=1<?= $queryString ?>"
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
                                href="<?= url('payroll-periods') ?>?p=<?= $i . $queryString ?>"
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
                                href="<?= url('payroll-periods') ?>?p=<?= $totalPages . $queryString ?>"
                            >
                                <?= $totalPages ?>
                            </a>
                        </li>

                    <?php endif; ?>

                    <!-- Next -->
                    <li class="page-item <?= $currentPage >= ($totalPages ?? 1) ? 'disabled' : '' ?>">

                        <a
                            class="page-link icon"
                            href="<?= url('payroll-periods') ?>?p=<?= $currentPage + 1 . $queryString ?>"
                        >
                            <i class="material-symbols-outlined">east</i>
                        </a>

                    </li>

                </ul>

            </nav>

        <?php endif; ?>

    </div>

</div>