                <div class="main-content-container overflow-hidden">
                  <div class="row">

                    <div class="col-xxl-3 col-xxxxl-6">
                      <div class="card bg-white p-20 py-30 rounded-10 border border-white mb-4 position-relative z-1">
                        <div class="d-flex">
                          <div class="flex-grow-1">
                            <h3 class="mb-10 lh-1 fs-14 text-body">Profit & Loss</h3>

                            <h2 class="fs-26 fw-bold mb-10 lh-1">
                              Rp <?= number_format((float) ($currentMonthProfit ?? 0), 0, ',', '.') ?>
                          </h2>

                          <div class="d-inline-block" style="margin-bottom: 1px;">
                              <span class="d-flex align-content-center gap-1 <?= ($currentMonthProfit ?? 0) >= 0 ? 'bg-success-70' : 'bg-danger' ?> border-white rounded-1" style="padding: 3px 5px;">
                                <i class="material-symbols-outlined fs-14 text-white">
                                  <?= ($currentMonthProfit ?? 0) >= 0 ? 'trending_up' : 'trending_down' ?>
                              </i>

                              <span class="lh-1 fs-14 text-white">
                                  Bulan Ini
                              </span>
                          </span>
                      </div>

                      <p class="mb-0 fs-14">Laba/rugi bulan berjalan</p>
                  </div>

                  <div class="flex-shrink-0 ms-3 position-relative" style="width: 110px;">
                    <div class="w-100 position-absolute top-50 translate-middle-y" style="right: -8px;">
                      <div id="profit_loss_chart"></div>
                  </div>
              </div>
          </div>
      </div>
  </div>

  <div class="col-xxl-3 col-xxxxl-6">
      <div class="card bg-white p-20 py-30 rounded-10 border border-white mb-4 position-relative z-1">
        <div class="d-flex">
          <div class="flex-grow-1">
            <h3 class="mb-10 lh-1 fs-14 text-body">Cash Flow</h3>

            <h2 class="fs-26 fw-bold mb-10 lh-1">
              Rp <?= number_format((float) ($cashFlowThisMonth ?? 0), 0, ',', '.') ?>
          </h2>

          <div class="d-inline-block" style="margin-bottom: 1px;">
              <span class="d-flex align-content-center gap-1 <?= ($cashFlowThisMonth ?? 0) >= 0 ? 'bg-success-70' : 'bg-danger' ?> border-white rounded-1" style="padding: 3px 5px;">
                <i class="material-symbols-outlined fs-14 text-white">
                  <?= ($cashFlowThisMonth ?? 0) >= 0 ? 'trending_up' : 'trending_down' ?>
              </i>

              <span class="lh-1 fs-14 text-white">
                  Realtime
              </span>
          </span>
      </div>

      <p class="mb-0 fs-14">Income dikurangi expense</p>
  </div>

  <div class="flex-shrink-0 ms-3 position-relative" style="width: 160px;">
    <div class="w-100 position-absolute top-50 translate-middle-y" style="right: -14px;">
      <div id="cash_flow_chart"></div>
  </div>
</div>
</div>
</div>
</div>

<div class="col-xxl-3 col-xxxxl-6">
  <div class="card bg-white p-20 py-30 rounded-10 border border-white mb-4 position-relative z-1">
    <div class="d-flex">
      <div class="flex-grow-1">
        <h3 class="mb-10 lh-1 fs-14 text-body">Income</h3>

        <h2 class="fs-26 fw-bold mb-10 lh-1">
          Rp <?= number_format((float) ($incomeThisMonth ?? 0), 0, ',', '.') ?>
      </h2>

      <div class="d-inline-block" style="margin-bottom: 1px;">
          <span class="d-flex align-content-center gap-1 bg-success-70 border-white rounded-1" style="padding: 3px 5px;">
            <i class="material-symbols-outlined fs-14 text-white">trending_up</i>
            <span class="lh-1 fs-14 text-white">Masuk</span>
        </span>
    </div>

    <p class="mb-0 fs-14">Pembayaran masuk bulan ini</p>
</div>

