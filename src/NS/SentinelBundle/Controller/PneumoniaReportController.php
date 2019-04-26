<?php

namespace NS\SentinelBundle\Controller;

use NS\SentinelBundle\Entity\Pneumonia\Pneumonia;
use NS\SentinelBundle\Filter\Type\BaseQuarterlyFilterType;
use NS\SentinelBundle\Filter\Type\IBD\QuarterlyLinkingReportFilterType;
use NS\SentinelBundle\Filter\Type\IBD\ReportFilterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of ReportController
 *
 * @author gnat
 * @Route("/{_locale}/pneumonia/reports")
 */
class PneumoniaReportController extends Controller
{
    /**
     * @Route("/percent-enrolled",name="pneuReportPercentEnrolled")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function percentEnrolledAction(Request $request): Response
    {
        $form    = $this->createForm(ReportFilterType::class);
        $service = $this->get('ns_sentinel.pneu_report');
        $params  = $service->numberEnrolled($request, $form, 'pneuReportPercentEnrolled');
        if ($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report:Pneumonia/percentEnrolled.html.twig', $params);
    }

    /**
     * @Route("/annual-age-distribution",name="pneuReportAnnualAgeDistribution")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function annualAgeDistributionAction(Request $request): Response
    {
        $form    = $this->createForm(ReportFilterType::class);
        $service = $this->get('ns_sentinel.pneu_report');
        $params  = $service->getAnnualAgeDistribution($request, $form, 'pneuReportAnnualAgeDistribution');
        if ($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report:Pneumonia/annualAgeDistribution.html.twig', $params);
    }

    public function byCountryGraphAction(): Response
    {
        $reports = $this->get('doctrine.orm.entity_manager')->getRepository(Pneumonia::class)->getByCountry();

        return $this->render('NSSentinelBundle:Report:Pneumonia/byCountryGraph.html.twig', ['reports' => $reports]);
    }

    public function bySiteGraphAction(): Response
    {
        $reports = $this->get('doctrine.orm.entity_manager')->getRepository(Pneumonia::class)->getBySite();

        return $this->render('NSSentinelBundle:Report:Pneumonia/bySiteGraph.html.twig', ['reports' => $reports]);
    }

    public function generalStatsAction(): Response
    {
        $reports = $this->get('doctrine.orm.entity_manager')->getRepository(Pneumonia::class)->getStats();
       
        return $this->render('NSSentinelBundle:Report:Pneumonia/generalStats.html.twig', ['reports' => $reports]);
    }

    public function byDiagnosisGraphAction(): Response
    {
        $reports = $this->get('doctrine.orm.entity_manager')->getRepository(Pneumonia::class)->getByDiagnosis();
       
        return $this->render('NSSentinelBundle:Report:Pneumonia/byDiagnosisGraph.html.twig', ['reports' => $reports]);
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
     * @Route("/data-quality",name="pneuReportDataQuality")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function dataQualityAction(Request $request): Response
    {
        $form    = $this->createForm(ReportFilterType::class, null, ['site_type'=>'advanced']);
        $service = $this->get('ns_sentinel.pneu_report');
        $params  = $service->getDataQuality($request, $form, 'pneuReportDataQuality');
        if ($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report:Pneumonia/dataQuality.html.twig', $params);
    }

    /**
     * @Route("/site-performance",name="pneuReportSitePerformance")
     * @param Request $request
     * @return Response
     */
    public function sitePerformanceAction(Request $request): Response
    {
        $form    = $this->createForm(BaseQuarterlyFilterType::class, null, ['site_type'=>'advanced']);
        $service = $this->get('ns_sentinel.pneu_report');
        $params  = $service->getSitePerformance($request, $form, 'pneuReportDataQuality');
        if ($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report:Pneumonia/site-performance.html.twig', $params);
    }

    /**
     * @Route("/data-linking",name="pneuReportDataLinking")
     *
     * @param Request $request
     * @return Response
     */
    public function dataLinking(Request $request): Response
    {
        $form    = $this->createForm(QuarterlyLinkingReportFilterType::class, null, ['site_type'=>'advanced']);
        $service = $this->get('ns_sentinel.pneu_report');
        $params  = $service->getDataLinking($request, $form, 'pneuReportDataLinking');
        if ($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report:Pneumonia/data-linking.html.twig', $params);
    }

    /**
     * @Route("/stats",name="pneuReportStats")
     *
     * @param Request $request
     * @return Response
     */
    public function statsAction(Request $request): Response
    {
        $form    = $this->createForm(ReportFilterType::class, null, ['site_type'=>'advanced']);
        $service = $this->get('ns_sentinel.pneu_report');
        $params  = $service->getStats($request, $form, 'pneuReportStats');
        if ($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report:Pneumonia/stats.html.twig', $params);
    }

    /**
     * @Route("/year-month", name="pneuReportYearMonth")
     *
     * @param Request $request
     * @return Response
     */
    public function yearAndMonthAction(Request $request): Response
    {
        $form    = $this->createForm(BaseQuarterlyFilterType::class, null, ['site_type'=>'advanced']);
        $service = $this->get('ns_sentinel.pneu_report');
        $params  = $service->getYearMonth($request, $form, 'pneuReportYearMonth');
        if ($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report:year-month.html.twig', $params);
    }
}
