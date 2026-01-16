<div class="d-flex justify-content-center flex-column align-items-center" style="height: 100vh;">
    <div class="card shadow-sm mx-auto">
        <div class="card-body p-4">
            <h3 class="text-center mb-4"><?= __('sign_in') ?></h3>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="/login" method="POST">
                <?= \App\Core\Csrf::field() ?>
                <div class="mb-3">
                    <label for="email" class="form-label"><?= __('email') ?></label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <label for="password" class="form-label"><?= __('password') ?></label>
                        <a href="/forgot-password" class="small text-decoration-none"><?= __('forgot') ?></a>
                    </div>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary"><?= __('login') ?></button>
                </div>
            </form>
            <div class="text-center mt-3">
                <small><?= __('dont_have_account') ?> <a href="/register"><?= __('sign_up') ?></a></small>
            </div>
        </div>
    </div>
    <div class="text-center mt-3">
        <small><a href="/"><?= __('go_back_home') ?></a></small>
    </div>
</div>