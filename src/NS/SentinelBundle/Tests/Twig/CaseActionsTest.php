<?php

namespace NS\SentinelBundle\Tests\Twig;

use \NS\SentinelBundle\Twig\CaseActions;
use \NS\SentinelBundle\Entity\IBD;
use \NS\SentinelBundle\Entity\RotaVirus;

/**
 * Description of CaseActionsTest
 *
 * @author gnat
 */
class CaseActionsTest extends \PHPUnit_Framework_TestCase
{

    public function testBigShowOnlyActions()
    {
        list($securityContext, $trans, $router) = $this->getMockedObjects();

        $securityContext->expects($this->any())
            ->method('isGranted')
            ->with('ROLE_CAN_CREATE')
            ->will($this->returnValue(false));

        $action     = new CaseActions($securityContext, $trans, $router);
        $obj        = new IBD();
        $bigResults = $action->getBigActions($obj);

        $this->assertContains("Show IBD Case", $bigResults, "User who can't create can only see");
        $this->assertNotContains("Edit IBD Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertNotContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertNotContains("Edit NL", $bigResults, "NL Link");

        $obj        = new RotaVirus();
        $bigResults = $action->getBigActions($obj);

        $this->assertContains("Show Rota Case", $bigResults, "User who can't create can only see");
        $this->assertNotContains("Edit Rota Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertNotContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertNotContains("Edit NL", $bigResults, "NL Link");
    }

    public function testBigCanCreateCaseActions()
    {
        list($securityContext, $trans, $router) = $this->getMockedObjects();

        $map = array(
            array('ROLE_CAN_CREATE', null, true),
            array('ROLE_CAN_CREATE_CASE', null, true),
        );

        $securityContext->expects($this->any())
            ->method('isGranted')
            ->will($this->returnValueMap($map));

        $action     = new CaseActions($securityContext, $trans, $router);
        $obj        = new IBD();
        $bigResults = $action->getBigActions($obj);

        $this->assertContains("Show IBD Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit IBD Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertNotContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertNotContains("Edit NL", $bigResults, "NL Link");

        $obj        = new RotaVirus();
        $bigResults = $action->getBigActions($obj);
        $this->assertContains("Show Rota Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit Rota Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertNotContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertNotContains("Edit NL", $bigResults, "NL Link");
    }

    public function testBigCanCreateRRLActions()
    {
        list($securityContext, $trans, $router) = $this->getMockedObjects();

        $map = array(
            array('ROLE_CAN_CREATE', null, true),
            array('ROLE_CAN_CREATE_CASE', null, true),
            array('ROLE_CAN_CREATE_RRL_LAB', null, true),
        );

        $securityContext->expects($this->any())
            ->method('isGranted')
            ->will($this->returnValueMap($map));

        $action = new CaseActions($securityContext, $trans, $router);

        $obj = new IBD();
        $lab = new \NS\SentinelBundle\Entity\IBD\SiteLab();
        $lab->setSentToReferenceLab(true);
        $lab->setSentToNationalLab(true);
        $obj->setSiteLab($lab);

        $bigResults = $action->getBigActions($obj);

        $this->assertContains("Show IBD Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit IBD Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertNotContains("Edit NL", $bigResults, "NL Link");

        $obj = new RotaVirus();
        $lab = new \NS\SentinelBundle\Entity\Rota\SiteLab();
        $lab->setSentToReferenceLab(true);
        $lab->setSentToNationalLab(true);
        $obj->setSiteLab($lab);

        $bigResults = $action->getBigActions($obj);
        $this->assertContains("Show Rota Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit Rota Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertNotContains("Edit NL", $bigResults, "NL Link");
    }

    public function testBigCanCreateNLActions()
    {
        list($securityContext, $trans, $router) = $this->getMockedObjects();

        $map = array(
            array('ROLE_CAN_CREATE', null, true),
            array('ROLE_CAN_CREATE_CASE', null, true),
            array('ROLE_CAN_CREATE_LAB', null, false),
            array('ROLE_CAN_CREATE_RRL_LAB', null, false),
            array('ROLE_CAN_CREATE_NL_LAB', null, true),
        );

        $securityContext->expects($this->any())
            ->method('isGranted')
            ->will($this->returnValueMap($map));

        $action = new CaseActions($securityContext, $trans, $router);

        $obj = new IBD();
        $lab = new \NS\SentinelBundle\Entity\IBD\SiteLab();
        $lab->setSentToReferenceLab(true);
        $lab->setSentToNationalLab(true);
        $obj->setSiteLab($lab);

        $bigResults = $action->getBigActions($obj);

        $this->assertContains("Show IBD Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit IBD Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertNotContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertContains("Edit NL", $bigResults, "NL Link");

        $obj = new RotaVirus();
        $lab = new \NS\SentinelBundle\Entity\Rota\SiteLab();
        $lab->setSentToReferenceLab(true);
        $lab->setSentToNationalLab(true);
        $obj->setSiteLab($lab);

        $bigResults = $action->getBigActions($obj);

        $this->assertContains("Show Rota Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit Rota Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertNotContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertContains("Edit NL", $bigResults, "NL Link");
    }

    public function testBigCanCreateAllActions()
    {
        list($securityContext, $trans, $router) = $this->getMockedObjects();

        $map = array(
            array('ROLE_CAN_CREATE', null, true),
            array('ROLE_CAN_CREATE_CASE', null, true),
            array('ROLE_CAN_CREATE_LAB', null, true),
            array('ROLE_CAN_CREATE_RRL_LAB', null, true),
            array('ROLE_CAN_CREATE_NL_LAB', null, true),
        );

        $securityContext->expects($this->any())
            ->method('isGranted')
            ->will($this->returnValueMap($map));

        $action = new CaseActions($securityContext, $trans, $router);
        $obj    = new IBD();
        $lab    = new \NS\SentinelBundle\Entity\IBD\SiteLab();
        $lab->setSentToReferenceLab(true);
        $lab->setSentToNationalLab(true);
        $obj->setSiteLab($lab);

        $bigResults = $action->getBigActions($obj);

        $this->assertContains("Show IBD Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit IBD Case", $bigResults, "Case Link Exists");
        $this->assertContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertContains("Edit NL", $bigResults, "NL Link");

        $obj        = new RotaVirus();
        $lab        = new \NS\SentinelBundle\Entity\Rota\SiteLab();
        $lab->setSentToReferenceLab(true);
        $lab->setSentToNationalLab(true);
        $obj->setSiteLab($lab);
        $bigResults = $action->getBigActions($obj);

        $this->assertContains("Show Rota Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit Rota Case", $bigResults, "Case Link Exists");
        $this->assertContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertContains("Edit NL", $bigResults, "NL Link");
    }

    public function testSmallShowOnlyActions()
    {
        list($securityContext, $trans, $router) = $this->getMockedObjects();

        $securityContext->expects($this->any())
            ->method('isGranted')
            ->with('ROLE_CAN_CREATE')
            ->will($this->returnValue(false));


        $action     = new CaseActions($securityContext, $trans, $router);
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
        list($securityContext, $trans, $router) = $this->getMockedObjects();

        $map = array(
            array('ROLE_CAN_CREATE', null, true),
            array('ROLE_CAN_CREATE_CASE', null, true),
        );

        $securityContext->expects($this->any())
            ->method('isGranted')
            ->will($this->returnValueMap($map));

        $action     = new CaseActions($securityContext, $trans, $router);
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
        list($securityContext, $trans, $router) = $this->getMockedObjects();

        $map = array(
            array('ROLE_CAN_CREATE', null, true),
            array('ROLE_CAN_CREATE_CASE', null, true),
            array('ROLE_CAN_CREATE_RRL_LAB', null, true),
        );

        $securityContext->expects($this->any())
            ->method('isGranted')
            ->will($this->returnValueMap($map));

        $action = new CaseActions($securityContext, $trans, $router);
        $obj    = new IBD();
        $lab    = new \NS\SentinelBundle\Entity\IBD\SiteLab();
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
        $lab = new \NS\SentinelBundle\Entity\Rota\SiteLab();
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
        list($securityContext, $trans, $router) = $this->getMockedObjects();

        $map = array(
            array('ROLE_CAN_CREATE', null, true),
            array('ROLE_CAN_CREATE_CASE', null, true),
            array('ROLE_CAN_CREATE_LAB', null, false),
            array('ROLE_CAN_CREATE_RRL_LAB', null, false),
            array('ROLE_CAN_CREATE_NL_LAB', null, true),
        );

        $securityContext->expects($this->any())
            ->method('isGranted')
            ->will($this->returnValueMap($map));

        $action = new CaseActions($securityContext, $trans, $router);
        $obj    = new IBD();
        $lab    = new \NS\SentinelBundle\Entity\IBD\SiteLab();
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
        $lab = new \NS\SentinelBundle\Entity\Rota\SiteLab();
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
        list($securityContext, $trans, $router) = $this->getMockedObjects();

        $map = array(
            array('ROLE_CAN_CREATE', null, true),
            array('ROLE_CAN_CREATE_CASE', null, true),
            array('ROLE_CAN_CREATE_LAB', null, true),
            array('ROLE_CAN_CREATE_RRL_LAB', null, true),
            array('ROLE_CAN_CREATE_NL_LAB', null, true),
        );

        $securityContext->expects($this->any())
            ->method('isGranted')
            ->will($this->returnValueMap($map));

        $action = new CaseActions($securityContext, $trans, $router);
        $obj    = new IBD();
        $lab    = new \NS\SentinelBundle\Entity\IBD\SiteLab();
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
        $lab = new \NS\SentinelBundle\Entity\Rota\SiteLab();
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
        // SecurityContext
        $securityContext = $this->getMockBuilder('\Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        //================================
        // Translator
        $trans = $this->getMockBuilder('\Symfony\Component\Translation\TranslatorInterface')
            ->disableOriginalConstructor()
            ->getMock();

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
        $router = $this->getMockBuilder('\Symfony\Component\Routing\RouterInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $rmap = array(
            array('ibdShow', array('id' => null), false, 'Show IBD Case'),
            array('ibdEdit', array('id' => null), false, 'Edit IBD Case'),
            array('ibdRRLEdit', array('id' => null), false, 'Edit RRL'),
            array('ibdNLEdit', array('id' => null), false, 'Edit NL'),
            array('ibdLabEdit', array('id' => null), false, 'Edit Lab'),
            array('rotavirusShow', array('id' => null), false, 'Show Rota Case'),
            array('rotavirusEdit', array('id' => null), false, 'Edit Rota Case'),
            array('rotavirusRRLEdit', array('id' => null), false, 'Edit RRL'),
            array('rotavirusNLEdit', array('id' => null), false, 'Edit NL'),
            array('rotavirusLabEdit', array('id' => null), false, 'Edit Lab'),
        );

        $router->expects($this->any())
            ->method('generate')
            ->will($this->returnValueMap($rmap));

        return array($securityContext, $trans, $router);
    }

}
