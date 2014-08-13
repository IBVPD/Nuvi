<?php

namespace NS\SentinelBundle\Controller;

use DateTime;
use Exporter\Source\ArraySourceIterator;
use NS\SentinelBundle\Form\Types\Diagnosis;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
        $alias        = 'c';
        $queryBuilder = $this->get('ns.model_manager')->getRepository('NSSentinelBundle:IBD')->numberAndPercentEnrolledByAdmissionDiagnosis($alias);
        $form         = $this->createForm('IBDReportFilterType');
        $export       = false;

        $form->handleRequest($request);

        if($form->isValid())
        {
            if($form->get('reset')->isClicked())
                return $this->redirect ($this->generateUrl ('reportPercentEnrolled'));
            else
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($form, $queryBuilder, $alias);

            $export = ($form->get('export')->isClicked());
        }

        $ibdResults   = $queryBuilder->getQuery()->getResult();
        $diagnosis    = new Diagnosis();

        $headers      = array('Month')+$diagnosis->getValues();
        $headerValues = array_fill_keys($diagnosis->getValues(),0);
        $results = array();

        foreach($ibdResults as $res)
        {
            $diagnosis = $res['admDx']->__toString();
            if(!isset($results[$res['CreatedMonth']]))
                $results[$res['CreatedMonth']] = $headerValues;

            $results[$res['CreatedMonth']][$diagnosis] = $res['admDxCount'];
        }

        if($export)
        {
            $format   = 'csv';
            $source   = new ArraySourceIterator($results,$headers);
            $filename = sprintf('export_%s.%s',date('Y_m_d_H_i_s'), $format);

            return $this->get('sonata.admin.exporter')->getResponse($format, $filename, $source);
        }

        return array('results' => $results, 'form' => $form->createView(),'headers'=>$headers);
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
        $from  = new DateTime("2001-01-01");
        $today = new DateTime();

        $results = $this->get('ns.model_manager')->getRepository('NSSentinelBundle:IBD')->getAnnualAgeDistribution($from,$today);

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
