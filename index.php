<?php

// require_once '/var/www/html/MVC_EMPLOYEE/app/Controllers/LoginController.php';

// $controllerName = $_GET['controller'] ?? 'login';
// $action = $_GET['action'] ?? 'showLoginForm';

// $controller = null;

// if ($controllerName === 'login') {
//     $controller = new LoginController();
// }

// if ($controller && method_exists($controller, $action)) {
//     $controller->$action();
// } else {
//     echo "404 - Page Not Found";
// }


require_once '/var/www/html/MVC_PROJECTPRACTICE/app/Controllers/LoginController.php';

$controllerName = $_GET['controller'] ?? 'login';
$action = $_GET['action'] ?? 'showLoginForm';

$controller = null;

if ($controllerName === 'login') {
    $controller = new LoginController();
}

if ($controller && method_exists($controller, $action)) {
    $controller->$action();
} else {
    echo "404 - Page Not Found";
}


?>



