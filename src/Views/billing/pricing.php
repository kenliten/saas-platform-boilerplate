<?php
$title = __('nav_pricing');
?>

<!DOCTYPE html>
<html lang="<?= current_locale() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    
    <div class="container py-5">
        <?php include __DIR__ . '/_pricing_content.php'; ?>
        
        <div class="text-center mt-5">
            <a href="/" class="text-decoration-none text-muted">&larr; <?= __('back_home') ?></a>
        </div>
    </div>

</body>
</html>
