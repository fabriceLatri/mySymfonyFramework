<?php

namespace Calendar\Tests;

use Simplex\Framework;
use PHPUnit\Framework\TestCase;
use Calendar\Controller\HelloController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;

class HelloTest extends TestCase
{
  public function testHelloWithNameGiven()
  {
    $matcher = $this->createMock(UrlMatcherInterface::class);

    $matcher
      ->expects($this->once())
      ->method('match')
      ->will($this->returnValue([
        '_route' => '/{name}',
        'name' => 'Fabrice',
        '_controller' => [new HelloController(), 'index']
      ]));

    $matcher
      ->expects($this->once())
      ->method('getContext')
      ->will($this->returnValue($this->createMock(RequestContext::class)));

    $controllerResolver = new ControllerResolver();
    $argumentResolver = new ArgumentResolver();

    $framework = new Framework($matcher, $controllerResolver, $argumentResolver);

    $response = $framework->handle(new Request());

    $this->assertEquals(200, $response->getStatusCode());
  }
}
