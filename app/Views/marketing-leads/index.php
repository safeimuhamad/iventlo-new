<div class="card bg-white rounded-10 border border-white mb-4">
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

    <div>

        <h3 class="mb-0">
            Marketing Leads
        </h3>

        <p class="text-body fs-14 mb-0">
            Data prospect dan calon customer marketing
        </p>

    </div>

    <?php if (can('marketing_lead.create')): ?>

        <a
            href="<?= url('marketing-leads-create') ?>"
            class="btn btn-primary text-white erp-btn"
        >
            + Tambah Lead
        </a>

    <?php endif; ?>

</div>
    <div class="p-20 border-top">
        <form method="GET" class="row g-3 align-items-end">

            <input type="hidden" name="page" value="marketing-leads">

            <div class="col-md-4">
                <label class="form-label">Cari Lead</label>

                <input
                type="text"
                name="keyword"
                class="form-control erp-control erp-input"
                value="<?= htmlspecialchars($keyword ?? '') ?>"
                placeholder="No lead, company, PIC, HP, email"
                >
            </div>

            <div class="col-md-3">
                <label class="form-label">Status</label>

                <select
                name="status"
                class="form-select erp-control erp-select"
                >
                <option value="">Semua Status</option>

                <?php
                $filterStatuses = [
                    'new' => 'New',
                    'contacted' => 'Contacted',
                    'follow_up' => 'Follow Up',
                    'survey' => 'Survey',
                    'quotation' => 'Quotation',
                    'deal' => 'Deal',
                    'lost' => 'Lost',
                ];
                ?>

                <?php foreach ($filterStatuses as $key => $label): ?>
                    <option
                    value="<?= $key ?>"
                    <?= ($status ?? '') === $key ? 'selected' : '' ?>
                    >
                    <?= $label ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-3">
        <label class="form-label">Source</label>

        <select
        name="source"
        class="form-select erp-control erp-select"
        >
        <option value="">Semua Source</option>

        <?php
        $filterSources = [
            'Website',
            'WhatsApp',
            'Instagram',
            'Google Ads',
            'Referral',
            'Repeat Inquiry',
            'Other',
        ];
        ?>

        <?php foreach ($filterSources as $itemSource): ?>
            <option
            value="<?= $itemSource ?>"
            <?= ($source ?? '') === $itemSource ? 'selected' : '' ?>
            >
            <?= $itemSource ?>
        </option>
    <?php endforeach; ?>
</select>
</div>

<div class="col-md-2">
    <div class="d-flex gap-2 filter-action-group">

        <button
        type="submit"
        class="btn btn-primary text-white erp-btn w-100"
        >
        Filter
    </button>

    <a
    href="<?= url('marketing-leads') ?>"
    class="btn btn-light erp-btn"
    >
    Reset
</a>

</div>
</div>

