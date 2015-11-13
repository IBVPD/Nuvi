<?php

namespace NS\SentinelBundle\Controller;

use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Response;
use \Symfony\Component\HttpFoundation\Request;

/**
 * Class RotaVirusReportController
 * @package NS\SentinelBundle\Controller
 * @Route("/{_locale}/rota/reports")
 */
class RotaVirusReportController extends Controller
{
    /**
     * @Route("/data-quality",name="reportRotaDataQuality")
     */
    public function dataQualityAction(Request $request)
    {
        $form    = $this->createForm('RotaVirusReportFilterType',null,array('site_type'=>'advanced','validation_groups' => array('FieldPopulation')));
        $service = $this->get('ns_sentinel.rotavirus_report');
        $params  = $service->getDataQuality($request,$form,'reportRotaDataQuality');
        if($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report:RotaVirus/dataQuality.html.twig',$params);
    }

    /**
     * @Route("/site-performance",name="reportRotaSitePerformance")
     * @param Request $request
     * @return Response
     */
    public function sitePerformanceAction(Request $request)
    {
        $form    = $this->createForm('QuarterlyReportFilter',null,array('site_type'=>'advanced','include_intense'=>false));
        $service = $this->get('ns_sentinel.rotavirus_report');
        $params  = $service->getSitePerformance($request,$form,'reportRotaSitePerformance');
        if($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report/RotaVirus:site-performance.html.twig', $params);
    }
}
