<?php

namespace NS\SentinelBundle\Controller;

use NS\SentinelBundle\Filter\Type\BaseQuarterlyFilterType;
use NS\SentinelBundle\Filter\Type\RotaVirus\QuarterlyLinkingReportFilterType;
use NS\SentinelBundle\Filter\Type\RotaVirus\ReportFilterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/{_locale}/rota/reports")
 */
class RotaVirusReportController extends Controller
{
    /**
     * @Route("/data-quality",name="rotaReportDataQuality")
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function dataQualityAction(Request $request): Response
    {
        $form = $this->createForm(ReportFilterType::class, null, ['site_type' => 'advanced', 'validation_groups' => ['FieldPopulation']]);
        $service = $this->get('ns_sentinel.rotavirus_report');
        $params = $service->getDataQuality($request, $form, 'rotaReportDataQuality');
        if ($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report:RotaVirus/dataQuality.html.twig', $params);
    }

    /**
     * @Route("/data-completion",name="rotaReportDataCompletion")
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function dataCompletionAction(Request $request): Response
    {
        $form = $this->createForm(ReportFilterType::class, null, ['site_type' => 'advanced', 'validation_groups' => ['FieldPopulation']]);
        $service = $this->get('ns_sentinel.rotavirus_report');
        $params = $service->getDataCompletion($request, $form, 'rotaReportDataQuality');
        if ($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report:RotaVirus/dataCompletion.html.twig', $params);
    }

    /**
     * @Route("/discharge-classificiation", name="rotaReportDischargeClassification")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function dischargeClassificationAction(Request $request): Response
    {
        $form = $this->createForm(ReportFilterType::class, null, ['site_type' => 'advanced', 'validation_groups' => ['FieldPopulation']]);
        $service = $this->get('ns_sentinel.rotavirus_report');
        $params = $service->getDischargeByHospital($request, $form, 'rotaReportDischargeClassification');
        if ($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report:RotaVirus/discharge-classification.html.twig', $params);
    }

    /**
     * @Route("/discharge-classificiation-doses", name="rotaReportDischargeClassificationDoses")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function dischargeClassificationDosesAction(Request $request): Response
    {
        $form = $this->createForm(ReportFilterType::class, null, ['site_type' => 'advanced', 'validation_groups' => ['FieldPopulation']]);
        $service = $this->get('ns_sentinel.rotavirus_report');
        $params = $service->getDischargeByDoses($request, $form, 'rotaReportDischargeClassificationDoses');
        if ($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report:RotaVirus/discharge-classification-doses.html.twig', $params);
    }

    /**
     * @Route("/site-performance",name="rotaReportSitePerformance")
     * @param Request $request
     * @return Response
     */
    public function sitePerformanceAction(Request $request): Response
    {
        $form = $this->createForm(BaseQuarterlyFilterType::class, null, ['site_type' => 'advanced', 'include_intense' => false]);
        $service = $this->get('ns_sentinel.rotavirus_report');
        $params = $service->getSitePerformance($request, $form, 'rotaReportSitePerformance');
        if ($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report/RotaVirus:site-performance.html.twig', $params);
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/data-linking",name="rotaReportDataLinking")
     */
    public function dataLinking(Request $request): Response
    {
        $form = $this->createForm(QuarterlyLinkingReportFilterType::class, null, ['site_type' => 'advanced', 'include_intense' => false]);
        $service = $this->get('ns_sentinel.rotavirus_report');
        $params = $service->getDataLinking($request, $form, 'rotaReportDataLinking');
        if ($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report/RotaVirus:data-linking.html.twig', $params);
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/stats",name="rotaReportStats")
     */
    public function statsAction(Request $request): Response
    {
        $form = $this->createForm(ReportFilterType::class, null, ['site_type' => 'advanced']);
        $service = $this->get('ns_sentinel.rotavirus_report');
        $params = $service->getStats($request, $form, 'rotaReportStats');
        if ($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report/RotaVirus:stats.html.twig', $params);
    }

    /**
     * @Route("/year-month", name="rotaReportYearMonth")
     *
     * @param Request $request
     * @return Response
     */
    public function yearAndMonthAction(Request $request): Response
    {
        $form    = $this->createForm(BaseQuarterlyFilterType::class, null, ['site_type'=>'advanced']);
        $service = $this->get('ns_sentinel.rotavirus_report');
        $params  = $service->getYearMonth($request, $form, 'rotaReportYearMonth');
        if ($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report:year-month.html.twig', $params);
    }
}
