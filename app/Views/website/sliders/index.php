<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>

            <h3 class="mb-0">
                Website Slider
            </h3>

            <p class="text-body fs-14 mb-0">
                Kelola banner slider homepage website Iventlo
            </p>

        </div>

        <?php if (can('website_slider.create')): ?>
            <a
                href="<?= url('website-sliders-create') ?>"
                class="btn btn-primary text-white erp-btn"
            >
                + Tambah Slider
            </a>
        <?php endif; ?>

    </div>

    <?php if (!empty($_SESSION['error'])): ?>

        <div class="alert alert-danger m-20">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>

    <?php endif; ?>

    <div class="default-table-area mx-minus-1">

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>Slider</th>
                        <th>Title Indonesia</th>
                        <th>Title English</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th width="180">Action</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($sliders)): ?>

                        <?php foreach ($sliders as $slider): ?>

                            <?php
                            $status = strtolower($slider['status'] ?? 'active');

                            $statusClass = $status === 'active'
                                ? 'bg-success bg-opacity-10 text-success'
                                : 'bg-secondary bg-opacity-10 text-secondary';

                            $statusLabel = $status === 'active'
                                ? 'Active'
                                : 'Inactive';
                            ?>

                            <tr>

                                <td width="180">

                                    <?php if (!empty($slider['image'])): ?>

                                        <img
                                            src="<?= uploadAsset($slider['image']) ?>"
                                            alt="Slider"
                                            style="
                                                width:140px;
                                                height:70px;
                                                object-fit:cover;
                                                border-radius:10px;
                                            "
                                        >

                                    <?php else: ?>

                                        <span class="text-body">
                                            No Image
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <a
                                        href="<?= url('website-sliders-edit') ?>?id=<?= $slider['id'] ?>"
                                        class="fw-semibold text-primary text-decoration-none"
                                    >
                                        <?= htmlspecialchars($slider['title_id'] ?? '-') ?>
                                    </a>

                                </td>

                                <td>
                                    <?= htmlspecialchars($slider['title_en'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= (int) ($slider['sort_order'] ?? 0) ?>
                                </td>

                                <td>

                                    <span class="default-badge <?= $statusClass ?>">
                                        <?= $statusLabel ?>
                                    </span>

                                </td>

                                <td>

                                    <div class="d-flex gap-2">

                                        <?php if (can('website_slider.edit')): ?>

                                            <a
                                                href="<?= url('website-sliders-edit') ?>?id=<?= $slider['id'] ?>"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                Edit
                                            </a>

                                        <?php endif; ?>

                                        <?php if (can('website_slider.delete')): ?>

                                            <a
                                                href="<?= url('website-sliders-delete') ?>?id=<?= $slider['id'] ?>"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Yakin ingin menghapus slider ini?')"
                                            >
                                                Hapus
                                            </a>

                                        <?php endif; ?>

                                    </div>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>

                            <td colspan="6" class="text-center text-body py-4">

                                Belum ada data slider.

                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

        <?php require __DIR__ . '/../../components/pagination.php'; ?>

    </div>

</div>
