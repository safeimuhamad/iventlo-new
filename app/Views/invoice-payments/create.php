<div class="card bg-white rounded-10 border border-white p-20">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Terima Pembayaran</h3>

            <p class="text-body mb-0">
                <?= htmlspecialchars($invoice['no_invoice'] ?? '-') ?>
            </p>
        </div>

        <a href="<?= url('invoices-show') ?>?id=<?= $invoice['id'] ?>" class="btn btn-secondary">
            Kembali
        </a>
    </div>

    <form method="POST" action="<?= url('invoice-payments-store') ?>">

        <input type="hidden" name="invoice_id" value="<?= $invoice['id'] ?>">

        <div class="row g-3">

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
                <label>Nominal Pembayaran</label>

                <input 
                type="text"
                name="payment_amount_display"
                id="payment_amount_display"
                class="form-control"
                value="<?= number_format((float)($invoice['subtotal'] ?? $invoice['billing_total']), 0, ',', '.') ?>"
                required
                >

                <input 
                type="hidden"
                name="payment_amount"
                id="payment_amount"
                value="<?= (float)($invoice['subtotal'] ?? $invoice['billing_total']) ?>"
                >
            </div>

            <div class="col-md-4">
                <label>Metode Pembayaran</label>

                <select name="payment_method" class="form-control">
                    <option value="transfer">Transfer Bank</option>
                    <option value="cash">Cash</option>
                    <option value="qris">QRIS</option>
                    <option value="giro">Giro</option>
                </select>
            </div>
            <div class="col-md-6">
                <label>Rekening Penerima</label>

                <select name="bank_account_id" class="form-control" required>
                    <option value="">-- Pilih Rekening --</option>

                    <?php foreach ($bankAccounts as $account): ?>
                        <option value="<?= $account['id'] ?>">
                            <?= htmlspecialchars($account['account_name']) ?> -
                            <?= htmlspecialchars($account['bank_name']) ?>
                            <?= htmlspecialchars($account['account_number']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label>No Referensi / Bukti Transfer</label>

                <input 
                type="text"
                name="reference_no"
                class="form-control"
                placeholder="Optional"
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

        <div class="d-flex justify-content-between align-items-center">

            <div>
                <small class="text-body">
                    Total Invoice:
                    <strong>
                        Rp <?= number_format((float)($invoice['billing_total'] ?? 0), 0, ',', '.') ?>
                    </strong>
                </small>
            </div>

            <button class="btn btn-primary text-white">
                Simpan Pembayaran
            </button>

        </div>

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
            return String(value || '')
            .replace(/\./g, '')
            .replace(/,/g, '');
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