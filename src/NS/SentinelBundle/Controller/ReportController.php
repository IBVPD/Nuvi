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
        $reports = $this->get('ns.model_manager')->getRepository("NSSentinelBundle:Meningitis")->getByCountry();

        return array('reports' => $reports);
    }

    /**
     * @Template()
     */
    public function bySiteGraphAction()
    {
        $reports = $this->get('ns.model_manager')->getRepository("NSSentinelBundle:Meningitis")->getBySite();

        return array('reports' => $reports);
    }

    /**
     * @Template()
     */
    public function generalStatsAction()
    {
        $reports = $this->get('ns.model_manager')->getRepository("NSSentinelBundle:Meningitis")->getStats();
       
        return array('reports' => $reports);
    }
    
    /**
     * @Template()
     */
    public function byDiagnosisGraphAction()
    {
        $reports = $this->get('ns.model_manager')->getRepository("NSSentinelBundle:Meningitis")->getByDiagnosis();
       
        return array('reports' => $reports);
    }
}
