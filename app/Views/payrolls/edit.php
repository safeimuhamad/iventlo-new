<div class="card bg-white rounded-10 border border-white mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20 border-bottom">
        <div>
            <h3 class="mb-1">Edit Payroll</h3>
            <div class="text-muted">
                <?= htmlspecialchars($payroll['full_name'] ?? '-') ?> —
                <?= htmlspecialchars($payroll['employee_code'] ?? '-') ?>
            </div>
        </div>

        <a href="<?= url('payrolls-show') ?>?id=<?= $payroll['id'] ?>" class="btn btn-light">
            Kembali
        </a>
    </div>

    <div class="p-20">
        <form action="<?= url('payrolls-update') ?>" method="POST">
            <input type="hidden" name="id" value="<?= $payroll['id'] ?>">

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Gaji Pokok</label>
                    <input type="text" autocomplete="off" name="basic_salary" class="form-control payroll-input"
                        value="<?= htmlspecialchars($payroll['basic_salary'] ?? 0) ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tunjangan</label>
                    <input type="text" autocomplete="off" name="allowance_amount" class="form-control payroll-input"
                        value="<?= htmlspecialchars($payroll['allowance_amount'] ?? 0) ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Lembur</label>
                    <input type="text" autocomplete="off" name="overtime_amount" class="form-control payroll-input"
                        value="<?= htmlspecialchars($payroll['overtime_amount'] ?? 0) ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Bonus</label>
                    <input type="text" autocomplete="off" name="bonus_amount" class="form-control payroll-input"
                        value="<?= htmlspecialchars($payroll['bonus_amount'] ?? 0) ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Potongan</label>
                    <input type="text" autocomplete="off" name="deduction_amount" class="form-control payroll-input"
                        value="<?= htmlspecialchars($payroll['deduction_amount'] ?? 0) ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">BPJS</label>
                    <input type="text" autocomplete="off" name="bpjs_amount" class="form-control payroll-input"
                        value="<?= htmlspecialchars($payroll['bpjs_amount'] ?? 0) ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Pajak</label>
                    <input type="text" autocomplete="off" name="tax_amount" class="form-control payroll-input"
                        value="<?= htmlspecialchars($payroll['tax_amount'] ?? 0) ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="draft" <?= (($payroll['status'] ?? '') === 'draft') ? 'selected' : '' ?>>Draft</option>
                        <option value="processed" <?= (($payroll['status'] ?? '') === 'processed') ? 'selected' : '' ?>>Diproses</option>
                        <option value="paid" <?= (($payroll['status'] ?? '') === 'paid') ? 'selected' : '' ?>>Dibayar</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Estimasi Gaji Bersih</label>
                    <input type="text" id="netSalaryPreview" class="form-control fw-bold text-success" readonly>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" rows="3" class="form-control"><?= htmlspecialchars($payroll['notes'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="<?= url('payrolls-show') ?>?id=<?= $payroll['id'] ?>" class="btn btn-light">
                    Batal
                </a>

                <button type="submit" class="btn btn-primary text-white">
                    Update Payroll
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function parseNumber(value) {
    return Number(String(value).replace(/\./g, '').replace(/,/g, '')) || 0;
}

function formatNumber(value) {
    return new Intl.NumberFormat('id-ID').format(value || 0);
}

function formatRupiah(number) {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(number || 0);
}

function setupCurrencyInput(input) {

    const rawValue = parseNumber(input.value);

    if (rawValue > 0) {
        input.value = formatNumber(rawValue);
    }

    input.addEventListener('input', function () {

        let numeric = parseNumber(this.value);

        this.value = numeric ? formatNumber(numeric) : '';

        calculatePayroll();
    });

    input.form.addEventListener('submit', function () {
        input.value = parseNumber(input.value);
    });
}

function calculatePayroll() {

    const basic = parseNumber(document.querySelector('[name="basic_salary"]').value);
    const allowance = parseNumber(document.querySelector('[name="allowance_amount"]').value);
    const overtime = parseNumber(document.querySelector('[name="overtime_amount"]').value);
    const bonus = parseNumber(document.querySelector('[name="bonus_amount"]').value);

    const deduction = parseNumber(document.querySelector('[name="deduction_amount"]').value);
    const bpjs = parseNumber(document.querySelector('[name="bpjs_amount"]').value);
    const tax = parseNumber(document.querySelector('[name="tax_amount"]').value);

    const gross = basic + allowance + overtime + bonus;

    const net = gross - deduction - bpjs - tax;

    document.getElementById('netSalaryPreview').value = formatRupiah(net);
}

document.querySelectorAll('.payroll-input').forEach(function(input) {
    setupCurrencyInput(input);
});

calculatePayroll();
</script>