<?php

require_once __DIR__ . '/ControllerViews.php';

$modelViews = new ControllerViews();
$ruta = $_GET['url'] ?? 'home/index';
$ruta = explode('/', filter_var($ruta, FILTER_SANITIZE_URL));

$controllerName = ucfirst($ruta[0]) . 'Controller';
$method = $ruta[1] ?? 'index';

$controllerFile = __DIR__ . '/../controllers/' . $controllerName . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controller = new $controllerName();

    if (method_exists($controller, $method)) {
        $controller->$method();
    } else {
        echo "Metodo '$method' no encontrado";
    }
} else {
    $modelViews->views('404');
}