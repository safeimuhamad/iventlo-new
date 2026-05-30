<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger mb-4">
        <?= $_SESSION['error']; ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success mb-4">
        <?= $_SESSION['success']; ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<div class="card bg-white rounded-10 border border-white p-20 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h3 class="mb-1">
                Website Setting
            </h3>

            <p class="mb-0 text-body">
                Kelola identitas website, kontak, sosial media dan SEO default website Iventlo.
            </p>
        </div>

    </div>

</div>

<form
    method="POST"
    action="<?= url('website-settings-update') ?>"
    enctype="multipart/form-data"
>

    <!-- IDENTITAS WEBSITE -->
    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Identitas Website
            </h4>
        </div>

        <div class="p-20">

            <div class="row g-4">

                <div class="col-md-6">
                    <label class="erp-detail-label">
                        Nama Perusahaan
                    </label>

                    <input
                        type="text"
                        name="company_name"
                        class="form-control"
                        value="<?= htmlspecialchars($setting['company_name'] ?? '') ?>"
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">
                        Tagline
                    </label>

                    <input
                        type="text"
                        name="tagline"
                        class="form-control"
                        value="<?= htmlspecialchars($setting['tagline'] ?? '') ?>"
                    >
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">
                        Logo Utama
                    </label>

                    <input
                        type="file"
                        name="logo"
                        class="form-control"
                    >

                    <?php if (!empty($setting['logo'])): ?>
                        <div class="mt-3">
                            <img
                                src="<?= uploadAsset($setting['logo']) ?>"
                                style="max-height:80px"
                            >
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">
                        Logo Putih
                    </label>

                    <input
                        type="file"
                        name="logo_white"
                        class="form-control"
                    >

                    <?php if (!empty($setting['logo_white'])): ?>
                        <div class="mt-3 bg-dark rounded p-2">
                            <img
                                src="<?= uploadAsset($setting['logo_white']) ?>"
                                style="max-height:80px"
                            >
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">
                        Favicon
                    </label>

                    <input
                        type="file"
                        name="favicon"
                        class="form-control"
                    >

                    <?php if (!empty($setting['favicon'])): ?>
                        <div class="mt-3">
                            <img
                                src="<?= uploadAsset($setting['favicon']) ?>"
                                style="max-height:40px"
                            >
                        </div>
                    <?php endif; ?>
                </div>

            </div>

        </div>

    </div>

    <!-- KONTAK -->
    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Informasi Kontak
            </h4>
        </div>

        <div class="p-20">

            <div class="row g-4">

                <div class="col-md-4">
                    <label class="erp-detail-label">
                        Telepon
                    </label>

                    <input
                        type="text"
                        name="phone"
                        class="form-control"
                        value="<?= htmlspecialchars($setting['phone'] ?? '') ?>"
                    >
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">
                        WhatsApp
                    </label>

                    <input
                        type="text"
                        name="whatsapp"
                        class="form-control"
                        value="<?= htmlspecialchars($setting['whatsapp'] ?? '') ?>"
                    >
                </div>

                <div class="col-md-4">
                    <label class="erp-detail-label">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="<?= htmlspecialchars($setting['email'] ?? '') ?>"
                    >
                </div>

                <div class="col-md-12">
                    <label class="erp-detail-label">
                        Alamat
                    </label>

                    <textarea
                        name="address"
                        class="form-control"
                        rows="3"
                    ><?= htmlspecialchars($setting['address'] ?? '') ?></textarea>
                </div>

            </div>

        </div>

    </div>

    <!-- SOCIAL MEDIA -->
    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                Social Media
            </h4>
        </div>

        <div class="p-20">

            <div class="row g-4">

                <div class="col-md-6">
                    <label class="erp-detail-label">Instagram</label>

                    <input
                        type="url"
                        name="instagram"
                        class="form-control"
                        value="<?= htmlspecialchars($setting['instagram'] ?? '') ?>"
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Facebook</label>

                    <input
                        type="url"
                        name="facebook"
                        class="form-control"
                        value="<?= htmlspecialchars($setting['facebook'] ?? '') ?>"
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">LinkedIn</label>

                    <input
                        type="url"
                        name="linkedin"
                        class="form-control"
                        value="<?= htmlspecialchars($setting['linkedin'] ?? '') ?>"
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">YouTube</label>

                    <input
                        type="url"
                        name="youtube"
                        class="form-control"
                        value="<?= htmlspecialchars($setting['youtube'] ?? '') ?>"
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">TikTok</label>

                    <input
                        type="url"
                        name="tiktok"
                        class="form-control"
                        value="<?= htmlspecialchars($setting['tiktok'] ?? '') ?>"
                    >
                </div>

                <div class="col-md-6">
                    <label class="erp-detail-label">Google Maps</label>

                    <input
                        type="text"
                        name="google_map"
                        class="form-control"
                        value="<?= htmlspecialchars($setting['google_map'] ?? '') ?>"
                    >
                </div>

            </div>

        </div>

    </div>

    <!-- SEO -->
    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="p-20 border-bottom">
            <h4 class="erp-detail-section-title mb-0">
                SEO Default Website
            </h4>
        </div>

        <div class="p-20">

            <div class="row g-4">

                <div class="col-md-12">
                    <label class="erp-detail-label">
                        Meta Title
                    </label>

                    <input
                        type="text"
                        name="meta_title"
                        class="form-control"
                        value="<?= htmlspecialchars($setting['meta_title'] ?? '') ?>"
                    >
                </div>

                <div class="col-md-12">
                    <label class="erp-detail-label">
                        Meta Keywords
                    </label>

                    <textarea
                        name="meta_keywords"
                        class="form-control"
                        rows="3"
                    ><?= htmlspecialchars($setting['meta_keywords'] ?? '') ?></textarea>
                </div>

                <div class="col-md-12">
                    <label class="erp-detail-label">
                        Meta Description
                    </label>

                    <textarea
                        name="meta_description"
                        class="form-control"
                        rows="4"
                    ><?= htmlspecialchars($setting['meta_description'] ?? '') ?></textarea>
                </div>

            </div>

        </div>

    </div>

    <!-- ACTION -->
    <div class="card bg-white rounded-10 border border-white p-20">

        <div class="d-flex justify-content-end flex-wrap gap-3">

            <button
                type="submit"
                class="btn btn-primary text-white erp-btn"
            >
                <i class="ri-save-line me-1"></i>
                Simpan Website Setting
            </button>

        </div>

    </div>

</form>