<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success mb-4">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger mb-4">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">Detail Inquiry</h3>
            <p class="mb-0 text-body">
                Informasi inquiry dari website.
            </p>
        </div>

        <div class="d-flex justify-content-between justify-content-md-end align-items-center flex-wrap gap-3">

            <a href="<?= url('website-inquiries') ?>" class="btn btn-light erp-btn">
                <i class="ri-arrow-left-line me-1"></i>
                Kembali
            </a>

            <?php if (can('website_inquiry.delete')): ?>
                <a
                    href="<?= url('website-inquiries-delete') ?>?id=<?= $inquiry['id'] ?>"
                    class="btn btn-outline-danger erp-btn"
                    onclick="return confirm('Yakin ingin menghapus inquiry ini?')"
                >
                    <i class="ri-delete-bin-line me-1"></i>
                    Hapus
                </a>
            <?php endif; ?>

        </div>

    </div>

</div>

<div class="row g-4 mb-4">

    <div class="col-md-7">

        <div class="card bg-white rounded-10 border border-white h-100">

            <div class="p-20 border-bottom">
                <h4 class="erp-detail-section-title mb-0">
                    Informasi Client
                </h4>
            </div>

            <div class="p-20">

                <div class="row g-4">

                    <div class="col-md-6">
                        <label class="erp-detail-label">Nama</label>
                        <div class="fw-semibold">
                            <?= htmlspecialchars($inquiry['name'] ?? '-') ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="erp-detail-label">Perusahaan / Instansi</label>
                        <div class="fw-semibold">
                            <?= htmlspecialchars($inquiry['company_name'] ?? '-') ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="erp-detail-label">Telepon / WhatsApp</label>
                        <div>
                            <a href="tel:<?= preg_replace('/[^0-9+]/', '', $inquiry['phone'] ?? '') ?>">
                                <?= htmlspecialchars($inquiry['phone'] ?? '-') ?>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="erp-detail-label">Email</label>
                        <div>
                            <a href="mailto:<?= htmlspecialchars($inquiry['email'] ?? '') ?>">
                                <?= htmlspecialchars($inquiry['email'] ?? '-') ?>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label class="erp-detail-label">Kebutuhan Event</label>
                        <div>
                            <?= htmlspecialchars($inquiry['service_interest'] ?? '-') ?>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label class="erp-detail-label">Pesan</label>
                        <div class="border rounded-10 p-3 bg-light">
                            <?= nl2br(htmlspecialchars($inquiry['message'] ?? '-')) ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="erp-detail-label">Sumber</label>
                        <div>
                            <?= htmlspecialchars($inquiry['source'] ?? 'website') ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="erp-detail-label">Tanggal Masuk</label>
                        <div>
                            <?= !empty($inquiry['created_at'])
                                ? date('d M Y H:i', strtotime($inquiry['created_at']))
                                : '-' ?>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="col-md-5">

        <form method="POST" action="<?= url('website-inquiries-update') ?>">

            <input type="hidden" name="id" value="<?= $inquiry['id'] ?>">

            <div class="card bg-white rounded-10 border border-white h-100">

                <div class="p-20 border-bottom">
                    <h4 class="erp-detail-section-title mb-0">
                        Follow Up Inquiry
                    </h4>
                </div>

                <div class="p-20">

                    <div class="mb-3">
                        <label class="erp-detail-label">Status</label>
                        <select name="status" class="form-control">
                            <?php
                            $currentStatus = $inquiry['status'] ?? 'new';
                            $statuses = [
                                'new' => 'New',
                                'contacted' => 'Contacted',
                                'follow_up' => 'Follow Up',
                                'closed' => 'Closed',
                            ];
                            ?>

                            <?php foreach ($statuses as $value => $label): ?>
                                <option value="<?= $value ?>" <?= $currentStatus === $value ? 'selected' : '' ?>>
                                    <?= $label ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="erp-detail-label">Tanggal Follow Up</label>
                        <input
                            type="date"
                            name="follow_up_date"
                            class="form-control"
                            value="<?= htmlspecialchars($inquiry['follow_up_date'] ?? '') ?>"
                        >
                    </div>

                    <div class="mb-3">
                        <label class="erp-detail-label">Catatan</label>
                        <textarea
                            name="notes"
                            class="form-control"
                            rows="6"
                        ><?= htmlspecialchars($inquiry['notes'] ?? '') ?></textarea>
                    </div>

                    <?php if (can('website_inquiry.edit')): ?>
                        <button type="submit" class="btn btn-primary text-white erp-btn">
                            <i class="ri-save-line me-1"></i>
                            Simpan Follow Up
                        </button>
                    <?php endif; ?>

                </div>

            </div>

        </form>

    </div>

</div>