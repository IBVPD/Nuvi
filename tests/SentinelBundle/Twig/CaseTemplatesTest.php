<?php

namespace NS\SentinelBundle\Tests\Twig;

use NS\SentinelBundle\Twig\CaseTemplates;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Twig_Environment;

class CaseTemplatesTest extends TestCase
{
    public function testConstruct(): void
    {
        $authMock = $this->createMock(AuthorizationCheckerInterface::class);
        $authMock->expects($this->atLeast(3))
            ->method('isGranted')
            ->willReturn(false);

        $twigMock = $this->createMock(Twig_Environment::class);
        $twigMock->expects($this->never())
            ->method('render');

        $twigExtension = new CaseTemplates($authMock, $twigMock);

        $this->assertEquals('twig_case_templates', $twigExtension->getName());
        $this->assertCount(1, $twigExtension->getFunctions());

        $res = $twigExtension->renderTable([], 'myTable');
        $this->assertNull($res);
    }

    public function testSiteRoleRenderTable(): void
    {
        $authMock = $this->createMock(AuthorizationCheckerInterface::class);
        $authMock->expects($this->once())
            ->method('isGranted')
            ->with('ROLE_SITE_LEVEL')
            ->willReturn(true);

        $twigMock = $this->createMock(Twig_Environment::class);
        $twigMock->expects($this->once())
            ->method('render')
            ->with('NSSentinelBundle:Case:site.html.twig', ['results'=> [], 'tableId'=>'myTable'])
            ->willReturn('siterow');

        $extension = new CaseTemplates($authMock, $twigMock);
        $res = $extension->renderTable([], 'myTable');
        $this->assertEquals('siterow', $res);
    }

    public function testCountryRoleRenderTable(): void
    {
        $authMock = $this->createMock(AuthorizationCheckerInterface::class);
        $authMock->expects($this->at(0))
            ->method('isGranted')
            ->with('ROLE_SITE_LEVEL')
            ->willReturn(false);
        $authMock->expects($this->at(1))
            ->method('isGranted')
            ->with('ROLE_COUNTRY_LEVEL')
            ->willReturn(true);

        $twigMock = $this->createMock(Twig_Environment::class);
        $twigMock->expects($this->once())
            ->method('render')
            ->with('NSSentinelBundle:Case:country.html.twig', ['results'=> [], 'tableId'=>'myTable'])
            ->willReturn('countryrow');

        $extension = new CaseTemplates($authMock, $twigMock);
        $res = $extension->renderTable([], 'myTable');
        $this->assertEquals('countryrow', $res);
    }

    public function testRegionRoleRenderTable(): void
    {
        $authMock = $this->createMock(AuthorizationCheckerInterface::class);
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

        $twigMock = $this->createMock(Twig_Environment::class);
        $twigMock->expects($this->once())
            ->method('render')
            ->with('NSSentinelBundle:Case:region.html.twig', ['results'=> [], 'tableId'=>'myTable'])
            ->willReturn('regionrow');

        $extension = new CaseTemplates($authMock, $twigMock);
        $res = $extension->renderTable([], 'myTable');
        $this->assertEquals('regionrow', $res);
    }
}
