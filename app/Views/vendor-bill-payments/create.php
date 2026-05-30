<div class="card bg-white rounded-10 border border-white p-20">

    <h3 class="mb-4">Bayar Hutang Vendor</h3>

    <form method="POST" action="<?= url('vendor-bill-payments-store') ?>">

        <input type="hidden" name="vendor_bill_id" value="<?= htmlspecialchars($bill['id']) ?>">

        <div class="row g-3">

            <div class="col-md-4">
                <label>No Bill</label>
                <input 
                    type="text" 
                    class="form-control"
                    value="<?= htmlspecialchars($bill['bill_no'] ?? '-') ?>"
                    readonly
                >
            </div>

            <div class="col-md-4">
                <label>Vendor</label>
                <input 
                    type="text" 
                    class="form-control"
                    value="<?= htmlspecialchars($bill['vendor_name'] ?? '-') ?>"
                    readonly
                >
            </div>

            <div class="col-md-4">
                <label>Tanggal Pembayaran</label>
                <input 
                    type="date"
                    name="payment_date"
                    class="form-control"
                    value="<?= date('Y-m-d') ?>"
                    required
                >
            </div>

            <div class="col-md-4">
                <label>Rekening Pembayaran</label>
                <select name="bank_account_id" class="form-control" required>
                    <option value="">-- Pilih Rekening --</option>

                    <?php foreach ($bankAccounts as $account): ?>
                        <option value="<?= $account['id'] ?>">
                            <?= htmlspecialchars($account['account_code'] ?? '-') ?>
                            -
                            <?= htmlspecialchars($account['account_name'] ?? '-') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4">
                <label>Nominal Pembayaran</label>
                <input 
                    type="text"
                    id="payment_amount_display"
                    class="form-control"
                    value="<?= number_format((float)($bill['remaining_amount'] ?? 0), 0, ',', '.') ?>"
                    required
                >

                <input 
                    type="hidden"
                    name="payment_amount"
                    id="payment_amount"
                    value="<?= htmlspecialchars($bill['remaining_amount'] ?? 0) ?>"
                >
            </div>

            <div class="col-md-4">
                <label>Metode Pembayaran</label>
                <select name="payment_method" class="form-control">
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="cash">Cash</option>
                    <option value="qris">QRIS</option>
                    <option value="debit">Debit</option>
                </select>
            </div>

            <div class="col-md-6">
                <label>No Referensi</label>
                <input 
                    type="text"
                    name="reference_no"
                    class="form-control"
                    placeholder="Nomor referensi transfer / bukti bayar"
                >
            </div>

            <div class="col-md-12">
                <label>Catatan</label>
                <textarea 
                    name="notes"
                    class="form-control"
                    rows="4"
                    placeholder="Catatan pembayaran..."
                ></textarea>
            </div>

        </div>

        <hr>

        <button class="btn btn-primary text-white">
            Simpan Pembayaran
        </button>

        <a href="<?= url('vendor-bills-show') ?>?id=<?= $bill['id'] ?>" class="btn btn-secondary">
            Kembali
        </a>

    </form>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const displayInput = document.getElementById('payment_amount_display');
    const hiddenInput = document.getElementById('payment_amount');

    function formatRupiah(value) {
        return Number(value || 0).toLocaleString('id-ID');
    }

    function parseRupiah(value) {
        return String(value || '').replace(/\./g, '').replace(/,/g, '');
    }

    if (displayInput) {
        displayInput.addEventListener('input', function () {
            const cleanValue = parseRupiah(this.value);

            hiddenInput.value = cleanValue;
            this.value = formatRupiah(cleanValue);
        });
    }
});
</script>