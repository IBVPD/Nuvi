<?php

namespace NS\SentinelBundle\Controller;

use NS\SentinelBundle\Filter\Type\BaseQuarterlyFilterType;
use NS\SentinelBundle\Filter\Type\RotaVirus\QuarterlyLinkingReportFilterType;
use NS\SentinelBundle\Filter\Type\RotaVirus\ReportFilterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RotaVirusReportController
 * @package NS\SentinelBundle\Controller
 * @Route("/{_locale}/rota/reports")
 */
class RotaVirusReportController extends Controller
{
    /**
     * @Route("/data-quality",name="reportRotaDataQuality")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function dataQualityAction(Request $request)
    {
        $form    = $this->createForm(ReportFilterType::class, null, ['site_type'=>'advanced', 'validation_groups' => ['FieldPopulation']]);
        $service = $this->get('ns_sentinel.rotavirus_report');
        $params  = $service->getDataQuality($request, $form, 'reportRotaDataQuality');
        if ($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report:RotaVirus/dataQuality.html.twig', $params);
    }

    /**
     * @Route("/site-performance",name="reportRotaSitePerformance")
     * @param Request $request
     * @return Response
     */
    public function sitePerformanceAction(Request $request)
    {
        $form    = $this->createForm(BaseQuarterlyFilterType::class, null, ['site_type'=>'advanced', 'include_intense'=>false]);
        $service = $this->get('ns_sentinel.rotavirus_report');
        $params  = $service->getSitePerformance($request, $form, 'reportRotaSitePerformance');
        if ($params instanceof Response) {
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
        $form    = $this->createForm(QuarterlyLinkingReportFilterType::class, null, ['site_type'=>'advanced']);
        $service = $this->get('ns_sentinel.rotavirus_report');
        $params  = $service->getDataLinking($request, $form, 'reportRotaDataLinking');
        if ($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report/RotaVirus:data-linking.html.twig', $params);
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/stats",name="reportRotaStats")
     */
    public function statsAction(Request $request)
    {
        $form    = $this->createForm(ReportFilterType::class, null, ['site_type'=>'advanced']);
        $service = $this->get('ns_sentinel.rotavirus_report');
        $params  = $service->getStats($request, $form, 'reportRotaStats');
        if ($params instanceof Response) {
            return $params;
        }

        return $this->render('NSSentinelBundle:Report/RotaVirus:stats.html.twig', $params);
    }
}
