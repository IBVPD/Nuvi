<?php

namespace NS\SentinelBundle\Controller;

use NS\SentinelBundle\Entity\IBD;
use NS\SentinelBundle\Entity\Meningitis\Meningitis;
use NS\SentinelBundle\Filter\Type\BaseQuarterlyFilterType;
use NS\SentinelBundle\Filter\Type\IBD\QuarterlyLinkingReportFilterType;
use NS\SentinelBundle\Filter\Type\IBD\ReportFilterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of ReportController
 *
 * @author gnat
 * @Route("/{_locale}/meningitis/reports")
 */
class MeningitisReportController extends Controller
{
    /**
     * @Route("/percent-enrolled",name="meningReportPercentEnrolled")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function percentEnrolledAction(Request $request): Response
    {
        $form    = $this->createForm(ReportFilterType::class);
        $service = $this->get('ns_sentinel.mening_report');
        $params  = $service->numberEnrolled($request, $form, 'meningReportPercentEnrolled');
        if ($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report/IBD:percentEnrolled.html.twig', $params);
    }

    /**
     * @Route("/annual-age-distribution",name="meningReportAnnualAgeDistribution")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function annualAgeDistributionAction(Request $request): Response
    {
        $form    = $this->createForm(ReportFilterType::class);
        $service = $this->get('ns_sentinel.mening_report');
        $params  = $service->getAnnualAgeDistribution($request, $form, 'meningReportAnnualAgeDistribution');
        if ($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report/IBD:annualAgeDistribution.html.twig', $params);
    }

    /**
     * @return Response
     */
    public function byCountryGraphAction(): Response
    {
        $reports = $this->get('doctrine.orm.entity_manager')->getRepository("NSSentinelBundle:IBD")->getByCountry();

        return $this->render('NSSentinelBundle:Report/IBD:byCountryGraph.html.twig', ['reports' => $reports]);
    }

    /**
     * @return Response
     */
    public function bySiteGraphAction(): Response
    {
        $reports = $this->get('doctrine.orm.entity_manager')->getRepository("NSSentinelBundle:IBD")->getBySite();

        return $this->render('NSSentinelBundle:Report/IBD:bySiteGraph.html.twig', ['reports' => $reports]);
    }

    /**
     * @return Response
     */
    public function generalStatsAction(): Response
    {
        $reports = $this->get('doctrine.orm.entity_manager')->getRepository(Meningitis::class)->getStats();
       
        return $this->render('NSSentinelBundle:Report/IBD:generalStats.html.twig', ['reports' => $reports]);
    }

    /**
     * @return Response
     */
    public function byDiagnosisGraphAction(): Response
    {
        $reports = $this->get('doctrine.orm.entity_manager')->getRepository(Meningitis::class)->getByDiagnosis();
       
        return $this->render('NSSentinelBundle:Report/IBD:byDiagnosisGraph.html.twig', ['reports' => $reports]);
    }

    /**
     * @Route("/field-population",name="meningReportFieldPopulation")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function fieldPopulationAction(Request $request): Response
    {
        $form    = $this->createForm(ReportFilterType::class, null, ['site_type'=>'advanced', 'validation_groups'=> ['FieldPopulation']]);
        $service = $this->get('ns_sentinel.mening_report');
        $params = $service->getFieldPopulation($request, $form, 'meningReportFieldPopulation');
        if ($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report/IBD:fieldPopulation.html.twig', $params);
    }

    /**
     * @Route("/culture-positive",name="meningReportCulturePositive")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function culturePositiveAction(Request $request): Response
    {
        $form    = $this->createForm(ReportFilterType::class, null, ['site_type'=>'advanced']);
        $service = $this->get('ns_sentinel.mening_report');
        $params  = $service->getCulturePositive($request, $form, 'meningReportCulturePositive');
        if ($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report/IBD:culturePositive.html.twig', $params);
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
     * @Route("/data-quality",name="meningReportDataQuality")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function dataQualityAction(Request $request): Response
    {
        $form    = $this->createForm(ReportFilterType::class, null, ['site_type'=>'advanced']);
        $service = $this->get('ns_sentinel.mening_report');
        $params  = $service->getDataQuality($request, $form, 'meningReportDataQuality');
        if ($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report/IBD:dataQuality.html.twig', $params);
    }

    /**
     * @Route("/site-performance",name="meningReportSitePerformance")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function sitePerformanceAction(Request $request): Response
    {
        $form    = $this->createForm(BaseQuarterlyFilterType::class, null, ['site_type'=>'advanced']);
        $service = $this->get('ns_sentinel.mening_report');
        $params  = $service->getSitePerformance($request, $form, 'meningReportSitePerformance');
        if ($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report/IBD:site-performance.html.twig', $params);
    }

    /**
     * @Route("/data-linking",name="meningReportDataLinking")
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function dataLinking(Request $request): Response
    {
        $form    = $this->createForm(QuarterlyLinkingReportFilterType::class, null, ['site_type'=>'advanced']);
        $service = $this->get('ns_sentinel.mening_report');
        $params  = $service->getDataLinking($request, $form, 'meningReportDataLinking');
        if ($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report/IBD:data-linking.html.twig', $params);
    }

    /**
     * @Route("/stats",name="meningReportStats")
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function statsAction(Request $request): Response
    {
        $form    = $this->createForm(ReportFilterType::class, null, ['site_type'=>'advanced']);
        $service = $this->get('ns_sentinel.mening_report');
        $params  = $service->getStats($request, $form, 'meningReportStats');
        if ($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report/IBD:stats.html.twig', $params);
    }

    /**
     * @Route("/year-month", name="meningReportYearMonth")
     *
     * @param Request $request
     * @return Response
     */
    public function yearAndMonthAction(Request $request): Response
    {
        $form    = $this->createForm(BaseQuarterlyFilterType::class, null, ['site_type'=>'advanced']);
        $service = $this->get('ns_sentinel.mening_report');
        $params  = $service->getYearMonth($request, $form, 'meningReportYearMonth');
        if ($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report:year-month.html.twig', $params);
    }
}
