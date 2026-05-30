<div class="card bg-white rounded-10 border border-white p-20 mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h3 class="mb-1">Assign Teknisi</h3>
            <p class="mb-0 text-body">
                <?= htmlspecialchars($rental['no_rental'] ?? '-') ?> - 
                <?= htmlspecialchars($rental['customer_name'] ?? '-') ?>
            </p>
        </div>

        <a href="<?= url('rentals-show') ?>?id=<?= $rental['id'] ?>" class="btn btn-secondary">
            Kembali
        </a>
    </div>
</div>

<div class="card bg-white rounded-10 border border-white p-20">
    <form method="POST" action="<?= url('rentals-store-technician') ?>">

        <input type="hidden" name="rental_id" value="<?= htmlspecialchars($rental['id']) ?>">

        <div class="mb-3">
            <label>Jenis Pekerjaan</label>
            <select name="task_type" id="task_type" class="form-control" required>
                <option value="">-- Pilih Pekerjaan --</option>
                <option value="kirim_pasang">Kirim / Pasang</option>
                <option value="bongkar">Bongkar</option>
            </select>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Tanggal Jadwal</label>
                <input 
                    type="date" 
                    name="scheduled_date" 
                    id="scheduled_date"
                    class="form-control" 
                    value="<?= htmlspecialchars($rental['tanggal_rental'] ?? date('Y-m-d')) ?>"
                    required
                >
            </div>

            <div class="col-md-6 mb-3">
                <label>Jam Jadwal</label>
                <input 
                    type="time" 
                    name="scheduled_time" 
                    class="form-control"
                >
            </div>
        </div>

        <div class="mb-3">
            <label>Pilih Teknisi</label>

            <div class="row">
                <?php foreach ($technicians as $technician): ?>
                    <div class="col-md-4 mb-2">
                        <label class="border rounded-10 p-3 d-block cursor-pointer">
                            <input 
                                type="checkbox" 
                                name="technician_ids[]" 
                                value="<?= $technician['id'] ?>"
                                class="me-2"
                            >

                            <strong><?= htmlspecialchars($technician['name']) ?></strong><br>
                            <small class="text-body">
                                <?= htmlspecialchars($technician['role_type'] ?? '-') ?>
                            </small>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <button class="btn btn-primary text-white">
            Simpan Assignment
        </button>

    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const taskType = document.getElementById('task_type');
    const scheduledDate = document.getElementById('scheduled_date');

    const rentalDate = "<?= htmlspecialchars($rental['tanggal_rental'] ?? '') ?>";
    const returnDate = "<?= htmlspecialchars($rental['tanggal_selesai'] ?? '') ?>";

    taskType.addEventListener('change', function () {
        if (this.value === 'kirim_pasang') {
            scheduledDate.value = rentalDate;
        }

        if (this.value === 'bongkar') {
            scheduledDate.value = returnDate;
        }
    });
});
</script>