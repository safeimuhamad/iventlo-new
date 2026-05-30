<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger mb-4">
        <?= $_SESSION['error']; ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>
<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Tambah Penerimaan Barang
            </h3>

            <p class="mb-0 text-body">
                Catat penerimaan barang berdasarkan purchase order yang sudah dibuat.
            </p>
        </div>

        <a
            href="<?= url('goods-receipts') ?>"
            class="btn btn-light erp-btn"
        >
            <i class="ri-arrow-left-line me-1"></i>
            Kembali
        </a>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title mb-0">
            Pilih Purchase Order
        </h4>
    </div>

    <div class="p-20">

        <form method="GET">

            <input type="hidden" name="page" value="goods-receipts-create">

            <div class="row g-4 align-items-end">

                <div class="col-md-6">
                    <label class="erp-detail-label">Purchase Order</label>

                    <select name="purchase_order_id" class="form-control" onchange="this.form.submit()">
                        <option value="">-- Pilih PO --</option>

                        <?php foreach ($purchaseOrders as $po): ?>
                            <option
                                value="<?= $po['id'] ?>"
                                <?= (int) ($purchaseOrderId ?? 0) === (int) $po['id'] ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars(($po['po_number'] ?? '-') . ' - ' . ($po['vendor_name'] ?? '-')) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>

        </form>

    </div>

</div>

