<?php

namespace NS\ImportBundle\Controller;

use \Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \Symfony\Component\HttpFoundation\Request;

/**
 * Description of ExportController
 *
 * @author gnat
 * @Route("/export")
 */
class ExportController extends Controller
{
    /**
     * @Route("/",name="exportIndex")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        return array();
    }
}
