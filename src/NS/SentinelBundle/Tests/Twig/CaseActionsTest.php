<?php

namespace NS\SentinelBundle\Tests\Twig;

use \NS\SentinelBundle\Twig\CaseActions;
use \NS\SentinelBundle\Entity\Meningitis;
use NS\SentinelBundle\Entity\RotaVirus;

/**
 * Description of CaseActionsTest
 *
 * @author gnat
 */
class CaseActionsTest extends \PHPUnit_Framework_TestCase
{
    public function testBigShowOnlyActions()
    {
        list($sc,$trans,$router) = $this->getMockedObjects();

        $sc->expects($this->any())
           ->method('isGranted')
           ->with('ROLE_CAN_CREATE')
           ->will($this->returnValue(false));
      

        $action     = new CaseActions($sc,$trans,$router);
        $obj        = new Meningitis();
        $bigResults = $action->getBigActions($obj);

        $this->assertContains("Show IBD Case", $bigResults, "User who can't create can only see");
        $this->assertNotContains("Edit IBD Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertNotContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertNotContains("Edit NL", $bigResults, "NL Link");

        $obj        = new RotaVirus();
        $obj->setSentToReferenceLab(true);
        $obj->setSentToNationalLab(true);

        $bigResults = $action->getBigActions($obj);

        $this->assertContains("Show Rota Case", $bigResults, "User who can't create can only see");
        $this->assertNotContains("Edit Rota Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertNotContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertNotContains("Edit NL", $bigResults, "NL Link");
    }

    public function testBigCanCreateCaseActions()
    {
        list($sc,$trans,$router) = $this->getMockedObjects();

        $map = array(
                array('ROLE_CAN_CREATE',null,true),
                array('ROLE_CAN_CREATE_CASE',null,true),
            );

        $sc->expects($this->any())
           ->method('isGranted')
           ->will($this->returnValueMap($map));

        $action     = new CaseActions($sc,$trans,$router);
        $obj        = new Meningitis();
        $bigResults = $action->getBigActions($obj);

        $this->assertContains("Show IBD Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit IBD Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertNotContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertNotContains("Edit NL", $bigResults, "NL Link");

        $obj        = new RotaVirus();
        $obj->setSentToReferenceLab(true);
        $obj->setSentToNationalLab(true);

        $bigResults = $action->getBigActions($obj);
        $this->assertContains("Show Rota Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit Rota Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertNotContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertNotContains("Edit NL", $bigResults, "NL Link");

    }

    public function testBigCanCreateRRLActions()
    {
        list($sc,$trans,$router) = $this->getMockedObjects();

        $map = array(
                array('ROLE_CAN_CREATE',null,true),
                array('ROLE_CAN_CREATE_CASE',null,true),
                array('ROLE_CAN_CREATE_RRL_LAB',null,true),
            );

        $sc->expects($this->any())
           ->method('isGranted')
           ->will($this->returnValueMap($map));

        $action     = new CaseActions($sc,$trans,$router);
        $obj        = new Meningitis();
        $obj->setSentToReferenceLab(true);
        $obj->setSentToNationalLab(true);

        $bigResults = $action->getBigActions($obj);

        $this->assertContains("Show IBD Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit IBD Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertNotContains("Edit NL", $bigResults, "NL Link");

        $obj        = new RotaVirus();
        $obj->setSentToReferenceLab(true);
        $obj->setSentToNationalLab(true);

        $bigResults = $action->getBigActions($obj);
        $this->assertContains("Show Rota Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit Rota Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertNotContains("Edit NL", $bigResults, "NL Link");
    }

    public function testBigCanCreateNLActions()
    {
        list($sc,$trans,$router) = $this->getMockedObjects();

        $map = array(
                array('ROLE_CAN_CREATE',null,true),
                array('ROLE_CAN_CREATE_CASE',null,true),
                array('ROLE_CAN_CREATE_LAB',null,false),
                array('ROLE_CAN_CREATE_RRL_LAB',null,false),
                array('ROLE_CAN_CREATE_NL_LAB',null,true),
            );

        $sc->expects($this->any())
           ->method('isGranted')
           ->will($this->returnValueMap($map));

        $action     = new CaseActions($sc,$trans,$router);
        $obj        = new Meningitis();
        $obj->setSentToReferenceLab(true);
        $obj->setSentToNationalLab(true);

        $bigResults = $action->getBigActions($obj);

        $this->assertContains("Show IBD Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit IBD Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertNotContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertContains("Edit NL", $bigResults, "NL Link");

        $obj        = new RotaVirus();
        $obj->setSentToReferenceLab(true);
        $obj->setSentToNationalLab(true);

        $bigResults = $action->getBigActions($obj);

        $this->assertContains("Show Rota Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit Rota Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertNotContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertContains("Edit NL", $bigResults, "NL Link");
    }

    public function testBigCanCreateAllActions()
    {
        list($sc,$trans,$router) = $this->getMockedObjects();

        $map = array(
                array('ROLE_CAN_CREATE',null,true),
                array('ROLE_CAN_CREATE_CASE',null,true),
                array('ROLE_CAN_CREATE_LAB',null,true),
                array('ROLE_CAN_CREATE_RRL_LAB',null,true),
                array('ROLE_CAN_CREATE_NL_LAB',null,true),
            );

        $sc->expects($this->any())
           ->method('isGranted')
           ->will($this->returnValueMap($map));

        $action     = new CaseActions($sc,$trans,$router);
        $obj        = new Meningitis();
        $obj->setSentToReferenceLab(true);
        $obj->setSentToNationalLab(true);

        $bigResults = $action->getBigActions($obj);

        $this->assertContains("Show IBD Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit IBD Case", $bigResults, "Case Link Exists");
        $this->assertContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertContains("Edit NL", $bigResults, "NL Link");

        $obj = new RotaVirus();
        $obj->setSentToReferenceLab(true);
        $obj->setSentToNationalLab(true);

        $bigResults = $action->getBigActions($obj);

        $this->assertContains("Show Rota Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit Rota Case", $bigResults, "Case Link Exists");
        $this->assertContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertContains("Edit NL", $bigResults, "NL Link");
    }

    public function testSmallShowOnlyActions()
    {
        list($sc,$trans,$router) = $this->getMockedObjects();

        $sc->expects($this->any())
           ->method('isGranted')
           ->with('ROLE_CAN_CREATE')
           ->will($this->returnValue(false));


        $action     = new CaseActions($sc,$trans,$router);
        $obj        = new Meningitis();
        $bigResults = $action->getSmallActions($obj);

        $this->assertContains("Show IBD Case", $bigResults, "User who can't create can only see");
        $this->assertNotContains("Edit IBD Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertNotContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertNotContains("Edit NL", $bigResults, "NL Link");

        $obj        = new RotaVirus();
        $obj->setSentToReferenceLab(true);
        $obj->setSentToNationalLab(true);

        $bigResults = $action->getSmallActions($obj);

        $this->assertContains("Show Rota Case", $bigResults, "User who can't create can only see");
        $this->assertNotContains("Edit Rota Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertNotContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertNotContains("Edit NL", $bigResults, "NL Link");
    }

    public function testSmallCanCreateCaseActions()
    {
        list($sc,$trans,$router) = $this->getMockedObjects();

        $map = array(
                array('ROLE_CAN_CREATE',null,true),
                array('ROLE_CAN_CREATE_CASE',null,true),
            );

        $sc->expects($this->any())
           ->method('isGranted')
           ->will($this->returnValueMap($map));

        $action     = new CaseActions($sc,$trans,$router);
        $obj        = new Meningitis();
        $bigResults = $action->getSmallActions($obj);

        $this->assertContains("Show IBD Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit IBD Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertNotContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertNotContains("Edit NL", $bigResults, "NL Link");

        $obj        = new RotaVirus();
        $obj->setSentToReferenceLab(true);
        $obj->setSentToNationalLab(true);

        $bigResults = $action->getSmallActions($obj);
        $this->assertContains("Show Rota Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit Rota Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertNotContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertNotContains("Edit NL", $bigResults, "NL Link");

    }

    public function testSmallCanCreateRRLActions()
    {
        list($sc,$trans,$router) = $this->getMockedObjects();

        $map = array(
                array('ROLE_CAN_CREATE',null,true),
                array('ROLE_CAN_CREATE_CASE',null,true),
                array('ROLE_CAN_CREATE_RRL_LAB',null,true),
            );

        $sc->expects($this->any())
           ->method('isGranted')
           ->will($this->returnValueMap($map));

        $action     = new CaseActions($sc,$trans,$router);
        $obj        = new Meningitis();
        $obj->setSentToReferenceLab(true);
        $obj->setSentToNationalLab(true);

        $bigResults = $action->getSmallActions($obj);

        $this->assertContains("Show IBD Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit IBD Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertNotContains("Edit NL", $bigResults, "NL Link");

        $obj        = new RotaVirus();
        $obj->setSentToReferenceLab(true);
        $obj->setSentToNationalLab(true);

        $bigResults = $action->getSmallActions($obj);
        $this->assertContains("Show Rota Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit Rota Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertNotContains("Edit NL", $bigResults, "NL Link");
    }

    public function testSmallCanCreateNLActions()
    {
        list($sc,$trans,$router) = $this->getMockedObjects();

        $map = array(
                array('ROLE_CAN_CREATE',null,true),
                array('ROLE_CAN_CREATE_CASE',null,true),
                array('ROLE_CAN_CREATE_LAB',null,false),
                array('ROLE_CAN_CREATE_RRL_LAB',null,false),
                array('ROLE_CAN_CREATE_NL_LAB',null,true),
            );

        $sc->expects($this->any())
           ->method('isGranted')
           ->will($this->returnValueMap($map));

        $action     = new CaseActions($sc,$trans,$router);
        $obj        = new Meningitis();
        $obj->setSentToReferenceLab(true);
        $obj->setSentToNationalLab(true);

        $bigResults = $action->getSmallActions($obj);

        $this->assertContains("Show IBD Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit IBD Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertNotContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertContains("Edit NL", $bigResults, "NL Link");

        $obj        = new RotaVirus();
        $obj->setSentToReferenceLab(true);
        $obj->setSentToNationalLab(true);

        $bigResults = $action->getSmallActions($obj);

        $this->assertContains("Show Rota Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit Rota Case", $bigResults, "Case Link Exists");
        $this->assertNotContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertNotContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertContains("Edit NL", $bigResults, "NL Link");
    }

    public function testSmallCanCreateAllActions()
    {
        list($sc,$trans,$router) = $this->getMockedObjects();

        $map = array(
                array('ROLE_CAN_CREATE',null,true),
                array('ROLE_CAN_CREATE_CASE',null,true),
                array('ROLE_CAN_CREATE_LAB',null,true),
                array('ROLE_CAN_CREATE_RRL_LAB',null,true),
                array('ROLE_CAN_CREATE_NL_LAB',null,true),
            );

        $sc->expects($this->any())
           ->method('isGranted')
           ->will($this->returnValueMap($map));

        $action     = new CaseActions($sc,$trans,$router);
        $obj        = new Meningitis();
        $obj->setSentToReferenceLab(true);
        $obj->setSentToNationalLab(true);

        $bigResults = $action->getSmallActions($obj);

        $this->assertContains("Show IBD Case", $bigResults, "User who can't create can only see");
        $this->assertContains("Edit IBD Case", $bigResults, "Case Link Exists");
        $this->assertContains("Edit Lab", $bigResults, "Lab Link");
        $this->assertContains("Edit RRL", $bigResults, "RRL Link");
        $this->assertContains("Edit NL", $bigResults, "NL Link");

        $obj = new RotaVirus();
        $obj->setSentToReferenceLab(true);
        $obj->setSentToNationalLab(true);

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
        $sc     = $this->getMockBuilder('\Symfony\Component\Security\Core\SecurityContextInterface')
                       ->disableOriginalConstructor()
                       ->getMock();

        //================================
        // Translator
        $trans  = $this->getMockBuilder('\Symfony\Component\Translation\TranslatorInterface')
                       ->disableOriginalConstructor()
                       ->getMock();

        $tmap = array(
                    array('EPI', array(), null, null, 'EPI'),
                    array('Lab', array(), null, null, 'Lab'),
                    array('RRL', array(), null, null, 'RRL'),
                    array('NL',  array(), null, null, 'NL'),
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
                    array('meningitisShow',     array('id'=>null), false, 'Show IBD Case'),
                    array('meningitisEdit',     array('id'=>null), false, 'Edit IBD Case'),
                    array('meningitisRRLEdit',  array('id'=>null), false, 'Edit RRL'),
                    array('meningitisNLEdit',   array('id'=>null), false, 'Edit NL'),
                    array('meningitisLabEdit',  array('id'=>null), false, 'Edit Lab'),
                    array('rotavirusShow',     array('id'=>null), false, 'Show Rota Case'),
                    array('rotavirusEdit',     array('id'=>null), false, 'Edit Rota Case'),
                    array('rotavirusRRLEdit',  array('id'=>null), false, 'Edit RRL'),
                    array('rotavirusNLEdit',   array('id'=>null), false, 'Edit NL'),
                    array('rotavirusLabEdit',  array('id'=>null), false, 'Edit Lab'),
                    );

        $router->expects($this->any())
               ->method('generate')
               ->will($this->returnValueMap($rmap));

        return array($sc,$trans,$router);
    }
}
