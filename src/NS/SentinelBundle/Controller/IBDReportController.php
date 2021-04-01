<?php

namespace NS\SentinelBundle\Controller;

use NS\SentinelBundle\Filter\Type\BaseQuarterlyFilterType;
use NS\SentinelBundle\Filter\Type\IBD\QuarterlyLinkingReportFilterType;
use NS\SentinelBundle\Filter\Type\IBD\ReportFilterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/{_locale}/ibd/reports")
 */
class IBDReportController extends Controller
{
    /**
     * @Route("/percent-enrolled",name="ibdReportPercentEnrolled")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function percentEnrolledAction(Request $request)
    {
        $form    = $this->createForm(ReportFilterType::class);
        $service = $this->get('ns_sentinel.ibd_report');
        $params  = $service->numberEnrolled($request, $form, 'ibdReportPercentEnrolled');
        if ($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report:IBD/percentEnrolled.html.twig', $params);
    }

    /**
     * @Route("/annual-age-distribution",name="ibdReportAnnualAgeDistribution")
     * @param Request $request
     * @return array|RedirectResponse|Response
     */
    public function annualAgeDistributionAction(Request $request)
    {
        $form    = $this->createForm(ReportFilterType::class);
        $service = $this->get('ns_sentinel.ibd_report');
        $params  = $service->getAnnualAgeDistribution($request, $form, 'ibdReportAnnualAgeDistribution');
        if ($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report:IBD/annualAgeDistribution.html.twig', $params);
    }

    /**
     * @return Response
     */
    public function byCountryGraphAction()
    {
        $reports = $this->get('doctrine.orm.entity_manager')->getRepository("NSSentinelBundle:IBD")->getByCountry();

        return $this->render('NSSentinelBundle:Report:IBD/byCountryGraph.html.twig', ['reports' => $reports]);
    }

    /**
     * @return Response
     */
    public function bySiteGraphAction()
    {
        $reports = $this->get('doctrine.orm.entity_manager')->getRepository("NSSentinelBundle:IBD")->getBySite();

        return $this->render('NSSentinelBundle:Report:IBD/bySiteGraph.html.twig', ['reports' => $reports]);
    }

    /**
     * @return Response
     */
    public function generalStatsAction()
    {
        $reports = $this->get('doctrine.orm.entity_manager')->getRepository("NSSentinelBundle:IBD")->getStats();
       
        return $this->render('NSSentinelBundle:Report:IBD/generalStats.html.twig', ['reports' => $reports]);
    }

    /**
     * @return Response
     */
    public function byDiagnosisGraphAction()
    {
        $reports = $this->get('doctrine.orm.entity_manager')->getRepository("NSSentinelBundle:IBD")->getByDiagnosis();
       
        return $this->render('NSSentinelBundle:Report:IBD/byDiagnosisGraph.html.twig', ['reports' => $reports]);
    }

    /**
     * @Route("/field-population",name="ibdReportFieldPopulation")
     * @param Request $request
     * @return array|RedirectResponse|Response
     */
    public function fieldPopulationAction(Request $request)
    {
        $form    = $this->createForm(ReportFilterType::class, null, ['site_type'=>'advanced', 'validation_groups'=> ['FieldPopulation']]);
        $service = $this->get('ns_sentinel.ibd_report');
        $params = $service->getFieldPopulation($request, $form, 'ibdReportFieldPopulation');
        if ($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report:IBD/fieldPopulation.html.twig', $params);
    }

    /**
     * @Route("/culture-positive",name="ibdReportCulturePositive")
     * @param Request $request
     * @return array|RedirectResponse|Response
     */
    public function culturePositiveAction(Request $request)
    {
        $form    = $this->createForm(ReportFilterType::class, null, ['site_type'=>'advanced']);
        $service = $this->get('ns_sentinel.ibd_report');
        $params  = $service->getCulturePositive($request, $form, 'ibdReportCulturePositive');
        if ($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report:IBD/culturePositive.html.twig', $params);
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
     * @Route("/data-quality",name="ibdReportDataQuality")
     * @param Request $request
     * @return array|RedirectResponse|Response
     */
    public function dataQualityAction(Request $request)
    {
        $form    = $this->createForm(ReportFilterType::class, null, ['site_type'=>'advanced']);
        $service = $this->get('ns_sentinel.ibd_report');
        $params  = $service->getDataQuality($request, $form, 'ibdReportDataQuality');
        if ($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report:IBD/dataQuality.html.twig', $params);
    }

    /**
     * @Route("/data-completion",name="ibdReportDataCompletion")
     * @param Request $request
     * @return array|RedirectResponse|Response
     */
    public function dataCompletionAction(Request $request)
    {
        $form    = $this->createForm(ReportFilterType::class, null, ['site_type' => 'advanced']);
        $service = $this->get('ns_sentinel.ibd_report');
        $params  = $service->getDataCompletion($request, $form, 'ibdReportDataCompletion');
        if ($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report:IBD/data-completion.html.twig', $params);
    }

    /**
     * @Route("/site-performance",name="ibdReportSitePerformance")
     * @param Request $request
     * @return Response
     */
    public function sitePerformanceAction(Request $request)
    {
        $form    = $this->createForm(BaseQuarterlyFilterType::class, null, ['site_type'=>'advanced']);
        $service = $this->get('ns_sentinel.ibd_report');
        $params  = $service->getSitePerformance($request, $form, 'ibdReportSitePerformance');
        if ($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report:IBD/site-performance.html.twig', $params);
    }

    /**
     * @Route("/data-linking",name="ibdReportDataLinking")
     *
     * @param Request $request
     * @return Response
     */
    public function dataLinking(Request $request)
    {
        $form    = $this->createForm(QuarterlyLinkingReportFilterType::class, null, ['site_type'=>'advanced']);
        $service = $this->get('ns_sentinel.ibd_report');
        $params  = $service->getDataLinking($request, $form, 'ibdReportDataLinking');
        if ($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report:IBD/data-linking.html.twig', $params);
    }

    /**
     * @Route("/stats",name="ibdReportStats")
     *
     * @param Request $request
     * @return Response
     */
    public function statsAction(Request $request)
    {
        $form    = $this->createForm(ReportFilterType::class, null, ['site_type'=>'advanced']);
        $service = $this->get('ns_sentinel.ibd_report');
        $params  = $service->getStats($request, $form, 'ibdReportStats');
        if ($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report:IBD/stats.html.twig', $params);
    }

    /**
     * @Route("/year-month", name="ibdReportYearMonth")
     *
     * @param Request $request
     * @return Response
     */
    public function yearAndMonthAction(Request $request)
    {
        $form    = $this->createForm(BaseQuarterlyFilterType::class, null, ['site_type'=>'advanced']);
        $service = $this->get('ns_sentinel.ibd_report');
        $params  = $service->getYearMonth($request, $form, 'ibdReportYearMonth');
        if ($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report:year-month.html.twig', $params);
    }
}
