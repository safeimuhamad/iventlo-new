<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>
            <h3 class="mb-0">Cuti / Izin</h3>

            <p class="text-body fs-14 mb-0">
                Data pengajuan cuti dan izin karyawan
            </p>
        </div>

        <a
        href="<?= url('leave-requests-create') ?>"
        class="btn btn-primary text-white erp-btn"
        >
        + Tambah Pengajuan
    </a>

</div>

<div class="p-20 border-top">

    <form method="GET" class="row g-3 align-items-end">

        <input type="hidden" name="page" value="leave-requests">

        <div class="col-md-5">

            <label class="form-label">Cari Pengajuan</label>

            <input
            type="text"
            name="search"
            class="form-control erp-control erp-input"
            placeholder="Cari karyawan, kode, jenis, status..."
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
            href="<?= url('leave-requests') ?>"
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
                    <th>Jenis</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Total Hari</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($leaveRequests)): ?>
                    <?php foreach ($leaveRequests as $leaveRequest): ?>
                        <?php
                        $typeLabels = [
                            'annual_leave' => 'Cuti Tahunan',
                            'sick' => 'Sakit',
                            'permission' => 'Izin',
                            'unpaid_leave' => 'Cuti Tanpa Gaji',
                            'other' => 'Lainnya'
                        ];

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

                        $status = $leaveRequest['status'] ?? '';
                        ?>

                        <tr>
                            <td>
                                <a
                                href="<?= url('leave-requests-show') ?>?id=<?= $leaveRequest['id'] ?>"
                                class="fw-semibold text-primary text-decoration-none"
                                >
                                <?= htmlspecialchars($leaveRequest['full_name'] ?? '-') ?>
                            </a>
                        </td>

                        <td><?= htmlspecialchars($leaveRequest['employee_code'] ?? '-') ?></td>

                        <td><?= htmlspecialchars($leaveRequest['department_name'] ?? '-') ?></td>

                        <td><?= htmlspecialchars($leaveRequest['position_name'] ?? '-') ?></td>

                        <td><?= $typeLabels[$leaveRequest['leave_type'] ?? ''] ?? '-' ?></td>

                        <td>
                            <?= !empty($leaveRequest['start_date'])
                            ? date('d M Y', strtotime($leaveRequest['start_date']))
                            : '-' ?>
                        </td>

                        <td>
                            <?= !empty($leaveRequest['end_date'])
                            ? date('d M Y', strtotime($leaveRequest['end_date']))
                            : '-' ?>
                        </td>

                        <td><?= (int) ($leaveRequest['total_days'] ?? 0) ?> hari</td>

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
                        Belum ada data cuti / izin.
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
                    href="<?= url('leave-requests') ?>?p=<?= $currentPage - 1 . $queryString ?>"
                    >
                    <i class="material-symbols-outlined">west</i>
                </a>

            </li>

            <!-- First -->
            <?php if ($startPage > 1): ?>

                <li class="page-item">
                    <a
                    class="page-link"
                    href="<?= url('leave-requests') ?>?p=1<?= $queryString ?>"
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
                href="<?= url('leave-requests') ?>?p=<?= $i . $queryString ?>"
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
            href="<?= url('leave-requests') ?>?p=<?= $totalPages . $queryString ?>"
            >
            <?= $totalPages ?>
        </a>
    </li>

<?php endif; ?>

<!-- Next -->
<li class="page-item <?= $currentPage >= ($totalPages ?? 1) ? 'disabled' : '' ?>">

    <a
    class="page-link icon"
    href="<?= url('leave-requests') ?>?p=<?= $currentPage + 1 . $queryString ?>"
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