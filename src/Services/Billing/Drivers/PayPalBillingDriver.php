<?php

namespace App\Services\Billing\Drivers;

use App\Services\Billing\BillingInterface;

class PayPalBillingDriver implements BillingInterface
{
    protected $clientId;
    protected $secret;
    protected $baseUrl;
    protected $accessToken;

    public function __construct()
    {
        $this->clientId = getenv('PAYPAL_CLIENT_ID');
        $this->secret = getenv('PAYPAL_SECRET');
        $isLive = getenv('APP_ENV') === 'production';
        $this->baseUrl = $isLive ? 'https://api-m.paypal.com' : 'https://api-m.sandbox.paypal.com';
    }

    protected function getAccessToken()
    {
        if ($this->accessToken) return $this->accessToken;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl . '/v1/oauth2/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $this->clientId . ":" . $this->secret);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");

        $response = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($response, true);
        $this->accessToken = $json['access_token'] ?? null;
        return $this->accessToken;
    }

    protected function request($method, $endpoint, $data = [])
    {
        $token = $this->getAccessToken();
        if (!$token) return null;

        $ch = curl_init();
        $url = $this->baseUrl . $endpoint;

        $headers = [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        ];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return json_decode($response, true);
    }

    public function createCustomer(array $user)
    {
        // PayPal doesn't have a direct "Customer" object exactly like Stripe used for subscriptions via API easily
        // Usually you create a Product/Plan first. 
        // For simplicity, we return null or a placeholder, as the flow relies on the user approving the subscription.
        return null; 
    }

    public function createSubscription($customerId, $planId)
    {
        // In PayPal REST API, you create a subscription specifying the Plan ID.
        // Needs a return_url and cancel_url passed in 'application_context' usually
        $appUrl = getenv('APP_URL') ?: 'http://localhost:8000';
        
        $response = $this->request('POST', '/v1/billing/subscriptions', [
            'plan_id' => $planId,
            'application_context' => [
                'return_url' => $appUrl . '/dashboard?subscription_id=APPROVED',
                'cancel_url' => $appUrl . '/pricing',
            ]
        ]);
        
        return $response;
    }

    public function cancelSubscription($subscriptionId)
    {
        // /v1/billing/subscriptions/{id}/cancel
        $response = $this->request('POST', "/v1/billing/subscriptions/$subscriptionId/cancel", [
            'reason' => 'User requested cancellation'
        ]);
        
        // PayPal returns 204 No Content on success
        return true; 
    }

    public function getCheckoutUrl($planId)
    {
        // We can create the subscription object, and the response links contain the 'approve' href.
        $response = $this->createSubscription(null, $planId);
        if (isset($response['links'])) {
            foreach ($response['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return $link['href'];
                }
            }
        }
        return null;
    }
}
