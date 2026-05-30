<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger mb-4">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Edit Lead
            </h3>

            <p class="mb-0 text-body">
                Perbarui informasi prospek pelanggan untuk kebutuhan follow up, survey, quotation, dan monitoring pipeline penjualan.
            </p>
        </div>

        <a
            href="<?= url('marketing-leads-show') ?>?id=<?= $item['id'] ?>"
            class="btn btn-light erp-btn"
        >
            <i class="ri-arrow-left-line me-1"></i>
            Kembali
        </a>

    </div>

</div>

<form method="POST" action="<?= url('marketing-leads-update') ?>">

    <input type="hidden" name="id" value="<?= $item['id'] ?>">

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Informasi Lead
            </h4>
        </div>

        <div class="p-20">

            <div class="row g-4">

                <div class="col-md-6">
                    <label class="erp-detail-label">Company</label>
                    <input
                        type="text"
                        name="company_name"
                        class="form-control"
                        value="<?= htmlspecialchars($item['company_name'] ?? '') ?>"
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">
                        PIC <span class="text-danger">*</span>
                    </label>
                    <input
                        type="text"
                        name="pic_name"
                        class="form-control"
                        value="<?= htmlspecialchars($item['pic_name'] ?? '') ?>"
                        required
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">No. HP</label>
                    <input
                        type="text"
                        name="phone"
                        class="form-control"
                        value="<?= htmlspecialchars($item['phone'] ?? '') ?>"
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Email</label>
                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="<?= htmlspecialchars($item['email'] ?? '') ?>"
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Source</label>

                    <select name="source" class="form-select">

                        <?php
                        $sources = [
                            'Website',
                            'WhatsApp',
                            'Instagram',
                            'Google Ads',
                            'Referral',
                            'Repeat Inquiry',
                            'Other'
                        ];
                        ?>

                        <option value="">-- Pilih Source --</option>

                        <?php foreach ($sources as $source): ?>
                            <option
                                value="<?= $source ?>"
                                <?= ($item['source'] ?? '') === $source ? 'selected' : '' ?>
                            >
                                <?= $source ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Minat Layanan</label>
                    <input
                        type="text"
                        name="service_interest"
                        class="form-control"
                        value="<?= htmlspecialchars($item['service_interest'] ?? '') ?>"
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
                    <label class="erp-detail-label">Estimasi Nilai</label>

                    <input
                        type="text"
                        id="estimated_value_display"
                        class="form-control"
                        autocomplete="off"
                        value="<?= number_format((float) ($item['estimated_value'] ?? 0), 0, ',', '.') ?>"
                    >

                    <input
                        type="hidden"
                        name="estimated_value"
                        id="estimated_value"
                        value="<?= htmlspecialchars($item['estimated_value'] ?? 0) ?>"
                    >
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">Priority</label>

                    <select name="priority" class="form-select">
                        <option value="low" <?= ($item['priority'] ?? '') === 'low' ? 'selected' : '' ?>>Low</option>
                        <option value="medium" <?= ($item['priority'] ?? '') === 'medium' ? 'selected' : '' ?>>Medium</option>
                        <option value="high" <?= ($item['priority'] ?? '') === 'high' ? 'selected' : '' ?>>High</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">Status</label>

                    <select name="status" class="form-select">

                        <?php
                        $statuses = [
                            'new' => 'New',
                            'contacted' => 'Contacted',
                            'follow_up' => 'Follow Up',
                            'survey' => 'Survey',
                            'quotation' => 'Quotation',
                            'deal' => 'Deal',
                            'lost' => 'Lost',
                        ];
                        ?>

                        <?php foreach ($statuses as $key => $label): ?>
                            <option
                                value="<?= $key ?>"
                                <?= ($item['status'] ?? '') === $key ? 'selected' : '' ?>
                            >
                                <?= $label ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Assign To</label>

                    <select name="assigned_to" class="form-select">

                        <option value="">
                            -- Belum Ditugaskan --
                        </option>

                        <?php foreach ($users as $user): ?>
                            <option
                                value="<?= $user['id'] ?>"
                                <?= ($item['assigned_to'] ?? '') == $user['id'] ? 'selected' : '' ?>
                            >
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
                        ><?= htmlspecialchars($item['address'] ?? '') ?></textarea>

                    </div>

                    <div>

                        <label class="erp-detail-label">
                            Catatan
                        </label>

                        <textarea
                            name="notes"
                            rows="6"
                            class="form-control"
                        ><?= htmlspecialchars($item['notes'] ?? '') ?></textarea>

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
                        <strong><?= htmlspecialchars($item['status'] ?? 'new') ?></strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Priority</span>
                        <strong><?= htmlspecialchars($item['priority'] ?? 'medium') ?></strong>
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
                href="<?= url('marketing-leads-show') ?>?id=<?= $item['id'] ?>"
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