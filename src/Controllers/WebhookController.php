<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Database;

class WebhookController extends BaseController
{
    public function paypal()
    {
        // 1. Read Payload
        $payload = file_get_contents('php://input');
        $data = json_decode($payload, true);
        
        if (!$data) {
            http_response_code(400);
            exit;
        }

        $eventType = $data['event_type'] ?? '';
        $resource = $data['resource'] ?? [];
        $subscriptionId = $resource['id'] ?? null;
        
        // Sometimes ID is inside billing_agreement_id depending on event
        if (!$subscriptionId && isset($resource['billing_agreement_id'])) {
            $subscriptionId = $resource['billing_agreement_id'];
        }

        if ($subscriptionId) {
            $db = Database::getInstance();
            
            switch ($eventType) {
                case 'BILLING.SUBSCRIPTION.ACTIVATED':
                case 'BILLING.SUBSCRIPTION.RE-ACTIVATED':
                case 'PAYMENT.SALE.COMPLETED':
                    $status = 'active';
                    $plan = 'pro';
                    
                    // Try to get next billing date
                    $nextBilling = null;
                    if (isset($resource['billing_info']['next_billing_time'])) {
                        $nextBilling = date('Y-m-d', strtotime($resource['billing_info']['next_billing_time']));
                    }
                    
                    $sql = "UPDATE accounts SET subscription_status = ?, plan = ?";
                    $params = [$status, $plan];
                    
                    if ($nextBilling) {
                        $sql .= ", next_billing_date = ?";
                        $params[] = $nextBilling;
                    }
                    
                    $sql .= " WHERE subscription_id = ?";
                    $params[] = $subscriptionId;
                    
                    $db->query($sql, $params);
                    break;

                case 'BILLING.SUBSCRIPTION.CANCELLED':
                case 'BILLING.SUBSCRIPTION.SUSPENDED':
                case 'BILLING.SUBSCRIPTION.EXPIRED':
                    $status = 'cancelled'; // or suspended
                    $plan = 'free'; // Downgrade?
                    
                    $db->query("UPDATE accounts SET subscription_status = ?, plan = ? WHERE subscription_id = ?", [$status, $plan, $subscriptionId]);
                    break;
                    
                case 'PAYMENT.SALE.DENIED':
                    $db->query("UPDATE accounts SET subscription_status = 'inactive' WHERE subscription_id = ?", [$subscriptionId]);
                    break;
            }
        }

        http_response_code(200);
    }
}
