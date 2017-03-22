<?php

/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 22/03/17
 * Time: 1:21 PM
 */

namespace NS\SentinelBundle\Tests\Form\IBD;

use NS\AceBundle\Form\Extensions\HiddenParentChildExtension;
use NS\SentinelBundle\Entity\Country;
use NS\SentinelBundle\Entity\IBD;
use NS\SentinelBundle\Entity\Region;
use NS\SentinelBundle\Entity\Site;
use NS\SentinelBundle\Form\IBD\CaseType;
use NS\SentinelBundle\Form\IBD\Types\CXRAdditionalResult;
use NS\SentinelBundle\Form\IBD\Types\CXRResult;
use NS\SentinelBundle\Form\IBD\Types\VaccinationType;
use NS\SentinelBundle\Form\Types\Gender;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\ValidatorGroup\ValidatorGroupResolver;
use NS\SentinelBundle\Interfaces\SerializedSitesInterface;
use NS\SentinelBundle\Services\SerializedSites;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class CaseTypeTest extends TypeTestCase
{
    public function testConstruction()
    {
        $this->authChecker->expects($this->atLeast(2))->method('isGranted')->with('ROLE_AMR')->willReturn(false);
        $form = $this->factory->create(CaseType::class);
        foreach ($form as $name => $field) {
            $this->assertEquals(in_array($name, $this->requiredFields), $field->getConfig()->getOption('required'), sprintf('Field %s doesn\'t have properly required field', $name));
        }

        $this->assertFalse($form->has('bloodNumberOfSamples'));
        $this->assertNotNull($form->get('otherSpecimenCollected'));
    }

    public function testDefaultPaho()
    {
        $fields = array_merge($this->requiredFields,$this->pahoRequiredFields);

        $this->authChecker->expects($this->atLeast(2))->method('isGranted')->with('ROLE_AMR')->willReturn(true);
        $form = $this->factory->create(CaseType::class);

        foreach ($form as $name => $field) {
            $this->assertEquals(in_array($name, $fields), $field->getConfig()->getOption('required'), sprintf('Field %s doesn\'t have properly required field', $name));
        }

        $this->assertTrue($form->has('bloodNumberOfSamples'));
        $this->assertFalse($form->has('otherSpecimenCollected'));
    }

    public function testNonPahoEditingPahoData()
    {
        $fields = array_merge($this->requiredFields,$this->pahoRequiredFields);
        $this->authChecker->expects($this->once())->method('isGranted')->with('ROLE_AMR')->willReturn(false);
        $case = new IBD();
        $case->setSite($this->pahoSite);

        $form = $this->factory->create(CaseType::class, $case);

        foreach ($form as $name => $field) {
            $this->assertEquals(in_array($name, $fields), $field->getConfig()->getOption('required'), sprintf('Field %s doesn\'t have properly required field', $name));
        }

        $this->assertTrue($form->has('bloodNumberOfSamples'));
        $this->assertTrue($form->has('cxrAdditionalResult'));

        $this->assertFalse($form->has('otherSpecimenCollected'));
        $this->assertTrue($form->has('pneuOxygenSaturation'));
    }

    public function testNonPneumoniaCountry()
    {
        $this->authChecker->expects($this->atLeast(2))->method('isGranted')->with('ROLE_AMR')->willReturn(false);

        $case = new IBD();
        $case->setSite($this->euroSite);

        $form = $this->factory->create(CaseType::class, $case);

        foreach ($form as $name => $field) {
            $this->assertEquals(in_array($name, $this->requiredFields), $field->getConfig()->getOption('required'), sprintf('Field %s doesn\'t have properly required field', $name));
        }

        $this->assertFalse($form->has('bloodNumberOfSamples'));
        $this->assertTrue($form->has('otherSpecimenCollected'));

        $this->assertFalse($form->has('pneuDiffBreathe'));
        $this->assertFalse($form->has('pneuChestIndraw'));
        $this->assertFalse($form->has('pneuCough'));
        $this->assertFalse($form->has('pneuCyanosis'));
        $this->assertFalse($form->has('pneuStridor'));
        $this->assertFalse($form->has('pneuRespRate'));
        $this->assertFalse($form->has('pneuVomit'));
        $this->assertFalse($form->has('pneuHypothermia'));
        $this->assertFalse($form->has('pneuMalnutrition'));
        $this->assertFalse($form->has('cxrDone'));
        $this->assertFalse($form->has('cxrResult'));
        $this->assertFalse($form->has('cxrAdditionalResult'));

    }

    /** @var array */
    private $requiredFields = ['caseId'];

    /** @var array */
    private $pahoRequiredFields = ['lastName', 'firstName', 'admDate', 'dobKnown', 'gender'];

    /** @var SerializedSitesInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $siteSerializer;

    /** @var ValidatorGroupResolver|\PHPUnit_Framework_MockObject_MockObject */
    private $groupResolver;

    /** @var AuthorizationCheckerInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $authChecker;

    /** @var Region */
    private $pahoRegion;

    /** @var Country */
    private $pahoCountry;

    /** @var Site */
    private $pahoSite;

    /** @var Region */
    private $euroRegion;

    /** @var Country */
    private $euroCountry;

    /** @var Site */
    private $euroSite;

    protected function setUp()
    {
        $this->siteSerializer = $this->createMock(SerializedSites::class);
        $this->groupResolver = $this->createMock(ValidatorGroupResolver::class);
        $this->authChecker = $this->createMock(AuthorizationCheckerInterface::class);

        $this->pahoRegion = new Region('AMR','PAHO');
        $this->pahoCountry = new Country('PER','Peru');
        $this->pahoCountry->setRegion($this->pahoRegion);
        $this->pahoCountry->setTracksPneumonia(true);
        $this->pahoSite = new Site('PER-1','Site 1');
        $this->pahoSite->setCountry($this->pahoCountry);

        $this->euroRegion = new Region('EUR','Europe');
        $this->euroCountry = new Country('FRA','France');
        $this->euroCountry->setRegion($this->euroRegion);
        $this->euroCountry->setTracksPneumonia(false);
        $this->euroSite = new Site('FRA-1','Site 1');
        $this->euroSite->setCountry($this->euroCountry);

        parent::setUp();
    }

    protected function getExtensions()
    {
        $extension = new HiddenParentChildExtension();
        $vacType = new VaccinationType();
        $vacType->setAuthorizationChecker($this->authChecker);
        $types = [new TripleChoice(), new CXRResult(), new CXRAdditionalResult(), new Gender(), new CaseType($this->siteSerializer, $this->groupResolver, $this->authChecker),$vacType];
        return [new PreloadedExtension($types,[ $extension->getExtendedType()=>[$extension]])];
    }
}
