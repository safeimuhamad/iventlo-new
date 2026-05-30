<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>
            <h3 class="mb-0">Website FAQ</h3>
            <p class="text-body fs-14 mb-0">
                Kelola pertanyaan umum yang tampil di website Iventlo.
            </p>
        </div>

        <?php if (can('website_faq.create')): ?>
            <a href="<?= url('website-faqs-create') ?>" class="btn btn-primary text-white erp-btn">
                + Tambah FAQ
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
                        <th>Pertanyaan</th>
                        <th>Kategori</th>
                        <th>Answer</th>
                        <th>Status</th>
                        <th width="180">Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($faqs)): ?>
                        <?php foreach ($faqs as $faq): ?>
                            <?php
                            $status = strtolower($faq['status'] ?? 'active');

                            $statusClass = $status === 'active'
                                ? 'bg-success bg-opacity-10 text-success'
                                : 'bg-secondary bg-opacity-10 text-secondary';

                            $statusLabel = $status === 'active' ? 'Active' : 'Inactive';
                            ?>

                            <tr>
                                <td>
                                    <a
                                        href="<?= url('website-faqs-edit') ?>?id=<?= $faq['id'] ?>"
                                        class="fw-semibold text-primary text-decoration-none"
                                    >
                                        <?= htmlspecialchars($faq['question_id'] ?? '-') ?>
                                    </a>

                                    <?php if (!empty($faq['question_en'])): ?>
                                        <div class="text-body fs-14">
                                            <?= htmlspecialchars($faq['question_en']) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($faq['category_id'] ?? '-') ?>

                                    <?php if (!empty($faq['category_en']) && ($faq['category_en'] !== ($faq['category_id'] ?? ''))): ?>
                                        <div class="text-body fs-14">
                                            <?= htmlspecialchars($faq['category_en']) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(mb_strimwidth($faq['answer_id'] ?? '-', 0, 120, '...')) ?>
                                </td>

                                <td>
                                    <span class="default-badge <?= $statusClass ?>">
                                        <?= $statusLabel ?>
                                    </span>
                                </td>

                                <td>
                                    <div class="d-flex gap-2">
                                        <?php if (can('website_faq.edit')): ?>
                                            <a
                                                href="<?= url('website-faqs-edit') ?>?id=<?= $faq['id'] ?>"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                Edit
                                            </a>
                                        <?php endif; ?>

                                        <?php if (can('website_faq.delete')): ?>
                                            <a
                                                href="<?= url('website-faqs-delete') ?>?id=<?= $faq['id'] ?>"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Yakin ingin menghapus FAQ ini?')"
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
                            <td colspan="5" class="text-center text-body py-4">
                                Belum ada data FAQ.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php require __DIR__ . '/../../components/pagination.php'; ?>
    </div>
</div>
