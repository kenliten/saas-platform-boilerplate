<?php

require_once __DIR__ . '/../app/bootstrap.php';

use App\Seeds\PlanSeeder;
use App\Seeds\UserSeeder;

echo "Seeding database...\n";

PlanSeeder::seed();
UserSeeder::seedDemoUser();
// Add seeders as needed

echo "Seeding complete.\n";
