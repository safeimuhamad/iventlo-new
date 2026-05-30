<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger mb-4">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Edit Akun COA
            </h3>

            <p class="mb-0 text-body">
                Perbarui akun Chart of Accounts yang digunakan pada jurnal, transaksi keuangan, laporan laba rugi, dan neraca.
            </p>
        </div>

        <a
            href="<?= url('chart-of-accounts') ?>"
            class="btn btn-light erp-btn"
        >
            <i class="ri-arrow-left-line me-1"></i>
            Kembali
        </a>

    </div>

</div>

<form method="POST" action="<?= url('chart-of-accounts-update') ?>">

    <input
        type="hidden"
        name="id"
        value="<?= htmlspecialchars($account['id']) ?>"
    >

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Informasi Akun
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
                        value="<?= htmlspecialchars($account['account_code'] ?? '') ?>"
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
                        value="<?= htmlspecialchars($account['account_name'] ?? '') ?>"
                        required
                    >
                </div>

                <div class="col-md-2">
                    <label class="erp-detail-label">
                        Tipe Akun <span class="text-danger">*</span>
                    </label>

                    <select name="account_type" class="form-control" required>
                        <?php foreach (['asset', 'liability', 'equity', 'income', 'expense', 'cost'] as $type): ?>
                            <option
                                value="<?= $type ?>"
                                <?= ($account['account_type'] ?? '') === $type ? 'selected' : '' ?>
                            >
                                <?= ucfirst($type) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="erp-detail-label">
                        Normal Balance <span class="text-danger">*</span>
                    </label>

                    <select name="normal_balance" class="form-control" required>
                        <option value="debit" <?= ($account['normal_balance'] ?? '') === 'debit' ? 'selected' : '' ?>>
                            Debit
                        </option>

                        <option value="credit" <?= ($account['normal_balance'] ?? '') === 'credit' ? 'selected' : '' ?>>
                            Credit
                        </option>
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

                        <p class="mb-2">
                            Akun COA digunakan sebagai dasar pencatatan jurnal dan laporan keuangan.
                        </p>

                        <ul class="mb-0 ps-3">
                            <li>Asset → Kas, Bank, Piutang, Persediaan</li>
                            <li>Liability → Hutang Vendor, Hutang Pajak</li>
                            <li>Equity → Modal dan Laba Ditahan</li>
                            <li>Income → Pendapatan Rental & Jasa</li>
                            <li>Expense → Biaya Operasional</li>
                            <li>Cost → HPP / Cost of Sales</li>
                        </ul>

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
                        <span>Kode</span>
                        <strong>
                            <?= htmlspecialchars($account['account_code'] ?? '-') ?>
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Tipe</span>
                        <strong>
                            <?= htmlspecialchars(ucfirst($account['account_type'] ?? '-')) ?>
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Normal</span>
                        <strong>
                            <?= htmlspecialchars(ucfirst($account['normal_balance'] ?? '-')) ?>
                        </strong>
                    </div>

                    <hr>

                    <div class="text-body">
                        Perubahan akun akan memengaruhi jurnal otomatis dan laporan keuangan yang menggunakan akun ini.
                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white p-20">

        <div class="d-flex justify-content-end flex-wrap gap-3">

            <a
                href="<?= url('chart-of-accounts') ?>"
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
