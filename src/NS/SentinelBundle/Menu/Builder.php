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
    
    public function sidebar()
    {
        $menu = $this->factory->createItem('root');

        $menu->setChildrenAttribute('class','nav nav-list');
        if( $this->securityContext->isGranted('IS_AUTHENTICATED_FULLY') )
        {
            $d = $menu->addChild('Data Entry', array('label'=> 'menu.data-entry'))
                      ->setExtra('icon','icon-edit');
            $d->addChild('Meningitis',array('label'=>'menu.meningitis','route'=>'meningitisIndex'));
            $d->addChild('Rotavirus', array('route'=>'rotavirusIndex'))->setExtra('translation_domain', 'NSSentinelBundle');

            $menu->addChild('Reports', array('label'=> 'menu.data-reports'))->setExtra('icon','icon-dashboard');
            if($this->securityContext->isGranted('ROLE_ADMIN'))
            {
                $admin = $menu->addChild('Admin', array('label'=> 'menu.data-admin'))->setExtra('icon','icon-desktop');
                $admin->addChild('Admin', array('label'=> 'menu.data-admin','route'=>'sonata_admin_dashboard'));
                $admin->addChild('Translation',array('label'=> 'menu.translation','route'=>'jms_translation_index'));
            }
        }

        return $menu;
    }
    
    public function user()
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav ace-nav');

        $p = $menu->addChild('Profile')->setExtra('icon', 'icon-profile');
        $p->setChildrenAttribute('class', 'user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close');

        $p->addChild('Settings')->setExtra('icon', 'icon-cog');
        $p->addChild(' ')->setAttribute('class', 'divider');
        $p->addChild('Logout',array('route' => 'logout'))->setExtra('icon','icon-off');
        
        return $menu;
    }
}
