<?php require_once __DIR__ . '/layouts/auth-header.php'; ?>
<div class="member-auth-form">
	    <h2><?= t('Lupa password?', 'Forgot password?') ?></h2>
    <p class="intro"><?= t('Masukkan email member. Kami akan mengirim link untuk membuat password baru.', 'Enter your member email. We will send a link to create a new password.') ?></p>
    <?php if (!empty($memberError)): ?><div class="member-alert error"><?= htmlspecialchars($memberError) ?></div><?php endif; ?>
    <?php if (!empty($memberSuccess)): ?><div class="member-alert success"><?= htmlspecialchars($memberSuccess) ?></div><?php endif; ?>
    <form method="POST" action="<?= frontUrl('member-forgot') ?>">
        <div class="member-form-group">
            <label>Email</label>
            <input type="email" name="email" class="member-form-control" required placeholder="<?= t('Masukkan email Anda', 'Enter your email') ?>">
        </div>
	        <button type="submit" class="member-submit"><?= t('Kirim link reset', 'Send reset link') ?></button>
    </form>
    <p class="member-auth-links"><a href="<?= frontUrl('member-login') ?>"><?= t('Kembali ke login', 'Back to sign in') ?></a></p>
</div>
<?php require_once __DIR__ . '/layouts/auth-footer.php'; ?>
