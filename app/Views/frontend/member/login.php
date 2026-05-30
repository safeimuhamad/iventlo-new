<?php require_once __DIR__ . '/layouts/auth-header.php'; ?>
<div class="member-auth-form">
	    <h2><?= t('Masuk ke portal Iventlo', 'Sign in to Iventlo portal') ?></h2>
    <p class="intro"><?= t('Peserta dan client dapat masuk melalui halaman ini.', 'Participants and clients can sign in through this page.') ?></p>
    <?php if (!empty($memberError)): ?><div class="member-alert error"><?= htmlspecialchars($memberError) ?></div><?php endif; ?>
    <?php if (!empty($memberSuccess)): ?><div class="member-alert success"><?= htmlspecialchars($memberSuccess) ?></div><?php endif; ?>
    <form method="POST" action="<?= frontUrl('member-login') ?>">
        <div class="member-form-group">
            <label>Email</label>
            <input type="email" name="email" class="member-form-control" required placeholder="<?= t('Masukkan email Anda', 'Enter your email') ?>">
        </div>
        <div class="member-form-group">
	            <div class="d-flex justify-content-between"><label>Password</label><a href="<?= frontUrl('member-forgot') ?>"><?= t('Lupa password?', 'Forgot password?') ?></a></div>
            <input type="password" name="password" class="member-form-control" required placeholder="<?= t('Masukkan password', 'Enter password') ?>">
        </div>
        <button type="submit" class="member-submit"><?= t('Masuk', 'Sign in') ?></button>
    </form>
	    <p class="member-auth-links"><?= t('Belum punya akun?', 'Not registered yet?') ?> <a href="<?= frontUrl('member-register') ?>"><?= t('Daftar sekarang', 'Register now') ?></a></p>
</div>
<?php require_once __DIR__ . '/layouts/auth-footer.php'; ?>
