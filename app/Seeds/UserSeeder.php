<?php

namespace App\Seeds;

use App\Models\Account;
use App\Models\User;

class UserSeeder
{
    public static function seedDemoUser()
    {
        $userModel = new User();
        $email = env('DEMO_EMAIL');
        $user = $userModel->findByEmail($email);

        if ($user) {
            echo "User already exists: $email\n";
            return;
        }

        $accountModel = new Account();
        $account = $accountModel->findByColumn('slug', 'demo');
        $name = env('DEMO_NAME', 'Demo Account');

        if (!$account) {
            $accountId = $accountModel->create([
                'slug' => 'demo',
                'name' => $name,
                'subscription_status' => 'active',
                'plan' => 'pro' # The highest default plan, update if the plans are not the default
            ]);
            echo "Created Account: $name (demo)\n";
        } else {
            $accountId = $account['id'];
            echo "Account already exists: $name\n";
        }

        $password = env('DEMO_PASSWORD');
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $role = 'admin';

        if (!$user) {
            $userModel->create([
                'account_id' => $accountId,
                'email' => $email,
                'name' => $name,
                'password_hash' => $hash,
                'role' => $role
            ]);
            echo "Created User: $email / $password\n";
        } else {
            echo "User already exists: $email\n";
        }
    }
}
