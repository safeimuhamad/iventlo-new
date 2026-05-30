<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="row align-items-center g-3 p-20">

        <div class="col-lg-4">

            <div>

                <h3 class="mb-0">
                    Jadwal Teknisi
                </h3>

                <p class="text-body fs-14 mb-0">
                    Cek teknisi available dan yang sedang bertugas.
                </p>

            </div>

        </div>

        <div class="col-lg-8">

            <form method="GET">

                <input type="hidden" name="page" value="technician-schedules">

                <div class="row g-3 align-items-end">

                    <div class="col-md-4">

                        <label class="form-label">
                            Tanggal
                        </label>

                        <input
                            type="date"
                            name="date"
                            class="form-control erp-control erp-input"
                            value="<?= htmlspecialchars($date) ?>"
                        >

                    </div>

                    <div class="col-md-3">

                        <div class="d-flex gap-2 filter-action-group">

                            <button
                                class="btn btn-primary text-white erp-btn w-100"
                            >
                                Cek 
                            </button>

                            <a
                                href="<?= url('technician-schedules') ?>"
                                class="btn btn-light erp-btn"
                            >
                                Reset
                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>
<div class="row">

    <div class="col-xl-6">

        <div class="card bg-white rounded-10 border border-white mb-4">

            <div class="p-20 border-bottom">
                <h4 class="mb-0 text-success">
                    Teknisi Available
                </h4>
            </div>

            <div class="p-20">

                <?php if (!empty($available)): ?>

                    <?php foreach ($available as $tech): ?>

                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">

                            <div>
                                <div class="fw-semibold">
                                    <?= htmlspecialchars($tech['name']) ?>
                                </div>

                                <small class="text-body">
                                    <?= htmlspecialchars($tech['role_type'] ?? '-') ?>
                                </small>
                            </div>

                            <span class="badge bg-success">
                                Available
                            </span>

                        </div>

                    <?php endforeach; ?>

                <?php else: ?>

                    <div class="text-body">
                        Tidak ada teknisi available.
                    </div>

                <?php endif; ?>

            </div>

        </div>

    </div>

    <div class="col-xl-6">

        <div class="card bg-white rounded-10 border border-white mb-4">

            <div class="p-20 border-bottom">
                <h4 class="mb-0 text-warning">
                    Teknisi Bertugas
                </h4>
            </div>

            <div class="p-20">

                <?php if (!empty($assigned)): ?>

                    <?php foreach ($assigned as $item): ?>

                        <div class="border-bottom py-2">

                            <div class="d-flex justify-content-between">

                                <div>
                                    <div class="fw-semibold">
                                        <?= htmlspecialchars($item['name']) ?>
                                    </div>

                                    <small class="text-body">
                                        <?= htmlspecialchars($item['customer_name']) ?>
                                    </small>
                                </div>

                                <span class="badge bg-warning text-dark">
                                    <?= htmlspecialchars($item['task_type']) ?>
                                </span>

                            </div>

                            <small class="text-body">
                                <?= htmlspecialchars($item['scheduled_time'] ?? '-') ?>
                                •
                                <?= htmlspecialchars($item['lokasi'] ?? '-') ?>
                            </small>

                        </div>

                    <?php endforeach; ?>

                <?php else: ?>

                    <div class="text-body">
                        Tidak ada teknisi bertugas.
                    </div>

                <?php endif; ?>

            </div>

        </div>

    </div>

</div>