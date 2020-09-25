<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Routing\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

$request = Request::createFromGlobals();

$routes = require_once __DIR__ . '/../src/routes.php';

$context = new RequestContext();
$context->fromRequest($request);

$urlMatcher = new UrlMatcher($routes, $context);

// ControllerResolver permet de retrouver le controller à appeller.
$controllerResolver = new ControllerResolver();

// ArgumentResolver permet d'envoyer les arguments demandés par la méthode appelée.
$argumentResolver = new ArgumentResolver();


try {
    // Recherche la route depuis l'url donnée.
    $resultat = ($urlMatcher->match($request->getPathInfo()));
    $request->attributes->add($resultat);

    // Permet de retrouver le controller depuis routes.php grâce à la clé '_controller'. Attention, la value entre l'instance et la méthode doit être séparée par ::
    $controller = $controllerResolver->getController($request);

    /*  Permet de récupérer les arguments depuis la request->attributes (grâce à l'URL Matcher):

        Récupère la valeur de {name} en pour l'injecter par réflexion à la méthode du controller invoquée.
    */
    $arguments = $argumentResolver->getArguments($request, $controller);

    // call_user_func permet d'invoquer la méthode d'un objet par le système de callable [Nom de l'instance, Nom de la méthode].
    $response = call_user_func_array($controller, $arguments);

} catch (ResourceNotFoundException $exception) {
    $response = new Response('La page demandée n\'existe pas', 404);
} catch (Exception $exception) {
    $response = new Response('An error occurred', 500);
}

$response->send();