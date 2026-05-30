<?php
$status = strtolower($item['status'] ?? 'draft');

$statusClass = match ($status) {
    'draft' => 'bg-secondary bg-opacity-10 text-secondary',
    'approved' => 'bg-info bg-opacity-10 text-info',
    'sent' => 'bg-primary bg-opacity-10 text-primary',
    'partial_received' => 'bg-warning bg-opacity-10 text-warning',
    'completed' => 'bg-success bg-opacity-10 text-success',
    'cancelled' => 'bg-danger bg-opacity-10 text-danger',
    default => 'bg-secondary bg-opacity-10 text-secondary'
};
?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Detail Purchase Order
            </h3>

            <p class="text-body mb-0">
                <?= htmlspecialchars($item['po_number'] ?? '-') ?>
            </p>
        </div>

        <div class="d-flex justify-content-between justify-content-md-end align-items-center flex-wrap gap-3">

            <a
                href="<?= url('purchase-orders') ?>"
                class="btn btn-light erp-btn"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Kembali
            </a>

            <?php if (can('purchase_order.edit') && in_array(($item['status'] ?? ''), ['draft', 'rejected'])): ?>
                <a
                    href="<?= url('purchase-orders-edit') ?>?id=<?= $item['id'] ?>"
                    class="btn btn-outline-primary erp-btn"
                >
                    <i class="ri-edit-line me-1"></i>
                    Edit
                </a>
            <?php endif; ?>

            <?php if (can('purchase_order.print')): ?>
                <a
                    href="<?= url('purchase-orders-print') ?>?id=<?= $item['id'] ?>"
                    target="_blank"
                    class="btn btn-outline-primary erp-btn"
                >
                    <i class="ri-printer-line me-1"></i>
                    Print
                </a>
            <?php endif; ?>

            <div class="dropdown">

                <button
                    class="btn btn-primary text-white dropdown-toggle erp-btn"
                    type="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                >
                    <i class="ri-settings-3-line me-1"></i>
                    Actions
                </button>

                <ul class="dropdown-menu dropdown-menu-end erp-dropdown-menu">

                    <?php if (can('purchase_order.approve') && ($item['status'] ?? '') === 'draft'): ?>
                        <li>
                            <a
                                href="<?= url('purchase-orders-approve') ?>?id=<?= $item['id'] ?>"
                                class="dropdown-item erp-dropdown-item"
                                onclick="return confirm('Approve purchase order ini?')"
                            >
                                <div class="erp-dropdown-title text-success">
                                    <i class="ri-check-line me-2"></i>
                                    Approve PO
                                </div>

                                <div class="erp-dropdown-desc">
                                    Setujui purchase order
                                </div>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (can('purchase_order.edit') && ($item['status'] ?? '') === 'approved'): ?>
                        <li>
                            <a
                                href="<?= url('purchase-orders-sent') ?>?id=<?= $item['id'] ?>"
                                class="dropdown-item erp-dropdown-item"
                                onclick="return confirm('Tandai PO sudah dikirim ke vendor?')"
                            >
                                <div class="erp-dropdown-title text-primary">
                                    <i class="ri-send-plane-line me-2"></i>
                                    Mark Sent
                                </div>

                                <div class="erp-dropdown-desc">
                                    Tandai PO sudah dikirim ke vendor
                                </div>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (
                        can('goods_receipt.create') &&
                        in_array(($item['status'] ?? ''), ['approved', 'sent', 'partial_received'])
                    ): ?>
                        <li>
                            <a
                                href="<?= url('goods-receipts-create') ?>?purchase_order_id=<?= $item['id'] ?>"
                                class="dropdown-item erp-dropdown-item"
                            >
                                <div class="erp-dropdown-title text-success">
                                    <i class="ri-inbox-archive-line me-2"></i>
                                    Receive Barang
                                </div>

                                <div class="erp-dropdown-desc">
                                    Buat penerimaan barang dari PO
                                </div>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (
                        can('vendor_bill.create') &&
                        empty($hasVendorBill) &&
                        in_array(($item['status'] ?? ''), ['approved', 'sent', 'partial_received', 'completed'])
                    ): ?>
                        <li>
                            <a
                                href="<?= url('purchase-orders-create-bill') ?>?id=<?= $item['id'] ?>"
                                class="dropdown-item erp-dropdown-item"
                                onclick="return confirm('Buat vendor bill dari PO ini?')"
                            >
                                <div class="erp-dropdown-title text-success">
                                    <i class="ri-bill-line me-2"></i>
                                    Buat Vendor Bill
                                </div>

                                <div class="erp-dropdown-desc">
                                    Generate tagihan vendor dari PO
                                </div>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (!empty($hasVendorBill)): ?>
                        <li>
                            <a
                                href="<?= url('vendor-bills-show') ?>?id=<?= $hasVendorBill['id'] ?>"
                                class="dropdown-item erp-dropdown-item"
                            >
                                <div class="erp-dropdown-title text-info">
                                    <i class="ri-file-list-3-line me-2"></i>
                                    Lihat Vendor Bill
                                </div>

                                <div class="erp-dropdown-desc">
                                    Buka tagihan vendor terkait PO
                                </div>
                            </a>
                        </li>
                    <?php endif; ?>

                </ul>

            </div>

        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="row g-4">

        <div class="col-md-3">
            <div class="erp-detail-label">Status</div>

            <div class="erp-detail-value">
                <span class="default-badge <?= $statusClass ?>">
                    <?= htmlspecialchars(ucwords(str_replace('_', ' ', $status))) ?>
                </span>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">Vendor</div>

            <div class="erp-detail-value">
                <?= htmlspecialchars($item['vendor_name'] ?? '-') ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">Tanggal PO</div>

            <div class="erp-detail-value">
                <?= !empty($item['po_date'])
                    ? date('d M Y', strtotime($item['po_date']))
                    : '-' ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">Grand Total</div>

            <div class="erp-detail-value text-primary">
                Rp <?= number_format((float) ($item['grand_total'] ?? 0), 0, ',', '.') ?>
            </div>
        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title">
            Informasi Purchase Order
        </h4>
    </div>

    <div class="p-20">

        <div class="row g-4">

            <div class="col-md-3">
                <div class="erp-detail-label">No. PO</div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($item['po_number'] ?? '-') ?>
                </div>
            </div>

            <?php if (!empty($item['purchase_request_id'])): ?>
                <div class="col-md-3">
                    <div class="erp-detail-label">Purchase Request</div>

                    <div class="erp-detail-value">
                        <a
                            href="<?= url('purchase-requests-show') ?>?id=<?= $item['purchase_request_id'] ?>"
                            class="text-primary text-decoration-none fw-semibold"
                        >
                            <?= htmlspecialchars($item['pr_number'] ?? '-') ?>
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <div class="col-md-3">
                <div class="erp-detail-label">Tanggal PO</div>

                <div class="erp-detail-value">
                    <?= !empty($item['po_date'])
                        ? date('d M Y', strtotime($item['po_date']))
                        : '-' ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">Estimasi Datang</div>

                <div class="erp-detail-value">
                    <?= !empty($item['expected_date'])
                        ? date('d M Y', strtotime($item['expected_date']))
                        : '-' ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">Status</div>

                <div class="erp-detail-value">
                    <span class="default-badge <?= $statusClass ?>">
                        <?= htmlspecialchars(ucwords(str_replace('_', ' ', $status))) ?>
                    </span>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">Vendor</div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($item['vendor_name'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">PIC Vendor</div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($item['vendor_pic'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="erp-detail-label">Kontak Vendor</div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($item['vendor_phone'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-12">
                <div class="erp-detail-label">Alamat Vendor</div>

                <div class="erp-detail-value">
                    <?= nl2br(htmlspecialchars($item['vendor_address'] ?? '-')) ?>
                </div>
            </div>

            <div class="col-md-12">
                <div class="erp-detail-label">Catatan</div>

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
            Item Pembelian
        </h4>
    </div>

    <div class="default-table-area mx-minus-1">

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead>
                    <tr>
                        <th>Item</th>
                        <th style="min-width:220px;">Deskripsi</th>
                        <th class="text-end">Qty</th>
                        <th>Unit</th>
                        <th class="text-end">Harga</th>
                        <th class="text-end">Subtotal</th>
                        <th class="text-end">Received</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($items)): ?>

                        <?php foreach ($items as $row): ?>

                            <tr>
                                <td class="fw-semibold">
                                    <?= htmlspecialchars($row['item_name'] ?? '-') ?>
                                </td>

                                <td class="text-wrap" style="min-width:220px; max-width:420px;">
                                    <?= htmlspecialchars($row['description'] ?? '-') ?>
                                </td>

                                <td class="text-end">
                                    <?= number_format((float) ($row['qty'] ?? 0), 2, ',', '.') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['unit_name'] ?? '-') ?>
                                </td>

                                <td class="text-end">
                                    Rp <?= number_format((float) ($row['unit_price'] ?? 0), 0, ',', '.') ?>
                                </td>

                                <td class="text-end fw-semibold">
                                    Rp <?= number_format((float) ($row['subtotal'] ?? 0), 0, ',', '.') ?>
                                </td>

                                <td class="text-end fw-semibold">
                                    <?= number_format((float) ($row['received_qty'] ?? 0), 2, ',', '.') ?>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                        <tr>
                            <td colspan="5" class="text-end fw-semibold border-top">
                                Subtotal
                            </td>

                            <td colspan="2" class="text-end fw-semibold border-top">
                                Rp <?= number_format((float) ($item['subtotal'] ?? 0), 0, ',', '.') ?>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="5" class="text-end text-body">
                                Pajak
                            </td>

                            <td colspan="2" class="text-end fw-semibold">
                                Rp <?= number_format((float) ($item['tax_amount'] ?? 0), 0, ',', '.') ?>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="5" class="text-end fw-bold">
                                Grand Total
                            </td>

                            <td colspan="2" class="text-end fw-bold text-primary">
                                Rp <?= number_format((float) ($item['grand_total'] ?? 0), 0, ',', '.') ?>
                            </td>
                        </tr>

                    <?php else: ?>

                        <tr>
                            <td colspan="7" class="text-center text-body py-4">
                                Belum ada item pembelian.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>
