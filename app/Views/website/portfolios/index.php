<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>
            <h3 class="mb-0">Website Portfolio</h3>
            <p class="text-body fs-14 mb-0">
                Kelola portfolio event yang tampil di website Iventlo.
            </p>
        </div>

        <?php if (can('website_portfolio.create')): ?>
            <a href="<?= url('website-portfolios-create') ?>" class="btn btn-primary text-white erp-btn">
                + Tambah Portfolio
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
                        <th>Portfolio</th>
                        <th>Client</th>
                        <th>Kategori</th>
                        <th>Tanggal Event</th>
                        <th>Status</th>
                        <th width="180">Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($portfolios)): ?>
                        <?php foreach ($portfolios as $portfolio): ?>
                            <?php
                            $status = strtolower($portfolio['status'] ?? 'active');

                            $statusClass = in_array($status, ['active', 'publish'], true)
                                ? 'bg-success bg-opacity-10 text-success'
                                : 'bg-secondary bg-opacity-10 text-secondary';

                            $statusLabel = in_array($status, ['active', 'publish'], true) ? 'Active' : 'Inactive';
                            ?>

                            <tr>
                                <td>
                                    <a href="<?= url('website-portfolios-edit') ?>?id=<?= $portfolio['id'] ?>"
                                       class="fw-semibold text-primary text-decoration-none">
                                        <?= htmlspecialchars($portfolio['title_id'] ?? '-') ?>
                                    </a>

                                    <?php if (!empty($portfolio['title_en'])): ?>
                                        <div class="text-body fs-14">
                                            <?= htmlspecialchars($portfolio['title_en']) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($portfolio['client_name'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($portfolio['category_id'] ?? '-') ?>
                                    <?php if (!empty($portfolio['category_en'])): ?>
                                        <div class="text-body fs-14">
                                            <?= htmlspecialchars($portfolio['category_en']) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?= !empty($portfolio['event_date'])
                                        ? date('d M Y', strtotime($portfolio['event_date']))
                                        : '-' ?>
                                </td>

                                <td>
                                    <span class="default-badge <?= $statusClass ?>">
                                        <?= $statusLabel ?>
                                    </span>
                                </td>

                                <td>
                                    <div class="d-flex gap-2">
                                        <?php if (can('website_portfolio.edit')): ?>
                                            <a href="<?= url('website-portfolios-edit') ?>?id=<?= $portfolio['id'] ?>"
                                               class="btn btn-sm btn-outline-primary">
                                                Edit
                                            </a>
                                        <?php endif; ?>

                                        <?php if (can('website_portfolio.delete')): ?>
                                            <a href="<?= url('website-portfolios-delete') ?>?id=<?= $portfolio['id'] ?>"
                                               class="btn btn-sm btn-outline-danger"
                                               onclick="return confirm('Yakin ingin menghapus portfolio ini?')">
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
                                Belum ada data portfolio.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php require __DIR__ . '/../../components/pagination.php'; ?>
    </div>
</div>
