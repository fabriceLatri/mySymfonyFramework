<?php

require __DIR__ . '/../vendor/autoload.php';

use Simplex\Framework;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;

function render_template(Request $request)
{
  extract($request->attributes->all(), EXTR_SKIP);
  ob_start();
  include sprintf(__DIR__ . '../src/pages/%s.php', $_route);

  return new Response(ob_get_clean());
}

$request = Request::createFromGlobals();

$routes = require_once __DIR__ . '/../src/app.php';

$context = new RequestContext();
$context->fromRequest($request);

$urlMatcher = new UrlMatcher($routes, $context);

// ControllerResolver permet de retrouver le controller à appeller.
$controllerResolver = new ControllerResolver();

// ArgumentResolver permet d'envoyer les arguments demandés par la méthode appelée.
$argumentResolver = new ArgumentResolver();

$framework = new Framework($urlMatcher, $controllerResolver, $argumentResolver);
$response = $framework->handle($request);

$response->send();