<div class="flex-shrink-0 ms-3 position-relative" style="width: 170px;">
    <div class="w-100 position-absolute top-50 translate-middle-y" style="right: -8px;">
      <div id="sales_chart"></div>
  </div>
</div>
</div>
</div>
</div>

<div class="col-xxl-3 col-xxxxl-6">
  <div class="card bg-white p-20 py-30 rounded-10 border border-white mb-4 position-relative z-1">
    <div class="d-flex">
      <div class="flex-grow-1">
        <h3 class="mb-10 lh-1 fs-14 text-body">Expense</h3>

        <h2 class="fs-26 fw-bold mb-10 lh-1">
          Rp <?= number_format((float) ($expenseThisMonth ?? 0), 0, ',', '.') ?>
      </h2>

      <div class="d-inline-block" style="margin-bottom: 1px;">
          <span class="d-flex align-content-center gap-1 bg-danger border-white rounded-1" style="padding: 3px 5px;">
            <i class="material-symbols-outlined fs-14 text-white">trending_down</i>
            <span class="lh-1 fs-14 text-white">Keluar</span>
        </span>
    </div>

    <p class="mb-0 fs-14">Pengeluaran bulan ini</p>
</div>

<div class="flex-shrink-0 ms-3 position-relative" style="width: 155px;">
    <div class="w-100 position-absolute top-50 translate-middle-y" style="right: -8px;">
      <div id="payment_profit_chart"></div>
  </div>
</div>
</div>
</div>
</div>

</div>
<div class="row">
    <div class="col-xxl-7 col-xxxxxl-12">
      <div class="card bg-white p-20 rounded-10 border border-white mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-20">
          <h3>Income vs Expense</h3>

          <span class="badge bg-primary bg-opacity-10 text-primary">
            Tahun <?= date('Y') ?>
        </span>
    </div>
    <div style="margin: -24px -25px -14px -25px;">
      <div id="revenue_vs_operating_margin_chart"></div>
  </div>
</div>
</div>
<div class="col-xxl-5 col-xxxxxl-12">
  <div class="card bg-white p-20 rounded-10 border border-white mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-20">
      <h3>Pengeluaran per Kategori</h3>

      <span class="badge bg-danger bg-opacity-10 text-danger">
        Bulan Ini
    </span>
</div>

<div id="expense_by_category_chart" style="height: 360px;"></div>
</div>
</div>
</div>
<div class="row">
    <div class="col-xxl-3 col-xxxl-6">
      <div class="card bg-white p-20 rounded-10 border border-white mb-4">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-20">
          <h3>Financial Summary</h3>

          <span class="badge bg-primary bg-opacity-10 text-primary">
            Realtime
        </span>
    </div>

    <div class="d-flex flex-column gap-20">

      <!-- Saldo Bank -->
      <div class="d-flex justify-content-between align-items-center border-bottom pb-3">
        <div>
          <h4 class="fs-15 mb-1">Total Saldo Bank</h4>
          <span class="text-muted fs-13">
            Semua rekening aktif
        </span>
    </div>

    <strong class="fs-16 text-primary">
      Rp <?= number_format((float) ($totalBankBalance ?? 0), 0, ',', '.') ?>
  </strong>
</div>

<!-- Piutang -->
<div class="d-flex justify-content-between align-items-center border-bottom pb-3">
    <div>
      <h4 class="fs-15 mb-1">Outstanding Receivable</h4>
      <span class="text-muted fs-13">
        Invoice belum lunas
    </span>
</div>

<strong class="fs-16 text-warning">
  Rp <?= number_format((float) ($outstandingReceivable ?? 0), 0, ',', '.') ?>
</strong>
</div>

<!-- Income -->
<div class="d-flex justify-content-between align-items-center border-bottom pb-3">
    <div>
      <h4 class="fs-15 mb-1">Income Bulan Ini</h4>
      <span class="text-muted fs-13">
        Pembayaran masuk
    </span>
</div>

<strong class="fs-16 text-success">
  Rp <?= number_format((float) ($incomeThisMonth ?? 0), 0, ',', '.') ?>
</strong>
</div>

