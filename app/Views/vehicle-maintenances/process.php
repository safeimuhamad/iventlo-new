<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="p-20 border-bottom">
        <h3 class="mb-1">Proses Service Kendaraan</h3>

        <p class="text-muted mb-0">
            <?= htmlspecialchars($vehicle['vehicle_name'] ?? '-') ?> -
            <?= htmlspecialchars($vehicle['plate_number'] ?? '-') ?>
        </p>
    </div>

    <form 
        action="<?= url('vehicle-maintenances-store') ?>" 
        method="POST"
        enctype="multipart/form-data"
        class="p-20"
    >

        <input 
            type="hidden" 
            name="vehicle_id" 
            value="<?= $vehicle['id'] ?>"
        >

        <div class="row">

            <div class="col-md-4 mb-3">
                <label class="form-label">Tanggal Service</label>

                <input 
                    type="date"
                    name="maintenance_date"
                    class="form-control"
                    value="<?= date('Y-m-d') ?>"
                    required
                >
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Jenis Service</label>

                <select 
                    name="maintenance_type"
                    class="form-select"
                    required
                >
                    <option value="rutin">Service Rutin</option>
                    <option value="ganti_oli">Ganti Oli</option>
                    <option value="ban">Ban</option>
                    <option value="rem">Rem</option>
                    <option value="mesin">Mesin</option>
                    <option value="kelistrikan">Kelistrikan</option>
                    <option value="darurat">Darurat</option>
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">KM Saat Service</label>

                <input 
                    type="number"
                    name="km_at_maintenance"
                    class="form-control"
                    value="<?= (int) ($vehicle['total_km'] ?? 0) ?>"
                    required
                >
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Nama Mekanik</label>

                <input 
                    type="text"
                    name="mechanic_name"
                    class="form-control"
                    placeholder="Nama mekanik"
                >
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Bengkel</label>

                <input 
                    type="text"
                    name="workshop_name"
                    class="form-control"
                    placeholder="Nama bengkel"
                >
            </div>

        </div>

        <hr>

        <h5 class="mb-3">Checklist Service</h5>

        <div class="table-responsive mb-4">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>Checklist</th>
                        <th width="180">Status</th>
                        <th>Catatan</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($checklists as $checklist): ?>

                        <tr>

                            <td>
                                <?= htmlspecialchars($checklist) ?>

                                <input 
                                    type="hidden"
                                    name="checklist_name[]"
                                    value="<?= htmlspecialchars($checklist) ?>"
                                >
                            </td>

                            <td>

                                <select 
                                    name="checklist_status[]"
                                    class="form-select"
                                >
                                    <option value="ok">OK</option>
                                    <option value="not_ok">Tidak OK</option>
                                    <option value="need_repair">Perlu Perbaikan</option>
                                </select>

                            </td>

                            <td>

                                <input 
                                    type="text"
                                    name="checklist_notes[]"
                                    class="form-control"
                                    placeholder="Catatan"
                                >

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

        <div class="mb-3">
            <label class="form-label">Catatan Service</label>

            <textarea 
                name="notes"
                class="form-control"
                rows="4"
                placeholder="Catatan hasil service kendaraan"
            ></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Biaya Service</label>

            <input 
                type="number"
                name="cost"
                class="form-control"
                value="0"
                min="0"
            >
        </div>

        <div class="mb-4">
            <label class="form-label">Dokumentasi Service</label>

            <input 
                type="file"
                name="documents[]"
                class="form-control"
                multiple
                accept="image/*,.pdf"
            >

            <small class="text-muted">
                Upload foto atau PDF hasil service kendaraan.
            </small>
        </div>

        <div class="d-flex justify-content-between">

            <a 
                href="<?= url('vehicle-maintenances-due') ?>"
                class="btn btn-light"
            >
                Kembali
            </a>

            <button 
                type="submit"
                class="btn btn-primary text-white"
            >
                Simpan Service
            </button>

        </div>

    </form>

</div>