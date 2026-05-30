<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger mb-4">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Tambah Akun COA
            </h3>

            <p class="mb-0 text-body">
                Tambahkan akun baru ke Chart of Accounts (COA) yang akan digunakan pada jurnal, transaksi keuangan, laporan laba rugi, dan neraca.
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

<form method="POST" action="<?= url('chart-of-accounts-store') ?>">

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
                        placeholder="Contoh: 5-10006"
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
                        placeholder="Contoh: Biaya Parkir"
                        required
                    >

                </div>

                <div class="col-md-2">

                    <label class="erp-detail-label">
                        Tipe Akun <span class="text-danger">*</span>
                    </label>

                    <select
                        name="account_type"
                        class="form-control"
                        required
                    >
                        <option value="asset">Asset</option>
                        <option value="liability">Liability</option>
                        <option value="equity">Equity</option>
                        <option value="income">Income</option>
                        <option value="expense">Expense</option>
                        <option value="cost">Cost</option>
                    </select>

                </div>

                <div class="col-md-2">

                    <label class="erp-detail-label">
                        Normal Balance <span class="text-danger">*</span>
                    </label>

                    <select
                        name="normal_balance"
                        class="form-control"
                        required
                    >
                        <option value="debit">Debit</option>
                        <option value="credit">Credit</option>
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
                        <span>Status</span>
                        <strong>Active</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Modul</span>
                        <strong>Chart Of Accounts</strong>
                    </div>

                    <hr>

                    <div class="text-body">
                        Pemilihan tipe akun dan normal balance akan mempengaruhi proses jurnal otomatis serta laporan keuangan.
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
                Simpan
            </button>

        </div>

    </div>

</form>