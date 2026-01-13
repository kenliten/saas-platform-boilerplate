<?php

require_once __DIR__ . '/../app/bootstrap.php';

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Middlewares\AuthMiddleware;

use App\Controllers\DashboardController;
use App\Middlewares\CsrfMiddleware;
use App\Middlewares\RateLimitMiddleware;

use App\Controllers\HealthController;

$router = new Router();

// Auth Public
$router->get('/register', [\App\Controllers\AuthController::class, 'register']);
$router->post('/register', [\App\Controllers\AuthController::class, 'store']);
$router->get('/forgot-password', [\App\Controllers\AuthController::class, 'forgotPassword']);
$router->post('/forgot-password', [\App\Controllers\AuthController::class, 'sendResetLink']);

// Auth Protected
$router->get('/health', [HealthController::class, 'check']);
$router->get('/thank-you', [\App\Controllers\PageController::class, 'thankYou']); // Public
$router->get('/404', [\App\Controllers\PageController::class, 'notFound']); // Public

// Protected
$router->get('/notifications', [\App\Controllers\NotificationController::class, 'index'])->middleware(AuthMiddleware::class);
$router->get('/profile', [\App\Controllers\ProfileController::class, 'index'])->middleware(AuthMiddleware::class);
$router->post('/profile', [\App\Controllers\ProfileController::class, 'update'])->middleware(AuthMiddleware::class)->middleware(CsrfMiddleware::class);
$router->post('/profile/password', [\App\Controllers\ProfileController::class, 'updatePassword'])->middleware(AuthMiddleware::class)->middleware(CsrfMiddleware::class);

// Admin Routes
$router->get('/admin/users', [\App\Controllers\Admin\UserController::class, 'index'])->middleware(AuthMiddleware::class);
$router->post('/admin/users/toggle', [\App\Controllers\Admin\UserController::class, 'toggleStatus'])->middleware(AuthMiddleware::class)->middleware(CsrfMiddleware::class);

$router->get('/', [HomeController::class, 'index'])->middleware(AuthMiddleware::class);
$router->get('/dashboard', [DashboardController::class, 'index'])->middleware(AuthMiddleware::class);

$router->get('/login', [AuthController::class, 'login'])->middleware(RateLimitMiddleware::class);
$router->post('/login', [AuthController::class, 'authenticate'])
       ->middleware(RateLimitMiddleware::class)
       ->middleware(CsrfMiddleware::class);

$router->post('/logout', [AuthController::class, 'logout'])->middleware(CsrfMiddleware::class);

$router->dispatch();