</form>
</div>
<div class="default-table-area mx-minus-1">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>No Lead</th>
                    <th>Company</th>
                    <th>PIC</th>
                    <th>No. HP</th>
                    <th>Minat Layanan</th>
                    <th>Source</th>
                    <th class="text-end">Estimasi</th>
                    <th>Next Follow Up</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($items)): ?>
                    <?php foreach ($items as $item): ?>
                        <?php
                        $itemStatus = $item['status'] ?? 'new';

                        $statusLabel = [
                            'new' => 'New',
                            'contacted' => 'Contacted',
                            'follow_up' => 'Follow Up',
                            'survey' => 'Survey',
                            'quotation' => 'Quotation',
                            'deal' => 'Deal',
                            'lost' => 'Lost',
                        ];

                        $statusClass = [
                            'new' => 'bg-primary bg-opacity-10 text-primary',
                            'contacted' => 'bg-info bg-opacity-10 text-info',
                            'follow_up' => 'bg-warning bg-opacity-10 text-warning',
                            'survey' => 'bg-secondary bg-opacity-10 text-secondary',
                            'quotation' => 'bg-purple bg-opacity-10 text-purple',
                            'deal' => 'bg-success bg-opacity-10 text-success',
                            'lost' => 'bg-danger bg-opacity-10 text-danger',
                        ];
                        ?>

                        <tr>
                            <td>
                                <a href="<?= url('marketing-leads-show') ?>?id=<?= $item['id'] ?>" class="text-primary">
                                    <?= htmlspecialchars($item['lead_number'] ?? '-') ?>
                                </a>
                            </td>

                            <td><?= htmlspecialchars($item['company_name'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($item['pic_name'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($item['phone'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($item['service_interest'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($item['source'] ?? '-') ?></td>

                            <td class="text-end">
                                <?php if (($item['estimated_value'] ?? 0) > 0): ?>
                                    Rp <?= number_format((float) $item['estimated_value'], 0, ',', '.') ?>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($item['next_followup_date'])): ?>
                                    <?= htmlspecialchars($item['next_followup_date']) ?>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="default-badge <?= $statusClass[$itemStatus] ?? 'bg-secondary bg-opacity-10 text-secondary' ?>">
                                    <?= $statusLabel[$itemStatus] ?? $itemStatus ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center text-body">
                            Belum ada data lead.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center justify-content-sm-between align-items-center text-center flex-wrap gap-2 showing-wrap pt-15 p-20">
        <span class="fs-15">
            Showing
            <?= $totalData > 0 ? (($currentPage - 1) * $limit + 1) : 0 ?>
            to
            <?= min($currentPage * $limit, $totalData) ?>
            of
            <?= $totalData ?> entries
        </span>

        <?php
        $queryString =
        '&keyword=' . urlencode($keyword ?? '') .
        '&status=' . urlencode($status ?? '') .
        '&source=' . urlencode($source ?? '');
        ?>

        <?php if ($totalPages > 1): ?>

            <?php
            $startPage = max(1, $currentPage - 2);
            $endPage = min($totalPages, $currentPage + 2);
            ?>

            <nav class="custom-pagination">
                <ul class="pagination mb-0 justify-content-center">

                    <!-- Previous -->
                    <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                        <a
                        class="page-link icon"
                        href="<?= url('marketing-leads') ?>?p=<?= $currentPage - 1 ?><?= $queryString ?>"
                        >
                        <i class="material-symbols-outlined">west</i>
                    </a>
                </li>

                <!-- First Page -->
                <?php if ($startPage > 1): ?>
                    <li class="page-item">
                        <a
                        class="page-link"
                        href="<?= url('marketing-leads') ?>?p=1<?= $queryString ?>"
                        >
                        1
                    </a>
                </li>

                <?php if ($startPage > 2): ?>
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Page Numbers -->
            <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                <li class="page-item">
                    <a
                    class="page-link <?= $currentPage == $i ? 'active' : '' ?>"
                    href="<?= url('marketing-leads') ?>?p=<?= $i ?><?= $queryString ?>"
                    >
                    <?= $i ?>
                </a>
            </li>
        <?php endfor; ?>

        <!-- Last Page -->
        <?php if ($endPage < $totalPages): ?>

            <?php if ($endPage < $totalPages - 1): ?>
                <li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>
            <?php endif; ?>

            <li class="page-item">
                <a
                class="page-link"
                href="<?= url('marketing-leads') ?>?p=<?= $totalPages ?><?= $queryString ?>"
                >
                <?= $totalPages ?>
            </a>
        </li>

    <?php endif; ?>

    <!-- Next -->
    <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
        <a
        class="page-link icon"
        href="<?= url('marketing-leads') ?>?p=<?= $currentPage + 1 ?><?= $queryString ?>"
        >
        <i class="material-symbols-outlined">east</i>
    </a>
</li>

</ul>
</nav>

<?php endif; ?>
</div>
</div>
</div>