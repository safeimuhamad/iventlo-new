<?php require_once __DIR__ . '/layouts/auth-header.php'; ?>
<div class="member-auth-form">
    <?php if (!empty($invalidTicket)): ?>
	        <h2>QR tiket tidak valid</h2>
        <p class="intro">QR tiket tidak ditemukan atau belum terdaftar di sistem.</p>
    <?php elseif (!empty($denied)): ?>
	        <h2>Akses ditolak</h2>
	        <p class="intro">Akun client ini tidak memiliki akses admin client pada event tersebut.</p>
    <?php else: ?>
	        <h2>Login petugas dibutuhkan</h2>
	        <p class="intro">Scan QR tiket peserta harus dilakukan oleh petugas EO atau client admin yang sudah login.</p>
	        <a class="member-submit d-block text-center" href="<?= frontUrl('member-login') ?>">Masuk portal client</a>
	        <p class="member-auth-links"><a href="<?= url('login') ?>">Masuk area admin / EO</a></p>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/layouts/auth-footer.php'; ?>
