<?php

namespace NS\SentinelBundle\Tests\Twig;

use NS\SentinelBundle\Entity\Meningitis;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Twig\CaseActions;
use NS\SentinelBundle\Entity\IBD;
use NS\SentinelBundle\Entity\RotaVirus;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Description of CaseActionsTest
 *
 * @author gnat
 */
class CaseActionsTest extends \PHPUnit_Framework_TestCase
{
    /** @var AuthorizationCheckerInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $authChecker;

    /** @var TranslatorInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $trans;

    /** @var RouterInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $router;

    public function setUp()
    {
        $this->authChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $this->trans = $this->createMock(TranslatorInterface::class);
        $this->router = $this->createMock(RouterInterface::class);

        $tmap = [
            ['EPI', [], null, null, 'EPI'],
            ['Lab', [], null, null, 'Lab'],
            ['RRL', [], null, null, 'RRL'],
            ['NL', [], null, null, 'NL'],
        ];

        $this->trans->expects($this->any())
            ->method('trans')
            ->will($this->returnValueMap($tmap));

        $rmap = [
            ['ibdShow', ['id' => null], UrlGeneratorInterface::ABSOLUTE_PATH, 'Show IBD Case'],
            ['ibdEdit', ['id' => null], UrlGeneratorInterface::ABSOLUTE_PATH, 'Edit IBD Case'],
            ['ibdRRLEdit', ['id' => null], UrlGeneratorInterface::ABSOLUTE_PATH, 'Edit RRL'],
            ['ibdNLEdit', ['id' => null], UrlGeneratorInterface::ABSOLUTE_PATH, 'Edit NL'],
            ['ibdLabEdit', ['id' => null], UrlGeneratorInterface::ABSOLUTE_PATH, 'Edit Lab'],
            ['meningitisShow', ['id' => null], UrlGeneratorInterface::ABSOLUTE_PATH, 'Show Meningitis Case'],
            ['meningitisEdit', ['id' => null], UrlGeneratorInterface::ABSOLUTE_PATH, 'Edit Meningitis Case'],
            ['meningitisRRLEdit', ['id' => null], UrlGeneratorInterface::ABSOLUTE_PATH, 'Edit RRL'],
            ['meningitisNLEdit', ['id' => null], UrlGeneratorInterface::ABSOLUTE_PATH, 'Edit NL'],
            ['meningitisLabEdit', ['id' => null], UrlGeneratorInterface::ABSOLUTE_PATH, 'Edit Lab'],
            ['pneumoniaShow', ['id' => null], UrlGeneratorInterface::ABSOLUTE_PATH, 'Show Pneumonia Case'],
            ['pneumoniaEdit', ['id' => null], UrlGeneratorInterface::ABSOLUTE_PATH, 'Edit Pneumonia Case'],
            ['pneumoniaRRLEdit', ['id' => null], UrlGeneratorInterface::ABSOLUTE_PATH, 'Edit RRL'],
            ['pneumoniaNLEdit', ['id' => null], UrlGeneratorInterface::ABSOLUTE_PATH, 'Edit NL'],
            ['pneumoniaLabEdit', ['id' => null], UrlGeneratorInterface::ABSOLUTE_PATH, 'Edit Lab'],
            ['rotavirusShow', ['id' => null], UrlGeneratorInterface::ABSOLUTE_PATH, 'Show Rota Case'],
            ['rotavirusEdit', ['id' => null], UrlGeneratorInterface::ABSOLUTE_PATH, 'Edit Rota Case'],
            ['rotavirusRRLEdit', ['id' => null], UrlGeneratorInterface::ABSOLUTE_PATH, 'Edit RRL'],
            ['rotavirusNLEdit', ['id' => null], UrlGeneratorInterface::ABSOLUTE_PATH, 'Edit NL'],
            ['rotavirusLabEdit', ['id' => null], UrlGeneratorInterface::ABSOLUTE_PATH, 'Edit Lab'],
        ];

        $this->router->expects($this->any())
            ->method('generate')
            ->will($this->returnValueMap($rmap));
    }

    public function testBigShowOnlyActions()
    {
        $this->authChecker->expects($this->any())
            ->method('isGranted')
            ->with('ROLE_CAN_CREATE')
            ->will($this->returnValue(false));

        $action     = new CaseActions($this->authChecker, $this->trans, $this->router);
        $obj        = new IBD();
        $bigResults = $action->getBigActions($obj);

        $this->assertContains('fa-list',$bigResults);
        $this->assertContains("Show IBD Case", $bigResults, "User who can't create can only see");
        $this->assertNotContains("Edit IBD Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertNotContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertNotContains("Edit NL", $bigResults, "NL Link");

        $obj        = new Meningitis\Meningitis();
        $bigResults = $action->getBigActions($obj);

        $this->assertContains('fa-list',$bigResults);
        $this->assertContains("Show Meningitis Case", $bigResults, "User who can't create can only see");
        $this->assertNotContains("Edit Meningitis Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertNotContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertNotContains("Edit NL", $bigResults, "NL Link");

        $obj        = new RotaVirus();
        $bigResults = $action->getBigActions($obj);

        $this->assertContains('fa-list',$bigResults);
        $this->assertContains("Show Rota Case", $bigResults, "User who can't create can only see");
        $this->assertNotContains("Edit Rota Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertNotContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertNotContains("Edit NL", $bigResults, "NL Link");
    }

    public function testBigCanCreateCaseActions()
    {
        $map = [
            ['ROLE_CAN_CREATE', null, true],
            ['ROLE_CAN_CREATE_CASE', null, true],
        ];

        $this->authChecker->expects($this->any())
            ->method('isGranted')
            ->will($this->returnValueMap($map));

        $action     = new CaseActions($this->authChecker, $this->trans, $this->router);
        $obj        = new IBD();
        $bigResults = $action->getBigActions($obj);

        $this->assertContains('fa-list',$bigResults);
        $this->assertContains("Show IBD Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit IBD Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertNotContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertNotContains("Edit NL", $bigResults, "NL Link");

        $obj        = new Meningitis\Meningitis();
        $bigResults = $action->getBigActions($obj);

        $this->assertContains('fa-list',$bigResults);
        $this->assertContains("Show Meningitis Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit Meningitis Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertNotContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertNotContains("Edit NL", $bigResults, "NL Link");

        $obj        = new RotaVirus();
        $bigResults = $action->getBigActions($obj);
        $this->assertContains('fa-list',$bigResults);
        $this->assertContains("Show Rota Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit Rota Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertNotContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertNotContains("Edit NL", $bigResults, "NL Link");
    }

    public function testBigCanCreateRRLActions()
    {
        $map = [
            ['ROLE_CAN_CREATE', null, true],
            ['ROLE_CAN_CREATE_CASE', null, true],
            ['ROLE_CAN_CREATE_RRL_LAB', null, true],
        ];

        $this->authChecker->expects($this->any())
            ->method('isGranted')
            ->will($this->returnValueMap($map));

        $action = new CaseActions($this->authChecker, $this->trans, $this->router);

        $obj = new IBD();
        $lab = new IBD\SiteLab();
        $lab->setRlBrothSent(true);
        $lab->setNlBrothSent(true);
        $obj->setSiteLab($lab);

        $bigResults = $action->getBigActions($obj);

        $this->assertContains('fa-list',$bigResults);
        $this->assertContains("Show IBD Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit IBD Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertNotContains("Edit NL", $bigResults, "NL Link");

        $obj = new Meningitis\Meningitis();
        $lab = new Meningitis\SiteLab();
        $lab->setRlBrothSent(true);
        $lab->setNlBrothSent(true);
        $obj->setSiteLab($lab);

        $bigResults = $action->getBigActions($obj);

        $this->assertContains('fa-list',$bigResults);
        $this->assertContains("Show Meningitis Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit Meningitis Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertNotContains("Edit NL", $bigResults, "NL Link");

        $obj = new RotaVirus();
        $lab = new RotaVirus\SiteLab();
        $lab->setStoolSentToRRL(new TripleChoice(TripleChoice::YES));
        $lab->setStoolSentToNL(new TripleChoice(TripleChoice::YES));
        $obj->setSiteLab($lab);

        $bigResults = $action->getBigActions($obj);

        $this->assertContains('fa-list',$bigResults);
        $this->assertContains("Show Rota Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit Rota Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertNotContains("Edit NL", $bigResults, "NL Link");
    }

    public function testBigCanCreateNLActions()
    {
        $map = [
            ['ROLE_CAN_CREATE', null, true],
            ['ROLE_CAN_CREATE_CASE', null, true],
            ['ROLE_CAN_CREATE_LAB', null, false],
            ['ROLE_CAN_CREATE_RRL_LAB', null, false],
            ['ROLE_CAN_CREATE_NL_LAB', null, true],
        ];

        $this->authChecker->expects($this->any())
            ->method('isGranted')
            ->will($this->returnValueMap($map));

        $action = new CaseActions($this->authChecker, $this->trans, $this->router);

        $obj = new IBD();
        $lab = new IBD\SiteLab();
        $lab->setRlCsfSent(true);
        $lab->setNlCsfSent(true);
        $obj->setSiteLab($lab);

        $bigResults = $action->getBigActions($obj);

        $this->assertContains('fa-list',$bigResults);
        $this->assertContains("Show IBD Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit IBD Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertNotContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertContains("Edit NL", $bigResults, "NL Link");

        $obj = new Meningitis\Meningitis();
        $lab = new Meningitis\SiteLab();
        $lab->setRlCsfSent(true);
        $lab->setNlCsfSent(true);
        $obj->setSiteLab($lab);

        $bigResults = $action->getBigActions($obj);

        $this->assertContains('fa-list',$bigResults);
        $this->assertContains("Show Meningitis Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit Meningitis Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertNotContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertContains("Edit NL", $bigResults, "NL Link");

        $obj = new RotaVirus();
        $lab = new RotaVirus\SiteLab();
        $lab->setStoolSentToRRL(new TripleChoice(TripleChoice::YES));
        $lab->setStoolSentToNL(new TripleChoice(TripleChoice::YES));
        $obj->setSiteLab($lab);

        $bigResults = $action->getBigActions($obj);

        $this->assertContains('fa-list',$bigResults);
        $this->assertContains("Show Rota Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit Rota Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertNotContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertContains("Edit NL", $bigResults, "NL Link");
    }

    public function testBigCanCreateAllActions()
    {
        $map = [
            ['ROLE_CAN_CREATE', null, true],
            ['ROLE_CAN_CREATE_CASE', null, true],
            ['ROLE_CAN_CREATE_LAB', null, true],
            ['ROLE_CAN_CREATE_RRL_LAB', null, true],
            ['ROLE_CAN_CREATE_NL_LAB', null, true],
        ];

        $this->authChecker->expects($this->any())
            ->method('isGranted')
            ->will($this->returnValueMap($map));

        $action = new CaseActions($this->authChecker, $this->trans, $this->router);
        $obj    = new IBD();
        $lab    = new IBD\SiteLab();
        $lab->setRlCsfSent(true);
        $lab->setNlCsfSent(true);
        $obj->setSiteLab($lab);

        $bigResults = $action->getBigActions($obj);

        $this->assertContains('fa-list',$bigResults);
        $this->assertContains("Show IBD Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit IBD Case", $bigResults, "Case Link Exists");
        $this->assertContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertContains("Edit NL", $bigResults, "NL Link");

        $obj    = new Meningitis\Meningitis();
        $lab    = new Meningitis\SiteLab();
        $lab->setRlCsfSent(true);
        $lab->setNlCsfSent(true);
        $obj->setSiteLab($lab);

        $bigResults = $action->getBigActions($obj);

        $this->assertContains('fa-list',$bigResults);
        $this->assertContains("Show Meningitis Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit Meningitis Case", $bigResults, "Case Link Exists");
        $this->assertContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertContains("Edit NL", $bigResults, "NL Link");

        $obj        = new RotaVirus();
        $lab        = new RotaVirus\SiteLab();
        $lab->setStoolSentToRRL(new TripleChoice(TripleChoice::YES));
        $lab->setStoolSentToNL(new TripleChoice(TripleChoice::YES));
        $obj->setSiteLab($lab);
        $bigResults = $action->getBigActions($obj);

        $this->assertContains('fa-list',$bigResults);
        $this->assertContains("Show Rota Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit Rota Case", $bigResults, "Case Link Exists");
        $this->assertContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertContains("Edit NL", $bigResults, "NL Link");
    }

    public function testBigActionsNoList()
    {
        $map = [
            ['ROLE_CAN_CREATE', null, true],
            ['ROLE_CAN_CREATE_CASE', null, true],
            ['ROLE_CAN_CREATE_LAB', null, true],
            ['ROLE_CAN_CREATE_RRL_LAB', null, true],
            ['ROLE_CAN_CREATE_NL_LAB', null, true],
        ];

        $this->authChecker->expects($this->any())
            ->method('isGranted')
            ->will($this->returnValueMap($map));

        $action = new CaseActions($this->authChecker, $this->trans, $this->router);
        $obj    = new IBD();
        $lab    = new IBD\SiteLab();
        $lab->setRlCsfSent(true);
        $lab->setNlCsfSent(true);
        $obj->setSiteLab($lab);

        $bigResults = $action->getBigActions($obj,false);
        $this->assertNotContains('fa-list',$bigResults);

        $obj    = new Meningitis\Meningitis();
        $lab    = new Meningitis\SiteLab();
        $lab->setRlCsfSent(true);
        $lab->setNlCsfSent(true);
        $obj->setSiteLab($lab);

        $bigResults = $action->getBigActions($obj,false);
        $this->assertNotContains('fa-list',$bigResults);
    }

    public function testSmallShowOnlyActions()
    {
        $this->authChecker->expects($this->any())
            ->method('isGranted')
            ->with('ROLE_CAN_CREATE')
            ->will($this->returnValue(false));

        $action     = new CaseActions($this->authChecker, $this->trans, $this->router);
        $obj        = new IBD();
        $bigResults = $action->getSmallActions($obj);

        $this->assertContains("Show IBD Case", $bigResults, "User who can't create can only see");
        $this->assertNotContains("Edit IBD Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertNotContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertNotContains("Edit NL", $bigResults, "NL Link");

        $obj        = new Meningitis\Meningitis();
        $bigResults = $action->getSmallActions($obj);

        $this->assertContains("Show Meningitis Case", $bigResults, "User who can't create can only see");
        $this->assertNotContains("Edit Meningitis Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertNotContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertNotContains("Edit NL", $bigResults, "NL Link");

        $obj = new RotaVirus();

        $bigResults = $action->getSmallActions($obj);

        $this->assertContains("Show Rota Case", $bigResults, "User who can't create can only see");
        $this->assertNotContains("Edit Rota Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertNotContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertNotContains("Edit NL", $bigResults, "NL Link");
    }

    public function testSmallCanCreateCaseActions()
    {
        $map = [
            ['ROLE_CAN_CREATE', null, true],
            ['ROLE_CAN_CREATE_CASE', null, true],
        ];

        $this->authChecker->expects($this->any())
            ->method('isGranted')
            ->will($this->returnValueMap($map));

        $action     = new CaseActions($this->authChecker, $this->trans, $this->router);
        $obj        = new IBD();
        $bigResults = $action->getSmallActions($obj);

        $this->assertContains("Show IBD Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit IBD Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertNotContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertNotContains("Edit NL", $bigResults, "NL Link");

        $obj        = new Meningitis\Meningitis();
        $bigResults = $action->getSmallActions($obj);

        $this->assertContains("Show Meningitis Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit Meningitis Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertNotContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertNotContains("Edit NL", $bigResults, "NL Link");

        $obj = new RotaVirus();

        $bigResults = $action->getSmallActions($obj);
        $this->assertContains("Show Rota Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit Rota Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertNotContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertNotContains("Edit NL", $bigResults, "NL Link");
    }

    public function testSmallCanCreateRRLActions()
    {
        $map = [
            ['ROLE_CAN_CREATE', null, true],
            ['ROLE_CAN_CREATE_CASE', null, true],
            ['ROLE_CAN_CREATE_RRL_LAB', null, true],
        ];

        $this->authChecker->expects($this->any())
            ->method('isGranted')
            ->will($this->returnValueMap($map));

        $action = new CaseActions($this->authChecker, $this->trans, $this->router);
        $obj    = new IBD();
        $lab    = new IBD\SiteLab();
        $lab->setRlCsfSent(true);
        $lab->setNlCsfSent(true);
        $obj->setSiteLab($lab);

        $bigResults = $action->getSmallActions($obj);

        $this->assertContains("Show IBD Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit IBD Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertNotContains("Edit NL", $bigResults, "NL Link");

        $obj    = new Meningitis\Meningitis();
        $lab    = new Meningitis\SiteLab();
        $lab->setRlCsfSent(true);
        $lab->setNlCsfSent(true);
        $obj->setSiteLab($lab);

        $bigResults = $action->getSmallActions($obj);

        $this->assertContains("Show Meningitis Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit Meningitis Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertNotContains("Edit NL", $bigResults, "NL Link");

        $obj = new RotaVirus();
        $lab = new RotaVirus\SiteLab();
        $lab->setStoolSentToRRL(new TripleChoice(TripleChoice::YES));
        $lab->setStoolSentToNL(new TripleChoice(TripleChoice::YES));
        $obj->setSiteLab($lab);

        $bigResults = $action->getSmallActions($obj);
        $this->assertContains("Show Rota Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit Rota Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertNotContains("Edit NL", $bigResults, "NL Link");
    }

    public function testSmallCanCreateNLActions()
    {
        $map = [
            ['ROLE_CAN_CREATE', null, true],
            ['ROLE_CAN_CREATE_CASE', null, true],
            ['ROLE_CAN_CREATE_LAB', null, false],
            ['ROLE_CAN_CREATE_RRL_LAB', null, false],
            ['ROLE_CAN_CREATE_NL_LAB', null, true],
        ];

        $this->authChecker->expects($this->any())
            ->method('isGranted')
            ->will($this->returnValueMap($map));

        $action = new CaseActions($this->authChecker, $this->trans, $this->router);
        $obj    = new IBD();
        $lab    = new IBD\SiteLab();
        $lab->setRlCsfSent(true);
        $lab->setNlCsfSent(true);
        $obj->setSiteLab($lab);

        $bigResults = $action->getSmallActions($obj);

        $this->assertContains("Show IBD Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit IBD Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertNotContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertContains("Edit NL", $bigResults, "NL Link");

        $obj    = new Meningitis\Meningitis();
        $lab    = new Meningitis\SiteLab();
        $lab->setRlCsfSent(true);
        $lab->setNlCsfSent(true);
        $obj->setSiteLab($lab);

        $bigResults = $action->getSmallActions($obj);

        $this->assertContains("Show Meningitis Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit Meningitis Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertNotContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertContains("Edit NL", $bigResults, "NL Link");

        $obj = new RotaVirus();
        $lab = new RotaVirus\SiteLab();
        $lab->setStoolSentToRRL(new TripleChoice(TripleChoice::YES));
        $lab->setStoolSentToNL(new TripleChoice(TripleChoice::YES));
        $obj->setSiteLab($lab);

        $bigResults = $action->getSmallActions($obj);

        $this->assertContains("Show Rota Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit Rota Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertNotContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertContains("Edit NL", $bigResults, "NL Link");
    }

    public function testSmallCanCreateAllActions()
    {
        $map = [
            ['ROLE_CAN_CREATE', null, true],
            ['ROLE_CAN_CREATE_CASE', null, true],
            ['ROLE_CAN_CREATE_LAB', null, true],
            ['ROLE_CAN_CREATE_RRL_LAB', null, true],
            ['ROLE_CAN_CREATE_NL_LAB', null, true],
        ];

        $this->authChecker->expects($this->any())
            ->method('isGranted')
            ->will($this->returnValueMap($map));

        $action = new CaseActions($this->authChecker, $this->trans, $this->router);
        $obj    = new IBD();
        $lab    = new IBD\SiteLab();
        $lab->setRlCsfSent(true);
        $lab->setNlCsfSent(true);
        $obj->setSiteLab($lab);

        $bigResults = $action->getSmallActions($obj);

        $this->assertContains("Show IBD Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit IBD Case", $bigResults, "Case Link Exists");
        $this->assertContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertContains("Edit NL", $bigResults, "NL Link");

        $obj    = new Meningitis\Meningitis();
        $lab    = new Meningitis\SiteLab();
        $lab->setRlCsfSent(true);
        $lab->setNlCsfSent(true);
        $obj->setSiteLab($lab);

        $bigResults = $action->getSmallActions($obj);

        $this->assertContains("Show Meningitis Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit Meningitis Case", $bigResults, "Case Link Exists");
        $this->assertContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertContains("Edit NL", $bigResults, "NL Link");

        $obj = new RotaVirus();
        $lab = new RotaVirus\SiteLab();
        $lab->setStoolSentToRRL(new TripleChoice(TripleChoice::YES));
        $lab->setStoolSentToNL(new TripleChoice(TripleChoice::YES));
        $obj->setSiteLab($lab);

        $bigResults = $action->getSmallActions($obj);

        $this->assertContains("Show Rota Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit Rota Case", $bigResults, "Case Link Exists");
        $this->assertContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertContains("Edit NL", $bigResults, "NL Link");
    }
}
