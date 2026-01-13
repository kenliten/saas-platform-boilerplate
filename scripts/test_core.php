<?php

require_once __DIR__ . '/../app/bootstrap.php';

use App\Core\Router;
use App\Core\Database;

echo "Testing Autoloading...\n";

if (class_exists(Router::class)) {
    echo "Router class found.\n";
} else {
    echo "Router class NOT found.\n";
    exit(1);
}

if (class_exists(Database::class)) {
    echo "Database class found.\n";
} else {
    echo "Database class NOT found.\n";
    exit(1);
}

echo "Framework instantiated successfully.\n";
