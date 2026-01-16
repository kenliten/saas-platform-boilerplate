<div class="bg-warning" id="newsletter">
    <div class="container py-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <div class="text-center text-md-start text-dark">
                <h6 class=" mb-3">
                    <?= __('newsletter') ?>
                </h6>
                <p class="small">
                    <?= __('newsletter_description') ?>
                </p>
            </div>
            <div class="text-center text-md-end">
                <form action="/newsletter/subscribe" method="POST" class="d-flex gap-2">
                    <?= \App\Core\Csrf::field() ?>
                    <input type="email" name="email" class="form-control form-control-lg"
                        placeholder="<?= __('newsletter_email_placeholder') ?>" required>
                    <button class="btn btn-primary btn-lg" type="submit">
                        <?= __('newsletter_subscribe') ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>