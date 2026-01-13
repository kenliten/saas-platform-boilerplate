<?php

namespace App\Services\Billing;

interface BillingInterface
{
    /**
     * Create a customer in the billing provider.
     * 
     * @param array $user User data (email, name, id)
     * @return string|null External Customer ID
     */
    public function createCustomer(array $user);

    /**
     * Create a subscription for a customer.
     * 
     * @param string $customerId External Customer ID
     * @param string $planId Internal Plan ID or Price ID
     * @return array|null Subscription details
     */
    public function createSubscription($customerId, $planId);

    /**
     * Cancel a subscription.
     * 
     * @param string $subscriptionId External Subscription ID
     * @return bool Success
     */
    public function cancelSubscription($subscriptionId);
    
    /**
     * Get checkout URL for a plan (for hosted pages like Stripe Checkout).
     * 
     * @param string $planId
     * @return string|null URL
     */
    public function getCheckoutUrl($planId);
}
