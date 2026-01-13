<?php

namespace App\Services\Billing\Drivers;

use App\Services\Billing\BillingInterface;

class StripeBillingDriver implements BillingInterface
{
    protected $secretKey;
    protected $baseUrl = 'https://api.stripe.com/v1';

    public function __construct()
    {
        $this->secretKey = getenv('STRIPE_SECRET_KEY');
    }

    protected function request($method, $endpoint, $data = [])
    {
        $ch = curl_init();
        $url = $this->baseUrl . $endpoint;

        $postFields = http_build_query($data);

        if ($method === 'GET' && !empty($data)) {
            $url .= '?' . $postFields;
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $this->secretKey . ':');
        
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        } elseif ($method === 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }

        $response = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $json = json_decode($response, true);
        
        if ($code >= 400) {
            // In a real app, throw a specific exception or log error
            return null;
        }

        return $json;
    }

    public function createCustomer(array $user)
    {
        $response = $this->request('POST', '/customers', [
            'email' => $user['email'],
            'name' => $user['name'] ?? '',
            'metadata' => ['user_id' => $user['id']]
        ]);

        return $response['id'] ?? null;
    }

    public function createSubscription($customerId, $planId)
    {
        // This assumes $planId is a Stripe Price ID
        $response = $this->request('POST', '/subscriptions', [
            'customer' => $customerId,
            'items' => [['price' => $planId]],
        ]);

        return $response;
    }

    public function cancelSubscription($subscriptionId)
    {
        $response = $this->request('DELETE', '/subscriptions/' . $subscriptionId);
        return isset($response['status']) && $response['status'] === 'canceled';
    }

    public function getCheckoutUrl($planId)
    {
        // Creates a Stripe Checkout Session
        // Requires success_url and cancel_url
        $appUrl = getenv('APP_URL') ?: 'http://localhost:8000';
        
        $response = $this->request('POST', '/checkout/sessions', [
            'line_items' => [['price' => $planId, 'quantity' => 1]],
            'mode' => 'subscription',
            'success_url' => $appUrl . '/dashboard?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $appUrl . '/pricing',
        ]);

        return $response['url'] ?? null;
    }
}