<!-- Expense -->
<div class="d-flex justify-content-between align-items-center">
    <div>
      <h4 class="fs-15 mb-1">Expense Bulan Ini</h4>
      <span class="text-muted fs-13">
        Total pengeluaran
    </span>
</div>

<strong class="fs-16 text-danger">
  Rp <?= number_format((float) ($expenseThisMonth ?? 0), 0, ',', '.') ?>
</strong>
</div>

</div>

</div>
</div>
<div class="col-xxl-7 col-xxxl-6">
  <div class="card bg-white rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">
      <h3>Transaksi Keuangan Terbaru</h3>

      <span class="badge bg-primary bg-opacity-10 text-primary">
        Latest
    </span>
</div>

<div class="default-table-area mx-minus-1 style-two table-latest-transaction for-border-color1">
  <div class="table-responsive">

    <table class="table align-middle">
      <thead>
        <tr>
          <th class="fw-normal text-body-color-40 fs-14">Tanggal</th>
          <th class="fw-normal text-body-color-40 fs-14">Transaksi</th>
          <th class="fw-normal text-body-color-40 fs-14">Debit</th>
          <th class="fw-normal text-body-color-40 fs-14">Credit</th>
          <th class="fw-normal text-body-color-40 fs-14 text-center">Type</th>
      </tr>
  </thead>

  <tbody>
    <?php if (!empty($latestJournalTransactions)): ?>
        <?php foreach ($latestJournalTransactions as $trx): ?>
            <tr>
              <td class="text-secondary">
                <?= !empty($trx['journal_date'])
                ? date('d M Y', strtotime($trx['journal_date']))
                : '-'
                ?>
            </td>

            <td>
                <strong class="fs-14 text-secondary">
                  <?= htmlspecialchars($trx['description'] ?? '-') ?>
              </strong>

              <small class="text-muted d-block">
                  Ref: <?= htmlspecialchars($trx['reference_type'] ?? '-') ?>
                  #<?= htmlspecialchars($trx['reference_id'] ?? '-') ?>
              </small>
          </td>

          <td class="text-success fw-medium fs-14">
            Rp <?= number_format((float) ($trx['total_debit'] ?? 0), 0, ',', '.') ?>
        </td>

        <td class="text-danger fw-medium fs-14">
            Rp <?= number_format((float) ($trx['total_credit'] ?? 0), 0, ',', '.') ?>
        </td>

        <td class="text-center">
            <span class="text-primary bg-primary bg-opacity-10 fs-15 fw-normal d-inline-block default-badge style-two border border-primary">
              <?= htmlspecialchars($trx['reference_type'] ?? '-') ?>
          </span>
      </td>
  </tr>
<?php endforeach; ?>
<?php else: ?>
    <tr>
      <td colspan="5" class="text-center text-muted py-4">
        Belum ada transaksi keuangan.
    </td>
</tr>
<?php endif; ?>
</tbody>
</table>

</div>
</div>

</div>
</div>
<div class="col-xxl-2 col-xxxl-12">
  <?php
  $todayDay = (int) date('d');

  $vehicleInstallments = [
    [
        'vehicle' => 'Mobil 1',
        'due_day' => 4,
    ],
    [
        'vehicle' => 'Mobil 2',
        'due_day' => 15,
    ],
    [
        'vehicle' => 'Mobil 3',
        'due_day' => 10,
    ],
];

$getInstallmentStatus = function ($dueDay) use ($todayDay) {
    $diff = $dueDay - $todayDay;

    if ($diff === 0) {
        return [
            'class' => 'bg-danger text-white',
            'label' => 'Jatuh tempo hari ini',
        ];
    }

    if ($diff > 0 && $diff <= 3) {
        return [
            'class' => 'bg-warning text-dark',
            'label' => $diff . ' hari lagi',
        ];
    }

    if ($diff < 0) {
        return [
            'class' => 'bg-secondary text-white',
            'label' => 'Sudah lewat bulan ini',
        ];
    }

    return [
        'class' => 'bg-success text-white',
        'label' => $diff . ' hari lagi',
    ];
};
?>

