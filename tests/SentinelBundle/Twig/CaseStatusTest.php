<?php

namespace NS\SentinelBundle\Tests\Twig;

use NS\SentinelBundle\Entity\BaseCase;
use NS\SentinelBundle\Entity\Region;
use NS\SentinelBundle\Entity\Site;
use NS\SentinelBundle\Twig\CaseStatus;
use NS\SentinelBundle\Entity\IBD;
use NS\SentinelBundle\Form\Types\CaseStatus as FormCaseStatus;
use NS\SentinelBundle\Entity\IBD\SiteLab;
use NS\SentinelBundle\Entity\IBD\ReferenceLab;
use NS\SentinelBundle\Entity\IBD\NationalLab;
use NS\SentinelBundle\Validators\Cache\CachedValidations;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;

class CaseStatusTest extends TestCase
{
    /** @var CachedValidations|MockObject */
    private $validator;

    /** @var CamelCaseToSnakeCaseNameConverter */
    private $namer;

    /** @var CaseStatus */
    private $extension;

    /** @var BaseCase */
    private $case;

    public function setUp()
    {
        $this->validator = $this->createMock(CachedValidations::class);
        $this->namer     = new CamelCaseToSnakeCaseNameConverter();
        $this->extension = new CaseStatus($this->validator, $this->namer);
        $this->case      = new IBD();
        $this->case->setId('Some Id');
        $this->case->setRegion(new Region('RCODE', 'Test Region'));
    }

    public function testNoSiteLabIncompleteCase(): void
    {
        $label = $this->extension->getLabel($this->case, 'nothing');

        $this->assertContains('label-danger', $label);
    }

    public function testCaseWithoutSiteLabHasError(): void
    {
        $this->validator->expects($this->once())->method('validate')->willReturn([['field'=>'value']]);
        $label = $this->extension->getLabel($this->case, 'nothing');

        $this->assertContains('label-danger', $label);
    }
    public function testIncompleteCaseWithSiteLab(): void
    {
        $this->validator->expects($this->once())->method('validate')->willReturn([['field'=>'value']]);
        $this->case->setSiteLab(new SiteLab());
        $label = $this->extension->getLabel($this->case, 'nothing');

        $this->assertContains('label-warning', $label);
    }

    public function testCompleteCase(): void
    {
        $this->validator->expects($this->once())->method('validate')->willReturn([]);
        $this->case->setSiteLab(new SiteLab());

        $label = $this->extension->getLabel($this->case, 'nothing');

        $this->assertContains('label-success', $label);
    }

    public function testErrorCase(): void
    {
        $lab = new SiteLab();
        $lab->setNlBrothSent(true);
//        $this->case->setSiteLab($lab);

        $label = $this->extension->getLabLabel($this->case, 'nothing');

        $this->assertContains('Missing', $label);

        $lab = new SiteLab();
        $lab->setRlIsolCsfSent(true);
//        $this->case->setSiteLab($lab);

        $label = $this->extension->getLabLabel($this->case, 'nothing');

        $this->assertContains('Missing', $label);
    }

    public function testLabIncomplete(): void
    {
        $this->validator->expects($this->once())->method('validate')->willReturn([['field'=>'value']]);
        $lab = new SiteLab();
        $this->case->setSiteLab($lab);
        $label = $this->extension->getLabLabel($this->case, 'nothing');
        $this->assertContains('label-warning', $label);
    }

    public function testLabComplete(): void
    {
        $this->validator->expects($this->once())->method('validate')->willReturn([]);
        $this->case->setSiteLab(new SiteLab());

        $label = $this->extension->getLabLabel($this->case, 'nothing');

        $this->assertContains('label-success', $label, 'Complete case has success label');
    }

    public function testLabErrorCase(): void
    {
        $label = $this->extension->getLabLabel($this->case, 'nothing');

        $this->assertContains('label-danger', $label, 'Case without lab record has danger label');
    }

    public function testNoExternalLabs(): void
    {
        $l1 = $this->extension->getRRLLabel($this->case, 'nothing');
        $this->assertNull($l1, 'Case without RRL returns null');

        $l2 = $this->extension->getNLLabel($this->case, 'nothing');
        $this->assertNull($l2, 'Case without RRL returns null');
    }

