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
    
    public function userMenu()
    {
        $menu = $this->factory->createItem('root')->setExtra('translation_domain', 'NSSentinelBundle');
        $menu->setChildrenAttribute('class','nav');
        $menu->addChild('NUVI');
        if( $this->securityContext->isGranted('IS_AUTHENTICATED_FULLY') )
        {
            $d = $menu->addChild('Data Entry', array('label'=> 'menu.data-entry'));
            $d->addChild('Meningitis',array('label'=>'menu.meningitis','route'=>'meningitisIndex'));
            $d->addChild('Rotavirus');
            
            $menu->addChild('Reports', array('label'=> 'menu.data-reports'));
            $menu->addChild('Admin', array('label'=> 'menu.data-admin','route'=>'sonata_admin_dashboard'));
            $menu->addChild('Logout',array('route' => 'logout'))->setExtra('translation_domain', 'NSSentinelBundle');
        }
        else
            $menu->addChild('Login', array('route' => 'login'))->setExtra('translation_domain', 'NSSentinelBundle');

        return $menu;
    }    
}
