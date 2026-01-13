<div class="card shadow-sm mx-auto" style="max-width: 400px; width: 100%;">
    <div class="card-body p-4">
        <h3 class="text-center mb-4">Sign In</h3>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="/login" method="POST">
            <?= \App\Core\Csrf::field() ?>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <div class="d-flex justify-content-between">
                    <label for="password" class="form-label">Password</label>
                    <a href="/forgot-password" class="small text-decoration-none">Forgot?</a>
                </div>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Sign In</button>
            </div>
        </form>
        <div class="text-center mt-3">
            <small>Don't have an account? <a href="/register">Sign up</a></small>
        </div>
    </div>
</div>
