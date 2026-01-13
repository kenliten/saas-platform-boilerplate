<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?= __('User Management') ?></h1>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th><?= __('ID') ?></th>
                <th><?= __('Email') ?></th>
                <th><?= __('Role') ?></th>
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
                    <?php if($user['is_active']): ?>
                        <span class="badge bg-success">Active</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Disabled</span>
                    <?php endif; ?>
                </td>
                <td><?= $user['created_at'] ?></td>
                <td>
                    <?php if($user['id'] != \App\Core\Session::get('user_id')): ?>
                    <form action="/admin/users/toggle" method="POST" class="d-inline">
                        <?= \App\Core\Csrf::field() ?>
                        <input type="hidden" name="id" value="<?= $user['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                            <?= $user['is_active'] ? 'Disable' : 'Enable' ?>
                        </button>
                    </form>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
