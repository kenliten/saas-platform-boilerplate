<?php

require_once __DIR__ . '/../app/bootstrap.php';

use App\Models\Account;
use App\Models\User;

if (php_sapi_name() !== 'cli')
    exit;

echo "Create Admin User\n";
echo "-----------------\n";

$name = readline("Name: ");
$email = readline("Email: ");
$password = readline("Password: ");

if (empty($email) || empty($password)) {
    echo "Email and password are required.\n";
    exit(1);
}

// Check if email exists (simple check without loading Model completely if desired, but let's use PDO)
$userModel = new User();
if ($userModel->findByEmail($email)) {
    echo "User with this email already exists.\n";
    exit(1);
}

echo "Creating new Admin Account for this user...\n";
$accountModel = new Account();
$slug = 'admin-' . uniqid();
$accountId = $accountModel->create([
    'slug' => $slug,
    'name' => $name,
    'subscription_status' => 'active',
    'plan' => 'pro',
]);

$hash = password_hash($password, PASSWORD_DEFAULT);
$userId = $userModel->create([
    'name' => 'Admin',
    'account_id' => $accountId,
    'email' => $email,
    'password_hash' => $hash,
    'role' => 'admin',
]);

if ($userId) {
    echo "Admin created successfully.\n";
} else {
    echo "Failed to create admin.\n";
    exit(1);
}
