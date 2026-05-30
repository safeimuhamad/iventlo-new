<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>
            <h3 class="mb-0">Website Articles</h3>
            <p class="text-body fs-14 mb-0">
                Kelola artikel, berita, dan konten SEO website Iventlo.
            </p>
        </div>

        <?php if (can('website_post.create')): ?>
            <a href="<?= url('website-posts-create') ?>" class="btn btn-primary text-white erp-btn">
                + Tambah Artikel
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
                        <th>Artikel</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Publish</th>
                        <th>Views</th>
                        <th>Order</th>
                        <th width="180">Action</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($posts)): ?>

                        <?php foreach ($posts as $post): ?>

                            <?php
                            $status = strtolower($post['status'] ?? 'draft');

                            $statusClass = in_array($status, ['published', 'publish'], true)
                                ? 'bg-success bg-opacity-10 text-success'
                                : 'bg-secondary bg-opacity-10 text-secondary';

                            $statusLabel = in_array($status, ['published', 'publish'], true)
                                ? 'Published'
                                : 'Draft';
                            ?>

                            <tr>

                                <td>
                                    <a
                                        href="<?= url('website-posts-edit') ?>?id=<?= $post['id'] ?>"
                                        class="fw-semibold text-primary text-decoration-none"
                                    >
                                        <?= htmlspecialchars($post['title_id'] ?? '-') ?>
                                    </a>

                                    <?php if (!empty($post['title_en'])): ?>
                                        <div class="text-body fs-14">
                                            <?= htmlspecialchars($post['title_en']) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <div><?= htmlspecialchars($post['slug_id'] ?? '-') ?></div>
                                    <div class="text-body fs-14"><?= htmlspecialchars($post['slug_en'] ?? '-') ?></div>
                                </td>

                                <td>
                                    <span class="default-badge <?= $statusClass ?>">
                                        <?= $statusLabel ?>
                                    </span>
                                </td>

                                <td>
                                    <?= !empty($post['published_at'])
                                        ? date('d M Y H:i', strtotime($post['published_at']))
                                        : '-' ?>
                                </td>

                                <td>
                                    <?= (int) ($post['views'] ?? 0) ?>
                                </td>

                                <td>
                                    <?= (int) ($post['sort_order'] ?? 0) ?>
                                </td>

                                <td>
                                    <div class="d-flex gap-2">

                                        <?php if (can('website_post.edit')): ?>
                                            <a
                                                href="<?= url('website-posts-edit') ?>?id=<?= $post['id'] ?>"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                Edit
                                            </a>
                                        <?php endif; ?>

                                        <?php if (can('website_post.delete')): ?>
                                            <a
                                                href="<?= url('website-posts-delete') ?>?id=<?= $post['id'] ?>"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Yakin ingin menghapus artikel ini?')"
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
                            <td colspan="7" class="text-center text-body py-4">
                                Belum ada data artikel.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

        <?php require __DIR__ . '/../../components/pagination.php'; ?>

    </div>

</div>
