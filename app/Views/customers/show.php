<?php
$status = strtolower($customer['status'] ?? 'inactive');

$statusClass = $status === 'active'
    ? 'bg-success bg-opacity-10 text-success'
    : 'bg-danger bg-opacity-10 text-danger';

$statusLabel = $status === 'active'
    ? 'Active'
    : 'Non Active';
?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Detail Customer
            </h3>

            <p class="mb-0 text-body">
                <?= htmlspecialchars($customer['company_name'] ?? '-') ?>
            </p>
        </div>

        <div class="d-flex justify-content-between justify-content-md-end align-items-center flex-wrap gap-3">

            <a
                href="<?= url('customers') ?>"
                class="btn btn-light erp-btn"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Kembali
            </a>

            <div class="d-flex flex-wrap align-items-center erp-action-group">

                <a
                    href="<?= url('customers-edit') ?>?id=<?= $customer['id'] ?>"
                    class="btn btn-outline-primary erp-btn"
                >
                    <i class="ri-edit-line me-1"></i>
                    Edit
                </a>

                <div class="dropdown">

                    <button
                        class="btn btn-primary text-white dropdown-toggle erp-btn"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >
                        <i class="ri-settings-3-line me-1"></i>
                        Actions
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end erp-dropdown-menu">

                        <?php if (!($transactionCheck['has_transaction'] ?? false)): ?>
                            <li>
                                <a
                                    class="dropdown-item erp-dropdown-item"
                                    href="<?= url('customers-delete') ?>?id=<?= $customer['id'] ?>"
                                    onclick="return confirm('Yakin ingin menghapus customer ini?')"
                                >
                                    <div class="erp-dropdown-title text-danger">
                                        <i class="ri-delete-bin-line me-2"></i>
                                        Hapus Customer
                                    </div>

                                    <div class="erp-dropdown-desc">
                                        Hapus data customer dari sistem
                                    </div>
                                </a>
                            </li>
                        <?php endif; ?>

                    </ul>

                </div>

            </div>

        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="row g-4">

        <div class="col-md-3">
            <div class="erp-detail-label">Status</div>

            <div class="erp-detail-value">
                <span class="default-badge <?= $statusClass ?>">
                    <?= $statusLabel ?>
                </span>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">Customer</div>

            <div class="erp-detail-value">
                <?= htmlspecialchars($customer['company_name'] ?? '-') ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">PIC</div>

            <div class="erp-detail-value">
                <?= htmlspecialchars($customer['pic_name'] ?? '-') ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="erp-detail-label">No. HP</div>

            <div class="erp-detail-value">
                <?= htmlspecialchars($customer['phone'] ?? '-') ?>
            </div>
        </div>

    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title">
            Informasi Customer
        </h4>
    </div>

    <div class="p-20">

        <div class="row g-4">

            <div class="col-md-6">
                <div class="erp-detail-label">Nama Perusahaan / Customer</div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($customer['company_name'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="erp-detail-label">Nama PIC</div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($customer['pic_name'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="erp-detail-label">No. HP</div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($customer['phone'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="erp-detail-label">Email</div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($customer['email'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="erp-detail-label">NPWP</div>

                <div class="erp-detail-value">
                    <?= htmlspecialchars($customer['npwp'] ?? '-') ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="erp-detail-label">Status</div>

                <div class="erp-detail-value">
                    <span class="default-badge <?= $statusClass ?>">
                        <?= $statusLabel ?>
                    </span>
                </div>
            </div>

            <div class="col-md-12">
                <div class="erp-detail-label">Alamat</div>

                <div class="erp-detail-value">
                    <?= nl2br(htmlspecialchars($customer['address'] ?? '-')) ?>
                </div>
            </div>

        </div>

    </div>

</div>