<div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

        <div>
            <h3 class="mb-0">
                Jurnal Umum
            </h3>

            <p class="text-body fs-14 mb-0">
                Data jurnal akuntansi dari transaksi keuangan
            </p>
        </div>

    </div>

    <div class="default-table-area mx-minus-1">

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th style="min-width:220px;">Keterangan</th>
                        <th>Tipe Referensi</th>
                        <th>ID Referensi</th>
                        <th class="text-end">Debit</th>
                        <th class="text-end">Kredit</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($journals)): ?>

                        <?php foreach ($journals as $journal): ?>

                            <?php
                            $refType = $journal['reference_type'] ?? '';
                            $refId = $journal['reference_id'] ?? '';
                            $refUrl = '#';

                            if ($refType === 'invoice') {
                                $refUrl = url('invoices-show') . '?id=' . $refId;
                            } elseif ($refType === 'invoice_payment') {
                                $refUrl = url('invoices-show') . '?id=' . $refId;
                            } elseif ($refType === 'expense') {
                                $refUrl = url('expenses-show') . '?id=' . $refId;
                            } elseif ($refType === 'bank_transfer') {
                                $refUrl = url('bank-transfers-show') . '?id=' . $refId;
                            } elseif ($refType === 'vendor_bill') {
                                $refUrl = url('vendor-bills-show') . '?id=' . $refId;
                            } elseif ($refType === 'vendor_bill_payment') {
                                $refUrl = url('vendor-bills-show') . '?id=' . $refId;
                            }
                            ?>

                            <tr>

                                <td>
                                    <a
                                        href="<?= url('journal-entries-show') ?>?id=<?= $journal['id'] ?>"
                                        class="fw-semibold text-primary text-decoration-none"
                                    >
                                        <?= !empty($journal['journal_date'])
                                            ? date('d M Y', strtotime($journal['journal_date']))
                                            : '-' ?>
                                    </a>
                                </td>

                                <td class="text-wrap" style="min-width:220px; max-width:420px;">
                                    <?= htmlspecialchars($journal['description'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($refType ?: '-') ?>
                                </td>

                                <td>
                                    <?php if (!empty($refId)): ?>
                                        <a
                                            href="<?= $refUrl ?>"
                                            class="fw-semibold text-primary text-decoration-none"
                                        >
                                            #<?= htmlspecialchars($refId) ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>

                                <td class="text-end fw-semibold">
                                    Rp <?= number_format((float) ($journal['total_debit'] ?? 0), 0, ',', '.') ?>
                                </td>

                                <td class="text-end fw-semibold">
                                    Rp <?= number_format((float) ($journal['total_credit'] ?? 0), 0, ',', '.') ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="6" class="text-center text-body py-4">
                                Belum ada jurnal.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>