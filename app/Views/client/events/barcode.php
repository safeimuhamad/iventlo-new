<?php
$titleText = htmlspecialchars($event['title'] ?? 'Event Iventlo');
$scanUrlSafe = htmlspecialchars($scanUrl);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title ?? 'QR Code Check-in') ?></title>
    <style>
        * { box-sizing: border-box; }
        body { background: #f4f1f8; color: #151a34; font-family: Arial, sans-serif; margin: 0; padding: 34px; }
        .print-sheet { background: #fff; border-radius: 22px; box-shadow: 0 4px 22px rgba(35,12,72,.08); margin: 0 auto; max-width: 900px; padding: 52px 54px; text-align: center; }
        .brand { color: #831d91; font-size: 30px; font-weight: 700; letter-spacing: .14em; margin-bottom: 35px; }
        h1 { font-size: 35px; margin: 0 0 10px; }
        .venue { color: #69758c; font-size: 18px; margin-bottom: 36px; }
        .qr-box { background: #fff; border: 1px solid #ebe5f1; border-radius: 18px; display: inline-flex; margin: 0 auto 18px; padding: 22px; }
        .event-qr-svg { display: block; height: 420px; max-height: 70vw; max-width: 70vw; width: 420px; }
        .code { color: #111; font-family: monospace; font-size: 13px; overflow-wrap: anywhere; }
        .instruction { background: #f3ebfa; border-radius: 12px; font-size: 20px; font-weight: 600; margin: 36px 0 30px; padding: 20px; }
        .tip { color: #69758c; font-size: 15px; line-height: 1.5; margin: -14px auto 30px; max-width: 560px; }
        .action { background: #831d91; border: 0; border-radius: 9px; color: #fff; cursor: pointer; font-size: 16px; font-weight: 600; padding: 15px 29px; }
        @media print { body { background: #fff; padding: 0; } .print-sheet { box-shadow: none; max-width: none; padding: 24px; } .event-qr-svg { height: 105mm; max-height: none; max-width: none; width: 105mm; } .qr-box { border: 0; padding: 10mm; } .no-print { display: none; } }
    </style>
</head>
<body>
<section class="print-sheet">
    <div class="brand">IVENTLO</div>
    <p>CHECK-IN PESERTA</p>
    <h1><?= $titleText ?></h1>
    <p class="venue"><?= htmlspecialchars($event['venue'] ?: '-') ?><?= !empty($event['event_date']) ? ' | ' . date('d M Y', strtotime($event['event_date'])) : '' ?></p>
    <div class="qr-box"><?= qrCodeCheckInSvg($scanUrl, 440) ?></div>
    <div class="code"><?= $scanUrlSafe ?></div>
    <p class="instruction">Scan QR Code ini untuk konfirmasi kehadiran</p>
    <p class="tip">Cetak dalam ukuran asli, jangan dipotong, dan tempatkan di area dengan pencahayaan yang baik agar mudah dipindai dari berbagai tipe ponsel.</p>
    <button class="action no-print" type="button" onclick="window.print()">Cetak QR Code</button>
</section>
</body>
</html>
