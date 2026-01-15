<?php include_once 'header.php' ?>
<div class="container">
    <div class="pricing-header p-3 pb-md-4 mx-auto text-center">
        <h1 class="display-4 fw-normal text-body-emphasis"><?= __('pricing') ?></h1>
        <p class="fs-5 text-body-secondary">
            <?= __('pricing_description') ?>
        </p>
    </div>
    <div class="row mb-3 text-center">
        <?php foreach ($plans as $plan): ?>
            <div class="col">
                <div class="card mb-4 rounded-3 shadow-sm" style="height: 100%">
                    <div class="card-header py-3">
                        <h4 class="my-0 fw-normal"><?= $plan['name'] ?></h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column justify-content-between align-items-center h-100">
                            <h1 class="card-title pricing-card-title">
                                $
                                <?= $plan['price'] ?><small class="text-body-secondary fw-light">/mo</small>
                            </h1>
                            <div class="card-text">
                                <?= $plan['description'] ?>
                            </div>
                            <ul class="list-unstyled mt-3 mb-4">
                                <?php foreach (json_decode($plan['features'], true) as $feature): ?>
                                    <li class="mb-1">
                                        <i class="bi bi-check"></i>
                                        <?= $feature ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php if ($plan['price'] == 0): ?>
                                <button type="button" class="w-100 btn btn-lg btn-outline-primary">
                                    Sign up for free
                                </button>
                            <?php else: ?>
                                <button type="button" class="w-100 btn btn-lg btn-primary">
                                    Get started
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <p class="text-center text-body-secondary">
        <?= __('pricing_notes') ?>
    </p>
</div>
<?php

include_once 'newsletter-subscriber.php';
include_once 'footer.php';