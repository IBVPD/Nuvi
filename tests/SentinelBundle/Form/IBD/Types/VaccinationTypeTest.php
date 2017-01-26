<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 26/01/17
 * Time: 4:13 PM
 */

namespace NS\SentinelBundle\Tests\Form\IBD\Types;

use NS\SentinelBundle\Form\IBD\Types\VaccinationType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class VaccinationTypeTest extends TypeTestCase
{
    /** @var AuthorizationCheckerInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $authChecker;
    private $defaultChoices = [];

    protected function setUp()
    {
        $this->authChecker = $this->getMockBuilder(AuthorizationCheckerInterface::class)->disableOriginalConstructor()->getMock();
        parent::setUp();
    }

    protected function getExtensions()
    {
        $vaccinationReceived = new VaccinationType();
        $vaccinationReceived->setAuthorizationChecker($this->authChecker);
        $this->defaultChoices = $vaccinationReceived->getValues();
        return [new PreloadedExtension([$vaccinationReceived], [])];
    }

    public function testNormalOptionSet()
    {
        $this->authChecker->expects($this->once())->method('isGranted')->with('ROLE_AMR')->willReturn(false);
        $form = $this->factory->create(VaccinationType::class);
        $choices = $form->getConfig()->getOption('choices');
        $this->assertNotEmpty($choices);
        $this->assertEquals(array_flip($this->defaultChoices), $choices);
    }

    public function testAmrOptions()
    {
        $this->authChecker->expects($this->once())->method('isGranted')->with('ROLE_AMR')->willReturn(true);
        $form = $this->factory->create(VaccinationType::class);
        $choices = $form->getConfig()->getOption('choices');

        $this->assertNotEmpty($choices);

        unset($this->defaultChoices[VaccinationType::MEN_AFR_VAC]);
        unset($this->defaultChoices[VaccinationType::ACW135]);
        $this->defaultChoices[VaccinationType::B] = 'B recombinante';
        $this->defaultChoices[VaccinationType::C] = 'C (conjugada)';

        sort($this->defaultChoices);
        $this->assertEquals(array_flip($this->defaultChoices), $choices);
    }
}
