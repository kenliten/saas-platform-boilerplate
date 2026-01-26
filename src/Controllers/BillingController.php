<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Session;
use App\Core\Database;
use App\Services\Billing\Drivers\PayPalBillingDriver;

class BillingController extends BaseController
{
    private $paypal;
    private $planId = 'P-55W68810E5797325NNFTPT5I'; // Hardcoded as per request

    public function __construct()
    {
        $this->paypal = new PayPalBillingDriver();
    }

    public function pricing()
    {
        // If user is already active, redirect to dashboard or profile
        if (Session::has('user_id')) {
            $status = user_payment_status(); // Helper function check
            if ($status === 'active') {
                $this->redirect('/dashboard');
            } else {
                $this->redirect('/buy');
            }
        }
        
        $this->view('billing/pricing', [], null);
    }

    public function buy()
    {
        // Must be logged in to reach here via middleware, but check anyway
        if (!Session::has('user_id')) {
            $this->redirect('/login');
        }

        $this->view('billing/buy', [], 'dashboard');
    }

    public function subscribe()
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
        }

        $url = $this->paypal->getCheckoutUrl($this->planId);
        
        if ($url) {
            $this->redirect($url);
        } else {
            // Handle error (e.g., config missing)
            $this->view('billing/pricing', ['error' => 'Unable to connect to payment provider.']);
        }
    }

    public function success()
    {
        // PayPal redirects here with subscription_id
        $subscriptionId = $_GET['subscription_id'] ?? null;
        
        if (!$subscriptionId) {
            $this->redirect('/pricing');
        }

        // Verify status with PayPal
        $details = $this->paypal->getSubscriptionDetails($subscriptionId);
        
        if ($details && in_array($details['status'], ['ACTIVE', 'APPROVED'])) {
             // Activate Account
             $userId = Session::get('user_id');
             $user = user();
             
             $db = Database::getInstance();
             $sql = "UPDATE accounts SET subscription_id = ?, subscription_status = 'active', plan = 'pro', next_billing_date = ? WHERE id = ?";
             
             // Calculate next billing (approx +1 year)
             $nextBilling = date('Y-m-d', strtotime('+1 year'));
             if (isset($details['billing_info']['next_billing_time'])) {
                 $nextBilling = date('Y-m-d', strtotime($details['billing_info']['next_billing_time']));
             }

             $db->query($sql, [$subscriptionId, $nextBilling, $user['account_id']]);
             
             $this->redirect('/dashboard?msg=welcome_pro');
        } else {
             $this->redirect('/pricing?error=payment_failed');
        }
    }

    public function cancel()
    {
        $user = user();
        $db = Database::getInstance();
        $stmt = $db->query("SELECT subscription_id FROM accounts WHERE id = ?", [$user['account_id']]);
        $subId = $stmt->fetchColumn();

        if ($subId) {
            $this->paypal->cancelSubscription($subId);
            
            // Update DB
            $db->query("UPDATE accounts SET subscription_status = 'cancelled' WHERE id = ?", [$user['account_id']]);
        }

        $this->redirect('/profile');
    }
}
