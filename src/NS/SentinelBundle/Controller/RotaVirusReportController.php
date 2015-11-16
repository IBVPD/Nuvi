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

    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/data-linking",name="reportRotaDataLinking")
     */
    public function dataLinking(Request $request)
    {
        $form    = $this->createForm('RotaVirusQuarterlyLinkingReportFilter',null,array('site_type'=>'advanced'));
        $service = $this->get('ns_sentinel.rotavirus_report');
        $params  = $service->getDataLinking($request,$form,'reportRotaDataLinking');
        if($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report/RotaVirus:data-linking.html.twig',$params);
    }

    public function getLinkedCount($alias, array $countryCodes)
    {
        return $this->getByCountryCountQueryBuilder($alias, $countryCodes)
            ->select(sprintf('COUNT(%s) as caseCount,c.code', $alias, $alias))
            ->innerJoin('cf.referenceLab',$alias)
            ->innerJoin('cf.site','s');
    }

    public function getFailedLinkedCount($alias, array $countryCodes)
    {
        return $this->getByCountryCountQueryBuilder($alias, $countryCodes)
            ->select(sprintf('COUNT(%s) as caseCount,c.code', $alias, $alias))
            ->innerJoin('cf.referenceLab',$alias)
            ->leftJoin('cf.site','s')
            ->andWhere('s.code IS NULL');
    }

    public function getNoLabCount($alias, array $countryCodes)
    {
        return $this->getByCountryCountQueryBuilder($alias, $countryCodes)
            ->select(sprintf('COUNT(%s) as caseCount,c.code', $alias, $alias))
            ->leftJoin('cf.referenceLab',$alias)
            ->andWhere($alias.' IS NULL');
    }
}
