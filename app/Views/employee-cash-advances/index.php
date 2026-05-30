<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>

            <h3 class="mb-0">
                Kasbon Karyawan
            </h3>

            <p class="text-body fs-14 mb-0">
                Data pengajuan kasbon dan pencairan karyawan
            </p>

        </div>

        <?php if (can('employee_cash_advance.create')): ?>

            <a
                href="<?= url('employee-cash-advances-create') ?>"
                class="btn btn-primary text-white erp-btn"
            >
                + Tambah Kasbon
            </a>

        <?php endif; ?>

    </div>

    <div class="default-table-area mx-minus-1">

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>No Kasbon</th>
                        <th>Karyawan</th>
                        <th>Kode</th>
                        <th class="text-end">Nominal</th>
                        <th class="text-end">Disetujui</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($items)): ?>

                        <?php foreach ($items as $item): ?>

                            <?php
                            $status = $item['status'] ?? '';

                            $statusLabel = [
                                'waiting_supervisor_approval' => 'Menunggu Persetujuan Atasan',
                                'waiting_finance_approval' => 'Menunggu Persetujuan Finance',
                                'waiting_disbursement' => 'Menunggu Pencairan',
                                'paid' => 'Telah Dicairkan',
                                'rejected' => 'Ditolak',
                                'cancelled' => 'Dibatalkan',
                            ];

                            $statusClass = [
                                'waiting_supervisor_approval' => 'bg-warning bg-opacity-10 text-warning',
                                'waiting_finance_approval' => 'bg-info bg-opacity-10 text-info',
                                'waiting_disbursement' => 'bg-primary bg-opacity-10 text-primary',
                                'paid' => 'bg-success bg-opacity-10 text-success',
                                'rejected' => 'bg-danger bg-opacity-10 text-danger',
                                'cancelled' => 'bg-secondary bg-opacity-10 text-secondary',
                            ];
                            ?>

                            <tr>

                                <td>

                                    <a
                                        href="<?= url('employee-cash-advances-show') ?>?id=<?= $item['id'] ?>"
                                        class="fw-semibold text-primary text-decoration-none"
                                    >
                                        <?= htmlspecialchars($item['cash_advance_number'] ?? '-') ?>
                                    </a>

                                </td>

                                <td>

                                    <?= htmlspecialchars($item['employee_name'] ?? '-') ?>

                                </td>

                                <td>

                                    <?= htmlspecialchars($item['employee_code'] ?? '-') ?>

                                </td>

                                <td class="text-end fw-semibold text-danger">

                                    Rp <?= number_format((float) ($item['amount'] ?? 0), 0, ',', '.') ?>

                                </td>

                                <td class="text-end fw-semibold text-success">

                                    Rp <?= number_format((float) ($item['approved_amount'] ?? 0), 0, ',', '.') ?>

                                </td>

                                <td>

                                    <span class="default-badge <?= $statusClass[$status] ?? 'bg-secondary bg-opacity-10 text-secondary' ?>">

                                        <?= $statusLabel[$status] ?? $status ?>

                                    </span>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="6" class="text-center text-body py-4">
                                Belum ada data kasbon.
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
                                href="<?= url('employee-cash-advances') ?>?p=<?= $currentPage - 1 ?>"
                            >
                                <i class="material-symbols-outlined">west</i>
                            </a>

                        </li>

                        <!-- First -->
                        <?php if ($startPage > 1): ?>

                            <li class="page-item">

                                <a
                                    class="page-link"
                                    href="<?= url('employee-cash-advances') ?>?p=1"
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
                                    href="<?= url('employee-cash-advances') ?>?p=<?= $i ?>"
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
                                    href="<?= url('employee-cash-advances') ?>?p=<?= $totalPages ?>"
                                >
                                    <?= $totalPages ?>
                                </a>

                            </li>

                        <?php endif; ?>

                        <!-- Next -->
                        <li class="page-item <?= $currentPage >= ($totalPages ?? 1) ? 'disabled' : '' ?>">

                            <a
                                class="page-link icon"
                                href="<?= url('employee-cash-advances') ?>?p=<?= $currentPage + 1 ?>"
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