    public function testExternalLabIncomplete(): void
    {
        $this->validator->expects($this->exactly(2))->method('validate')->willReturn([['field'=>'value']]);
        //---------------------------
        // RRL
        $lab = new SiteLab();
        $lab->setRlCsfSent(true);
        $this->case->setSiteLab($lab);

        $rrl = new ReferenceLab();
        $this->case->setReferenceLab($rrl);

        $l1 = $this->extension->getRRLLabel($this->case, 'nothing');

        //---------------------------
        // NL
        $case2 = new IBD();
        $case2->setId('ID');
        $case2->setRegion(new Region('RCODE', 'Test Region'));
        $lab = new SiteLab();
        $lab->setNlBrothSent(true);
        $case2->setSiteLab($lab);
        $case2->setNationalLab(new NationalLab());

        $l2 = $this->extension->getNLLabel($case2, 'nothing');

        $this->assertContains('label-warning', $l1, 'Incomplete RRL lab has warning label');
        $this->assertContains('label-warning', $l2, 'Incomplete RRL lab has warning label');
    }

    public function testExternalLabComplete(): void
    {
        $this->validator->expects($this->exactly(2))->method('validate')->willReturn([]);

        //----------------------
        // RRL test
        $lab = new SiteLab();
        $lab->setRlCsfSent(true);
        $this->case->setSiteLab($lab);

        $rrl = new ReferenceLab();

        $this->case->setReferenceLab($rrl);

        $l1 = $this->extension->getRRLLabel($this->case, 'nothing');

        //----------------------
        // NL test
        $case2 = new IBD();
        $case2->setId('ID');
        $case2->setRegion(new Region('RCODE', 'Test Region'));

        $lab = new SiteLab();
        $lab->setNlBrothSent(true);
        $case2->setSiteLab($lab);

        $nl = new NationalLab();

        $case2->setNationalLab($nl);

        $l2 = $this->extension->getNLLabel($case2, 'nothing');

//        $this->assertTrue($rrl->isComplete(), 'rrl is complete');
        $this->assertContains('label-success', $l1, 'Complete RRL lab has success label');

//        $this->assertTrue($nl->isComplete(), 'nl is complete');
        $this->assertContains('label-success', $l2, 'Complete NL lab has success label');
    }

    public function testExternalLabErrorCase(): void
    {
        //----------------------
        // RRL - sent to lab but no lab data
        $lab = new SiteLab();
        $lab->setRlBrothSent(true);
        $this->case->setSiteLab($lab);

        $l1  = $this->extension->getRRLLabel($this->case, 'nothing');

        //----------------------
        // RRL - lab data but no sent to lab
        $case2 = new IBD();
        $case2->setId('ID');
        $case2->setRegion(new Region('RCODE', 'Test Region'));
        $case2->setReferenceLab(new ReferenceLab());

        $l2  = $this->extension->getRRLLabel($case2, 'nothing');
        $l21 = $this->extension->getLabel($case2, 'nothing');

        //----------------------
        // NL - sent to lab but no lab data
        $case3 = new IBD();
        $case3->setId('ID');
        $case3->setRegion(new Region('RCODE', 'Test Region'));
        $lab = new SiteLab();
        $lab->setNlCsfSent(true);
        $case3->setSiteLab($lab);

        $l3  = $this->extension->getNLLabel($case3, 'nothing');

        //----------------------
        // NL - lab data but no sent to lab
        $case4 = new IBD();
        $case4->setId('ID');
        $case4->setRegion(new Region('RCODE', 'Test Region'));
        $case4->setNationalLab(new NationalLab());

        $l4  = $this->extension->getNLLabel($case4, 'nothing');
        $l41 = $this->extension->getLabel($case4, 'nothing');

        $this->assertContains('label-danger', $l1, 'RRL - sent to lab but no lab data');
        $this->assertContains('label-danger', $l2, 'RRL - lab data but no sent to lab');
        $this->assertContains('label-danger', $l21, 'Case Error - RRL - lab data but no sent to lab');
        $this->assertContains('label-danger', $l3, 'NL - sent to lab but no lab data');
        $this->assertContains('label-danger', $l4, 'NL - lab data but no sent to lab');
        $this->assertContains('label-danger', $l41, 'Case Error - NL - lab data but no sent to lab');
    }
}
