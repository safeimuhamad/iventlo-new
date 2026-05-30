<?php
$status = strtolower($item['status'] ?? 'received');

$statusClass = match ($status) {
    'draft' => 'bg-secondary bg-opacity-10 text-secondary',
    'received' => 'bg-success bg-opacity-10 text-success',
    'cancelled' => 'bg-danger bg-opacity-10 text-danger',
    default => 'bg-secondary bg-opacity-10 text-secondary'
};

$totalQtyReceived = 0;

foreach (($items ?? []) as $row) {
    $totalQtyReceived += (float) ($row['qty_received'] ?? 0);
}
?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Detail Penerimaan Barang
            </h3>

            <p class="text-body mb-0">
                <?= htmlspecialchars($item['receipt_number'] ?? '-') ?>
            </p>
        </div>

        <div class="d-flex justify-content-between justify-content-md-end align-items-center flex-wrap gap-3">

            <a
                href="<?= url('goods-receipts') ?>"
                class="btn btn-light erp-btn"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Kembali
            </a>

            <a
                href="<?= url('purchase-orders-show') ?>?id=<?= $item['purchase_order_id'] ?>"
                class="btn btn-outline-primary erp-btn"
            >
                <i class="ri-file-list-3-line me-1"></i>
                Lihat PO
            </a>

            <?php if (can('goods_receipt.delete')): ?>
                <div class="dropdown">

                    <button
                        class="btn btn-primary text-white dropdown-toggle erp-btn"
                        type="button"
                        data-bs-toggle="dropdown"
                    >
                        <i class="ri-settings-3-line me-1"></i>
                        Actions
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end erp-dropdown-menu">

                        <li>
                            <a
                                href="<?= url('goods-receipts-delete') ?>?id=<?= $item['id'] ?>"
                                class="dropdown-item erp-dropdown-item"
                                onclick="return confirm('Hapus data penerimaan barang ini?')"
                            >
                                <div class="erp-dropdown-title text-danger">
                                    <i class="ri-delete-bin-line me-2"></i>
                                    Hapus Penerimaan Barang
                                </div>

                                <div class="erp-dropdown-desc">
                                    Hapus dokumen penerimaan barang
                                </div>
                            </a>
                        </li>

                    </ul>

                </div>
            <?php endif; ?>

        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="row g-4">

        <div class="col-md-3">
            <div class="erp-detail-label">
                Status
            </div>

            <div class="erp-detail-value">
                <span class="default-badge <?= $statusClass ?>">
                    <?= ucwords(str_replace('_', ' ', $status)) ?>
                </span>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">
                Vendor
            </div>

            <div class="erp-detail-value">
                <?= htmlspecialchars($item['vendor_name'] ?? '-') ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">
                Tanggal Terima
            </div>

            <div class="erp-detail-value">
                <?= !empty($item['receipt_date'])
                    ? date('d M Y', strtotime($item['receipt_date']))
                    : '-' ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">
                Total Qty Diterima
            </div>

            <div class="erp-detail-value text-primary">
                <?= number_format($totalQtyReceived, 2, ',', '.') ?>
            </div>
        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title">
            Informasi Penerimaan Barang
        </h4>
    </div>

    <div class="p-20">

        <div class="row g-4">

            <div class="col-md-3">
                <div class="erp-detail-label">
                    No. Receipt
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($item['receipt_number'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">
                    Tanggal Terima
                </div>

                <div class="erp-detail-value">
                    <?= !empty($item['receipt_date'])
                        ? date('d M Y', strtotime($item['receipt_date']))
                        : '-' ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">
                    Status
                </div>

                <div class="erp-detail-value">
                    <span class="default-badge <?= $statusClass ?>">
                        <?= ucwords(str_replace('_', ' ', $status)) ?>
                    </span>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">
                    Purchase Order
                </div>

                <div class="erp-detail-value">
                    <a
                        href="<?= url('purchase-orders-show') ?>?id=<?= $item['purchase_order_id'] ?>"
                        class="text-primary text-decoration-none fw-semibold"
                    >
                        <?= htmlspecialchars($item['po_number'] ?? '-') ?>
                    </a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">
                    Vendor
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($item['vendor_name'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">
                    PIC Vendor
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($item['vendor_pic'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">
                    Kontak Vendor
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($item['vendor_phone'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-12">
                <div class="erp-detail-label">
                    Catatan
                </div>

                <div class="erp-detail-value">
                    <?= nl2br(htmlspecialchars($item['notes'] ?? '-')) ?>
                </div>
            </div>

        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title">
            Item Diterima
        </h4>
    </div>

    <div class="default-table-area mx-minus-1">

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead>
                    <tr>
                        <th>Item</th>
                        <th class="text-end">Qty PO</th>
                        <th class="text-end">Qty Diterima</th>
                        <th>Unit</th>
                        <th>Catatan</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($items)): ?>

                        <?php foreach ($items as $row): ?>

                            <tr>

                                <td class="fw-semibold">
                                    <?= htmlspecialchars($row['item_name'] ?? '-') ?>
                                </td>

                                <td class="text-end">
                                    <?= number_format((float) ($row['qty_ordered'] ?? 0), 2, ',', '.') ?>
                                </td>

                                <td class="text-end fw-semibold text-success">
                                    <?= number_format((float) ($row['qty_received'] ?? 0), 2, ',', '.') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['unit_name'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['notes'] ?? '-') ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="5" class="text-center text-body py-4">
                                Belum ada item diterima.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>