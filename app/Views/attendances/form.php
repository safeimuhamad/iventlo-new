<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger mb-4">
        <?= $_SESSION['error']; ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>
<?php
$attendance = $attendance ?? [];
?>
<?php
$attendance = $attendance ?? [];
?>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title mb-0">
            Informasi Absensi
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
                            <?= (($attendance['employee_id'] ?? '') == $employee['id']) ? 'selected' : '' ?>
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
                    Tanggal Absensi <span class="text-danger">*</span>
                </label>

                <input
                    type="date"
                    name="attendance_date"
                    class="form-control"
                    value="<?= htmlspecialchars($attendance['attendance_date'] ?? date('Y-m-d')) ?>"
                    required
                >
            </div>

            <div class="col-md-6">
                <label class="erp-detail-label">
                    Check In
                </label>

                <input
                    type="time"
                    name="check_in"
                    class="form-control"
                    value="<?= htmlspecialchars($attendance['check_in'] ?? '') ?>"
                >
            </div>

            <div class="col-md-6">
                <label class="erp-detail-label">
                    Check Out
                </label>

                <input
                    type="time"
                    name="check_out"
                    class="form-control"
                    value="<?= htmlspecialchars($attendance['check_out'] ?? '') ?>"
                >
            </div>

            <div class="col-md-4">
                <label class="erp-detail-label">
                    Status
                </label>

                <select name="status" class="form-select">
                    <option value="present" <?= (($attendance['status'] ?? 'present') === 'present') ? 'selected' : '' ?>>
                        Hadir
                    </option>
                    <option value="late" <?= (($attendance['status'] ?? '') === 'late') ? 'selected' : '' ?>>
                        Terlambat
                    </option>
                    <option value="permission" <?= (($attendance['status'] ?? '') === 'permission') ? 'selected' : '' ?>>
                        Izin
                    </option>
                    <option value="sick" <?= (($attendance['status'] ?? '') === 'sick') ? 'selected' : '' ?>>
                        Sakit
                    </option>
                    <option value="leave" <?= (($attendance['status'] ?? '') === 'leave') ? 'selected' : '' ?>>
                        Cuti
                    </option>
                    <option value="absent" <?= (($attendance['status'] ?? '') === 'absent') ? 'selected' : '' ?>>
                        Alpa
                    </option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="erp-detail-label">
                    Terlambat / Menit
                </label>

                <input
                    type="number"
                    name="late_minutes"
                    class="form-control"
                    value="<?= htmlspecialchars($attendance['late_minutes'] ?? 0) ?>"
                    min="0"
                >
            </div>

            <div class="col-md-4">
                <label class="erp-detail-label">
                    Lembur / Menit
                </label>

                <input
                    type="number"
                    name="overtime_minutes"
                    class="form-control"
                    value="<?= htmlspecialchars($attendance['overtime_minutes'] ?? 0) ?>"
                    min="0"
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
                    placeholder="Catatan absensi"
                ><?= htmlspecialchars($attendance['notes'] ?? '') ?></textarea>

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
                    <span>Status Default</span>
                    <strong>
                        <?= htmlspecialchars(ucfirst($attendance['status'] ?? 'present')) ?>
                    </strong>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Tanggal</span>
                    <strong>
                        <?= htmlspecialchars($attendance['attendance_date'] ?? date('Y-m-d')) ?>
                    </strong>
                </div>

                <hr>

                <div class="text-body">
                    Data absensi digunakan untuk monitoring kehadiran, keterlambatan, dan lembur karyawan.
                </div>

            </div>

        </div>

    </div>

</div>