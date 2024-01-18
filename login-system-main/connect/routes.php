<?php

// login-system-main/config/routes.php

use App\Controllers\Admin\AuthController;

// Define routes
$routes = [
    '/login-system-main/public/index.php/admin/login' => [AuthController::class, 'showLoginForm'],
    '/login-system-main/public/index.php/admin/login-process' => [AuthController::class, 'login'],
    '/login-system-main/public/index.php/admin/logout' => [AuthController::class, 'logout'],
    // Add more routes as needed
];

return $routes;
