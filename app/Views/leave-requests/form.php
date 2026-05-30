<?php
$leaveRequest = $leaveRequest ?? [];
?>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title mb-0">
            Informasi Cuti / Izin
        </h4>
    </div>

    <div class="p-20">

        <div class="row g-4">

            <div class="col-md-6">

                <label class="erp-detail-label">
                    Karyawan <span class="text-danger">*</span>
                </label>

                <select
                    name="employee_id"
                    class="form-select"
                    required
                >
                    <option value="">
                        Pilih Karyawan
                    </option>

                    <?php foreach (($employees ?? []) as $employee): ?>
                        <option
                            value="<?= $employee['id'] ?>"
                            <?= (($leaveRequest['employee_id'] ?? '') == $employee['id']) ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($employee['full_name']) ?>
                            -
                            <?= htmlspecialchars($employee['employee_code'] ?? '-') ?>
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
                    Jenis Pengajuan
                </label>

                <select
                    name="leave_type"
                    class="form-select"
                >
                    <option value="annual_leave" <?= (($leaveRequest['leave_type'] ?? 'annual_leave') === 'annual_leave') ? 'selected' : '' ?>>
                        Cuti Tahunan
                    </option>

                    <option value="sick" <?= (($leaveRequest['leave_type'] ?? '') === 'sick') ? 'selected' : '' ?>>
                        Sakit
                    </option>

                    <option value="permission" <?= (($leaveRequest['leave_type'] ?? '') === 'permission') ? 'selected' : '' ?>>
                        Izin
                    </option>

                    <option value="unpaid_leave" <?= (($leaveRequest['leave_type'] ?? '') === 'unpaid_leave') ? 'selected' : '' ?>>
                        Cuti Tanpa Gaji
                    </option>

                    <option value="other" <?= (($leaveRequest['leave_type'] ?? '') === 'other') ? 'selected' : '' ?>>
                        Lainnya
                    </option>
                </select>

            </div>

            <div class="col-md-6">

                <label class="erp-detail-label">
                    Tanggal Mulai
                    <span class="text-danger">*</span>
                </label>

                <input
                    type="date"
                    name="start_date"
                    class="form-control"
                    value="<?= htmlspecialchars($leaveRequest['start_date'] ?? date('Y-m-d')) ?>"
                    required
                >

            </div>

            <div class="col-md-6">

                <label class="erp-detail-label">
                    Tanggal Selesai
                    <span class="text-danger">*</span>
                </label>

                <input
                    type="date"
                    name="end_date"
                    class="form-control"
                    value="<?= htmlspecialchars($leaveRequest['end_date'] ?? date('Y-m-d')) ?>"
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
                    <option value="draft" <?= (($leaveRequest['status'] ?? 'draft') === 'draft') ? 'selected' : '' ?>>
                        Draft
                    </option>

                    <option value="waiting_approval" <?= (($leaveRequest['status'] ?? '') === 'waiting_approval') ? 'selected' : '' ?>>
                        Menunggu Approval
                    </option>

                    <option value="approved" <?= (($leaveRequest['status'] ?? '') === 'approved') ? 'selected' : '' ?>>
                        Disetujui
                    </option>

                    <option value="rejected" <?= (($leaveRequest['status'] ?? '') === 'rejected') ? 'selected' : '' ?>>
                        Ditolak
                    </option>

                    <option value="cancelled" <?= (($leaveRequest['status'] ?? '') === 'cancelled') ? 'selected' : '' ?>>
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
                    placeholder="Tuliskan alasan pengajuan cuti atau izin"
                ><?= htmlspecialchars($leaveRequest['reason'] ?? '') ?></textarea>

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
                    <span>Jenis</span>

                    <strong>
                        <?= htmlspecialchars($leaveRequest['leave_type'] ?? 'annual_leave') ?>
                    </strong>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Status</span>

                    <strong>
                        <?= htmlspecialchars($leaveRequest['status'] ?? 'draft') ?>
                    </strong>
                </div>

                <hr>

                <div class="text-body">
                    Data pengajuan akan digunakan untuk proses approval dan pencatatan absensi karyawan.
                </div>

            </div>

        </div>

    </div>

</div>