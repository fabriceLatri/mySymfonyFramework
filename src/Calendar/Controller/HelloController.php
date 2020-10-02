<?php

namespace Calendar\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HelloController
{
    public function index(Request $request, string $name) : Response
    {
        $name = $request->attributes->get('name');

        ob_start();
        include __DIR__ .'/../pages/hello.php';

        return new Response(ob_get_clean());

    }
}