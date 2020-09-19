<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class PageController
{
    public function about ()
    {
        // Intégrer du HTML:
        include __DIR__ . '/../pages/about.php';

        // Renvoyer la réponse:
        return new Response(ob_get_clean());
    }
}