<?php

namespace NS\SentinelBundle\Tests\Twig;

use \NS\SentinelBundle\Twig\CaseActions;
use \NS\SentinelBundle\Entity\IBD;
use \NS\SentinelBundle\Entity\RotaVirus;

use \NS\SentinelBundle\Entity\IBD\SiteLab as IBDSiteLab;
use \NS\SentinelBundle\Entity\RotaVirus\SiteLab as RVSiteLab;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Description of CaseActionsTest
 *
 * @author gnat
 */
class CaseActionsTest extends \PHPUnit_Framework_TestCase
{

    public function testBigShowOnlyActions()
    {
        list($authChecker, $trans, $router) = $this->getMockedObjects();

        $authChecker->expects($this->any())
            ->method('isGranted')
            ->with('ROLE_CAN_CREATE')
            ->will($this->returnValue(false));

        $action     = new CaseActions($authChecker, $trans, $router);
        $obj        = new IBD();
        $bigResults = $action->getBigActions($obj);

        $this->assertContains('fa-list',$bigResults);
        $this->assertContains("Show IBD Case", $bigResults, "User who can't create can only see");
        $this->assertNotContains("Edit IBD Case", $bigResults, "Case Link Exists");
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
        list($authChecker, $trans, $router) = $this->getMockedObjects();

        $map = array(
            array('ROLE_CAN_CREATE', null, true),
            array('ROLE_CAN_CREATE_CASE', null, true),
        );

        $authChecker->expects($this->any())
            ->method('isGranted')
            ->will($this->returnValueMap($map));

        $action     = new CaseActions($authChecker, $trans, $router);
        $obj        = new IBD();
        $bigResults = $action->getBigActions($obj);

        $this->assertContains('fa-list',$bigResults);
        $this->assertContains("Show IBD Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit IBD Case", $bigResults, "Case Link Exists");
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
        list($authChecker, $trans, $router) = $this->getMockedObjects();

        $map = array(
            array('ROLE_CAN_CREATE', null, true),
            array('ROLE_CAN_CREATE_CASE', null, true),
            array('ROLE_CAN_CREATE_RRL_LAB', null, true),
        );

        $authChecker->expects($this->any())
            ->method('isGranted')
            ->will($this->returnValueMap($map));

        $action = new CaseActions($authChecker, $trans, $router);

        $obj = new IBD();
        $lab = new IBDSiteLab();
        $lab->setSentToReferenceLab(true);
        $lab->setSentToNationalLab(true);
        $obj->setSiteLab($lab);

        $bigResults = $action->getBigActions($obj);

        $this->assertContains('fa-list',$bigResults);
        $this->assertContains("Show IBD Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit IBD Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertNotContains("Edit NL", $bigResults, "NL Link");

        $obj = new RotaVirus();
        $lab = new RVSiteLab();
        $lab->setSentToReferenceLab(true);
        $lab->setSentToNationalLab(true);
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
        list($authChecker, $trans, $router) = $this->getMockedObjects();

        $map = array(
            array('ROLE_CAN_CREATE', null, true),
            array('ROLE_CAN_CREATE_CASE', null, true),
            array('ROLE_CAN_CREATE_LAB', null, false),
            array('ROLE_CAN_CREATE_RRL_LAB', null, false),
            array('ROLE_CAN_CREATE_NL_LAB', null, true),
        );

        $authChecker->expects($this->any())
            ->method('isGranted')
            ->will($this->returnValueMap($map));

        $action = new CaseActions($authChecker, $trans, $router);

        $obj = new IBD();
        $lab = new IBDSiteLab();
        $lab->setSentToReferenceLab(true);
        $lab->setSentToNationalLab(true);
        $obj->setSiteLab($lab);

        $bigResults = $action->getBigActions($obj);

        $this->assertContains('fa-list',$bigResults);
        $this->assertContains("Show IBD Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit IBD Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertNotContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertContains("Edit NL", $bigResults, "NL Link");

        $obj = new RotaVirus();
        $lab = new RVSiteLab();
        $lab->setSentToReferenceLab(true);
        $lab->setSentToNationalLab(true);
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
        list($authChecker, $trans, $router) = $this->getMockedObjects();

        $map = array(
            array('ROLE_CAN_CREATE', null, true),
            array('ROLE_CAN_CREATE_CASE', null, true),
            array('ROLE_CAN_CREATE_LAB', null, true),
            array('ROLE_CAN_CREATE_RRL_LAB', null, true),
            array('ROLE_CAN_CREATE_NL_LAB', null, true),
        );

        $authChecker->expects($this->any())
            ->method('isGranted')
            ->will($this->returnValueMap($map));

        $action = new CaseActions($authChecker, $trans, $router);
        $obj    = new IBD();
        $lab    = new IBDSiteLab();
        $lab->setSentToReferenceLab(true);
        $lab->setSentToNationalLab(true);
        $obj->setSiteLab($lab);

        $bigResults = $action->getBigActions($obj);

        $this->assertContains('fa-list',$bigResults);
        $this->assertContains("Show IBD Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit IBD Case", $bigResults, "Case Link Exists");
        $this->assertContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertContains("Edit NL", $bigResults, "NL Link");

        $obj        = new RotaVirus();
        $lab        = new RVSiteLab();
        $lab->setSentToReferenceLab(true);
        $lab->setSentToNationalLab(true);
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
        list($authChecker, $trans, $router) = $this->getMockedObjects();

        $map = array(
            array('ROLE_CAN_CREATE', null, true),
            array('ROLE_CAN_CREATE_CASE', null, true),
            array('ROLE_CAN_CREATE_LAB', null, true),
            array('ROLE_CAN_CREATE_RRL_LAB', null, true),
            array('ROLE_CAN_CREATE_NL_LAB', null, true),
        );

        $authChecker->expects($this->any())
            ->method('isGranted')
            ->will($this->returnValueMap($map));

        $action = new CaseActions($authChecker, $trans, $router);
        $obj    = new IBD();
        $lab    = new IBDSiteLab();
        $lab->setSentToReferenceLab(true);
        $lab->setSentToNationalLab(true);
        $obj->setSiteLab($lab);

        $bigResults = $action->getBigActions($obj,false);
        $this->assertNotContains('fa-list',$bigResults);
    }

    public function testSmallShowOnlyActions()
    {
        list($authChecker, $trans, $router) = $this->getMockedObjects();

        $authChecker->expects($this->any())
            ->method('isGranted')
            ->with('ROLE_CAN_CREATE')
            ->will($this->returnValue(false));


        $action     = new CaseActions($authChecker, $trans, $router);
        $obj        = new IBD();
        $bigResults = $action->getSmallActions($obj);

        $this->assertContains("Show IBD Case", $bigResults, "User who can't create can only see");
        $this->assertNotContains("Edit IBD Case", $bigResults, "Case Link Exists");
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
        list($authChecker, $trans, $router) = $this->getMockedObjects();

        $map = array(
            array('ROLE_CAN_CREATE', null, true),
            array('ROLE_CAN_CREATE_CASE', null, true),
        );

        $authChecker->expects($this->any())
            ->method('isGranted')
            ->will($this->returnValueMap($map));

        $action     = new CaseActions($authChecker, $trans, $router);
        $obj        = new IBD();
        $bigResults = $action->getSmallActions($obj);

        $this->assertContains("Show IBD Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit IBD Case", $bigResults, "Case Link Exists");
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
        list($authChecker, $trans, $router) = $this->getMockedObjects();

        $map = array(
            array('ROLE_CAN_CREATE', null, true),
            array('ROLE_CAN_CREATE_CASE', null, true),
            array('ROLE_CAN_CREATE_RRL_LAB', null, true),
        );

        $authChecker->expects($this->any())
            ->method('isGranted')
            ->will($this->returnValueMap($map));

        $action = new CaseActions($authChecker, $trans, $router);
        $obj    = new IBD();
        $lab    = new IBDSiteLab();
        $lab->setSentToReferenceLab(true);
        $lab->setSentToNationalLab(true);
        $obj->setSiteLab($lab);

        $bigResults = $action->getSmallActions($obj);

        $this->assertContains("Show IBD Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit IBD Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertNotContains("Edit NL", $bigResults, "NL Link");

        $obj = new RotaVirus();
        $lab = new RVSiteLab();
        $lab->setSentToReferenceLab(true);
        $lab->setSentToNationalLab(true);
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
        list($authChecker, $trans, $router) = $this->getMockedObjects();

        $map = array(
            array('ROLE_CAN_CREATE', null, true),
            array('ROLE_CAN_CREATE_CASE', null, true),
            array('ROLE_CAN_CREATE_LAB', null, false),
            array('ROLE_CAN_CREATE_RRL_LAB', null, false),
            array('ROLE_CAN_CREATE_NL_LAB', null, true),
        );

        $authChecker->expects($this->any())
            ->method('isGranted')
            ->will($this->returnValueMap($map));

        $action = new CaseActions($authChecker, $trans, $router);
        $obj    = new IBD();
        $lab    = new IBDSiteLab();
        $lab->setSentToReferenceLab(true);
        $lab->setSentToNationalLab(true);
        $obj->setSiteLab($lab);

        $bigResults = $action->getSmallActions($obj);

        $this->assertContains("Show IBD Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit IBD Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertNotContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertContains("Edit NL", $bigResults, "NL Link");

        $obj = new RotaVirus();
        $lab = new RVSiteLab();
        $lab->setSentToReferenceLab(true);
        $lab->setSentToNationalLab(true);
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
        list($authChecker, $trans, $router) = $this->getMockedObjects();

        $map = array(
            array('ROLE_CAN_CREATE', null, true),
            array('ROLE_CAN_CREATE_CASE', null, true),
            array('ROLE_CAN_CREATE_LAB', null, true),
            array('ROLE_CAN_CREATE_RRL_LAB', null, true),
            array('ROLE_CAN_CREATE_NL_LAB', null, true),
        );

        $authChecker->expects($this->any())
            ->method('isGranted')
            ->will($this->returnValueMap($map));

        $action = new CaseActions($authChecker, $trans, $router);
        $obj    = new IBD();
        $lab    = new IBDSiteLab();
        $lab->setSentToReferenceLab(true);
        $lab->setSentToNationalLab(true);
        $obj->setSiteLab($lab);

        $bigResults = $action->getSmallActions($obj);

        $this->assertContains("Show IBD Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit IBD Case", $bigResults, "Case Link Exists");
        $this->assertContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertContains("Edit NL", $bigResults, "NL Link");

        $obj = new RotaVirus();
        $lab = new RVSiteLab();
        $lab->setSentToReferenceLab(true);
        $lab->setSentToNationalLab(true);
        $obj->setSiteLab($lab);

        $bigResults = $action->getSmallActions($obj);

        $this->assertContains("Show Rota Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit Rota Case", $bigResults, "Case Link Exists");
        $this->assertContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertContains("Edit NL", $bigResults, "NL Link");
    }

    private function getMockedObjects()
    {
        //================================
        // AuthorizationCheckerInterface
        $authChecker = $this->createMock('\Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface');

        //================================
        // Translator
        $trans = $this->createMock('\Symfony\Component\Translation\TranslatorInterface');

        $tmap = array(
            array('EPI', array(), null, null, 'EPI'),
            array('Lab', array(), null, null, 'Lab'),
            array('RRL', array(), null, null, 'RRL'),
            array('NL', array(), null, null, 'NL'),
        );

        $trans->expects($this->any())
            ->method('trans')
            ->will($this->returnValueMap($tmap));

        //================================
        // Router
        $router = $this->createMock('\Symfony\Component\Routing\RouterInterface');

        $rmap = array(
            array('ibdShow', array('id' => null), UrlGeneratorInterface::ABSOLUTE_PATH, 'Show IBD Case'),
            array('ibdEdit', array('id' => null), UrlGeneratorInterface::ABSOLUTE_PATH, 'Edit IBD Case'),
            array('ibdRRLEdit', array('id' => null), UrlGeneratorInterface::ABSOLUTE_PATH, 'Edit RRL'),
            array('ibdNLEdit', array('id' => null), UrlGeneratorInterface::ABSOLUTE_PATH, 'Edit NL'),
            array('ibdLabEdit', array('id' => null), UrlGeneratorInterface::ABSOLUTE_PATH, 'Edit Lab'),
            array('rotavirusShow', array('id' => null), UrlGeneratorInterface::ABSOLUTE_PATH, 'Show Rota Case'),
            array('rotavirusEdit', array('id' => null), UrlGeneratorInterface::ABSOLUTE_PATH, 'Edit Rota Case'),
            array('rotavirusRRLEdit', array('id' => null), UrlGeneratorInterface::ABSOLUTE_PATH, 'Edit RRL'),
            array('rotavirusNLEdit', array('id' => null), UrlGeneratorInterface::ABSOLUTE_PATH, 'Edit NL'),
            array('rotavirusLabEdit', array('id' => null), UrlGeneratorInterface::ABSOLUTE_PATH, 'Edit Lab'),
        );

        $router->expects($this->any())
            ->method('generate')
            ->will($this->returnValueMap($rmap));

        return array($authChecker, $trans, $router);
    }
}
