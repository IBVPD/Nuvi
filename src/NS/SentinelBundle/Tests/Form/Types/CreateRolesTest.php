<?php

namespace NS\SentinelBundle\Tests\Form\Types;

use \NS\SentinelBundle\Form\Types\CreateRoles;
use \Symfony\Component\Form\Test\TypeTestCase;

/**
 * Description of CreateRolesTest
 *
 * @author gnat
 */
class CreateRolesTest extends TypeTestCase
{
    const CREATE      = 'ROLE_CAN_CREATE';
    const CREATE_CASE = 'ROLE_CAN_CREATE_CASE';
    const CREATE_LAB  = 'ROLE_CAN_CREATE_LAB';
    const CREATE_RRL  = 'ROLE_CAN_CREATE_RRL_LAB';
    const CREATE_NL   = 'ROLE_CAN_CREATE_NL_LAB';

    public function testByRoles()
    {
        foreach ($this->roleProvider() as $data)
        {
            $sc         = $this->getSecurityContext($data['data']);
            $createRole = new CreateRoles();
            $createRole->setSecurityContext($sc);
            $form       = $this->factory->create($createRole);
            $this->assertEquals('CreateRoles', $form->getName());
            $choices    = $form->getConfig()->getOption('choices');
            $this->assertCount($data['count'], $choices);
        }
    }

    public function roleProvider()
    {
        return array(
            array('count' => 2, 'data' => array(
                0 => array('param' => self::CREATE, 'ret' => true),
                1 => array('param' => self::CREATE_CASE, 'ret' => true),
                2 => array('param' => self::CREATE_LAB, 'ret' => true),
                3 => array('param' => self::CREATE_RRL, 'ret' => false),
                4 => array('param' => self::CREATE_NL, 'ret' => false),
            )),
            array('count' => 2, 'data' => array(
                0 => array('param' => self::CREATE, 'ret' => true),
                1 => array('param' => self::CREATE_CASE, 'ret' => true),
                2 => array('param' => self::CREATE_LAB, 'ret' => false),
                3 => array('param' => self::CREATE_RRL, 'ret' => true),
                4 => array('param' => self::CREATE_NL, 'ret' => false),
            )),
            array('count' => 2, 'data'  => array(
                0 => array('param' => self::CREATE, 'ret' => true),
                1 => array('param' => self::CREATE_CASE, 'ret' => true),
                2 => array('param' => self::CREATE_LAB, 'ret' => false),
                3 => array('param' => self::CREATE_RRL, 'ret' => false),
                4 => array('param' => self::CREATE_NL, 'ret' => true),
            )),
            array('count' => 4, 'data'  => array(
                0 => array('param' => self::CREATE, 'ret' => true),
                1 => array('param' => self::CREATE_CASE, 'ret' => true),
                2 => array('param' => self::CREATE_LAB, 'ret' => true),
                3 => array('param' => self::CREATE_RRL, 'ret' => true),
                4 => array('param' => self::CREATE_NL, 'ret' => true),
            )),
            array('count' => 2, 'data' => array(
                0 => array('param' => self::CREATE, 'ret' => true),
                1 => array('param' => self::CREATE_CASE, 'ret' => false),
                2 => array('param' => self::CREATE_LAB, 'ret' => false),
                3 => array('param' => self::CREATE_RRL, 'ret' => true),
                4 => array('param' => self::CREATE_NL, 'ret' => true),
            )),
            array('count' => 2, 'data' => array(
                0 => array('param' => self::CREATE, 'ret' => true),
                1 => array('param' => self::CREATE_CASE, 'ret' => false),
                2 => array('param' => self::CREATE_LAB, 'ret' => true),
                3 => array('param' => self::CREATE_RRL, 'ret' => true),
                4 => array('param' => self::CREATE_NL, 'ret' => false),
            )),
            array('count' => 3, 'data'  => array(
                0 => array('param' => self::CREATE, 'ret' => true),
                1 => array('param' => self::CREATE_CASE, 'ret' => false),
                2 => array('param' => self::CREATE_LAB, 'ret' => true),
                3 => array('param' => self::CREATE_RRL, 'ret' => true),
                4 => array('param' => self::CREATE_NL, 'ret' => true),
            )),
        );
    }

    public function getSecurityContext(array $calls = array())
    {
        $sc = $this->getMockBuilder('\Symfony\Component\Security\Core\SecurityContextInterface')->disableOriginalConstructor()->getMock();
        foreach ($calls as $index => $call)
        {
            $sc->expects($this->at($index))
                ->method('isGranted')
                ->with($call['param'])
                ->willReturn($call['ret']);
        }

        return $sc;
    }
}
