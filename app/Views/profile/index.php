<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">My Profile</h1>
</div>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>
<?php if ($error = \App\Core\Session::get('flash_error')): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php \App\Core\Session::remove('flash_error'); ?>
<?php endif; ?>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form action="/profile" method="POST" enctype="multipart/form-data">
                    <?= \App\Core\Csrf::field() ?>
                    
                    <div class="mb-3 text-center">
                        <?php if ($user['avatar']): ?>
                            <img src="<?= htmlspecialchars($user['avatar']) ?>" alt="Avatar" class="rounded-circle mb-3" width="120" height="120" style="object-fit: cover;">
                        <?php else: ?>
                            <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center text-white mb-3" style="width: 120px; height: 120px; font-size: 3rem;">
                                <?= strtoupper(substr($user['email'], 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="mt-2">
                             <label for="avatar" class="form-label">Change Avatar</label>
                             <input type="file" class="form-control" name="avatar" id="avatar">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                    </div>

                    <div class="mb-3">
                        <label for="fullname" class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="fullname" id="fullname" value="<?= htmlspecialchars($user['fullname'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" name="phone" id="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label for="bio" class="form-label">Bio</label>
                        <textarea class="form-control" name="bio" id="bio" rows="3"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
                    </div>

                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Change Password</h5>
            </div>
            <div class="card-body">
                <form action="/profile/password" method="POST">
                    <?= \App\Core\Csrf::field() ?>
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="new_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-warning">Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
