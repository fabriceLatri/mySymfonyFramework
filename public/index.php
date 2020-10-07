<?php

require __DIR__ . '/../vendor/autoload.php';

use Simplex\Framework;
use Simplex\StringResponseListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\EventListener\ErrorListener;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;

$request = Request::createFromGlobals();
$requestStack = new RequestStack();
$routes = require_once __DIR__ . '/../src/app.php';

$context = new RequestContext();

$urlMatcher = new UrlMatcher($routes, $context);


// ControllerResolver permet de retrouver le controller à appeller.
$controllerResolver = new ControllerResolver();

// ArgumentResolver permet d'envoyer les arguments demandés par la méthode appelée.
$argumentResolver = new ArgumentResolver();

$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(new RouterListener($urlMatcher, $requestStack));

// Ajout de la subscription d'erreurs

$listener = new ErrorListener(
  'Calendar\Controller\ErrorController::exception'
);
$dispatcher->addSubscriber($listener);
$dispatcher->addSubscriber(new StringResponseListener());

$framework = new Framework($dispatcher, $controllerResolver, $requestStack,  $argumentResolver);

$response = $framework->handle($request);



$response->send();
