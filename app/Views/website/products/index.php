<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>
            <h3 class="mb-0">Website Products</h3>
            <p class="text-body fs-14 mb-0">
                Kelola produk, paket, atau penawaran event yang tampil di website Iventlo.
            </p>
        </div>

        <?php if (can('website_product.create')): ?>
            <a href="<?= url('website-products-create') ?>" class="btn btn-primary text-white erp-btn">
                + Tambah Produk
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
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th>Harga / Label</th>
                        <th>Status</th>
                        <th width="180">Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <?php
                            $status = strtolower($product['status'] ?? 'active');

                            $statusClass = $status === 'active'
                                ? 'bg-success bg-opacity-10 text-success'
                                : 'bg-secondary bg-opacity-10 text-secondary';

                            $statusLabel = $status === 'active' ? 'Active' : 'Inactive';
                            ?>

                            <tr>
                                <td>
                                    <a href="<?= url('website-products-edit') ?>?id=<?= $product['id'] ?>"
                                       class="fw-semibold text-primary text-decoration-none">
                                        <?= htmlspecialchars($product['title_id'] ?? '-') ?>
                                    </a>

                                    <?php if (!empty($product['title_en'])): ?>
                                        <div class="text-body fs-14">
                                            <?= htmlspecialchars($product['title_en']) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($product['category'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($product['price_label_id'] ?? '-') ?>
                                    <?php if (!empty($product['price_label_en'])): ?>
                                        <div class="text-body fs-14">
                                            <?= htmlspecialchars($product['price_label_en']) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <span class="default-badge <?= $statusClass ?>">
                                        <?= $statusLabel ?>
                                    </span>
                                </td>

                                <td>
                                    <div class="d-flex gap-2">
                                        <?php if (can('website_product.edit')): ?>
                                            <a href="<?= url('website-products-edit') ?>?id=<?= $product['id'] ?>"
                                               class="btn btn-sm btn-outline-primary">
                                                Edit
                                            </a>
                                        <?php endif; ?>

                                        <?php if (can('website_product.delete')): ?>
                                            <a href="<?= url('website-products-delete') ?>?id=<?= $product['id'] ?>"
                                               class="btn btn-sm btn-outline-danger"
                                               onclick="return confirm('Yakin ingin menghapus produk ini?')">
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
                                Belum ada data produk.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php require __DIR__ . '/../../components/pagination.php'; ?>
    </div>
</div>
