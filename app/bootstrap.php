<?php

use Example\Core\Application;
use Example\Core\RouteCollection;
use Example\Provider\ArrayServiceProvider;
use Example\Util\Config;
use Slim\Csrf\Guard;

/** @const string */
const DEBUG_ENABLED_KEY = 'slim.debug';

//include composers autoloader.
require_once APP_PATH . '/vendor/autoload.php';

//retrieve the config.
//At this point we could also retrieve config based on our current environment. i.e dev etc.
$configFiles = [
    APP_PATH . '/config/config.php',
    //APP_PATH . '/config/config_ . APP_ENV . '.php'
];

//Generate the configuration.
$config = new Config($configFiles);

//Enable error reporting if its defined in the configuration.
if ($config->get(DEBUG_ENABLED_KEY)) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

//This is the point where the application is bootstrapped.
$application = new Application($config);

//We provide our service definitions to the container.
$application->registerServiceProvider(
    new ArrayServiceProvider(require APP_PATH . '/app/services.php')
);

//We provide a collection of routes to handle.
$application->registerRoutes(new RouteCollection(require APP_PATH . '/app/routes.php'));

//Run the application.
$application->run();
