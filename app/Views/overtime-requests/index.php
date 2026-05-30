<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>
            <h3 class="mb-0">Lembur</h3>

            <p class="text-body fs-14 mb-0">
                Data pengajuan lembur karyawan
            </p>
        </div>

        <a
            href="<?= url('overtime-requests-create') ?>"
            class="btn btn-primary text-white erp-btn"
        >
            + Tambah Lembur
        </a>

    </div>

    <div class="p-20 border-top">

        <form method="GET" class="row g-3 align-items-end">

            <input type="hidden" name="page" value="overtime-requests">

            <div class="col-md-5">

                <label class="form-label">Cari Lembur</label>

                <input
                    type="text"
                    name="search"
                    class="form-control erp-control erp-input"
                    placeholder="Cari karyawan, kode, tanggal, status..."
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
                        href="<?= url('overtime-requests') ?>"
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
                        <th>Nama Karyawan</th>
                        <th>Kode</th>
                        <th>Divisi</th>
                        <th>Jabatan</th>
                        <th>Tanggal</th>
                        <th>Jam Mulai</th>
                        <th>Jam Selesai</th>
                        <th>Durasi</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($overtimeRequests)): ?>

                        <?php foreach ($overtimeRequests as $overtimeRequest): ?>

                            <?php
                            $statusLabels = [
                                'draft' => 'Draft',
                                'waiting_approval' => 'Menunggu Approval',
                                'approved' => 'Disetujui',
                                'rejected' => 'Ditolak',
                                'cancelled' => 'Dibatalkan'
                            ];

                            $statusClass = [
                                'draft' => 'bg-secondary bg-opacity-10 text-secondary',
                                'waiting_approval' => 'bg-warning bg-opacity-10 text-warning',
                                'approved' => 'bg-success bg-opacity-10 text-success',
                                'rejected' => 'bg-danger bg-opacity-10 text-danger',
                                'cancelled' => 'bg-dark bg-opacity-10 text-dark'
                            ];

                            $status = $overtimeRequest['status'] ?? '';

                            $minutes = (int) ($overtimeRequest['total_minutes'] ?? 0);
                            $hours = floor($minutes / 60);
                            $remainingMinutes = $minutes % 60;
                            ?>

                            <tr>

                                <td>

                                    <a
                                        href="<?= url('overtime-requests-show') ?>?id=<?= $overtimeRequest['id'] ?>"
                                        class="fw-semibold text-primary text-decoration-none"
                                    >
                                        <?= htmlspecialchars($overtimeRequest['full_name'] ?? '-') ?>
                                    </a>

                                </td>

                                <td>
                                    <?= htmlspecialchars($overtimeRequest['employee_code'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($overtimeRequest['department_name'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($overtimeRequest['position_name'] ?? '-') ?>
                                </td>

                                <td>

                                    <?php if (!empty($overtimeRequest['overtime_date'])): ?>

                                        <?= date('d M Y', strtotime($overtimeRequest['overtime_date'])) ?>

                                    <?php else: ?>

                                        <span class="text-muted">-</span>

                                    <?php endif; ?>

                                </td>

                                <td>
                                    <?= htmlspecialchars($overtimeRequest['start_time'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($overtimeRequest['end_time'] ?? '-') ?>
                                </td>

                                <td>

                                    <span class="fw-semibold">

                                        <?= $hours ?> jam

                                        <?php if ($remainingMinutes > 0): ?>
                                            <?= $remainingMinutes ?> menit
                                        <?php endif; ?>

                                    </span>

                                </td>

                                <td>

                                    <span class="default-badge <?= $statusClass[$status] ?? 'bg-secondary bg-opacity-10 text-secondary' ?>">

                                        <?= $statusLabels[$status] ?? '-' ?>

                                    </span>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                Belum ada data lembur.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

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
                                href="<?= url('overtime-requests') ?>?p=<?= $currentPage - 1 . $queryString ?>"
                            >
                                <i class="material-symbols-outlined">west</i>
                            </a>

                        </li>

                        <!-- First -->
                        <?php if ($startPage > 1): ?>

                            <li class="page-item">
                                <a
                                    class="page-link"
                                    href="<?= url('overtime-requests') ?>?p=1<?= $queryString ?>"
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
                                    href="<?= url('overtime-requests') ?>?p=<?= $i . $queryString ?>"
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
                                    href="<?= url('overtime-requests') ?>?p=<?= $totalPages . $queryString ?>"
                                >
                                    <?= $totalPages ?>
                                </a>
                            </li>

                        <?php endif; ?>

                        <!-- Next -->
                        <li class="page-item <?= $currentPage >= ($totalPages ?? 1) ? 'disabled' : '' ?>">

                            <a
                                class="page-link icon"
                                href="<?= url('overtime-requests') ?>?p=<?= $currentPage + 1 . $queryString ?>"
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