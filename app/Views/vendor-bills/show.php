<?php
$total = 0;

foreach ($items as $item) {
    $total += (float) ($item['amount'] ?? 0);
}

$status = $bill['status_payment'] ?? 'unpaid';

$statusClass = match ($status) {
    'paid' => 'bg-success bg-opacity-10 text-success',
    'partial paid' => 'bg-warning bg-opacity-10 text-warning',
    default => 'bg-danger bg-opacity-10 text-danger'
};
?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Detail Hutang Vendor
            </h3>

            <p class="mb-0 text-body">
                <?= htmlspecialchars($bill['bill_no'] ?? '-') ?>
            </p>
        </div>

        <div class="d-flex justify-content-between justify-content-md-end align-items-center flex-wrap gap-3">

            <a
                href="<?= url('vendor-bills') ?>"
                class="btn btn-light erp-btn"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Kembali
            </a>

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

                    <?php if (($bill['status_payment'] ?? 'unpaid') !== 'paid'): ?>
                        <li>
                            <a
                                href="<?= url('vendor-bill-payments-create') ?>?bill_id=<?= $bill['id'] ?>"
                                class="dropdown-item erp-dropdown-item"
                            >
                                <div class="erp-dropdown-title text-success">
                                    <i class="ri-money-dollar-circle-line me-2"></i>
                                    Bayar Hutang
                                </div>

                                <div class="erp-dropdown-desc">
                                    Input pembayaran vendor bill
                                </div>
                            </a>
                        </li>
                    <?php endif; ?>

                    <li>
                        <a
                            href="<?= url('vendor-bills-delete') ?>?id=<?= $bill['id'] ?>"
                            class="dropdown-item erp-dropdown-item"
                            onclick="return confirm('Yakin ingin menghapus vendor bill ini?')"
                        >
                            <div class="erp-dropdown-title text-danger">
                                <i class="ri-delete-bin-line me-2"></i>
                                Hapus Vendor Bill
                            </div>

                            <div class="erp-dropdown-desc">
                                Hapus dokumen hutang vendor
                            </div>
                        </a>
                    </li>

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
                    <?= htmlspecialchars(ucwords($status)) ?>
                </span>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">Vendor</div>

            <div class="erp-detail-value">
                <?= htmlspecialchars($bill['vendor_name'] ?? '-') ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">Grand Total</div>

            <div class="erp-detail-value text-primary">
                Rp <?= number_format((float) ($bill['grand_total'] ?? 0), 0, ',', '.') ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">Sisa Hutang</div>

            <div class="erp-detail-value text-danger">
                Rp <?= number_format((float) ($bill['remaining_amount'] ?? 0), 0, ',', '.') ?>
            </div>
        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title">
            Informasi Bill
        </h4>
    </div>

    <div class="p-20">

        <div class="row g-4">

            <div class="col-md-3">
                <div class="erp-detail-label">Vendor</div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($bill['vendor_name'] ?? '-') ?>
                </div>
            </div>

            <?php if (!empty($bill['purchase_order_id'])): ?>
                <div class="col-md-3">
                    <div class="erp-detail-label">Purchase Order</div>

                    <div class="erp-detail-value">
                        <a
                            href="<?= url('purchase-orders-show') ?>?id=<?= $bill['purchase_order_id'] ?>"
                            class="text-primary text-decoration-none fw-semibold"
                        >
                            <?= htmlspecialchars($bill['po_number'] ?? '-') ?>
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <div class="col-md-3">
                <div class="erp-detail-label">Tanggal Bill</div>

                <div class="erp-detail-value">
                    <?= !empty($bill['bill_date'])
                        ? date('d M Y', strtotime($bill['bill_date']))
                        : '-' ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">Jatuh Tempo</div>

                <div class="erp-detail-value">
                    <?= !empty($bill['due_date'])
                        ? date('d M Y', strtotime($bill['due_date']))
                        : '-' ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">Status</div>

                <div class="erp-detail-value">
                    <span class="default-badge <?= $statusClass ?>">
                        <?= htmlspecialchars(ucwords($status)) ?>
                    </span>
                </div>
            </div>

            <div class="col-md-12">
                <div class="erp-detail-label">Catatan</div>

                <div class="erp-detail-value">
                    <?= nl2br(htmlspecialchars($bill['notes'] ?? '-')) ?>
                </div>
            </div>

        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title">
            Detail Tagihan
        </h4>
    </div>

    <div class="default-table-area mx-minus-1">

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead>
                    <tr>
                        <th>Kode Akun</th>
                        <th>Nama Akun</th>
                        <th style="min-width:220px;">Deskripsi</th>
                        <th class="text-end">Nominal</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($items)): ?>

                        <?php foreach ($items as $item): ?>

                            <tr>
                                <td>
                                    <?= htmlspecialchars($item['account_code'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($item['account_name'] ?? '-') ?>
                                </td>

                                <td class="text-wrap" style="min-width:220px; max-width:420px;">
                                    <?= htmlspecialchars($item['description'] ?? '-') ?>
                                </td>

                                <td class="text-end fw-semibold">
                                    Rp <?= number_format((float) ($item['amount'] ?? 0), 0, ',', '.') ?>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                        <?php if ((float) ($bill['tax_amount'] ?? 0) > 0): ?>
                            <tr>
                                <td colspan="3" class="text-end fw-semibold border-top">
                                    PPN Masukan
                                </td>

                                <td class="text-end fw-semibold border-top">
                                    Rp <?= number_format((float) ($bill['tax_amount'] ?? 0), 0, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <tr>
                            <td colspan="3" class="text-end fw-bold">
                                Grand Total
                            </td>

                            <td class="text-end fw-bold text-primary">
                                Rp <?= number_format((float) ($bill['grand_total'] ?? 0), 0, ',', '.') ?>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="3" class="text-end text-body">
                                Sudah Dibayar
                            </td>

                            <td class="text-end fw-semibold text-success">
                                Rp <?= number_format((float) ($bill['paid_amount'] ?? 0), 0, ',', '.') ?>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="3" class="text-end fw-bold">
                                Sisa Hutang
                            </td>

                            <td class="text-end fw-bold text-danger">
                                Rp <?= number_format((float) ($bill['remaining_amount'] ?? 0), 0, ',', '.') ?>
                            </td>
                        </tr>

                    <?php else: ?>

                        <tr>
                            <td colspan="4" class="text-center text-body py-4">
                                Belum ada detail tagihan.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title">
            Riwayat Pembayaran
        </h4>
    </div>

    <div class="default-table-area mx-minus-1">

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kode Akun</th>
                        <th>Nama Akun</th>
                        <th>Bank</th>
                        <th>No Rekening</th>
                        <th>Metode</th>
                        <th>Referensi</th>
                        <th style="min-width:220px;">Catatan</th>
                        <th class="text-end">Nominal</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($payments)): ?>

                        <?php foreach ($payments as $payment): ?>

                            <tr>
                                <td>
                                    <?= !empty($payment['payment_date'])
                                        ? date('d M Y', strtotime($payment['payment_date']))
                                        : '-' ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($payment['account_code'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($payment['account_name'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($payment['bank_name'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($payment['account_number'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($payment['payment_method'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($payment['reference_no'] ?? '-') ?>
                                </td>

                                <td class="text-wrap" style="min-width:220px; max-width:320px;">
                                    <?= htmlspecialchars($payment['notes'] ?? '-') ?>
                                </td>

                                <td class="text-end fw-semibold text-danger">
                                    Rp <?= number_format((float) ($payment['payment_amount'] ?? 0), 0, ',', '.') ?>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="9" class="text-center text-body py-4">
                                Belum ada pembayaran hutang.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>