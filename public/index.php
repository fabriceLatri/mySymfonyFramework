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

$response = new Response();

$routes = new RouteCollection();

$routes->add('hello', new Route('/hello/{name}', ['name' => 'World']));
$routes->add('bye', new Route('/bye'));
$routes->add('cms/about', new Route('/a-propos'));

$context = new RequestContext();

$context->fromRequest($request);

$urlMatcher = new UrlMatcher($routes, $context);

$pathInfo = $request->getPathInfo();

try {
    $attributes = $urlMatcher->match($pathInfo);
    extract($attributes);
    extract($request->query->all());

    ob_start();
    include __DIR__ . '/../src/pages/' . $attributes['_route'] . '.php';
    $response->setContent(ob_get_clean());

} catch (ResourceNotFoundException $exception) {
        $response->setContent('La page demandée n\'existe pas');
        $response->setStatusCode(404);
} catch (Exception $exception) {
    $response = new Response('An error occurred', 500);
}

$response->send();