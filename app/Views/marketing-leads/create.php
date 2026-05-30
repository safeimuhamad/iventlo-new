<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger mb-4">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success mb-4">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Tambah Lead
            </h3>

            <p class="mb-0 text-body">
                Tambahkan prospek pelanggan baru untuk kebutuhan follow up, survey, quotation, dan monitoring pipeline penjualan.
            </p>
        </div>

        <a
            href="<?= url('marketing-leads') ?>"
            class="btn btn-light erp-btn"
        >
            <i class="ri-arrow-left-line me-1"></i>
            Kembali
        </a>

    </div>

</div>

<form method="POST" action="<?= url('marketing-leads-store') ?>">

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Informasi Lead
            </h4>
        </div>

        <div class="p-20">

            <div class="row g-4">

                <div class="col-md-6">
                    <label class="erp-detail-label">
                        Company
                    </label>

                    <input
                        type="text"
                        name="company_name"
                        class="form-control"
                        required
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">
                        PIC
                    </label>

                    <input
                        type="text"
                        name="pic_name"
                        class="form-control"
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">
                        No. HP
                    </label>

                    <input
                        type="text"
                        name="phone"
                        class="form-control"
                        required
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                    >
                </div>

                <div class="col-md-6">

                    <label class="erp-detail-label">
                        Source
                    </label>

                    <select
                        name="source"
                        class="form-select"
                        required
                    >
                        <option value="">-- Pilih Source --</option>
                        <option value="Website">Website</option>
                        <option value="WhatsApp">WhatsApp</option>
                        <option value="Instagram">Instagram</option>
                        <option value="Google Ads">Google Ads</option>
                        <option value="Referral">Referral</option>
                        <option value="Repeat Inquiry">Repeat Inquiry</option>
                        <option value="Other">Other</option>
                    </select>

                </div>

                <div class="col-md-6">

                    <label class="erp-detail-label">
                        Minat Layanan
                    </label>

                    <input
                        type="text"
                        name="service_interest"
                        class="form-control"
                        placeholder="Contoh: Rental AC, Maintenance AC, Cleaning Karpet"
                    >

                </div>

            </div>

        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Pipeline & Assignment
            </h4>
        </div>

        <div class="p-20">

            <div class="row g-4">

                <div class="col-md-4">

                    <label class="erp-detail-label">
                        Estimasi Nilai
                    </label>

                    <input
                        type="text"
                        id="estimated_value_display"
                        class="form-control"
                        autocomplete="off"
                        placeholder="0"
                    >

                    <input
                        type="hidden"
                        name="estimated_value"
                        id="estimated_value"
                        value="0"
                    >

                </div>

                <div class="col-md-4">

                    <label class="erp-detail-label">
                        Priority
                    </label>

                    <select name="priority" class="form-select">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>

                </div>

                <div class="col-md-4">

                    <label class="erp-detail-label">
                        Status
                    </label>

                    <select name="status" class="form-select">
                        <option value="new" selected>New</option>
                        <option value="contacted">Contacted</option>
                        <option value="follow_up">Follow Up</option>
                        <option value="survey">Survey</option>
                        <option value="quotation">Quotation</option>
                        <option value="deal">Deal</option>
                        <option value="lost">Lost</option>
                    </select>

                </div>

                <div class="col-md-6">

                    <label class="erp-detail-label">
                        Assign To
                    </label>

                    <select
                        name="assigned_to"
                        class="form-select"
                        required
                    >
                        <option value="">
                            -- Belum Ditugaskan --
                        </option>

                        <?php foreach ($users as $user): ?>
                            <option value="<?= $user['id'] ?>">
                                <?= htmlspecialchars($user['name'] ?? '-') ?>
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
                        Alamat & Catatan
                    </h4>
                </div>

                <div class="p-20">

                    <div class="mb-4">

                        <label class="erp-detail-label">
                            Alamat
                        </label>

                        <textarea
                            name="address"
                            rows="4"
                            class="form-control"
                        ></textarea>

                    </div>

                    <div>

                        <label class="erp-detail-label">
                            Catatan
                        </label>

                        <textarea
                            name="notes"
                            rows="6"
                            class="form-control"
                        ></textarea>

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
                        <span>Status Awal</span>
                        <strong>New</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Priority</span>
                        <strong>Medium</strong>
                    </div>

                    <hr>

                    <div class="text-body">
                        Lead digunakan untuk monitoring pipeline sales mulai dari inquiry hingga deal.
                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white p-20">

        <div class="d-flex justify-content-end flex-wrap gap-3">

            <a
                href="<?= url('marketing-leads') ?>"
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const displayInput = document.getElementById('estimated_value_display');
    const hiddenInput = document.getElementById('estimated_value');

    if (!displayInput || !hiddenInput) return;

    displayInput.addEventListener('input', function () {
        let value = this.value.replace(/\D/g, '');

        hiddenInput.value = value || 0;

        this.value = value
            ? new Intl.NumberFormat('id-ID').format(value)
            : '';
    });
});
</script>