<?php

namespace NS\SentinelBundle\Controller;

use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Response;
use \Symfony\Component\HttpFoundation\Request;

/**
 * Description of ReportController
 *
 * @author gnat
 * @Route("/{_locale}/reports")
 */
class ReportController extends Controller
{
    /**
     * @Route("/percent-enrolled",name="reportPercentEnrolled")
     * @param Request $request
     * @return array|RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function percentEnrolledAction(Request $request)
    {
        $form    = $this->createForm('IBDReportFilterType');
        $service = $this->get('ns.sentinel.services.report');
        $params  = $service->numberEnrolled($request,$form,'reportPercentEnrolled');
        if($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report:percentEnrolled.html.twig',$params);
    }

    /**
     * @Route("/annual-age-distribution",name="reportAnnualAgeDistribution")
     * @param Request $request
     * @return array|RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function annualAgeDistributionAction(Request $request)
    {
        $form    = $this->createForm('IBDReportFilterType');
        $service = $this->get('ns.sentinel.services.report');
        $params  = $service->getAnnualAgeDistribution($request,$form,'reportAnnualAgeDistribution');
        if($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report:annualAgeDistribution.html.twig',$params);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function byCountryGraphAction()
    {
        $reports = $this->get('doctrine.orm.entity_manager')->getRepository("NSSentinelBundle:IBD")->getByCountry();

        return $this->render('NSSentinelBundle:Report:byCountryGraph.html.twig',array('reports' => $reports));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function bySiteGraphAction()
    {
        $reports = $this->get('doctrine.orm.entity_manager')->getRepository("NSSentinelBundle:IBD")->getBySite();

        return $this->render('NSSentinelBundle:Report:bySiteGraph.html.twig',array('reports' => $reports));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function generalStatsAction()
    {
        $reports = $this->get('doctrine.orm.entity_manager')->getRepository("NSSentinelBundle:IBD")->getStats();
       
        return $this->render('NSSentinelBundle:Report:generalStats.html.twig',array('reports' => $reports));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function byDiagnosisGraphAction()
    {
        $reports = $this->get('doctrine.orm.entity_manager')->getRepository("NSSentinelBundle:IBD")->getByDiagnosis();
       
        return $this->render('NSSentinelBundle:Report:byDiagnosisGraph.html.twig',array('reports' => $reports));
    }

    /**
     * @Route("/field-population",name="reportFieldPopulation")
     * @param Request $request
     * @return array|RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function fieldPopulationAction(Request $request)
    {
        $form    = $this->createForm('IBDFieldPopulationFilterType',null,array('site_type'=>'advanced','validation_groups'=>array('FieldPopulation')));
        $service = $this->get('ns.sentinel.services.report');
        $params = $service->getFieldPopulation($request,$form,'reportFieldPopulation');
        if($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report:fieldPopulation.html.twig',$params);
    }

    /**
     * @Route("/culture-positive",name="reportCulturePositive")
     * @param Request $request
     * @return array|RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function culturePositiveAction(Request $request)
    {
        $form    = $this->createForm('IBDFieldPopulationFilterType',null,array('site_type'=>'advanced'));
        $service = $this->get('ns.sentinel.services.report');
        $params  = $service->getCulturePositive($request,$form,'reportCulturePositive');
        if($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report:culturePositive.html.twig',$params);
    }

    /*
     * 1 - Number And Percent Enrolled: Admission Diagnosis
     * 4 - Age Distribution - Suspect vs Probable based on admin diagnosis
     *
     * 5 - Clinical Specimens Obtained Report (lower priority)
     *
     * Data Quality Reports
     * 1 - Field Population Report - ("data consistency do file.txt" + "analysis do file.txt" + "2013 analysis of key sites.xls")
     * 4 - Potential Duplicate Report - High priority but needs to be discussed first.
     *
     * Export - Never dumps identifiable data
     *   - Includes filters - country, site, date range etc.
     */

    /**
     * @Route("/data-quality",name="reportDataQuality")
     */
    public function dataQualityAction(Request $request)
    {
        $form    = $this->createForm('IBDFieldPopulationFilterType',null,array('site_type'=>'advanced'));
        $service = $this->get('ns.sentinel.services.report');
        $params  = $service->getDataQuality($request,$form,'reportDataQuality');
        if($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report:dataQuality.html.twig',$params);
    }
}
