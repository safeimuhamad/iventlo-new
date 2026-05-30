<?php require_once __DIR__ . '/layouts/auth-header.php'; ?>
<div class="member-auth-form">
	    <h2><?= t('Lengkapi profil Anda', 'Complete your profile') ?></h2>
    <p class="intro"><?= t('Email berhasil diverifikasi. Lengkapi data untuk mengaktifkan akun peserta.', 'Email verified. Complete your information to activate your participant account.') ?></p>
    <?php if (!empty($memberError)): ?><div class="member-alert error"><?= htmlspecialchars($memberError) ?></div><?php endif; ?>
    <form method="POST" action="<?= frontUrl('member-verify', ['slug' => $token]) ?>">
        <div class="member-form-group"><label>Email</label><input type="email" class="member-form-control" value="<?= htmlspecialchars($user['email']) ?>" readonly></div>
	        <div class="member-form-group"><label><?= t('Nama lengkap', 'Full name') ?></label><input name="name" class="member-form-control" required></div>
        <div class="row">
	            <div class="col-md-6"><div class="member-form-group"><label><?= t('Tanggal lahir', 'Date of birth') ?></label><input type="date" name="birth_date" max="<?= date('Y-m-d') ?>" class="member-form-control" required></div></div>
	            <div class="col-md-6"><div class="member-form-group"><label><?= t('Jenis kelamin', 'Gender') ?></label><select name="gender" class="member-form-control" required><option value=""><?= t('Pilih', 'Select') ?></option><option value="male"><?= t('Laki-laki', 'Male') ?></option><option value="female"><?= t('Perempuan', 'Female') ?></option></select></div></div>
        </div>
        <div class="member-form-group"><label>Password</label><input type="password" name="password" minlength="8" class="member-form-control" required placeholder="<?= t('Minimal 8 karakter', 'At least 8 characters') ?>"></div>
	        <div class="member-form-group"><label><?= t('Konfirmasi password', 'Confirm password') ?></label><input type="password" name="password_confirmation" minlength="8" class="member-form-control" required></div>
	        <button type="submit" class="member-submit"><?= t('Aktifkan akun', 'Activate account') ?></button>
    </form>
</div>
<?php require_once __DIR__ . '/layouts/auth-footer.php'; ?>
