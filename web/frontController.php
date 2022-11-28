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

$action = $_GET['action'] ?? 'home';

// On vérifie si l'action existe
Session::getInstance();


if (method_exists(ControllerQuestion::class, $action)) {
    ControllerQuestion::$action();
} else {
    ControllerQuestion::pageIntrouvable();
}

?>