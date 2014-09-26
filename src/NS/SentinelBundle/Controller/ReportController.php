<?php

namespace NS\SentinelBundle\Controller;

use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     * @Route("/dashboard",name="reportDashboard")
     * @Template("NSSentinelBundle:Report:unimplemented.html.twig")
     */
    public function dashboardAction()
    {
        return array();
    }

    /**
     * @Route("/percent-enrolled",name="reportPercentEnrolled")
     * @Template()
     */
    public function percentEnrolledAction(Request $request)
    {
        $form = $this->createForm('IBDReportFilterType');
        $s    = $this->get('ns.sentinel.services.report');

        return $s->numberEnrolled($request,$form,'reportPercentEnrolled');
    }

    /**
     * @Route("/monthly",name="reportNumberPerMonth")
     * @Template("NSSentinelBundle:Report:unimplemented.html.twig")
     */
    public function numberPerMonthAction()
    {
        return array();
    }

    /**
     * @Route("/per-year-clinical",name="reportNumberPerYearClinical")
     * @Template("NSSentinelBundle:Report:unimplemented.html.twig")
     */
    public function numberPerYearClinicalAction()
    {
        return array();
    }

    /**
     * @Route("/annual-age-distribution",name="reportAnnualAgeDistribution")
     * @Template()
     */
    public function annualAgeDistributionAction(Request $request)
    {
        $form  = $this->createForm('IBDReportFilterType');
        $s     = $this->get('ns.sentinel.services.report');

        return $s->getAnnualAgeDistribution($request,$form,'reportAnnualAgeDistribution');
    }

    /**
     * @Route("/data-quality",name="reportDataQuality")
     * @Template("NSSentinelBundle:Report:unimplemented.html.twig")
     */
    public function dataQualityAction()
    {
        return array();
    }

    /**
     * @Template()
     */
    public function byCountryGraphAction()
    {
        $reports = $this->get('ns.model_manager')->getRepository("NSSentinelBundle:IBD")->getByCountry();

        return array('reports' => $reports);
    }

    /**
     * @Template()
     */
    public function bySiteGraphAction()
    {
        $reports = $this->get('ns.model_manager')->getRepository("NSSentinelBundle:IBD")->getBySite();

        return array('reports' => $reports);
    }

    /**
     * @Template()
     */
    public function generalStatsAction()
    {
        $reports = $this->get('ns.model_manager')->getRepository("NSSentinelBundle:IBD")->getStats();
       
        return array('reports' => $reports);
    }

    /**
     * @Template()
     */
    public function byDiagnosisGraphAction()
    {
        $reports = $this->get('ns.model_manager')->getRepository("NSSentinelBundle:IBD")->getByDiagnosis();
       
        return array('reports' => $reports);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @Route("/field-population",name="reportFieldPopulation")
     * @Template()
     */
    public function fieldPopulationAction(Request $request)
    {
        $form  = $this->createForm('IBDFieldPopulationFilterType',null,array('site_type'=>'advanced','validation_groups'=>array('FieldPopulation')));
        $s     = $this->get('ns.sentinel.services.report');

        return $s->getFieldPopulation($request,$form,'reportFieldPopulation');
    }

    /**
     *
     * @param Request $request
     * @Route("/culture-positive",name="reportCulturePositive")
     * @Template()
     */
    public function culturePositiveAction(Request $request)
    {
        $form  = $this->createForm('IBDFieldPopulationFilterType',null,array('site_type'=>'advanced'));
        $s     = $this->get('ns.sentinel.services.report');

        return $s->getCulturePositive($request,$form,'reportCulturePositive');
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
}
