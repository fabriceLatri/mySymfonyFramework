<?php

require __DIR__ . '/../vendor/autoload.php';

use Simplex\ContentLengthListener;
use Simplex\Framework;
use Simplex\GoogleListener;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\HttpCache\Esi;
use Symfony\Component\HttpKernel\HttpCache\HttpCache;
use Symfony\Component\HttpKernel\HttpCache\Store;

$request = Request::createFromGlobals();

$routes = require_once __DIR__ . '/../src/app.php';

$context = new RequestContext();
$context->fromRequest($request);

$urlMatcher = new UrlMatcher($routes, $context);

$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(new ContentLengthListener());
$dispatcher->addSubscriber(new GoogleListener());

// ControllerResolver permet de retrouver le controller à appeller.
$controllerResolver = new ControllerResolver();

// ArgumentResolver permet d'envoyer les arguments demandés par la méthode appelée.
$argumentResolver = new ArgumentResolver();

$framework = new Framework($dispatcher, $urlMatcher, $controllerResolver, $argumentResolver);
$framework = new HttpCache(
  $framework,
  new Store(__DIR__ . '/../cache'),
  new Esi(),
  ['debug' => true]
);
$response = $framework->handle($request)->send();
