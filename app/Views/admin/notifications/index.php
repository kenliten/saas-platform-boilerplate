<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?= __('admin_notifications_title') ?></h1>
</div>

<?php if (\App\Core\Session::has('flash_success')): ?>
    <div class="alert alert-success"><?= e(\App\Core\Session::get('flash_success')); \App\Core\Session::remove('flash_success'); ?></div>
<?php endif; ?>

<?php if (\App\Core\Session::has('flash_error')): ?>
    <div class="alert alert-danger"><?= e(\App\Core\Session::get('flash_error')); \App\Core\Session::remove('flash_error'); ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-md-5">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white fw-bold"><?= __('admin_send_global') ?></div>
            <div class="card-body">
                <form action="/admin/notifications" method="POST">
                    <?= \App\Core\Csrf::field() ?>
                    <div class="mb-3">
                        <label class="form-label"><?= __('type') ?></label>
                        <select class="form-select" name="type">
                            <option value="news"><?= __('label_notify_news') ?></option>
                            <option value="marketing"><?= __('label_notify_marketing') ?></option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= __('title') ?></label>
                        <input type="text" class="form-control" name="subject" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= __('admin_msg_html') ?></label>
                        <textarea class="form-control" name="message" rows="5" required></textarea>
                    </div>
                    <div class="form-text mb-3 small text-muted"><?= __('admin_delivery_info') ?></div>
                    <button type="submit" class="btn btn-primary w-100"><?= __('admin_btn_queue') ?></button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-bold"><?= __('admin_recently_queued') ?></div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th><?= __('type') ?></th>
                            <th><?= __('title') ?></th>
                            <th><?= __('date') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($announcements)): ?>
                            <tr><td colspan="3" class="text-center py-3 text-muted"><?= __('no_data_month') ?></td></tr>
                        <?php else: ?>
                            <?php foreach ($announcements as $a): ?>
                            <tr>
                                <td><span class="badge bg-<?= $a['type'] === 'news' ? 'info' : 'warning text-dark' ?>"><?= ucfirst($a['type']) ?></span></td>
                                <td><?= htmlspecialchars($a['subject']) ?></td>
                                <td class="small text-muted"><?= date('M d, Y', strtotime($a['created_at'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>