<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow-sm">
        <div class="container">
            <a class="d-flex gap-2 align-items-center text-decoration-none text-white" href="/">
                <img src="<?= env('APP_LOGO') ?>" alt="<?= env('APP_NAME') ?> Logo" style="width: 28px;" />
                <span><?= env('APP_NAME') ?></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="#features"><?= __('nav_features') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#testimonials"><?= __('nav_testimonials') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/pricing"><?= __('nav_pricing') ?></a>
                    </li>
                    <?php if (\App\Core\Session::has('user_id')): ?>
                        <li class="nav-item ms-lg-3">
                            <a class="btn btn-light text-primary fw-bold px-4 rounded-pill shadow-sm"
                                href="/dashboard"><?= __('nav_dashboard') ?></a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item ms-lg-3">
                            <a class="nav-link" href="/login"><?= __('nav_login') ?></a>
                        </li>
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-warning text-dark fw-bold px-4 rounded-pill shadow-sm"
                                href="/register"><?= __('nav_get_started') ?></a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>