<div class="card bg-white rounded-10 border border-white mb-4 p-20">

    <div class="d-flex justify-content-between align-items-center mb-20">
      <h3 class="mb-0">Reminder Cicilan Kendaraan</h3>

      <span class="badge bg-primary bg-opacity-10 text-primary">
        <?= date('d M Y') ?>
    </span>
</div>

<div class="d-flex flex-column gap-3">

  <?php foreach ($vehicleInstallments as $item): ?>
      <?php $status = $getInstallmentStatus($item['due_day']); ?>

      <div class="border rounded-3 p-15">
        <div class="d-flex justify-content-between align-items-center">

          <div>
            <h4 class="fs-15 mb-1">
              <?= htmlspecialchars($item['vehicle']) ?>
          </h4>

          <span class="text-muted fs-13">
              Jatuh tempo setiap tanggal <?= (int) $item['due_day'] ?>
          </span>
      </div>

      <span class="badge <?= $status['class'] ?>">
        <?= $status['label'] ?>
    </span>

</div>
</div>
<?php endforeach; ?>

</div>

</div>
</div>
</div>
<div class="row">
    <div class="col-lg-6">
      <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">
          <h3>Piutang Jatuh Tempo</h3>

          <span class="badge bg-danger bg-opacity-10 text-danger">
            Overdue
        </span>
    </div>

    <div class="default-table-area mx-minus-1">
      <div class="table-responsive">

        <table class="table align-middle">
          <thead>
            <tr>
              <th>Invoice</th>
              <th>Customer</th>
              <th>Jatuh Tempo</th>
              <th>Sisa Tagihan</th>
              <th>Terlambat</th>
          </tr>
      </thead>

      <tbody>

        <?php if (!empty($overdueInvoices)): ?>

            <?php foreach ($overdueInvoices as $invoice): ?>

                <tr>

                  <td>
                    <strong>
                      <?= htmlspecialchars($invoice['no_invoice'] ?? '-') ?>
                  </strong>
              </td>

              <td>
                <?= htmlspecialchars($invoice['customer_name'] ?? '-') ?>
            </td>

            <td>
                <?= !empty($invoice['due_date'])
                ? date('d M Y', strtotime($invoice['due_date']))
                : '-'
                ?>
            </td>

            <td class="text-danger fw-medium">
                Rp <?= number_format((float) ($invoice['remaining_amount'] ?? 0), 0, ',', '.') ?>
            </td>

            <td>
                <span class="badge bg-danger text-white">
                  <?= (int) ($invoice['overdue_days'] ?? 0) ?> Hari
              </span>
          </td>

      </tr>

  <?php endforeach; ?>

<?php else: ?>

    <tr>
      <td colspan="5" class="text-center text-muted py-4">
        Tidak ada piutang jatuh tempo.
    </td>
</tr>

<?php endif; ?>

</tbody>

</table>

</div>
</div>

</div>
</div>
<div class="col-lg-6">
  <div class="card bg-white p-20 rounded-10 border border-white mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-20">
      <h3>Total Receivables vs Total Payable</h3>

      <span class="badge bg-primary bg-opacity-10 text-primary">
        Tahun <?= date('Y') ?>
    </span>
</div>

<div style="margin: -24px -9px -14px -15px;">
  <div id="total_receivables_vs_total_payable_chart"></div>
</div>

