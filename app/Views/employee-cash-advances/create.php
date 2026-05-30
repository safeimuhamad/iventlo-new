<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger mb-4">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Pengajuan Kasbon
            </h3>

            <p class="mb-0 text-body">
                Ajukan kasbon untuk kebutuhan operasional, pembelian material, transportasi, atau keperluan proyek yang memerlukan dana talangan.
            </p>
        </div>

        <a
            href="<?= url('employee-cash-advances') ?>"
            class="btn btn-light erp-btn"
        >
            <i class="ri-arrow-left-line me-1"></i>
            Kembali
        </a>

    </div>

</div>

<form method="POST" action="<?= url('employee-cash-advances-store') ?>">

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Informasi Pengajuan
            </h4>
        </div>

        <div class="p-20">

            <div class="row g-4">

                <div class="col-md-6">

                    <label class="erp-detail-label">
                        Nominal Kasbon <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        id="amount_display"
                        class="form-control"
                        placeholder="0"
                        autocomplete="off"
                        required
                    >

                    <input
                        type="hidden"
                        name="amount"
                        id="amount"
                        value="<?= htmlspecialchars($item['amount'] ?? '') ?>"
                    >

                </div>

                <div class="col-md-6">

                    <label class="erp-detail-label">
                        Keperluan <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="purpose"
                        class="form-control"
                        placeholder="Contoh: Pembelian Material Proyek"
                        required
                    >

                </div>

            </div>

        </div>

    </div>

    <div class="row g-4 mb-4">

        <div class="col-lg-8">

            <div class="card bg-white rounded-10 border border-white h-100">

                <div class="p-20 border-bottom">
                    <h4 class="erp-detail-section-title mb-0">
                        Keterangan
                    </h4>
                </div>

                <div class="p-20">

                    <textarea
                        name="description"
                        rows="8"
                        class="form-control"
                        placeholder="Jelaskan kebutuhan penggunaan kasbon"
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
                        <span>Status Awal</span>
                        <strong>Draft</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Tipe</span>
                        <strong>Kasbon Karyawan</strong>
                    </div>

                    <hr>

                    <div class="text-body">
                        Pengajuan kasbon akan mengikuti alur approval sesuai DOA Matrix yang berlaku sebelum dana dicairkan.
                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white p-20">

        <div class="d-flex justify-content-end flex-wrap gap-3">

            <a
                href="<?= url('employee-cash-advances') ?>"
                class="btn btn-light erp-btn"
            >
                <i class="ri-close-line me-1"></i>
                Batal
            </a>

            <button
                type="submit"
                class="btn btn-primary text-white erp-btn"
            >
                <i class="ri-send-plane-line me-1"></i>
                Ajukan Kasbon
            </button>

        </div>

    </div>

</form>
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const displayInput = document.getElementById('amount_display');
        const hiddenInput  = document.getElementById('amount');

            // set initial value
        if (hiddenInput.value) {
            displayInput.value = formatRupiah(hiddenInput.value);
        }

        displayInput.addEventListener('input', function () {

            let value = this.value.replace(/\D/g, '');

            hiddenInput.value = value;

            this.value = formatRupiah(value);
        });

        function formatRupiah(value) {

            if (!value) return '';

            return new Intl.NumberFormat('id-ID').format(value);
        }
    });
</script>