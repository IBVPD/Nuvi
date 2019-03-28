<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 26/01/17
 * Time: 4:28 PM
 */

namespace NS\SentinelBundle\Tests\Form\IBD\Types;

use NS\SentinelBundle\Form\IBD\Types\GramStainResult;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class GramStainResultTest extends TypeTestCase
{
    /** @var  AuthorizationCheckerInterface|MockObject */
    private $authChecker;

    private $defaultChoices = [];

    protected function getExtensions()
    {
        $formType = new GramStainResult();
        $formType->setAuthorizationChecker($this->authChecker);
        $this->defaultChoices = $formType->getValues();
        return [new PreloadedExtension([$formType], [])];
    }

    protected function setUp()
    {
        $this->authChecker = $this->getMockBuilder(AuthorizationCheckerInterface::class)->disableOriginalConstructor()->getMock();
        parent::setUp();
    }

    public function testDefaultOptions()
    {
        $this->authChecker->expects($this->once())->method('isGranted')->with('ROLE_AMR')->willReturn(false);
        $form = $this->factory->create(GramStainResult::class);
        $choices = $form->getConfig()->getOption('choices');
        $this->assertNotEmpty($choices);
        $this->assertEquals(array_flip($this->defaultChoices),$choices);
    }

    public function testPahoOptions()
    {
        $this->authChecker->expects($this->once())->method('isGranted')->with('ROLE_AMR')->willReturn(true);
        $form = $this->factory->create(GramStainResult::class);
        $choices = $form->getConfig()->getOption('choices');
        $this->assertNotEmpty($choices);

        unset($this->defaultChoices[GramStainResult::OTHER]);
        $this->defaultChoices[GramStainResult::UNKNOWN] = 'Undetermined';

        $this->assertEquals(array_flip($this->defaultChoices),$choices);
    }
}
