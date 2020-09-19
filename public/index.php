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
    // Recherche la route depuis l'url donnée.
    $resultat = ($urlMatcher->match($request->getPathInfo()));

    // Retrouver la classe à instancier (encore sous forme de string) dans le tableau associatif $resultat['_controller']:
    $className = substr($resultat['_controller'], 0, strpos($resultat['_controller'],'::'));

    // Retrouver la méthode à utiliser (encore sous forme de string) dans le tableau associatif $resultat['_controller']:
    $methodName = substr($resultat['_controller'], strpos($resultat['_controller'],'::') +2);

    // Creation du callable
    $callable = [new $className, $methodName];

    // var_dump($className, $methodName); die;

    $request->attributes->add($resultat);
    $response = call_user_func($callable, $request);

    // ob_start();
    // include __DIR__ . '/../src/pages/' . $_route . '.php';

    // $response = new Response(ob_get_clean());

} catch (ResourceNotFoundException $exception) {
    $response = new Response('La page demandée n\'existe pas', 404);
} catch (Exception $exception) {
    $response = new Response('An error occurred', 500);
}

$response->send();