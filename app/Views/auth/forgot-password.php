<div class="d-flex justify-content-center flex-column align-items-center" style="height: 100vh;">
    <div class="card p-4">
        <div class="text-center mb-4">
            <h3>
                <?= __('forgot_password_title') ?>
            </h3>
            <p class="text-muted">
                <?= __('click_below_to_reset') ?>
            </p>
        </div>

        <form action="/forgot-password" method="POST">
            <?= \App\Core\Csrf::field() ?>
            <div class="mb-3">
                <label class="form-label">
                    <?= __('email') ?>
                </label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">
                    <?= __('send_reset_link') ?>
                </button>
            </div>
        </form>
        <div class="text-center mt-3">
            <a href="/login">
                <?= __('back_to_login') ?>
            </a>
        </div>
    </div>
    <div class="text-center mt-3">
        <small><a href="/"><?= __('go_back_home') ?></a></small>
    </div>
</div>