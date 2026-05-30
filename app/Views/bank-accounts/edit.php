<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger mb-4">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Edit Rekening
            </h3>

            <p class="mb-0 text-body">
                Perbarui informasi rekening bank perusahaan yang digunakan untuk transaksi penerimaan, pembayaran, transfer, dan rekonsiliasi keuangan.
            </p>
        </div>

        <a
            href="<?= url('bank-accounts') ?>"
            class="btn btn-light erp-btn"
        >
            <i class="ri-arrow-left-line me-1"></i>
            Kembali
        </a>

    </div>

</div>

<form method="POST" action="<?= url('bank-accounts-update') ?>">

    <input
        type="hidden"
        name="id"
        value="<?= htmlspecialchars($bank['id']) ?>"
    >

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Informasi Rekening
            </h4>
        </div>

        <div class="p-20">

            <div class="row g-4">

                <div class="col-md-3">
                    <label class="erp-detail-label">
                        Kode Akun <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="account_code"
                        class="form-control"
                        value="<?= htmlspecialchars($bank['account_code'] ?? '') ?>"
                        required
                    >
                </div>

                <div class="col-md-5">
                    <label class="erp-detail-label">
                        Nama Akun <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="account_name"
                        class="form-control"
                        value="<?= htmlspecialchars($bank['account_name'] ?? '') ?>"
                        required
                    >
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">
                        Bank
                    </label>

                    <input
                        type="text"
                        name="bank_name"
                        class="form-control"
                        value="<?= htmlspecialchars($bank['bank_name'] ?? '') ?>"
                    >
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">
                        No Rekening
                    </label>

                    <input
                        type="text"
                        name="account_number"
                        class="form-control"
                        value="<?= htmlspecialchars($bank['account_number'] ?? '') ?>"
                    >
                </div>

                <div class="col-md-5">
                    <label class="erp-detail-label">
                        Pemilik Rekening
                    </label>

                    <input
                        type="text"
                        name="account_holder"
                        class="form-control"
                        value="<?= htmlspecialchars($bank['account_holder'] ?? '') ?>"
                    >
                </div>

                <div class="col-md-3">
                    <label class="erp-detail-label">
                        Saldo Saat Ini
                    </label>

                    <input
                        type="number"
                        name="current_balance"
                        class="form-control text-end"
                        value="<?= htmlspecialchars($bank['current_balance'] ?? 0) ?>"
                    >
                </div>

                <div class="col-md-12">
                    <label class="erp-detail-label">
                        COA Akun
                    </label>

                    <select name="coa_id" class="form-control">

                        <option value="">
                            -- Pilih COA --
                        </option>

                        <?php foreach ($accounts as $coa): ?>
                            <option
                                value="<?= $coa['id'] ?>"
                                <?= ($bank['coa_id'] ?? '') == $coa['id'] ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($coa['account_code']) ?>
                                -
                                <?= htmlspecialchars($coa['account_name']) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

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

                    <div class="text-body">
                        Perubahan rekening bank akan mempengaruhi transaksi pembayaran, penerimaan customer, transfer antar rekening, dan laporan kas & bank.
                    </div>

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
                        <span>Bank</span>
                        <strong>
                            <?= htmlspecialchars($bank['bank_name'] ?? '-') ?>
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>No Rekening</span>
                        <strong>
                            <?= htmlspecialchars($bank['account_number'] ?? '-') ?>
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Saldo Saat Ini</span>
                        <strong>
                            Rp <?= number_format((float)($bank['current_balance'] ?? 0), 0, ',', '.') ?>
                        </strong>
                    </div>

                    <hr>

                    <div class="text-body">
                        Pastikan rekening dan COA sesuai agar jurnal transaksi kas dan bank tercatat dengan benar.
                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white p-20">

        <div class="d-flex justify-content-end flex-wrap gap-3">

            <a
                href="<?= url('bank-accounts') ?>"
                class="btn btn-light erp-btn"
            >
                <i class="ri-close-line me-1"></i>
                Batal
            </a>

            <button
                type="submit"
                class="btn btn-primary text-white erp-btn"
            >
                <i class="ri-save-line me-1"></i>
                Update
            </button>

        </div>

    </div>

</form>
