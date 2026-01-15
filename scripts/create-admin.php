<?php

require_once __DIR__ . '/../app/bootstrap.php';

use App\Core\Database;
use App\Models\User;

if (php_sapi_name() !== 'cli') exit;

echo "Create Admin User\n";
echo "-----------------\n";

$email = readline("Email: ");
$password = readline("Password: ");

if (empty($email) || empty($password)) {
    echo "Email and password are required.\n";
    exit(1);
}

$pdo = Database::getConnection();

// Check if email exists (simple check without loading Model completely if desired, but let's use PDO)
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    echo "User with this email already exists.\n";
    exit(1);
}

// Find an account to attach to, or create one?
// For simpler admin creation, let's look for a default account or ask for Account ID.
// We'll create a new Admin Account for this user.
echo "Creating new Admin Account for this user...\n";
$stmt = $pdo->prepare("INSERT INTO accounts (slug, name, plan) VALUES (?, ?, ?)");
$slug = 'admin-' . uniqid();
$stmt->execute([$slug, 'Admin Account', 'enterprise']);
$accountId = $pdo->lastInsertId();

$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO users (account_id, email, password_hash, role) VALUES (?, ?, ?, ?)");
$stmt->execute([$accountId, $email, $hash, 'admin']);

echo "Admin created successfully.\n";
