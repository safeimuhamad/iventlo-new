<div class="card bg-white rounded-10 border border-white p-20">

    <h3 class="mb-4">Tambah Rental</h3>

    <form method="POST" action="<?= url('rentals-store') ?>">

        <div class="mb-3">
            <label>Customer Name</label>
            <input type="text" name="customer_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>No. HP</label>
            <input type="text" name="customer_phone" class="form-control">
        </div>
        <div class="mb-3">
            <label>Lokasi</label>
            <textarea name="lokasi" class="form-control" required></textarea>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Tanggal Rental</label>
                <input type="date" name="tanggal_rental" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Jam Kirim</label>
                <input type="time" name="jam_kirim" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>Jam Bongkar</label>
                <input type="time" name="jam_bongkar" class="form-control">
            </div>
        </div>

        <div class="mb-3">
            <label>Catatan</label>
            <textarea name="catatan" class="form-control"></textarea>
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="<?= url('rentals') ?>" class="btn btn-secondary">Kembali</a>

    </form>

</div>