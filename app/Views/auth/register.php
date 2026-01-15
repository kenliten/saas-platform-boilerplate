<div class="card p-4">
    <div class="text-center mb-4">
        <h3><?= __('register') ?></h3>
        <p class="text-muted"><?= __('register_msg') ?></p>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="/register" method="POST">
        <?= \App\Core\Csrf::field() ?>
        <div class="mb-3">
            <label class="form-label">
                <?= __('email') ?>
            </label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">
                <?= __('password') ?>
            </label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-primary"><?= __('register') ?></button>
        </div>
    </form>
    <div class="text-center mt-3">
        <small><?= __('have_account') ?> <a href="/login"><?= __('login') ?></a></small>
    </div>
</div>