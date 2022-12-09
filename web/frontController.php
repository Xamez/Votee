<?php

use App\Votee\Controller\AbstractController;
use App\Votee\Controller\ControllerQuestion as ControllerQuestion;
use App\Votee\Model\HTTP\Session;

require_once __DIR__ . '/../src/Lib/Psr4AutoloaderClass.php';

// instantiate the loader
$loader = new App\Votee\Lib\Psr4AutoloaderClass();
// register the base directories for the namespace prefix
$loader->addNamespace('App\Votee', __DIR__ . '/../src');
// register the autoloader
$loader->register();

$controllerDefaut = 'question';
$controller = $_GET['controller'] ?? $controllerDefaut;

$action = $_GET['action'] ?? 'home';

$controllerClassName = 'App\Votee\Controller\Controller' . ucfirst($controller);
// On vérifie si l'action existe
Session::getInstance();

if (class_exists($controllerClassName)) {
    if (in_array($action,get_class_methods($controllerClassName))){
        $controllerClassName::$action();
    } else {
        ControllerQuestion::pageIntrouvable();
    }
} else {
    ControllerQuestion::pageIntrouvable();
}