<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 14/02/18
 * Time: 10:24 AM
 */

namespace NS\SentinelBundle\Tests\Form\IBD\Types;

use NS\SentinelBundle\Form\IBD\Types\Diagnosis;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class DiagnosisTest extends TypeTestCase
{
    /** @var AuthorizationCheckerInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $authChecker;
    private $defaultChoices = [];

    public function setUp()
    {
        $this->authChecker = $this->createMock(AuthorizationCheckerInterface::class);
        parent::setUp();
    }

    protected function getExtensions()
    {
        $admDiagnosis = new Diagnosis();
        $admDiagnosis->setAuthorizationChecker($this->authChecker);
        $this->defaultChoices = $admDiagnosis->getValues();
        return [new PreloadedExtension([$admDiagnosis], [])];
    }

    public function testNormalOptionSet()
    {
        $this->authChecker->expects($this->once())->method('isGranted')->with('ROLE_AMR')->willReturn(false);
        $form = $this->factory->create(Diagnosis::class);
        $choices = $form->getConfig()->getOption('choices');
        $this->assertNotEmpty($choices);
        $this->assertEquals(array_flip($this->defaultChoices), $choices);
    }

    public function testAmrOptions()
    {
        $this->authChecker->expects($this->once())->method('isGranted')->with('ROLE_AMR')->willReturn(true);
        $form = $this->factory->create(Diagnosis::class);
        $choices = $form->getConfig()->getOption('choices');

        $this->assertNotEmpty($choices);
        unset($this->defaultChoices[Diagnosis::SUSPECTED_SEVERE_PNEUMONIA]);
        unset($this->defaultChoices[Diagnosis::UNKNOWN]);
        unset($this->defaultChoices[Diagnosis::OTHER]);
        unset($this->defaultChoices[Diagnosis::SUSPECTED_SEPSIS]);
        $this->assertEquals(array_flip($this->defaultChoices), $choices);
    }
}
