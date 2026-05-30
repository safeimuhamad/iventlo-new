<?php
$overtimeRequest = $overtimeRequest ?? [];
?>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title mb-0">
            Informasi Lembur
        </h4>
    </div>

    <div class="p-20">

        <div class="row g-4">

            <div class="col-md-6">
                <label class="erp-detail-label">
                    Karyawan <span class="text-danger">*</span>
                </label>

                <select name="employee_id" class="form-select" required>
                    <option value="">Pilih Karyawan</option>

                    <?php foreach (($employees ?? []) as $employee): ?>
                        <option
                            value="<?= $employee['id'] ?>"
                            <?= (($overtimeRequest['employee_id'] ?? '') == $employee['id']) ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($employee['full_name']) ?>
                            - <?= htmlspecialchars($employee['employee_code'] ?? '-') ?>
                            |
                            <?= htmlspecialchars($employee['department_name'] ?? '-') ?>
                            /
                            <?= htmlspecialchars($employee['position_name'] ?? '-') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label class="erp-detail-label">
                    Tanggal Lembur <span class="text-danger">*</span>
                </label>

                <input
                    type="date"
                    name="overtime_date"
                    class="form-control"
                    value="<?= htmlspecialchars($overtimeRequest['overtime_date'] ?? date('Y-m-d')) ?>"
                    required
                >
            </div>

            <div class="col-md-6">
                <label class="erp-detail-label">
                    Jam Mulai <span class="text-danger">*</span>
                </label>

                <input
                    type="time"
                    name="start_time"
                    class="form-control"
                    value="<?= htmlspecialchars($overtimeRequest['start_time'] ?? '') ?>"
                    required
                >
            </div>

            <div class="col-md-6">
                <label class="erp-detail-label">
                    Jam Selesai <span class="text-danger">*</span>
                </label>

                <input
                    type="time"
                    name="end_time"
                    class="form-control"
                    value="<?= htmlspecialchars($overtimeRequest['end_time'] ?? '') ?>"
                    required
                >
            </div>

            <div class="col-md-6">
                <label class="erp-detail-label">
                    Status
                </label>

                <select name="status" class="form-select">
                    <option value="draft" <?= (($overtimeRequest['status'] ?? 'draft') === 'draft') ? 'selected' : '' ?>>
                        Draft
                    </option>
                    <option value="waiting_approval" <?= (($overtimeRequest['status'] ?? '') === 'waiting_approval') ? 'selected' : '' ?>>
                        Menunggu Approval
                    </option>
                    <option value="approved" <?= (($overtimeRequest['status'] ?? '') === 'approved') ? 'selected' : '' ?>>
                        Disetujui
                    </option>
                    <option value="rejected" <?= (($overtimeRequest['status'] ?? '') === 'rejected') ? 'selected' : '' ?>>
                        Ditolak
                    </option>
                    <option value="cancelled" <?= (($overtimeRequest['status'] ?? '') === 'cancelled') ? 'selected' : '' ?>>
                        Dibatalkan
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
                    Alasan / Keterangan
                </h4>
            </div>

            <div class="p-20">
                <textarea
                    name="reason"
                    rows="8"
                    class="form-control"
                    placeholder="Tuliskan alasan lembur"
                ><?= htmlspecialchars($overtimeRequest['reason'] ?? '') ?></textarea>
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
                        <?= htmlspecialchars($overtimeRequest['status'] ?? 'draft') ?>
                    </strong>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Tanggal</span>
                    <strong>
                        <?= htmlspecialchars($overtimeRequest['overtime_date'] ?? date('Y-m-d')) ?>
                    </strong>
                </div>

                <hr>

                <div class="text-body">
                    Data lembur digunakan untuk proses approval dan perhitungan kompensasi karyawan.
                </div>

            </div>

        </div>

    </div>

</div>