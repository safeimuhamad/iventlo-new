<?php
$approvalMatrix = $approvalMatrix ?? [];
?>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title mb-0">
            Informasi Approval Matrix
        </h4>
    </div>

    <div class="p-20">

        <div class="row g-4">

            <div class="col-md-6">
                <label class="erp-detail-label">
                    Module <span class="text-danger">*</span>
                </label>

                <select name="module_name" class="form-select" required>

                    <?php
                    $modules = [
                        'purchase_requests' => 'Purchase Request',
                        'purchase_orders' => 'Purchase Order',
                        'vendor_bills' => 'Vendor Bill',
                        'expenses' => 'Expense',
                        'employee_cash_advances' => 'Employee Cash Advance',
                        'quotations' => 'Quotation',
                        'invoices' => 'Invoice',
                        'rentals' => 'Rental Order'
                    ];
                    ?>

                    <option value="">Pilih Module</option>

                    <?php foreach ($modules as $value => $label): ?>
                        <option
                            value="<?= $value ?>"
                            <?= (($approvalMatrix['module_name'] ?? '') === $value) ? 'selected' : '' ?>
                        >
                            <?= $label ?>
                        </option>
                    <?php endforeach; ?>

                </select>

            </div>

            <div class="col-md-6">

                <label class="erp-detail-label">
                    Tipe Dokumen
                </label>

                <input
                    type="text"
                    name="document_type"
                    class="form-control"
                    placeholder="Contoh: material, jasa, rental, project"
                    value="<?= htmlspecialchars($approvalMatrix['document_type'] ?? '') ?>"
                >

                <small class="text-muted">
                    Kosongkan jika berlaku untuk semua tipe dokumen.
                </small>

            </div>

            <div class="col-md-6">

                <label class="erp-detail-label">
                    Department
                </label>

                <select name="department_id" class="form-select">

                    <option value="">
                        Semua Department
                    </option>

                    <?php foreach (($departments ?? []) as $department): ?>
                        <option
                            value="<?= $department['id'] ?>"
                            <?= (($approvalMatrix['department_id'] ?? '') == $department['id']) ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($department['name']) ?>
                        </option>
                    <?php endforeach; ?>

                </select>

            </div>

            <div class="col-md-6">

                <label class="erp-detail-label">
                    Status
                </label>

                <select name="is_active" class="form-select">
                    <option value="1" <?= ((int) ($approvalMatrix['is_active'] ?? 1) === 1) ? 'selected' : '' ?>>
                        Aktif
                    </option>

                    <option value="0" <?= ((int) ($approvalMatrix['is_active'] ?? 1) === 0) ? 'selected' : '' ?>>
                        Nonaktif
                    </option>
                </select>

            </div>

        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title mb-0">
            Rule Approval
        </h4>
    </div>

    <div class="p-20">

        <div class="row g-4">

            <div class="col-md-6">

                <label class="erp-detail-label">
                    Minimal Nominal
                </label>

                <input
                    type="text"
                    name="min_amount"
                    class="form-control rupiah-format"
                    value="<?= isset($approvalMatrix['min_amount']) ? number_format((float)$approvalMatrix['min_amount'], 0, ',', '.') : '' ?>"
                    placeholder="0"
                >

            </div>

            <div class="col-md-6">

                <label class="erp-detail-label">
                    Maksimal Nominal
                </label>

                <input
                    type="text"
                    name="max_amount"
                    class="form-control rupiah-format"
                    value="<?= isset($approvalMatrix['max_amount']) && $approvalMatrix['max_amount'] !== null ? number_format((float)$approvalMatrix['max_amount'], 0, ',', '.') : '' ?>"
                    placeholder="Kosongkan untuk unlimited"
                >

            </div>

            <div class="col-md-4">

                <label class="erp-detail-label">
                    Approval Level <span class="text-danger">*</span>
                </label>

                <input
                    type="number"
                    name="approval_level"
                    class="form-control"
                    value="<?= htmlspecialchars($approvalMatrix['approval_level'] ?? 1) ?>"
                    min="1"
                    required
                >

            </div>

            <div class="col-md-4">

                <label class="erp-detail-label">
                    Approver Role
                </label>

                <select name="approver_role_id" class="form-select">

                    <option value="">
                        Pilih Role
                    </option>

                    <?php foreach (($roles ?? []) as $role): ?>
                        <option
                            value="<?= $role['id'] ?>"
                            <?= (($approvalMatrix['approver_role_id'] ?? '') == $role['id']) ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($role['name']) ?>
                        </option>
                    <?php endforeach; ?>

                </select>

            </div>

            <div class="col-md-4">

                <label class="erp-detail-label">
                    Approver User
                </label>

                <select name="approver_user_id" class="form-select">

                    <option value="">
                        Pilih User
                    </option>

                    <?php foreach (($users ?? []) as $user): ?>
                        <option
                            value="<?= $user['id'] ?>"
                            <?= (($approvalMatrix['approver_user_id'] ?? '') == $user['id']) ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($user['name'] ?? $user['username'] ?? '-') ?>
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
                    Catatan
                </h4>
            </div>

            <div class="p-20">

                <div class="text-body">
                    Approval Matrix digunakan untuk menentukan alur persetujuan otomatis berdasarkan modul, nominal transaksi, department, dan level approval.
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
                    <span>Level Approval</span>
                    <strong>
                        <?= htmlspecialchars($approvalMatrix['approval_level'] ?? 1) ?>
                    </strong>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Status</span>
                    <strong>
                        <?= ((int) ($approvalMatrix['is_active'] ?? 1) === 1) ? 'Aktif' : 'Nonaktif' ?>
                    </strong>
                </div>

            </div>

        </div>

    </div>

</div>