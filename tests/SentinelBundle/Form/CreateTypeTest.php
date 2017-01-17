<?php

namespace NS\SentinelBundle\Tests\Form;

use Nelmio\ApiDocBundle\Form\Extension\DescriptionFormTypeExtension;
use NS\SentinelBundle\Form\CreateType;
use NS\SentinelBundle\Form\Types\CaseCreationType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Description of CreateTypeTest
 *
 * @author gnat
 */
class CreateTypeTest extends TypeTestCase
{
    public function testSingleSiteForm()
    {
        $form = $this->factory->create(CreateType::class);

        $this->assertCount(2, $form);

        $choices = $form['type']->getConfig()->getOption('choices');
        $this->assertCount(4, $choices);

        $formData = ['caseId' => 12, 'type' => 1];
        $form->submit($formData);

        $data = $form->getData();
        $this->assertArrayHasKey('caseId', $data);
        $this->assertEquals(12, $data['caseId']);
        $this->assertArrayHasKey('type', $data);
        $this->assertEquals(1, $data['type']->getValue());
        $this->assertArrayNotHasKey('site', $data);
    }

    protected function setUp()
    {
        $formTypeExtension = new DescriptionFormTypeExtension();

        $this->factory = Forms::createFormFactoryBuilder()
            ->addExtensions($this->getExtensions())
            ->addTypeExtension($formTypeExtension)
            ->getFormFactory();

        $this->dispatcher = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->builder    = new FormBuilder(null, null, $this->dispatcher, $this->factory);
    }

    public function getExtensions()
    {
        $serializedSites = $this->getMockBuilder('NS\SentinelBundle\Interfaces\SerializedSitesInterface')
            ->disableOriginalConstructor()
            ->setMethods(['hasMultipleSites', 'setSites', 'getSites', 'getSite'])
            ->getMock();

        $serializedSites->expects($this->once())
            ->method('hasMultipleSites')
            ->willReturn(false);

        $entityMgr = $this->createMock('Doctrine\Common\Persistence\ObjectManager');

        $type = new CreateType($serializedSites, $entityMgr);

        $authChecker = $this->getMockBuilder('\Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface')
            ->setMethods(['isGranted'])
            ->getMock();

        $authChecker->expects($this->any())
            ->method('isGranted')
            ->willReturn(true);

        $childType = new CaseCreationType();
        $childType->setAuthChecker($authChecker);

        return [new PreloadedExtension([$childType, $type], [])];
    }
}
