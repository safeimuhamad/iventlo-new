<div class="card bg-white rounded-10 border border-white p-20">

    <h3 class="mb-4">Edit Vendor</h3>

    <form method="POST" action="<?= url('vendors-update') ?>">

        <input 
            type="hidden"
            name="id"
            value="<?= htmlspecialchars($vendor['id']) ?>"
        >

        <div class="row g-3">

            <div class="col-md-3">
                <label>Kode Vendor</label>
                <input 
                    type="text"
                    class="form-control"
                    value="<?= htmlspecialchars($vendor['vendor_code'] ?? '') ?>"
                    readonly
                >
            </div>

            <div class="col-md-6">
                <label>Nama Vendor</label>
                <input 
                    type="text"
                    name="vendor_name"
                    class="form-control"
                    value="<?= htmlspecialchars($vendor['vendor_name'] ?? '') ?>"
                    required
                >
            </div>

            <div class="col-md-3">
                <label>Nama PIC</label>
                <input 
                    type="text"
                    name="pic_name"
                    class="form-control"
                    value="<?= htmlspecialchars($vendor['pic_name'] ?? '') ?>"
                >
            </div>

            <div class="col-md-4">
                <label>No Telepon</label>
                <input 
                    type="text"
                    name="phone"
                    class="form-control"
                    value="<?= htmlspecialchars($vendor['phone'] ?? '') ?>"
                >
            </div>

            <div class="col-md-4">
                <label>Email</label>
                <input 
                    type="email"
                    name="email"
                    class="form-control"
                    value="<?= htmlspecialchars($vendor['email'] ?? '') ?>"
                >
            </div>

            <div class="col-md-4">
                <label>NPWP</label>
                <input 
                    type="text"
                    name="npwp"
                    class="form-control"
                    value="<?= htmlspecialchars($vendor['npwp'] ?? '') ?>"
                >
            </div>

            <div class="col-md-12">
                <label>Alamat</label>
                <textarea 
                    name="address"
                    class="form-control"
                    rows="3"
                ><?= htmlspecialchars($vendor['address'] ?? '') ?></textarea>
            </div>

            <div class="col-md-12">
                <label>Catatan</label>
                <textarea 
                    name="notes"
                    class="form-control"
                    rows="4"
                ><?= htmlspecialchars($vendor['notes'] ?? '') ?></textarea>
            </div>

        </div>

        <hr>

        <button class="btn btn-primary text-white">
            Update Vendor
        </button>

        <a href="<?= url('vendors') ?>" class="btn btn-secondary">
            Kembali
        </a>

    </form>

</div>