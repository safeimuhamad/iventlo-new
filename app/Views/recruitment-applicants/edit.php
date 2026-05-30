
<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger mb-4">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>
<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Edit Kandidat
            </h3>

            <p class="mb-0 text-body">
                Perbarui data kandidat untuk kebutuhan screening, interview, offering, dan proses rekrutmen.
            </p>
        </div>

        <a
            href="<?= url('recruitment-applicants-show') ?>?id=<?= $item['id'] ?>"
            class="btn btn-light erp-btn"
        >
            <i class="ri-arrow-left-line me-1"></i>
            Kembali
        </a>

    </div>

</div>

<form method="POST" action="<?= url('recruitment-applicants-update') ?>">

    <input
        type="hidden"
        name="id"
        value="<?= htmlspecialchars($item['id']) ?>"
    >

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Informasi Kandidat
            </h4>
        </div>

        <div class="p-20">

            <div class="row g-4">

                <div class="col-md-6">
                    <label class="erp-detail-label">
                        Nama Kandidat
                    </label>

                    <input
                        type="text"
                        name="full_name"
                        class="form-control"
                        value="<?= htmlspecialchars($item['full_name'] ?? '') ?>"
                        required
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
                        value="<?= htmlspecialchars($item['phone'] ?? '') ?>"
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
                        value="<?= htmlspecialchars($item['email'] ?? '') ?>"
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">
                        Source
                    </label>

                    <select name="source" class="form-select">

                        <?php $sources = ['LinkedIn', 'Job Portal', 'Instagram', 'Referral', 'Walk In', 'Other']; ?>

                        <option value="">
                            -- Pilih Source --
                        </option>

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

            </div>

        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Informasi Rekrutmen
            </h4>
        </div>

        <div class="p-20">

            <div class="row g-4">

                <div class="col-md-6">

                    <label class="erp-detail-label">
                        Department
                    </label>

                    <select name="department_id" class="form-select">

                        <option value="">
                            -- Pilih Department --
                        </option>

                        <?php foreach ($departments as $department): ?>
                            <option
                                value="<?= $department['id'] ?>"
                                <?= ($item['department_id'] ?? '') == $department['id'] ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($department['name'] ?? '-') ?>
                            </option>
                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="col-md-6">

                    <label class="erp-detail-label">
                        Position
                    </label>

                    <select name="position_id" class="form-select">

                        <option value="">
                            -- Pilih Position --
                        </option>

                        <?php foreach ($positions as $position): ?>
                            <option
                                value="<?= $position['id'] ?>"
                                <?= ($item['position_id'] ?? '') == $position['id'] ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($position['name'] ?? '-') ?>
                            </option>
                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="col-md-6">

                    <label class="erp-detail-label">
                        Expected Salary
                    </label>

                    <input
                        type="text"
                        id="expected_salary_display"
                        class="form-control"
                        autocomplete="off"
                        value="<?= number_format((float) ($item['expected_salary'] ?? 0), 0, ',', '.') ?>"
                    >

                    <input
                        type="hidden"
                        name="expected_salary"
                        id="expected_salary"
                        value="<?= htmlspecialchars($item['expected_salary'] ?? 0) ?>"
                    >

                </div>

                <div class="col-md-6">

                    <label class="erp-detail-label">
                        Status
                    </label>

                    <select name="status" class="form-select">

                        <?php
                        $statuses = [
                            'new' => 'New',
                            'screening' => 'Screening',
                            'interview' => 'Interview',
                            'test' => 'Test',
                            'offering' => 'Offering',
                            'hired' => 'Hired',
                            'rejected' => 'Rejected',
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

                    <label class="erp-detail-label">
                        Jadwal Interview
                    </label>

                    <input
                        type="datetime-local"
                        name="interview_date"
                        class="form-control"
                        value="<?= !empty($item['interview_date']) ? date('Y-m-d\TH:i', strtotime($item['interview_date'])) : '' ?>"
                    >

                </div>

                <div class="col-md-6">

                    <label class="erp-detail-label">
                        URL Folder Google Drive
                    </label>

                    <input
                        type="url"
                        name="google_drive_url"
                        class="form-control"
                        value="<?= htmlspecialchars($item['google_drive_url'] ?? '') ?>"
                        placeholder="https://drive.google.com/drive/folders/..."
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

                        <strong>
                            <?= htmlspecialchars($item['status'] ?? 'new') ?>
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Source</span>

                        <strong>
                            <?= htmlspecialchars($item['source'] ?? '-') ?>
                        </strong>
                    </div>

                    <hr>

                    <div class="text-body">
                        Data kandidat digunakan untuk proses screening, interview, offering, hingga hiring.
                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="card bg-white rounded-10 border border-white p-20">

        <div class="d-flex justify-content-end flex-wrap gap-3">

            <a
                href="<?= url('recruitment-applicants-show') ?>?id=<?= $item['id'] ?>"
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
    const displayInput = document.getElementById('expected_salary_display');
    const hiddenInput = document.getElementById('expected_salary');

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