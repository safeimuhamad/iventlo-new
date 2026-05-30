<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>
            <h3 class="mb-0">Website Services</h3>
            <p class="text-body fs-14 mb-0">
                Kelola layanan yang tampil di website Iventlo.
            </p>
        </div>

        <?php if (can('website_service.create')): ?>
            <a href="<?= url('website-services-create') ?>" class="btn btn-primary text-white erp-btn">
                + Tambah Layanan
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
                        <th>Layanan</th>
                        <th>Title English</th>
                        <th>Icon</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th width="180">Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($services)): ?>
                        <?php foreach ($services as $service): ?>
                            <?php
                            $status = strtolower($service['status'] ?? 'active');

                            $statusClass = $status === 'active'
                                ? 'bg-success bg-opacity-10 text-success'
                                : 'bg-secondary bg-opacity-10 text-secondary';

                            $statusLabel = $status === 'active' ? 'Active' : 'Inactive';
                            ?>

                            <tr>
                                <td>
                                    <a href="<?= url('website-services-edit') ?>?id=<?= $service['id'] ?>"
                                       class="fw-semibold text-primary text-decoration-none">
                                        <?= htmlspecialchars($service['title_id'] ?? '-') ?>
                                    </a>

                                    <?php if (!empty($service['description_id'])): ?>
                                        <div class="text-body fs-14">
                                            <?= htmlspecialchars(mb_strimwidth($service['description_id'], 0, 80, '...')) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($service['title_en'] ?? '-') ?>
                                </td>

                                <td>
                                    <?php if (!empty($service['icon'])): ?>
                                        <span class="default-badge bg-primary bg-opacity-10 text-primary">
                                            <?= htmlspecialchars($service['icon']) ?>
                                        </span>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?= (int) ($service['sort_order'] ?? 0) ?>
                                </td>

                                <td>
                                    <span class="default-badge <?= $statusClass ?>">
                                        <?= $statusLabel ?>
                                    </span>
                                </td>

                                <td>
                                    <div class="d-flex gap-2">
                                        <?php if (can('website_service.edit')): ?>
                                            <a href="<?= url('website-services-edit') ?>?id=<?= $service['id'] ?>"
                                               class="btn btn-sm btn-outline-primary">
                                                Edit
                                            </a>
                                        <?php endif; ?>

                                        <?php if (can('website_service.delete')): ?>
                                            <a href="<?= url('website-services-delete') ?>?id=<?= $service['id'] ?>"
                                               class="btn btn-sm btn-outline-danger"
                                               onclick="return confirm('Yakin ingin menghapus layanan ini?')">
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
                                Belum ada data layanan.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php require __DIR__ . '/../../components/pagination.php'; ?>
    </div>
</div>
