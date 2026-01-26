<?php

namespace App\Services\Billing\Drivers;

use App\Services\Billing\BillingInterface;

class NoneBillingDriver implements BillingInterface
{
    public function createCustomer(array $user)
    {
        return 'local_customer_' . $user['id'];
    }

    public function createSubscription($customerId, $planId)
    {
        return [
            'id' => 'sub_local_' . uniqid(),
            'status' => 'active',
            'plan' => $planId
        ];
    }

    public function cancelSubscription($subscriptionId)
    {
        return true;
    }

    public function getCheckoutUrl($planId)
    {
        return '#manual-upgrade';
    }
}
