<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?= __('user_management') ?></h1>
</div>

<!-- Invite Section -->
<div class="card mb-4">
    <div class="card-header">
        <?= __('invite_users') ?>
    </div>
    <div class="card-body">
        <form action="/admin/users/invite" method="POST">
            <?= \App\Core\Csrf::field() ?>
            <div class="mb-3">
                <label for="emails" class="form-label"><?= __('email_addresses_placeholder') ?></label>
                <input type="text" class="form-control" id="emails" name="emails"
                    placeholder="user1@example.com, user2@example.com" required>
            </div>
            <button type="submit" class="btn btn-primary"><?= __('send_invitations') ?></button>
        </form>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th><?= __('ID') ?></th>
                <th><?= __('Email') ?></th>
                <th><?= __('Role') ?></th>
                <th><?= __('Plan') ?? 'Plan' ?></th>
                <th><?= __('Subscription') ?? 'Subscription' ?></th>
                <th><?= __('Status') ?></th>
                <th><?= __('Joined') ?></th>
                <th><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['role']) ?></td>
                    <td>
                        <span class="badge bg-<?= $user['plan'] === 'pro' ? 'primary' : 'light text-dark' ?>">
                            <?= ucfirst($user['plan']) ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-<?= $user['subscription_status'] === 'active' ? 'success' : 'secondary' ?>">
                            <?= ucfirst($user['subscription_status']) ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($user['is_active']): ?>
                            <span class="badge bg-success">Active</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Disabled</span>
                        <?php endif; ?>
                    </td>
                    <td><?= $user['created_at'] ?></td>
                    <td>
                        <?php if ($user['id'] != \App\Core\Session::get('user_id')): ?>
                            <form action="/admin/users/toggle" method="POST" class="d-inline">
                                <?= \App\Core\Csrf::field() ?>
                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-outline-secondary">
                                    <?= $user['is_active'] ? 'Disable' : 'Enable' ?>
                                </button>
                            </form>

                            <form action="/admin/users/activate-subscription" method="POST" class="d-inline">
                                <?= \App\Core\Csrf::field() ?>
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-outline-success"
                                    onclick="return confirm('Manually activate Pro for this user?');">
                                    Activate Pro
                                </button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>