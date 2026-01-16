<?php
$clientId = env('PAYPAL_CLIENT_ID');
$annualPlanId = 'P-55W68810E5797325NNFTPT5I';
$monthlyPlanId = 'P-3LM22945YM4299435NFTWAYQ';

$reason = $_GET['msg'] ?? null;
?>

<div class="row justify-content-center">
    <div class="col-lg-10 text-center mb-5">
        <h1 class="display-5 fw-bold mb-3"><?= __('get_started_now') ?></h1>
        <p class="lead text-muted"><?= __('choose_your_plan') ?></p>
        
        <?php if (is_pro()): ?>
            <div class="alert alert-info border-0 shadow-sm mt-4 d-inline-block px-5">
                <i class="bi bi-patch-check-fill me-2"></i> 
                <?= __('already_subscribed_msg') ?>
                <div class="mt-2">
                    <a href="/profile" class="btn btn-sm btn-primary rounded-pill"><?= __('go_to_settings') ?></a>
                </div>
            </div>
        <?php elseif ($reason === 'limit_reached' || $reason === 'upgrade_required'): ?>
            <div class="alert alert-warning border-0 shadow-sm mt-4 d-inline-block px-5">
                <i class="bi bi-info-circle-fill me-2"></i> 
                <?= ($reason === 'limit_reached') ? __('limit_reached_msg') : __('no_access_msg') ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="row justify-content-center g-4">
    <!-- Monthly Plan -->
    <div class="col-md-5">
        <div class="card h-100 border-0 shadow-sm rounded-3">
            <div class="card-header bg-light text-center py-4">
                <h5 class="mb-0 fw-bold"><?= __('monthly_pro') ?></h5>
            </div>
            <div class="card-body p-5 text-center">
                <div class="display-4 fw-bold mb-2">$2.99</div>
                <div class="text-muted mb-4"><?= __('plan_per_month') ?></div>
                <div id="paypal-button-container-monthly"></div>
            </div>
        </div>
    </div>

    <!-- Annual Plan -->
    <div class="col-md-5">
        <div class="card h-100 border-primary border-2 shadow-lg rounded-3">
            <div class="card-header bg-primary text-white text-center py-4">
                <h5 class="mb-0 fw-bold"><?= __('annual_pro') ?></h5>
                <span class="badge bg-warning text-dark"><?= __('Best Value') ?></span>
            </div>
            <div class="card-body p-5 text-center">
                <div class="display-4 fw-bold mb-2">$24.99</div>
                <div class="text-muted mb-4"><?= __('plan_per_year') ?></div>
                <div id="paypal-button-container-annual"></div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-12 text-center">
        <ul class="list-inline text-muted small">
            <li class="list-inline-item me-4"><i class="bi bi-check-circle text-success me-1"></i> <?= __('feature_all_access') ?></li>
            <li class="list-inline-item me-4"><i class="bi bi-check-circle text-success me-1"></i> <?= __('feature_unlimited_goals') ?></li>
            <li class="list-inline-item me-4"><i class="bi bi-check-circle text-success me-1"></i> <?= __('feature_financial_tracking') ?></li>
        </ul>
        <p class="mt-3 text-muted small"><?= __('secure_payment_paypal') ?></p>
    </div>
</div>

<?php if (\App\Core\Session::has('user_id')): ?>
<!-- PayPal SDK -->
<script src="https://www.paypal.com/sdk/js?client-id=<?= $clientId ?>&vault=true&intent=subscription"></script>
<script>
    if (typeof paypal !== 'undefined') {
        // Monthly Button
        paypal.Buttons({
            style: { shape: 'pill', color: 'blue', layout: 'vertical', label: 'subscribe' },
            createSubscription: function(data, actions) {
                return actions.subscription.create({ 'plan_id': '<?= $monthlyPlanId ?>' });
            },
            onApprove: function(data, actions) {
                window.location.href = "/billing/success?subscription_id=" + data.subscriptionID;
            }
        }).render('#paypal-button-container-monthly');

        // Annual Button
        paypal.Buttons({
            style: { shape: 'pill', color: 'gold', layout: 'vertical', label: 'subscribe' },
            createSubscription: function(data, actions) {
                return actions.subscription.create({ 'plan_id': '<?= $annualPlanId ?>' });
            },
            onApprove: function(data, actions) {
                window.location.href = "/billing/success?subscription_id=" + data.subscriptionID;
            }
        }).render('#paypal-button-container-annual');
    }
</script>
<?php endif; ?>