<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>
            <h3 class="mb-0">Website Testimonials</h3>
            <p class="text-body fs-14 mb-0">
                Kelola testimoni client yang tampil di website Iventlo.
            </p>
        </div>

        <?php if (can('website_testimonial.create')): ?>
            <a href="<?= url('website-testimonials-create') ?>" class="btn btn-primary text-white erp-btn">
                + Tambah Testimoni
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
                        <th>Client</th>
                        <th>Kategori</th>
                        <th>Testimoni</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th width="180">Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($testimonials)): ?>
                        <?php foreach ($testimonials as $testimonial): ?>
                            <?php
                            $status = strtolower($testimonial['status'] ?? 'active');

                            $statusClass = $status === 'active'
                                ? 'bg-success bg-opacity-10 text-success'
                                : 'bg-secondary bg-opacity-10 text-secondary';

                            $statusLabel = $status === 'active' ? 'Active' : 'Inactive';
                            ?>

                            <tr>
                                <td>
                                    <a href="<?= url('website-testimonials-edit') ?>?id=<?= $testimonial['id'] ?>"
                                       class="fw-semibold text-primary text-decoration-none">
                                        <?= htmlspecialchars($testimonial['name'] ?? '-') ?>
                                    </a>

                                    <div class="text-body fs-14">
                                        <?= htmlspecialchars($testimonial['company_name'] ?? '-') ?>
                                    </div>

                                    <?php if (!empty($testimonial['position'])): ?>
                                        <div class="text-body fs-14">
                                            <?= htmlspecialchars($testimonial['position']) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($testimonial['category_id'] ?? '-') ?>

                                    <?php if (!empty($testimonial['category_en']) && ($testimonial['category_en'] !== ($testimonial['category_id'] ?? ''))): ?>
                                        <div class="text-body fs-14">
                                            <?= htmlspecialchars($testimonial['category_en']) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(mb_strimwidth($testimonial['testimonial_id'] ?? '-', 0, 100, '...')) ?>
                                </td>

                                <td>
                                    <span class="default-badge bg-warning bg-opacity-10 text-warning">
                                        <?= (int) ($testimonial['rating'] ?? 5) ?> / 5
                                    </span>
                                </td>

                                <td>
                                    <span class="default-badge <?= $statusClass ?>">
                                        <?= $statusLabel ?>
                                    </span>
                                </td>

                                <td>
                                    <div class="d-flex gap-2">
                                        <?php if (can('website_testimonial.edit')): ?>
                                            <a href="<?= url('website-testimonials-edit') ?>?id=<?= $testimonial['id'] ?>"
                                               class="btn btn-sm btn-outline-primary">
                                                Edit
                                            </a>
                                        <?php endif; ?>

                                        <?php if (can('website_testimonial.delete')): ?>
                                            <a href="<?= url('website-testimonials-delete') ?>?id=<?= $testimonial['id'] ?>"
                                               class="btn btn-sm btn-outline-danger"
                                               onclick="return confirm('Yakin ingin menghapus testimoni ini?')">
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
                                Belum ada data testimoni.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php require __DIR__ . '/../../components/pagination.php'; ?>
    </div>
</div>
