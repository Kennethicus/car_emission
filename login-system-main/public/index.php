<?php

// login-system-main/public/index.php

// Define the base path for the project
define('BASE_PATH', __DIR__ . '/..');

// Load routes
$routes = require_once BASE_PATH . '/connect/routes.php';

// Get the current URL path
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Check if the requested path is defined in the routes
if (isset($routes[$currentPath])) {
    // Get the controller and method associated with the route
    [$controllerClass, $method] = $routes[$currentPath];

    // Construct the full path to the controller file
    $controllerFile = BASE_PATH . '/app/Controllers/Admin/AuthControllers.php';

    // Check if the file exists before including it
    if (file_exists($controllerFile)) {
        require_once $controllerFile;

        // Create an instance of the controller
        $controller = new $controllerClass();

        // Call the method
        $controller->$method();
    } else {
        // Handle 404 - Controller Not Found
        header('HTTP/1.0 404 Not Found');
        echo 'Controller Not Found';
    }
} else {
    // Handle 404 - Page Not Found
    header('HTTP/1.0 404 Not Found');
    echo '404 Not Found';
}
