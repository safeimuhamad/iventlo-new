<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="row align-items-center g-3 p-20">

        <div class="col-lg-4">

            <div>
                <h3 class="mb-0">
                    Cek Stok Unit
                </h3>

                <p class="text-body fs-14 mb-0">
                    Cek availability unit berdasarkan tanggal rental.
                </p>
            </div>

        </div>

        <div class="col-lg-8">

            <form method="GET">

                <input type="hidden" name="page" value="stock">

                <div class="row g-3 align-items-end">

                    <div class="col-md-4">

                        <label class="form-label">
                            Tanggal Rental
                        </label>

                        <input
                            type="date"
                            name="tanggal_rental"
                            class="form-control erp-control erp-input"
                            value="<?= htmlspecialchars($tanggalRental) ?>"
                        >

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">
                            Tanggal Selesai
                        </label>

                        <input
                            type="date"
                            name="tanggal_selesai"
                            class="form-control erp-control erp-input"
                            value="<?= htmlspecialchars($tanggalSelesai) ?>"
                        >

                    </div>

                    <div class="col-md-4">

                        <div class="d-flex gap-2 filter-action-group">

                            <button
                                class="btn btn-primary text-white erp-btn w-100"
                            >

                                <span class="material-symbols-outlined align-middle fs-18 me-1">
                                    search
                                </span>

                                Cek Stok

                            </button>

                            <a
                                href="<?= url('stock') ?>"
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

        <div class="card bg-white rounded-10 border border-white p-20 mb-4">

            <h4 class="mb-3 text-success">
                Unit Available
            </h4>

            <?php if (!empty($availableUnits)): ?>

                <?php foreach ($availableUnits as $unit): ?>

                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">

                        <div>
                            <div class="fw-semibold">
                                <?= htmlspecialchars($unit['nama_unit']) ?>
                            </div>
                        </div>

                        <span class="badge bg-success">
                            <?= $unit['total'] ?> Unit
                        </span>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <div class="text-body">
                    Tidak ada unit tersedia.
                </div>

            <?php endif; ?>

        </div>

    </div>

    <div class="col-xl-6">

        <div class="card bg-white rounded-10 border border-white p-20 mb-4">

            <h4 class="mb-3 text-warning">
                Unit Terpakai
            </h4>

            <?php if (!empty($bookedUnits)): ?>

                <?php foreach ($bookedUnits as $unit): ?>

                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">

                        <div>
                            <div class="fw-semibold">
                                <?= htmlspecialchars($unit['nama_unit']) ?>
                            </div>
                        </div>

                        <span class="badge bg-warning text-dark">
                            <?= $unit['total'] ?> Unit
                        </span>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <div class="text-body">
                    Tidak ada unit terpakai.
                </div>

            <?php endif; ?>

        </div>

    </div>

</div>

