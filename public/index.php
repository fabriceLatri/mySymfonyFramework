<?php

require __DIR__ . '/../vendor/autoload.php';


use Simplex\StringResponseListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\DependencyInjection\Reference;



$routes = require_once __DIR__ . '/../src/app.php';
$container = include __DIR__ . '/../src/container.php';


$request = Request::createFromGlobals();

$container->register('listener.string_response', StringResponseListener::class);
$container->getDefinition('dispatcher')
  ->addMethodCall('addSubscriber', [new Reference('listener.string_response')]);

$container->setParameter('debug', true);

$container->register('matcher', UrlMatcher::class)
  ->setArguments(['%routes%', new Reference('context')]);

$container->setParameter('routes', include __DIR__.'/../src/app.php');


$response = $container->get('framework')->handle($request);


$response->send();
