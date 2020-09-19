<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

$request = Request::createFromGlobals();

$routes = require_once __DIR__ . '/../src/routes.php';

$context = new RequestContext();
$context->fromRequest($request);

$urlMatcher = new UrlMatcher($routes, $context);

try {
    // extract($urlMatcher->match($request->getPathInfo()));
    $resultat = ($urlMatcher->match($request->getPathInfo()));

    $request->attributes->add($resultat);
    $response = call_user_func($resultat['_controller'], $request);

    // ob_start();
    // include __DIR__ . '/../src/pages/' . $_route . '.php';

    // $response = new Response(ob_get_clean());

} catch (ResourceNotFoundException $exception) {
    $response = new Response('La page demandée n\'existe pas', 404);
} catch (Exception $exception) {
    $response = new Response('An error occurred', 500);
}

$response->send();