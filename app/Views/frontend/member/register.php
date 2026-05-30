<?php require_once __DIR__ . '/layouts/auth-header.php'; ?>
<div class="member-auth-form">
	    <h2><?= t('Daftar akun peserta', 'Register participant account') ?></h2>
    <p class="intro"><?= t('Masukkan email Anda. Kami akan mengirim link verifikasi untuk melengkapi profil.', 'Enter your email. We will send a verification link to complete your profile.') ?></p>
    <?php if (!empty($memberError)): ?><div class="member-alert error"><?= htmlspecialchars($memberError) ?></div><?php endif; ?>
    <?php if (!empty($memberSuccess)): ?><div class="member-alert success"><?= htmlspecialchars($memberSuccess) ?></div><?php endif; ?>
    <form method="POST" action="<?= frontUrl('member-register') ?>">
        <div class="member-form-group">
            <label>Email</label>
            <input type="email" name="email" class="member-form-control" required placeholder="<?= t('Masukkan email aktif', 'Enter active email') ?>">
        </div>
	        <button type="submit" class="member-submit"><?= t('Kirim link verifikasi', 'Send verification link') ?></button>
    </form>
    <p class="member-auth-links"><?= t('Sudah punya akun?', 'Already registered?') ?> <a href="<?= frontUrl('member-login') ?>"><?= t('Masuk di sini', 'Sign in here') ?></a></p>
</div>
<?php require_once __DIR__ . '/layouts/auth-footer.php'; ?>
