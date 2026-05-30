<?php
$payrollPeriod = $payrollPeriod ?? [];
?>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title mb-0">
            Informasi Periode Payroll
        </h4>
    </div>

    <div class="p-20">

        <div class="row g-4">

            <div class="col-md-6">

                <label class="erp-detail-label">
                    Nama Periode <span class="text-danger">*</span>
                </label>

                <input
                    type="text"
                    name="period_name"
                    class="form-control"
                    value="<?= htmlspecialchars($payrollPeriod['period_name'] ?? '') ?>"
                    placeholder="Contoh: Payroll Mei 2026"
                    required
                >

            </div>

            <div class="col-md-6">

                <label class="erp-detail-label">
                    Status
                </label>

                <select
                    name="status"
                    class="form-select"
                >
                    <option value="draft" <?= (($payrollPeriod['status'] ?? 'draft') === 'draft') ? 'selected' : '' ?>>
                        Draft
                    </option>

                    <option value="processed" <?= (($payrollPeriod['status'] ?? '') === 'processed') ? 'selected' : '' ?>>
                        Diproses
                    </option>

                    <option value="paid" <?= (($payrollPeriod['status'] ?? '') === 'paid') ? 'selected' : '' ?>>
                        Dibayar
                    </option>

                    <option value="closed" <?= (($payrollPeriod['status'] ?? '') === 'closed') ? 'selected' : '' ?>>
                        Ditutup
                    </option>
                </select>

            </div>

            <div class="col-md-4">

                <label class="erp-detail-label">
                    Tanggal Mulai <span class="text-danger">*</span>
                </label>

                <input
                    type="date"
                    name="start_date"
                    class="form-control"
                    value="<?= htmlspecialchars($payrollPeriod['start_date'] ?? date('Y-m-01')) ?>"
                    required
                >

            </div>

            <div class="col-md-4">

                <label class="erp-detail-label">
                    Tanggal Selesai <span class="text-danger">*</span>
                </label>

                <input
                    type="date"
                    name="end_date"
                    class="form-control"
                    value="<?= htmlspecialchars($payrollPeriod['end_date'] ?? date('Y-m-t')) ?>"
                    required
                >

            </div>

            <div class="col-md-4">

                <label class="erp-detail-label">
                    Tanggal Payroll
                </label>

                <input
                    type="date"
                    name="payroll_date"
                    class="form-control"
                    value="<?= htmlspecialchars($payrollPeriod['payroll_date'] ?? date('Y-m-t')) ?>"
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
                    Catatan
                </h4>
            </div>

            <div class="p-20">

                <textarea
                    name="notes"
                    rows="8"
                    class="form-control"
                    placeholder="Catatan periode payroll"
                ><?= htmlspecialchars($payrollPeriod['notes'] ?? '') ?></textarea>

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
                        <?= htmlspecialchars($payrollPeriod['status'] ?? 'draft') ?>
                    </strong>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Tanggal Payroll</span>

                    <strong>
                        <?= htmlspecialchars($payrollPeriod['payroll_date'] ?? date('Y-m-t')) ?>
                    </strong>
                </div>

                <hr>

                <div class="text-body">
                    Periode payroll digunakan sebagai dasar proses perhitungan gaji, tunjangan, potongan, dan pembayaran karyawan.
                </div>

            </div>

        </div>

    </div>

</div>