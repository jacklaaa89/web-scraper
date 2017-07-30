<?php

use Example\Controller\IndexController;
use Example\Core\Route;

/**
 * Routes are defined in Route objects, which makes it easier to read and makes routes concrete
 * objects.
 */
return [
    //pattern, controller class & action.
    new Route('/', IndexController::class, 'index', 'GET'),
    new Route('/scrape', IndexController::class, 'scrape', 'POST'),
];
