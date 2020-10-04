<?php

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection;

$routes->add('leap_year', new Route('/is_leap_year/{year}', [
    'year' => null,
    '_controller' => 'Calendar\Controller\LeapYearController::index'
]));

$routes->add('hello', new Route('/{name}', [
    'name' => 'World',
    '_controller' => 'Calendar\Controller\HelloController::index'
]));

return $routes;
