<!DOCTYPE html>
<html lang="<?= current_locale() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('unsubscribe_title') ?? 'Unsubscribe' ?></title>
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body p-5 text-center">
                        <h2 class="mb-4"><?= __('unsubscribe_header') ?? 'Unsubscribe from Newsletter' ?></h2>
                        <p class="text-muted mb-4"><?= __('unsubscribe_confirm') ?? 'Are you sure you want to stop receiving our updates?' ?></p>
                        <form action="/newsletter/unsubscribe" method="POST">
                            <?= \App\Core\Csrf::field() ?>
                            <input type="hidden" name="email" value="<?= e($email) ?>">
                            <div class="mb-3 text-start">
                                <label class="form-label"><?= __('Email') ?></label>
                                <input type="email" class="form-control" value="<?= e($email) ?>" disabled>
                            </div>
                            <button type="submit" class="btn btn-danger w-100"><?= __('btn_unsubscribe') ?? 'Unsubscribe' ?></button>
                        </form>
                        <div class="mt-4">
                            <a href="/" class="text-muted small"><?= __('back_home') ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
