<?php


require_once '../app/Controllers/LoginController.php';

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