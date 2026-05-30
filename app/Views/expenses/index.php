<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>

            <h3 class="mb-0">
                Pengeluaran
            </h3>

            <p class="text-body fs-14 mb-0">
                Data pengeluaran operasional dan biaya perusahaan
            </p>

        </div>

        <a
            href="<?= url('expenses-create') ?>"
            class="btn btn-primary text-white erp-btn"
        >
            + Tambah Pengeluaran
        </a>

    </div>

    <div class="default-table-area mx-minus-1">

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nomor</th>
                        <th>Kategori</th>
                        <th style="min-width:220px;">Keterangan</th>
                        <th>Referensi</th>
                        <th class="text-end">Nominal</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($expenses)): ?>

                        <?php foreach ($expenses as $expense): ?>

                            <tr>

                                <td>

                                    <?= !empty($expense['expense_date'])
                                        ? date('d M Y', strtotime($expense['expense_date']))
                                        : '-' ?>

                                </td>

                                <td>

                                    <a
                                        href="<?= url('expenses-show') ?>?id=<?= $expense['id'] ?>"
                                        class="fw-semibold text-primary text-decoration-none"
                                    >
                                        <?= htmlspecialchars($expense['expense_no'] ?? '-') ?>
                                    </a>

                                </td>

                                <td>

                                    <?= htmlspecialchars($expense['expense_category'] ?? '-') ?>

                                </td>

                                <td class="text-wrap" style="min-width:220px; max-width:320px;">

                                    <?= htmlspecialchars($expense['description'] ?? '-') ?>

                                </td>

                                <td>

                                    <?= htmlspecialchars($expense['reference_no'] ?? '-') ?>

                                </td>

                                <td class="text-end fw-semibold text-danger">

                                    Rp <?= number_format((float) ($expense['amount'] ?? 0), 0, ',', '.') ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="6" class="text-center text-body py-4">
                                Belum ada data pengeluaran.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>