<?php

namespace NS\SentinelBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Description of Builder
 *
 * @author gnat
 */
class Builder 
{
    private $factory;
    protected $securityContext;

    /**
     * @param FactoryInterface $factory
     * @param SecurityContext $securityContext
     */
    public function __construct(FactoryInterface $factory, SecurityContext $securityContext)
    {
        $this->factory         = $factory;
        $this->securityContext = $securityContext;
    }

    /**
     * @return \Knp\Menu\ItemInterface
     */
    public function sidebar()
    {
        $menu = $this->factory->createItem('root');

        $menu->setChildrenAttribute('class','nav nav-list');
        if ($this->securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
            if ($this->securityContext->isGranted('ROLE_CAN_CREATE')) {
                $data = $menu->addChild('Data Entry', array('label'=> 'menu.data-entry'))->setExtra('icon','fa fa-edit');
                $data->addChild('Meningitis', array('label' => 'menu.ibd', 'route' => 'ibdIndex'));
                $data->addChild('Rotavirus', array('route'=>'rotavirusIndex'))->setExtra('translation_domain', 'NSSentinelBundle');
            }

            $reports   = $menu->addChild('Reports', array('label' => 'menu.data-reports'))->setExtra('icon', 'fa fa-dashboard');
            $ibdReport = $reports->addChild('IBD');
            $reports->addChild('Rota');

            $ibdReport->addChild('Age Distribution',array('label'=> 'menu.data-reports-age-distribution','route'=>'reportAnnualAgeDistribution'));
            $ibdReport->addChild('Enrolment %',array('label'=> 'menu.data-reports-percent-enrolled','route'=>'reportPercentEnrolled'));
            $ibdReport->addChild('Field Population',array('label'=>'menu.data-reports-field-population','route'=>'reportFieldPopulation'));
            $ibdReport->addChild('Culture Positive',array('label'=>'menu.data-reports-culture-positive','route'=>'reportCulturePositive'));

            if ($this->securityContext->isGranted('ROLE_API')) {
                $api = $menu->addChild('Api Resources', array('label' => 'Api Resources'))->setExtra('icon', 'fa fa-book');
                $api->addChild('Dashboard', array('label' => 'Dashboard', 'route' => 'ns_api_dashboard'));
                $api->addChild('Documentation', array('label' => 'Documentation','route' => 'nelmio_api_doc_index'));
            }

            if ($this->securityContext->isGranted('ROLE_IMPORT')) {
                $import = $menu->addChild('Import', array('label' => 'menu.import-export'))->setExtra('icon', 'fa fa-cloud-upload');
                $import->addChild('Import', array('label' => 'menu.import', 'route' => 'importIndex'))->setExtra('icon', 'fa fa-cloud-upload');
                $import->addChild('Export', array('label' => 'menu.export', 'route' => 'exportIndex'))->setExtra('icon', 'fa fa-cloud-download');
            }

            if ($this->securityContext->isGranted('ROLE_ADMIN')) {
                $admin = $menu->addChild('Admin', array('label' => 'menu.data-admin'))->setExtra('icon', 'fa fa-desktop');
                $admin->addChild('Admin', array('label' => 'menu.data-admin', 'route' => 'sonata_admin_dashboard'));
                $admin->addChild('Translation', array('label' => 'menu.translation','route' => 'jms_translation_index'));
            }
        }

        return $menu;
    }

    /**
     * @return \Knp\Menu\ItemInterface
     */
    public function user()
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav ace-nav');

        $profile = $menu->addChild('Profile')->setExtra('icon', 'fa fa-profile');
        $profile->setChildrenAttribute('class', 'user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close');

        $profile->addChild('Settings')->setExtra('icon', 'fa fa-cog');
        $profile->addChild(' ')->setAttribute('class', 'divider');
        $profile->addChild('Logout',array('route' => 'logout'))->setExtra('icon','fa fa-off');

        return $menu;
    }
}