</div>
</div>
</div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const rupiah = function (value) {
            return "Rp " + new Intl.NumberFormat("id-ID").format(value || 0);
        };

        const rupiahCompact = function (value) {
            return "Rp " + new Intl.NumberFormat("id-ID", {
                notation: "compact",
                compactDisplay: "short"
            }).format(value || 0);
        };

        const renderChart = function (selector, options) {
            const el = document.querySelector(selector);

            if (!el || typeof ApexCharts === 'undefined') {
                return;
            }

            new ApexCharts(el, options).render();
        };

        renderChart("#profit_loss_chart", {
            series: [{ data: [12, 18, 15, 22, 28, 24, 30] }],
            chart: {
                type: "area",
                height: 70,
                sparkline: { enabled: true },
                toolbar: { show: false }
            },
            stroke: {
                curve: "smooth",
                width: 3
            },
            fill: { opacity: 0.15 },
            colors: ["#198754"]
        });

        renderChart("#cash_flow_chart", {
            series: [{ data: [20, 15, 18, 25, 22, 28, 26] }],
            chart: {
                type: "bar",
                height: 70,
                sparkline: { enabled: true },
                toolbar: { show: false }
            },
            plotOptions: {
                bar: {
                    columnWidth: "50%",
                    borderRadius: 2
                }
            },
            colors: ["#0d6efd"]
        });

        renderChart("#sales_chart", {
            series: [{ data: [8, 12, 10, 16, 20, 18, 24] }],
            chart: {
                type: "line",
                height: 70,
                sparkline: { enabled: true },
                toolbar: { show: false }
            },
            stroke: {
                curve: "smooth",
                width: 3
            },
            colors: ["#20c997"]
        });

        renderChart("#payment_profit_chart", {
            series: [{ data: [18, 16, 20, 14, 12, 10, 15] }],
            chart: {
                type: "area",
                height: 70,
                sparkline: { enabled: true },
                toolbar: { show: false }
            },
            stroke: {
                curve: "smooth",
                width: 3
            },
            fill: { opacity: 0.15 },
            colors: ["#dc3545"]
        });

        renderChart("#revenue_vs_operating_margin_chart", {
            series: [
            {
                name: "Income",
                data: <?= json_encode($incomeMonthlyChart ?? []) ?>
            },
            {
                name: "Expense",
                data: <?= json_encode($expenseMonthlyChart ?? []) ?>
            }
            ],
            chart: {
                type: "line",
                height: 380,
                toolbar: { show: false }
            },
            stroke: {
                curve: "smooth",
                width: 4
            },
            dataLabels: { enabled: false },
            xaxis: {
                categories: [
                    "Jan", "Feb", "Mar", "Apr", "Mei", "Jun",
                    "Jul", "Agu", "Sep", "Okt", "Nov", "Des"
                    ]
            },
            yaxis: {
                labels: {
                    formatter: rupiah
                }
            },
            tooltip: {
                y: {
                    formatter: rupiah
                }
            },
            colors: ["#198754", "#dc3545"],
            grid: {
                borderColor: "#e5e7eb",
                strokeDashArray: 5
            },
            legend: {
                position: "top",
                horizontalAlign: "center"
            }
        });

        renderChart("#expense_by_category_chart", {
            series: <?= json_encode(array_map('floatval', array_column($expenseByAccount ?? [], 'total'))) ?>,
            chart: {
                type: "donut",
                height: 360
            },
            labels: <?= json_encode(array_column($expenseByAccount ?? [], 'account_name')) ?>,
            dataLabels: { enabled: false },
            legend: {
                position: "bottom"
            },
            tooltip: {
                y: {
                    formatter: rupiah
                }
            }
        });

        renderChart("#total_receivables_vs_total_payable_chart", {
            series: [
            {
                name: "Payables",
                data: <?= json_encode($payableMonthlyChart ?? []) ?>
            },
            {
                name: "Receivables",
                data: <?= json_encode($receivableMonthlyChart ?? []) ?>
            }
            ],
            chart: {
                type: "area",
                height: 360,
                toolbar: { show: false }
            },
            stroke: {
                curve: "smooth",
                width: 4
            },
            fill: {
                type: "solid",
                opacity: 0.18
            },
            dataLabels: { enabled: false },
            xaxis: {
                categories: [
                    "JAN", "FEB", "MAR", "APR", "MAY", "JUN",
                    "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"
                    ]
            },
            yaxis: {
                labels: {
                    formatter: rupiahCompact
                }
            },
            tooltip: {
                y: {
                    formatter: rupiah
                }
            },
            colors: ["#796df6", "#06b6d4"],
            grid: {
                borderColor: "#e5e7eb",
                strokeDashArray: 5
            },
            legend: {
                position: "bottom",
                horizontalAlign: "center"
            }
        });

    });
</script>