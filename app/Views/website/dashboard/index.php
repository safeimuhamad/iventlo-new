<div class="card bg-white rounded-10 border border-white p-20 mb-4">
    <div>
        <h3 class="mb-1">Website Dashboard</h3>
        <p class="mb-0 text-body">
            Ringkasan performa dan konten website Iventlo.
        </p>
    </div>
</div>

<div class="row g-4 mb-4">

    <div class="col-xl-3 col-md-6">
        <div class="card bg-white rounded-10 border border-white p-20 h-100">
            <p class="text-body mb-1">Slider Aktif</p>
            <h3 class="mb-0"><?= (int) ($activeSliders ?? 0) ?></h3>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card bg-white rounded-10 border border-white p-20 h-100">
            <p class="text-body mb-1">Inquiry Hari Ini</p>
            <h3 class="mb-0"><?= (int) ($todayInquiries ?? 0) ?></h3>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card bg-white rounded-10 border border-white p-20 h-100">
            <p class="text-body mb-1">Inquiry Bulan Ini</p>
            <h3 class="mb-0"><?= (int) ($monthInquiries ?? 0) ?></h3>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card bg-white rounded-10 border border-white p-20 h-100">
            <p class="text-body mb-1">Total Inquiry</p>
            <h3 class="mb-0"><?= (int) ($totalInquiries ?? 0) ?></h3>
        </div>
    </div>

</div>

<div class="card bg-white rounded-10 border border-white mb-4">
    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title mb-0">
            Inquiry Terbaru
        </h4>
    </div>

    <div class="default-table-area mx-minus-1">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Kontak</th>
                        <th>Kebutuhan</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($latestInquiries)): ?>
                        <?php foreach ($latestInquiries as $inquiry): ?>
                            <tr>
                                <td>
                                    <a href="<?= url('website-inquiries-show') ?>?id=<?= $inquiry['id'] ?>"
                                       class="fw-semibold text-primary text-decoration-none">
                                        <?= htmlspecialchars($inquiry['name'] ?? '-') ?>
                                    </a>
                                </td>

                                <td>
                                    <?= htmlspecialchars($inquiry['phone'] ?? '-') ?><br>
                                    <span class="text-body fs-14">
                                        <?= htmlspecialchars($inquiry['email'] ?? '-') ?>
                                    </span>
                                </td>

                                <td>
                                    <?= htmlspecialchars($inquiry['service_interest'] ?? '-') ?>
                                </td>

                                <td>
                                    <span class="default-badge bg-primary bg-opacity-10 text-primary">
                                        <?= htmlspecialchars($inquiry['status'] ?? 'new') ?>
                                    </span>
                                </td>

                                <td>
                                    <?= !empty($inquiry['created_at'])
                                        ? date('d M Y H:i', strtotime($inquiry['created_at']))
                                        : '-' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-body py-4">
                                Belum ada inquiry.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card bg-white rounded-10 border border-white mb-4">
    <div class="p-20 border-bottom">
        <h4 class="erp-detail-section-title mb-0">
            Shortcut Website CMS
        </h4>
    </div>

    <div class="p-20">
        <div class="row g-3">

            <div class="col-md-3">
                <a href="<?= url('website-settings') ?>" class="btn btn-light erp-btn w-100">
                    Website Setting
                </a>
            </div>

            <div class="col-md-3">
                <a href="<?= url('website-sliders') ?>" class="btn btn-light erp-btn w-100">
                    Slider Homepage
                </a>
            </div>

            <div class="col-md-3">
                <a href="<?= url('website-inquiries') ?>" class="btn btn-light erp-btn w-100">
                    Inquiry Leads
                </a>
            </div>

            <div class="col-md-3">
                <a href="<?= frontUrl('home', [], 'id') ?>" target="_blank" class="btn btn-primary text-white erp-btn w-100">
                    Lihat Website
                </a>
            </div>

        </div>
    </div>
</div>
