<?php

namespace App\Seeds;

use App\Models\Plan;

class PlanSeeder
{
    public static function seed()
    {
        $planModel = new Plan();
        if (empty($planModel->all())) {
            echo "Seeding Plans...\n";
            $plans = [
                [
                    'slug' => 'free',
                    'name' => 'Free',
                    'price' => 0.00,
                    'description' => 'For those just starting out.',
                    'features' => json_encode(['1 User', 'Basic Support'])
                ],
                [
                    'slug' => 'basic',
                    'name' => 'Basic',
                    'price' => 9.99,
                    'description' => 'Essential features for small teams.',
                    'features' => json_encode(['5 Users', 'Email Support', '1GB Storage'])
                ],
                [
                    'slug' => 'plus',
                    'name' => 'Plus',
                    'price' => 29.99,
                    'description' => 'Everything you need to grow.',
                    'features' => json_encode(['20 Users', 'Priority Support', '10GB Storage', 'Analytics'])
                ],
                [
                    'slug' => 'pro',
                    'name' => 'Pro',
                    'price' => 99.99,
                    'description' => 'Advanced features for scaling businesses.',
                    'features' => json_encode(['Unlimited Users', '24/7 Support', 'Unlimited Storage', 'AI Features'])
                ]
            ];

            foreach ($plans as $plan) {
                $planModel->create($plan);
            }
            echo "Plans seeded.\n";
        }
    }
}
