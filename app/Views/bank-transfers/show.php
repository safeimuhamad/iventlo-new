<?php
$amount = (float) ($transfer['amount'] ?? 0);
?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Detail Transfer Bank
            </h3>

            <p class="mb-0 text-body">
                Transfer #<?= htmlspecialchars($transfer['id'] ?? '-') ?>
            </p>
        </div>

        <div class="d-flex justify-content-between justify-content-md-end align-items-center flex-wrap gap-3">

            <a
                href="<?= url('bank-transfers') ?>"
                class="btn btn-light erp-btn"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Kembali
            </a>

            <a
                href="<?= url('bank-transfers-delete') ?>?id=<?= $transfer['id'] ?>"
                class="btn btn-outline-danger erp-btn"
                onclick="return confirm('Yakin ingin menghapus transfer ini? Saldo dan jurnal akan dikembalikan.')"
            >
                <i class="ri-delete-bin-line me-1"></i>
                Hapus
            </a>

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
                <span class="default-badge bg-success bg-opacity-10 text-success">
                    Posted
                </span>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">
                Tanggal Transfer
            </div>

            <div class="erp-detail-value">
                <?= !empty($transfer['transfer_date'])
                    ? date('d M Y', strtotime($transfer['transfer_date']))
                    : '-' ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">
                No Referensi
            </div>

            <div class="erp-detail-value">
                <?= htmlspecialchars($transfer['reference_no'] ?? '-') ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">
                Nominal
            </div>

            <div class="erp-detail-value text-primary">
                Rp <?= number_format($amount, 0, ',', '.') ?>
            </div>
        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title">
            Informasi Transfer
        </h4>
    </div>

    <div class="p-20">

        <div class="row g-4">

            <div class="col-md-3">
                <div class="erp-detail-label">
                    Tanggal Transfer
                </div>

                <div class="erp-detail-value">
                    <?= !empty($transfer['transfer_date'])
                        ? date('d M Y', strtotime($transfer['transfer_date']))
                        : '-' ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">
                    No Referensi
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($transfer['reference_no'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">
                    Nominal
                </div>

                <div class="erp-detail-value text-primary">
                    Rp <?= number_format($amount, 0, ',', '.') ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">
                    Status
                </div>

                <div class="erp-detail-value">
                    <span class="default-badge bg-success bg-opacity-10 text-success">
                        Posted
                    </span>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">
                    Kode Rekening Asal
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($transfer['from_account_code'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">
                    Rekening Asal
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($transfer['from_account_name'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">
                    Bank Asal
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($transfer['from_bank_name'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">
                    No Rekening Asal
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($transfer['from_account_number'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">
                    Kode Rekening Tujuan
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($transfer['to_account_code'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">
                    Rekening Tujuan
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($transfer['to_account_name'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">
                    Bank Tujuan
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($transfer['to_bank_name'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="erp-detail-label">
                    No Rekening Tujuan
                </div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($transfer['to_account_number'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-12">
                <div class="erp-detail-label">
                    Catatan
                </div>

                <div class="erp-detail-value">
                    <?= nl2br(htmlspecialchars($transfer['notes'] ?? '-')) ?>
                </div>
            </div>

        </div>

    </div>

</div>