<div class="min-vh-100 d-flex align-items-center justify-content-center bg-body-bg px-3">

    <div class="card bg-white border-0 rounded-10 p-4"
         style="width: 100%; max-width: 420px;">

        <div class="text-center mb-4">

            <img src="<?= $logoSrc ?>"
                alt="Logo"
                style="width: 254px;"
            >

            <p class="text-body mb-0">
                Silakan login untuk melanjutkan
            </p>

        </div>

        <?php if (!empty($_SESSION['error'])): ?>

            <div class="alert alert-danger">

                <?= $_SESSION['error']; unset($_SESSION['error']); ?>

            </div>

        <?php endif; ?>

        <?php if (!empty($_SESSION['success'])): ?>

            <div class="alert alert-success">

                <?= $_SESSION['success']; unset($_SESSION['success']); ?>

            </div>

        <?php endif; ?>

        <form method="POST" action="<?= url('process-login') ?>">

            <div class="mb-3">

                <label class="form-label">
                    Email
                </label>

                <div class="position-relative">

                    <span
                        class="material-symbols-outlined position-absolute top-50 translate-middle-y text-body"
                        style="left: 14px;"
                    >
                        mail
                    </span>

                    <input 
                        type="email"
                        name="email"
                        class="form-control"
                        style="padding-left: 45px;"
                        placeholder="Masukkan email"
                        required
                        autofocus
                    >

                </div>

            </div>

            <div class="mb-4">

                <label class="form-label">
                    Password
                </label>

                <div class="position-relative">

                    <span
                        class="material-symbols-outlined position-absolute top-50 translate-middle-y text-body"
                        style="left: 14px;"
                    >
                        lock
                    </span>

                    <input 
                        type="password"
                        name="password"
                        class="form-control"
                        style="padding-left: 45px;"
                        placeholder="Masukkan password"
                        required
                    >

                </div>

            </div>

            <button
                type="submit"
                class="btn btn-primary text-white w-100"
            >
                Login
            </button>

        </form>

        <div class="text-center mt-4">

            <a href="<?= url('forgot-password') ?>">
                Lupa Password?
            </a>

        </div>

        <p class="text-center text-body mt-4 mb-0 fs-14">

            © <?= date('Y') ?> Iventlo Business Platform 

        </p>

    </div>

</div>