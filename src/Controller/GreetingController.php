<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;



class GreetingController {
    public function hello (Request $request)
    {
        // Intégrer du HTML:
        ob_start();
        $name = $request->attributes->get('name');
        include __DIR__ . '/../pages/hello.php';

        // Renvoyer la réponse:
        return new Response(ob_get_clean());
    }

    public function bye ()
    {
        // Intégrer du HTML:
        ob_start();
        include __DIR__ . '/../pages/bye.php';

        // Renvoyer la réponse:
        return new Response(ob_get_clean());
    }
}