<?php if (!empty($purchaseOrder)): ?>

    <form method="POST" action="<?= url('goods-receipts-store') ?>">

        <input type="hidden" name="purchase_order_id" value="<?= htmlspecialchars($purchaseOrder['id']) ?>">

        <div class="card bg-white rounded-10 border border-white mb-4">

            <div class="p-20 border-bottom">
                <h4 class="erp-detail-section-title mb-0">
                    Informasi Penerimaan
                </h4>
            </div>

            <div class="p-20">

                <div class="row g-4">

                    <div class="col-md-3">
                        <label class="erp-detail-label">No. Receipt</label>
                        <input
                            type="text"
                            name="receipt_number"
                            class="form-control"
                            value="<?= htmlspecialchars($receiptNumber ?? '') ?>"
                            readonly
                        >
                    </div>

                    <div class="col-md-3">
                        <label class="erp-detail-label">Tanggal Terima</label>
                        <input
                            type="date"
                            name="receipt_date"
                            class="form-control"
                            value="<?= date('Y-m-d') ?>"
                            required
                        >
                    </div>

                    <div class="col-md-3">
                        <label class="erp-detail-label">No. PO</label>
                        <input
                            type="text"
                            class="form-control"
                            value="<?= htmlspecialchars($purchaseOrder['po_number'] ?? '-') ?>"
                            readonly
                        >
                    </div>

                    <div class="col-md-3">
                        <label class="erp-detail-label">Vendor</label>
                        <input
                            type="text"
                            class="form-control"
                            value="<?= htmlspecialchars($purchaseOrder['vendor_name'] ?? '-') ?>"
                            readonly
                        >
                    </div>

                </div>

            </div>

        </div>

        <div class="card bg-white rounded-10 border border-white mb-4">

            <div class="p-20 border-bottom">
                <h4 class="erp-detail-section-title mb-0">
                    Item Diterima
                </h4>
            </div>

            <div class="p-20">

                <div id="items-wrapper">

                    <?php if (!empty($purchaseOrderItems)): ?>

                        <?php foreach ($purchaseOrderItems as $row): ?>

                            <div class="item-row border rounded-10 p-3 mb-3">

                                <input
                                    type="hidden"
                                    name="purchase_order_item_id[]"
                                    value="<?= htmlspecialchars($row['id']) ?>"
                                >

                                <input
                                    type="hidden"
                                    name="item_name[]"
                                    value="<?= htmlspecialchars($row['item_name'] ?? '') ?>"
                                >

                                <input
                                    type="hidden"
                                    name="qty_ordered[]"
                                    value="<?= htmlspecialchars($row['qty'] ?? 0) ?>"
                                >

                                <input
                                    type="hidden"
                                    name="unit_name[]"
                                    value="<?= htmlspecialchars($row['unit_name'] ?? 'unit') ?>"
                                >

                                <div class="row align-items-end g-3">

                                    <div class="col-md-4">
                                        <label class="erp-detail-label">Item</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            value="<?= htmlspecialchars($row['item_name'] ?? '-') ?>"
                                            readonly
                                        >
                                    </div>

                                    <div class="col-md-2">
                                        <label class="erp-detail-label">Qty PO</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            value="<?= number_format((float) ($row['qty'] ?? 0), 2, ',', '.') ?>"
                                            readonly
                                        >
                                    </div>

                                    <div class="col-md-2">
                                        <label class="erp-detail-label">Sudah Diterima</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            value="<?= number_format((float) ($row['received_qty'] ?? 0), 2, ',', '.') ?>"
                                            readonly
                                        >
                                    </div>

                                    <div class="col-md-2">
                                        <label class="erp-detail-label">Sisa</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            value="<?= number_format((float) ($row['remaining_qty'] ?? 0), 2, ',', '.') ?>"
                                            readonly
                                        >
                                    </div>

                                    <div class="col-md-2">
                                        <label class="erp-detail-label">Qty Diterima</label>
                                        <input
                                            type="number"
                                            name="qty_received[]"
                                            class="form-control qty-received"
                                            value="<?= htmlspecialchars($row['remaining_qty'] ?? 0) ?>"
                                            min="0"
                                            max="<?= htmlspecialchars($row['remaining_qty'] ?? 0) ?>"
                                            step="0.01"
                                        >
                                    </div>

                                    <div class="col-md-10">
                                        <label class="erp-detail-label">Catatan Item</label>
                                        <input
                                            type="text"
                                            name="item_notes[]"
                                            class="form-control"
                                        >
                                    </div>

                                    <div class="col-md-2">
                                        <label class="erp-detail-label">Unit</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            value="<?= htmlspecialchars($row['unit_name'] ?? 'unit') ?>"
                                            readonly
                                        >
                                    </div>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <div class="alert alert-info mb-0">
                            Semua item pada PO ini sudah diterima.
                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>

        <div class="row g-4 mb-4">

            <div class="col-lg-8">

                <div class="card bg-white rounded-10 border border-white h-100">

                    <div class="p-20 border-bottom">
                        <h4 class="erp-detail-section-title mb-0">
                            Catatan
                        </h4>
                    </div>

                    <div class="p-20">
                        <textarea
                            name="notes"
                            class="form-control"
                            rows="8"
                        ></textarea>
                    </div>

                </div>

            </div>

            <div class="col-lg-4">

                <div class="card bg-white rounded-10 border border-white h-100">

                    <div class="p-20 border-bottom">
                        <h4 class="erp-detail-section-title mb-0">
                            Ringkasan
                        </h4>
                    </div>

                    <div class="p-20">

                        <div class="d-flex justify-content-between mb-2">
                            <span>No. PO</span>
                            <strong><?= htmlspecialchars($purchaseOrder['po_number'] ?? '-') ?></strong>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Vendor</span>
                            <strong><?= htmlspecialchars($purchaseOrder['vendor_name'] ?? '-') ?></strong>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <span>Total Item</span>
                            <strong><?= count($purchaseOrderItems ?? []) ?></strong>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="card bg-white rounded-10 border border-white p-20">

            <div class="d-flex justify-content-end flex-wrap gap-3">

                <a
                    href="<?= url('goods-receipts') ?>"
                    class="btn btn-light erp-btn"
                >
                    <i class="ri-close-line me-1"></i>
                    Batal
                </a>

                <?php if (!empty($purchaseOrderItems)): ?>
                    <button
                        type="submit"
                        class="btn btn-primary text-white erp-btn"
                    >
                        <i class="ri-save-line me-1"></i>
                        Simpan
                    </button>
                <?php endif; ?>

            </div>

        </div>

    </form>

<?php else: ?>

    <div class="card bg-white rounded-10 border border-white p-20">
        <div class="alert alert-info mb-0">
            Silakan pilih purchase order terlebih dahulu.
        </div>
    </div>

<?php endif; ?>