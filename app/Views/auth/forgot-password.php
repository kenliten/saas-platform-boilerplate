<div class="card p-4">
    <div class="text-center mb-4">
        <h3>Forgot Password?</h3>
        <p class="text-muted">Enter your email to receive a reset link</p>
    </div>

    <form action="/forgot-password" method="POST">
        <?= \App\Core\Csrf::field() ?>
        <div class="mb-3">
            <label class="form-label">Email address</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Send Reset Link</button>
        </div>
    </form>
    <div class="text-center mt-3">
        <a href="/login">Back to Login</a>
    </div>
</div>
