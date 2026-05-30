<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>
            <h3 class="mb-0">
                Vendor
            </h3>

            <p class="text-body fs-14 mb-0">
                Data master vendor dan supplier perusahaan
            </p>
        </div>

        <a
            href="<?= url('vendors-create') ?>"
            class="btn btn-primary text-white erp-btn"
        >
            + Tambah Vendor
        </a>

    </div>

    <div class="default-table-area mx-minus-1">

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Vendor</th>
                        <th>PIC</th>
                        <th>No. HP</th>
                        <th>Email</th>
                        <th>NPWP</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($vendors)): ?>

                        <?php foreach ($vendors as $vendor): ?>

                            <tr>

                                <td>
                                    <?= htmlspecialchars($vendor['vendor_code'] ?? '-') ?>
                                </td>

                                <td>
                                    <a
                                        href="<?= url('vendors-edit') ?>?id=<?= $vendor['id'] ?>"
                                        class="fw-semibold text-primary text-decoration-none"
                                    >
                                        <?= htmlspecialchars($vendor['vendor_name'] ?? '-') ?>
                                    </a>
                                </td>

                                <td>
                                    <?= htmlspecialchars($vendor['pic_name'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($vendor['phone'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($vendor['email'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($vendor['npwp'] ?? '-') ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="6" class="text-center text-body py-4">
                                Belum ada vendor.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>