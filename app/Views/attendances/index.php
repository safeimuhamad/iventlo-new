<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>
            <h3 class="mb-0">Absensi</h3>

            <p class="text-body fs-14 mb-0">
                Data kehadiran karyawan
            </p>
        </div>

        <a
        href="<?= url('attendances-create') ?>"
        class="btn btn-primary text-white erp-btn"
        >
        + Tambah Absensi
    </a>

</div>

<div class="p-20 border-top">

    <form method="GET" class="row g-3 align-items-end">

        <input type="hidden" name="page" value="attendances">

        <div class="col-md-5">

            <label class="form-label">Cari Absensi</label>

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
            href="<?= url('attendances') ?>"
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
                    <th>Tanggal</th>
                    <th>NIK</th>
                    <th>Nama Karyawan</th>
                    <th>Divisi</th>
                    <th>Jabatan</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Status</th>
                    <th>Terlambat</th>
                    <th>Lembur</th>
                </tr>
            </thead>

            <tbody>

                <?php if (!empty($attendances)): ?>

                    <?php foreach ($attendances as $attendance): ?>

                        <?php
                        $statusLabels = [
                            'present' => 'Hadir',
                            'late' => 'Terlambat',
                            'permission' => 'Izin',
                            'sick' => 'Sakit',
                            'leave' => 'Cuti',
                            'absent' => 'Alpa'
                        ];

                        $statusClass = [
                            'present' => 'bg-success bg-opacity-10 text-success',
                            'late' => 'bg-warning bg-opacity-10 text-warning',
                            'permission' => 'bg-info bg-opacity-10 text-info',
                            'sick' => 'bg-primary bg-opacity-10 text-primary',
                            'leave' => 'bg-secondary bg-opacity-10 text-secondary',
                            'absent' => 'bg-danger bg-opacity-10 text-danger'
                        ];

                        $status = $attendance['status'] ?? '';
                        ?>

                        <tr>

                            <td>

                                <a
                                href="<?= url('attendances-show') ?>?id=<?= $attendance['id'] ?>"
                                class="fw-semibold text-primary text-decoration-none"
                                >
                                <?php if (!empty($attendance['attendance_date'])): ?>

                                    <?= date('d M Y', strtotime($attendance['attendance_date'])) ?>

                                <?php else: ?>

                                    -

                                <?php endif; ?>
                            </a>

                        </td>
                        <td>
                            <?= htmlspecialchars($attendance['employee_code'] ?? '-') ?>

                        </td>
                        <td>

                            <?= htmlspecialchars($attendance['full_name'] ?? '-') ?>


                        </td>
                        <td>

                            <?= htmlspecialchars($attendance['department_name'] ?? '-') ?>


                        </td>
                        <td>
                            <?= htmlspecialchars($attendance['position_name'] ?? '-') ?>
                        </td>

                        <td>

                            <?= !empty($attendance['check_in'])
                            ? substr($attendance['check_in'], 0, 5)
                            : '-' ?>

                        </td>

                        <td>

                            <?= !empty($attendance['check_out'])
                            ? substr($attendance['check_out'], 0, 5)
                            : '-' ?>

                        </td>

                        <td>

                            <span class="default-badge <?= $statusClass[$status] ?? 'bg-secondary bg-opacity-10 text-secondary' ?>">

                                <?= $statusLabels[$status] ?? '-' ?>

                            </span>

                        </td>

                        <td>

                            <?php if (($attendance['late_minutes'] ?? 0) > 0): ?>

                                <span class="text-warning fw-semibold">
                                    <?= (int) ($attendance['late_minutes'] ?? 0) ?> menit
                                </span>

                            <?php else: ?>

                                <span class="text-muted">-</span>

                            <?php endif; ?>

                        </td>

                        <td>

                            <?php if (($attendance['overtime_minutes'] ?? 0) > 0): ?>

                                <span class="text-success fw-semibold">
                                    <?= (int) ($attendance['overtime_minutes'] ?? 0) ?> menit
                                </span>

                            <?php else: ?>

                                <span class="text-muted">-</span>

                            <?php endif; ?>

                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        Belum ada data absensi.
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
                    href="<?= url('attendances') ?>?p=<?= $currentPage - 1 . $queryString ?>"
                    >
                    <i class="material-symbols-outlined">west</i>
                </a>

            </li>

            <!-- First -->
            <?php if ($startPage > 1): ?>

                <li class="page-item">
                    <a
                    class="page-link"
                    href="<?= url('attendances') ?>?p=1<?= $queryString ?>"
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
                href="<?= url('attendances') ?>?p=<?= $i . $queryString ?>"
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
            href="<?= url('attendances') ?>?p=<?= $totalPages . $queryString ?>"
            >
            <?= $totalPages ?>
        </a>
    </li>

<?php endif; ?>

<!-- Next -->
<li class="page-item <?= $currentPage >= ($totalPages ?? 1) ? 'disabled' : '' ?>">

    <a
    class="page-link icon"
    href="<?= url('attendances') ?>?p=<?= $currentPage + 1 . $queryString ?>"
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