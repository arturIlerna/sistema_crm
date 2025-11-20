<?php
// index.php - Enrutador principal

require_once 'core/db_connection.php';
require_once 'core/functions.php';

$controllerName = ucfirst($_GET['controller'] ?? 'auth') . 'Controller';
$actionName = $_GET['action'] ?? 'login';

$controllerFile = 'controllers/' . $controllerName . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    if (class_exists($controllerName)) {
        $controller = new $controllerName();
        if (method_exists($controller, $actionName)) {
            $controller->$actionName();
        } else {
            die('Acción no encontrada');
        }
    } else {
        die('Controlador no encontrado');
    }
} else {
    // Fallback a login si no se especifica controlador y no está logueado
    if (!is_logged_in() && $controllerName === 'AuthController') {
        require_once 'controllers/AuthController.php';
        $controller = new AuthController();
        $controller->login();
    } else {
        die('Controlador no encontrado');
    }
}
?>