<?php

namespace NS\AceBundle\Listener;

use \Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Description of AccessDenied
 *
 * @author gnat
 */
class AccessDenied implements AccessDeniedHandlerInterface
{
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        return new \Symfony\Component\HttpFoundation\Response("Access Denied!"); // $this->twig->render("NSAceBundle:Exceptions:AccessDenied.html.twig");
    }
}
