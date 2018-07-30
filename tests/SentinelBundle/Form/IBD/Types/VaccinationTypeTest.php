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
    private $defaultChoices = [];

    protected function getExtensions()
    {
        $vaccinationReceived = new VaccinationType();
        $this->defaultChoices = $vaccinationReceived->getValues();
        return [new PreloadedExtension([$vaccinationReceived], [])];
    }

    public function testNormalOptionSet()
    {
        $form = $this->factory->create(VaccinationType::class);
        $choices = $form->getConfig()->getOption('choices');
        $this->assertNotEmpty($choices);
        $this->assertEquals(array_flip($this->defaultChoices), $choices);
    }

    public function testAmrOptions()
    {
        $form = $this->factory->create(VaccinationType::class, null, ['exclude_choices' => [VaccinationType::MEN_AFR_VAC, VaccinationType::ACW135]]);
        $choices = $form->getConfig()->getOption('choices');

        unset($this->defaultChoices[VaccinationType::MEN_AFR_VAC]);
        unset($this->defaultChoices[VaccinationType::ACW135]);

        $this->assertNotEmpty($choices);
        $this->assertEquals(array_flip($this->defaultChoices), $choices);
    }
}
