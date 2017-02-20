<?php

namespace NS\SentinelBundle\Tests\Form;

use Nelmio\ApiDocBundle\Form\Extension\DescriptionFormTypeExtension;
use NS\SentinelBundle\Form\CreateType;
use NS\SentinelBundle\Form\Types\CaseCreationType;
use NS\SentinelBundle\Interfaces\SerializedSitesInterface;
use Symfony\Component\Form\Extension\Validator\Type\FormTypeValidatorExtension;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormExtensionInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\FormTypeExtensionInterface;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Description of CreateTypeTest
 *
 * @author gnat
 */
class CreateTypeTest extends TypeTestCase
{
    private $validator;

    public function testSingleSiteForm()
    {
        $this->validator->expects($this->any())
            ->method('validate')
            ->willReturn([]);

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
        $this->validator = $this->createMock(ValidatorInterface::class);

        parent::setUp();
    }

    public function getExtensions()
    {
        $serializedSites = $this->getMockBuilder(SerializedSitesInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['hasMultipleSites', 'setSites', 'getSites', 'getSite'])
            ->getMock();

        $serializedSites->expects($this->once())
            ->method('hasMultipleSites')
            ->willReturn(false);

        $entityMgr = $this->createMock('Doctrine\Common\Persistence\ObjectManager');

        $createType = new CreateType($serializedSites, $entityMgr);

        $authChecker = $this->getMockBuilder('\Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface')
            ->setMethods(['isGranted'])
            ->getMock();

        $authChecker->expects($this->any())
            ->method('isGranted')
            ->willReturn(true);

        $childType = new CaseCreationType();
        $childType->setAuthChecker($authChecker);

        $validatorExtension = new FormTypeValidatorExtension($this->validator);
        $formTypeExtension = new DescriptionFormTypeExtension();
        $extensions = [];

        /** @var FormTypeExtensionInterface $type */
        foreach ([$validatorExtension,$formTypeExtension] as $type) {
            $extType = $type->getExtendedType();

            if(!isset($extensions[$extType])) {
                $extensions[$extType] = [];
            }

            $extensions[$extType][] = $type;
        }

        return [new PreloadedExtension([$childType, $createType], $extensions)];
    }
}
