<?php

namespace NS\SentinelBundle\Tests\Twig;

use NS\SentinelBundle\Twig\CaseTemplates;

class CaseTemplatesTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $authMock = $this->getMock('\Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface');
        $authMock->expects($this->atLeast(3))
            ->method('isGranted')
            ->willReturn(false);

        $twigMock = $this->getMock('\Twig_Environment');
        $twigMock->expects($this->never())
            ->method('render');

        $twigExtension = new CaseTemplates($authMock, $twigMock);

        $this->assertEquals('twig_case_templates', $twigExtension->getName());
        $this->assertCount(1, $twigExtension->getFunctions());

        $res = $twigExtension->renderTable(array(), 'myTable');
        $this->assertNull($res);
    }

    public function testSiteRoleRenderTable()
    {
        $authMock = $this->getMock('\Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface');
        $authMock->expects($this->once())
            ->method('isGranted')
            ->with('ROLE_SITE_LEVEL')
            ->willReturn(true);

        $twigMock = $this->getMock('\Twig_Environment');
        $twigMock->expects($this->once())
            ->method('render')
            ->with('NSSentinelBundle:Case:site.html.twig', array('results'=>array(), 'tableId'=>'myTable'))
            ->willReturn('siterow');

        $extension = new CaseTemplates($authMock, $twigMock);
        $res = $extension->renderTable(array(), 'myTable');
        $this->assertEquals('siterow', $res);
    }

    public function testCountryRoleRenderTable()
    {
        $authMock = $this->getMock('\Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface');
        $authMock->expects($this->at(0))
            ->method('isGranted')
            ->with('ROLE_SITE_LEVEL')
            ->willReturn(false);
        $authMock->expects($this->at(1))
            ->method('isGranted')
            ->with('ROLE_COUNTRY_LEVEL')
            ->willReturn(true);

        $twigMock = $this->getMock('\Twig_Environment');
        $twigMock->expects($this->once())
            ->method('render')
            ->with('NSSentinelBundle:Case:country.html.twig', array('results'=>array(), 'tableId'=>'myTable'))
            ->willReturn('countryrow');

        $extension = new CaseTemplates($authMock, $twigMock);
        $res = $extension->renderTable(array(), 'myTable');
        $this->assertEquals('countryrow', $res);
    }

    public function testRegionRoleRenderTable()
    {
        $authMock = $this->getMock('\Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface');
        $authMock->expects($this->at(0))
            ->method('isGranted')
            ->with('ROLE_SITE_LEVEL')
            ->willReturn(false);
        $authMock->expects($this->at(1))
            ->method('isGranted')
            ->with('ROLE_COUNTRY_LEVEL')
            ->willReturn(false);
        $authMock->expects($this->at(2))
            ->method('isGranted')
            ->with('ROLE_REGION_LEVEL')
            ->willReturn(true);

        $twigMock = $this->getMock('\Twig_Environment');
        $twigMock->expects($this->once())
            ->method('render')
            ->with('NSSentinelBundle:Case:region.html.twig', array('results'=>array(), 'tableId'=>'myTable'))
            ->willReturn('regionrow');

        $extension = new CaseTemplates($authMock, $twigMock);
        $res = $extension->renderTable(array(), 'myTable');
        $this->assertEquals('regionrow', $res);
    }
}
