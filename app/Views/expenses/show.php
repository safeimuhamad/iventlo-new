<?php
$total = 0;

foreach ($items as $item) {
    $total += (float) ($item['amount'] ?? 0);
}
?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Detail Pengeluaran
            </h3>

            <p class="mb-0 text-body">
                <?= htmlspecialchars($expense['expense_no'] ?? '-') ?>
            </p>
        </div>

        <div class="d-flex justify-content-between justify-content-md-end align-items-center flex-wrap gap-3">

            <a
                href="<?= url('expenses') ?>"
                class="btn btn-light erp-btn"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Kembali
            </a>

            <a
                href="<?= url('expenses-edit') ?>?id=<?= $expense['id'] ?>"
                class="btn btn-outline-primary erp-btn"
            >
                <i class="ri-edit-line me-1"></i>
                Edit
            </a>

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
                            href="<?= url('expenses-delete') ?>?id=<?= $expense['id'] ?>"
                            class="dropdown-item erp-dropdown-item"
                            onclick="return confirm('Yakin ingin menghapus pengeluaran ini? Saldo bank akan dikembalikan.')"
                        >
                            <div class="erp-dropdown-title text-danger">
                                <i class="ri-delete-bin-line me-2"></i>
                                Hapus Pengeluaran
                            </div>

                            <div class="erp-dropdown-desc">
                                Hapus transaksi pengeluaran dan rollback saldo
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

            <div class="erp-detail-label">
                Tanggal
            </div>

            <div class="erp-detail-value">
                <?= !empty($expense['expense_date'])
                    ? date('d M Y', strtotime($expense['expense_date']))
                    : '-' ?>
            </div>

        </div>

        <div class="col-md-3">

            <div class="erp-detail-label">
                Metode Pembayaran
            </div>

            <div class="erp-detail-value">
                <?= htmlspecialchars($expense['payment_method'] ?? '-') ?>
            </div>

        </div>

        <div class="col-md-3">

            <div class="erp-detail-label">
                No Referensi
            </div>

            <div class="erp-detail-value">
                <?= htmlspecialchars($expense['reference_no'] ?? '-') ?>
            </div>

        </div>

        <div class="col-md-3">

            <div class="erp-detail-label">
                Total Pengeluaran
            </div>

            <div class="erp-detail-value text-danger">
                Rp <?= number_format((float) ($expense['amount'] ?? 0), 0, ',', '.') ?>
            </div>

        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">

        <h4 class="erp-detail-section-title">
            Informasi Pengeluaran
        </h4>

    </div>

    <div class="p-20">

        <div class="row g-4">

            <div class="col-md-3">

                <div class="erp-detail-label">
                    Tanggal
                </div>

                <div class="erp-detail-value">
                    <?= !empty($expense['expense_date'])
                        ? date('d M Y', strtotime($expense['expense_date']))
                        : '-' ?>
                </div>

            </div>

            <div class="col-md-3">

                <div class="erp-detail-label">
                    Metode Pembayaran
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($expense['payment_method'] ?? '-') ?>
                </div>

            </div>

            <div class="col-md-3">

                <div class="erp-detail-label">
                    No Referensi
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($expense['reference_no'] ?? '-') ?>
                </div>

            </div>

            <div class="col-md-3">

                <div class="erp-detail-label">
                    Total
                </div>

                <div class="erp-detail-value text-danger">
                    Rp <?= number_format((float) ($expense['amount'] ?? 0), 0, ',', '.') ?>
                </div>

            </div>

            <div class="col-md-6">

                <div class="erp-detail-label">
                    Rekening Sumber
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($expense['account_code'] ?? '-') ?>
                    -
                    <?= htmlspecialchars($expense['account_name'] ?? '-') ?>
                </div>

                <div class="text-muted small mt-1">
                    <?= htmlspecialchars($expense['bank_name'] ?? '-') ?>
                    <?= htmlspecialchars($expense['account_number'] ?? '') ?>
                </div>

            </div>

            <div class="col-md-3">

                <div class="erp-detail-label">
                    Penerima
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($expense['beneficiary'] ?? '-') ?>
                </div>

            </div>

            <div class="col-md-3">

                <div class="erp-detail-label">
                    Nominal
                </div>

                <div class="erp-detail-value text-danger">
                    Rp <?= number_format((float) ($expense['amount'] ?? 0), 0, ',', '.') ?>
                </div>

            </div>

            <div class="col-md-12">

                <div class="erp-detail-label">
                    Alamat / Catatan Penerima
                </div>

                <div class="erp-detail-value">
                    <?= nl2br(htmlspecialchars($expense['billing_address'] ?? '-')) ?>
                </div>

            </div>

            <div class="col-md-12">

                <div class="erp-detail-label">
                    Catatan Umum
                </div>

                <div class="erp-detail-value">
                    <?= nl2br(htmlspecialchars($expense['description'] ?? '-')) ?>
                </div>

            </div>

        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">

        <h4 class="erp-detail-section-title">
            Detail Biaya
        </h4>

    </div>

    <div class="default-table-area mx-minus-1">

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead>
                    <tr>
                        <th>Akun Biaya</th>
                        <th>Deskripsi</th>
                        <th class="text-end">Nominal</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($items)): ?>

                        <?php foreach ($items as $item): ?>

                            <tr>

                                <td>
                                    <?= htmlspecialchars($item['account_code'] ?? '-') ?>
                                    -
                                    <?= htmlspecialchars($item['account_name'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($item['description'] ?? '-') ?>
                                </td>

                                <td class="text-end fw-semibold text-danger">
                                    Rp <?= number_format((float) ($item['amount'] ?? 0), 0, ',', '.') ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                        <tr class="table-light">

                            <td colspan="2" class="text-end fw-bold">
                                TOTAL
                            </td>

                            <td class="text-end fw-bold text-danger">
                                Rp <?= number_format($total, 0, ',', '.') ?>
                            </td>

                        </tr>

                    <?php else: ?>

                        <tr>
                            <td colspan="3" class="text-center text-body py-4">
                                Belum ada detail biaya.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>