<?php

require_once __DIR__ . '/../app/bootstrap.php';

use App\Controllers\Admin\NotificationSenderController;
use App\Controllers\BillingController;
use App\Controllers\NewsletterController;
use App\Controllers\WebhookController;
use App\Core\Router;

use App\Controllers\Admin\UserController;
use App\Controllers\AdminController;
use App\Controllers\ProfileController;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\PageController;
use App\Controllers\NotificationController;
use App\Controllers\HealthController;

use App\Middlewares\AuthMiddleware;
use App\Middlewares\AdminMiddleware;
use App\Middlewares\CsrfMiddleware;
use App\Middlewares\RateLimitMiddleware;

$router = new Router();
$router->globalMiddleware(\App\Middlewares\LanguageMiddleware::class);

// Auth Public
$router->get('/register', [AuthController::class, 'register']);
$router->post('/register', [AuthController::class, 'store']);
$router->get('/forgot-password', [AuthController::class, 'forgotPassword']);
$router->post('/forgot-password', [AuthController::class, 'sendResetLink']);
$router->get('/login', [AuthController::class, 'login'])->middleware(RateLimitMiddleware::class);
$router->post('/login', [AuthController::class, 'authenticate'])
       ->middleware(RateLimitMiddleware::class)
       ->middleware(CsrfMiddleware::class);

// Admin Routes

$router->get('/health', [HealthController::class, 'check'])
       ->middleware(AuthMiddleware::class)
       ->middleware(AdminMiddleware::class);
$router->get('/admin', [AdminController::class, 'index'])
       ->middleware(AdminMiddleware::class);
$router->get('/admin/users', [UserController::class, 'index'])
       ->middleware(AdminMiddleware::class);
$router->post('/admin/users/toggle', [UserController::class, 'toggleStatus'])
       ->middleware(AdminMiddleware::class)
       ->middleware(CsrfMiddleware::class);
$router->post('/admin/users/invite', [UserController::class, 'invite'])
       ->middleware(AuthMiddleware::class)
       ->middleware(CsrfMiddleware::class)
       ->middleware(AdminMiddleware::class);
$router->post('/admin/users/activate-subscription', [UserController::class, 'activateSubscription'])
       ->middleware(AuthMiddleware::class)
       ->middleware(CsrfMiddleware::class)
       ->middleware(AdminMiddleware::class);
$router->get('/admin/notifications', [NotificationSenderController::class, 'index'])
       ->middleware(AuthMiddleware::class)
       ->middleware(AdminMiddleware::class);
$router->post('/admin/notifications', [NotificationSenderController::class, 'store'])
       ->middleware(AuthMiddleware::class)
       ->middleware(CsrfMiddleware::class)
       ->middleware(AdminMiddleware::class);

// Newsletter
$router->post('/newsletter/subscribe', [NewsletterController::class, 'subscribe']);
$router->get('/newsletter/verify', [NewsletterController::class, 'verify']);
$router->get('/newsletter/unsubscribe', [NewsletterController::class, 'unsubscribe']);
$router->post('/newsletter/unsubscribe', [NewsletterController::class, 'unsubscribe']);

// Public Pages
$router->get('/', [HomeController::class, 'index']);
$router->get('/pricing', [HomeController::class, 'pricing']);
$router->get('/about', [HomeController::class, 'about']);
$router->get('/contact', [HomeController::class, 'contact']);
$router->post('/contact', [HomeController::class, 'sendMessage'])
       ->middleware(CsrfMiddleware::class);
$router->get('/faq', [HomeController::class, 'faq']);

$router->get('/thank-you', [PageController::class, 'thankYou']);
$router->get('/404', [PageController::class, 'notFound']);

$router->get('/pricing', [BillingController::class, 'pricing']);
$router->post('/webhook/paypal', [WebhookController::class, 'paypal']);

// Auth Protected Routes
$router->get('/buy', [BillingController::class, 'buy'])->middleware(AuthMiddleware::class);
$router->get('/dashboard', [DashboardController::class, 'index'])->middleware(AuthMiddleware::class);
$router->get('/notifications', [NotificationController::class, 'index'])->middleware(AuthMiddleware::class);

// Auth Related Protected Routes
$router->get('/profile', [ProfileController::class, 'index'])->middleware(AuthMiddleware::class);
$router->post('/profile', [ProfileController::class, 'update'])->middleware(AuthMiddleware::class)->middleware(CsrfMiddleware::class);
$router->post('/profile/password', [ProfileController::class, 'updatePassword'])->middleware(AuthMiddleware::class)->middleware(CsrfMiddleware::class);
$router->post('/logout', [AuthController::class, 'logout'])->middleware(CsrfMiddleware::class);

$router->dispatch();
