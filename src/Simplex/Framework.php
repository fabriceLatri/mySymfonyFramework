<?php

namespace Simplex;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Calendar\Controller\LeapYearController;

class Framework
{
    protected $matcher;
    protected $controllerResolver;
    protected $argumentResolver;

    public function __construct(UrlMatcherInterface $matcher, ControllerResolverInterface $controllerResolver, ArgumentResolverInterface $argumentResolver)
    {
        $this->matcher = $matcher;
        $this->controllerResolver = $controllerResolver;
        $this->argumentResolver = $argumentResolver;
    }

    public function handle(Request $request)
    {
        // Récupération des elements de la requête selon le context défini.
        $this->matcher->getContext()->fromRequest($request);

        try {
            // Recherche la route depuis l'url donnée.
            $request->attributes->add($this->matcher->match($request->getPathInfo()));

            // Permet de résoudre le controller à instancier depuis routes.php grâce à la clé '_controller'.
            $controller = $this->controllerResolver->getController($request);

            /*  Permet de récupérer les arguments depuis la request->attributes (grâce à l'URL Matcher):

                Récupère la valeur de {argument} en pour l'injecter par réflexion à la méthode du controller invoquée.
            */
            $arguments =$this->argumentResolver->getArguments($request, $controller);

            return call_user_func_array($controller, $arguments);
        } catch (ResourceNotFoundException $exception) {
            return new Response('La page demandée n\'existe pas', 404);
        } catch (\Exception $exception) {
            return new Response('An error occured', 500);
        }
    }
}