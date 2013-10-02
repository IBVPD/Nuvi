<?php

namespace NS\SentinelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('NSSentinelBundle:Default:index.html.twig', array('name' => $name));
    }
}
