<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>

            <h3 class="mb-0">
                Jadwal Harian Operasional
            </h3>

            <p class="text-body fs-14 mb-0">
                Kontrol jadwal kirim dan bongkar unit rental.
            </p>

        </div>

    </div>

    <div class="p-20 border-top">

        <form method="GET" class="row g-3 align-items-end">

            <input type="hidden" name="page" value="schedules">

            <div class="col-md-3">

                <label class="form-label">
                    Tanggal Jadwal
                </label>

                <input
                    type="date"
                    name="date"
                    class="form-control erp-control erp-input"
                    value="<?= htmlspecialchars($date) ?>"
                >

            </div>

            <div class="col-md-2">

                <div class="d-flex gap-2 filter-action-group">

                    <button
                        class="btn btn-primary text-white erp-btn w-100"
                    >
                        Filter
                    </button>

                    <a
                        href="<?= url('schedules') ?>"
                        class="btn btn-light erp-btn"
                    >
                        Reset
                    </a>

                </div>

            </div>

        </form>

    </div>

</div>
<div class="row">
    <div class="col-xl-6">
        <div class="card bg-white rounded-10 border border-white mb-4">
            <div class="p-20 border-bottom">
                <h3 class="mb-0">Jadwal Kirim</h3>
            </div>

            <div class="default-table-area mx-minus-1">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Jam</th>
                                <th>No Rental</th>
                                <th>Customer</th>
                                <th>Lokasi</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($deliveries)): ?>
                                <?php foreach ($deliveries as $rental): ?>
                                    <?php
                                    $status = $rental['status_rental'] ?? 'draft';
                                    $statusClass = match ($status) {
                                        'draft' => 'bg-secondary bg-opacity-10 text-secondary',
                                        'scheduled' => 'bg-primary bg-opacity-10 text-primary',
                                        'on_rent' => 'bg-warning bg-opacity-10 text-warning',
                                        'completed' => 'bg-success bg-opacity-10 text-success',
                                        'cancelled' => 'bg-danger bg-opacity-10 text-danger',
                                        default => 'bg-secondary bg-opacity-10 text-secondary'
                                    };
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($rental['jam_kirim'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($rental['no_rental'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($rental['customer_name'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($rental['lokasi'] ?? '-') ?></td>
                                        <td>
                                            <span class="default-badge <?= $statusClass ?>">
                                                <?= htmlspecialchars($status) ?>
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <a href="<?= url('rentals-show') ?>?id=<?= $rental['id'] ?>" class="text-primary" title="Detail">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <?php if (($rental['status_rental'] ?? '') === 'scheduled'): ?>
                                                <a href="<?= url('rentals-process-out') ?>?id=<?= $rental['id'] ?>"
                                                   class="text-warning me-2"
                                                   title="Proses Keluar"
                                                   onclick="return confirm('Proses unit keluar?')">
                                                   <i class="ri-truck-line"></i>
                                               </a>
                                           <?php endif; ?>
                                       </td>
                                   </tr>
                               <?php endforeach; ?>
                           <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-body">
                                    Tidak ada jadwal kirim.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-6">
    <div class="card bg-white rounded-10 border border-white mb-4">
        <div class="p-20 border-bottom">
            <h3 class="mb-0">Jadwal Bongkar</h3>
        </div>

        <div class="default-table-area mx-minus-1">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Jam</th>
                            <th>No Rental</th>
                            <th>Customer</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pickups)): ?>
                            <?php foreach ($pickups as $rental): ?>
                                <?php
                                $status = $rental['status_rental'] ?? 'draft';
                                $statusClass = match ($status) {
                                    'draft' => 'bg-secondary bg-opacity-10 text-secondary',
                                    'scheduled' => 'bg-primary bg-opacity-10 text-primary',
                                    'on_rent' => 'bg-warning bg-opacity-10 text-warning',
                                    'completed' => 'bg-success bg-opacity-10 text-success',
                                    'cancelled' => 'bg-danger bg-opacity-10 text-danger',
                                    default => 'bg-secondary bg-opacity-10 text-secondary'
                                };
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($rental['jam_bongkar'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($rental['no_rental'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($rental['customer_name'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($rental['lokasi'] ?? '-') ?></td>
                                    <td>
                                        <span class="default-badge <?= $statusClass ?>">
                                            <?= htmlspecialchars($status) ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="<?= url('rentals-show') ?>?id=<?= $rental['id'] ?>" class="text-primary" title="Detail">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <?php if (($rental['status_rental'] ?? '') === 'on_rent'): ?>
                                            <a href="<?= url('rentals-process-return') ?>?id=<?= $rental['id'] ?>"
                                             class="text-success me-2"
                                             title="Proses Kembali"
                                             onclick="return confirm('Proses unit kembali?')">
                                             <i class="ri-inbox-archive-line"></i>
                                         </a>
                                     <?php endif; ?>
                                 </td>
                             </tr>
                         <?php endforeach; ?>
                     <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-body">
                                Tidak ada jadwal bongkar.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
</div>