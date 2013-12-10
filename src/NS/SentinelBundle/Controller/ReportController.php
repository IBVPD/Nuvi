<?php

namespace NS\SentinelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of ReportController
 *
 * @author gnat
 */
class ReportController extends Controller
{
    /**
     * @Template()
     */
    public function byCountryGraphAction()
    {
        return array('reports' => array( 
                                    array('name' => 'Canada','numberOfCases'=>30),
                                    array('name' => 'United States','numberOfCases'=>10),
                                    array('name' => 'Mexico','numberOfCases'=>50),
                                    ));
    }

    /**
     * @Template()
     */
    public function generalStatsAction()
    {
        $reports = $this->get('ns.model_manager')->getRepository("NSSentinelBundle:Meningitis")->getStats();
       
        return array('reports' => $reports);
    }
}
