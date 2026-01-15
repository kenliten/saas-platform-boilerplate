<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Dashboard') ?> - SaaS Platform</title>
    <!-- PayPal SDK -->
    <script
        src="https://www.paypal.com/sdk/js?client-id=<?= env('PAYPAL_CLIENT_ID') ?>&vault=true&intent=subscription"></script>
    <!-- Local Bootstrap -->
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Local Bootstrap Icons -->
    <link href="/assets/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            padding: 48px 0 0;
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
            overflow-y: auto;
        }

        main {
            margin-left: 240px;
            /* Sidebar width */
            padding-top: 60px;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            main {
                margin-left: 0;
            }
        }

        .bi {
            vertical-align: -.125em;
            fill: currentColor;
        }
    </style>
</head>

<body>

    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="/">SaaS Venture</a>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#sidebarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-nav w-100"></div>
        <div class="navbar-nav">
            <div class="nav-item text-nowrap d-flex align-items-center">
                <!-- Notifications Check -->
                <?php
                $notifModel = new \App\Models\Notification();
                $userId = \App\Core\Session::get('user_id');
                $hasUnread = !empty($notifModel->getUnreadForUser($userId));
                ?>
                <a class="nav-link px-3" href="/profile">
                    <i class="bi bi-person-circle"></i> Profile
                </a>
                <a class="nav-link px-3 position-relative" href="/notifications">
                    <i class="bi bi-bell"></i>
                    <?php if ($hasUnread): ?>
                        <span
                            class="position-absolute top-10 start-90 translate-middle p-1 bg-danger border border-light rounded-circle">
                            <span class="visually-hidden">New alerts</span>
                        </span>
                    <?php endif; ?>
                </a>
                <form action="/logout" method="POST" class="d-inline">
                    <?= \App\Core\Csrf::field() ?>
                    <button type="submit" class="nav-link px-3 bg-dark border-0">Sign out</button>
                </form>
            </div>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="/dashboard">
                                <i class="bi bi-speedometer2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-file-earmark-text"></i>
                                Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-people"></i>
                                Customers
                            </a>
                        </li>
                        <hr>
                        <li class="nav-item">
                            <a class="nav-link" href="/profile">
                                <i class="bi bi-person-badge"></i>
                                My Profile
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <?= $content ?>
            </main>
        </div>
    </div>

    <script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/chart.js/dist/chart.umd.js"></script> <!-- Local Chart.js -->
</body>

</html>