<?php

namespace NS\SentinelBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class Builder
{
    /** @var FactoryInterface */
    private $factory;

    /** @var AuthorizationCheckerInterface */
    private $authChecker;

    public function __construct(FactoryInterface $factory, AuthorizationCheckerInterface $authChecker)
    {
        $this->factory     = $factory;
        $this->authChecker = $authChecker;
    }

    public function sidebar(): ItemInterface
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav nav-list');

        if ($this->authChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            if ($this->authChecker->isGranted('ROLE_CAN_CREATE')) {
                $data = $menu->addChild('Data Entry', ['label' => 'menu.data-entry', 'extras' => ['icon' => 'fa fa-edit']]);
                $data->addChild('Meningitis', ['label' => 'menu.meningitis', 'route' => 'meningitisIndex', 'extras' => ['routes' => ['meningitisEdit', 'meningitisLabEdit', 'meningitisNLEdit', 'meningitisRRLEdit', 'meningitisOutcomeEdit']]]);
                $data->addChild('Pneumonia', ['label' => 'menu.pneumonia', 'route' => 'pneumoniaIndex', 'extras' => ['routes' => ['pneumoniaEdit', 'pneumoniaLabEdit', 'pneumoniaNLEdit', 'pneumoniaRRLEdit', 'pneumoniaOutcomeEdit']]]);
//                $data->addChild('IBD', [ 'label' => 'menu.ibd', 'route' => 'ibdIndex', 'extras' => ['routes'=>['ibdEdit','ibdLabEdit','ibdNLEdit','ibdRRLEdit','ibdOutcomeEdit']] ]);
                $data->addChild('Rotavirus', ['route' => 'rotavirusIndex', 'extras' => ['translation_domain' => 'NSSentinelBundle', 'routes' => ['rotavirusEdit', 'rotavirusLabEdit', 'rotavirusNLEdit', 'rotavirusRRLEdit', 'rotavirusOutcomeEdit']]]);
                $data->addChild('Zero Reporting', ['route' => 'zeroReportIndex']);
            }

            $reports      = $menu->addChild('Reports', ['label' => 'menu.data-reports', 'extras' => ['icon' => 'fa fa-dashboard']]);
            $meningReport = $reports->addChild('Meningitis', ['label' => 'menu.meningitis']);
            $pneuReport   = $reports->addChild('Pneumonia', ['label' => 'menu.pneumonia']);
            $rotaReport   = $reports->addChild('Rota', ['label' => 'Rotavirus']);
            $ibdReport    = $reports->addChild('IBD', ['label' => 'IBD']);

            $rotaReport->addChild('Data Quality Checks', ['label' => 'menu.data-reports-data-quality', 'route' => 'rotaReportDataQuality']);
            $rotaReport->addChild('Site Performance', ['label' => 'menu.data-reports-site-performance', 'route' => 'rotaReportSitePerformance']);
            $rotaReport->addChild('RRL Linking', ['label' => 'menu.data-reports-rrl-linking', 'route' => 'rotaReportDataLinking']);
            $rotaReport->addChild('Stats', ['label' => 'menu.data-reports-stats', 'route' => 'rotaReportStats']);
            $rotaReport->addChild('Year / Month', ['label' => 'menu.data-reports-year-month', 'route' => 'rotaReportYearMonth']);

            $ibdReport->addChild('Data Quality Checks', ['label' => 'menu.data-reports-data-quality', 'route' => 'ibdReportDataQuality']);
            $ibdReport->addChild('Site Performance', ['label' => 'menu.data-reports-site-performance', 'route' => 'ibdReportSitePerformance']);
            $ibdReport->addChild('RRL Linking', ['label' => 'menu.data-reports-rrl-linking', 'route' => 'ibdReportDataLinking']);
            $ibdReport->addChild('Stats', ['label' => 'menu.data-reports-stats', 'route' => 'ibdReportStats']);
            $ibdReport->addChild('Year / Month', ['label' => 'menu.data-reports-year-month', 'route' => 'ibdReportYearMonth']);

            $ibdReport->addChild('Age Distribution', ['label' => 'menu.data-reports-age-distribution', 'route' => 'ibdReportAnnualAgeDistribution']);
            $ibdReport->addChild('Enrolment %', ['label' => 'menu.data-reports-percent-enrolled', 'route' => 'ibdReportPercentEnrolled']);
            $ibdReport->addChild('Field Population', ['label' => 'menu.data-reports-field-population', 'route' => 'ibdReportFieldPopulation']);
            $ibdReport->addChild('Culture Positive', ['label' => 'menu.data-reports-culture-positive', 'route' => 'ibdReportCulturePositive']);

            $pneuReport->addChild('Data Quality Checks', ['label' => 'menu.data-reports-data-quality', 'route' => 'pneuReportDataQuality']);
            $pneuReport->addChild('Site Performance', ['label' => 'menu.data-reports-site-performance', 'route' => 'pneuReportSitePerformance']);
            $pneuReport->addChild('RRL Linking', ['label' => 'menu.data-reports-rrl-linking', 'route' => 'pneuReportDataLinking']);
            $pneuReport->addChild('Stats', ['label' => 'menu.data-reports-stats', 'route' => 'pneuReportStats']);
            $pneuReport->addChild('Year / Month', ['label' => 'menu.data-reports-year-month', 'route' => 'pneuReportYearMonth']);

            $pneuReport->addChild('Age Distribution', ['label' => 'menu.data-reports-age-distribution', 'route' => 'pneuReportAnnualAgeDistribution']);
            $pneuReport->addChild('Enrolment %', ['label' => 'menu.data-reports-percent-enrolled', 'route' => 'pneuReportPercentEnrolled']);

            $meningReport->addChild('Data Quality Checks', ['label' => 'menu.data-reports-data-quality', 'route' => 'meningReportDataQuality']);
            $meningReport->addChild('Site Performance', ['label' => 'menu.data-reports-site-performance', 'route' => 'meningReportSitePerformance']);
            $meningReport->addChild('RRL Linking', ['label' => 'menu.data-reports-rrl-linking', 'route' => 'meningReportDataLinking']);
            $meningReport->addChild('Stats', ['label' => 'menu.data-reports-stats', 'route' => 'meningReportStats']);
            $meningReport->addChild('Year / Month', ['label' => 'menu.data-reports-year-month', 'route' => 'meningReportYearMonth']);

            $meningReport->addChild('Age Distribution', ['label' => 'menu.data-reports-age-distribution', 'route' => 'meningReportAnnualAgeDistribution']);
            $meningReport->addChild('Enrolment %', ['label' => 'menu.data-reports-percent-enrolled', 'route' => 'meningReportPercentEnrolled']);
            $meningReport->addChild('Field Population', ['label' => 'menu.data-reports-field-population', 'route' => 'meningReportFieldPopulation']);
            $meningReport->addChild('Culture Positive', ['label' => 'menu.data-reports-culture-positive', 'route' => 'meningReportCulturePositive']);

            if ($this->authChecker->isGranted('ROLE_API')) {
                $api = $menu->addChild('Api Resources', ['label' => 'Api Resources', 'extras' => ['icon' => 'fa fa-book']]);
                $api->addChild('Dashboard', ['label' => 'Dashboard', 'route' => 'ns_api_dashboard']);
                $api->addChild('Documentation', ['label' => 'Documentation', 'route' => 'nelmio_api_doc_index']);
            }

            if ($this->authChecker->isGranted('ROLE_IMPORT')) {
                $menu->addChild('Import', ['label' => 'menu.import', 'route' => 'importIndex', 'extras' => ['icon' => 'fa fa-cloud-upload']]);
            }

            $menu->addChild('Export', ['label' => 'menu.export', 'route' => 'exportIndex', 'extras' => ['icon' => 'fa fa-cloud-download']]);

            if ($this->authChecker->isGranted('ROLE_ADMIN')) {
                $admin = $menu->addChild('Admin', ['label' => 'menu.data-admin', 'extras' => ['icon' => 'fa fa-desktop']]);
                $admin->addChild('Admin', ['label' => 'menu.data-admin', 'route' => 'sonata_admin_dashboard']);
                $admin->addChild('Translation', ['label' => 'menu.translation', 'route' => 'jms_translation_index']);
            }
        }

        return $menu;
    }

    public function user(): ItemInterface
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav ace-nav');

        $profile = $menu->addChild('Profile', ['extras' => ['icon' => 'fa fa-profile']]);
        $profile->setChildrenAttribute('class', 'user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close');

        $profile->addChild('Settings', ['extras' => ['icon' => 'fa fa-cog']]);
        $profile->addChild(' ')->setAttribute('class', 'divider');
        $profile->addChild('Logout', ['route' => 'logout', 'extras' => ['icon' => 'fa fa-off']]);

        return $menu;
    }
}
