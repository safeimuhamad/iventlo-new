<?php require_once __DIR__ . '/layouts/auth-header.php'; ?>
<div class="member-auth-form">
	    <h2><?= t('Buat password baru', 'Create new password') ?></h2>
    <p class="intro"><?= t('Silakan buat password baru untuk akun peserta Anda.', 'Create a new password for your participant account.') ?></p>
    <?php if (!empty($memberError)): ?><div class="member-alert error"><?= htmlspecialchars($memberError) ?></div><?php endif; ?>
    <form method="POST" action="<?= frontUrl('member-reset', ['slug' => $token]) ?>">
	        <div class="member-form-group"><label><?= t('Password baru', 'New password') ?></label><input type="password" name="password" minlength="8" class="member-form-control" required placeholder="<?= t('Minimal 8 karakter', 'At least 8 characters') ?>"></div>
	        <div class="member-form-group"><label><?= t('Konfirmasi password', 'Confirm password') ?></label><input type="password" name="password_confirmation" minlength="8" class="member-form-control" required placeholder="<?= t('Ulangi password', 'Confirm password') ?>"></div>
	        <button type="submit" class="member-submit"><?= t('Simpan password', 'Save password') ?></button>
    </form>
</div>
<?php require_once __DIR__ . '/layouts/auth-footer.php'; ?>
