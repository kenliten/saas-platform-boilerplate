<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Notifications</h1>
</div>

<div class="list-group">
    <?php if (empty($notifications)): ?>
        <div class="list-group-item">No notifications found.</div>
    <?php else: ?>
        <?php foreach ($notifications as $notif): ?>
            <div class="list-group-item list-group-item-action <?= $notif['is_read'] ? 'bg-light' : '' ?>">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1 text-<?= htmlspecialchars($notif['type'] == 'error' ? 'danger' : ($notif['type'] == 'success' ? 'success' : 'primary')) ?>">
                        <?= ucfirst(htmlspecialchars($notif['type'])) ?>
                    </h5>
                    <small class="text-muted"><?= $notif['created_at'] ?></small>
                </div>
                <p class="mb-1"><?= htmlspecialchars($notif['message']) ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
