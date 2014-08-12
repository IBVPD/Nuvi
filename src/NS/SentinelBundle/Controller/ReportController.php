<?php

namespace NS\SentinelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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
     * @Template("NSSentinelBundle:Report:unimplemented.html.twig")
     */
    public function percentEnrolledAction()
    {
        return array();
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
    public function annualAgeDistributionAction()
    {
        $from  = new \DateTime("2001-01-01");
        $today = new \DateTime();

        $results = $this->get('ns.model_manager')->getRepository('NS\SentinelBundle\Entity\Meningitis')->getAnnualAgeDistribution($from,$today);

        return array('results'=>$results);
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
