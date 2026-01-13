<div class="card p-4">
    <div class="text-center mb-4">
        <h3>Create Account</h3>
        <p class="text-muted">Start your free trial today</p>
    </div>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="/register" method="POST">
        <?= \App\Core\Csrf::field() ?>
        <div class="mb-3">
            <label class="form-label">Email address</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Sign Up</button>
        </div>
    </form>
    <div class="text-center mt-3">
        <small>Already have an account? <a href="/login">Log in</a></small>
    </div>
</div>
