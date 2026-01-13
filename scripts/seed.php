<?php

require_once __DIR__ . '/../app/bootstrap.php';

use App\Core\Database;

$pdo = Database::getConnection();

echo "Seeding database...\n";

// 1. Create an Account
$slug = 'demo';
$name = 'Demo Account';
$plan = 'free';

$stmt = $pdo->prepare("SELECT id FROM accounts WHERE slug = ?");
$stmt->execute([$slug]);
$account = $stmt->fetch();

if (!$account) {
    $stmt = $pdo->prepare("INSERT INTO accounts (slug, name, plan) VALUES (?, ?, ?)");
    $stmt->execute([$slug, $name, $plan]);
    $accountId = $pdo->lastInsertId();
    echo "Created Account: $name ($slug)\n";
} else {
    $accountId = $account['id'];
    echo "Account already exists: $name\n";
}

// 2. Create a User
$email = 'admin@example.com';
$password = 'password';
$hash = password_hash($password, PASSWORD_DEFAULT);
$role = 'admin';

$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND account_id = ?");
$stmt->execute([$email, $accountId]);
$user = $stmt->fetch();

if (!$user) {
    $stmt = $pdo->prepare("INSERT INTO users (account_id, email, password_hash, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$accountId, $email, $hash, $role]);
    echo "Created User: $email / $password\n";
} else {
    echo "User already exists: $email\n";
}

// 3. Plans
$planModel = new \App\Models\Plan();
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

    $stmt = $pdo->prepare("INSERT INTO plans (slug, name, price, description, features) VALUES (?, ?, ?, ?, ?)");
    foreach ($plans as $plan) {
        $stmt->execute([$plan['slug'], $plan['name'], $plan['price'], $plan['description'], $plan['features']]);
    }
    echo "Plans seeded.\n";
}

echo "Seeding complete.\n";
