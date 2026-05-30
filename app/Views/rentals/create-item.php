<div class="card bg-white rounded-10 border border-white p-20">

    <h3 class="mb-4">Tambah Unit Rental</h3>

    <form method="POST" action="<?= url('rental-items-store') ?>">

        <input type="hidden" name="rental_id" value="<?= $rental_id ?>">

        <div class="mb-3">
            <label>Source Unit</label>

            <select name="source_type" id="source_type" class="form-control" required>
                <option value="internal">Internal</option>
                <option value="partner">Rekanan</option>
            </select>
        </div>

        <!-- INTERNAL -->
        <div id="internal-area">

            <div class="mb-3">
                <label>Pilih Unit</label>

                <select name="unit_id" class="form-control">
                    <option value="">-- Pilih Unit --</option>

                    <?php foreach ($units as $unit): ?>

                        <option value="<?= $unit['id'] ?>">
                            <?= $unit['kode_unit'] ?>
                            -
                            <?= $unit['nama_unit'] ?>
                            -
                            <?= $unit['brand'] ?>
                        </option>

                    <?php endforeach; ?>
                </select>
            </div>

        </div>

        <!-- PARTNER -->
        <div id="partner-area" style="display:none;">

            <div class="mb-3">
                <label>Vendor Rekanan</label>

                <select name="partner_id" class="form-control">
                    <option value="">-- Pilih Vendor --</option>

                    <?php foreach ($partners as $partner): ?>
                        <option value="<?= $partner['id'] ?>">
                            <?= htmlspecialchars($partner['partner_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label>Nama Unit Rekanan</label>
                <input type="text" name="partner_unit_name" class="form-control">
            </div>

            <div class="mb-3">
                <label>Brand Unit</label>
                <input type="text" name="partner_unit_brand" class="form-control">
            </div>

            <div class="mb-3">
                <label>Kategori</label>
                <input type="text" name="partner_unit_category" class="form-control">
            </div>

            <div class="mb-3">
                <label>Modal Rekanan</label>
                <input type="number" name="partner_cost" class="form-control">
            </div>

        </div>

        <button class="btn btn-primary">
            Simpan
        </button>

    </form>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const source = document.getElementById('source_type');
        const internalArea = document.getElementById('internal-area');
        const partnerArea = document.getElementById('partner-area');

        function toggleArea() {

            if (source.value === 'partner') {

                partnerArea.style.display = 'block';
                internalArea.style.display = 'none';

            } else {

                partnerArea.style.display = 'none';
                internalArea.style.display = 'block';

            }
        }

        toggleArea();

        source.addEventListener('change', toggleArea);

    });
</script>