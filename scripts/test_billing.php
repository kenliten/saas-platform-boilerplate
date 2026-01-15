<?php

require_once __DIR__ . '/../app/bootstrap.php';

use App\Services\Billing\BillingService;

echo "Current Billing Driver: " . getenv('BILLING_DRIVER') . "\n";

$billing = BillingService::getDriver();
echo "Driver Class: " . get_class($billing) . "\n";

$user = ['id' => 1, 'email' => 'test@example.com', 'name' => 'Test User'];

// Test Customer Creation
try {
    echo "Creating Customer...\n";
    $customerId = $billing->createCustomer($user);
    echo "Customer ID: " . ($customerId ?? 'NULL (Expected for some drivers)') . "\n";
} catch (Exception $e) {
    echo "Error creating customer: " . $e->getMessage() . "\n";
}

// Test Checkout URL (Dry run)
try {
    echo "Getting Checkout URL for plan 'price_test'...\n";
    $url = $billing->getCheckoutUrl('price_test');
    echo "URL: " . ($url ?? 'NULL (Expected without keys)') . "\n";
} catch (Exception $e) {
    echo "Error getting URL: " . $e->getMessage() . "\n";
}
